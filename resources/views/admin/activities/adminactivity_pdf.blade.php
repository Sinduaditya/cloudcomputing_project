<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Admin Activity Log Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            line-height: 1.3;
            color: #333;
            margin: 0;
            padding: 15px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #4a86e8;
        }

        .header h1 {
            color: #4a86e8;
            font-size: 20px;
            margin: 0 0 8px 0;
        }

        .header .subtitle {
            font-size: 12px;
            margin: 3px 0;
        }

        .info-section {
            background: #f5f5f5;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            margin-bottom: 15px;
            display: table;
            width: 100%;
        }

        .info-row {
            display: table-row;
        }

        .info-label {
            display: table-cell;
            font-weight: bold;
            padding: 4px 10px 4px 0;
            width: 25%;
        }

        .info-value {
            display: table-cell;
            padding: 4px 0;
        }

        .activities-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 9px;
        }

        .activities-table th {
            background: #4a86e8;
            color: white;
            padding: 6px 4px;
            text-align: left;
            border: 1px solid #ddd;
        }

        .activities-table td {
            padding: 5px 4px;
            border: 1px solid #ddd;
            vertical-align: top;
        }

        .activities-table tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .action-badge {
            padding: 2px 5px;
            border-radius: 10px;
            font-size: 8px;
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
            padding: 2px 4px;
            border-radius: 3px;
            font-size: 8px;
            font-weight: bold;
            color: white;
        }

        .resource-user { background-color: #007bff; }
        .resource-download { background-color: #17a2b8; }
        .resource-schedule { background-color: #ffc107; color: #212529; }
        .resource-token { background-color: #28a745; }
        .resource-default { background-color: #6c757d; }

        .user-info {
            font-size: 9px;
        }

        .user-name {
            font-weight: bold;
        }

        .user-email {
            color: #666;
        }

        .ip-address {
            font-family: monospace;
            font-size: 9px;
        }

        .datetime {
            font-size: 9px;
        }

        .datetime-date {
            font-weight: bold;
        }

        .no-activities {
            text-align: center;
            padding: 20px;
            color: #666;
            font-style: italic;
        }

        .footer {
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px solid #ddd;
            text-align: center;
            font-size: 9px;
            color: #666;
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
                    <th width="5%">#</th>
                    <th width="24%">User</th>
                    <th width="18%">Action</th>
                    <th width="16%">Resource</th>
                    <th width="15%">IP Address</th>
                    <th width="22%">Date & Time</th>
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
                                <span class="datetime-date">{{ $activity->created_at->format('M d, Y') }}</span>
                                <span class="datetime-time">{{ $activity->created_at->format('H:i:s') }}</span>
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
