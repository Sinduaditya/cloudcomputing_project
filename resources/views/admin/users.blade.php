<!-- resources/views/admin/users.blade.php -->
@extends('layouts.admin')

@section('content')
<div class="container mt-4">
    <h2>User List</h2>
    <table class="table table-bordered table-hover mt-3">
        <thead class="table-light">
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Token</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        @foreach($users as $user)
            <tr>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->token_balance }}</td>
                <td>
                    @if($user->is_active)
                        <span class="badge bg-success">Active</span>
                    @else
                        <span class="badge bg-secondary">Inactive</span>
                    @endif
                </td>
                <td>
                    <a href="{{ route('admin.users.downloads', $user) }}" class="btn btn-sm btn-info">Downloads</a>
                    <a href="{{ route('admin.users.schedules', $user) }}" class="btn btn-sm btn-warning">Schedules</a>
                    <form action="{{ route('admin.users.toggle', $user) }}" method="POST" class="d-inline">
                        @csrf
                        <button class="btn btn-sm btn-secondary">{{ $user->is_active ? 'Deactivate' : 'Activate' }}</button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    {{ $users->links() }}
</div>
@endsection
