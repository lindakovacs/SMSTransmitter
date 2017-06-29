<?php
namespace SMSTransmitter\Validator;

use SMSTransmitter\Config\Config;
use SMSTransmitter\Exceptions\RequestMethodException;
use SMSTransmitter\Exceptions\UnallowedParametersException;
use SMSTransmitter\Exceptions\MissingParametersException;
use SMSTransmitter\Exceptions\EmptyParametersException;

/**
 * Class RequestValidator
 * @package SMSTransmitter\Validator
 */
class RequestValidator
{
    /**
     * 
     * @param  string $method
     * @return void 
     * @throws  RequestMethodException
     */
    private function validateMethod($method)
    {
        if (!in_array($method, Config::getAllowedQueryMethods())) {
            $e = new RequestMethodException();
            $e->setMethod($method);
            $e->setMessage("$request_method not allowed.");
            throw $e;
        }
    }

    /**
     * 
     * @param  array $parameters
     * @return void 
     * @throws  MissingParametersException
     */
    private function checkMissingParameters($parameters)
    {
        $parameterNames=array_keys($parameters);
        $missingParameterNames=array_diff(Config::getAllowedQueryParameters(), $parameterNames);

        if (!empty($missingParameterNames)) {
            $errorMessage=implode(", ", $missingParameterNames)." parameter(s) is(are) missing.";
            $e = new MissingParametersException();
            $e->setParameters($missingParameterNames);
            $e->setMessage($errorMessage);
            throw $e;
        }
    }

    /**
     * 
     * @param  array $parameters
     * @return void 
     * @throws  UnallowedParametersException
     */
    private function checkUnallowedParameters($parameters)
    {
        $parameterNames=array_keys($parameters);
        $unallowedParameterNames=array_diff($parameterNames, Config::getAllowedQueryParameters());
        if (!empty($unallowedParameterNames)) {
            $errorMessage=implode(", ", $unallowedParameterNames)." parameter(s) is(are) not allowed.";
            $e = new UnallowedParametersException();
            $e->setParameters($unallowedParameterNames);
            $e->setMessage($errorMessage);
            throw $e;
        }
    }

    /**
     * 
     * @param  array $parameters
     * @return void 
     * @throws  EmptyParametersException
     */
    private function validateContent($parameters)
    {
        $emptyParameters=[];
        foreach ($parameters as $key=>$val) {
            if (empty($val)) {
                $emptyParameters[]=$key;
            }
        }
        if (!empty($emptyParameters)) {
            $errorMessage="Parameter(s): ".implode(", ", $emptyParameters)." cannot be empty.";
            $e = new EmptyParametersException();
            $e->setParameters($emptyParameters);
            $e->setMessage($errorMessage);
            throw $e;
        }
    }


    /**
     * 
     * @param  array $parameters
     * @param  string $method
     * @return void 
     */
    public function validate($method, $parameters)
    {
        $this->validateMethod($method);
        $this->checkMissingParameters($parameters);
        $this->checkUnallowedParameters($parameters);
        $this->validateContent($parameters);
    }
}
