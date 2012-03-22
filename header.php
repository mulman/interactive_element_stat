<?php

$id = required_param('id',PARAM_INT);       // course id
$view = optional_param('view', 'default', PARAM_ALPHA);

if (!$course = get_record('course', 'id', $id)) {
    error('Course id is incorrect.');
}

require_login($course);
$context = get_context_instance(CONTEXT_COURSE, $course->id);
require_capability('coursereport/'.MODULENAME.':view', $context);

$strlogs = 'IES';
$stradministration = get_string('administration');
$strreports = get_string('reports');
$stries = get_string('interactiveelementstat', 'report_interactiveelementstat');    

// Navigation
$navlinks = array();
$navlinks[] = array('name' => $strreports, 'link' => "../../report.php?id=$course->id", 'type' => 'misc');
$navlinks[] = array('name' => $strlogs, 'link' => null, 'type' => 'misc');
$navigation = build_navigation($navlinks);
print_header("$course->shortname: $strlogs", $course->fullname, $navigation);
print_heading(format_string($course->fullname));

echo "<h4 style='text-align: center'>".MODULENICENAME."</h4>";

?>
