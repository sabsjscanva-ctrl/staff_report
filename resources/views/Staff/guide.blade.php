@extends('layouts.app')
@section('title', 'Staff User Guide')

@section('content')
<div class="mb-8">
    <h2 class="text-3xl font-black text-slate-800 tracking-tight">Staff User Guide</h2>
    <p class="text-slate-500 text-sm mt-1 font-medium">Portal use karne ka step-by-step tarika aur naye features ki jankari.</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    {{-- Step 1: Login --}}
    <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm hover:shadow-md transition">
        <div class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center font-black text-xl mb-5 border border-indigo-100">1</div>
        <h3 class="text-lg font-bold text-slate-800 mb-3">Login Kaise Karein?</h3>
        <ul class="text-sm text-slate-600 space-y-3">
            <li class="flex gap-2 items-start"><svg class="w-5 h-5 text-indigo-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg> <span>Apne browser mein portal open karein.</span></li>
            <li class="flex gap-2 items-start"><svg class="w-5 h-5 text-indigo-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg> <span>Apna <strong>Registered Email ID</strong> enter karein.</span></li>
            <li class="flex gap-2 items-start"><svg class="w-5 h-5 text-indigo-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg> <span>Password mein apna <strong>Registered Mobile Number</strong> dalein.</span></li>
            <li class="flex gap-2 items-start"><svg class="w-5 h-5 text-indigo-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg> <span><strong>Login</strong> button par click karein.</span></li>
        </ul>
    </div>

    {{-- Step 2: Daily Report (Live Tracking) --}}
    <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm hover:shadow-md transition">
        <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center font-black text-xl mb-5 border border-emerald-100">2</div>
        <h3 class="text-lg font-bold text-slate-800 mb-3">Apna Kaam Track Kaise Karein?</h3>
        <ul class="text-sm text-slate-600 space-y-3">
            <li class="flex gap-2 items-start"><svg class="w-5 h-5 text-emerald-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg> <span>Menu se <strong>"Track My Task"</strong> par jayein.</span></li>
            <li class="flex gap-2 items-start"><svg class="w-5 h-5 text-emerald-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" /></svg> <span>Naya kaam shuru karne ke liye Task ka naam likhein aur <strong>"Start New Task"</strong> dabayein.</span></li>
            <li class="flex gap-2 items-start"><svg class="w-5 h-5 text-emerald-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg> <span>Kaam ko rokna ho toh <strong>"Pause"</strong> aur dobara shuru karne ke liye <strong>"Resume"</strong> karein.</span></li>
            <li class="flex gap-2 items-start"><svg class="w-5 h-5 text-emerald-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg> <span>Kaam poora hone par <strong>"End Task"</strong> dabayein.</span></li>
        </ul>
    </div>

    {{-- Step 3: Log Update Feature --}}
    <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm hover:shadow-md transition">
        <div class="w-12 h-12 bg-sky-50 text-sky-600 rounded-2xl flex items-center justify-center font-black text-xl mb-5 border border-sky-100">3</div>
        <h3 class="text-lg font-bold text-slate-800 mb-3">Update Kaise Log Karein?</h3>
        <p class="text-xs text-slate-500 mb-3 font-medium">Ab aap kaam karte hue bina pause kiye updates add kar sakte hain:</p>
        <ul class="text-sm text-slate-600 space-y-3">
            <li class="flex gap-2 items-start"><svg class="w-5 h-5 text-sky-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg> <span>Active task ke <strong>"Add New Update"</strong> box mein apna kaam describe karein.</span></li>
            <li class="flex gap-2 items-start"><svg class="w-5 h-5 text-sky-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg> <span><strong>"Log Update"</strong> button par click karein.</span></li>
            <li class="flex gap-2 items-start"><svg class="w-5 h-5 text-sky-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg> <span>Aapki description Time aur Date ke sath save ho jayegi. Aap isko din mein multiple times use kar sakte hain.</span></li>
        </ul>
    </div>

    {{-- Step 4: Quick Add: Other Task --}}
    <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm hover:shadow-md transition">
        <div class="w-12 h-12 bg-fuchsia-50 text-fuchsia-600 rounded-2xl flex items-center justify-center font-black text-xl mb-5 border border-fuchsia-100">4</div>
        <h3 class="text-lg font-bold text-slate-800 mb-3">Quick Add: Other Tasks</h3>
        <p class="text-xs text-slate-500 mb-3 font-medium">Bina time track kiye direct chote kaam add karne ke liye:</p>
        <ul class="text-sm text-slate-600 space-y-3">
            <li class="flex gap-2 items-start"><svg class="w-5 h-5 text-fuchsia-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg> <span>Dashboard par <strong>"Quick Add: Other Task"</strong> button par click karein.</span></li>
            <li class="flex gap-2 items-start"><svg class="w-5 h-5 text-fuchsia-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg> <span>Popup mein apne task ki detail ya description likhein.</span></li>
            <li class="flex gap-2 items-start"><svg class="w-5 h-5 text-fuchsia-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg> <span>Isme time nahi lagega, seedha aaj ki date mein aapka kaam save ho jayega.</span></li>
        </ul>
    </div>

    {{-- Step 5: IT Support Ticket --}}
    <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm hover:shadow-md transition">
        <div class="w-12 h-12 bg-rose-50 text-rose-600 rounded-2xl flex items-center justify-center font-black text-xl mb-5 border border-rose-100">5</div>
        <h3 class="text-lg font-bold text-slate-800 mb-3">IT Support (Helpdesk)</h3>
        <p class="text-xs text-slate-500 mb-3 font-medium">System ya Hardware mein koi problem aane par yahan report karein:</p>
        <ul class="text-sm text-slate-600 space-y-3">
            <li class="flex gap-2 items-start"><svg class="w-5 h-5 text-rose-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z" /></svg> <span>Top Menu se <strong>"IT Support"</strong> section mein jayein.</span></li>
            <li class="flex gap-2 items-start"><svg class="w-5 h-5 text-rose-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg> <span><strong>"Create New Ticket"</strong> par click karein.</span></li>
            <li class="flex gap-2 items-start"><svg class="w-5 h-5 text-rose-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg> <span>Problem detail aur evidence ki <strong>Photo</strong> upload karke submit karein.</span></li>
            <li class="flex gap-2 items-start"><svg class="w-5 h-5 text-rose-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" /></svg> <span>IT Team aapki problem jaldi solve karegi aur aap status track kar sakenge.</span></li>
        </ul>
    </div>

    {{-- Step 6: Profile Update --}}
    <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm hover:shadow-md transition">
        <div class="w-12 h-12 bg-orange-50 text-orange-600 rounded-2xl flex items-center justify-center font-black text-xl mb-5 border border-orange-100">6</div>
        <h3 class="text-lg font-bold text-slate-800 mb-3">Profile Update Kaise Karein?</h3>
        <ul class="text-sm text-slate-600 space-y-3">
            <li class="flex gap-2 items-start"><svg class="w-5 h-5 text-orange-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg> <span>Dashboard par <strong>"Update Profile"</strong> button dabayein.</span></li>
            <li class="flex gap-2 items-start"><svg class="w-5 h-5 text-orange-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg> <span>Apni details (Naam, DOB, Mobile, Address) likhein ya nayi Photo lagayein.</span></li>
            <li class="flex gap-2 items-start"><svg class="w-5 h-5 text-orange-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg> <span><strong>Submit Request</strong> dabayein. Manager ke approve hone par profile update ho jayegi.</span></li>
        </ul>
    </div>

    {{-- Step 7: Change Password --}}
    <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm hover:shadow-md transition">
        <div class="w-12 h-12 bg-purple-50 text-purple-600 rounded-2xl flex items-center justify-center font-black text-xl mb-5 border border-purple-100">7</div>
        <h3 class="text-lg font-bold text-slate-800 mb-3">Password Kaise Badlein?</h3>
        <ul class="text-sm text-slate-600 space-y-3">
            <li class="flex gap-2 items-start"><svg class="w-5 h-5 text-purple-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" /></svg> <span>Upar apne naam par click karein aur <strong>"Change Password"</strong> chunein.</span></li>
            <li class="flex gap-2 items-start"><svg class="w-5 h-5 text-purple-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg> <span>Apna Purana Password aur Naya Password likhein.</span></li>
            <li class="flex gap-2 items-start"><svg class="w-5 h-5 text-purple-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg> <span><strong>Save</strong> karein taki aapka account secure rahe.</span></li>
        </ul>
    </div>
</div>

<div class="mt-10 bg-gradient-to-r from-indigo-600 to-indigo-800 p-10 rounded-3xl text-white shadow-xl shadow-indigo-200 text-center relative overflow-hidden">
    <div class="relative z-10">
        <h3 class="text-2xl font-black mb-3">Daily Reminders & Tips</h3>
        <p class="text-indigo-100 text-sm md:text-base max-w-2xl mx-auto font-medium leading-relaxed">
            Apna kaam shuru karte hi tracker ON karein. Kaam se uthte waqt usay Pause karna na bhoolein. 
            Agar network chala jaye toh ghabrayein mat, system sab secure rakhega.
        </p>
        <a href="{{ route('staff.dashboard') }}" class="inline-flex items-center gap-2 mt-6 px-8 py-3 bg-white text-indigo-700 font-bold rounded-2xl shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg>
            Dashboard Par Wapis Jayein
        </a>
    </div>
    
    <!-- Background Decor -->
    <div class="absolute top-0 left-0 w-full h-full overflow-hidden opacity-10 pointer-events-none">
        <svg class="absolute -top-24 -left-24 w-64 h-64 text-white" fill="currentColor" viewBox="0 0 100 100"><circle cx="50" cy="50" r="50"/></svg>
        <svg class="absolute -bottom-32 -right-32 w-96 h-96 text-white" fill="currentColor" viewBox="0 0 100 100"><circle cx="50" cy="50" r="50"/></svg>
    </div>
</div>
@endsection
