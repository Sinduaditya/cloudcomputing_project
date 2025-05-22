<!-- resources/views/admin/token-management.blade.php -->
@extends('layouts.admin')

@section('content')
<div class="container mt-4">
    <h2>Token Management</h2>
    <table class="table table-bordered table-hover mt-3">
        <thead class="table-light">
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Token Balance</th>
                <th>Adjust Token</th>
            </tr>
        </thead>
        <tbody>
        @foreach($users as $user)
            <tr>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->token_balance }}</td>
                <td>
                    <form action="{{ route('admin.tokens.update', $user) }}" method="POST" class="d-flex gap-2">
                        @csrf
                        <input type="number" name="amount" class="form-control form-control-sm" placeholder="Amount" required>
                        <input type="text" name="reason" class="form-control form-control-sm" placeholder="Reason" required>
                        <button class="btn btn-sm btn-primary">Update</button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
@endsection
