<?php

require_once('../../../config.php');
require_once('locallib.php');
require_once('header.php');

$id = required_param('id',PARAM_INT);       // course id
$element = required_param('elementid',PARAM_TEXT); 
$element_name = required_param('element_name',PARAM_TEXT); 
$page = optional_param('page',0,PARAM_TEXT);
$timePeriod = required_param('time_period',PARAM_TEXT);

$record = getDetailElementStat($element,$page);
$recordCount = count($record);

if($recordCount != 0)
{ 
    $table = new object();
    $table->width = '60%';
    $table->head = $detailElementStatColumns;
    $table->align = array('center','center','center');

    $record = getDetailElementStat($element,$page);
    $recordCount = count($record);
     
    foreach($record as $r):      
        $table->data[] = array($r->element_part_name,  secondsToTime(makeTotalDetailTime($element, $r->element_part_name)));
    endforeach;

    echo "<h2 style='text-align: center'>".get_string('detail_stat', 'report_'.MODULENAME)." ".$element_name."</h2>";

    print_paging_bar($recordCount,$page,RECORDTOPAGE,'detail.php?id='.$id.'&time_period='.$timePeriod.'&element_name='.$element_name.'&elementid='.$element.'&');
    print_table($table); 
    print_paging_bar($recordCount,$page,RECORDTOPAGE,'detail.php?id='.$id.'&time_period='.$timePeriod.'&element_name='.$element_name.'&elementid='.$element.'&');

    // Export Button
    $options = array(
        'id'           => $id,
        'element_name'      => $element_name,
        'elementid'      => $element,
        'stat_type'    => 'detail');
    
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