<?php
	session_start();
	include("../includes/dbUSEi.php");
	
	$data = array();
	
	$datares = $con->query("select lpad(sw_no,6,0) as docno, date_format(sw_date,'%m/%d/%Y') as sdate, withdrawn_by, remarks, format(amount,2) as amount, status from sw_header where branch = '1';");
	while($row = $datares->fetch_array(MYSQLI_ASSOC)){
	  $data[] = array_map('utf8_encode',$row);
	}
	$results = ["sEcho" => 1,"iTotalRecords" => count($data),"iTotalDisplayRecords" => count($data),"aaData" => $data];
	echo json_encode($results);
	@mysqli_close($con);
?>