<?php

namespace NotificationChannels\Bird\Exceptions;

use Exception;

class CouldNotSendNotification extends Exception
{
    public function __construct(Exception $exception)
    {
        parent::__construct(
            sprintf('Bird service responded with an error: %s:%s', $exception->getCode(), $exception->getMessage()),
            $exception->getCode()
        );
    }
}
