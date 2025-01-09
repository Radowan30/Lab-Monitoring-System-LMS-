<!DOCTYPE html>
<html>
<head>
    <title>Lab Report</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        h1 { text-align: center; }
        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        table, th, td { border: 1px solid #000; }
        th, td { padding: 10px; text-align: center; }
    </style>
</head>
<body>
    <h1>Lab Report</h1>
    <p><strong>Start Date:</strong> {{ $summary['start_date'] }}</p>
    <p><strong>End Date:</strong> {{ $summary['end_date'] }}</p>
    <table>
        <tr>
            <th>Max Temperature</th>
            <th>Max Humidity</th>
            <th>Avg Temperature</th>
            <th>Avg Humidity</th>
        </tr>
        <tr>
            <td>{{ $summary['max_temp'] }} °C</td>
            <td>{{ $summary['max_hum'] }} %</td>
            <td>{{ $summary['avg_temp'] }} °C</td>
            <td>{{ $summary['avg_hum'] }} %</td>
        </tr>
    </table>
    <p><strong>Problem Description:</strong></p>
    <p>{{ $summary['problem_desc'] }}</p>


</body>
</html>
