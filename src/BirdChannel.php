<?php

namespace NotificationChannels\BIrd;

use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Notifications\Events\NotificationFailed;
use Illuminate\Notifications\Notification;
use NotificationChannels\Messagebird\Exceptions\CouldNotSendNotification;

class BirdChannel
{
    private Dispatcher $dispatcher;

    public function __construct(public BirdClient $client, Dispatcher $dispatcher = null)
    {
        $this->dispatcher = $dispatcher;
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
            $message->setRecipients($to);
        }

        try {
            $data = $this->client->send($message);

            if ($this->dispatcher !== null) {
                $this->dispatcher->dispatch('bird-sms', [$notifiable, $notification, $data]);
            }
        } catch (CouldNotSendNotification $e) {
            if ($this->dispatcher !== null) {
                $this->dispatcher->dispatch(
                    new NotificationFailed(
                        $notifiable,
                        $notification,
                        'bird-sms',
                        $e->getMessage()
                    )
                );
            }
        }

        return $data;
    }
}
