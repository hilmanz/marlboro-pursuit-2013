<?php

/* Config Server */
/*
	1. Local
	2. Stage
	3. Prod
*/

$config = 1;

$rejectedDay = 13;
$deletedDay = 10;


/* Local */
$host[1] = "localhost";
$user[1] = "root";
$pass[1] = "";
$dbase[1] = "marlboro_pursuit_2013";

/* Stage */
$host[2] = "localhost";
$user[2] = "marlborostage";
$pass[2] = "ZiSx6hwiIhOY3MGzq4yqiRqOS4EhtY";
$dbase[2] = "marlborostage";

/* Prod */
$host[3] = "10.66.16.202";
$user[3] = "marlboro.ph2";
$pass[3] = "NDexb4t5235mLqn2crk3Oqw19ZIf85";
$dbase[3] = "marlboro.ph2";




mysql_connect($host[$config], $user[$config], $pass[$config]);
mysql_select_db($dbase[$config]);


run();

function run()
{
	setNstatus();
	selectInsertRejected();
	selectInsertDeleted();
}

function setNstatus()
{
	global $dbase, $config;
	
	$db = $dbase[$config];
	
	$sql = "SELECT id, register_date FROM {$db}.social_member WHERE verified = 0 ";
	$res = mysql_query($sql);
	while ($data = mysql_fetch_array($res)){
		$dataArr[] = $data;
	}

	if ($dataArr){
		$date = date('Y-m-d');
		foreach ($dataArr as $reg){
			$sql = "UPDATE {$db}.social_member SET n_status = 3 WHERE id = $reg[id] AND 
					(SELECT ABS(DATEDIFF('{$reg['register_date']}', '{$date}'))) > 7";
			$res = mysql_query($sql);
		}
		
	}
}


function selectInsertRejected()
{
	global $dbase, $config, $rejectedDay;
	
	$db = $dbase[$config];
	
	$date = date('Y-m-d');
	
	$sqlreject = "	INSERT INTO {$db}.social_member_deleted 
					SELECT a.*, NOW() FROM {$db}.social_member AS a 
					WHERE a.n_status = 3 AND (SELECT ABS(DATEDIFF(a.register_date, '{$date}'))) > {$rejectedDay}";
	$resultreject = mysql_query($sqlreject);
	// print_r($sqlreject);
	
	$sqlrejected = "DELETE FROM {$db}.social_member 
					WHERE n_status = 3 AND (SELECT ABS(DATEDIFF(register_date, '{$date}'))) > {$rejectedDay}";
	$resultrejected = mysql_query($sqlrejected);
	
	echo "Success";
}

function selectInsertDeleted()
{
	global $dbase, $config, $deletedDay;
	
	$db = $dbase[$config];
	
	$date = date('Y-m-d');
	
	$sqldelete = "	INSERT INTO {$db}.social_member_deleted 
					SELECT a.* FROM {$db}.social_member AS a 
					WHERE a.n_status = 4 AND (SELECT ABS(DATEDIFF(a.register_date, '{$date}'))) > {$deletedDay}";
	$resultdelete = mysql_query($sqldelete);
	
	
	$sqldeleted = "DELETE FROM {$db}.social_member 
					WHERE n_status = 4 AND (SELECT ABS(DATEDIFF(register_date, '{$date}'))) > {$deletedDay}";
	$resultdeleted = mysql_query($sqldeleted);
	
	echo "Success";
}

// echo '<pre>';
// print_r($sqldel);
// print_r($datarejected);

?>
