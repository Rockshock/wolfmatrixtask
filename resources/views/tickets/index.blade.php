@extends('layouts.main')

@section('content')
<div class="container">
    <h1>Tickets</h1>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">{{ $errors->first() }}</div>
    @endif

    <a href='{{ route('tickets.seed') }}' class="btn btn-primary mb-4">Add Tickets</a>

    <table class="table">
        <thead>
            <tr>
                <th>Code</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($tickets as $ticket)
                <tr>
                    <td>{{ $ticket->code }}</td>
                    <td>{{ $ticket->is_reserved ? 'Reserved' : 'Available' }}</td>
                    <td>
                        @if (!$ticket->is_reserved)
                            <form method="POST" action="{{ route('tickets.reserve', $ticket->id) }}">
                                @csrf
                                <button type="submit" class="btn btn-primary btn-sm">Reserve</button>
                            </form>
                        @else
                            <span class="text-muted">Reserved</span>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{ $tickets->links() }}
</div>
@endsection
