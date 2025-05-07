<?php
	session_start();
	require_once "handlers/initDB.php";	
	$con = new myDB;
	$con->dbquery("delete from active_sessions where userid = '$_SESSION[userid]';");
	unset($_SESSION['m_userid']);
	unset($_SESSION['m_authkey']);
	unset($_SESSION['so_no']);
	unset($_SESSION['type']);
	session_destroy();
	
	$URL = "index.php";
	header ("Location: $URL");
	exit();

?>