<<?php
include_once ("header.php");
require_once('fun.php');

if (isset($_POST['fknrt'])) {
    //$_SESSION['fk']['fkNo']=$_POST['fkNo']+1;
    //$_SESSION['fk']['fkName'][$_POST['fkNo']]=$_POST['fkName'];
    //$_SESSION['fk']['fkTable'][$_POST['fkNo']]=$_POST['fkTable'];
    $key=array('tableInfo'=>$_POST['fkTable']);
    $tbInfo=find_one($_SESSION['mdbInfo'],$key);
    for ($z = 0; $z <$tbInfo['columnNo']; $z++)echo $tbInfo['columnNames'][$z];
    //var_dump($tbInfo);
    ?>
        <div class="form-doc">
            <form action="" method="POST" name="rcs" >
            <input type='hidden' id="fNo" name='fkNo' value=<?php echo $_POST['fkNo']; ?>>
            <input type="hidden" id="fkNa" name='fkName' value=<?php echo $_POST['fkName']; ?>>
            <input type="hidden" id="ftb" name='fkTable' value=<?php echo $_POST['fkTable']; ?>>
            <label for="sfK">Selected a Foreign Key: <?php echo $_POST['fkName']; ?></label><br>
            <label for="srt">Selected Reference Table: <?php echo $_POST['fkTable']; ?></label><br>
            <label for="src">Select a Reference Column:</label>
            <select name="fkColumn" require>
                <option value="">--Select--</option>
                
                <option value="<?php echo $tbInfo['pk'];?>"  <?php if(isset($_POST['fkColumn']) && $_POST['fkcolumn']==$tbInfo['pk']) { ?>selected<?php  } ?>><?php echo $tbInfo['pk'];?></option>
                
            </select><br> 
            <input name="rc" id='rfk' type="submit" value="SUBMITE" tabindex="2">
            </form>
        </div>
    <?php 
}elseif(isset($_POST['rc'])) {
    unset($_POST['rc']);
    $data['fkNo']=$_POST['fkNo']+1;
    $data['fkName'][$_POST['fkNo']]=$_POST['fkName'];
    $data['fkTable'][$_POST['fkNo']]=$_POST['fkTable'];
    $data['fkColumn'][$_POST['fkNo']]=$_POST['fkColumn'];
    $x=addFK($_SESSION['mdbInfo'],$data,$_SESSION['selectedTable']);
    //echo $x;
    switch ($x) {
        case 0:
            ?>
              <script>
                alert("The Foreign Key added successfully.");
                window.location.href ='seeTB.php';
              </script>
            <?php  
          break;
          case 1:
            ?>
              <script>
                alert("Foreign Key could not added. Row(s) in selected Table.");
                window.location.href ='seeTB.php';
              </script>
            <?php
            break;
            case 2:
              ?>
                <script>
                  alert("Foreign Key could not added. Data type mismatch.");
                  window.location.href ='seeTB.php';
                </script>
              <?php  
            break;
            case 3:
                ?>
                  <script>
                    alert("Foreign Key could not added. Try again later.");
                    window.location.href ='seeTB.php';
                  </script>
                <?php
                break;
            }
}else {
    $selectedTable=$_SESSION['selectedTable'];
    $mdbInfo=$_SESSION['mdbInfo'];
    $i=0;
    $key=array('tableInfo'=>$selectedTable);
    $tInfo=find_one($mdbInfo,$key);
    if(($tInfo['columnNo']-$tInfo['fkNo']-1)>0) {
        $key=array('db'=>'RDMS');
        $dbInfo=find_one($mdbInfo,$key);
        /*echo $tInfo['fkNo'];
        echo "<br>";
        for ($z = 0; $z <$tInfo['columnNo']; $z++){unset($fk);echo $tInfo['columnNames'][$z]; echo "<br>";
            if($tInfo['fkNo']>0){$fk=strInArray($tInfo['fkName'],$tInfo['columnNames'][$z]);if(isset($fk)) echo $fk; echo "<br>";}}*/

        ?>
            <div class="form-doc">
              <form action="" method="POST" name="fkey" >
                <input type="hidden" id="fNo" name='fkNo' value=<?php echo $tInfo['fkNo']; ?>>
                <label for="fKey">Select a Foreign Key:</label>
                <select name="fkName" require>
                  <option value="">--Select--</option>
                  <?php
                    for ($z = 0; $z <$tInfo['columnNo']; $z++){unset($fk);
                        if($tInfo['fkNo'])$fk=strInArray($tInfo['fkName'],$tInfo['columnNames'][$z]);
                        if($tInfo['columnNames'][$z]!=$tInfo['pk']&& !isset($fk)){
                  ?>
                  <option value="<?php echo $tInfo['columnNames'][$z];?>"  <?php if(isset($_POST['fkName']) && $_POST['fkName']==$tInfo['columnNames'][$z]) { ?>selected<?php  } ?>><?php echo $tInfo['columnNames'][$z];?></option>
                  <?php }}?>
                </select><br>
                <label for="rtb">Select a Reference Table:</label>
                <select name="fkTable" require>
                  <option value="">--Select--</option>
                  <?php
                    for ($z = 0; $z <$dbInfo['tableNo']; $z++){ if($dbInfo['tableNames'][$z]!=$selectedTable){
                  ?>
                  <option value="<?php echo $dbInfo['tableNames'][$z];?>"  <?php if(isset($_POST['fkTable']) && $_POST['fkTable']==$dbInfo['tableNames'][$z]) { ?>selected<?php  } ?>><?php echo $dbInfo['tableNames'][$z];?></option>
                  <?php }}?>
                </select>
                <input name="fknrt" id='rfk' type="submit" value="SUBMITE" tabindex="2">
              </form>
            </div>
<?php 
    }else echo 'No Key available for Foreign Key.';
    ?>
<?php } ?><br>
<a class=""  href='seeTB.php'>>Back<</a>
<?php
include_once('footer.php');
?>