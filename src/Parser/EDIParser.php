<?php
namespace PharmaIntelligence\GS1EDI\Parser;

use PharmaIntelligence\GS1EDI\Mapper\EDIMapper;
use PharmaIntelligence\GS1EDI\Message;

class EDIParser
{
    const STATE_SEGMENT     = 1;
    const STATE_ELEMENT     = 2;
    const STATE_COMPONENT   = 3;
    const STATE_TEXT        = 4;
    
    const CONTROL_SEGMENT       = 'UNA';
    const CONTROL_MESSAGE_START = 'UNH';
    const CONTROL_MESSAGE_END   = 'UNT';
    
    protected $parserState = self::STATE_SEGMENT;
    
    protected $escapeNextChar = false;
    
    protected $controlChars = [
        'component' => ':',
        'element' => '+',
        'decimal' => '.',
        'escape' => '?',
        'space' => ' ',
        'segment' => "'"
    ];
    
    protected $characterArray = [];

    /**
     * @param $ediString
     * @return Message[]
     */
    public function load($ediString) {
        $ediString = trim($ediString);
        if(substr($ediString, 0, 3) == self::CONTROL_SEGMENT) {
            $escapeCharacters = str_split(substr($ediString, 3, 6));
            $this->controlChars = [
                'component' => $escapeCharacters[0],
                'element' => $escapeCharacters[1],
                'decimal' => $escapeCharacters[2],
                'escape' => $escapeCharacters[3],
                'space' => $escapeCharacters[4],
                'segment' => $escapeCharacters[5]
            ];
            $this->characterArray = str_split(trim(substr($ediString, 9)));
        } else {
            $this->characterArray = str_split($ediString);
        }
        
        $tokens = $this->tokenize();
     
        $tree = $this->parse($tokens);
        $map = $this->map($tree);
        return $map;
    }
    
    protected function isControlCharacter($char) {
        return in_array($char, [
            $this->controlChars['element'],
            $this->controlChars['segment'],
            $this->controlChars['component'],
        ]);
    }
    
    protected function map(array $tree) {
        $mappedMessages = []; 
        $messages = [];
        $curMessage = [];
        foreach($tree as $segment) {
            if($segment[1][1] === self::CONTROL_MESSAGE_START) {
                $curMessage = [];
            }
            $curMessage[] = $segment;
            if($segment[1][1] === self::CONTROL_MESSAGE_END) {
                $messages[] = $curMessage;
            }
        }
        $mapper = new EDIMapper();
        foreach($messages as $message) {
            $mappedMessages[] = $mapper->map($message);
        }
        return $mappedMessages;
    }
    
    protected function parse(array $tokens) {
        $segmentCounter = $elementCounter = $componentCounter = 0;
        $tree = [];
        foreach($tokens as $token) {
            if($token['type'] === self::STATE_SEGMENT) {
                $segmentCounter++;
                $elementCounter = $componentCounter = 0;
            } elseif($token['type'] === self::STATE_ELEMENT) {
                $elementCounter++;
                $componentCounter = 0;
            }  elseif($token['type'] === self::STATE_COMPONENT) {
                $componentCounter++;
            } elseif($token['type'] === self::STATE_TEXT) {
                $tree[$segmentCounter][$elementCounter][$componentCounter] = $token['content'];
            }
        }
        return $tree;
        
    }
    
    protected function tokenize() {
        $position = 0;
        $tokens = [
            ['type' => $this->parserState]
        ];
        $curToken = '';
        while($position < count($this->characterArray)) {
            $char = $this->characterArray[$position];
            if($char === "\n" || $char === "\r") {
                $position++;
                continue;
            }
            if(!$this->escapeNextChar && $this->isControlCharacter($char)) {
                if($this->parserState == self::STATE_TEXT) {
                    $tokens[] = [
                        'type' => $this->parserState,
                        'content' => $curToken
                    ];
                }
                $curToken = '';
                $this->escapeNextChar = false;
                if($char === $this->controlChars['component']) {
                    // If the first component of an element is empty, add this to the tokenlist
                    if($this->parserState === self::STATE_ELEMENT) {
                        $tokens[] = ['type' => self::STATE_COMPONENT];
                    }
                    $this->parserState = self::STATE_COMPONENT;
                } 
                if($char === $this->controlChars['segment']) {
                    $this->parserState = self::STATE_SEGMENT;
                }
                if($char === $this->controlChars['element']) {
                    $this->parserState = self::STATE_ELEMENT;
                }
                $tokens[] = ['type' => $this->parserState];
            } elseif($char == $this->controlChars['escape']) {
                $this->escapeNextChar = true;
            } else {
                if($this->parserState === self::STATE_SEGMENT) {
                    $tokens[] = ['type' => self::STATE_ELEMENT];
                    $this->parserState = self::STATE_ELEMENT;
                } 
                if($this->parserState === self::STATE_ELEMENT) {
                    $tokens[] = ['type' => self::STATE_COMPONENT];
                    $this->parserState = self::STATE_COMPONENT;
                } 
                    
                $this->parserState = self::STATE_TEXT;
                $curToken .= $char;
                $this->escapeNextChar = false;
            }
            $position++;
        }
        
        return $tokens;
    }
}

