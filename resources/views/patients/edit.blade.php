@extends('layouts.main')

@section('content')
<div class="container">
    <h2>Edit Patient</h2>
    @include('partials.flash_message')
    <form method="POST" action="{{ route('patients.update', $patient->id) }}" enctype="multipart/form-data">
        @method('PUT')
        @include('patients.form', ['patient' => $patient])
    </form>
</div>
@endsection
