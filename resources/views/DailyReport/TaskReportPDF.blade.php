<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Task Report</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; color: #333; line-height: 1.5; font-size: 14px; margin: 0; padding: 20px; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #eee; padding-bottom: 20px; }
        .header h1 { margin: 0; font-size: 24px; color: #1e293b; }
        .summary-box { background-color: #f8fafc; border: 1px solid #e2e8f0; padding: 15px; border-radius: 8px; margin-bottom: 30px; }
        .summary-box table { width: 100%; }
        .summary-box td { padding: 5px 0; }
        .summary-box strong { color: #475569; display: inline-block; width: 120px; }
        .timeline { width: 100%; border-collapse: collapse; }
        .timeline th, .timeline td { border: 1px solid #cbd5e1; padding: 12px; text-align: left; vertical-align: top; }
        .timeline th { background-color: #f1f5f9; color: #334155; font-weight: bold; }
        .timeline tr:nth-child(even) { background-color: #f8fafc; }
        .status { font-size: 11px; text-transform: uppercase; padding: 3px 6px; border-radius: 4px; display: inline-block; font-weight: bold; }
        .status-completed { background-color: #d1fae5; color: #047857; }
        .status-paused { background-color: #fef3c7; color: #b45309; }
        .status-live { background-color: #e0e7ff; color: #4338ca; }
        .status-idle { background-color: #e2e8f0; color: #334155; }
        .desc { white-space: pre-wrap; font-size: 13px; margin-top: 5px; color: #475569; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Task Report</h1>
        <p style="margin: 5px 0 0; color: #64748b;">Generated on {{ now()->format('d M Y, h:i A') }}</p>
    </div>

    <div class="summary-box">
        <table>
            <tr>
                <td><strong>Task Title:</strong></td>
                <td>{{ $title }}</td>
            </tr>
            <tr>
                <td><strong>Assigned Staff:</strong></td>
                <td>{{ $task->dailyReport->staff->name ?? 'Unknown' }}</td>
            </tr>
            <tr>
                <td><strong>Total Time:</strong></td>
                <td>{{ $totalTimeFormatted }}</td>
            </tr>
        </table>
    </div>

    <h3>Activity Timeline</h3>
    <table class="timeline">
        <thead>
            <tr>
                <th width="15%">Date</th>
                <th width="15%">Status</th>
                <th width="15%">Time Spent</th>
                <th width="55%">Description / Updates</th>
            </tr>
        </thead>
        <tbody>
            @foreach($historyData as $data)
                <tr>
                    <td>{{ $data['date'] }}</td>
                    <td>
                        @php
                            $s = strtolower($data['status']);
                            $sClass = match($s) {
                                'completed' => 'status-completed',
                                'paused' => 'status-paused',
                                'live' => 'status-live',
                                default => 'status-idle',
                            };
                        @endphp
                        <span class="status {{ $sClass }}">{{ $data['status'] }}</span>
                    </td>
                    <td>{{ $data['time_spend'] ?: '0m' }}</td>
                    <td>
                        <div class="desc">{{ strip_tags($data['description']) }}</div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
