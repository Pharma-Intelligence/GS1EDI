<?php
namespace PharmaIntelligence\GS1EDI\Segment;

class LIN extends Segment
{
    public $lineNumber = 0;
    public $gtin = null;
    public $hasGtin = false;
    
    
    const IDENTIFICATION_GTIN = 'SRV';
    const IDENTIFICATION_EAN = 'EN';
    
    protected function map() {
        $this->lineNumber = $this->getComponent('line_number', -1);
        if($this->getComponent('identification_type', '') == self::IDENTIFICATION_EAN) {
            $this->gtin = $this->getComponent('product_identification', null);
            $this->hasGtin = true;
        }
        if($this->getComponent('identification_type', '') == self::IDENTIFICATION_GTIN) {
            $this->gtin = $this->getComponent('product_identification', null);
            $this->hasGtin = true;
        }
    }
}

