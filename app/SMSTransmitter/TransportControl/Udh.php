<?php
namespace SMSTransmitter\TransportControl;

use SMSTransmitter\Config\Config as Config;

/**
 * For full UDH specifications, check https://en.wikipedia.org/wiki/Concatenated_SMS
 * 
 * Field 1 (1 octet): Length of User Data Header, in this case 05.
 * Field 2 (1 octet): Information Element Identifier, equal to 00 (Concatenated short messages, 8-bit reference number)
 * Field 3 (1 octet): Length of the header, excluding the first two fields; equal to 03
 * Field 4 (1 octet): 00-FF, CSMS reference number, must be same for all the SMS parts in the CSMS
 * Field 5 (1 octet): 00-FF, total number of parts. The value shall remain constant for every short message which makes up the concatenated short message. If the value is zero then the receiving entity shall ignore the whole information element
 * Field 6 (1 octet): 00-FF, this part's number in the sequence. The value shall start at 1 and increment for every short message which makes up the concatenated short message. If the value is zero or greater than the value in Field 5 then the receiving entity shall ignore the whole information element. [ETSI Specification: GSM 03.40 Version 5.3.0: July 1996]
 */

/**
 * Class Udh
 * @package SMSTransmitter\TransportControl
 */
class Udh
{
    const UDH_LENGTH="05";
    const UDH_IEI="00";
    const UDH_SMALL_LENGTH="03";

    /**
     * @var int
     */
    private $csms;

    /**
     * @var int
     */
    private $total;

    /**
     * @var int
     */
    private $ordinal;

    /**
     * @param int $csms    
     * @param int $total   
     * @param int $ordinal 
     */
    public function __toString()
    {
        return $this->getAssembled();
    }
    public function __construct($csms=null, $total=null, $ordinal=null)
    {
        if ($csms!=null) {
            $this->setCSMS($csms);
        }
        
        if ($total!=null) {
            $this->setTotal($total);
        }
        
        if ($ordinal!=null) {
            $this->setOrdinal($ordinal);
        }
    }

    /**
     * return an array containing the UDH compenents (toArray)
     * @return string 
     */
    public function getAssembled()
    {
        return
             self::UDH_LENGTH
            .self::UDH_IEI
            .self::UDH_SMALL_LENGTH
            .$this->csms
            ."0".$this->total
            ."0".$this->ordinal
            ;
    }
    
    // Getters & Setters (pseudo)
    public function getCSMS()
    {
        return $this->csms;
    }

    public function setCSMS($csms)
    {
        $this->csms=$csms;
        return $this;
    }

    public function getTotal()
    {
        return $this->$total;
    }

    public function setTotal($total)
    {
        $this->total=$total;
        return $this;
    }

    public function getOrdinal()
    {
        return $this->$ordinal;
    }

    public function setOrdinal($ordinal)
    {
        $this->ordinal=$ordinal;
        return $this;
    }

    // End of Getters & Setters
}
