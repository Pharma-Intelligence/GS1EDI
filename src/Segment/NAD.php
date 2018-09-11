<?php
namespace PharmaIntelligence\GS1EDI\Segment;

class NAD extends Segment
{
    public $isGLN = false;
    public $gln = null;
    
    public $addressType = null;
    
    const LOCATION_NUMBER_ORG_EAN   = 9;
    
    const TYPE_SUPPLIER             = 'SU';
    const TYPE_BUYER                = 'BY';
    const TYPE_INVOICE              = 'IV';
    const TYPE_FINAL_DESTINATION    = 'UC';
    const TYPE_DELIVERY             = 'DP';
    
    protected function map() {
        $this->addressType = $this->getComponent('adress_type', null);
        if(isset($this->components['location_number']) && intval($this->components['location_number_org']) === self::LOCATION_NUMBER_ORG_EAN) {        
            $this->isGLN = true;
            $this->gln = $this->components['location_number'];
        }
    }
}

