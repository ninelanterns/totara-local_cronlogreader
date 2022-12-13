<?php
/**
* @package      local_cronlogreader
* @author       Alex
* @license      http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
*/

namespace local_cronlogreader\form;
use moodleform;

//moodleform is defined in formslib.php
require_once("$CFG->libdir/formslib.php");

class cronlogreader extends moodleform {
    public function definition(){
        $mform = $this->_form;

        $mform->addElement('hidden', 'id');
        $mform->setType('id', PARAM_INT);

        $mform->addElement('text', 'filepath', get_string('file_path_text', 'local_cronlogreader'));
        $mform->setType('filepath', PARAM_NOTAGS);               
        $mform->setDefault('filepath', get_string('file_path_here', 'local_cronlogreader'));   
        $mform->addRule('filepath', get_string('filepath_required_error', 'local_cronlogreader'), 'required'); 

        $mform->addElement('text', 'filename', get_string('file_name_text', 'local_cronlogreader'));
        $mform->setType('filename', PARAM_NOTAGS);               
        $mform->setDefault('filename', get_string('file_name_here', 'local_cronlogreader'));    
        $mform->addRule('filename', get_string('filename_required_error', 'local_cronlogreader'), 'required');

        $mform->addElement('duration', 'tasktime', get_string('task_time_text', 'local_cronlogreader'));
        $mform->setType('tasktime', PARAM_INT);  
        $this->add_action_buttons(true, "Generate");
    }

    //Custom validation should be added here
    function validation($data, $files) {
        return array();
    }
}