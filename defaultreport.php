<?php 

if(count_records(TABLEA,'course',$id) != 0){ 

$intElems = getInteractiveElementsArray($id);    
 
?>
    
<form style="text-align: center" method="GET" action="report.php">
    <input type="hidden" name="id" value="<?php echo $id;?>"/>
    <label><?php echo get_string('element_name', 'report_'.MODULENAME);?></label>
    <select name="element_name">
        <option value="all"><?php echo get_string('all_element_in_course', 'report_'.MODULENAME);?></option>   
        <option disabled="disabled">---<?php echo get_string('elements', 'report_'.MODULENAME);?>---</option> 
        <?php foreach($intElems as $key => $value):?>
        <option value="<?php echo $key;?>"><?php echo $value;?></option>
        <?php endforeach; ?>   
    </select>    
    <label><?php echo get_string('time_period', 'report_'.MODULENAME);?></label>
    <select name="time_period">
        <option value="all">All</option>    
        <option value="today">Today</option> 
        <option value="week">Last Week</option> 
        <option value="month">Last Month</option> 
        <option value="year">Last Year</option>         
    </select>   
    <input type="submit" />
</form>
    
<?php 
} else
    notify(get_string('empty_stat', 'report_'.MODULENAME));  

?>