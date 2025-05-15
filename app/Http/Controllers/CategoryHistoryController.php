<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CategoryDeletion;

class CategoryHistoryController extends Controller
{
    public function index()
    {
        $history = CategoryDeletion::with('user')->latest('deleted_at')->paginate(20);
        return view('categories.history', compact('history'));
    }
}
