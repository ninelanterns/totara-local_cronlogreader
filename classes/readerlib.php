<?php

/**
* @package      local_cronlogreader
* @author       Alex
* @license      http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
*/

class reader {

    /** Get amount of errors in the cron log file
    * @param string $file
    * @return int     
    */

    public function get_errors(string $file): int
    {
        $errors = explode(" ", strtolower($file));
        $errorCount = 0;
        foreach($errors as $error){
            // If "error" or "Error" exist in the log file
            if(strpos(strip_tags($error), "error") === 0){
                $errorCount++;
            }
        }   

        return $errorCount;
    }

    /** Get file based on file extension
    * @return string   
    */

    public function get_file(): string
    {
        $fileExtension = pathinfo($_SESSION['filename'], PATHINFO_EXTENSION);
        if($fileExtension == 'gz'){
            $file = gzdecode(file_get_contents($_SESSION['filepath'] . $_SESSION['filename']));
        } else if($fileExtension == 'log'){
            $file = file_get_contents($_SESSION['filepath'] . $_SESSION['filename']);
        }

        return $file;
    }

    /** Get the time-flagged tasks.
    * @param string $file 
    * @return array  
    */

    public function get_time_flagged_tasks(string $file): array
    {
        // Get tasks that, given an amount of time, use at least that amount of time to complete
        // $matches[0] because there are other arrays inside $matches
        // There are two matches per task in a Moodle cronlog using this pattern
        // One is the task itself, the other is the time taken in seconds
        // We want to merge each task's match into one array
            // Only if the time taken in seconds is more than an input time
        // So that we can output them together when we want to
        // E.g., we want the following for each task: ["Execute scheduled task: Cleanup old sessions (core\\task\\session_cleanup_task)\n","used 0.22468209266663 seconds"]
        $taskTime = $_SESSION['tasktime'];
        $longTaskCount = 0;
        $master = [];
        if($taskTime > 0){
            $pattern = "/Execute scheduled task:\s*(.*)\s*|used\s*(.*)\s*seconds/i";
            preg_match_all($pattern, $file, $matches);
            $sub = array();
            for($i = 1; $i < count($matches[0]); $i++){
                array_push($sub, $matches[0][$i-1]);
                if(($i % 2 === 0) && ($i > 0)){
                    // Explode "used x.xxxxxx seconds" 
                    $seconds = explode(" ", $sub[1]);
                    // There are three strings, get the middle string
                    $seconds = floatval($seconds[1]);
                    // Push task, only if time taken >= input time
                    if($seconds >= floatval($taskTime)){
                        $finalTask = implode(" ", $sub);
                        array_push($master, $finalTask);
                        $longTaskCount++;
                    }
                    $sub = array();
                }
            }
        }

        return array($master, $longTaskCount);
    }

}