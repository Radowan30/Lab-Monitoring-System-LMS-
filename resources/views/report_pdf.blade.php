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

    {{-- <div class="summary-section">
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

    <div class="graph-section">
        <div class="graph-title">Temperature and Humidity Graph</div>
        @if($summary['chart_image'])
            <img src="{{ $summary['chart_image'] }}" 
                 class="graph-image" 
                 alt="Temperature and Humidity Graph">
        @else
            <p>No chart image available.</p>
        @endif
    </div>

    <div class="problems-section">
        <div class="section-title">Problems</div>
        <p>{{ $summary['problem_desc'] ?: 'No issues reported.' }}</p>
    </div> --}}



    <div class="space-y-4 p-4">
        <div class="border-b pb-4">
            <h3 class="text-lg font-semibold mb-3">Report Summary</h3>
            <div class="grid grid-cols-1 gap-2">
                <p><strong>Lab Room:</strong>{{ $summary['lab_room_name'] }}</p>
                <p><strong>Date Range:</strong>{{ $summary['start_date'] }} to {{ $summary['end_date'] }}</p>
            </div>
        </div>
        
        <!-- Chart image section -->
        <div class="mt-4">
            <h3 class="text-lg font-semibold mb-3">Temperature & Humidity Graph</h3>
            @if($summary['chart_image'])
                <img src="{{ $summary['chart_image'] }}" 
                     alt="Temperature and Humidity Graph" 
                     class="graph-image"/>
            @else
                <p>No chart image available</p>
            @endif

            <div class="mt-4 text-center">
                <p>Average Temperature: {{ $summary['avg_temp'] }}°C</p>
                <p>Maximum Temperature: {{ $summary['max_temp'] }}°C</p>
                <p>Minimum Temperature: {{ $summary['min_temp'] }}°C</p>
                <p>Maximum Humidity: {{ $summary['max_hum'] }}%</p>
                <p>Average Humidity: {{ $summary['avg_hum'] }}%</p>
            </div>
        </div>

        <!-- Problem description section -->
        <div class="mt-4">
            <h3 class="text-lg font-semibold mb-3">Problems</h3>
            <p>{{ $summary['problem_desc'] }}</p>
        </div>
    </div>
    
</body>
</html>