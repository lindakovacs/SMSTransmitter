<?php

namespace SMSTransmitter\Exceptions;

/**
 * Class MissingParametersException
 *
 * @package SMSTransmitter\Exceptions
 */
class MissingParametersException extends SMSTransmitterException
{
    
    /**
     * array containing the missing parameters
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
