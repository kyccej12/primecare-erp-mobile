<?php
	session_start();
	//ini_set("display_errors","On");
	require_once '../lib/PHPExcel/PHPExcel.php';
	include("../handlers/_generics.php");
	$con = new _init;

	date_default_timezone_set('Asia/Manila');
	set_time_limit(0);
	
	$co = $con->getArray("select * from companies where company_id = '$_SESSION[company]';");	
		
	$headerStyle = array(
		'font' => array('bold' => true,'color' => array('rgb' => 'FFFFFF')),
		'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER),
		'borders' => array('outline' => array('style' =>PHPExcel_Style_Border::BORDER_THIN)),
		'fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => '00b33c'))
	);
	
	$contentStyle = array(
		'borders' => array('outline' => array('style' => PHPExcel_Style_Border::BORDER_THIN))
	);
	
	$totalStyle = array(
		'font' => array('bold' => true),
		'borders' => array('outline' => array('style' => PHPExcel_Style_Border::BORDER_THIN))
	);

	$searchString = '';


	/* if($_GET['date'] != '' && $_GET['shift'] != '') {

		$date = $con->formatDate($_GET['date']);
		list($nextday) = $con->getArray("SELECT DATE_ADD('$date', INTERVAL 1 DAY);");

		if($_GET['shift'] == 1) {
			$range = " AND processed_on BETWEEN '$date 06:00:00' and '$date 14:00:00'";
		} else {
			$range = " AND processed_on BETWEEN '$date 20:00:00' and '$nextday 05:59:00'";
		}

		$searchString = " AND a.pid in (SELECT DISTINCT pid FROM cso_details WHERE barcode = 'Y' AND cso_no = '$_REQUEST[so_no]' $range) ";

	}

	if($_GET['date'] != '' && $_GET['shift'] == '') {
		$date = $con->formatDate($_GET['date']);
		$searchString = " AND a.pid in (SELECT DISTINCT pid from cso_details where cso_no = '$_REQUEST[so_no]' and barcode = 'Y' AND DATE(processed_on) = '$date') ";
	} */



	if($_GET['date'] != '') {
		$date = $con->formatDate($_GET['date']);
		list($nextday) = $con->getArray("SELECT DATE_ADD('$date', INTERVAL 1 DAY);");
		
		if($_GET['shift'] != '') {
			if($_GET['shift'] == 1) {
				$range = " AND processed_on BETWEEN '$date 06:00:00' and '$date 14:00:00' ";
			} else {
				$range = " AND processed_on BETWEEN '$date 14:01:00' and '$date 22:00:00' ";
			}
		} else {

			$shiftedCSO = array(78);
			if(in_array($_REQUEST['so_no'],$shiftedCSO)) {
				$range = " AND processed_on BETWEEN '$date 13:00:00' and '$nextday 10:00:00' ";
			} else {
				$range = " AND processed_on BETWEEN '$date 00:00:01' and '$date 23:59:00' ";
			}
		}

		$searchString = " AND a.pid in (SELECT DISTINCT pid FROM cso_details WHERE barcode = 'Y' AND cso_no = '$_REQUEST[so_no]' $range) ";

	}

	
	$sql = $con->dbquery("SELECT DISTINCT b.cso_no, DATE_FORMAT(b.cso_date,'%d %b %Y') AS date_availed, b.cso_date, a.pid, CONCAT(c.lname,', ',c.fname, ', ',c.mname) AS pname, IF(c.gender='M','MALE','FEMALE') AS gender, DATE_FORMAT(c.birthdate,'%d %b %Y') AS bday, c.birthdate, c.emp_idno, a.wt, a.ht, a.bmi, a.bmi_category, a.bp, a.pulse, a.rr, CONCAT(lefteye,' - ',righteye) AS visual, fm_history, IF(cbc_normal='Y','NORMAL',cbc_findings) AS cbc, IF(ua_normal='Y','NORMAL',ua_findings) AS ua, IF(se_normal='Y','NORMAL',se_findings) AS se, IF(chest_normal='Y','NORMAL',chest_findings) AS chest, IF(ecg_normal='Y','NORMAL',ecg_findings) AS ecg, IF(pap_normal='Y','NORMAL',pap_findings) AS pap, IF(dt_normal='N','NEGATIVE',dt_findings) AS dt, concat(others1_name,' ', IF(others1_normal='N','NORMAL', others1_findings),', ', others2_name,' ', IF(others2_normal='N','NORMAL', others2_findings)) AS others, classification, class_b, class_b_remarks1, class_b_remarks2, class_c_remarks1, class_c_remarks2,  pending_remarks, concat(class_b_remarks1,' ',class_b_remarks2, ' ',class_c_remarks1,' ',class_c_remarks2,' ',pending_remarks) as other_remarks, overall_remarks, CONCAT(d.fullname,' ,',d.prefix) AS examined_by, CONCAT(e.fullname,' ,',e.prefix) AS classified_by FROM peme a LEFT JOIN cso_header b ON a.so_no = b.cso_no LEFT JOIN pccmain.patient_info c ON a.pid = c.patient_id LEFT JOIN options_doctors d ON a.pre_examined_by = d.id LEFT JOIN options_doctors e ON a.examined_by = e.id LEFT JOIN cso_details f ON c.patient_id = f.pid WHERE so_no = '$_REQUEST[so_no]' and f.barcode = 'Y' $searchString ORDER BY c.lname, c.fname;");
	
	$objPHPExcel = new PHPExcel();
	$objPHPExcel->getDefaultStyle()->getFont()->setSize(9);
	$objPHPExcel->getProperties()->setCreator("Root Admin")
								 ->setLastModifiedBy("Root Admin")
								 ->setTitle("$co[company_name] - Mobile Result Summary")
								 ->setSubject("$co[company_name] - Mobile Result Summary")
								 ->setDescription("$co[company_name] - Mobile Result Summary")
								 ->setKeywords("office 2007 openxml php")
								 ->setCategory("Exported File");
	

	$objDrawing = new PHPExcel_Worksheet_Drawing();
	$objDrawing->setName('test_img');
	$objDrawing->setDescription('test_img');
	$objDrawing->setPath('../images/doc-header.jpg');
	$objDrawing->setCoordinates('I1');                      
	//setOffsetX works properly
	$objDrawing->setOffsetX(5); 
	$objDrawing->setOffsetY(5);                
	//set width, height
	$objDrawing->setWidth(72); 
	$objDrawing->setHeight(72); 
	$objDrawing->setWorksheet($objPHPExcel->getActiveSheet());

	
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue("A7","NO");
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue("B7","DATE & TIME PROCESSED");
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue("C7","PATIENT ID");
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue("D7","EMPLOYEE ID");
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue("E7","PATIENT NAME");
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue("F7","SEX");
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue("G7","BIRTHDAY");
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue("H7","AGE");
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue("I7","WEIGHT (KGS)");
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue("J7","HEIGHT (CM)");

	$objPHPExcel->setActiveSheetIndex(0)->setCellValue("K7","BMI");
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue("L7","CATEGORY");
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue("M7","SYSTOLIC");
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue("N7","DIASTOLIC");
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue("O7","BLOOD PRESSURE CLASSIFICATION");
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue("P7","PULSE RATE");
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue("Q7","RESPIRATORY RATE");
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue("R7","VISUAL ACUITY");
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue("S7","FAMILY HISTORY");
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue("T7","CBC");
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue("U7","UA");
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue("V7","SE");
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue("W7","XRAY");
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue("X7","ECG");
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue("Y7","PAPSMEAR");
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue("Z7","DRUG TEST");
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue("AA7","OTHER TESTS");
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue("AB7","FINAL CLASSIFICATION");
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue("AC7","DIAGNOSIS");
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue("AD7","OTHER PROCEDURES");
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue("AE7","OVERALL REMARKS");
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue("AF7","PRE-EXAMINED BY");
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue("AG7","CLASSIFIED BY");

	$objPHPExcel->getActiveSheet()->getColumnDimension("A")->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension("B")->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension("C")->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension("D")->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension("E")->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension("F")->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension("G")->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension("H")->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension("I")->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension("J")->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension("K")->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension("L")->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension("M")->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension("N")->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension("O")->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension("P")->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension("Q")->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension("R")->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension("S")->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension("T")->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension("U")->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension("V")->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension("W")->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension("X")->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension("Y")->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension("Z")->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension("AA")->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension("AB")->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension("AC")->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension("AD")->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension("AE")->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension("AF")->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension("AG")->setAutoSize(true);

	$objPHPExcel->getActiveSheet()->getStyle('A7')->applyFromArray($headerStyle);
	$objPHPExcel->getActiveSheet()->getStyle('B7')->applyFromArray($headerStyle);
	$objPHPExcel->getActiveSheet()->getStyle('C7')->applyFromArray($headerStyle);
	$objPHPExcel->getActiveSheet()->getStyle('D7')->applyFromArray($headerStyle);
	$objPHPExcel->getActiveSheet()->getStyle('E7')->applyFromArray($headerStyle);
	$objPHPExcel->getActiveSheet()->getStyle('F7')->applyFromArray($headerStyle);
	$objPHPExcel->getActiveSheet()->getStyle('G7')->applyFromArray($headerStyle);
	$objPHPExcel->getActiveSheet()->getStyle('H7')->applyFromArray($headerStyle);
	$objPHPExcel->getActiveSheet()->getStyle('I7')->applyFromArray($headerStyle);
	$objPHPExcel->getActiveSheet()->getStyle('J7')->applyFromArray($headerStyle);
	$objPHPExcel->getActiveSheet()->getStyle('K7')->applyFromArray($headerStyle);
	$objPHPExcel->getActiveSheet()->getStyle('L7')->applyFromArray($headerStyle);
	$objPHPExcel->getActiveSheet()->getStyle('M7')->applyFromArray($headerStyle);
	$objPHPExcel->getActiveSheet()->getStyle('N7')->applyFromArray($headerStyle);
	$objPHPExcel->getActiveSheet()->getStyle('O7')->applyFromArray($headerStyle);
	$objPHPExcel->getActiveSheet()->getStyle('P7')->applyFromArray($headerStyle);
	$objPHPExcel->getActiveSheet()->getStyle('Q7')->applyFromArray($headerStyle);
	$objPHPExcel->getActiveSheet()->getStyle('R7')->applyFromArray($headerStyle);
	$objPHPExcel->getActiveSheet()->getStyle('S7')->applyFromArray($headerStyle);
	$objPHPExcel->getActiveSheet()->getStyle('T7')->applyFromArray($headerStyle);
	$objPHPExcel->getActiveSheet()->getStyle('U7')->applyFromArray($headerStyle);
	$objPHPExcel->getActiveSheet()->getStyle('V7')->applyFromArray($headerStyle);
	$objPHPExcel->getActiveSheet()->getStyle('W7')->applyFromArray($headerStyle);
	$objPHPExcel->getActiveSheet()->getStyle('X7')->applyFromArray($headerStyle);
	$objPHPExcel->getActiveSheet()->getStyle('Y7')->applyFromArray($headerStyle);
	$objPHPExcel->getActiveSheet()->getStyle('Z7')->applyFromArray($headerStyle);
	$objPHPExcel->getActiveSheet()->getStyle('AA7')->applyFromArray($headerStyle);
	$objPHPExcel->getActiveSheet()->getStyle('AB7')->applyFromArray($headerStyle);
	$objPHPExcel->getActiveSheet()->getStyle('AC7')->applyFromArray($headerStyle);
	$objPHPExcel->getActiveSheet()->getStyle('AD7')->applyFromArray($headerStyle);
	$objPHPExcel->getActiveSheet()->getStyle('AE7')->applyFromArray($headerStyle);
	$objPHPExcel->getActiveSheet()->getStyle('AF7')->applyFromArray($headerStyle);
	$objPHPExcel->getActiveSheet()->getStyle('AG7')->applyFromArray($headerStyle);

	$row = 8; $no = 1;
	while($data = $sql->fetch_array()) {

		$bp = explode("/",$data['bp']);
		$age = $con->calculateAge($data['cso_date'],$data['birthdate']);

		/* if($_GET['date'] != '') {
			list($dateAvailed) = $con->getArray("SELECT date_format('$date','%d %b %Y');");
		} else {
			list($dateAvailed) = $con->getArray("select date_format(extractdate,'%d %b %Y') from lab_samples where so_no = '$data[cso_no]' and pid = '$data[pid]' and extracted = 'Y' limit 1;");
		} */

		list($dateAvailed) = $con->getArray("select date_format(processed_on,'%d %b %Y %h:%i %p') from cso_details where cso_no = '$data[cso_no]' and pid = '$data[pid]' limit 1;");

		$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0,$row,$no);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(1,$row,$dateAvailed);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(2,$row,$data['pid']);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(3,$row,$data['emp_idno']);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(4,$row,html_entity_decode($data['pname']));
		$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(5,$row,$data['gender']);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(6,$row,$data['bday']);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(7,$row,$age);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(8,$row,$data['wt']);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(9,$row,$data['ht']);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(10,$row,$data['bmi']);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(11,$row,$data['bmi_category']);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(12,$row,$bp[0]);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(13,$row,$bp[1]);
		
		if($data['bp'] >= 120) {
			$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(14,$row,"HYPERTENSION");
		}else if($data['bp'] == ''){
			$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(14,$row,"");
		}else {
			$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(14,$row,"NORMAL");
		} 
		
		$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(15,$row,$data['pulse']);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(16,$row,$data['rr']);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(17,$row,$data['visual']);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(18,$row,strtoupper($data['fm_history']));
		$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(19,$row,strtoupper($data['cbc']));
		$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(20,$row,strtoupper($data['ua']));
		$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(21,$row,strtoupper($data['se']));
		$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(22,$row,strtoupper($data['chest']));

		if($age <= 34) {
			$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(23,$row,"NOT APPLICABLE");
		}else {
		$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(23,$row,strtoupper($data['ecg']));
		}		
		
		$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(24,$row,strtoupper($data['pap']));
		$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(25,$row,strtoupper($data['dt']));
		$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(26,$row,strtoupper($data['others']));
		$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(27,$row,strtoupper($data['classification']));
		
		if($data['classification'] == 'B') {
			$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(28,$row,strtoupper($data['class_b_remarks1']));
		}else if($data['classification'] == 'C') {
			$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(28,$row,strtoupper($data['class_c_remarks1']));
		}else if($data['classification'] == 'PENDING') {
			$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(28,$row,strtoupper($data['pending_remarks']));
		}else if($data['class_b'] == 2) {
			$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(28,$row,strtoupper($data['class_b_remarks2']));
		}else {
			$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(28,$row,strtoupper($data['overall_remarks']));
		}
		
		$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(29,$row,$data['other_remarks']);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(30,$row,$data['overall_remarks']);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(31,$row,$data['examined_by']);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(32,$row,$data['classified_by']);
		
		for($z=0;$z<=32;$z++) {
			$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($z,$row)->applyFromArray($contentStyle);
		}

		$row++; $no++;
	}
	


	// Rename worksheet
	$objPHPExcel->getActiveSheet()->setTitle("Mobile Result Summary");
	
	// Set active sheet index to the first sheet, so Excel opens this as the first sheet
	$objPHPExcel->setActiveSheetIndex(0);
			
	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	header('Content-Disposition: attachment;filename="mobilesummary.xlsx"');
	header('Cache-Control: max-age=0');
	
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
	$objWriter->save('php://output');
	exit;
?>