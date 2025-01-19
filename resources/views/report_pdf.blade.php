<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Lab Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .report-title {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .summary-section {
            margin-bottom: 30px;
        }
        .summary-title {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 15px;
            color: #2c5282;
        }
        .data-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-bottom: 20px;
        }
        .data-item {
            margin-bottom: 10px;
        }
        .label {
            font-weight: bold;
            color: #4a5568;
        }
        .graph-section {
            margin-top: 30px;
            text-align: center;
        }
        .graph-title {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 15px;
            color: #2c5282;
        }
        .graph-image {
            max-width: 100%;
            height: auto;
            margin: 0 auto;
        }
        .problems-section {
            margin-top: 30px;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="report-title">Lab Report</div>
        <div>Generated on {{ date('Y-m-d H:i:s') }}</div>
    </div>

    <div class="summary-section">
        <div class="summary-title">Summary</div>
        <div class="data-grid">
            <div class="data-item">
                <span class="label">Lab Room:</span>
                <span>{{ $summary['lab_room_name'] }}</span>
            </div>
            <div class="data-item">
                <span class="label">Date Range:</span>
                <span>{{ $summary['start_date'] }} to {{ $summary['end_date'] }}</span>
            </div>
            <div class="data-item">
                <span class="label">Maximum Temperature:</span>
                <span>{{ $summary['max_temp'] }}°C</span>
            </div>
            <div class="data-item">
                <span class="label">Maximum Humidity:</span>
                <span>{{ $summary['max_hum'] }}%</span>
            </div>
            <div class="data-item">
                <span class="label">Average Temperature:</span>
                <span>{{ $summary['avg_temp'] }}°C</span>
            </div>
            <div class="data-item">
                <span class="label">Average Humidity:</span>
                <span>{{ $summary['avg_hum'] }}%</span>
            </div>
        </div>
    </div>

    {{-- <div class="graph-section">
        <div class="graph-title">Temperature and Humidity Graph</div>
        @if($summary['chart_image'])
            <img src="data:image/png;base64,{{ $summary['chart_image'] }}" 
                 class="graph-image" 
                 alt="Temperature and Humidity Graph">
        @endif
    </div> --}}

    <div class="problems-section">
        <div class="section-title">Problems</div>
        <p>{{ $summary['problem_desc'] ?: 'No issues reported.' }}</p>
    </div>
</body>
</html>