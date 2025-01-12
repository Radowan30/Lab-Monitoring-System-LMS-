<!DOCTYPE html>
<html>
<head>
    <title>Lab Report</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .graph-container {
            margin: 20px 0;
            text-align: center;
        }
        .graph-container img {
            max-width: 100%;
            height: auto;
        }
    </style>
</head>
<body>
    <h1>Lab Report</h1>
    
    <p><strong>Lab Room:</strong> {{ $summary['lab_room_name'] }}</p>
    <p><strong>Start Date:</strong> {{ $summary['start_date'] }}</p>
    <p><strong>End Date:</strong> {{ $summary['end_date'] }}</p>

    <h2>Temperature and Humidity Graph</h2>
    <div class="graph-container">
        @if($summary['chart_image'])
            <img src="data:image/png;base64,{{ $summary['chart_image'] }}" alt="Temperature and Humidity Graph">
        @endif
    </div>

    <h2>Summary Statistics</h2>
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

    <h2>Problem Description</h2>
    <p>{{ $summary['problem_desc'] }}</p>
</body>
</html>