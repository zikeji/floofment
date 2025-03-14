<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SharedMemoriesCountChanged implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public int $count)
    {
        // no-op
    }

    public function broadcastAs(): string
    {
        return 'SharedMemoriesCountChanged';
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('Sidebar'),
        ];
    }
}
