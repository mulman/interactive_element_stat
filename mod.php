<?php

if (!defined('MOODLE_INTERNAL')) {
    die('Direct access to this script is forbidden.');
}

if (has_capability('coursereport/interactiveelementstat:view', $context) and has_capability('moodle/role:assign', $context)) {
    echo "<p><a href=\"$CFG->wwwroot/course/report/interactiveelementstat/index.php?id=$course->id\">".
          get_string('interactiveelementstat', 'report_interactiveelementstat').
         '</a></p>';
}

?>