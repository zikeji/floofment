<?php

namespace App\Models;

use App\PhoneRecordingStatus;
use Illuminate\Database\Eloquent\Model;

class PhoneRecording extends Model
{
    protected $primaryKey = 'sid';

    protected $keyType = 'string';

    public $incrementing = false;

    protected function casts(): array
    {
        return [
            'status' => PhoneRecordingStatus::class,
        ];
    }
}
