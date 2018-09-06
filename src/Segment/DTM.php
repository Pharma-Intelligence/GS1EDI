<?php
namespace PharmaIntelligence\GS1EDI\Segment;

class DTM extends Segment
{
    const INDICATOR_DATETIME_US = 203;
    const INDICATOR_DATE_US     = 102;
    
    const FORMAT_DATETIME_US    = 'Ymdhi';
    const FORMAT_DATE_US        = 'Ymd';
    
    const TYPE_ORDER_DATE               = 137;
    const TYPE_REQUESTED_DELIVERY_DATE  = 2;
    const TYPE_EARLIEST_DELIVERY_DATE   = 64;
    const TYPE_LATEST_DELIVERY_DATE     = 63;
    const TYPE_PLANNED_DELIVERY_DATE    = 76;
    
    public $dateType = null;
    public $date = null;
    
    protected function map() {
        $this->dateType = $this->components['date_type'];
        switch($this->components['date_format']) {
            case self::INDICATOR_DATE_US:
                $this->date = \DateTime::createFromFormat(self::FORMAT_DATE_US, $this->components['date_content']);
                break;
        }        
    }
}

