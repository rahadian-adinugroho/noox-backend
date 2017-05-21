<?php

namespace Noox\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class CommentLikedEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Liked comment ID.
     * 
     * @var integer
     */
    public $comment_id;

    /**
     * Liked comment parent ID.
     * 
     * @var integer
     */
    public $comment_parent_id;

    /**
     * Liker user data (ID, name)
     * 
     * @var string
     */
    public $liker;

    /**
     * The original comment object.
     * 
     * @var \Noox\Models\NewsComment
     */
    protected $comment;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($comment, $liker)
    {
        $this->comment    = $comment;

        $this->comment_id        = $comment->id;
        $this->comment_parent_id = $comment->parent_id;
        $this->liker             = ['id' => $liker->id, 'name' => $liker->name];
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('Noox.Models.User.'.$this->comment->user_id);
    }

    public function broadcastAs()
    {
        return 'comment-liked';
    }
}
