<?php
namespace PharmaIntelligence\GS1EDI\Segment;

class RFF extends Segment
{
    const TYPE_CONTRACT_NUMBER  = 'CT';
    const TYPE_ORDER_NUMBER     = 'ON';
    
    
    public $reference = null;
    public $referenceType = null;
    public $subReference = null;
    
    protected function map() {
        $this->reference = $this->components['reference'];
        $this->subReference = $this->getComponent('sub_reference', null);
        $this->referenceType = $this->components['reference_type'];
    }
}

