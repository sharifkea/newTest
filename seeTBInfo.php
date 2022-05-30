<?php
include_once ("header.php");
require_once('fun.php');?>
<p>Table Name: <?php echo $_SESSION['selectedTable']; ?></p><?php
$key=array('tableInfo'=>$_SESSION['selectedTable']);
$tbif=find_one($_SESSION['mdbInfo'],$key);
$tbif = json_decode(json_encode($tbif), true);
//var_dump($tbif);
?>
<div id='tableInfo'>
    <table style="width:50%">
        <tr>
            <th>Column Name</th>
            <th>Data Type</th>
            <th>Primary Key</th>
            <th>Not NULL</th>
            <th>Auto Increase</th>
<?php 
if($tbif['fkNo']>0){
    ?>      <th>Foreign Key</th>
            <th>Reference Table</th>
            <th>Reference Column</th>
    <?php
    } ?>
        </tr>
        
<?php
//echo $tbif['columnNo'];
for($i=0;$i<$tbif['columnNo'];$i++){
    ?>
        <tr>
            <td><?php echo $tbif['columnNames'][$i]; ?></td>
            <td><?php echo $tbif['type'][$i]; ?></td>
            <td><input type="checkbox" <?php if($tbif['columnNames'][$i]==$tbif['pk']) echo 'checked'; ?> readonly></td>
            <td><input type="checkbox" <?php if($tbif['nl'][$i]==1) echo 'checked'; ?> readonly></td>
            <td><input type="checkbox" <?php if($tbif['columnNames'][$i]==$tbif['ai']) echo 'checked'; ?> readonly></td>
            <?php
                //unset ($fkIn); 
                if($tbif['fkNo']>0){unset($fkIn);
                    foreach($tbif['fkName'] as $index => $value){
                        if($tbif['columnNames'][$i]==$value)$fkIn=$index;
                    }
            ?>      
            <td><input type="checkbox" <?php if(isset($fkIn)) echo 'checked'; ?> readonly></td>
            <td><?php if(isset($fkIn)) echo $tbif['fkTable'][$fkIn] ; ?></td>
            <td><?php if(isset($fkIn)) echo $tbif['fkColumn'][$fkIn] ; ?></td>
            <?php } ?>
        </tr> 
    <?php
    } ?>
               
    </table>
</div>
<?php 



?>
<a class=""  href='home.php'>>Back<</a>
<?php
include_once('footer.php');
?>