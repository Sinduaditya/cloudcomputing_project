<!-- resources/views/tokens/history.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h3>Token Transaction History</h3>
    <table class="table table-bordered table-hover mt-3">
        <thead class="table-light">
            <tr>
                <th>Date</th>
                <th>Type</th>
                <th>Amount</th>
                <th>Balance After</th>
                <th>Description</th>
            </tr>
        </thead>
        <tbody>
        @foreach($transactions as $trx)
            <tr>
                <td>{{ $trx->created_at }}</td>
                <td>{{ $trx->type_description }}</td>
                <td>{{ $trx->amount }}</td>
                <td>{{ $trx->balance_after }}</td>
                <td>{{ $trx->description }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
    {{ $transactions->links() }}
</div>
@endsection
