
<?php
session_start();
//var_dump($_SESSION);
if(!isset($_SESSION['Murl'])){
header("Location: index.php");
exit(); }
?>