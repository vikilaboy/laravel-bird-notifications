<?php

namespace NotificationChannels\Bird\Exceptions;

use Exception;

class InvalidRecipient extends Exception
{
    public function __construct($message = 'Invalid recipient provided', $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
