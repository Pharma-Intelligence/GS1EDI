<?php
namespace PharmaIntelligence\GS1EDI\Segment;

class UNH extends Segment
{
    
    public $messageType    = null;
    public $messageDraft   = null;
    public $messageVersion = null;
    
    protected function map() {
        $this->messageDraft = $this->components['message_draft'];
        $this->messageType = $this->components['message_type'];
        $this->messageVersion = $this->components['message_version'];
    }
}

