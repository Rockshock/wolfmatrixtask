@extends('layouts.main')

@section('content')
<div class="container">
    <h1>All Activity Logs</h1>

    @if($logs->count())
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Date & Time</th>
                    <th>Description</th>
                    <th>Model</th>
                    <th>Performed By</th>
                    <th>Changes</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($logs as $log)
                    <tr>
                        <td>{{ $log->created_at->format('Y-m-d H:i:s') }}</td>
                        <td>{{ $log->description }}</td>
                        <td>
                            @if($log->subject_type)
                                {{ class_basename($log->subject_type) }} (ID: {{ $log->subject_id }})
                            @else
                                N/A
                            @endif
                        </td>
                        <td>{{ $log->causer ? $log->causer->name : 'System' }}</td>
                        <td>
                            @if($log->properties->isNotEmpty())
                                <ul>
                                    @foreach ($log->properties->toArray() as $key => $value)
                                        <li><strong>{{ ucfirst($key) }}:</strong> {{ is_array($value) ? json_encode($value) : $value }}</li>
                                    @endforeach
                                </ul>
                            @else
                                No changes
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{ $logs->links() }}

    @else
        <p>No activity logs found.</p>
    @endif
</div>
@endsection
