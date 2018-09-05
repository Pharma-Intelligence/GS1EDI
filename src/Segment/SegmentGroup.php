<?php
namespace PharmaIntelligence\GS1EDI\Segment;

class SegmentGroup
{
    
    protected $segments = [];
    public $groupName = '';
    
    public function __construct(array $segments = [], $groupName = '') {
        $this->segments = $segments;
        $this->groupName = $groupName;
        $this->map();
    }
  
    protected function map() {
        
    }
    
    public function addSegment(Segment $segment) {
        $this->segments[] = $segment;
        $this->map();
    }
    
    public function __count() {
        $count = 0;
        foreach($this->segments as $segment) {
            if($segment instanceof SegmentGroup) {
                $count += count($segment);
            } else {
                $count++;
            }
        }
        return $count;
    }
    
}

