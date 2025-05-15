<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\PatientService;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\StorePatientRequest;

class PatientController extends Controller
{
    protected $patientService;

    public function __construct(PatientService $patientService)
    {
        $this->patientService = $patientService;
    }

    public function index()
    {
        $patients = $this->patientService->getAll();
        return view('patients.index', compact('patients'));
    }

    public function create()
    {
        return view('patients.create');
    }

    public function store(StorePatientRequest $request)
    {
        $result = $this->patientService->store($request);

        if (is_array($result)) {
            // Validation error (e.g., phone number invalid)
            return back()->withErrors($result)->withInput();
        }

        return redirect()->route('patients.index')->with('success', 'Patient created successfully.');
    }

    public function edit($id)
    {
        $patient = $this->patientService->findPatientById($id);
        if (!$patient) {
            abort(404);
        }
        return view('patients.edit', compact('patient'));
    }

    public function update(StorePatientRequest $request, $id)
    {
        $result = $this->patientService->update($request, $id);

        if (is_array($result)) {
            return back()->withErrors($result)->withInput();
        }

        return redirect()->route('patients.index')->with('success', 'Patient updated successfully.');
    }

    public function destroy($id)
    {
        $this->patientService->destroy($id);
        return redirect()->route('patients.index')->with('success', 'Patient deleted successfully.');
    }

    public function download($id)
    {
        $patient = $this->patientService->findPatientById($id);
        if (!$patient || !$patient->patient_history_file) {
            return redirect()->back()->withErrors('Patient history file not found!');
        }

        return Storage::download($patient->patient_history_file);
    }
}
