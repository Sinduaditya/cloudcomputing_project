@extends('layouts.admin')

@section('content')
    <div class="container">
        <h1 class="my-4">Download History</h1>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>User</th>
                    <th>Platform</th>
                    <th>Status</th>
                    <th>Created At</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($downloads as $d)
                    <tr>
                        <td>{{ $d->user->name ?? 'N/A' }}</td>
                        <td>{{ $d->platform }}</td>
                        <td>{{ $d->status }}</td>
                        <td>{{ $d->created_at }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{ $downloads->links() }}
    </div>
@endsection
