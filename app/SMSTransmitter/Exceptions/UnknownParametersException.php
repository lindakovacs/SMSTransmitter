<?php

namespace SMSTransmitter\Exceptions;

/**
 * Class UnknownParametersException
 *
 * @package SMSTransmitter\Exceptions
 */
class UnknownParametersException extends SMSTransmitterException
{
    /**
     * array containing the unknown parameters
     * @var array
     */
    private $parameters;

    // Begin Getters & Setters
    public function setParameters($parameters)
    {
        $this->parameters=$parameters;
    }

    public function getParameters()
    {
        return $this->parameters;
    }
    // End of Getters & Setters
}
