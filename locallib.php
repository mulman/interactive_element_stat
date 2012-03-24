<?php
define('TABLEA', 'ies_element_usage');
define('TABLEB', 'ies_element_action');
define('MODULENAME','interactiveelementstat');
define('MODULENICENAME','Interactive Element Stat');
define('RECORDTOPAGE',10);

$allElementStatColumns = array(
    get_string('element_name', 'report_'.MODULENAME),
    get_string('element_access_count', 'report_'.MODULENAME),
    get_string('click_count', 'report_'.MODULENAME),
    get_string('total_usage_time', 'report_'.MODULENAME)  
    ,'');

$oneElementStatColumns = array(
    get_string('element_name', 'report_'.MODULENAME),
    get_string('user', 'report_'.MODULENAME),
    get_string('start_time', 'report_'.MODULENAME),
    get_string('end_time', 'report_'.MODULENAME),  
    get_string('usage_time', 'report_'.MODULENAME), 
    get_string('click_count', 'report_'.MODULENAME),     
    '','');   

$allActionsElementStatColumns = array(
    get_string('time', 'report_'.MODULENAME), 
    get_string('element_part_name', 'report_'.MODULENAME),
    get_string('element_action', 'report_'.MODULENAME)   
    ,'');

$detailElementStatColumns = array(
    get_string('element_part_name', 'report_'.MODULENAME),
    get_string('total_usage_time', 'report_'.MODULENAME)    
    ,'');
/*
 * Vrati zoznam pouzitych interaktivnych
 * elementov v kurze.
 */
function getInteractiveElementsArray($courseid)
{    
    global $CFG;
    $sql = "SELECT DISTINCT * FROM {$CFG->prefix}".TABLEA." where course=".$courseid;    
    
    if(count_records_sql($sql) != 0)
    {     
        $result = (array)get_records_sql($sql);
              
        $newArray = array();

        foreach ($result as $r):
            $newArray[$r->element_name] = $r->element_name;
        endforeach;

        return $newArray;
    }    
}

/*
 * Vrati statistiku prace s elementom
 */
function getUserElementStat($courseid,$elementName,$timePeriod,$page)
{
    global $CFG;        
    
    //$sql =  "SELECT a.id,(SELECT COUNT(*) from {$CFG->prefix}".TABLEB." b WHERE b.element_usage_id = a.id) AS fcount,a.userid,a.element_name,a.start_time,a.end_time,(UNIX_timestamp(a.end_time) - UNIX_timestamp(a.start_time)) AS rozdiel,u.firstname,u.lastname FROM {$CFG->prefix}".TABLEA." a "; 
    $sql  =  "SELECT a.id,a.userid,a.element_name,u.firstname,u.lastname,";
    $sql .= "(SELECT COUNT(*) from {$CFG->prefix}".TABLEB." b WHERE b.element_usage_id = a.id AND b.element_part_action != 'noactivity') AS fcount, ";
    $sql .= "(SELECT MIN(b.time) from {$CFG->prefix}".TABLEB." b WHERE b.element_usage_id = a.id) AS start_time,";
    $sql .= "(SELECT MAX(b.time) from {$CFG->prefix}".TABLEB." b WHERE b.element_usage_id = a.id) AS end_time ";
    $sql .= "FROM {$CFG->prefix}".TABLEA." a "; 
    
    $sql .= "CROSS JOIN {$CFG->prefix}user u ON a.userid = u.id ";
    
    $sql .= "WHERE course=".$courseid." AND a.element_name='".$elementName."'";  
    
    if($timePeriod != 'all') $sql .= getTimePeriodSql($timePeriod);    
    
    $sql .= " ORDER BY start_time DESC";   
    if($page != 'null')
    {
        $page = $page * RECORDTOPAGE;
        $sql .= " LIMIT ".$page.",".RECORDTOPAGE;
    } 
    
    if(count_records_sql($sql) != 0)
    {     
        return get_records_sql($sql);
    }    
}

/*
 * Vrati statistiku pre vsetky elementy v kurze
 */
function getAllElementStat($courseid,$timePeriod)
{
    global $CFG;   
   
    
    $sql =  "SELECT a.id,a.userid,a.element_name,";
    $sql .= "SUM((SELECT COUNT(*) from {$CFG->prefix}".TABLEB." b WHERE b.element_usage_id = a.id AND b.element_part_action != 'noactivity')) AS fcount, ";   
    $sql .= "COUNT(*) AS element_access_count ";
    $sql .= "FROM {$CFG->prefix}".TABLEA." a "; 
    $sql .= "WHERE course=".$courseid;      
    
    if($timePeriod != 'all') $sql .= getTimePeriodSql($timePeriod);
    
    $sql .= " GROUP by a.element_name"; 
    
    if(count_records_sql($sql) != 0)
    {     
        return get_records_sql($sql);
    }    
}

function getTimePeriodSql($timePeriod)
{
    switch ($timePeriod):
        case "today":
            $sql = " AND DATE(a.start_time) = DATE(NOW())";
        break;
        case "week":
            $sql = " AND DATE_SUB(CURDATE(),INTERVAL 7 DAY) <= a.start_time";
        break;
        case "month":
            $sql = " AND DATE_SUB(CURDATE(),INTERVAL 30 DAY) <= a.start_time";
        break;
        case "year":
            $sql = " AND DATE_SUB(CURDATE(),INTERVAL 365 DAY) <= a.start_time";
        break;
    endswitch;
    
    return $sql;
}


/*
 * Vrati detailne informacie
 * o praci s elementom
 */
function getElementStat($elementID,$page)
{
    global $CFG;
        
    $sql =  "SELECT * FROM {$CFG->prefix}".TABLEB." b "; 
    $sql .= "WHERE b.element_usage_id='".$elementID."'";    
    $sql .= " ORDER BY b.time ASC";
    
    if($page != 'null')
    {
        $page = $page * RECORDTOPAGE;
        $sql .= " LIMIT ".$page.",".RECORDTOPAGE;
    }  
   
    
    $result = (array)get_records_sql($sql);        
    return $result;
}

function getDetailElementStat($elementID,$page)
{
    global $CFG;
        
    $sql =  "SELECT DISTINCT element_part_name FROM {$CFG->prefix}".TABLEB." b "; 
    $sql .= "WHERE b.element_usage_id='".$elementID."' AND element_part_name IS NOT NULL";    
    $sql .= " ORDER BY b.time ASC";
    
    if($page != 'null')
    {
        $page = $page * RECORDTOPAGE;
        $sql .= " LIMIT ".$page.",".RECORDTOPAGE;
    }  
   
    
    $result = (array)get_records_sql($sql);        
    return $result;
}

function secondsToTime($seconds)
{
    // extract hours
    $hours = floor($seconds / (60 * 60));
 
    // extract minutes
    $divisor_for_minutes = $seconds % (60 * 60);
    $minutes = floor($divisor_for_minutes / 60);
 
    // extract the remaining seconds
    $divisor_for_seconds = $divisor_for_minutes % 60;
    $seconds = ceil($divisor_for_seconds);
    
    $obj  = $hours . " " . get_string('hours', 'report_interactiveelementstat');
    $obj .= $minutes . " " . get_string('minutes', 'report_interactiveelementstat');
    $obj .= $seconds . " " . get_string('seconds', 'report_interactiveelementstat');

    return $obj;
}

function getTimeStatValues($courseid)
{
    global $CFG;
    $sql = "SELECT * from {$CFG->prefix}".TABLEA." WHERE DATE_SUB(CURDATE(),INTERVAL 6 DAY) <= start_time" ;
    
    return get_record_sql($sql);
}

function printBackButton($url,$options)
{      
    echo "<center>";
    print_single_button($url,$options,'Back');
    echo "</center>";
}

// Vypocita celkovy cas prace s elementom
function makeTotalTime($elementID,$part = 'none')
{    
    global $CFG;
     
    $sql =  "SELECT * FROM {$CFG->prefix}".TABLEB." b "; 
    $sql .= "WHERE b.element_usage_id='".$elementID."'";  
    if($part != 'none') $sql .= " AND (b.element_part_name='".$part."' OR b.element_part_name IS NULL)";  
    $sql .= " ORDER BY b.time ASC";
    
    $result = (array)get_records_sql($sql);     
    $finalResult = 0;    
    $last_key = end(array_keys($result));
    $activity = true;
    $i = 0;
    
    foreach ($result as $r):
        
        if($activity && $r->element_part_action != 'access') 
        {
            $accessTime = $r->time;
            $activity = false;
        }       
            
        $i++;
        if($r->element_part_action == 'access' && $activity != false)
        {
            $accessTime = $r->time;
            $activity = false;           
        }
        else if($r->element_part_action == 'noactivity' || count($result) == $i)
        {
            $noactivityTime = $r->time;
            $currTime = strtotime($noactivityTime) - strtotime($accessTime);;
            $finalResult += $currTime; 
            $activity = true;            
        }
         
    endforeach;   
    
    return $finalResult;   
}

function makeTotalDetailTime($elementID,$part = 'none')
{    
    global $CFG;
     
    $sql =  "SELECT * FROM {$CFG->prefix}".TABLEB." b "; 
    $sql .= "WHERE b.element_usage_id='".$elementID."'";      
    $sql .= " ORDER BY b.time ASC";       
    
    $result = (array)get_records_sql($sql);     
    $finalResult = 0;    
    $last_key = end(array_keys($result));
    $activity = true;
    $i = 0;
    
    foreach ($result as $r):
        
        if($activity && $r->element_part_action != 'access') 
        {
            $accessTime = $r->time;
            $activity = false;
        }        
           
        $i++;
        if($r->element_part_action == 'access' && $activity != false)
        {
            $accessTime = $r->time;
             $activity = false;            
        }
        else if($r->element_part_action == 'noactivity' || count($result) == $i || $r->element_part_name != $part)
        {
            $noactivityTime = $r->time;
            $currTime = strtotime($noactivityTime) - strtotime($accessTime);;
            $finalResult += $currTime; 
            $activity = true;           
        }
         
    endforeach;   
    
    return $finalResult;   
}

function makeTotalTimeForAllElement($elementName,$courseID,$timePeriod)
{
    global $CFG;   
   
    
    $sql =  "SELECT a.id ";
    $sql .= "FROM {$CFG->prefix}".TABLEA." a "; 
    $sql .= "WHERE course=".$courseID." AND element_name='".$elementName."'";      
    
    if($timePeriod != 'all') $sql .= getTimePeriodSql($timePeriod);

    $result = get_records_sql($sql);
    
    $finalResult = 0;
    foreach($result as $r):
    
        $finalResult += makeTotalTime($r->id);        
        
    endforeach;
    
    return secondsToTime($finalResult);       
}

/*
 * Formatuje cas do "peknej" podoby
 */
function formatDate($date)
{
    return date("d.m.Y H:i:s",  strtotime($date));
}

function notRegistered($elementID)
{
    if (!$record = get_record(TABLEA, 'id', $elementID))
        return true;
    else
        return false;
}

?>