<?php
    include_once ("header.php");
    require_once('fun.php');
    //var_dump($_SESSION);
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['submit'])) {
            unset($_POST['submit']);
            $ret=create($_SESSION['mdbInfo'],$_POST);
            if ($ret){
                $_SESSION['mdbInfo']['empty']=false;
                ?>
                <script>
                    alert("The database has been created successfully");
                    window.location.href ='home.php';
                </script>
                <?php  
            }else{
                echo '<script>alert("Something went wrong, The database was not created.")</script>';
                //header("Location: home.php");
            }
            //echo $ret;
            //header("Location: home.php");
        } 
    }else{
    ?>
    <div>
        
        <form action='' id="ctDb" method="POST">
            <input id="dbN" name="dbInfo"  placeholder="Database Name" type="text"  required tabindex="1"><br>
            <input id="tbN" name="tableNo" type="hidden" value=0 >
            <input id="hst" name="host"  placeholder="Host" type="text"  required tabindex="2"><br>
            <input id="pw" type="password"  name="password" placeholder="Password" required tabindex="3"><br>
            <input id="us" placeholder="User" type="text" name="user"  required tabindex="4"><br>
            <input id="db" name="db" type="hidden" value="RDMS" >
            <input name="submit" id="sb" type="submit" value="Submit" tabindex="5">
        </from>
    </div>
    
    <?php
    }
    include_once('footer.php');
?>