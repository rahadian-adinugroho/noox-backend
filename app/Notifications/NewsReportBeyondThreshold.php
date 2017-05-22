<?php

namespace Noox\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class NewsReportBeyondThreshold extends Notification
{
    use Queueable;

    protected $news;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($news)
    {
        $this->news = $news;
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
            'title'      => 'News report',
            'text'       => 'News "' . $this->news->title . '" total reports are beyond permitted threshold.',
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
            'title'      => 'News report',
            'text'       => 'News "' . $this->news->title . '" total reports are beyond permitted threshold.',
            'target_url' => 'cms/news/'. $this->news->id .'/reports', 
        ];
    }
}
