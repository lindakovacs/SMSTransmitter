<?php

namespace SMSTransmitter\Exceptions;

/**
 * Class LengthControlException
 *
 * @package SMSTransmitter\Exceptions
 */
class LengthControlException extends SMSTransmitterException
{
    /**
     * the length of the message
     * @var int
     */
    private $length;

    // Begin Getters & Setters
    public function setLength($length)
    {
        $this->length=$length;
    }

    public function getParameters()
    {
        return $this->length;
    }
    // End of Getters & Setters
}
