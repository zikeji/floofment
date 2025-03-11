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

    protected $appends = ['recording_url'];

    public function getRecordingUrlAttribute(): string
    {
        return sprintf(
            '%s/%s/phone_recordings/%s.mp3',
            config('filesystems.disks.s3.endpoint'),
            config('filesystems.disks.s3.bucket'),
            $this->sid,
        );
    }
}
