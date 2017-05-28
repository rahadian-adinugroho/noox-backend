<?php

namespace Noox\Notifications;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class NewsCommentLiked extends Notification
{
    use Queueable;

    protected $comment;

    protected $liker;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($comment, $liker)
    {
        $this->comment = $comment;
        $this->liker   = $liker;
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
        'comment_id'      => $this->comment->id,
        'comment_content' => $this->comment->content,
        'liker_id'        => $this->liker->id,
        'liker_name'      => $this->liker->name,
        'time'            => Carbon::now(),
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
        'comment_id'      => $this->comment->id,
        'comment_content' => $this->comment->content,
        'liker_id'        => $this->liker->id,
        'liker_name'      => $this->liker->name,
        ];
    }
}
