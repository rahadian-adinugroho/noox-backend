<?php

namespace Noox\Notifications;

use Noox\Channels\FcmChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class NewsReportApprovedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * News object.
     * @var \Noox\Models\News
     */
    protected $news;

    /**
     * FCM token to send notification.
     * @var array
     */
    protected $fcmTokens;

    /**
     * Time when the notification was issued.
     * @var \Carbon\Carbon
     */
    protected $time;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($news, array $fcmTokens=null)
    {
        $this->news      = $news;
        $this->fcmTokens = $fcmTokens;
        $this->time      = \Carbon\Carbon::now();
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
            'news_title' => $this->news->title,
            'time'       => $this->time,
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
            'news_title' => $this->news->title,
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
        if (! is_null($this->fcmTokens)) {
            $fcmTokens = $this->fcmTokens;
        } else {
            $fcmTokens = $notifiable->fcmTokens()->pluck('token')->toArray();
        }
        
        return [
        'to'      => $fcmTokens,
        'title'   => 'News report approved!',
        'body'    => "Your report on news '{$this->news->title}' has been approved!",
        'payload' => $this->toArray($notifiable),
        ];
    }
}
