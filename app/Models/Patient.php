<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Patient extends Model
{
    use LogsActivity;
    
    protected $fillable = [
        'name',
        'phone',
        'patient_history_file',
    ];

    protected static $logAttributes = ['name', 'phone', 'patient_history_file'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logOnly(self::$logAttributes)->useLogName('patient');
    }
}
