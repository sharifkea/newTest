<?php

require_once __DIR__ . '/vendor/autoload.php';

function create($sec,$toDb){
    $murl=$sec['txtMurl'];
    $coll=$sec['collection'];
    $doc=$sec['document'];
    //unset($toDb['submit']);
    try{
        $client = new MongoDB\Client($murl);
        $db = $client->$doc->$coll;// Mongodb 
        $insertOneResult = $db->insertOne($toDb);
        return true;
    }catch ( e ) {
        return false;
    }
}
function checkTN($sec,$tbNm){
    $murl=$sec['txtMurl'];
    $coll=$sec['collection'];
    $doc=$sec['document'];
    $colldb=($doc.'.'.$coll);
    $key= array("tableInfo"=>$tbNm);
    $options= [];
    try{
        $client = new MongoDB\Client($murl);
        $db = $client->$doc->$coll;
        $return = $db->findOne($key);
        if(isset($return->tableInfo))
        return false;
        else return true;
    }
    catch ( Exception $e) {
        return $e->getMessage();
    }
}
function update($sec,$where,$what) {
    $murl=$sec['txtMurl'];
    $coll=$sec['collection'];
    $doc=$sec['document'];
    $client = new MongoDB\Client($murl);
    $collection = $client->selectCollection($doc,$coll);
    if($collection->updateMany($where,['$set' => $what]))return true;
    else return false;
}
function find_one($sec,$key) {
    $murl=$sec['txtMurl'];
    $coll=$sec['collection'];
    $doc=$sec['document'];
    $client = new MongoDB\Client($murl);
    $db = $client->$doc->$coll;
    return $db->findOne($key);
}
function del($sec,$which) {
    $murl=$sec['txtMurl'];
    $coll=$sec['collection'];
    $doc=$sec['document'];
    $client = new MongoDB\Client($murl);
    //require_once __DIR__ . '/mongo.php';
    $collection = $client->selectCollection($doc,$coll);
    $r= $collection->deleteMany($which);
    return $r;    
}
function query($sec,$which) {
    $murl=$sec['txtMurl'];
    $coll=$sec['collection'];
    $doc=$sec['document'];
    $colldb=$doc.'.'.$coll;
    $options= [];
    $query = new MongoDB\Driver\Query($which, $options);
    $manager = new MongoDB\Driver\Manager($murl);
    $rows = $manager->executeQuery($colldb, $query);
    return $rows;    
}

function form($post,$colm) {
    //var_dump($post);
    //echo $colm;
    for($i=0;$i<$colm;$i++){

        $columnNames[$i]=$post['columnNames_'.$i];
        //echo $columnNames[$i];
        //echo "<br>";
        $type[$i]=$post['type_'.$i];
        if(isset($post['nl_'.$i]))$nl[$i]=1;
        else $nl[$i]=0;

        if(isset($post['pk_'.$i])){
            if(!isset($pk))
                {$pk=$columnNames[$i];}
            else
                {$out='pkc1'; break;}
            if($nl[$i]==0){$out='nlc1';break;}
            if($type[$i]!='integer'&&$type[$i]!='string'){$out='pkc3';break;}
        }
        if(isset($post['ai_'.$i])){
            if(!isset($ai))
                $ai=$columnNames[$i];
            else
                {$out='aic1';break;}
            if($ai!=$pk){$out='aic2';break;}
            elseif($type[$i]!='integer'){$out='aic3';break;}
        }
    }if(!isset($pk)) return 'pkc2';
    elseif(isset($out)) return $out;
    else{
        $ret['columnNames']=$columnNames;
        $ret['type']=$type;
        $ret['nl']=$nl;
        $ret['pk']=$pk;
        $ret['ai']=$ai;
        return $ret;
    } 
}
function createTb($sec,$data){
    
    $key=array('db'=>"RDMS");
    $re=find_one($sec,$key);
    $dt = array($re->tableNo => $data['tableInfo']);
    
    if($re->tableNo==0){
        $re->tableNames=$dt;
    }
    else{
        $ary=$re->tableNames;
        $myArray = json_decode(json_encode($ary), true);
        $myArray=array_merge($myArray,$dt);
        $re->tableNames=$myArray;
    }
    $re->tableNo =$re->tableNo+1;

    $delret=del($sec,$key);
    if(isset($delret)){
        $retval=create($sec,$re);
        if(isset($retval)){
            if(create($sec,$data)) return true;
        }
    }
    return false;
}
function strInArray($arr, $keyword) {
    foreach($arr as $index => $string) {
        //array_push($array,"blue","yellow");
        //echo 'key: '.$index.'-data:'.$string;
        if (strpos($string, $keyword) !== FALSE)
            //{echo 'return: '.$index;
            return $index;//}
    }
}
function addFK($mdbInfo,$data,$selectedTable){
    $key=array('tableInfo'=>$selectedTable);
    $tbif=find_one($mdbInfo,$key);
    $tbif = json_decode(json_encode($tbif), true);
    //echo $tbif['rowNo'];
    $e=0;
    if($tbif['rowNo']>0){
        $e=1; return $e;
    }
    $fkckey=array('tableInfo'=>$data['fkTable'][$data['fkNo']-1]);
    $fkcif=find_one($mdbInfo,$fkckey);
    $fkcif = json_decode(json_encode($fkcif), true);
    //var_dump ($fkcif['columnNames']);
    //echo $data['fkColumn'][$data['fkNo']-1]; 
    $fkt=strInArray($tbif['columnNames'],$data['fkName'][$data['fkNo']-1]);
    $fkct=strInArray($fkcif['columnNames'],$data['fkColumn'][$data['fkNo']-1]);
    //echo $fkcif['type'][$fkct];
    if($tbif['type'][$fkt]!=$fkcif['type'][$fkct]){$e=2; return $e;}
        
        
    $tbif['fkNo']=$data['fkNo'];
    $tbif['fkName'][$data['fkNo']-1]=$data['fkName'][$data['fkNo']-1];
    $tbif['fkTable'][$data['fkNo']-1]=$data['fkTable'][$data['fkNo']-1];
    $tbif['fkColumn'][$data['fkNo']-1]=$data['fkColumn'][$data['fkNo']-1];
    unset($tbif['_id']);
    
    $delret=del($mdbInfo,$key);
    if(isset($delret)){
        $retval=create($mdbInfo,$tbif);
            if(isset($retval)){return $e;}
    }
    else $e=3; return $e;
    }

function inTbRow($mdbInfo,$data){
    $tableName=$data['tableName'];
    $i=0;
    $key=array('tableInfo'=>$data['tableName']);
    $tbif=find_one($mdbInfo,$key);
    $tbif = json_decode(json_encode($tbif), true);
    foreach($tbif['columnNames'] as $index => $value){
        if($tbif['nl'][$index]==0 && !isset($data[$value]))$data[$value]=NULL;
    }
    //var_dump ($tbif);
    foreach($data as $index => $value){
        if($index!='tableName'){
            $cn=strInArray($tbif['columnNames'],$index);
            if($tbif['type'][$cn]=='integer'){$data[$index]=intval($value);}
            if($tbif['type'][$cn]=='boolean'){if($data[$index]=='true')$data[$index]=true;else $data[$index]=false;}
            if(gettype($data[$index])!=$tbif['type'][$cn]) {$i=2;  return $i;}
        }
        unset($fk);
        if($tbif['fkNo']>0){
            $fk=strInArray($tbif['fkName'],$index);
            if(isset($fk)){
                $fkey=array('tableName'=>$tbif['fkTable'][$fk],$tbif['fkColumn'][$fk]=>$data[$index]);
                //var_dump($fkey);
                $ret=find_one($mdbInfo,$fkey);
                if(!isset($ret)) {$i=3;  return $i;}
                //else echo var_dump ($ret);
            }
        }
    }
    if(!isset($tbif['ai'])){$fpk=array('tableName'=>$tableName, $tbif['pk']=>$data[$tbif['pk']]);
       //var_dump ($fpk);
        $pkRow=find_one($mdbInfo,$fpk);
        if($pkRow){
            $i=1;  return $i;
        }
    }
    if(create($mdbInfo,$data))
    {
        $what=array('lastId'=>$data[$tbif['pk']]);
        $upRet=update($mdbInfo,$key,$what);
        $what=array('rowNo'=>$tbif['rowNo']+1);
        $upRet=update($mdbInfo,$key,$what);
        return $i;
    }else {$i=4;  return $i;}
}
function edTbRow($mdbInfo,$data){
    if($data['pkType']=='integer')$data['oldpkv']=intval($data['oldpkv']);
    $oldpk=$data['oldpkv'];
    unset($data['oldpkv']);
    unset($data['pkType']);
    unset($data['pkIndex']);
    $i=0;
    $tableName=$data['tableName'];
    $key=array('tableInfo'=>$tableName);
    $tbif=find_one($mdbInfo,$key);
    $tbif = json_decode(json_encode($tbif), true);
    $oldkey=array('tableName'=>$tableName, $tbif['pk']=>$oldpk);
    $oldrow=find_one($mdbInfo,$oldkey);
    $dbKey=array('db'=>'RDMS');
    $dbif=find_one($mdbInfo,$dbKey);
    $dbif = json_decode(json_encode($dbif), true);
    if(!isset($tbif['ai']) && $oldpk!=$data[$tbif['pk']]){$fpk=array('tableName'=>$tableName,$tbif['pk']=>$data[$tbif['pk']]);
        if(find_one($mdbInfo,$fpk)){
            $i=1; return $i;
        }
    }
    if($oldpk!=$data[$tbif['pk']]){
        if(fkRowIn($mdbInfo,$tableName,$oldpk)) {$i=2; return $i;} 
    }
    foreach($tbif['columnNames'] as $index => $value){
        if($tbif['nl'][$index]==0 && !isset($data[$value]))$data[$value]=NULL;
    }
    foreach($data as $index => $value){
        if($index!='tableName'){
            $cn=strInArray($tbif['columnNames'],$index);
            if($tbif['type'][$cn]=='integer'){$data[$index]=intval($value);if($index==$tbif['pk'])$oldkey[$tbif['pk']]=intval($oldkey[$tbif['pk']]); }
            if($tbif['type'][$cn]=='boolean'){if($data[$index]=='true')$data[$index]=true;else $data[$index]=false;}
            if(gettype($data[$index])!=$tbif['type'][$cn]) {$i=3; return $i;}
        }
        unset($fk);
        if($tbif['fkNo']>0){
            $fk=strInArray($tbif['fkName'],$index);
            if(isset($fk)){
                $fkey=array('tableName'=>$tbif['fkTable'][$fk],$tbif['fkColumn'][$fk]=>$data[$index]);
                //var_dump($fkey);
                $ret=find_one($mdbInfo,$fkey);
                if(!isset($ret)) { $i=4; return $i; }
                //else echo var_dump ($ret);
            }
        }
    }
    if(del($mdbInfo,$oldkey)){
        if(create($mdbInfo,$data))
        {
            $what=array('lastId'=>$data[$tbif['pk']]);
            $upRet=update($mdbInfo,$key,$what);
            return $i;
        }
    }else {$i=5; return $i;}
}

function dlTbRow($mdbInfo,$data){
    $e=0;
    $tableName=$data['tableName'];
    
    $key=array('tableInfo'=>$tableName);
    $tbif=find_one($mdbInfo,$key);
    $tbif = json_decode(json_encode($tbif), true);

    if($data['pkType']=='integer')$data['oldpkv']=intval($data['oldpkv']);   
    if(fkRowIn($mdbInfo,$tableName,$data['oldpkv'])) return false;
    
    $rowKey=array('tableName'=>$tableName,$data['pkIndex']=>$data['oldpkv']);
    if(del($mdbInfo,$rowKey)){
        $what=array('rowNo'=>$tbif['rowNo']-1);
        if(update($mdbInfo,$key,$what))return true;
    }else return false;    
}

function fkRowIn($mdbInfo,$table,$fkV){
    $i=false;    
    $dbKey=array('db'=>'RDMS');
    $dbif=find_one($mdbInfo,$dbKey);
    $dbif = json_decode(json_encode($dbif), true);

    foreach($dbif['tableNames'] as $index => $value){
        if ($value!=$table){
            $newKey=array('tableInfo'=>$value);
            $newtbif=find_one($mdbInfo,$newKey);
            $newtbif = json_decode(json_encode($newtbif), true);
            if($newtbif['fkNo']>0){
                foreach($newtbif['fkTable'] as $fktindex => $fktvalue){
                    if($fktvalue==$table){
                        $newfkC=$newtbif['fkName'][$fktindex];
                        $findFK=array('tableName'=>$value,$newfkC=>$fkV);
                        if(find_one($mdbInfo,$findFK))$i=true; 
                    }
                }
            }
        }
    }
    return $i;
}
function delTb($mdbInfo,$table){
    $dbKey=array('db'=>'RDMS');
    $dbif=find_one($mdbInfo,$dbKey);
    $dbif = json_decode(json_encode($dbif), true);

    foreach($dbif['tableNames'] as $index => $value){
        if ($value!=$table){
            $newKey=array('tableInfo'=>$value);
            $newtbif=find_one($mdbInfo,$newKey);
            $newtbif = json_decode(json_encode($newtbif), true);
            if($newtbif['fkNo']>0){
                foreach($newtbif['fkTable'] as $fktindex => $fktvalue){
                    if($fktvalue==$table) return false; 
                }
            }
        }
        else $tbInd=$index;
    }
    if(isset($tbInd)){
        $tbRoKey=array('tableName'=>$table);
        if(del($mdbInfo,$tbRoKey)){
            $tbIfKey=array('tableInfo'=>$table);
            if(del($mdbInfo,$tbIfKey)) 
                if(del($mdbInfo,$dbKey)) {
                array_splice($dbif['tableNames'], $tbInd, 1);
                $dbif['tableNo']=$dbif['tableNo']-1;
                unset($dbif['_id']);
                if(create($mdbInfo,$dbif)) return true;
            }
        }
    }else
    return false;
}
?>