<?php
namespace PharmaIntelligence\GS1EDI\Segment;

class PIA extends Segment
{
    const TYPE_ZINDEX       = 'GD';
    const TYPE_HIBC         = 'HI';
    const TYPE_SUPPLIER     = 'SA';
    const TYPE_GTIN         = 'SRV';
    
    const TYPE_ORDERED_PRODUCT   = 5;
    const TYPE_REPLACEMENT       = 4;
    
    public $hibc    = null;
    public $zindex  = null;
    public $gtin    = null;
    public $supplierArticleNumber = null;
    public $identifcationType = null;
    
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
        if($this->components['identification_org'] == self::TYPE_GTIN) {
            $this->gtin = $this->components['identification'];
        }
        $this->identifcationType = $this->components['identification_type'];
    }
}

