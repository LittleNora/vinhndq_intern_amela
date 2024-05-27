<?php

namespace App\Models;

use App\Casts\Url;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class ScheduleAttachment extends Model
{
    use HasFactory;

    protected $fillable = [
        'schedule_id',
        'original_name',
        'path',
        'mime_type',
    ];

    protected $casts = [
        'path' => Url::class,
    ];

    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }
}
