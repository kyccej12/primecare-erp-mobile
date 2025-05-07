<?php
	session_start();
	ini_set("max_execution_time",-1);
	ini_set("memory_limit",-1);

	require_once '../lib/PHPExcel/PHPExcel.php';
	include("../handlers/_generics.php");
	$con = new _init;

	date_default_timezone_set('Asia/Manila');
	//ini_set("display_errors","On");
	//ini_set("max_execution_time",-1);
	
	
	$co = $con->getArray("select * from companies where company_id = '$_SESSION[company]';");	
	$cso = $con->getArray("SELECT customer_name, customer_address, `location`, company, DATE_FORMAT(`from`,'%d %b %Y') AS `from`, DATE_FORMAT(`from`,'%d %b %Y') AS `until`, `until` AS mobiledate FROM cso_header WHERE cso_no = '$_REQUEST[so_no]';");

	/* if($_GET['date'] != '' && $_GET['shift'] != '') {

		$date = $con->formatDate($_GET['date']);
		list($nextday) = $con->getArray("SELECT DATE_ADD('$date', INTERVAL 1 DAY);");

		if($_GET['shift'] == 1) {
			$searchString = " AND a.processed_on BETWEEN '$date 06:00:00' and '$date 14:00:00' ";
		} else {
			$searchString = " AND a.processed_on BETWEEN '$date 20:00:00' and '$nextday 05:59:00' ";
		}

		// if($_GET['shift'] == 1) {
		// 	$searchString = " AND a.processed_on BETWEEN '$date 14:00:00' and '$date 22:00:00' ";
		// } else {
		// 	$searchString = " AND a.processed_on BETWEEN '$date 22:00:00' and '$nextday 06:59:00' ";
		// }
	}

	if($_GET['date'] != '' && $_GET['shift'] == '') {
		$date = $con->formatDate($_GET['date']);
		$searchString = " AND DATE(processed_on) = '$date' ";
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


	if($_GET['package'] != '') {
		$searchString .= " and a.code = '$_GET[package]' ";
	}

	switch($_REQUEST['status']) {
		case "1": $stat = "Completed"; break;
		case "2": $stat = "For Completion"; break;
		case "3": $stat = "No Show"; break;
		default: $stat = "All"; break;

	}

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
	
	$objPHPExcel = new PHPExcel();
	$objPHPExcel->getDefaultStyle()->getFont()->setSize(9);
	$objPHPExcel->getProperties()->setCreator("Root Admin")
								 ->setLastModifiedBy("Root Admin")
								 ->setTitle("$co[company_name] - Mobile Result Summary")
								 ->setSubject("$co[company_name] - Mobile Result Summary")
								 ->setDescription("$co[company_name] - Mobile Result Summary")
								 ->setKeywords("office 2007 openxml php")
								 ->setCategory("Exported File");
	
	$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0,1,"MEDGRUPPE POLYCLINICS & DIAGNOSTIC CENTER INCORPORATED");
	$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0,2,"2nd Level, APM Central, A. Soriano Ave., NRA, Mabolo, Cebu City, 6000 Philippines");
	$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0,3,"Tel No. (32) 232-2273");
	$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0,4,"MOBILE CENSUS");
	$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0,6,"Billed To:");
	$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(1,6,"$cso[customer_name]");
	$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0,7,"Billing Address:");
	$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(1,7,"$cso[customer_address]");
	$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0,8,"Company:");
	$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(1,8,"$cso[company]");
	$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0,9,"Company Address: ");
	$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(1,9," $cso[location]");
	$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0,10,"Date Held:");
	$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(1,10,$_GET['date']);
	$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0,11,"Status:");
	$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(1,11,$stat);

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

	$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0,12,"NO.");
	$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(1,12,"DATEPROCESSED");
	$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(2,12,"TIME PROCESSED");
	$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(3,12,"SHIFT");
	$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(4,12,"EMPLOYEE ID");
	$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(5,12,"PATIENT NAME");
	$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(6,12,"GENDER");
	$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(7,12,"BIRTHDATE");
	$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(8,12,"AGE");
	$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(9,12,"AGE BRACKET");
	
	$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(0,12)->applyFromArray($headerStyle);
	$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(1,12)->applyFromArray($headerStyle);
	$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(2,12)->applyFromArray($headerStyle);
	$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(3,12)->applyFromArray($headerStyle);
	$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(4,12)->applyFromArray($headerStyle);
	$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(5,12)->applyFromArray($headerStyle);
	$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(6,12)->applyFromArray($headerStyle);
	$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(7,12)->applyFromArray($headerStyle);
	$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(8,12)->applyFromArray($headerStyle);
	$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(9,12)->applyFromArray($headerStyle);
	
	$headQuery = $con->dbquery("SELECT DISTINCT `code`,UPPER(`procedure`) AS `procedure` FROM lab_samples WHERE so_no = '$_REQUEST[so_no]' and `code` != 'O009' UNION ALL SELECT 'XXPEME' AS `code`, 'PHYSICAL EXAMINATION' AS `prcodure` ORDER BY `code`;");
	$col = 10;
	while($headerRow = $headQuery->fetch_array()) {
		$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow($col,12,$headerRow[1]);
		$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col,12)->applyFromArray($headerStyle);
		$col++;
	}

	$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow($col,12,"REMARKS");
	$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col,12)->applyFromArray($headerStyle);

	$objPHPExcel->getActiveSheet()->getColumnDimension("A")->setWidth(24);
	$objPHPExcel->getActiveSheet()->getColumnDimension("B")->setWidth(24);
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

	$row = 13; $no = 1;
	$dataQuery = $con->dbquery("SELECT DISTINCT pid, pname, b.gender, DATE_FORMAT(birthdate,'%d %b %Y') AS bday, birthdate, b.emp_idno FROM cso_details a LEFT JOIN pccmain.patient_info b ON a.pid = b.patient_id WHERE cso_no = '$_REQUEST[so_no]' and barcode = 'Y' $searchString ORDER BY pname;");

	while($data = $dataQuery->fetch_array()) {

			list($testCount) = $con->getArray("select count(*) from lab_samples where pid = '$data[pid]' and so_no = '$_REQUEST[so_no]' and `code` != 'L013';");
			list($collectCount) = $con->getArray("select count(*) from lab_samples where pid = '$data[pid]' and so_no = '$_REQUEST[so_no]' and extracted = 'Y' and `code` != 'L013';");

			switch($_REQUEST['status']) {

				case "1":
					if($collectCount == $testCount) {
						$ok = true;
					} else {
						$ok = false;
					}

				break;
				
				case "2":
					if($collectCount > 0 && $collectCount < $testCount) {
						$ok = true;
					} else {
						$ok = false;
					}
				break;

				case "3":
					if($collectCount == 0) {
						$ok = true;
					} else {
						$ok = false;
					}
				break;
				default:
					$ok = true;
				break;

			}


			if($ok) {

				$age = $con->calculateAge($cso['mobiledate'],$data['birthdate']);
				list($dateAvailed,$timeAvailed,$shift) = $con->getArray("select date_format(processed_on,'%d %b %Y'), date_format(processed_on,' %h:%i'),DATE_FORMAT(processed_on,'%p') from cso_details where cso_no = '$_REQUEST[so_no]' and pid = '$data[pid]' limit 1;");

				/* if($timeShift > 79200 || $timeShift < 28800) {
					$shift = '2';
				} else { $shift = '1'; } */

				if($data['gender'] == 'F') {
					if($age < 30) {
						$ageBracket = "FEMALE BELOW 30";
					} elseif ($age >= 30 && $age <= 34) {
						$ageBracket = "FEMALE 30-34";
					} else {
						$ageBracket = "FEMALE ABOVE 35";
					}

				} else {
					if($age >= 35) {
						$ageBracket = "MALE 30 & ABOVE";
					} else {
						$ageBracket = "MALE BELOW 30";
					}

				}


				$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0,$row,$no);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(1,$row,$dateAvailed);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(2,$row,$timeAvailed);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(3,$row,$shift);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(4,$row,$data['emp_idno']);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(5,$row,html_entity_decode($data['pname']));
				$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(6,$row,$data['gender']);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(7,$row,$data['bday']);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(8,$row,$age);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(9,$row,$ageBracket);

				$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(0,$row)->applyFromArray($contentStyle);
				$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(1,$row)->applyFromArray($contentStyle);
				$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(2,$row)->applyFromArray($contentStyle2);
				$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(3,$row)->applyFromArray($contentStyle2);
				$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(4,$row)->applyFromArray($contentStyle2);
				$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(5,$row)->applyFromArray($contentStyle2);
				$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(6,$row)->applyFromArray($contentStyle2);
				$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(7,$row)->applyFromArray($contentStyle2);
				$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(8,$row)->applyFromArray($contentStyle2);
				$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(9,$row)->applyFromArray($contentStyle2);

				$dataInnerQuery = $con->dbquery("SELECT DISTINCT `code`,UPPER(`procedure`) AS `procedure` FROM lab_samples WHERE so_no = '$_REQUEST[so_no]' and `code` != 'O009' UNION ALL SELECT 'XXPEME' AS `code`, 'PHYSICAL EXAMINATION' AS `prcodure`  ORDER BY `code`;");
				//$dataInnerQuery = $con->dbquery("SELECT DISTINCT `code`,UPPER(`procedure`) AS `procedure` FROM lab_samples WHERE so_no = '$_REQUEST[so_no]' AND `code` != 'O009' GROUP BY `code` UNION ALL SELECT 'XXPEME' AS `code`, 'PHYSICAL EXAMINATION' AS `procedure` UNION ALL SELECT 'REMARKS' AS `code`, 'REMARKS' AS `procedure`  ORDER BY `code`;");

				$col = 10; $remarks = "";
				while($dataInnerRow = $dataInnerQuery->fetch_array()) {

					if($dataInnerRow[0] == 'XXPEME') {
						list($ex) = $con->getArray("select pre_examined_by from peme where so_no = '$_REQUEST[so_no]' and pid = '$data[pid]';");
						if($ex == '') {
							$val = 'N/A';
						} else {
							if($ex > 0) { 
								$val = "✓"; 
							} else { 
								$val = 'NOT COMPLIED'; 
								
								if($ex == '') {
									$remarks .= "LACKING PHYSICAL EXAM,"; 
								} else {
									$remarks .= "PHYSICAL EXAM,";
								}
							}
						}
					} else {
						list($stat,$extracted,$procedure,$rem) = $con->getArray("select status, extracted, `procedure`, rejection_remarks from lab_samples where so_no = '$_REQUEST[so_no]' and pid = '$data[pid]' and `code` = '$dataInnerRow[code]';");
						if($stat == '') {
							$val = "N/A";
						} else {
							if($extracted == 'Y') {
								$val = "✓";
							} else {
								
								$val = "";

								if($dataInnerRow['code'] != 'L013') {
									$val = "NOT COMPLIED";

									if($stat == '2') {	$procedure .= " ($rem)"; }
									
									if($remarks == '') {
										$remarks .= "LACKING $procedure,"; 
									} else {
										$remarks .= "$procedure,";
									}

									// if($dataInnerRow['code'] != 'REMARKS') {
									// 	$remarks .= "LACKING $procedure,"; 
									// } else {
									// 	$remarks .= "$procedure,";
									// }
								}
							}
						}
					}

					$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow($col,$row,$val);
					$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col,$row)->applyFromArray($contentStyle2);
					$col++;
				}

				if($remarks == '') { $remarks = "COMPLETED (✓)"; }
				$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow($col,$row,trim($remarks,','));
				$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col,$row)->applyFromArray($contentStyle);

				$row++; $no++;
			}
	}

	// Rename worksheet
	$objPHPExcel->getActiveSheet()->setTitle("Mobile Census");
	
	// Set active sheet index to the first sheet, so Excel opens this as the first sheet
	$objPHPExcel->setActiveSheetIndex(0);
			
	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	header('Content-Disposition: attachment;filename="mobilecensus.xlsx"');
	header('Cache-Control: max-age=0');
	
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
	$objWriter->save('php://output');
	exit;
?>