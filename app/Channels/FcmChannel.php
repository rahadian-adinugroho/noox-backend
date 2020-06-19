<?php

namespace Noox\Channels;

use Noox\Models\FcmToken;
use Illuminate\Notifications\Notification;
use LaravelFCM\Message\OptionsBuilder;
use LaravelFCM\Message\PayloadDataBuilder;
use LaravelFCM\Message\PayloadNotificationBuilder;
use LaravelFCM\Message\Topics;
use FCM;

class FcmChannel
{
    /**
     * Send the given notification.
     *
     * @param  mixed  $notifiable
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return void
     */
    public function send($notifiable, Notification $notification)
    {
        $settings = $notification->toFcm($notifiable);

        if (! $this->isSettingsValid($settings, $notifiable, $notification)) {
            return;
        }

        $dataBuilder = new PayloadDataBuilder();
        $dataBuilder->addData(['id' => $notification->id, 'type' => get_class($notification)]);
        if (isset($settings['payload'])) {
            $dataBuilder->addData($settings['payload']);
        }
        $data = $dataBuilder->build();

        $notificationBuilder = new PayloadNotificationBuilder($settings['title']);
        $notificationBuilder->setBody($settings['body']);
        $notificationBuilder->setSound('default');

        if (isset($settings['sound'])) {
            $notificationBuilder->setSound($settings['sound']);
        }

        $fcmNotification = $notificationBuilder->build();

        $optionBuilder = new OptionsBuilder;
        $optionBuilder->setTimeToLive(60*60*24);
        $option = $optionBuilder->build();

        if ($settings['to'] instanceof Topics) {
            $topicResponse = FCM::sendToTopic($settings['to'], $option, $fcmNotification, $data);
        } else {
            $downstreamResponse = FCM::sendTo($settings['to'], $option, $fcmNotification, $data);
            if (count($downstreamResponse->tokensToDelete()) > 0) {
                FcmToken::whereIn('token', $downstreamResponse->tokensToDelete())->delete();
            }
        }
    }

    /**
     * Check the validity of the notification object.
     *
     * @param  array  $settings
     * @param  \Illuminate\Notifications\Notifiable  $notifiable
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return boolean
     */
    protected function isSettingsValid($settings, $notifiable, $notification)
    {
        if (! is_array($settings['to']) || (count($settings['to']) < 1)) {
            \Log::warning("User ({$notifiable->id}) has no FCM token.");
            return false;
        }
        if (! isset($settings['title']) || ! isset($settings['body'])) {
            \Log::error("FcmChannel: Notification {get_class($notification)} are missing title or body.");
            return false;
        }
        return true;
    }
}