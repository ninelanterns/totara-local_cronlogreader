<?php

/**
* @package      local_cronlogreader
* @author       Alex
* @license      http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
*/

use stdClass;

class paths {

    /** Get all available directories
    * @param string $directory     
    * @return array
    */

    public function get_all_directories(string $directory): array
    {
        // Get all directories with .gz files in them
        $paths = [];
        $Directory = new RecursiveDirectoryIterator($directory);
        foreach(new RecursiveIteratorIterator($Directory) as $filename => $file){
            if(pathinfo($filename, PATHINFO_EXTENSION) == 'gz' || pathinfo($filename, PATHINFO_EXTENSION) == 'log'){
                //echo $filename . ' - ' . $file->getSize() . ' bytes <br/>';
                array_push($paths, $filename);
            }
        }
        
        return $paths;
    }

}