//views/admin/activities/adminactivity_pdf.blade.php
// PDF template for Admin Activity Log Report

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Admin Activity Log Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 3px solid #2B7EC1;
        }
        
        .header h1 {
            color: #2B7EC1;
            font-size: 24px;
            margin: 0 0 10px 0;
            font-weight: bold;
        }
        
        .header .subtitle {
            color: #666;
            font-size: 14px;
            margin: 5px 0;
        }
        
        .info-section {
            background: #f8f9fa;
            padding: 15px;
            border: 2px solid #212529;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        
        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
        }
        
        .info-row:last-child {
            margin-bottom: 0;
        }
        
        .info-label {
            font-weight: bold;
            color: #2B7EC1;
        }
        
        .info-value {
            color: #333;
        }
        
        .activities-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            border: 2px solid #212529;
        }
        
        .activities-table th {
            background: linear-gradient(90deg, #2B7EC1 0%, #58A7E6 100%);
            color: white;
            padding: 12px 8px;
            text-align: left;
            font-weight: bold;
            border: 1px solid #212529;
            font-size: 11px;
        }
        
        .activities-table td {
            padding: 10px 8px;
            border: 1px solid #ddd;
            vertical-align: top;
            font-size: 10px;
        }
        
        .activities-table tbody tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        
        .activities-table tbody tr:hover {
            background-color: #e9ecef;
        }
        
        .action-badge {
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 9px;
            font-weight: bold;
            color: white;
            display: inline-block;
        }
        
        .action-login { background-color: #28a745; }
        .action-register { background-color: #007bff; }
        .action-download { background-color: #17a2b8; }
        .action-token { background-color: #ffc107; color: #212529; }
        .action-admin { background-color: #6f42c1; }
        .action-fail { background-color: #dc3545; }
        .action-default { background-color: #6c757d; }
        
        .resource-badge {
            padding: 2px 6px;
            border-radius: 8px;
            font-size: 8px;
            font-weight: bold;
            color: white;
            display: inline-block;
        }
        
        .resource-user { background-color: #007bff; }
        .resource-download { background-color: #17a2b8; }
        .resource-schedule { background-color: #ffc107; color: #212529; }
        .resource-token { background-color: #28a745; }
        .resource-default { background-color: #6c757d; }
        
        .user-info {
            font-size: 10px;
        }
        
        .user-name {
            font-weight: bold;
            color: #333;
        }
        
        .user-email {
            color: #666;
            font-style: italic;
        }
        
        .ip-address {
            font-family: 'Courier New', monospace;
            background-color: #f1f3f4;
            padding: 2px 4px;
            border-radius: 3px;
            font-size: 9px;
        }
        
        .datetime {
            font-size: 9px;
        }
        
        .datetime-date {
            font-weight: bold;
            color: #333;
        }
        
        .datetime-time {
            color: #666;
        }
        
        .footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 2px solid #dee2e6;
            text-align: center;
            font-size: 10px;
            color: #666;
        }
        
        .no-activities {
            text-align: center;
            padding: 40px 20px;
            color: #666;
            font-style: italic;
        }
        
        .page-break {
            page-break-before: always;
        }
        
        @media print {
            body { margin: 0; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Admin Activity Log Report</h1>
        <div class="subtitle">Generated on {{ $generated_at->format('F d, Y \a\t H:i:s') }}</div>
        <div class="subtitle">Exported by Admin: {{ $admin->name }} ({{ $admin->email }})</div>
        <div class="subtitle"><strong>All System Activities Report</strong></div>
    </div>

    <div class="info-section">
        <div class="info-row">
            <span class="info-label">Total Activities:</span>
            <span class="info-value">{{ number_format($total_activities) }} records</span>
        </div>
        <div class="info-row">
            <span class="info-label">Report Type:</span>
            <span class="info-value">All Users System Activities</span>
        </div>
        <div class="info-row">
            <span class="info-label">Date Range:</span>
            <span class="info-value">
                @if($from_date || $to_date)
                    {{ $from_date ? \Carbon\Carbon::parse($from_date)->format('M d, Y') : 'Beginning' }} 
                    to 
                    {{ $to_date ? \Carbon\Carbon::parse($to_date)->format('M d, Y') : 'Present' }}
                @else
                    All time
                @endif
            </span>
        </div>
        <div class="info-row">
            <span class="info-label">Exported by:</span>
            <span class="info-value">{{ $admin->name }} (Administrator)</span>
        </div>
        <div class="info-row">
            <span class="info-label">Report Generated:</span>
            <span class="info-value">{{ $generated_at->format('M d, Y H:i:s') }}</span>
        </div>
    </div>

    @if($activities->count() > 0)
        <table class="activities-table">
            <thead>
                <tr>
                    <th style="width: 8%;">#</th>
                    <th style="width: 22%;">User</th>
                    <th style="width: 18%;">Action</th>
                    <th style="width: 15%;">Resource</th>
                    <th style="width: 15%;">IP Address</th>
                    <th style="width: 22%;">Date & Time</th>
                </tr>
            </thead>
            <tbody>
                @foreach($activities as $index => $activity)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>
                            @if($activity->user)
                                <div class="user-info">
                                    <div class="user-name">{{ $activity->user->name }}</div>
                                    <div class="user-email">{{ $activity->user->email }}</div>
                                </div>
                            @else
                                <span style="color: #666; font-style: italic;">System</span>
                            @endif
                        </td>
                        <td>
                            @php
                                $actionClass = match(true) {
                                    str_contains($activity->action, 'login') => 'action-login',
                                    str_contains($activity->action, 'register') => 'action-register',
                                    str_contains($activity->action, 'download') => 'action-download',
                                    str_contains($activity->action, 'token') => 'action-token',
                                    str_contains($activity->action, 'admin') => 'action-admin',
                                    str_contains($activity->action, 'fail') || str_contains($activity->action, 'error') => 'action-fail',
                                    default => 'action-default'
                                }
                            @endphp
                            <span class="action-badge {{ $actionClass }}">
                                {{ str_replace('_', ' ', ucwords($activity->action)) }}
                            </span>
                        </td>
                        <td>
                            @if($activity->resource_type && $activity->resource_id)
                                @php
                                    $resourceClass = match($activity->resource_type) {
                                        'User' => 'resource-user',
                                        'Download' => 'resource-download',
                                        'Schedule' => 'resource-schedule',
                                        'TokenTransaction' => 'resource-token',
                                        default => 'resource-default'
                                    }
                                @endphp
                                <span class="resource-badge {{ $resourceClass }}">
                                    {{ $activity->resource_type }}
                                </span>
                                <small>#{{ $activity->resource_id }}</small>
                            @else
                                <span style="color: #999;">—</span>
                            @endif
                        </td>
                        <td>
                            @if($activity->ip_address)
                                <span class="ip-address">{{ $activity->ip_address }}</span>
                            @else
                                <span style="color: #999;">N/A</span>
                            @endif
                        </td>
                        <td>
                            <div class="datetime">
                                <div class="datetime-date">{{ $activity->created_at->format('M d, Y') }}</div>
                                <div class="datetime-time">{{ $activity->created_at->format('H:i:s') }}</div>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="no-activities">
            <strong>No activity logs found</strong><br>
            No activities match the selected criteria.
        </div>
    @endif

    <div class="footer">
        <p>This admin report contains {{ number_format($total_activities) }} activity log(s) from all system users</p>
        <p>Exported by {{ $admin->name }} (Administrator) on {{ $generated_at->format('F d, Y \a\t H:i:s') }}</p>
    </div>
</body>
</html>

