<?php
/**
* @package      local_cronlogreader
* @author       Alex
* @license      http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
*/

use core\session\redis;
use local_cronlogreader\form\cronlogreader;
require("classes/generatelib.php");

require_once(__DIR__ . '/../../config.php');

global $DB;

$PAGE->set_url(new moodle_url('/local/cronlogreader/generate.php'));
$PAGE->set_context(\context_system::instance());
$PAGE->set_title(get_string('generate_title' ,'local_cronlogreader'));
$PAGE->set_heading(get_string('generate_title', 'local_cronlogreader'));

$mform = new cronlogreader();
$generate = new generate();

if($mform->is_cancelled()){
    redirect($CFG->wwwroot . '/local/cronlogreader/generate.php', get_string('cancelled_generate_form', 'local_cronlogreader'));
} else if($fromform = $mform->get_data()){
    $generate->process_form($fromform, $_SESSION['filepath'], $_SESSION['filename']);
}

// Set default data
// Currently, this is the only purpose of having a db table for this plugin
$id = $generate->get_record('SELECT id from {local_cronlogreader}');
if($id){
    $data = $generate->get_record('SELECT * from {local_cronlogreader}');
    $mform->set_data($data);
} else {
    //die();
}

$templatecontext = (object)[
    'generateUrl' => new moodle_url('/local/cronlogreader/paths.php'),
];

echo $OUTPUT->header();

$mform->display();

echo $OUTPUT->render_from_template('local_cronlogreader/generate', $templatecontext);

echo $OUTPUT->footer();
