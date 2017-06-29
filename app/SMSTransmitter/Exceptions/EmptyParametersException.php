<?php

namespace SMSTransmitter\Exceptions;

/**
 * Class EmptyParametersException
 *
 * @package SMSTransmitter\Exceptions
 */
class EmptyParametersException extends SMSTransmitterException
{
    
    /**
     * array containing the empty parameters
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
