<?php

require_once('../../../config.php');
require_once('locallib.php');
require_once('header.php');

$id = required_param('id',PARAM_INT);       // course id
$element = required_param('elementid',PARAM_TEXT); 
$element_name = required_param('element_name',PARAM_TEXT); 
$page = optional_param('page',0,PARAM_TEXT);
$timePeriod = required_param('time_period',PARAM_TEXT);

$recordCount = count_records(TABLEB,'element_usage_id',$element);

if($recordCount != 0)
{ 
    $table = new object();
    $table->width = '60%';
    $table->head = $allActionsElementStatColumns;
    $table->align = array('center','center','center');

    $record = getElementStat($element,$page);

    foreach($record as $r):      
        $table->data[] = array(formatDate($r->time),$r->element_part_name,$r->element_part_action);
    endforeach;

    echo "<h2 style='text-align: center'>".get_string('all_actions_stat', 'report_'.MODULENAME)." ".$element_name."</h2>";

    print_paging_bar($recordCount,$page,RECORDTOPAGE,'allactions.php?id='.$id.'&time_period='.$timePeriod.'&element_name='.$element_name.'&elementid='.$element.'&');
    print_table($table); 
    print_paging_bar($recordCount,$page,RECORDTOPAGE,'allactions.php?id='.$id.'&time_period='.$timePeriod.'&element_name='.$element_name.'&elementid='.$element.'&');

    // Export Button
    $options = array(
        'id'           => $id,
        'element_name'      => $element_name,
        'elementid'      => $element,
        'stat_type'    => 'allactions');
    
    echo "<BR />";
    
    print_simple_box_start();
    echo '<center>';
    print_single_button('export.php',$options,'Export to XLS');
    echo '</center>';
    print_simple_box_end();

}
else notify(get_string('empty_stat', 'report_'.MODULENAME));

echo "<BR />";

$options = array(
    'id' => $id,
    'element_name' => $element_name,
    'time_period' => $timePeriod
    );

printBackButton('report.php',$options);

?>