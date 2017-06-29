<?php
namespace SMSTransmitter\Exceptions;

/**
 * Class SMSTransmitterException
 *
 * @package SMSTransmitter\Exceptions
 */
abstract class SMSTransmitterException extends \Exception
{
    // Allow override
    protected $message;

    public function setMessage($message)
    {
        $this->message=$message;
    }
}
