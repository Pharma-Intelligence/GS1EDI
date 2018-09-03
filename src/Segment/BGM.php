<?php
namespace PharmaIntelligence\GS1EDI\Segment;

class BGM extends Segment
{
    const TYPE_ORDER            = 220;
    const TYPE_PRIORITY_ORDER   = 224;
    const TYPE_CROSSDOCK        = 237;
    
    public $orderReference = null;
    public $orderType = null;
    
    protected function map() {
        $this->orderReference = $this->components['order_reference'];
        $this->orderType = $this->components['order_type'];
    }
}

