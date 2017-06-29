<?php
namespace SMSTransmitter\TransportControl;

use SMSTransmitter\Config\Config as Config;
use SMSTransmitter\TransportControl\Udh;
use SMSTransmitter\TransportControl\PartialMessage;
use SMSTransmitter\Exceptions\LengthControlException;

/**
 * Class LengthControl
 * @package SMSTransmitter\TransportControl
 */
class LengthControl
{
        
    /**
     * 
     * @param  string  $message 
     * @return boolean          
     */
    public function isTooLong($message)
    {
        return (strlen($message)>Config::MAX_SMS_LENGTH);
    }

    /**
     * 
     * @param  string $message 
     * @return array of SMSTransmitter\TransportControl\PartialMessage
     */
    public function split($message)
    {
        $length=strlen($message);
        if (!$this->isTooLong($message)) {
            $e = new LengthControlException("This message is too short to be split");
            $e->setLength($length);
            throw ($e);
        } else {
            $quotient = (int)($length / Config::MAX_SMS_LENGTH);
            $reminder = $length % Config::MAX_SMS_LENGTH ;

            $total=$quotient +($reminder==0?0:1);
            
            // generate a random CSMS
            $csms=(string)dechex(rand(0x00, 0xff));
            $csms=strlen($csms)==1?"0".$csms:$csms;

            $result=[];
            for ($ordinal=1;$ordinal<=$total;$ordinal++) {
                $udh = new Udh($csms, $total, $ordinal);
                $start=($ordinal-1)*Config::MAX_SMS_LENGTH;

                // If last counter and has reminder, just get the last (reminder) characters of the message
                $length=($ordinal==$total && $reminder!=0)?$reminder:Config::MAX_SMS_LENGTH;

                $content=substr($message, $start, $length);
                $partialMessage = new PartialMessage($content, $udh);
                $result[]=$partialMessage;
            }
        }

        return $result;
    }
}
