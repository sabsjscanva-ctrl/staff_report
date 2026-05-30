<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Daily Backup Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .header h2 {
            margin: 0;
            color: #4f46e5;
        }
        .header p {
            margin: 5px 0;
            color: #666;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f8fafc;
            font-weight: bold;
            color: #475569;
            text-transform: uppercase;
            font-size: 10px;
        }
        tr:nth-child(even) {
            background-color: #f9fafb;
        }
        .status-yes {
            color: #166534;
            font-weight: bold;
        }
        .status-no {
            color: #991b1b;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>Daily Backup Report</h2>
        <p>Generated on: {{ date('d M Y, h:i A') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Staff Name</th>
                <th>Date</th>
                <th>Status</th>
                <th>Location</th>
                <th>Remark</th>
            </tr>
        </thead>
        <tbody>
            @forelse($backups as $backup)
                <tr>
                    <td>{{ $backup->staff->name ?? 'Unknown' }}</td>
                    <td>{{ \Carbon\Carbon::parse($backup->backup_date)->format('d M Y') }}</td>
                    <td>
                        @if($backup->status == 'YES')
                            <span class="status-yes">YES</span>
                        @else
                            <span class="status-no">NO</span>
                        @endif
                    </td>
                    <td>{{ $backup->location ?? '-' }}</td>
                    <td>{{ $backup->remark ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align: center;">No backup records found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
