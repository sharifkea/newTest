<?php
include_once ("header.php");
require_once('fun.php');
?>
<p>Table Name: <?php echo $_SESSION['selectedTable']; ?></p>
<?php
if (isset($_POST['sub'])) {
  //var_dump($_POST);
  unset($_POST['sub']);
  unset($data); 
  $i= inTbRow($_SESSION['mdbInfo'],$_POST);
  switch ($i) {
    case 0:
        ?>
          <script>
            alert("Row successfully saved.");
            window.location.href ='seeTB.php';
          </script>
        <?php  
      break;
      case 1:
        ?>
          <script>
            alert("Row could not be saved. Primary Key not Unique.");
            window.location.href ='seeTB.php';
          </script>
        <?php
        break;
        case 2:
          ?>
            <script>
              alert("Row could not be saved. Input type error.");
              window.location.href ='seeTB.php';
            </script>
          <?php  
        break;
        case 3:
          ?>
            <script>
              alert("Row could not be saved. Foreign key mismatch.");
              window.location.href ='seeTB.php';
            </script>
          <?php
          break;
          case 4:
            ?>
              <script>
                alert("Row could not be saved. Try again later.");
                window.location.href ='seeTB.php';
              </script>
            <?php
            break;
        }
}elseif (isset($_POST['subEdit'])) {
  var_dump($_POST);
  unset($_POST['subEdit']);
  unset($data); 
  $i=edTbRow($_SESSION['mdbInfo'],$_POST);
  switch ($i) {
    case 0:
        ?>
          <script>
            alert("Row successfully Updated.");
            window.location.href ='seeTB.php';
          </script>
        <?php  
      break;
      case 1:
        ?>
          <script>
            alert("Row could not be Updated. Primary Key not Unique.");
            window.location.href ='seeTB.php';
          </script>
        <?php
        break;
        case 2:
          ?>
            <script>
              alert("Row could not be updated. The previous Primary key value is available as a foreign key in another table.");
              window.location.href ='seeTB.php';
            </script>
          <?php  
        break;
        case 3:
          ?>
            <script>
              alert("Row could not be Updated. Input type error.");
              window.location.href ='seeTB.php';
            </script>
          <?php  
        break;
        case 4:
          ?>
            <script>
              alert("Row could not be Updated. Foreign key mismatch.");
              window.location.href ='seeTB.php';
            </script>
          <?php
          break;
          case 5:
            ?>
              <script>
                alert("Row could not be Updated. Try again later.");
                window.location.href ='seeTB.php';
              </script>
            <?php
            break;
        }
}elseif (isset($_POST['subDel'])) {
  //var_dump($_POST);
  
  unset($_POST['subEdit']);
  unset($data); 
  if(dlTbRow($_SESSION['mdbInfo'],$_POST)){
    ?>
      <script>
        alert("Row successfully Deleted.");
        window.location.href ='seeTB.php';
      </script>
    <?php
  }else{
    ?>
      <script>
        alert("This Row could not be delered. The key has record as a Foreign key in other table(s).");
        window.location.href ='seeTB.php';
      </script>
    <?php  
  }
}else {
    $selectedTable=$_SESSION['selectedTable'];
    $mdbInfo=$_SESSION['mdbInfo'];
    $i=0;
    $key=array('tableInfo'=>$selectedTable);
    $re=find_one($mdbInfo,$key);
?>
<div id='seeTable'>
<table style="width:50%">
  <tr><?php 
    for ( $i = 0; $i < $re['columnNo']; $i++) {
      unset($fk);
      if($re['fkNo'])$fk=strInArray($re['fkName'],$re['columnNames'][$i]);
      if($re['columnNames'][$i]==$re['pk']){
        ?><th style="color:red"><?php echo $re['columnNames'][$i]; ?></th><?php
      }elseif(isset($fk)){
        ?><th style="color:green"><?php echo $re['columnNames'][$i]; ?></th><?php
      }else{
        ?><th><?php echo $re['columnNames'][$i]; ?></th><?php }
    }
  ?>
  </tr><?php
  if($re['rowNo']!=0){
    $which=array('tableName'=>$selectedTable);
    $rows=query($mdbInfo,$which);
    $count=0;
    foreach($rows as $ret){
      $ret=json_decode(json_encode($ret), true);
        //echo $ret->tableName;style="color:red"
        ?><tr>
          <form action=""  method="POST">
          <input type="hidden" id="tNa" name='tableName' value=<?php echo $selectedTable; ?>>
          <input type="hidden" id="pkin" name='pkIndex' value=<?php echo $re['pk']; ?>>
          <input type="hidden" id="opk" name='oldpkv' value=<?php echo $ret[$re['pk']]; ?>><?php
        for ( $i = 0; $i < $re['columnNo']; $i++) {
          if($re['columnNames'][$i]==$re['pk']) { ?>
          <input type="hidden" id="pkin" name='pkType' value=<?php echo $re['type'][$i]; ?>> <?php }
          if($re['type'][$i]=='integer'){
            ?><td><input type="text" id="fname"  oninput="this.value=this.value.replace(/[^0-9]/g,'');" name= <?php echo $re['columnNames'][$i]; if(isset($ret[$re['columnNames'][$i]])||$ret[$re['columnNames'][$i]]!='') echo ' value="'. $ret[$re['columnNames'][$i]].'"'; else echo ' value=""'; if($re['columnNames'][$i]==$re['ai']) echo ' readonly '; if($re['nl'][$i]==1) echo ' required'; ?> ></td><?php
          }elseif($re['type'][$i]=='double'){
              ?><td><input type="number" step="0.01"  id="fname" name=<?php echo $re['columnNames'][$i]; ?> onkeypress="return (event.charCode >= 48 && event.charCode <= 57) || event.charCode == 46 || event.charCode == 0 " <?php if(isset($ret[$re['columnNames'][$i]])||$ret[$re['columnNames'][$i]]!='') echo 'value="'. $ret[$re['columnNames'][$i]].'"'; else echo 'value=""'; ?> <?php if($re['nl'][$i]==1) echo 'required';?> ></td><?php
          }elseif($re['type'][$i]=='boolean'){
                ?><td><select name=<?php echo $re['columnNames'][$i]; ?> <?php if($re['nl'][$i]==1) echo 'required';?>>
                  <option  value="">--NULL--</option>
                  <option value=true <?php if((isset($ret[$re['columnNames'][$i]])||$ret[$re['columnNames'][$i]]!='') && $ret[$re['columnNames'][$i]]==true) { ?>selected<?php  } ?>>True</option>
                  <option value=false <?php if((isset($ret[$re['columnNames'][$i]])||$ret[$re['columnNames'][$i]]!='') && $ret[$re['columnNames'][$i]]==false) { ?>selected<?php  } ?>>False</option>
                </select></td><?php 
            }
            else{
                ?><td><input type="text" id="fname" name=<?php echo $re['columnNames'][$i]; ?> <?php if(isset($ret[$re['columnNames'][$i]])||$ret[$re['columnNames'][$i]]!='') { echo 'value="'. $ret[$re['columnNames'][$i]].'"'; } else {echo 'value=""'; } ?> <?php if($re['nl'][$i]==1) echo 'required';?>></th><?php 
            }
      }
        /*
        for ( $j = 0; $j < $re['columnNo']; $j++) {
          if(!isset($ret[$re['columnNames'][$j]])||$ret[$re['columnNames'][$j]]==''){
            ?><th><?php echo '-NULL-'; ?></th><?php 
          }else{

            ?><td><?php echo $ret[$re['columnNames'][$j]]; ?></td><?php
        }}*/
        $count=$count+1;
    ?><td><input type="submit" name="subEdit" value="Edit"></td>
    <td><input type="submit" name="subDel" value="Delete"></td>
    </form> </tr><?php
    }
}?>
  <tr>
  <form action=""  method="POST">
  <input type="hidden" id="tNa" name='tableName' value=<?php echo $selectedTable; ?>>
  <?php 
    //$id=$re['lastId']+1;

    for ( $i = 0; $i < $re['columnNo']; $i++) {
        if($re['columnNames'][$i]==$re['ai']){
          ?><td><input type="text" id="fname" name=<?php echo $re['columnNames'][$i]; ?> value=<?php echo $re['lastId']+1;?> readonly></td><?php  
        }elseif($re['type'][$i]=='integer'){if($re['nl'][$i]==0){
          ?><td><input type="text" id="fname" name=<?php echo $re['columnNames'][$i]; ?> oninput="this.value=this.value.replace(/[^0-9]/g,'');" value="" ></td><?php
        }else{?><td><input type="text" id="fname" name=<?php echo $re['columnNames'][$i]; ?> oninput="this.value=this.value.replace(/[^0-9]/g,'');" value="" required></td><?php }}
          elseif($re['type'][$i]=='double'){if($re['nl'][$i]==0){
            ?><td><input type="number" step="0.01"  id="fname" name=<?php echo $re['columnNames'][$i]; ?> onkeypress="return (event.charCode >= 48 && event.charCode <= 57) || event.charCode == 46 || event.charCode == 0 " value=Null ></td><?php
          }else{?><td><input type="number" step="0.01"  id="fname" name=<?php echo $re['columnNames'][$i]; ?> onkeypress="return (event.charCode >= 48 && event.charCode <= 57) || event.charCode == 46 || event.charCode == 0 " value="" required></td><?php }}
          elseif($re['type'][$i]=='boolean'){if($re['nl'][$i]==0){
              ?><td><select name=<?php echo $re['columnNames'][$i]; ?> ><?php } else {
              ?><td><select name=<?php echo $re['columnNames'][$i]; ?> required>
                <option  value="">--NULL--</option>
                <option value=true>True</option>
                <option value=false>False</option>
              </select></td><?php 
            }
          }
          else{
            if($re['nl'][$i]==0){
              ?><td><input type="text" id="fname" name=<?php echo $re['columnNames'][$i]; ?> value="" ></td><?php }
            else{
              ?><td><input type="text" id="fname" name=<?php echo $re['columnNames'][$i]; ?> value="" required></td><?php 
            }
          }
    }?>
    <td><input type="submit" name="sub" value="Add"></td>
</form> 
  </tr>
</table>
</div>
    <?php } ?>
<a class=""  href='fKey.php'>>Creat Foreign Key<</a><br>
<a class=""  href='home.php'>>Back<</a>
<?php
include_once('footer.php');
?>