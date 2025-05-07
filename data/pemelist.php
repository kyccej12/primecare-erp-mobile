<?php
	session_start();

	ini_set('max_execution_time',0);
	ini_set('memory_limit',-1);
	
	include("../handlers/_generics.php");
	$con = new _init;
	$searchString = '';

	if($_SESSION['so_no'] != '') { $searchString .= " and so_no = '$_SESSION[so_no]' "; }

	if($_REQUEST['displayType'] != "") {
		switch($_REQUEST['displayType']) {
			case "1":
				$searchString .= " and a.examined_by is NULL";
			break;
			case "2":
				$searchString .= " and a.examined_by > 0 and a.pre_examined_by > 0 ";
			break;
		}
	}


	$data = array();
	$datares = $con->dbquery("SELECT LPAD(so_no,6,0) AS so, DATE_FORMAT(so_date,'%m/%d/%Y') AS sodate, `code`, `procedure`, CONCAT(b.lname,', ',b.fname,', ',b.mname) AS pname, b.gender, DATE_FORMAT(b.birthdate,'%m/%d/%Y') AS bday, '' AS age, compname, a.status, a.so_date,prio AS `priority`,b.birthdate, a.pid,CONCAT(d.fullname,', ',d.prefix) AS pre_by, CONCAT(c.fullname,', ',c.prefix) AS ex_by FROM peme a LEFT JOIN pccmain.patient_info b ON a.pid = b.patient_id LEFT JOIN options_doctors c ON a.examined_by = c.id LEFT JOIN options_doctors d ON a.pre_examined_by = d.id  WHERE 1=1 and `status` != 'Cancelled' $searchString;");

    while($row = $datares->fetch_array(MYSQLI_ASSOC)){

		list($compname) = $con->getArray("select company from cso_header where cso_no = '$_SESSION[so_no]';");
		$row['compname'] = $compname;

        $row['age'] =  $con->calculateAge($row['so_date'],$row['birthdate']);
		$compname = html_entity_decode($compname);
		$row['pname'] = html_entity_decode($row['pname']);
        $data[] = array_map('utf8_encode',$row);

	}
	$results = ["sEcho" => 1,"iTotalRecords" => count($data),"iTotalDisplayRecords" => count($data),"aaData" => $data];
	echo json_encode($results);

?>