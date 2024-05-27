<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'subject',
        'message',
        'description',
        'date',
        'status',
        'send_at',
        'send_to',
        'send_to_user_id',
        'created_by',
    ];

    public function recipients()
    {
        return $this->hasMany(ScheduleRecipient::class);
    }

    public function attachments()
    {
        return $this->hasMany(ScheduleAttachment::class);
    }
}
