<?php
include_once ("header.php");
require_once('fun.php');
if (isset($_POST['num_fields'])||isset($_SESSION['tbData'])) {
//if (isset($_POST['num_fields'])) {
    if(isset($_SESSION['tbData']))$tbName=$_SESSION['tbData']['tableInfo'];
    else $tbName= $_POST['tableName'];
    //echo $_SESSION['tbData']['columnNames_0'];

    if(checkTN($_SESSION['mdbInfo'],$tbName)){
        //echo 'Table Name: '. $_POST['tableName'];
        if(!isset($_SESSION['tbData'])){
            $data['tableInfo']=$_POST['tableName'];
            $data['columnNo']=$_POST['fields'];
            $data['fkNo']=0;
            $data['lastId']=0;
            $data['rowNo']=0;
            $_SESSION['Data']=$data;
        }//else 'Not-------------';
        echo 'Table Name: '. $_SESSION['Data']['tableInfo'];
        $num_filds= $_SESSION['Data']['columnNo'];              
        ?>
        <div>
        <fieldset class="tblFooters">
        <form action='' id="tbDt" method="POST">
        <table id="table_columns" class="pma-table noclick">
            
            <tbody>
                <tr>
                    <th>
                    Column Name        
                    </th>
                    <th>
                        Type           
                    </th>
                    <th>
                        P.Key            
                    </th>
                    <th>
                        A.I.        
                    </th>
                    <th>
                        Not-Null        
                    </th>
                </tr>
                <?php
                for($i=0;$i<$num_filds;$i++){
                ?>
                <tr>
                    <td class="text-center">
                        <input id="field_0_1" type="text" name="columnNames.<?php echo $i; ?>" maxlength="64" class="textfield" title="Column" size="10"  autofocus="" required="" <?php if(isset($_SESSION['tbData']['columnNames_'.$i])) { echo 'value="'. $_SESSION['tbData']["columnNames_".$i].'"'; } else {echo 'value=""'; } ?>>
                    </td>
                    <td class="text-center">
                        <select name="type.<?php echo $i; ?>" id="tp_field.<?php echo $i; ?>" data-index="">
                            
                            <option value="integer" title="Integer">
                            Integer
                            </option>
                            <option value="double" title="Float">
                            Float
                            </option>
                            <option value="string" title="Varchar">
                            Varchar
                            </option>
                            <option value="boolean" title="Boolean">
                            Boolean
                            </option>
                        </select>
                    </td>
                    <td class="text-center">
                        <input name="pk.<?php echo $i; ?>" id="pk_field.<?php echo $i; ?>" type="checkbox" value=1>
                    </td>
                    <td class="text-center">
                        <input name="ai.<?php echo $i; ?>" id="ai_field.<?php echo $i; ?>" type="checkbox" value=1>
                    </td>
                    <td class="text-center">
                        <input name="nl.<?php echo $i; ?>" id="ai_field.<?php echo $i; ?>" type="checkbox" value=1>
                    </td>
                </tr>
                    <?php

                }
                unset($_SESSION['tbData']);
                ?>
            </tbody>
        </table>
        <input class="btn btn-primary" type="submit" name="save_tb" value="Save">
        </form>
        </fieldset>
        </div>
        <?php
        }else{
            unset($_POST); ?>
            <script>
                alert("A Table already exists with this name, try with another Name");
                window.location.href ='createTB.php';
            </script>
            <?php
           
            //header("Location: createTB.php");
        }
    }
    elseif(isset($_POST['save_tb'])){
        //unset($_POST['save_tb']);
        //var_dump($_POST);
        $retForm=form($_POST,$_SESSION['Data']['columnNo']);
        $_SESSION['tbData']=$_POST;
        //echo $_SESSION['tbData']['columnNames_0'];
        unset($_POST);
        switch ($retForm) {
            case 'pkc1':
                ?>
                <script>
                    alert("A table can have only one Primary Key.");
                    window.location.href ='createTB.php';
                </script>
                <?php  
              break;
              case 'pkc2':
                ?>
                <script>
                    alert("A table must have a Primary Key.");
                    window.location.href ='createTB.php';
                </script>
                <?php
                break; 
            case 'pkc3':
                    ?>
                    <script>
                        alert("For Primary Key type should be Integer or Varchar.");
                        window.location.href ='createTB.php';
                    </script>
                    <?php
                    break;
              case 'nlc1':
                ?>
                <script>
                    alert("Primary Key must be Not Null.");
                    window.location.href ='createTB.php';
                </script>
                <?php  
              break;
              case 'aic1':
                ?>
                <script>
                    alert("A table can have only one auto increase.");
                    window.location.href ='createTB.php';
                </script>
                <?php 
              break;
              case 'aic2':
                ?>
                <script>
                    alert("only Primary Key can have auto increase.");
                    window.location.href ='createTB.php';
                </script>
                <?php  
              break;
              case 'aic3':
                ?>
                <script>
                    alert("Data Type mast be 'Integer' for auto increase.");
                    window.location.href ='createTB.php';
                </script>
                <?php  
              break;
            default:
                //unset($_SESSION['tbData']['num_fields']);
                $_SESSION['Data']=array_merge($_SESSION['Data'],$retForm);
                $retval=createTb($_SESSION['mdbInfo'],$_SESSION['Data']);
                    if(isset($retval)){
                        unset($_SESSION['tbData']);
                        unset($_SESSION['Data']);
                        ?>
                        <script>
                            alert("The Table has been created successfully.");
                            window.location.href ='home.php';
                        </script>
                        <?php 
                    }  
        }
    }else{
    ?>
        <div id="table_name_col_no_outer">
            <form action='' id="tbNm" method="POST">
                <table id="table_name_col_no" class="pma-table tdblock">
                    <tbody>
                        <tr class="vmiddle floatleft">
                            <td>Table name:
                            <input type="text" name="tableName" size="40" maxlength="64" value="" class="textfield" autofocus="" required="">
                            </td>
                            <td>
                                Add<input type="number" id="added_fields" name="fields" size="2" value="1" min="1" onfocus="this.select()">
                                column(s)<input class="btn btn-secondary" type="submit" name="num_fields" value="Go">
                            </td>
                        </tr>
                    </tbody>
                </table>
            </form>
        </div>
    <?php  
}
include_once('footer.php');
?>