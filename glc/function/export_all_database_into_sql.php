<?php

/* backup the db OR just a table */
function backup_tables($backup_path,$tables = '*')
{
  
  
  
  //get all of the tables
  if($tables == '*')
  {
    $tables = array();
    $result = mysqli_query($GLOBALS["___mysqli_ston"], 'SHOW TABLES');
    while($row = mysqli_fetch_row($result))
    {
       $tables[] = $row[0];
    }
  }
  else
  {
    $tables = is_array($tables) ? $tables : explode(',',$tables);
  }
  
  //cycle through
  foreach($tables as $table)
  {
    $result = mysqli_query($GLOBALS["___mysqli_ston"], 'SELECT * FROM '.$table);
    $num_fields = (($___mysqli_tmp = mysqli_num_fields($result)) ? $___mysqli_tmp : false);
    
    //$return.= 'DROP TABLE '.$table.';';
    $row2 = mysqli_fetch_row(mysqli_query($GLOBALS["___mysqli_ston"], 'SHOW CREATE TABLE '.$table));
    $return.= "\n\n".$row2[1].";\n\n";
    
    for ($i = 0; $i < $num_fields; $i++) 
    {
      while($row = mysqli_fetch_row($result))
      {
        $return.= 'INSERT INTO '.$table.' VALUES(';
        for($j=0; $j<$num_fields; $j++) 
        {
          $row[$j] = addslashes($row[$j]);
          $row[$j] = ereg_replace("\n","\\n",$row[$j]);
          if (isset($row[$j])) { $return.= '"'.$row[$j].'"' ; } else { $return.= '""'; }
          if ($j<($num_fields-1)) { $return.= ','; }
        }
        $return.= ");\n";
      }
    }
    $return.="\n\n\n";
  }
  
  //save file
  $ff = $backup_path.'db-backup-'.time().'-'.(md5(implode(',',$tables))).'.sql';
  $handle = fopen($ff,'w+');
  
  fwrite($handle,$return);
  fclose($handle);
}