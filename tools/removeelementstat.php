<?php
/*
 * Remove element stat for course
 */

require_once('../../../../config.php');
require_once('../locallib.php');

$id = required_param('courseid',PARAM_INT); 

if(isset($_POST['courseid']) && isset($_POST['element_name']))
{
    $courseID = $_POST['courseid'];
    $elementName  = $_POST['element_name'];
    
    $sql = "SELECT * FROM {$CFG->prefix}".TABLEA." WHERE course=".$courseID." AND element_name='".$elementName."'";
    
    $result = get_records_sql($sql);
    
    // Delete from TABLEB
    foreach ($result as $r):        
        delete_records(TABLEB, 'element_usage_id', $r->id);         
    endforeach;
    
    delete_records(TABLEA, 'course', $courseID,'element_name',$elementName);  
}

redirect('../index.php?id='.$id.'&view=tools&message=remove_element_stat_notify');

?>