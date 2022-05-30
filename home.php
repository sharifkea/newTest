<?php
include_once ("header.php");
require_once('fun.php');
require_once __DIR__ . '/vendor/autoload.php';
//var_dump($_SESSION);
$murl=$_SESSION['mdbInfo']['txtMurl'];
$doc=$_SESSION['mdbInfo']['document'];
$coll=$_SESSION['mdbInfo']['collection'];


$client = new MongoDB\Client($murl);
$manager = new MongoDB\Driver\Manager($murl);
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    //Something posted
  
    if (isset($_POST['crtDB'])) {
        header("Location: createDB.php"); 
    } 
    elseif (isset($_POST['crtTB'])) {
        
        header("Location: createTB.php"); 
    }
    elseif (isset($_POST['addTB'])) {
        header("Location: createTB.php"); 
    }
    elseif (isset($_POST['seeTB'])) {
        $_SESSION['selectedTable']=$_POST['tableName'];
        header("Location: seeTB.php"); 
    }
    elseif (isset($_POST['seeTBInfo'])) {
        $_SESSION['selectedTable']=$_POST['tableName'];
        header("Location: seeTBInfo.php"); 
    }
    elseif (isset($_POST['delTB'])) {
        //$_SESSION['selectedTable']=$_POST['tableName'];
        if(delTb($_SESSION['mdbInfo'],$_POST['tableName'])){
            ?>
              <script>
                alert("Table successfully Deleted.");
                window.location.href ='home.php';
              </script>
            <?php
          }else{
            ?>
              <script>
                alert("This Table could not be delered. The key has record as a Foreign key in other table(s).");
                window.location.href ='home.php';
              </script>
            <?php  
          } 

    }
}else{
    if($_SESSION['mdbInfo']['empty']) {echo 'It is an Empty Collection.';
        //var_dump($_SESSION['mdbInfo']);
        ?> 
        <form action="" method="POST" name="createDB" >
            <label for="createDb">To create Database:</label>
            <input type="submit" name="crtDB" value="Create DB">
        </form>        
        
        <?php
        
        
    }
    else {
    
        $key=array('db'=>"RDMS");
        $return=find_one($_SESSION['mdbInfo'],$key);
        //$_SESSION["infoDB"]=$return;
        echo 'Database Name:'.$return->dbInfo;
        echo "<br>";
        echo "SQL Info: Host: '".$return->host."', Password:'".$return->password."', User:'".$return->user."'.<br>"; 
       
        echo "Relational MongoDB Info: Database: '".$doc."', Collection:'".$coll."'.<br>"; 
        $tableNo = $return->tableNo;
        if($tableNo==0){
            echo 'No table found.';
            ?> 
            <form action="" method="POST" name="createTB" >
                <label for="createTb">To create Table:</label>
                <input type="submit" name="crtTB" value="Create Table">
            </form>        
        
            <?php
        }else{
            $tableNames = $return->tableNames;
            foreach($tableNames as $z => $z_value){
                echo "<br>";
                echo "Table-" . $z+1 . ":". $z_value;
                ?>
                <form action="" method="POST" name="addseedel" >
                    <input id="tbid" name="tableId" type="hidden" value= <?php echo $z; ?> >
                    <input id="ibnm" name="tableName" type="hidden" value= <?php echo $z_value; ?> >
                    <input type="submit" name="seeTB" value="See Table">
                    <input type="submit" name="seeTBInfo" value="See Table Info">
                    <input type="submit" name="delTB" value="Delete Table">
                </form>
                <?php
            }
            ?> 
            <form action="" method="POST" name="addTB" >
                <label for="createTb">To Add a Table:</label>
                <input type="submit" name="addTB" value="Add Table">
            </form>        
        
            <?php
        }
    }
}
include_once('footer.php');
?>  