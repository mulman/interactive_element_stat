<?php

/**
 * @author Marek Ulman
 * @copyright Marek Ulman
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @package course-report-interactiveelementstat
 */

require_once('../../../config.php');
require_once('/lib/locallib.php');
require_once('header.php');

// Write event to log
add_to_log($course->id, "course", "report ".MODULENAME, "report/".MODULENAME."/index.php?id=$course->id", $course->id);

/// Print tabs with options for user
print_container_start();
$rows[0][] = new tabobject('default', "index.php?id={$course->id}&amp;view=default", get_string('default', 'report_'.MODULENAME));
$rows[0][] = new tabobject('tools', "index.php?id={$course->id}&amp;view=tools", get_string('tools', 'report_'.MODULENAME));   

print_tabs($rows, $view); 

if (file_exists($CFG->dirroot."/course/report/".MODULENAME."/{$view}report.php"))
{
    include $CFG->dirroot."/course/report/".MODULENAME."/{$view}report.php";
} 
else {
    print_error('non existing report view');
}

print_container_end();
print_footer($course);
?>