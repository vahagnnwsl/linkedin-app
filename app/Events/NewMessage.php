<?php

namespace App\Events;

use App\Http\Resources\MessageResource;
use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class NewMessage implements ShouldBroadcast
{

    use Dispatchable, InteractsWithSockets, SerializesModels;



    /**
     * The name of the queue the job should be sent to.
     *
     * @var string|null
     */
    public $queue = 'newMessage';
    /**
     * @var MessageResource
     */
    private  $message;

    /**
     * @var string
     */
    private string $channel_name;


    public function __construct( $message, string $channel_name)
    {

        $this->message = $message;
        $this->channel_name = 'channel.'.$channel_name;


    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel
     */
    public function broadcastOn(): Channel
    {

        return new Channel($this->channel_name);
    }


    public function broadcastWith()
    {

        return $this->message;
    }

    /**
     * Broadcast event name
     *
     * @return string
     */
    public function broadcastAs(): string
    {
        return 'newMessage';
    }
}
