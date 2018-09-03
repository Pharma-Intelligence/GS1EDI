<?php
namespace PharmaIntelligence\GS1EDI\Segment;

class Segment
{
	protected $components = [];
	public $type = null;
	
    public function __construct(array $components) {
        $this->type = $components['type'];
        $this->components = $components;
        $this->map();
    } 
    
    protected function map() {
        
    }
    
    public function getComponent($name) {
        if(!array_key_exists($name, $this->components))
            return null;
        return $this->components[$name];
    }
}

