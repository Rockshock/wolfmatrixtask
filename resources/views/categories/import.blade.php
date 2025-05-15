@extends('layouts.main')

@section('content')
<div class="container mt-5">
    <h2>Import Categories from CSV</h2>

    @if(session('message'))
        <div class="alert alert-success">{{ session('message') }}</div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Upload failed:</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('categories.import') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label for="file" class="form-label">CSV File</label>
            <input type="file" name="file" class="form-control" accept=".csv,.txt" required>
            <small class="form-text text-muted">Make sure the CSV has headers: <code>name,parent_name</code></small>
            <small class="form-text text-muted">Sample file download: <a href="{{ asset('file/sample.csv') }}" download>sample.csv</a></small>
        </div>

        <button type="submit" class="btn btn-primary">Import</button>
    </form>
</div>
@endsection
