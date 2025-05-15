<?php

namespace App\Http\Controllers\Api;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\CategoryDeletion;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use App\Repositories\CategoryRepository;

class CategoryController extends Controller
{
    protected $categoryRepo;

    public function __construct(CategoryRepository $categoryRepo)
    {
        $this->categoryRepo = $categoryRepo;
    }

    public function index()
    {
        $cacheKey = 'categories_index';

        $categories = Cache::remember($cacheKey, 60 * 60, function () {
            return $this->categoryRepo->getAll();
        });

        return response()->json($categories, 200);
    }

    public function store(Request $request)
    {
        $data = $request->only('name', 'parent_id');

        if (!empty($data['parent_id'])) {
            $parent = $this->categoryRepo->find($data['parent_id']);
            $data['path'] = $parent ? $parent->path . '/' . $data['name'] : $data['name'];
        } else {
            $data['path'] = $data['name'];
        }

        $category = $this->categoryRepo->create($data);

        Cache::forget('categories_index');

        return response()->json($category, 201);
    }

    public function update(Request $request, $id)
    {
        $data = $request->only('name');

        $category = $this->categoryRepo->update($id, $data);

        Cache::forget('categories_index');

        return response()->json($category);
    }

    public function destroy(Request $request, $id)
    {
        $deletedByUserId = $request->user()->id ?? null; // Get user id if authenticated

        Category::findOrFail($id)->delete($deletedByUserId);

        Cache::forget('categories_index');

        return response()->json(['message' => 'Deleted']);
    }
}
