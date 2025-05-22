<!-- resources/views/admin/user-downloads.blade.php -->
@extends('layouts.admin')

@section('content')
<div class="container mt-4">
    <h2>Downloads for {{ $user->name }}</h2>
    <table class="table table-bordered table-hover mt-3">
        <thead class="table-light">
            <tr>
                <th>Title</th>
                <th>Platform</th>
                <th>Status</th>
                <th>Format</th>
                <th>Token Cost</th>
                <th>Created At</th>
            </tr>
        </thead>
        <tbody>
        @foreach($downloads as $download)
            <tr>
                <td>{{ $download->title }}</td>
                <td>{{ ucfirst($download->platform) }}</td>
                <td><span class="badge {{ $download->status_badge_color }}">{{ ucfirst($download->status) }}</span></td>
                <td>{{ $download->format }}</td>
                <td>{{ $download->token_cost }}</td>
                <td>{{ $download->created_at }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
    {{ $downloads->links() }}
</div>
@endsection
