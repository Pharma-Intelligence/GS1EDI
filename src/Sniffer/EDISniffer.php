<?php
namespace PharmaIntelligence\GS1EDI\Sniffer;

class EDISniffer
{
    public static function isFileEDI($filename) {
        if(!file_exists($filename))
            throw new \InvalidArgumentException(sprintf('File %s does not exist', $filename));
        // Read first line, or first 128 bytes, to prevent memory bloat
        $fh = fopen($filename, 'r');
        $line = fgets($fh, 128);
        return self::isEDI($line);
    }
    
    public static function isEDI($string) {
        // Strip whitespace for UNA check
        $string = trim($string);

        // If we start with UNA this must be EDI 
        if(substr($string, 0, 3) == 'UNA')
            return true;
        
        // UNA is optional, but then separators are standard and we can check for transmission or message headers
        if(substr($string, 0, 4) == 'UNB+' || substr($string, 0, 4) == 'UNH+')
            return true;
        
        return false;
    }
}

