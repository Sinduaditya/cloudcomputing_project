@extends('layouts.admin') {{-- atau layouts.app tergantung layout-mu --}}

@section('content')
    <h1>Scheduled Tasks</h1>

    @if(count($schedules) > 0)
        <ul>
            @foreach ($schedules as $task)
                <li>{{ $task->name }} - {{ $task->scheduled_for }} - {{ $task->status }}</li>
            @endforeach
        </ul>
    @else
        <p>No scheduled tasks found.</p>
    @endif
@endsection
