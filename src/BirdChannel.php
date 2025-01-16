<?php

namespace NotificationChannels\Bird;

use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Notifications\Events\NotificationFailed;
use Illuminate\Notifications\Notification;
use NotificationChannels\Messagebird\Exceptions\CouldNotSendNotification;

class BirdChannel
{

    public function __construct(public BirdClient $client, protected Dispatcher $dispatcher)
    {
    }

    /**
     * Send the given notification.
     *
     * @param mixed $notifiable
     * @param \Illuminate\Notifications\Notification $notification
     *
     * @return object with response body data if succesful response from API | empty array if not
     * @throws \NotificationChannels\MessageBird\Exceptions\CouldNotSendNotification
     */
    public function send($notifiable, Notification $notification)
    {
        $message = $notification->toMessagebird($notifiable);

        $data = [];

        if (is_string($message)) {
            $message = new BirdMessage($message);
        }

        if ($to = $notifiable->routeNotificationFor('bird')) {
            $message->setRecipients([$to]);
        }

        try {
            $data = $this->client->send($message);
            $this->dispatcher->dispatch('BirdSms', [$notifiable, $notification, $data]);

            return $data;
        } catch (CouldNotSendNotification $couldNotSendNotification) {
            $this->dispatcher->dispatch(
                new NotificationFailed(
                    $notifiable,
                    $notification,
                    'BirdSmsException',
                    [
                        'message' => $couldNotSendNotification->getMessage(),
                        'exception' => $couldNotSendNotification->getTraceAsString(),
                        'code' => $couldNotSendNotification->getCode(),
                    ]
                )
            );
        }

        return $data;
    }
}
