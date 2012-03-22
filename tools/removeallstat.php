<?php
/*
 * Remove all stat for course
 */

require_once('../../../../config.php');
require_once('../locallib.php');

$id = required_param('courseid',PARAM_INT); 

if(isset($_POST['courseid']))
{  
    $courseID = $_POST['courseid'];    
    
    $result = get_records(TABLEA,'course',$courseID);
    
    // Delete from TABLEB
    foreach ($result as $r):        
        delete_records(TABLEB, 'element_usage_id', $r->id);         
    endforeach;
    
    // Delete from TABLEA
    delete_records(TABLEA, 'course', $courseID);      
}

redirect('../index.php?id='.$id.'&view=tools&message=remove_element_stat_notify');
?>