<p>Temperature and/or humidity above threshold in {{ $sensor->lab_room_name }}:</p>
<ul>
    <li>Recorded temperature: {{ $temperature }}°C</li>
    <li>Recorded humidity: {{ $humidity }}%</li>
    <li>Thresholds: temperature {{ $tempThreshold }}°C, humidity {{ $humidThreshold }}%</li>
</ul>
<p>Date and Time: {{ now() }}</p>