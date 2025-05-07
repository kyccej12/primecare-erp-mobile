<?php
	session_start();
	include("../handlers/_generics.php");
	ini_set("max_execution_time",-1);
	ini_set("memory_limit",-1);
	$con = new _init;

	if($_SESSION['so_no'] != '') { $myso = " and a.so_no = '$_SESSION[so_no]' "; }

	$data = array();
	$datares = $con->dbquery("SELECT record_id AS id, LPAD(a.so_no,6,0) AS sono, DATE_FORMAT(a.so_date,'%m/%d/%Y') AS sodate, a.pid, CONCAT(b.lname,', ',b.fname,' ',b.mname) AS pname, '' AS age, IF(b.gender='M','Male','Female') AS gender,a.procedure,c.sample_type,serialno,DATE_FORMAT(CONCAT(extractdate,' ',extractime),'%m/%d/%Y %h:%i %p') AS tstamp,d.fullname AS createdby, DATE_FORMAT(a.created_on,'%m/%d/%Y %h:%i %p') AS createdon, a.code, a.so_date, b.birthdate FROM lab_samples a LEFT JOIN pccmain.patient_info b ON a.pid = b.patient_id LEFT JOIN pccmain.options_sampletype c ON a.sampletype = c.id LEFT JOIN pccmain.user_info d ON a.created_by = d.emp_id LEFT JOIN pccmain.services_master e ON a.code = e.code WHERE e.category = '1' AND a.status = '3' $myso;");
	
    while($row = $datares->fetch_array(MYSQLI_ASSOC)){
		$row['age'] = $con->calculateAge($a['so_date'],$a['birthdate']);
        $data[] = array_map('utf8_encode',$row);

	}
	$results = ["sEcho" => 1,"iTotalRecords" => count($data),"iTotalDisplayRecords" => count($data),"aaData" => $data];
	echo json_encode($results);

?>