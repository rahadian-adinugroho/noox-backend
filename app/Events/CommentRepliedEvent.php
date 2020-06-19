<?php

namespace Noox\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class CommentRepliedEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Replied comment ID.
     * 
     * @var integer
     */
    public $comment_id;

    /**
     * Replier user data (ID, name)
     * 
     * @var string
     */
    public $replier;

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
    public function __construct($comment, $reply)
    {
        $this->comment    = $comment;

        $replyAuthor         = $reply->author;
        $this->comment_id    = $comment->id;
        $this->replier = ['id' => $replyAuthor->id, 'name' => $replyAuthor->name];
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
        return 'comment-replied';
    }
}
