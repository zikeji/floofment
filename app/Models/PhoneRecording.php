<?php

namespace App\Models;

use App\Events\PhoneRecordingsCountChanged;
use App\PhoneRecordingStatus;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Database\Eloquent\BroadcastsEvents;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

/**
 * @property string $sid
 * @property string $from
 * @property string $called
 * @property PhoneRecordingStatus $status
 * @property Contact $contact
 * @property string $label
 * @property string $recording_url
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class PhoneRecording extends Model
{
    use BroadcastsEvents, HasFactory;

    protected $primaryKey = 'sid';

    protected $keyType = 'string';

    public $incrementing = false;

    protected $fillable = ['sid', 'from', 'called'];

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

    protected $appends = ['label', 'recording_url'];

    public function getLabelAttribute(): string
    {
        return $this->contact?->nickname ?? $this->from;
    }

    public function getRecordingUrlAttribute(): string
    {
        return Storage::disk('s3')->url("phone_recordings/{$this->sid}.mp3");
    }

    public function broadcastOn(string $event): array
    {
        return [
            new PrivateChannel('App.Models.PhoneRecording'),
        ];
    }

    public function contact(): BelongsTo
    {
        return $this->belongsTo(Contact::class, 'from', 'phone');
    }
}
