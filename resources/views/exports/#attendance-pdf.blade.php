<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Attendance Export</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; }
        th { background: #eee; }
    </style>
</head>
<body>
    <h2>Attendance Report</h2>
    <p><strong>Service:</strong> {{ $service }}</p>
    <p><strong>Date:</strong> {{ \Carbon\Carbon::parse($date)->format('l, jS F Y') }}</p>

    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Matric No</th>
                <th>Status</th>
                <th>Marked At</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($records as $record)
                <tr>
                    <td>{{ $record->student->name }}</td>
                    <td>{{ $record->student->matric_no }}</td>
                    <td>{{ $record->is_late ? 'Late' : 'On Time' }}</td>
                    <td>{{ \Carbon\Carbon::parse($record->created_at)->format('h:i A') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
