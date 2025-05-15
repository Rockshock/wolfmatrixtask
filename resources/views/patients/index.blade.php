@extends('layouts.main')

@section('content')
<div class="container">
    <h2>Patients</h2>

    @include('partials.flash_message')

    <a href="{{ route('patients.create') }}" class="btn btn-primary mb-3">Add Patient</a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Name</th>
                <th>Phone</th>
                <th>History File</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($patients as $patient)
                <tr>
                    <td>{{ $patient->name }}</td>
                    <td>{{ $patient->phone }}</td>
                    <td>
                        @if($patient->patient_history_file)
                            <a href="{{ route('patients.download', $patient->id) }}">Download</a>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('patients.edit', $patient->id) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form action="{{ route('patients.destroy', $patient->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this patient?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="4">No patients found.</td></tr>
            @endforelse
        </tbody>
    </table>

    {{ $patients->links() }}
</div>
@endsection
