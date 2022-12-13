<?php
/**
* @package      local_cronlogreader
* @author       Alex
* @license      http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
*/

require_once(__DIR__ . '/../../config.php');
require("classes/readerlib.php");

global $DB;

$PAGE->set_url(new moodle_url('/local/cronlogreader/reader.php'));
$PAGE->set_context(\context_system::instance());
$PAGE->set_title(get_string('generate_title' ,'local_cronlogreader'));
$PAGE->set_heading(get_string('reader_title' ,'local_cronlogreader'));


$reader = new reader();

$file = $reader->get_file();
$errorCount = $reader->get_errors($file);
list($tasks, $longTasks) = $reader->get_time_flagged_tasks($file);

echo $OUTPUT->header();

$templatecontext = (object)[
    'file' => $file,
    'errorCount' => $errorCount,
    'longTaskCount' => $longTasks,
    'tasks' => $tasks,
    'generateUrl' => new moodle_url('/local/cronlogreader/generate.php'),
];

echo $OUTPUT->render_from_template('local_cronlogreader/reader', $templatecontext);

echo $OUTPUT->footer();