<?php
require_once('../../../../config.php');
require_once('../lib/locallib.php');

if (!defined('MOODLE_INTERNAL')) {
    die('Direct access to this script is forbidden.');
}

echo "<h3>Uninstall course report: " . get_string('interactiveelementstat', 'report_'.MODULENAME) . "</h3>" ;

$sql = "DELETE FROM {$CFG->prefix}config where name='coursereport_".MODULENAME."_version'";
execute_sql($sql);
$sql = "DELETE FROM {$CFG->prefix}capabilities where component='coursereport/".MODULENAME."'";
execute_sql($sql);
$sql = "DROP TABLE {$CFG->prefix}".TABLEA;
execute_sql($sql);
$sql = "DROP TABLE {$CFG->prefix}".TABLEB;
execute_sql($sql);
?>