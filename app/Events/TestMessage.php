<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
//use Illuminate\Foundation\Bus\Dispatchable;

class TestMessage implements ShouldBroadcast
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public string $message
    ) {}

    public function broadcastOn(): array
    {
        return [
            new Channel('test-channel'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'test.message';
    }
}