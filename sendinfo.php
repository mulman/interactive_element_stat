<?php
require_once('../../../config.php');
require_once('/lib/locallib.php');

$courseID = $SESSION->cal_course_referer; //Course ID
$userID = $SESSION->cal_users_shown; //User ID
$timenow = date("Y-m-d H:i:s");

$newRecord = new stdClass;
$type = required_param('type');
$elementID = required_param('elementID');  

switch ($type):

    case "action":
            
        
        if($elementID == 'none' || notRegistered($elementID))
        {
            $newRecord->element_name = required_param('elementName');
            $newRecord->start_time = $timenow;
            $newRecord->course = $courseID;
            $newRecord->userid = $userID;   
            $elementID = insert_record(TABLEA, $newRecord);
            echo "&elementid=".$elementID;            
        }
        
        
        $newRecord->time = $timenow;
        $newRecord->element_usage_id = $elementID;
        $newRecord->element_part_name = optional_param('elementPartName');
        $newRecord->element_part_action = required_param('elementPartAction');
        insert_record(TABLEB, $newRecord);    
    break;
    case "unload":
        
        if($elementID == 'none' || notRegistered($elementID))
        {
            $newRecord->element_name = required_param('elementName');
            $newRecord->start_time = $timenow;
            $newRecord->course = $courseID;
            $newRecord->userid = $userID;   
            $elementID = insert_record(TABLEA, $newRecord);
            echo "&elementid=".$elementID;            
        }
        
        $newRecord->time = $timenow;
        $newRecord->element_usage_id = $elementID;      
        $newRecord->element_part_action = 'noactivity';
        insert_record(TABLEB, $newRecord);    
    break;

endswitch;




?>