<?php
namespace PharmaIntelligence\GS1EDI\Segment;

class UNT extends Segment
{
    
    public $messageSegmentCount    = null;
    public $messageReferenceControl   = null;
    
    
    protected function map() {
        $this->messageReferenceControl = $this->components['message_reference_control'];
        $this->messageSegmentCount = $this->components['message_segment_count'];
    }
}

