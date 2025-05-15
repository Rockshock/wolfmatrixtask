<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class CategoryRepository
{
    public function getAll()
    {
        return DB::table('categories')->whereNull('deleted_at')->orderBy('created_at', 'desc')->get();
    }

    public function find($id)
    {
        return DB::table('categories')->where('id', $id)->first();
    }

    public function findOrFail($id)
    {
        $category = $this->find($id);
        if (!$category) {
            abort(404, 'Category not found');
        }
        return $category;
    }

    public function create(array $data)
    {
        $id = DB::table('categories')->insertGetId([
            'name' => $data['name'],
            'path' => $data['path'] ?? $data['name'],
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        return $this->find($id);
    }

    public function update($id, array $data)
    {
        DB::table('categories')->where('id', $id)->update(array_merge(
            $data,
            ['updated_at' => now()]
        ));
        return $this->findOrFail($id);
    }
}
