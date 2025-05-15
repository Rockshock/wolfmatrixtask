<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoryDeletion extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'category_id',
        'name',
        'path',
        'deleted_by',
        'deleted_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
