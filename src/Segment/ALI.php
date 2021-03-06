<?php
namespace PharmaIntelligence\GS1EDI\Segment;

class ALI extends Segment
{
    
    const PARTIAL_DELIVERY_PROHIBITED   = 'X1';
    const PARTIAL_DELIVERY_ALLOWED      = 'X2';
    const PACKAGE_SEPERATELY            = 'SEP';
    
    
    
    protected function map() {
        $this->partialDeliveryAllowed = ($this->components['code_scenario'] == self::PARTIAL_DELIVERY_ALLOWED);
        
    }
}

