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
    
    
    protected function map() {
        foreach($this->segments as $segment) {
            if($segment instanceof UNH) {
                $this->type = $segment->messageType;
                $this->version = $segment->messageVersion;
                $this->draft = $segment->messageDraft;
            } elseif($segment instanceof UNT) {
                $this->segmentControlCount = $segment->messageSegmentCount;
            } else {
                continue;
            }
        }
    }
}

