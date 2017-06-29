<?php
namespace SMSTransmitter\Config;

/**
 * Class Config
 * @package SMSTransmitter\Config 
 */
class Config
{
    const MESSAGEBIRD_API_KEY = "YOUR_API_KEY";

    const MAX_REQUEST_RATE = 1;

    const MAX_SMS_LENGTH = 160 ;

    
    /**
     * 
     * @return array 
     */
    public static function getAllowedQueryMethods()
    {
        return ['POST'];
    }

    /**
     * 
     * @return array
     */
    public static function getAllowedQueryParameters()
    {
        return ['recipient','originator','body'];
    }
}
