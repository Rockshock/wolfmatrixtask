<?php

namespace App\Services;

use Illuminate\Http\Request;
use App\Repositories\PatientRepository;
use libphonenumber\PhoneNumberUtil;
use libphonenumber\PhoneNumberFormat;
use libphonenumber\NumberParseException;
use Illuminate\Support\Facades\Storage;

class PatientService
{
    protected $patientRepo;

    public function __construct(PatientRepository $patientRepo)
    {
        $this->patientRepo = $patientRepo;
    }

    public function getAll()
    {
        return $this->patientRepo->allPaginated();
    }

    public function findPatientById($id)
    {
        return $this->patientRepo->find($id);
    }

    public function store(Request $request)
    {
        $data = $request->validated();

        $phone = $this->normalizePhone($data['phone']);
        if (!$phone) {
            return ['phone' => 'Invalid phone number'];
        }

        $data['phone'] = $phone;

        if ($request->hasFile('patient_history_file')) {
            $data['patient_history_file'] = $request->file('patient_history_file')->store('patient_history_files');
        }

        $this->patientRepo->create($data);
        return true;
    }

    public function update(Request $request, $id)
    {
        $data = $request->validated();

        $phone = $this->normalizePhone($data['phone']);
        if (!$phone) {
            return ['phone' => 'Invalid phone number'];
        }

        $data['phone'] = $phone;

        if ($request->hasFile('patient_history_file')) {
            $patient = $this->patientRepo->find($id);
            if ($patient && $patient->patient_history_file) {
                Storage::delete($patient->patient_history_file);
            }
            $data['patient_history_file'] = $request->file('patient_history_file')->store('patient_history_files');
        }

        $this->patientRepo->update($id, $data);
        return true;
    }

    public function destroy($id)
    {
        $patient = $this->patientRepo->find($id);
        if ($patient && $patient->patient_history_file) {
            Storage::delete($patient->patient_history_file);
        }

        $this->patientRepo->delete($id);
    }

    private function normalizePhone($phone)
    {
        try {
            $phoneUtil = PhoneNumberUtil::getInstance();
            $numberProto = $phoneUtil->parse($phone, null);
            if (!$phoneUtil->isValidNumber($numberProto)) {
                return false;
            }
            return $phoneUtil->format($numberProto, PhoneNumberFormat::E164);
        } catch (NumberParseException $e) {
            return false;
        }
    }
}
