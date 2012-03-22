<?php

$id = required_param('id',PARAM_INT);  
$message = optional_param('message'); 
$options = array();

$intElems = getInteractiveElementsArray($id);  

// all stat
print_simple_box_start();

?>

<form style="text-align: center" method="POST" action="tools/removeelementstat.php">
    <input type="hidden" name="courseid" value="<?php echo $id;?>"/>
    <label><?php echo get_string('remove_element_stat', 'report_'.MODULENAME);?></label>
    <select name="element_name">           
        <?php foreach($intElems as $key => $value):?>
        <option value="<?php echo $key;?>"><?php echo $value;?></option>
        <?php endforeach; ?>   
    </select>    
    <input onclick="return confirm('<?php echo get_string('sure_question', 'report_'.MODULENAME)?>');" type="submit" value="<?php echo get_string('remove_button_label', 'report_'.MODULENAME)?>"/>
</form>

<?php

print_simple_box_end();

// stat for element
print_simple_box_start();

?>

<form style="text-align: center" method="POST" action="tools/removeallstat.php">
    <input type="hidden" name="courseid" value="<?php echo $id;?>"/>
    <label><?php echo get_string('remove_all_stat', 'report_'.MODULENAME);?></label>    
    <input onclick="return confirm('<?php echo get_string('sure_question', 'report_'.MODULENAME)?>');" type="submit" value="<?php echo get_string('remove_button_label', 'report_'.MODULENAME)?>"/>
</form>

<?php

print_simple_box_end(); 

if(isset($message)){
    notify(get_string($message, 'report_'.MODULENAME)); 
}

?>