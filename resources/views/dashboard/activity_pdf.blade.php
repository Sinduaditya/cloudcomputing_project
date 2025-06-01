<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Activity Log Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.5;
            color: #333;
            margin: 0;
            padding: 15px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #2B7EC1;
        }

        .header h1 {
            color: #2B7EC1;
            font-size: 22px;
            margin: 0 0 8px 0;
            font-weight: bold;
        }

        .header .subtitle {
            color: #666;
            font-size: 12px;
            margin: 4px 0;
        }

        .info-section {
            background: #f8f9fa;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            margin-bottom: 15px;
        }

        .info-row {
            margin-bottom: 5px;
            overflow: hidden;
        }

        .info-row:after {
            content: "";
            display: table;
            clear: both;
        }

        .info-label {
            float: left;
            width: 40%;
            font-weight: bold;
            color: #2B7EC1;
        }

        .info-value {
            float: right;
            width: 60%;
            text-align: right;
            color: #333;
        }

        .activities-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            border: 1px solid #ddd;
        }

        .activities-table th {
            background-color: #2B7EC1;
            color: white;
            padding: 8px 6px;
            text-align: left;
            font-weight: bold;
            border: 1px solid #ddd;
            font-size: 11px;
        }

        .activities-table td {
            padding: 8px 6px;
            border: 1px solid #ddd;
            vertical-align: top;
            font-size: 10px;
        }

        .activities-table tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .action-badge {
            padding: 2px 6px;
            border-radius: 10px;
            font-size: 10px;
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
            border-radius: 4px;
            font-size: 10px;
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
            font-family: monospace;
            background-color: #f1f3f4;
            padding: 2px 3px;
            border-radius: 3px;
            font-size: 10px;
        }

        .datetime {
            font-size: 10px;
        }

        .datetime-date {
            font-weight: bold;
            color: #333;
        }

        .datetime-time {
            color: #666;
        }

        .footer {
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px solid #ddd;
            text-align: center;
            font-size: 10px;
            color: #666;
        }

        .no-activities {
            text-align: center;
            padding: 30px 15px;
            color: #666;
            font-style: italic;
        }

        .page-break {
            page-break-before: always;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Activity Log Report</h1>
        <div class="subtitle">Generated on {{ $generated_at->format('F d, Y \a\t H:i:s') }}</div>
        <div class="subtitle">User: {{ $user->name }} ({{ $user->email }})</div>
    </div>

    <div class="info-section">
        <div class="info-row">
            <span class="info-label">Total Activities:</span>
            <span class="info-value">{{ number_format($total_activities) }} records</span>
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
            <span class="info-label">Report Generated:</span>
            <span class="info-value">{{ $generated_at->format('M d, Y H:i:s') }}</span>
        </div>
    </div>

    @if($activities->count() > 0)
        <table class="activities-table">
            <thead>
                <tr>
                    <th style="width: 5%;">#</th>
                    <th style="width: 25%;">User</th>
                    <th style="width: 18%;">Action</th>
                    <th style="width: 17%;">Resource</th>
                    <th style="width: 15%;">IP Address</th>
                    <th style="width: 20%;">Date & Time</th>
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
        <p>This report contains {{ number_format($total_activities) }} activity log(s) for {{ $user->name }}</p>
        <p>Generated automatically by the system on {{ $generated_at->format('F d, Y \a\t H:i:s') }}</p>
    </div>
</body>
</html>
