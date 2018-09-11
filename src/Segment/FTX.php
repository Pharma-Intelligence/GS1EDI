<?php
namespace PharmaIntelligence\GS1EDI\Segment;

class FTX extends Segment
{
    const PRINT_ON_LABEL                = 'X1';
    const PRINT_ON_DESADV               = 'X2';
    const PRINT_ON_LABEL_AND_DESADV     = 'X3';
    const PRINT_ON_INVOICE              = 'X4';
    const PRINT_ON_INVOICE_AND_DESADV   = 'X5';
    
    
    public $location = self::PRINT_ON_LABEL;
    public $content = '';
    
    protected function map() {
        $this->location = $this->getComponent('print_location', self::PRINT_ON_LABEL);
        $this->content = $this->getComponent('information_content', '');
    }
}

