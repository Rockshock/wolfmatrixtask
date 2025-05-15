<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class PatientRepository
{
    protected $table = 'patients';

    public function allPaginated($perPage = 10)
    {
        return DB::table($this->table)->latest()->paginate($perPage);
    }

    public function find($id)
    {
        return DB::table($this->table)->where('id', $id)->first();
    }

    public function create(array $data)
    {
        return DB::table($this->table)->insert([
            'name' => $data['name'],
            'phone' => $data['phone'],
            'patient_history_file' => $data['patient_history_file'] ?? null,
        ]);
    }

    public function update($id, array $data)
    {
        return DB::table($this->table)->where('id', $id)->update([
            'name' => $data['name'],
            'phone' => $data['phone'],
            'patient_history_file' => $data['patient_history_file'] ?? null,
        ]);
    }

    public function delete($id)
    {
        return DB::table($this->table)->where('id', $id)->delete();
    }
}
