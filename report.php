<?php

require_once('../../../config.php');
require_once('/lib/locallib.php');
require_once('header.php');

$id = required_param('id',PARAM_INT);       // course id
$element = required_param('element_name',PARAM_TEXT); 
$timePeriod = required_param('time_period',PARAM_TEXT); 
$page = optional_param('page',0,PARAM_TEXT);

$dateFormat = 'd-m-Y h:m:s';
$options = array(
    'id'           =>   $id,
    'element_name' =>   $element,
    'time_period'  =>   $timePeriod);

$table = new object();
$table->width = '60%';

switch ($element):
    /// ALL ELEMENT ///
    case "all":
        
        $table->head = $allElementStatColumns;
        $table->align = array('center', 'center', 'center','center');
        
        $record = getAllElementStat($id,$timePeriod);
             
        if($record != NULL)
        {         
            foreach($record as $r):  

                $finalTime = makeTotalTimeForAllElement($r->element_name,$id,$timePeriod);
          
                $detailLink = '<a href="report.php?id='.$id.'&element_name='.$r->element_name.'&time_period='.$timePeriod.'&page=0">Detail</a>';
                $table->data[] = array($r->element_name, $r->element_access_count,$r->fcount, $finalTime,$detailLink);
            endforeach;

            $options['stat_type'] = 'all';
            echo "<h2 style='text-align: center'>".get_string('all_element_stat', 'report_'.MODULENAME)."</h2>";              
            
            echo '<center>';
            echo '<img style="text-align:center" src="graph.php?id='.$id.'&element='.$element.'&time_period='.$timePeriod.'&type=clicks" alt="graph" />'; 
            echo '<img style="text-align:center" src="graph.php?id='.$id.'&element='.$element.'&time_period='.$timePeriod.'&type=time" alt="graph" />';             
            echo '</center><br />';
             
            print_table($table);  

            echo "<BR />";
            print_simple_box_start();
            echo '<center>';
            print_single_button('export.php',$options,'Export to XLS');
            echo '</center>';
            print_simple_box_end();
        }     
        else
        {
            notify(get_string('empty_stat', 'report_'.MODULENAME));
        }
    break;
    
    /// ONE ELEMENT ///  
    default:    
      
        $table->head = $oneElementStatColumns;
        $table->align = array('center', 'center', 'center','center', 'center', 'center');        
        
        $recordCount = count(getUserElementStat($id, $element, $timePeriod, 'null'));
        $record = getUserElementStat($id, $element, $timePeriod, $page);
           
        if($record != NULL) 
        {
            foreach($record as $r):  

                $finalTime = secondsToTime(makeTotalTime($r->id));           
                $detailLink = '<a href="detail.php?id='.$id.'&elementid='.$r->id.'&element_name='.$r->element_name.'&page=0&time_period='.$timePeriod.'">'.get_string('detail1', 'report_'.MODULENAME).'</a>';
                $detailLink2 = '<a href="allactions.php?id='.$id.'&elementid='.$r->id.'&element_name='.$r->element_name.'&page=0&time_period='.$timePeriod.'">'.get_string('detail2', 'report_'.MODULENAME).'</a>';
                $table->data[] = array($r->element_name,'<a href="'.$CFG->wwwroot.'/user/view.php?id='.$r->userid.'&amp;course='.$course->id.'">'.$r->firstname." ".$r->lastname, formatDate($r->start_time),  formatDate($r->end_time),$finalTime,$r->fcount,$detailLink,$detailLink2);
            endforeach;

            $options['stat_type'] = 'one';
            echo "<h2 style='text-align: center'>".get_string('current_element_stat', 'report_'.MODULENAME)." ".$element."</h2>";

            $paginatorURL = 'report.php?id='.$id.'&element_name='.$element.'&time_period='.$timePeriod.'&';

            print_paging_bar($recordCount,$page,RECORDTOPAGE,$paginatorURL);

            print_table($table);  

            print_paging_bar($recordCount,$page,RECORDTOPAGE,$paginatorURL);

            echo "<BR />";
            print_simple_box_start();
            echo '<center>';
            print_single_button('export.php',$options,'Export to XLS');
            echo '</center>';
            print_simple_box_end();
        }
        else
        {
            notify(get_string('empty_stat', 'report_'.MODULENAME));
        }
    break;

endswitch;

$options = array('id'=>$id);

printBackButton('index.php',$options);

?>