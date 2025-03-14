<?php

namespace App\Models;

use App\Collections\SharedMemoryAttachments;
use App\Events\SharedMemoriesCountChanged;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Database\Eloquent\BroadcastsEvents;
use Illuminate\Database\Eloquent\Casts\AsCollection;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

/**
 * @property string $id
 * @property string $name
 * @property string $message
 * @property bool $has_voice_message
 * @property string|null $voice_message_extension
 * @property SharedMemoryAttachments $attachments
 * @property string $ip_address
 * @property string $user_agent
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class SharedMemory extends Model
{
    use HasUlids, BroadcastsEvents;

    protected $fillable = ['name', 'message', 'has_voice_message', 'voice_message_extension', 'attachments', 'ip_address', 'user_agent'];

    protected function casts(): array
    {
        return [
            'has_voice_message' => 'boolean',
            'attachments' => AsCollection::using(SharedMemoryAttachments::class),
        ];
    }

    protected static function booting(): void
    {
        static::created(function () {
            broadcast(new SharedMemoriesCountChanged(static::count()));
        });

        static::deleted(function () {
            broadcast(new SharedMemoriesCountChanged(static::count()));
        });
    }

    protected $appends = ['voice_message_url'];

    public function getVoiceMessageUrlAttribute(): string
    {
        return $this->has_voice_message
            ? Storage::disk('s3')->url("voice-messages/{$this->id}.{}")
            : '';
    }

    public function broadcastOn(string $event): array
    {
        return [
            new PrivateChannel('App.Models.SharedMemory'),
        ];
    }
}
