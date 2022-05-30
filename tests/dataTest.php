<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;
require_once("fun.php");

final class dataTest extends TestCase

{   
    public function test_create(){
    
        $sec=array('txtMurl'=>'mongodb+srv://ronysharif:rony2204@sharifmdb.px3qb.mongodb.net/newdb?retryWrites=true&w=majority','document'=>'newdb','collection'=>'test');
        $toDb=array('dbInfo'=>"testDB",'tableNo'=>0,'host'=>"root",'password'=>"rony2204",'user'=>"rony",'db'=>"RDMS");   
        $result=create($sec,$toDb);
        $expected=true;
        $this->assertEquals($expected, $result);
    }
    
    public function test_find_one(){
        $sec=array('txtMurl'=>'mongodb+srv://ronysharif:rony2204@sharifmdb.px3qb.mongodb.net/newdb?retryWrites=true&w=majority','document'=>'newdb','collection'=>'test');
        $key=array('db'=>'RDMS');
        $result=find_one($sec,$key);
        $expected='testDB';
        $this->assertEquals($expected, $result->dbInfo);
    } 
    public function test_update(){
        $sec=array('txtMurl'=>'mongodb+srv://ronysharif:rony2204@sharifmdb.px3qb.mongodb.net/newdb?retryWrites=true&w=majority','document'=>'newdb','collection'=>'test');
        $where=array('db'=>'RDMS');
        $what=array('dbInfo'=>'updateDB');
        $result=update($sec,$where,$what);
        $expected=true;
        $this->assertEquals($expected, $result);
    }
    public function test_find_one_1(){
        $sec=array('txtMurl'=>'mongodb+srv://ronysharif:rony2204@sharifmdb.px3qb.mongodb.net/newdb?retryWrites=true&w=majority','document'=>'newdb','collection'=>'test');
        $key=array('db'=>'RDMS');
        $result=find_one($sec,$key);
        $expected='updateDB';
        $this->assertEquals($expected, $result->dbInfo);
    }
    public function test_update_1(){
        $sec=array('txtMurl'=>'mongodb+srv://ronysharif:rony2204@sharifmdb.px3qb.mongodb.net/newdb?retryWrites=true&w=majority','document'=>'newdb','collection'=>'test');
        $where=array('db'=>'RDMS');
        $what=array('dbInfo'=>'testDB');
        $result=update($sec,$where,$what);
        $expected=true;
        $this->assertEquals($expected, $result);
    }
    public function test_query(){
        $sec=array('txtMurl'=>'mongodb+srv://ronysharif:rony2204@sharifmdb.px3qb.mongodb.net/newdb?retryWrites=true&w=majority','document'=>'newdb','collection'=>'test');
        $which=array('db'=>'RDMS');
        $result=query($sec,$which);
        foreach($result as $eachRe)
        $expected='testDB';
        $this->assertEquals($expected, $eachRe->dbInfo);
    }
    

    public function test_checkTN(){
        $sec=array('txtMurl'=>'mongodb+srv://ronysharif:rony2204@sharifmdb.px3qb.mongodb.net/newdb?retryWrites=true&w=majority','document'=>'newdb','collection'=>'test');
        $tbName='users';
        $result=checkTN($sec,$tbName);
        $expected=true;
        $this->assertEquals($expected, $result);
    }
    public function test_strInArray(){
        $array=array('txtMurl'=>'mongodb+srv','document'=>'newdb','collection'=>'test');
        $key='mongodb+srv';
        $result= strInArray($array,$key);
        $expected='txtMurl';
        $this->assertEquals($expected, $result);
    }
    public function test_strInArray_1(){
        $array=array('txtMurl'=>'mongodb+srv://ronysharif:rony2204@sharifmdb.px3qb.mongodb.net/newdb?retryWrites=true&w=majority','document'=>'newdb','collection'=>'test');
        $key='test';
        $result= strInArray($array,$key);
        $expected='collection';
        $this->assertEquals($expected, $result);
    }
    
    public function test_form(){
        $colm='3';
        $post=array ('columnNames_0' =>'id','type_0' => 'integer','pk_0' => '1','ai_0' =>'1','nl_0' => '1','columnNames_1' =>'name',
        'type_1' => 'string','nl_1' => '1','columnNames_2' => 'age','type_2' => 'integer','nl_2' => '1','save_tb' =>'Save');
        $result=form($post,$colm);
        $expected=array('columnNames'=>array(0=>"id",1=>"name",2=>"age"),'type'=>Array(0=>"integer",1=>"string",2=>"integer"),'nl'=>Array(0=>1,1=>1,2=>1),'pk'=>"id",'ai'=>"id");
        $this->assertEquals($expected, $result);
    }
    public function test_createTb(){
        $sec=array('txtMurl'=>'mongodb+srv://ronysharif:rony2204@sharifmdb.px3qb.mongodb.net/newdb?retryWrites=true&w=majority','document'=>'newdb','collection'=>'test');
        $data=array('fkNo'=>0,'lastId'=>0,'rowNo'=>0,'tableInfo'=>'users','columnNo'=>'3','columnNames'=>array(0=>"id",1=>"name",2=>"age"),'type'=>Array(0=>"integer",1=>"string",2=>"integer"),'nl'=>Array(0=>1,1=>1,2=>1),'pk'=>"id",'ai'=>"id");
        $result= createTb($sec,$data);
        $expected=true;
        $this->assertEquals($expected, $result);
    }
    public function test_createTb_1(){
        $sec=array('txtMurl'=>'mongodb+srv://ronysharif:rony2204@sharifmdb.px3qb.mongodb.net/newdb?retryWrites=true&w=majority','document'=>'newdb','collection'=>'test');
        $data=array('fkNo'=>0,'lastId'=>0,'rowNo'=>0,'tableInfo'=>'usercountry','columnNo'=>'3','columnNames'=>array(0=>"id",1=>"userId",2=>"countryId"),'type'=>Array(0=>"integer",1=>"integer",2=>"integer"),'nl'=>Array(0=>1,1=>1,2=>1),'pk'=>"id",'ai'=>"id");
        $result= createTb($sec,$data);
        $expected=true;
        $this->assertEquals($expected, $result);
    }
    public function test_createTb_2(){
        $sec=array('txtMurl'=>'mongodb+srv://ronysharif:rony2204@sharifmdb.px3qb.mongodb.net/newdb?retryWrites=true&w=majority','document'=>'newdb','collection'=>'test');
        $data=array('fkNo'=>0,'lastId'=>0,'rowNo'=>0,'tableInfo'=>'countries','columnNo'=>'2','columnNames'=>array(0=>"id",1=>"country"),'type'=>Array(0=>"integer",1=>"string"),'nl'=>Array(0=>1,1=>1),'pk'=>"id",'ai'=>"id");
        $result= createTb($sec,$data);
        $expected=true;
        $this->assertEquals($expected, $result);
    }
    public function test_addFK(){
        $sec=array('txtMurl'=>'mongodb+srv://ronysharif:rony2204@sharifmdb.px3qb.mongodb.net/newdb?retryWrites=true&w=majority','document'=>'newdb','collection'=>'test');
        $data=array('fkNo'=>1,'fkName'=>array(0=>'userId'),'fkTable'=>array(0=>'users'),'fkColumn'=>array(0=>'id'));
        $table='usercountry';
        $result= addFK($sec,$data,$table);
        $expected=0;
        $this->assertEquals($expected, $result);
    }
    public function test_addFK_1(){
        $sec=array('txtMurl'=>'mongodb+srv://ronysharif:rony2204@sharifmdb.px3qb.mongodb.net/newdb?retryWrites=true&w=majority','document'=>'newdb','collection'=>'test');
        $data=array('fkNo'=>2,'fkName'=>array(1=>'countryId'),'fkTable'=>array(1=>'countries'),'fkColumn'=>array(1=>'id'));
        $table='usercountry';
        $result= addFK($sec,$data,$table);
        $expected=0;
        $this->assertEquals($expected, $result);
    }
     public function test_checkTN_1(){
        $sec=array('txtMurl'=>'mongodb+srv://ronysharif:rony2204@sharifmdb.px3qb.mongodb.net/newdb?retryWrites=true&w=majority','document'=>'newdb','collection'=>'test');
        $tbName='users';
        $result=checkTN($sec,$tbName);
        $expected=false;
        $this->assertEquals($expected, $result);
    } 
    public function test_inTbRow(){
        $sec=array('txtMurl'=>'mongodb+srv://ronysharif:rony2204@sharifmdb.px3qb.mongodb.net/newdb?retryWrites=true&w=majority','document'=>'newdb','collection'=>'test');
        $data=array('tableName'=>"users",'id'=>1,'name'=>"rony",'age'=>22);
        $result= inTbRow($sec,$data);
        $expected=0;
        $this->assertEquals($expected, $result);
    }
    public function test_edTbRow(){
        $sec=array('txtMurl'=>'mongodb+srv://ronysharif:rony2204@sharifmdb.px3qb.mongodb.net/newdb?retryWrites=true&w=majority','document'=>'newdb','collection'=>'test');
        $post=array('tableName'=>"users",'id'=>1,'name'=>"omar",'age'=>22,'oldpkv'=>'1','pkType'=>'integer','pkIndex'=>'id');
        $result=edTbRow($sec,$post);
        $expected=0;
        $this->assertEquals($expected, $result);
    }
    public function test_fkRowIn(){
        $sec=array('txtMurl'=>'mongodb+srv://ronysharif:rony2204@sharifmdb.px3qb.mongodb.net/newdb?retryWrites=true&w=majority','document'=>'newdb','collection'=>'test');
        $table="users";
        $fkV=1;
        $result=fkRowIn($sec,$table,$fkV);
        $expected=false;
        $this->assertEquals($expected, $result);
    }
    public function test_dlTbRow(){
        $sec=array('txtMurl'=>'mongodb+srv://ronysharif:rony2204@sharifmdb.px3qb.mongodb.net/newdb?retryWrites=true&w=majority','document'=>'newdb','collection'=>'test');
        $post=array('oldpkv'=>'1','pkType'=>'integer','pkIndex'=>'id','tableName'=>'users');
        $result=dlTbRow($sec ,$post);
        $expected=true;
        $this->assertEquals($expected, $result);
    }
    public function test_delTb_1(){
        $sec=array('txtMurl'=>'mongodb+srv://ronysharif:rony2204@sharifmdb.px3qb.mongodb.net/newdb?retryWrites=true&w=majority','document'=>'newdb','collection'=>'test');
        $which='usercountry';
        $result=delTb($sec,$which);
        $expected=true;
        $this->assertEquals($expected, $result);
    }
    public function test_delTb(){
        $sec=array('txtMurl'=>'mongodb+srv://ronysharif:rony2204@sharifmdb.px3qb.mongodb.net/newdb?retryWrites=true&w=majority','document'=>'newdb','collection'=>'test');
        $which='users';
        $result=delTb($sec,$which);
        $expected=true;
        $this->assertEquals($expected, $result);
    }
    
    public function test_delTb_3(){
        $sec=array('txtMurl'=>'mongodb+srv://ronysharif:rony2204@sharifmdb.px3qb.mongodb.net/newdb?retryWrites=true&w=majority','document'=>'newdb','collection'=>'test');
        $which='users';
        $result=delTb($sec,$which);
        $expected=false;
        $this->assertEquals($expected, $result);
    }
    public function test_delTb_2(){
        $sec=array('txtMurl'=>'mongodb+srv://ronysharif:rony2204@sharifmdb.px3qb.mongodb.net/newdb?retryWrites=true&w=majority','document'=>'newdb','collection'=>'test');
        $which='countries';
        $result=delTb($sec,$which);
        $expected=true;
        $this->assertEquals($expected, $result);
    }
    public function test_del(){
        $sec=array('txtMurl'=>'mongodb+srv://ronysharif:rony2204@sharifmdb.px3qb.mongodb.net/newdb?retryWrites=true&w=majority','document'=>'newdb','collection'=>'test');
        $which=array('db'=>'RDMS');
        $result=del($sec,$which);
        $expected=1;
        $this->assertEquals($expected, $result->getDeletedCount());
    }
}