<?php
namespace PharmaIntelligence\GS1EDI\Mapper;

use Symfony\Component\Yaml\Yaml;
use PharmaIntelligence\GS1EDI\Segment\Segment;

class EDIMapper
{
    protected $locations = [];
    protected $map = [];
    protected $structure = [];
    protected $position = 0;
    public function __construct(array $mappingLocations = array()) {
        $this->locations = array_merge([
            __DIR__.'/../../dictionaries/'
        ]);
    }
    
    protected function loadMap($messageTypeData) {
        $type = $messageTypeData[1];
        $version = $messageTypeData[2];
        $versionSub = $messageTypeData[3];
        $maps = [];
        foreach($this->locations as $location) {
            if(file_exists($location.'default.yml'))
                $maps[] = $location.'default.yml';
            if(is_dir($location.strtolower($type))) {
                if(file_exists($location.strtolower($type).'/default.yml'))
                    $maps[] = $location.strtolower($type).'/default.yml';
            }
        }
        foreach($maps as $map) {
            $map = Yaml::parseFile($map);
            
            $this->map = array_merge_recursive($this->map, $map['mapping']);
            if(array_key_exists('structure', $map)) {
                $this->structure = array_merge_recursive($this->structure, $map['structure']);
            }
        }
        
        $this->map = $this->map;
        
    }
    
    public function map(array $tree) {
        $this->loadMap($tree[0][3]);
        $mappedTree = [];
        foreach($tree as $segments) {
            $segmentType = $segments[1][1];
            $segment = [
                'type' => $segmentType
            ];
            foreach($segments as $elementCounter => $elements) {
                foreach($elements as $componentCounter => $component) {
                    if(isset($this->map[$segmentType][$elementCounter][$componentCounter])) {
                        $componentName = $this->map[$segmentType][$elementCounter][$componentCounter];
                    } elseif(isset($this->map['all'][$elementCounter][$componentCounter])) {
                        $componentName = $this->map['all'][$elementCounter][$componentCounter];
                    } else {
                        $componentName = $componentCounter;
                    }
                    
                    $segment[$componentName] = $component;
                }
            }
            $mappedTree[] = $segment;
        }
        $structure = $this->structure($mappedTree);
        return $structure;
    }
    
    protected function structure(array $mappedTree) {
        $structure = $this->buildStructureGroup($this->position, $this->structure, $mappedTree);
        return $structure;
    }
    
    protected function buildStructureGroup($position, $structureDefinition, $mappedTree) {
        $structure = [];
        while($this->position < count($mappedTree)) {
            $segment = $mappedTree[$this->position];
            $segmentType = $segment['type'];
            if(!in_array($segmentType, array_keys($structureDefinition))) {
                return $structure;
            }
            isset($structureDefinition[$segmentType]['seen'])?$structureDefinition[$segmentType]['seen']++:$structureDefinition[$segmentType]['seen'] = 1;
            if($structureDefinition[$segmentType]['seen'] > $structureDefinition[$segmentType]['max']) {
                return $structure;
            }
            
            if(isset($structureDefinition[$segmentType]['isGroup'])) {
                $structure[$structureDefinition[$segmentType]['groupName']][] = $this->buildStructureGroup($this->position, $structureDefinition[$segmentType]['children'], $mappedTree);
                continue;
            }
            if(class_exists(sprintf('PharmaIntelligence\GS1EDI\Segment\%s', $segmentType)))
                $class = sprintf('PharmaIntelligence\GS1EDI\Segment\%s', $segmentType);
            else 
                $class = 'PharmaIntelligence\GS1EDI\Segment\Segment';
                $structure[] = new $class($mappedTree[$this->position]);
            $this->position++;
        }
        
        return $structure;
        
    }
}

