<?php
	session_start();

	ini_set("max_execution_time",-1);
	ini_set("memory_limit",-1);
	
	include("../handlers/_generics.php");
	$con = new _init;

	if($_SESSION['so_no'] != '') { $myso = " and so_no = '$_SESSION[so_no]' "; }

	$data = array();
	$datares = $con->dbquery("SELECT record_id AS id, LPAD(so_no,6,'0') AS so, DATE_FORMAT(so_date,'%m/%d/%Y') AS sdate, CONCAT(b.lname,', ',b.fname,' ',b.mname) AS pname, gender, '' AS age, a.code, a.procedure, a.so_date, b.birthdate, a.pid FROM lab_samples a LEFT JOIN pccmain.patient_info b ON a.pid = b.patient_id WHERE a.status = '1' and a.extracted != 'Y' and a.code not in ('O009') $myso;");

    while($row = $datares->fetch_array()){
		$row['age'] = $con->calculateAge($row['so_date'],$row['birthdate']);
        $data[] = array_map('utf8_encode',$row);

	}
	$results = ["sEcho" => 1,"iTotalRecords" => count($data),"iTotalDisplayRecords" => count($data),"aaData" => $data];
	echo json_encode($results);

?>