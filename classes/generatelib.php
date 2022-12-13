<?php

/**
* @package      local_cronlogreader
* @author       Alex
* @license      http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
*/

class generate {

    /** Process the generate page form
    * @param string $filepath     
    * @param string $filename
    * @param object $fromform
    */

    public function process_form(object $fromform, string $filepath, string $filename)
    {
        global $DB, $CFG;

        if(@file_get_contents($filepath . $filename) === false){
            redirect($CFG->wwwroot . '/local/cronlogreader/generate.php', get_string('generation_fail_existence', 'local_cronlogreader'));  
        } else {
            $_SESSION['tasktime'] = $fromform->tasktime;
            $_SESSION['filepath'] = $fromform->filepath;
            $_SESSION['filename'] = $fromform->filename;

            $fileExtension = pathinfo($filename, PATHINFO_EXTENSION);
            if($fileExtension == "gz" || $fileExtension == "log"){   
                $updatedFileDetails = $this->get_file_details($fromform);  
                if($DB->record_exists('local_cronlogreader', array('id' => $updatedFileDetails->id))){
                    $this->update_file_details($fromform);
                } else {
                    $this->insert_file_details($fromform);
                }
                redirect($CFG->wwwroot . '/local/cronlogreader/reader.php', get_string('generation_success', 'local_cronlogreader'));
            } else {
                redirect($CFG->wwwroot . '/local/cronlogreader/generate.php', get_string('generation_fail_extension', 'local_cronlogreader'));  
            }
        }
    }

    /** Update file details generate from form
    * @param object $fromform
    * @return bool
    */

    private function update_file_details(object $fromform): bool{
        global $DB;
        $updatedFileDetails = $this->get_file_details($fromform);
        try {
            return $DB->update_record('local_cronlogreader', $updatedFileDetails);
        } catch (dml_exception $e){
            return false;
        }
    }

    /** Insert file details generate from form
    * @param object $fromform
    */

    private function insert_file_details(object $fromform){
        global $DB;
        $updatedFileDetails = $this->get_file_details($fromform);
        $DB->insert_record('local_cronlogreader', $updatedFileDetails);
    }

    /** Get file details generate from form
    * @param object $fromform
    */

    private function get_file_details(object $fromform){
        global $DB;

        $updatedFileDetails = new stdClass();
        $updatedFileDetails->id = $fromform->id;
        $updatedFileDetails->filepath = $fromform->filepath;
        $updatedFileDetails->filename = $fromform->filename;
        
        return $updatedFileDetails;
    }

    /** Get a single record
    * @param string $sql
    */

    public function get_record(string $sql){
        global $DB;
        try {
            return $DB->get_record_sql($sql);
        } catch (dml_exception $e){
            return false;
        }
    }
    
}