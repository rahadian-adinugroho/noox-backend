<?php

namespace Noox\Notifications;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Noox\Channels\FcmChannel;

class NewsCommentLiked extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * liked comment
     * @var \Noox\Models\NewsComment
     */
    protected $comment;

    /**
     * liker of the comment
     * @var \Noox\Models\User
     */
    protected $liker;

    /**
     * notification generation date
     * @var \Carbon\Carbon
     */
    protected $time;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($comment, $liker)
    {
        $this->time    = Carbon::now();
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
        'comment_id'         => $this->comment->id,
        'comment_content'    => $this->comment->content,
        'liker_id'           => $this->liker->id,
        'liker_name'         => $this->liker->name,
        'news_title'         => $this->comment->news->title,
        'news_category_name' => $this->comment->news->getCategoryName(),
        'time'               => $this->time,
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
        'title'   => 'Comment liked!',
        'body'    => "Your comment has been liked by {$this->liker->name}!",
        'payload' => $this->toArray($notifiable),
        ];
    }
}
