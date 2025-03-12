<?php

namespace App\Models;

use App\Events\PhoneRecordingsCountChanged;
use App\PhoneRecordingStatus;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Database\Eloquent\BroadcastsEvents;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PhoneRecording extends Model
{
    use BroadcastsEvents, HasFactory;

    protected $primaryKey = 'sid';

    protected $keyType = 'string';

    public $incrementing = false;

    protected function casts(): array
    {
        return [
            'status' => PhoneRecordingStatus::class,
        ];
    }

    protected static function booting(): void
    {
        static::created(function () {
            broadcast(new PhoneRecordingsCountChanged(static::count()));
        });

        static::deleted(function () {
            broadcast(new PhoneRecordingsCountChanged(static::count()));
        });
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

    public function broadcastOn(string $event): array
    {
        return [
            new PrivateChannel('App.Models.PhoneRecording'),
        ];
    }
}
