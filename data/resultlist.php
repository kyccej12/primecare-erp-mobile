<?php
	session_start();
	ini_set('max_execution_time',0);
	ini_set('memory_limit',-1);
	
	include("../handlers/_generics.php");
	
	$con = new _init;

	if($_SESSION['so_no'] != '') { $myso = " and a.so_no = '$_SESSION[so_no]' "; }

	$data = array();
	$datares = $con->dbquery("SELECT a.record_id AS id, a.pid, LPAD(a.so_no,6,0) AS sono, DATE_FORMAT(a.so_date,'%m/%d/%Y') AS sodate, CONCAT(b.lname,', ',b.fname,' ',b.mname) AS pname, '' AS age, IF(b.gender='M','Male','Female') AS gender,b.employer, d.customer_name,a.procedure, IF(a.released='Y','Yes','No') AS released, c.fullname AS rby, IF(release_date IS NOT NULL,DATE_FORMAT(release_date,'%m/%d/%Y'),'') AS rdate, release_mode, released_to,a.code, a.serialno, a.so_date, b.birthdate FROM lab_samples a LEFT JOIN pccmain.patient_info b ON a.pid = b.patient_id LEFT JOIN pccmain.user_info c ON a.released_by = c.emp_id LEFT JOIN cso_header d ON a.so_no = d.cso_no WHERE a.status = '4' $myso;");
	
    while($row = $datares->fetch_array(MYSQLI_ASSOC)){

		$row['age'] = $con->calculateAge($row['so_date'],$row['birthdate']);
        $data[] = array_map('utf8_encode',$row);

	}
	$results = ["sEcho" => 1,"iTotalRecords" => count($data),"iTotalDisplayRecords" => count($data),"aaData" => $data];
	echo json_encode($results);

?>