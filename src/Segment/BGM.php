<?php
namespace PharmaIntelligence\GS1EDI\Segment;

class BGM extends Segment
{
    const TYPE_ORDER            = 220;
    const TYPE_PRIORITY_ORDER   = 224;
    const TYPE_ORDER_RESPONSE   = 231;
    const TYPE_CROSSDOCK        = 237;
    
    const FUNCTION_CHANGED      = 4;
    const FUNCTION_REPLACE      = 5;
    const FUNCTION_ORIGINAL     = 9;
    const FUNCTION_NOT_ACCEPTED = 27;
    const FUNCTION_ACCEPTED     = 29;
    
    public $documentIdentification = null;
    public $documentNameCode = null;
    public $function = null;
    
    protected function map() {
        $this->documentIdentification = $this->components['document_identification'];
        $this->documentNameCode = $this->components['document_name_code'];
        $this->function = $this->getComponent('message_function', self::FUNCTION_ORIGINAL);
    }
}

