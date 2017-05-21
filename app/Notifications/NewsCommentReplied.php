<?php

namespace Noox\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class NewsCommentReplied extends Notification
{
    use Queueable;

    protected $parent;

    protected $reply;

    protected $replyAuthor;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($parent, $reply)
    {
        $this->parent      = $parent;
        $this->reply       = $reply;
        $this->replyAuthor = $reply->author;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['broadcast', 'database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
        'parent_id'    => $this->parent->id,
        'reply_id'     => $this->reply->id,
        'replier_id'   => $this->replyAuthor->id,
        'replier_name' => $this->replyAuthor->name,
        ];
    }
}
