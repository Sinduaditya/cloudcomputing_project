<!-- resources/views/admin/user-schedules.blade.php -->
@extends('layouts.admin')

@section('content')
<div class="container mt-4">
    <h2>Schedules for {{ $user->name }}</h2>
    <table class="table table-bordered table-hover mt-3">
        <thead class="table-light">
            <tr>
                <th>URL</th>
                <th>Format</th>
                <th>Scheduled For</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
        @foreach($schedules as $schedule)
            <tr>
                <td>{{ $schedule->url }}</td>
                <td>{{ $schedule->format }}</td>
                <td>{{ $schedule->scheduled_for }}</td>
                <td><span class="badge {{ $schedule->status_badge_color }}">{{ ucfirst($schedule->status) }}</span></td>
            </tr>
        @endforeach
        </tbody>
    </table>
    {{ $schedules->links() }}
</div>
@endsection
