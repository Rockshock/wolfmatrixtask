@extends('layouts.main')

@section('content')
<div class="container">
    <h2 class="mb-4">Deleted Categories History</h2>

    @if($history->count())
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Category Name</th>
                    <th>Path</th>
                    <th>Deleted By</th>
                    <th>Deleted At</th>
                </tr>
            </thead>
            <tbody>
                @foreach($history as $entry)
                    <tr>
                        <td>{{ $entry->name }}</td>
                        <td>{{ $entry->path }}</td>
                        <td>{{ $entry->user?->name ?? 'System' }}</td>
                        <td>{{ $entry->deleted_at }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{ $history->links() }}
    @else
        <p>No deleted categories found.</p>
    @endif
</div>
@endsection
