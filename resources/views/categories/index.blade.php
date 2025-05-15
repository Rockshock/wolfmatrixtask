@extends('layouts.main')

@section('content')
<div class="container">
    <h2>Categories</h2>

    @if(session('message'))
        <div class="alert alert-success">{{ session('message') }}</div>
    @endif

    <!-- Create Form -->
    <form action="{{ url('/categories') }}" method="POST" class="mb-4">
        @csrf
        <div class="mb-3">
            <label>Name:</label>
            <input type="text" name="name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Parent Category (optional):</label>
            <select name="parent_id" class="form-control">
                <option value="">-- None --</option>
                @if(!empty($categories))
                @foreach($categories as $cat)
                    <option value="{{ $cat['id'] }}">{{ $cat['name'] }}</option>
                @endforeach
                @endif
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Add Category</button>
    </form>

    <!-- List of Categories -->
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Name</th>
                <th>Path</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @if(!empty($categories))
            @foreach($categories as $cat)
                <tr>
                    <td>{{ $cat['name'] }}</td>
                    <td>{{ $cat['path'] }}</td>
                    <td>
                        <!-- Update Form (Inline) -->
                        <form action="{{ url('/categories/'.$cat['id']) }}" method="POST" style="display:inline-block">
                            @csrf
                            @method('PUT')
                            <input type="text" name="name" value="{{ $cat['name'] }}" required>
                            <button type="submit" class="btn btn-sm btn-warning">Update</button>
                        </form>

                        <!-- Delete Form -->
                        <form action="{{ url('/categories/'.$cat['id']) }}" method="POST" style="display:inline-block" onsubmit="return confirm('Delete this category?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            @endif
        </tbody>
    </table>
</div>
@endsection
