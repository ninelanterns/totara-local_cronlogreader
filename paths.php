<?php
/**
* @package      local_cronlogreader
* @author       Alex
* @license      http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
*/

require_once(__DIR__ . '/../../config.php');
require("classes/pathslib.php");

global $DB;

$PAGE->set_url(new moodle_url('/local/cronlogreader/reader.php'));
$PAGE->set_context(\context_system::instance());
$PAGE->set_title(get_string('generate_title' ,'local_cronlogreader'));
$PAGE->set_heading(get_string('reader_title' ,'local_cronlogreader'));

$directories = new paths();

echo $OUTPUT->header();

$paths = $directories->get_all_directories('var/log/nginx');

$templatecontext = (object)[
    'paths' => $paths,
    'generateUrl' => new moodle_url('/local/cronlogreader/generate.php'),
];

echo $OUTPUT->render_from_template('local_cronlogreader/paths', $templatecontext);

echo $OUTPUT->footer();