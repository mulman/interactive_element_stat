<?php

$sql = "SELECT i.id,i.element_name,i.element_part_name,i.element_part_action,i.time,u.firstname,u.lastname,u.id AS userid from {$CFG->prefix}interactiveelementstat i ";
$sql .= "INNER JOIN {$CFG->prefix}user u ON i.userid = u.id ";
$sql .= "WHERE course = $id ";

if($element != 'all') $sql .= "AND element_name = '$element' ";

$sql .= "ORDER BY time";               
$record = get_records_sql($sql);  

$table = new object();
$table->width = '60%';
$table->head = array('Time', 'Name', 'Element','Part','Action');
$table->align = array('center', 'left', 'center');
//$table->size = array('20%', '60%', '20%');

foreach($record as $r):
    $time = date('d-m-Y h:m:s', $r->time);
    $table->data[] = array($time,'<a href="'.$CFG->wwwroot.'/user/view.php?id='.$r->userid .'">'. $r->firstname." ".$r->lastname.'</a>',$r->element_name,$r->element_part_name,$r->element_part_action);
endforeach;

echo '<div style="text-align:center" class="graph"><img src="'.$CFG->wwwroot.'/course/report/interactiveelementstat/graph.php?id='.$id.'&element='.$element.'&user="'.$user.' /></div>';

echo "<br />";

print_table($table); 
?>
