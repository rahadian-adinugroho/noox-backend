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
        }
        \Log::debug($data);
    }
}