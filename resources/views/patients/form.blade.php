@csrf

<div class="mb-3">
    <label>Name</label>
    <input type="text" name="name" value="{{ old('name', $patient->name ?? '') }}" class="form-control">
    @error('name')
        <span class="text-danger">{{ $message }}</span>        
    @enderror
</div>

<div class="mb-3">
    <label>Phone <small>(Note: add [country code] e.g. +977 then [your number])</small></label>
    <input type="text" name="phone" value="{{ old('phone', $patient->phone ?? '') }}" class="form-control phone-input">
    @error('phone')
        <span class="text-danger">{{ $message }}</span>        
    @enderror
</div>

<div class="mb-3">
    <label>Patient History File (PDF only)</label>
    <input type="file" name="patient_history_file" class="form-control" accept="application/pdf">
    @if(!empty($patient->patient_history_file))
        <small>Current: <a href="{{ route('patients.download', $patient->id) }}">Download</a></small>
    @endif
    @error('patient_history_file')
        <span class="text-danger">{{ $message }}</span>        
    @enderror
</div>

<button class="btn btn-success">Submit</button>
