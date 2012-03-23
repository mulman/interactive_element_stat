<?php

//Include phpMyGraph class 
include_once('lib/phpMyGraph5.0.php');
require_once('../../../config.php');
require_once('locallib.php');

$data = array();

$id = required_param('id',PARAM_INT);       // course id
$element = required_param('element',PARAM_TEXT); 
$timePeriod = required_param('time_period',PARAM_TEXT);
$type = required_param('type',PARAM_TEXT);

$record = getAllElementStat($id,$timePeriod);

switch ($type):
    
    case "clicks":
        $cfg['title'] = get_string('click_graph', 'report_'.MODULENAME);
        //$cfg['column-color'] = '#999966';
        foreach($record as $r):  
            $data[$r->element_name] = $r->fcount;
        endforeach;
    break;    
    
    case "time":
        $cfg['title'] = get_string('access_graph', 'report_'.MODULENAME);
        //$cfg['column-color'] = '#999966';
        foreach($record as $r):  
            $data[$r->element_name] = $r->element_access_count;
        endforeach;
    break; 
    
endswitch;

//Set content-type header
header("Content-type: image/png");

//Set config directives

$cfg['width'] = 500;
$cfg['height'] = 350;
$cfg['average-line-visible'] = false;

$cfg['column-color-random'] = true;

//Create phpMyGraph instance
$graph = new phpMyGraph();
//Parse
$graph->parseVerticalColumnGraph($data, $cfg);
?>
