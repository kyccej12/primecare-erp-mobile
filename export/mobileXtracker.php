<?php
	session_start();

	//ini_set("display_errors","on");

	require_once '../lib/PHPExcel/PHPExcel.php';
	include("../handlers/_generics.php");
	$con = new _init;

	date_default_timezone_set('Asia/Manila');
	set_time_limit(0);
	
	$co = $con->getArray("select * from companies where company_id = '$_SESSION[company]';");	
	$cso = $con->getArray("SELECT customer_name, customer_address, `location`, company, DATE_FORMAT(`from`,'%d %b %Y') AS `from`, DATE_FORMAT(`from`,'%d %b %Y') AS `until`, `until` AS mobiledate FROM cso_header WHERE cso_no = '$_REQUEST[so_no]';");
		
	$headerStyle = array(
		'font' => array('bold' => true,'color' => array('rgb' => 'FFFFFF')),
		'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER),
		'borders' => array('outline' => array('style' =>PHPExcel_Style_Border::BORDER_THIN)),
		'fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => '00b33c'))
	);
	
	$contentStyle = array(
		'borders' => array('outline' => array('style' => PHPExcel_Style_Border::BORDER_THIN))
	);

	$contentStyle2 = array(
		'borders' => array('outline' => array('style' => PHPExcel_Style_Border::BORDER_THIN)),
		'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
	);
	
	$totalStyle = array(
		'font' => array('bold' => true),
		'borders' => array('outline' => array('style' => PHPExcel_Style_Border::BORDER_THIN))
	);

	$dtf = $con->formatDate($_GET['dtf']);
	$dt2 = $con->formatDate($_GET['dt2']);

	if($_GET['consultant'] != '') { $fs = " and e.consultant = '$_GET[consultant]' "; }
	if($_GET['type'] != '') { $fs .= " and e.result_type = '$_GET[type]' "; }
	
	if($_GET['shift'] != '') {

		$date = $con->formatDate($_GET['dtf']);
		list($nextday,$dateAvailed) = $con->getArray("SELECT DATE_ADD('$date', INTERVAL 1 DAY), DATE_FORMAT('$date','%b %d, %Y');");

		if($_GET['shift'] == 1) {
			$fs .= " AND b.barcode = 'Y' AND b.processed_on BETWEEN '$date 06:00:00' and '$date 14:00:00' ";
		} else {
			$fs .= " AND b.barcode = 'Y' AND b.processed_on BETWEEN '$date 20:00:00' and '$nextday 05:59:00' ";
		}


	} else {
		
		$fs .= "AND e.result_date BETWEEN '$dtf' AND '$dt2' ";

	}

	

	$sql = $con->dbquery("SELECT a.cso_no, DATE_FORMAT(a.cso_date,'%m/%d/%Y') AS date_availed, CONCAT(DATE_FORMAT(a.from,'%m/%d/%Y'),' - ',DATE_FORMAT(a.until,'%m/%d/%Y')) AS scheduled_date, DATE_FORMAT(a.cso_date, '%m/%d/%Y') AS cso_date, a.customer_name, b.pid, CONCAT(c.lname,', ',c.fname, ', ',c.mname) AS pname, IF(c.gender='M','MALE','FEMALE') AS gender, DATE_FORMAT(c.birthdate,'%d %b %Y') AS bday, c.birthdate, ROUND(DATEDIFF(a.cso_date,c.birthdate)/364.25) AS age, d.lotno, d.procedure, d.code, e.impression, e.result_stat, date_format(e.result_date,'%m/%d/%Y') as result_date, CONCAT(f.fullname,' ,',f.prefix) AS consultant, g.fullname AS encoder FROM cso_header a LEFT JOIN cso_details b ON a.trace_no = b.trace_no LEFT JOIN pccmain.patient_info c ON b.pid = c.patient_id LEFT JOIN lab_samples d ON c.patient_id = d.pid LEFT JOIN lab_descriptive e ON d.pid = e.pid LEFT JOIN options_doctors f ON e.consultant = f.id LEFT JOIN user_info g ON e.verified_by = g.emp_id WHERE d.procedure LIKE '%chest%' AND d.extracted != 'N' AND b.cso_no= '$_REQUEST[so_no]' $fs ORDER BY b.pname;");
	$title = $con->getArray("SELECT cso_no, CONCAT(DATE_FORMAT(`from`,'%b %d, %Y'),' - ',DATE_FORMAT(`until`,'%b %d, %Y')) AS scheduled_date FROM cso_header WHERE cso_no = '$_REQUEST[so_no]';");

	$objPHPExcel = new PHPExcel();
	$objPHPExcel->getDefaultStyle()->getFont()->setSize(9);
	$objPHPExcel->getProperties()->setCreator("Root Admin")
								 ->setLastModifiedBy("Root Admin")
								 ->setTitle("$co[company_name] - Mobile XBook Summary")
								 ->setSubject("$co[company_name] - Mobile XBook Summary")
								 ->setDescription("$co[company_name] - Mobile XBook Summary")
								 ->setKeywords("office 2007 openxml php")
								 ->setCategory("Exported File");
	

	$objDrawing = new PHPExcel_Worksheet_Drawing();
	$objDrawing->setName('test_img');
	$objDrawing->setDescription('test_img');
	$objDrawing->setPath('../images/doc-header.jpg');
	$objDrawing->setCoordinates('D1');                      
	//setOffsetX works properly
	$objDrawing->setOffsetX(5); 
	$objDrawing->setOffsetY(5);                
	//set width, height
	$objDrawing->setWidth(72); 
	$objDrawing->setHeight(72); 
	$objDrawing->setWorksheet($objPHPExcel->getActiveSheet());

    $objPHPExcel->getActiveSheet()->getColumnDimension("A")->setWidth(25);
	$objPHPExcel->getActiveSheet()->getColumnDimension("B")->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension("C")->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension("D")->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension("E")->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension("F")->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension("G")->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension("H")->setWidth(16);
	$objPHPExcel->getActiveSheet()->getColumnDimension("I")->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension("J")->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension("K")->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension("L")->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension("M")->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension("N")->setAutoSize(true);

	$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0,6,"Billed To:");
	$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(1,6,"$cso[customer_name]");
	$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0,7,"Billing Address:");
	$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(1,7,"$cso[customer_address]");
	$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0,8,"Company:");
	$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(1,8,"$cso[company]");
	$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0,9,"Company Address: ");
	$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(1,9," $cso[location]");
	$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0,10,"Date Held:");
	$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(1,10,"$cso[from] - $cso[until]");

	if($_GET['shift'] != '') {
		if($_GET['shift'] == 1) { $shift = "Day Shift"; } else { $shift = "Night Shift"; }
		$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0,11,"Shift:");
		$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(1,11,$shift);
		$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(0,11)->getFont()->setBold(true);
	}


	$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(0,1)->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(0,2)->getFont()->setItalic(true);
	$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(0,3)->getFont()->setItalic(true);
	$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(0,4)->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(0,6)->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(0,7)->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(0,8)->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(0,9)->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(0,10)->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(0,11)->getFont()->setBold(true);

	$objPHPExcel->setActiveSheetIndex(0)->setCellValue("A12","NO.");
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue("B12","XRAY CASE NO.");
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue("C12","DATE AVAILED");
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue("D12","RESULT DATE");
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue("E12","PATIENT ID");
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue("F12","PATIENT NAME");
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue("G12","GENDER");
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue("H12","BIRTHDAY");
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue("I12","AGE");
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue("J12","PROCEDURE TYPE");
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue("K12","REMARKS");
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue("L12","CODE");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("M12","COMPANY NAME");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("N12","CONSULTANT");

	$objPHPExcel->getActiveSheet()->getStyle('A12')->applyFromArray($headerStyle);
	$objPHPExcel->getActiveSheet()->getStyle('B12')->applyFromArray($headerStyle);
	$objPHPExcel->getActiveSheet()->getStyle('C12')->applyFromArray($headerStyle);
	$objPHPExcel->getActiveSheet()->getStyle('D12')->applyFromArray($headerStyle);
	$objPHPExcel->getActiveSheet()->getStyle('E12')->applyFromArray($headerStyle);
	$objPHPExcel->getActiveSheet()->getStyle('F12')->applyFromArray($headerStyle);
	$objPHPExcel->getActiveSheet()->getStyle('G12')->applyFromArray($headerStyle);
	$objPHPExcel->getActiveSheet()->getStyle('H12')->applyFromArray($headerStyle);
	$objPHPExcel->getActiveSheet()->getStyle('I12')->applyFromArray($headerStyle);
	$objPHPExcel->getActiveSheet()->getStyle('J12')->applyFromArray($headerStyle);
	$objPHPExcel->getActiveSheet()->getStyle('K12')->applyFromArray($headerStyle);
	$objPHPExcel->getActiveSheet()->getStyle('L12')->applyFromArray($headerStyle);
	$objPHPExcel->getActiveSheet()->getStyle('M12')->applyFromArray($headerStyle);
	$objPHPExcel->getActiveSheet()->getStyle('N12')->applyFromArray($headerStyle);

	$row = 13; $no = 1;
	while($data = $sql->fetch_array()) {

		if($_GET['shift'] != '') {
			$data['date_availed'] = $dateAvailed;
		}


		$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0,$row,$no);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(1,$row,$data['lotno']);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(2,$row,$data['date_availed']);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(3,$row,$data['result_date']);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(4,$row,$data['pid']);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(5,$row,html_entity_decode($data['pname']));
		$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(6,$row,$data['gender']);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(7,$row,$data['bday']);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(8,$row,$data['age']);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(9,$row,$data['procedure']);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(10,$row,strip_tags(htmlspecialchars_decode($data['impression'])));
		$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(11,$row,$data['code']);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(12,$row,html_entity_decode($data['customer_name']));
		$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(13,$row,html_entity_decode($data['consultant']));
		
		for($z=0;$z<=13;$z++) {
			$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($z,$row)->applyFromArray($contentStyle);
		}

		$row++; $no++;

	}


	// Rename worksheet
	$objPHPExcel->getActiveSheet()->setTitle("Mobile X-Ray Tracker");
	
	// Set active sheet index to the first sheet, so Excel opens this as the first sheet
	$objPHPExcel->setActiveSheetIndex(0);
			
	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	header('Content-Disposition: attachment;filename="mobile-xray-tracker.xlsx"');
	header('Cache-Control: max-age=0');
	
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
	$objWriter->save('php://output');
	exit;
?>