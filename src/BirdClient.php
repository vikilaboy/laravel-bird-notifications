<?php

namespace NotificationChannels\Bird;

use Exception;
use Illuminate\Support\Facades\Http;
use NotificationChannels\Bird\Exceptions\CouldNotSendNotification;
use NotificationChannels\Bird\Exceptions\InvalidRecipient;
use Illuminate\Notifications\Notification;

class BirdClient
{
    public function __construct(
        protected string $accessKey,
        public string $originator,
        public string $workspace,
        public string $channel
    ) {
    }

    protected function buildApiUrl(): string
    {
        return sprintf('https://api.bird.com/workspaces/%s/channels/%s/messages', $this->workspace, $this->channel);
    }

    /**
     * @throws Exception
     */
    public function send(BirdMessage $message)
    {
        if ($message->doesntHaveOriginator()) {
            $message->setOriginator($this->originator);
        }

        if ($message->doesntHaveRecipients()) {
            throw  new InvalidRecipient();
        }

        if (empty($message->datacoding)) {
            $message->setDatacoding('auto');
        }

        $message->generateReference();

        try {
            $response = Http::withHeader('Authorization', 'AccessKey '.$this->accessKey)
                ->post($this->buildApiUrl(), $message->toArray());

            return $response->json();
        } catch (Exception $exception) {
            throw new CouldNotSendNotification($exception);
        }
    }
}
