<?php
namespace PharmaIntelligence\GS1EDI;
use PharmaIntelligence\GS1EDI\Segment\SegmentGroup;
use PharmaIntelligence\GS1EDI\Segment\UNH;
use PharmaIntelligence\GS1EDI\Segment\UNT;

class Message extends SegmentGroup
{
    public $type = null;
    public $draft = null;
    public $version = null;
    public $segmentControlCount = 0;
    public $messageReference = null;
    public $messageReferenceControl = null;
    
    public function isValid() {
        if($this->segmentControlCount !== $this->count())
            return false;
        if(is_null($this->messageReference) || is_null($this->messageReferenceControl) || $this->messageReference !== $this->messageReferenceControl)
            return false;
        return true;
    }
    
    protected function map() {
        foreach($this->segments as $segment) {
            if($segment instanceof UNH) {
                $this->type = $segment->messageType;
                $this->version = $segment->messageVersion;
                $this->draft = $segment->messageDraft;
                $this->messageReference = $segment->getComponent('message_reference', null);
            } elseif($segment instanceof UNT) {
                $this->segmentControlCount = intval($segment->messageSegmentCount);
                $this->messageReferenceControl = $segment->getComponent('message_reference_control', null);
            } else {
                continue;
            }
        }
    }
}

