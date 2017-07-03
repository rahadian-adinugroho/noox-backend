<?php

namespace Noox\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Noox\Channels\FcmChannel;

class NewsCommentReplied extends Notification implements ShouldQueue
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
        return ['broadcast', 'database', FcmChannel::class];
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
        'news_title'   => $this->parent->news->title,
        'time'         => $this->reply->created_at,
        ];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toDatabase($notifiable)
    {
        return [
        'parent_id'    => $this->parent->id,
        'reply_id'     => $this->reply->id,
        'replier_id'   => $this->replyAuthor->id,
        'replier_name' => $this->replyAuthor->name,
        'news_title'   => $this->parent->news->title,
        ];
    }

    /**
     * Get the fcm representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toFcm($notifiable)
    {
        return [
        'to'      => $notifiable->fcmTokens()->pluck('token')->toArray(),
        'title'   => 'Comment replied!',
        'body'    => "Your comment has been replied by {$this->replyAuthor->name}!",
        'payload' => $this->toArray($notifiable),
        ];
    }
}
