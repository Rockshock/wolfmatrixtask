@extends('layouts.main')

@section('content')
<div class="container">
    <h2>Create Patient</h2>
    @include('partials.flash_message')
    <form method="POST" action="{{ route('patients.store') }}" enctype="multipart/form-data">
        @include('patients.form')
    </form>
</div>
@endsection
