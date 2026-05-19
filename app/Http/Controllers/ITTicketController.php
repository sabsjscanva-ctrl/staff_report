<?php

namespace App\Http\Controllers;

use App\Models\ITTicket;
use App\Models\ITTicketReply;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ITTicketController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();
        
        if ($user->canAccessIT()) {
            $tickets = ITTicket::with(['staff', 'itStaff'])->latest()->get();
        } else {
            $tickets = ITTicket::with(['itStaff'])->where('staff_id', $user->id)->latest()->get();
        }

        return view('ITTicket.Index', compact('tickets'));
    }

    public function report(Request $request)
    {
        $user = Auth::user();
        if (!$user->canAccessIT() && !$user->is_admin && !$user->is_manager) {
            abort(403);
        }

        $query = ITTicket::with(['staff', 'itStaff']);

        if ($request->has('start_date') && $request->start_date) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->has('end_date') && $request->end_date) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        if (!$request->has('start_date') && !$request->has('end_date')) {
            $query->whereDate('created_at', now());
        }

        if ($request->has('staff_id') && $request->staff_id) {
            $query->where('staff_id', $request->staff_id);
        }

        $tickets = $query->latest()->get();
        $allStaff = \App\Models\User::all(); // Simplified for now

        return view('ITTicket.Report', compact('tickets', 'allStaff'));
    }

    public function exportReport(Request $request)
    {
        $user = Auth::user();
        if (!$user->canAccessIT() && !$user->is_admin && !$user->is_manager) {
            abort(403);
        }

        $type = $request->get('type', 'excel');
        $query = ITTicket::with(['staff', 'itStaff']);

        if ($request->has('start_date') && $request->start_date) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->has('end_date') && $request->end_date) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        if ($request->has('staff_id') && $request->staff_id) {
            $query->where('staff_id', $request->staff_id);
        }

        $tickets = $query->latest()->get();

        if ($tickets->isEmpty()) {
            return back()->with('error', 'No data found to export.');
        }

        if ($type === 'pdf') {
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('ITTicket.ReportPdf', compact('tickets'));
            return $pdf->download('IT_Support_Report_' . now()->format('YmdHis') . '.pdf');
        }

        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\ITTicketExport($tickets), 'IT_Support_Report_' . now()->format('YmdHis') . '.xlsx');
    }

    public function create()
    {
        return view('ITTicket.Create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'category'          => 'required|in:Hardware,Software',
            'subject'           => 'required|string|max:255',
            'issue_description' => 'required|string',
            'photos'            => 'nullable|array|max:5',
            'photos.*'          => 'nullable|image|max:5120', // 5MB max each
        ]);

        $photoPaths = [];
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $photoPaths[] = $photo->store('it_tickets', 'public');
            }
        }

        $ticket = ITTicket::create([
            'staff_id'          => Auth::id(),
            'category'          => $request->category,
            'subject'           => $request->subject,
            'issue_description' => $request->issue_description,
            'photos'            => $photoPaths,
            'status'            => 'Pending',
        ]);

        // Notify IT department users
        $itUsers = \App\Models\User::all()->filter(function($u) {
            return $u->canAccessIT() && $u->id !== Auth::id();
        });

        foreach ($itUsers as $user) {
            \App\Models\Notification::create([
                'user_id' => $user->id,
                'title'   => 'New IT Ticket Raised',
                'message' => Auth::user()->name . ' has raised a ticket: "' . $ticket->subject . '"',
                'url'     => route('it-tickets.show', $ticket->id),
                'type'    => 'it_ticket_created',
            ]);
        }

        return redirect()->route('it-tickets.index')->with('success', 'Ticket successfully raise kar diya gaya hai!');
    }

    public function show(ITTicket $itTicket)
    {
        $user = Auth::user();

        // Authorization
        if (!$user->canAccessIT() && $itTicket->staff_id !== $user->id) {
            abort(403);
        }

        $itTicket->load(['staff', 'itStaff', 'replies.user']);

        return view('ITTicket.Show', compact('itTicket'));
    }

    public function reply(Request $request, ITTicket $itTicket)
    {
        $user = Auth::user();

        // Authorization
        if (!$user->canAccessIT() && $itTicket->staff_id !== $user->id) {
            abort(403);
        }

        $request->validate([
            'message'    => 'required|string',
            'attachment' => 'nullable|file|max:5120',
        ]);

        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $attachmentPath = $request->file('attachment')->store('it_tickets/replies', 'public');
        }

        if ($itTicket->status === 'Completed') {
            return back()->with('error', 'Ticket complete ho gaya hai, ab aap chat nahi kar sakte.');
        }

        $itTicket->replies()->create([
            'user_id'    => $user->id,
            'message'    => $request->message,
            'attachment' => $attachmentPath,
        ]);

        // Send notifications for the reply
        if ($itTicket->staff_id === $user->id) {
            // Staff replied: Notify assigned IT staff, or all IT staff if none is assigned yet
            $recipients = collect();
            if ($itTicket->it_staff_id) {
                if ($itTicket->it_staff_id !== $user->id) {
                    $recipients->push(\App\Models\User::find($itTicket->it_staff_id));
                }
            } else {
                $itUsers = \App\Models\User::all()->filter(function($u) use ($user) {
                    return $u->canAccessIT() && $u->id !== $user->id;
                });
                $recipients = $recipients->concat($itUsers);
            }

            foreach ($recipients as $recipient) {
                if ($recipient) {
                    \App\Models\Notification::create([
                        'user_id' => $recipient->id,
                        'title'   => 'New Ticket Message',
                        'message' => $user->name . ' replied: "' . \Illuminate\Support\Str::limit($request->message, 80) . '"',
                        'url'     => route('it-tickets.show', $itTicket->id),
                        'type'    => 'it_ticket_reply',
                    ]);
                }
            }
        } else {
            // IT staff/admin replied: Notify the staff member who created the ticket
            if ($itTicket->staff_id !== $user->id) {
                \App\Models\Notification::create([
                    'user_id' => $itTicket->staff_id,
                    'title'   => 'New Message on Your Ticket',
                    'message' => $user->name . ' replied: "' . \Illuminate\Support\Str::limit($request->message, 80) . '"',
                    'url'     => route('it-tickets.show', $itTicket->id),
                    'type'    => 'it_ticket_reply',
                ]);
            }
        }

        return back()->with('success', 'Reply bhej di gayi hai!');
    }

    public function updateStatus(Request $request, ITTicket $itTicket)
    {
        if (!Auth::user()->canAccessIT()) {
            abort(403);
        }

        $request->validate([
            'status'  => 'required|in:Pending,In Progress,Completed,Paused',
            'remarks' => 'nullable|string',
        ]);

        $oldStatus = $itTicket->status;
        $newStatus = $request->status;
        $now = now();

        $data = [
            'status'      => $newStatus,
            'remarks'     => $request->remarks,
            'it_staff_id' => Auth::id(),
        ];

        // Time Tracking Logic
        if ($newStatus === 'Completed') {
            $data['completed_at'] = $now;
            
            if ($oldStatus === 'In Progress') {
                // Calculate time from last "Start"
                $seconds = $now->diffInSeconds($itTicket->last_status_change_at ?? $itTicket->created_at);
                $data['total_seconds_spent'] = $itTicket->total_seconds_spent + $seconds;
            } elseif (!$itTicket->started_at || $itTicket->total_seconds_spent == 0) {
                // Fallback: If never formally started, calculate from creation to completion
                $data['started_at'] = $itTicket->started_at ?? $itTicket->created_at;
                $data['total_seconds_spent'] = $now->diffInSeconds($data['started_at']);
            }
        } elseif ($newStatus === 'In Progress') {
            $data['last_status_change_at'] = $now;
            if (!$itTicket->started_at) {
                $data['started_at'] = $now;
            }
        } elseif ($newStatus === 'Paused' && $oldStatus === 'In Progress') {
            // Calculate time spent in the last session before pausing
            $seconds = $now->diffInSeconds($itTicket->last_status_change_at ?? $itTicket->created_at);
            $data['total_seconds_spent'] = $itTicket->total_seconds_spent + $seconds;
        }

        $itTicket->update($data);

        // Notify staff member about status change
        if ($itTicket->staff_id !== Auth::id()) {
            \App\Models\Notification::create([
                'user_id' => $itTicket->staff_id,
                'title'   => 'Ticket Status Updated',
                'message' => 'Your ticket status has been changed to "' . $newStatus . '" by ' . Auth::user()->name,
                'url'     => route('it-tickets.show', $itTicket->id),
                'type'    => 'it_ticket_status',
            ]);
        }

        return back()->with('success', 'Ticket status update kar diya gaya hai!');
    }

    public function assignTime(Request $request, ITTicket $itTicket)
    {
        if (!Auth::user()->canAccessIT()) {
            abort(403);
        }

        $request->validate([
            'expected_arrival_time' => 'required|date|after:now',
        ]);

        $itTicket->update([
            'expected_arrival_time' => $request->expected_arrival_time,
            'it_staff_id'           => Auth::id(),
        ]);

        // Notify staff member about expected arrival time
        if ($itTicket->staff_id !== Auth::id()) {
            \App\Models\Notification::create([
                'user_id' => $itTicket->staff_id,
                'title'   => 'IT Support Scheduled',
                'message' => 'IT support arrival time is set to: ' . \Carbon\Carbon::parse($request->expected_arrival_time)->format('d M Y, h:i A'),
                'url'     => route('it-tickets.show', $itTicket->id),
                'type'    => 'it_ticket_time',
            ]);
        }

        return back()->with('success', 'Time assign kar diya gaya hai!');
    }
}
