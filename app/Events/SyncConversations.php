<?php

namespace App\Events;

use App\Http\Resources\LinkedinMessage;
use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class SyncConversations implements ShouldBroadcast
{
    /**
     * The name of the queue on which to place the event.
     *
     * @var string
     */
    public string $broadcastQueue = 'sync-conversations';


    /**
     * @var string
     */
    private string $channel_name;

    /**
     * NewMessage constructor.
     * @param string $channel_name
     */

    public function __construct(string $channel_name)
    {
        $this->channel_name = $channel_name;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('channel.'.$this->channel_name );
    }

    /**
     * Get the data to broadcast.
     *
     * @return array
     */
    public function broadcastWith(): array
    {
        return [];
    }

    /**
     * Broadcast event name
     *
     * @return string
     */
    public function broadcastAs(): string
    {
        return 'sync-conversations';
    }
}
