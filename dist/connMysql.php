<?php
// ** 
    $db_host = "sql304.byethost18.com";
    $db_user = "b18_19883081";
    $db_pwd =  "jmispace@0112";
    $db_name = "b18_19883081_workspace";

 //    try{
 //    	$DB_con = new PDO("mysql:host={$db_host};dbname={$db_name}",$db_user,$db_pwd);
 //    	$DB_con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
 //    }

	// catch(PDOException $e)
	// {
	//  echo $e->getMessage();
	// }

	try{
    	$DB_con = new pdo( 
                    "mysql:host=" . $db_host . ";dbname=" . $db_name, $db_user, $db_pwd,
                    array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
     	// $DB_con -> exec("SET CHARACTER SET utf8");
	}
	catch(PDOException $ex){
	    die(json_encode(array('outcome' => false, 'message' => $ex->getMessage())));
	}




	include_once 'class.crud.php';

	$crud = new crud($DB_con);


?>