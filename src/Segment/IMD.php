<?php
namespace PharmaIntelligence\GS1EDI\Segment;

class IMD extends Segment
{
    const DESCRIPTION_ARTICLE_NAME   = 'ANM';
    const DESCRIPTION_ARTICLE_TYPE   = 'TPE';
    const DESCRIPTION_ARTICLE_DESC   = 'DSC';
    
    public $description = '';
    public $descriptionType = '';
    
    protected function map() {
        $this->descriptionType = $this->components['description_type'];
        $this->description = $this->components['description'];
        
    }
}

