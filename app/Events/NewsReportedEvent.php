<?php

namespace Noox\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class NewsReportedEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $report;

    public $reporter;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($report, $reporter)
    {
        $this->report   = ['id' => $report->id, 'created_at' => $report->created_at];
        $this->reporter = ['user_id' => $reporter->id, 'name' => $reporter->name];
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        $channels = array(new PrivateChannel('Noox.Admin'), new PrivateChannel('Noox.Owner'));
        return $channels;
    }

    public function broadcastAs()
    {
        return 'news-reported';
    }
}
