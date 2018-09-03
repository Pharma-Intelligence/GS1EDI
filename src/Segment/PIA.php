<?php
namespace PharmaIntelligence\GS1EDI\Segment;

class PIA extends Segment
{
    const TYPE_ZINDEX       = 'GD';
    const TYPE_HIBC         = 'HI';
    const TYPE_SUPPLIER     = 'SA';
    
    public $hibc    = null;
    public $zindex  = null;
    public $supplierArticleNumber = null;
    
    protected function map() {
        if($this->components['identification_org'] == self::TYPE_HIBC) {        
            $this->hibc = $this->components['identification'];
        }
        if($this->components['identification_org'] == self::TYPE_ZINDEX) {
            $this->zindex = $this->components['identification'];
        }
        if($this->components['identification_org'] == self::TYPE_SUPPLIER) {
            $this->supplierArticleNumber = $this->components['identification'];
        }
    }
}

