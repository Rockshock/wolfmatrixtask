<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Category extends Model
{
    use SoftDeletes, LogsActivity;

    protected $fillable = ['name', 'path'];

    protected static $logAttributes = ['name', 'path'];

    public function getDepthAttribute()
    {
        return substr_count($this->path, '/');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logOnly(self::$logAttributes)->useLogName('category');
    }
}
