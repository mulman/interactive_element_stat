<?php
require_once('../../../config.php');
require_once($CFG->libdir.'/excellib.class.php');
require_once('/lib/locallib.php');

$id = required_param('id',PARAM_INT);       // course id
$element = required_param('element_name',PARAM_TEXT); 
$statType = required_param('stat_type',PARAM_TEXT);
$timePeriod = optional_param('time_period',PARAM_TEXT); 

$headerRow = 2;
$startRow = 3;
$strfilename = 'stat';
$timenow = date("d-m-Y H:i:s");
$columnWidth = 20;

$workbook = new MoodleExcelWorkbook('-');
$workbook->send($strfilename . '.xls');
$worksheet =& $workbook->add_worksheet($strfilename);

$headFormat =& $workbook->add_format(array(
    'bold' => 1,
    'border_color'=>'black',
    'fg_color' => 'silver',
    'bottom'=>1,
    'top'=>1,
    'left'=>1,
    'right'=>1,
    'align' => 'center'
    ));

$titleFormat =& $workbook->add_format(array('italic' => 1,'size' => 14,'bold'=>1,'merge'=>1));
$titleFormat2 =& $workbook->add_format(array('italic' => 1,'size' => 14,'bold'=>1,'merge'=>1,'color'=>'gray'));
$footFormat =& $workbook->add_format(array('italic' => 1,'size' => 8,'color'=>'gray'));
$textFormat = & $workbook->add_format(array('align' => 'center','text_wrap'=>1));


switch ($statType):
    // ALL
    case "all":  
        //Title
        $worksheet->write(0, 0, get_string('all_element_stat', 'report_'.MODULENAME),$titleFormat); 
        //Head       
        $worksheet->set_column(0, count($allElementStatColumns), $columnWidth);
        
        for($i=0;$i<count($allElementStatColumns);$i++):
            if($allElementStatColumns[$i] != '') $worksheet->write($headerRow, $i, $allElementStatColumns[$i],$headFormat);
        endfor;        
        $row = $startRow;  
        
        $rec = getAllElementStat($id,$timePeriod);
        
        foreach($rec as $rs):

            $finalTime = makeTotalTimeForAllElement($rs->element_name,$id,$timePeriod);

            $worksheet->write($row, 0, $rs->element_name, $textFormat); 
            $worksheet->write($row, 1, $rs->element_access_count, $textFormat); 
            $worksheet->write($row, 2, $rs->fcount, $textFormat);
            $worksheet->write($row, 3, $finalTime, $textFormat);
            $row++;
        endforeach;
        
    break;
    // One
    case "one":        
        //Title
        $worksheet->write(0, 0, get_string('current_element_stat', 'report_'.MODULENAME),$titleFormat); 
        $worksheet->write(0, 1, $element ,$titleFormat2); 
        
        $worksheet->set_column(0, count($oneElementStatColumns), $columnWidth);
        //Head
        for($i=0;$i<count($oneElementStatColumns);$i++):
            if($oneElementStatColumns[$i] != '') $worksheet->write($headerRow, $i, $oneElementStatColumns[$i],$headFormat);
        endfor;        
        $row = $startRow; 
        
        $rec = getUserElementStat($id, $element,$timePeriod,'null');
        
        foreach($rec as $rs):  
            
            $finalTime = secondsToTime(makeTotalTime($rs->id)); 
        
            $worksheet->write($row, 0, $rs->element_name, $textFormat); 
            $worksheet->write($row, 1, $rs->firstname . " " . $rs->lastname, $textFormat); 
            $worksheet->write($row, 2, formatDate($rs->start_time), $textFormat); 
            $worksheet->write($row, 3, formatDate($rs->end_time), $textFormat);
            $worksheet->write($row, 4, $finalTime, $textFormat);
            $worksheet->write($row, 5, $rs->fcount, $textFormat);            
            $row++;
        endforeach;
        
    break;
    //Detail
    case "detail":       
        
        $elementID = required_param('elementid',PARAM_TEXT); 
        
        //Title
        $worksheet->write(0, 0, get_string('detail_stat', 'report_'.MODULENAME),$titleFormat); 
        $worksheet->write(0, 1, $element,$titleFormat2); 
        
        $worksheet->set_column(0, count($detailElementStatColumns), $columnWidth);
        //Head        
        for($i=0;$i<count($detailElementStatColumns);$i++):
            if($detailElementStatColumns[$i] != '')  $worksheet->write($headerRow, $i, $detailElementStatColumns[$i],$headFormat);
        endfor;        
        $row = $startRow;  
        
        $rec = getDetailElementStat($elementID,'null');
                      
        foreach($rec as $rs):     
            
            $worksheet->write($row, 0, $rs->element_part_name, $textFormat); 
            $worksheet->write($row, 1, secondsToTime(makeTotalDetailTime($elementID, $rs->element_part_name), $textFormat)); 
                 
            $row++;
        endforeach;
    break;    
        //All actions
    case "allactions":       
        
        $elementID = required_param('elementid',PARAM_TEXT); 
        
        //Title
        $worksheet->write(0, 0, get_string('all_actions_stat', 'report_'.MODULENAME),$titleFormat); 
        $worksheet->write(0, 1, $element,$titleFormat2); 
        
        $worksheet->set_column(0, count($allActionsElementStatColumns), $columnWidth);
        //Head        
        for($i=0;$i<count($detailElementStatColumns);$i++):
            if($detailElementStatColumns[$i] != '')  $worksheet->write($headerRow, $i, $detailElementStatColumns[$i],$headFormat);
        endfor;        
        $row = $startRow;  
        
        $rec = getElementStat($elementID,'null');
        
        foreach($rec as $rs):     
            
            $worksheet->write($row, 0, formatDate($rs->time), $textFormat); 
            $worksheet->write($row, 1, $rs->element_part_name, $textFormat); 
            $worksheet->write($row, 2, $rs->element_part_action, $textFormat);        
            $row++;
        endforeach;
    break; 
endswitch;

$worksheet->write($row+1, 0, get_string('generated', 'report_'.MODULENAME) . get_string('interactiveelementstat', 'report_'.MODULENAME) . ", " . $timenow,$footFormat);
$workbook->close();       
?>