<?php
namespace PharmaIntelligence\GS1EDI\Segment;

class QTY extends Segment
{
    const TYPE_DISCRETE     = 1;
    const TYPE_ORDERED      = 21;
    
    const MEASURE_PACKAGE   = 'PCE';
    const MEASURE_KILOGRAM  = 'KGM';
    const MEASURE_POUND     = 'PND';
    
    public $quantityType = null;
    public $quantity = null;
    public $measurement = self::MEASURE_PACKAGE;
    
    public $isDifferentMeasurement = false;
    
    protected function map() {
        $this->quantity = $this->getComponent('quantity', 0);
        $this->quantityType = $this->getComponent('quantity_type', self::TYPE_DISCRETE);
        $this->measurement = $this->getComponent('measurement_unit', self::MEASURE_PACKAGE);
        if($this->measurement !== self::MEASURE_PACKAGE)
            $this->isDifferentMeasurement = true;
    }
}

