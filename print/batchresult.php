<?php
	session_start();
	ini_set("max_execution_time",-1);
	ini_set("memory_limit",-1);
	include("../lib/mpdf6/mpdf.php");
	include("../handlers/_generics.php");

	$con = new _init;

	function checkLimits($code,$age,$gender) {

		global $con;

		switch($code) {
			/* case "L019":
				$limits = "120-200 mg/dL";
			break;
			case "L009":
				$limits = "< 200 mg/dL";
			break; */
			default:
				$limits = $con->getAttribute($code,$age,$gender);
			break;

		}

		return $limits;
	}


/* MYSQL QUERIES SECTION */
	$now = date("m/d/Y h:i a");
	$co = $con->getArray("select * from companies where company_id = '$_SESSION[company]';");


	$mpdf=new mPDF('win-1252','LETTER','','',8,8,50,5,5,10);
	$mpdf->use_embeddedfonts_1252 = true;    // false is default
	$mpdf->SetProtection(array('print'));
	$mpdf->SetAuthor("PORT80 Solutions");
	$searchString = '';

	$dtf = $con->formatDate($_GET['dtf']);
	$dt2 = $con->formatDate($_GET['dt2']);

	list($cname,$soDate,$resultDate) = $con->getArray("select company,cso_date, date_format(until,'%d %b %Y') from cso_header where cso_no = '$_REQUEST[so_no]';");

	//$outerQuery = $con->dbquery("SELECT a.pid, concat(b.lname,', ',b.fname,' ',b.mname) as pname, b.birthdate, b.gender, b.street, b.brgy, b.city, b.province FROM peme a LEFT JOIN patient_info b ON a.pid = b.patient_id WHERE a.so_no = '$_REQUEST[so_no]' and a.examined_by > 0 ORDER BY b.lname, b.fname, b.mname");
	$outerQuery = $con->dbquery("SELECT a.pid, CONCAT(b.lname,', ',b.fname,' ',b.mname) AS pname, a.trace_no, b.birthdate, date_format(b.birthdate,'%d %b %Y') as bday, if(b.gender='M','Male','Female') as gender, b.street, b.brgy, b.city, b.province, b.gender as xgender, a.barcode, DATE_FORMAT(c.extractdate,'%m/%d/%Y') AS extractdate FROM cso_details a LEFT JOIN pccmain.patient_info b ON a.pid = b.patient_id LEFT JOIN lab_samples c on a.pid = c.pid WHERE a.cso_no = '$_REQUEST[so_no]' and a.barcode != 'N' and DATE(c.processed_on) between '$dtf' and '$dt2' GROUP BY a.pid ORDER BY b.lname desc, b.fname, b.mname;");
	while($outerRow = $outerQuery->fetch_array()) {

		list($extractDate) = $con->getArray("select date_format(processed_on,'%m/%d/%Y') from lab_samples where so_no = '$_REQUEST[so_no]' and pid = '$outerRow[pid]';");

		$comp = $_REQUEST['so_no'];
		$employer = '';
		if($comp == '185') {
			$employer = "SEIHA";
		}else {
			list($employer) = $con->getArray("select employer from pccmain.patient_info where patient_id = '$outerRow[pid]';");
		}

		$age = $con->calculateAge($soDate,$outerRow['birthdate']);
		
		$CBCmedtechFullname = '';
		$CBCmedtechSignature = '';
		$CBCmedtechLicense = '';
		$UAmedtechFullname = '';
		$UAmedtechSignature = '';
		$UAmedtechLicense = '';

		$myaddress = '';
		list($brgy) = $con->getArray("SELECT brgyDesc FROM options_brgy WHERE brgyCode = '$outerRow[brgy]';");
		list($ct) = $con->getArray("SELECT citymunDesc FROM options_cities WHERE cityMunCode = '$outerRow[city]';");
		list($prov) = $con->getArray("SELECT provDesc FROM options_provinces WHERE provCode = '$outerRow[province]';");
	
		if($outerRow['street'] != '') { $myaddress.=$outerRow['street'].", "; }
		if($brgy != "") { $myaddress .= $brgy.", "; }
		if($ct != "") { $myaddress .= $ct.", "; }
		if($prov != "")  { $myaddress .= $prov.", "; }
		$myaddress = substr($myaddress,0,-2);

		/* CBC Result */
		list($cbcSN) = $con->getArray("select serialno from lab_samples where pid = '$outerRow[pid]' and `code` = 'L010' and so_no = '$_REQUEST[so_no]' and extracted = 'Y' and `status` = '4';");
		$cbcResult = $con->getArray("SELECT *,date_format(result_date,'%m/%d/%Y') as rdate FROM lab_cbcresult WHERE serialno = '$cbcSN';");
		if($cbcResult['verified_by'] != '') {
			list($CBCmedtechSignature,$CBCmedtechFullname,$CBCmedtechLicense,$CBCmedtechRole) = $con->getArray("SELECT if(signature_file != '',concat('<img src=\"../images/signatures/',signature_file,'\" align=absmiddle width=105 height=35 />'),'<img src=\"../images/signatures/blank.png\" align=absmiddle width=105 height=35 />') as signature, fullname, license_no, role from user_info where emp_id = '$cbcResult[verified_by]';");
		}

		/* UA Result */
		$uaResult = $con->getArray("select * from lab_uaresult where so_no = '$_REQUEST[so_no]' and pid = '$outerRow[pid]';");
		if($uaResult['verified_by'] != '') {
			list($UAmedtechSignature,$UAmedtechFullname,$UAmedtechLicense,$UAmedtechRole) = $con->getArray("SELECT if(signature_file != '',concat('<img src=\"../images/signatures/',signature_file,'\" align=absmiddle width=105 height=35 />'),'<img src=\"../images/signatures/blank.png\" align=absmiddle width=105 height=35 />') as signature, fullname, license_no, role from user_info where emp_id = '$uaResult[verified_by]';");
		}

		$casts = '';
		if($b['hyaline'] != '') { 
			$casts .= "HYALINE: " . $b['hyaline'] . "<br/>";
		}

		if($uaResult['coarse_granular'] != '') {
			$casts .= "COARSE GRANULAR: " . $uaResult['coarse_granular'] . "<br/>";
		}

		if($uaResult['casts_wbc'] != '') {
			$casts .= "WBC CASTS: " . $uaResult['casts_wbc'] . "<br/>";
		}

		if($uaResult['casts_rbc'] != '') {
			$casts .= "RBC CASTS: " . $uaResult['casts_rbc'] . "<br/>";
		}

		$crystals = '';

		if($uaResult['calcium_oxalate'] != '') {
			$crystals .= "CALCIUM OXALATE: " . $uaResult['calcium_oxalate'] . "<br/>";
		}
		
		if($uaResult['triple_phosphates'] != '') {
			$crystals .= "TRIPLE PHOSPHATES: " . $uaResult['triple_phosphates'] . "<br/>";
		}

		if($uaResult['uric_acid'] != '') {
			$crystals .= "URIC ACID: " . $uaResult['uric_acid'] . "<br/>";
		}

		/* Stool Exam */
		$stoolResult = $con->getArray("select * from lab_stoolexam where so_no = '$_REQUEST[so_no]' and pid = '$outerRow[pid]';");
		if($stoolResult['verified_by'] != '') {
			list($STOOLmedtechSignature,$STOOLmedtechFullname,$STOOLmedtechLicense,$STOOLmedtechRole) = $con->getArray("SELECT if(signature_file != '',concat('<img src=\"../images/signatures/',signature_file,'\" align=absmiddle width=105 height=35 />'),'<img src=\"../images/signatures/blank.png\" align=absmiddle width=105 height=35 />') as signature, fullname, license_no, role from user_info where emp_id = '$stoolResult[verified_by]';");
		}

		/* Xray Result */
		$xrayResult = $con->getArray("select impression, consultant, b.signature_file, b.fullname, b.prefix, b.specialization, b.license_no, c.fullname as encname, if(c.signature_file != '',concat('<img src=\"../images/signatures/',c.signature_file,'\" align=absmiddle width=105 height=35 />'),'<img src=\"../images/signatures/blank.png\" align=absmiddle width=105 height=35 />') as encsignature from lab_descriptive a left join options_doctors b on a.consultant = b.id left join user_info c on a.created_by = c.emp_id where so_no = '$_REQUEST[so_no]' and pid = '$outerRow[pid]' limit 1;");
		if($xrayResult['signature_file'] != '') {
			$consultantSignature = "<img src='../images/signatures/$xrayResult[signature_file]' align=absmiddle />";
		} else {
			$consultantSignature = "<img src='../images/signatures/blank.png' align=absmiddle />";	
		}
		
		/* otherTests */
		$otherResult = $con->getArray("SELECT if(signature_file != '',concat('<img src=\"../images/signatures/',signature_file,'\" align=absmiddle width=105 height=35 />'),'<img src=\"../images/signatures/blank.png\" align=absmiddle width=105 height=35 />') as signature, fullname, license_no, role from user_info a left join lab_singleresult b on b.verified_by = a.emp_id where so_no = '$_REQUEST[so_no]' and pid = '$outerRow[pid]' limit 1;");


	/* END OF SQL QUERIES */

	//if(count($cbcResult) > 0 && count($uaResult) > 0) {
		$html = '
			<html>
				<head>
					<style>
						body {font-family: arial; font-size: 10pt; }
						.itemHeader {
							padding: 5px; font-weight: bold; border-bottom: 1px solid black;
						}

						.itemRows {
							padding: 5px;
						}
					</style>
				</head>
				<body>

				<!--mpdf
				<htmlpageheader name="myheader">
					<table width="100%" cellpadding=0 cellspaing=0>
						<tr><td align=center><img src="../images/prime-care-medgruppe.png" /></td></tr>
					</table>
					<table width=100% cellpadding=2 cellspacing=0 style="font-size:8pt;margin-top:5px;">
						<tr>
							<td width="13%"><b>PATIENT NAME</b></td>
							<td width="40%">:&nbsp;&nbsp;'.$outerRow['pname'].'</td>
							<td width="6%"><b>AGE</b></td>
							<td width="14%">:&nbsp;&nbsp;'. $con->calculateAge($soDate,$outerRow['birthdate']) .'yo</td>
							<td width="12%"><b>No.</b></td>
							<td width="15%">:&nbsp;&nbsp;MOB-'.$_REQUEST['so_no'].'-'.$outerRow['pid'].'</td>
						</tr>
						<tr>
							<td><b>ADDRESS</b></td>
							<td>:&nbsp;&nbsp;' . $myaddress . '</td>
							<td><b>DOB</b></td>
							<td>:&nbsp;&nbsp;'. $outerRow['bday'] .'</td>
							<td><b>GENDER</b></td>
							<td>:&nbsp;&nbsp;'. $outerRow['gender'] .'</td>
						</tr>
						<tr>
							<td><b>COMPANY</b></td>
							<td>:&nbsp;&nbsp;' . $cname . '</td>
							<td></td>
							<td></td>
							<td><b>RESULT DATE</b></td>
							<td>:&nbsp;&nbsp;' . $extractDate . '</td>
						</tr>
						<tr>
							<td colspan=6 style="border-top: 1px solid black;">&nbsp;</td>
						</tr>
					</table>
				</htmlpageheader>

				<htmlpagefooter name="myfooter">
					<table width=100%>
						<tr>
							<td align=left><barcode size=0.8 code="'.substr($outerRow['trace_no'],0,10).'" type="C128A"></td>
							<td align=right valign=bottom width=100%>Date & Time Printed: '.date('m/d/Y h:i:s a').'</td>
						</tr>
					</table>
				</htmlpagefooter>

				<sethtmlpageheader name="myheader" value="on" show-this-page="1" />
				<sethtmlpagefooter name="myfooter" value="on" />
				mpdf-->

				<table width=100% style="border-collapse: collapse;">
					<tr>
						<td width=50% valign=top style="border-right: 1px solid black;">
							<table width=100% cellpadding=0 align=center style="font-size: 9pt;"> 
								<tr>
									<td colspan=3 align=center style="font-weight: bold;padding: 5px; background-color: #A9D08E;">COMPLETE BLOOD COUNT</td>
								</tr>
								<tr>
									<td align="left" width=30%></td>
									<td align=center width=30%></td>
									<td align="left" width=40% style="padding-left: 15px;"><b>NORMAL VALUES</b></td>	
								</tr>
								<tr>
									<td align="left" style="padding-left: 15px;">WBC '.$con->checkCBCValues($age,$outerRow['xgender'],"WBC",$cbcResult['wbc'],$cbcResult['machine']).'</td>
									<td align=center style="border-bottom: 1px solid black;vertical-align: top;">'. number_format($cbcResult['wbc']) . '</td>
									<td align="left" style="padding-left: 15px;">'.$con->getCBCAttribute2($age,$outerRow['xgender'],"WBC",$cbcResult['machine']).'</td>	
								</tr>
							
								<tr>
									<td align="left" style="padding-left: 15px;" valign=top>RBC '.$con->checkCBCValues($age,$outerRow['xgender'],"RBC",$cbcResult['rbc'],$cbcResult['machine']).'</td>
									<td align=center style="border-bottom: 1px solid black;" valign=top>'. $cbcResult['rbc'] . '</td>
									<td align="left" style="padding-left: 15px;" valign=top>'.$con->getCBCAttribute2($age,$outerRow['xgender'],"RBC",$cbcResult['machine']).'</td>	
								</tr>
							
								<tr>
									<td align="left" style="padding-left: 15px;">Hemoglobin '.$con->checkCBCValues($age,$outerRow['xgender'],"HEMOGLOBIN",$cbcResult['hemoglobin'],$cbcResult['machine']).'</td>
									<td align=center style="border-bottom: 1px solid black;">'. $cbcResult['hemoglobin'] . '</td>
									<td align="left" style="padding-left: 15px;">'. $con->getCBCAttribute2($age,$outerRow['xgender'],"HEMOGLOBIN",$cbcResult['machine']) . '</td>	
								</tr>
								<tr>
									<td align="left" style="padding-left: 15px;">Hematocrit '.$con->checkCBCValues($age,$outerRow['xgender'],"HEMATOCRIT",$cbcResult['hematocrit'],$cbcResult['machine']).'</td>
									<td align=center style="border-bottom: 1px solid black;">'. $cbcResult['hematocrit'] . '</td>
									<td align="left" style="padding-left: 15px;">'. $con->getCBCAttribute2($age,$outerRow['xgender'],"HEMATOCRIT",$cbcResult['machine']).'</td>	
								</tr>
								<tr>
									<td align="left" style="padding-left: 15px;">MCV '.$con->checkCBCValues($age,$outerRow['xgender'],"MCV",$cbcResult['mcv'],$cbcResult['machine']).'</td>
									<td align=center style="border-bottom: 1px solid black;">'. $cbcResult['mcv'] . '</td>
									<td align="left" style="padding-left: 15px;">'. $con->getCBCAttribute2($age,$outerRow['xgender'],"MCV",$cbcResult['machine']).'</td>	
								</tr>
								<tr>
									<td align="left" style="padding-left: 15px;">MCH '.$con->checkCBCValues($age,$outerRow['xgender'],"MCH",$cbcResult['mch'],$cbcResult['machine']).'</td>
									<td align=center style="border-bottom: 1px solid black;">'. $cbcResult['mch'] . '</td>
									<td align="left" style="padding-left: 15px;">'. $con->getCBCAttribute2($age,$outerRow['xgender'],"MCH",$cbcResult['machine']).'</td>	
								</tr>
								<tr>
									<td align="left" style="padding-left: 15px;">MCHC '.$con->checkCBCValues($age,$outerRow['xgender'],"MCHC",$cbcResult['mchc'],$cbcResult['machine']).'</td>
									<td align=center style="border-bottom: 1px solid black;">'. $cbcResult['mchc'] . '</td>
									<td align="left" style="padding-left: 15px;">'. $con->getCBCAttribute2($age,$outerRow['xgender'],"MCHC",$cbcResult['machine']).'</td>	
								</tr>
								<tr><td height=5>&nbsp;</td></tr>
								<tr>
									<td align="left" colspan=3  style="padding-left: 15px;"><b>Differential Count&nbsp;:</b></td>
								</tr>
								<tr>
									<td align="left" style="padding-left: 35px;">Neutrophils '.$con->checkCBCValues($age,$outerRow['xgender'],"NEUTROPHILS",$cbcResult['neutrophils'],$cbcResult['machine']).'</td>
									<td align=center style="border-bottom: 1px solid black;">'. $cbcResult['neutrophils'] . '</td>
									<td align="left" style="padding-left: 15px;">'.$con->getCBCAttribute2($age,$outerRow['xgender'],"NEUTROPHILS",$cbcResult['machine']).'</td>	
								</tr>
								<tr>
									<td align="left" style="padding-left: 35px;">Lymphocytes '.$con->checkCBCValues($age,$outerRow['xgender'],"LYMPHOCYTES",$cbcResult['lymphocytes'],$cbcResult['machine']).'</td>
									<td align=center style="border-bottom: 1px solid black;">'. $cbcResult['lymphocytes'] . '</td>
									<td align="left" style="padding-left: 15px;">'.$con->getCBCAttribute2($age,$outerRow['xgender'],"LYMPHOCYTES",$cbcResult['machine']).'</td>	
								</tr>
								<tr>
									<td align="left" style="padding-left: 35px;">Monocytes '.$con->checkCBCValues($age,$outerRow['xgender'],"MONOCYTES",$cbcResult['monocytes'],$cbcResult['machine']).'</td>
									<td align=center style="border-bottom: 1px solid black;">'. $cbcResult['monocytes'] . '</td>
									<td align="left" style="padding-left: 15px;">'.$con->getCBCAttribute2($age,$outerRow['xgender'],"MONOCYTES",$cbcResult['machine']).'</td>	
								</tr>
								<tr>
									<td align="left" style="padding-left: 35px;">Eosinophils '.$con->checkCBCValues($age,$outerRow['xgender'],"EOSINOPHILS",$cbcResult['eosinophils'],$cbcResult['machine']).'</td>
									<td align=center style="border-bottom: 1px solid black;">'. $cbcResult['eosinophils'] . '</td>
									<td align="left" style="padding-left: 15px;">'.$con->getCBCAttribute2($age,$outerRow['xgender'],"EOSINOPHILS",$cbcResult['machine']).'</td>	
								</tr>
								<tr>
									<td align="left" style="padding-left: 35px;">Basophils '.$con->checkCBCValues($age,$outerRow['xgender'],"BASOPHILS",$cbcResult['basophils'],$cbcResult['machine']).'</td>
									<td align=center style="border-bottom: 1px solid black;">'. $cbcResult['basophils'] . '</td>
									<td align="left" style="padding-left: 15px;">'.$con->getCBCAttribute2($age,$outerRow['xgender'],"BASOPHILS",$cbcResult['machine']).'</td>	
								</tr>
								<tr>
									<td align="left" style="padding-left: 15px;">Platelet Count '.$con->checkCBCValues($age,$outerRow['xgender'],"PLATELATE",$cbcResult['platelate'],$cbcResult['machine']).'</td>
									<td align=center style="border-bottom: 1px solid black;">'. number_format($cbcResult['platelate']) . '</td>
									<td align="left" style="padding-left: 15px;">'.$con->getCBCAttribute2($age,$outerRow['xgender'],"PLATELATE",$cbcResult['machine']).'</td>	
								</tr>
								<tr>
									<td align="left" style="padding-left: 15px;" valign=top>Remarks</td>
									<td align=left colspan=2 style="border-bottom: 1px solid black;">'. $b['remarks'] . '</td>
								</tr>
							</table>';
	
						$html .= '</td>

						<td width=50% valign=top>

						<table width=100% cellpadding=0 align=center style="font-size: 9pt;"> 
						<tr>
							<td colspan=4 align=center style="font-weight: bold;padding: 5px; background-color: #A9D08E;">URINALYSIS</td>
						</tr>
						<tr>
							<td align="left" colspan=2  style="padding-left: 5px;"><b>MACROSCOPIC&nbsp;:</b></td>
							<td align="left" colspan=2  style="padding-left: 5px;"><b>MICROSCOPIC&nbsp;:</b></td>
						</tr>
						<tr>
							<td align="left" width=25%></td>
							<td align=center width=40%></td>
							<td align="left" width=25%></td>	
							<td align=center width=40%></td>

						</tr>
						<tr>
							<td align="left" style="padding-left: 5px;">Color</td>
							<td align=center style="border-bottom: 1px solid black;vertical-align: top;">'. $uaResult['color'] . '</td>
							<td align="left" style="padding-left: 5px;">RBC / hpf&nbsp;:</td>
							<td align=center style="border-bottom: 1px solid black;">'. $uaResult['rbc_hpf'] . '</td>	
						</tr>
						<tr>
							<td align="left" style="padding-left: 5px;">Appearance</td>
							<td align=center style="border-bottom: 1px solid black;vertical-align: top;">'. $uaResult['appearance'] . '</td>
							<td align="left" style="padding-left: 5px;">WBC / hpf&nbsp;:</td>
							<td align=center style="border-bottom: 1px solid black;">'. $uaResult['wbc_hpf'] . '</td>	
						</tr>';
						
						if($uaResult['leukocytes'] != '' && $uaResult['nitrite'] != '' && $uaResult['urobilinogen'] != '') {

		$html .=		'<tr>
							<td align="left" style="padding-left: 5px;">Leukocytes</td>
							<td align=center style="border-bottom: 1px solid black;vertical-align: top;">'. $uaResult['leukocytes'] . '</td>
							<td align="left"></td>	
							<td align="left"></td>	
						</tr>
							<tr>
							<td align="left" style="padding-left: 5px;">Nitrite</td>
							<td align=center style="border-bottom: 1px solid black;vertical-align: top;">'. $uaResult['nitrite'] . '</td>
							<td align="left"></td>	
							<td align="left"></td>	
						</tr>
						<tr>
							<td align="left" style="padding-left: 5px;">Urobilinogen</td>
							<td align=center style="border-bottom: 1px solid black;vertical-align: top;">'. $uaResult['urobilinogen'] . '</td>
							<td align="left"></td>	
							<td align="left"></td>	
						</tr>';
						}
						
		$html .=		'<tr>
							<td align="left" style="padding-left: 5px;">Protein</td>
							<td align=center style="border-bottom: 1px solid black;">'. $uaResult['protein'] . '</td>
							<td align="left" style="padding-left: 5px;">Epith. Cells&nbsp;:</td>
							<td align=center style="border-bottom: 1px solid black;">'. $uaResult['squamous'] . '</td>	
						</tr>
						<tr>
							<td align="left" style="padding-left: 5px;">pH</td>
							<td align=center style="border-bottom: 1px solid black;">'. $uaResult['ph'] . '</td>
							<td align="left" style="padding-left: 5px;">Casts&nbsp;:</td>
							<td align=center style="border-bottom: 1px solid black;">'. $uaResult['casts'] . '</td>	
						</tr>';

						if($uaResult['leukocytes'] != '' && $uaResult['nitrite'] != '' && $uaResult['urobilinogen'] != '') {
		$html .=		'<tr>
							<td align="left" style="padding-left: 5px;">Blood</td>
							<td align=center style="border-bottom: 1px solid black;">'. $uaResult['blood'] . '</td>
							<td align="left"></td>	
							<td align="center"></td>	
						</tr>';

						}

		$html .=		'<tr>
							<td align="left" style="padding-left: 5px;">Specific Gravity</td>
							<td align=center style="border-bottom: 1px solid black;">'. $uaResult['gravity'] . '</td>
							<td align="left" style="padding-left: 5px;">Mucus Threads&nbsp;:</td>
							<td align=center style="border-bottom: 1px solid black;">'. $uaResult['mucus_thread'] . '</td>	
						</tr>';

						if($uaResult['leukocytes'] != '' && $uaResult['nitrite'] != '' && $uaResult['urobilinogen'] != '') {
		$html .=		'<tr>
							<td align="left" style="padding-left: 5px;">Ketone</td>
							<td align=center style="border-bottom: 1px solid black;">'. $uaResult['ketone'] . '</td>
							<td align="left"></td>	
							<td align="center"></td>	
						</tr>
						<tr>
							<td align="left" style="padding-left: 5px;">Bilirubin</td>
							<td align=center style="border-bottom: 1px solid black;">'. $uaResult['bilirubin'] . '</td>
							<td align="left"></td>	
							<td align="center"></td>	
						</tr>';
						}

		$html .=		'<tr>
							<td align="left" style="padding-left: 5px;">Glucose</td>
							<td align=center style="border-bottom: 1px solid black;">'. $uaResult['glucose'] . '</td>
							<td align="left" style="padding-left: 5px;">Bacteria&nbsp;:</td>
							<td align=center style="border-bottom: 1px solid black;">'. $uaResult['bacteria'] . '</td>	
						</tr>
						
						<tr>
							<td align="left" style="padding-left: 5px;"></td>
							<td align=center></td>
							<td align="left" style="padding-left: 5px;">Crystals&nbsp;:</td>
							<td align=center style="border-bottom: 1px solid black;">'. $uaResult['crystals'] . '</td>	
						</tr>
						<tr>
							<td align="left" style="padding-left: 5px;"></td>
							<td align=center></td>
							<td align="left" style="padding-left: 5px;">Amorphous (Urates)&nbsp;:</td>
							<td align=center style="border-bottom: 1px solid black;">'. $uaResult['amorphous_urates'] . '</td>	
						</tr>
						<tr>
							<td align="left" style="padding-left: 5px;"></td>
							<td align=center></td>
							<td align="left" style="padding-left: 5px;">Amorphous (PO<sub>4</sub>)&nbsp;:</td>
							<td align=center style="border-bottom: 1px solid black;">'. $uaResult['amorphous_po4'] . '</td>	
						</tr>
						<tr>
							<td align="left" style="padding-left: 5px;" valign=top><b>Note&nbsp;:</b></td>
							<td align=left colspan=2 style="border-bottom: 1px solid black;">'. $uaResult['remarks'] . '</td>
						</tr>
						<tr>
							<td align="left" style="padding-left: 5px;" valign=top><b>Others&nbsp;:</b></td>
							<td align=left colspan=2 style="border-bottom: 1px solid black;">'. $uaResult['others'] . '</td>
						
						</tr>
					</table>';

						$html .= '</td>
					</tr>
					<tr>
						<td width=50% valign=top style="border-right: 1px solid black;">';

								if(count($stoolResult) > 0) {

								$html .= '<table width=100% cellpadding=0 align=center style="font-size: 9pt;"> 
									<tr>
										<td colspan=4 align=center style="font-weight: bold;padding: 5px; background-color: #A9D08E;">STOOL EXAM (FECALYSIS)</td>
									</tr>
									<tr>
										<td align="left" colspan=4  style="padding-left: 5px;"><b>MACROSCOPIC&nbsp;:</b></td>
									</tr>
									<tr>
										<td align="left" width=25%  style="padding-left: 10px;">Color</td>
										<td align=center width=40% style="border-bottom: 1px solid black;vertical-align: top;">'. $stoolResult['color'] . '</td>
										<td align="left" colspan=2  style="padding-left: 15px;"></td>	
									</tr>
									<tr>
										<td align="left" width=25%  style="padding-left: 10px;">Consistency</td>
										<td align=center style="border-bottom: 1px solid black;vertical-align: top;">'. $stoolResult['consistency'] . '</td>
										<td align="left" colspan=2  style="padding-left: 15px;"></td>	
									</tr>
									<tr>
										<td align="left" style="padding-left: 10px;">Blood</td>
										<td align=center style="border-bottom: 1px solid black;">'. $stoolResult['blood'] . '</td>
										<td align="left" colspan=2 style="padding-left: 15px;"></td>	
									</tr>
									<tr>
										<td align="left" style="padding-left: 10px;">Mucus</td>
										<td align=center style="border-bottom: 1px solid black;">'. $stoolResult['mucus'] . '</td>
										<td align="left" colspan=2 style="padding-left: 15px;"></td>	
									</tr>
									<tr>
										<td align="left" style="padding-left: 10px;">Parasites</td>
										<td align=center style="border-bottom: 1px solid black;">'. $stoolResult['parasites'] . '</td>
										<td align="left" colspan=2 style="padding-left: 15px;"></td>	
									</tr>
									<tr>
										<td align="left" colspan=4  style="padding-left: 5px;"><b>MICROSCOPIC&nbsp;:</b></td>
									</tr>
									<tr>
										<td align="left" style="padding-left: 10px;">RBC / hpf&nbsp;:</td>
										<td align=center style="border-bottom: 1px solid black;">'. $stoolResult['rbc'] . '</td>
										<td align="left" colspan=2 style="padding-left: 15px;"></td>	
									</tr>
									<tr>
										<td align="left" style="padding-left: 10px;">WBC / hpf&nbsp;:</td>
										<td align=center style="border-bottom: 1px solid black;">'. $stoolResult['wbc'] . '</td>
										<td align="left" colspan=2 style="padding-left: 15px;"></td>	
									</tr>
									<tr>
										<td align="left" style="padding-left: 10px;" valign=top>Ova & Parasites&nbsp;:</td>
										<td align=left colspan=2 style="border-bottom: 1px solid black;">'. $stoolResult['ova_parasites'] . '</td>
										<td width=10%></td>
									</tr>
									<tr>
										<td align="left" colspan=4  style="padding-left: 5px;"><b>OTHERS&nbsp;:</b></td>
									</tr>
									<tr>
										<td align="left" style="padding-left: 10px;">Bacteria&nbsp;:</td>
										<td align=center style="border-bottom: 1px solid black;">'. $stoolResult['bacteria'] . '</td>
										<td align="left" colspan=2 style="padding-left: 15px;"></td>	
									</tr>
									<tr>
										<td align="left" style="padding-left: 10px;">Fat Globules&nbsp;:</td>
										<td align=center style="border-bottom: 1px solid black;">'. $stoolResult['globules'] . '</td>
										<td align="left" colspan=2 style="padding-left: 15px;"></td>	
									</tr>
									<tr>
										<td align="left" style="padding-left: 10px;">Yeast Cells&nbsp;:</td>
										<td align=center style="border-bottom: 1px solid black;">'. $stoolResult['yeast_cells'] . '</td>
										<td align="left" colspan=2 style="padding-left: 15px;"></td>	
									</tr>
									<tr>
										<td align="left" style="padding-left: 10px;">Occult Blood&nbsp;:</td>
										<td align=center style="border-bottom: 1px solid black;">'. $stoolResult['occult_blood'] . '</td>
										<td align="left" colspan=2 style="padding-left: 15px;"></td>	
									</tr>
									<tr>
										<td align="left" style="padding-left: 5px;" valign=top><b>Note&nbsp;:</b></td>
										<td align=left colspan=2 style="border-bottom: 1px solid black;">'. $stoolResult['remarks'] . '</td>
										<td></td>
									</tr>
								</table>';
							} else {
								echo "&nbsp;";
							}
					$html .= '</td>
						<td width=50% valign=top>
							
							<table width=100% cellpadding=0 align=center style="font-size: 8pt;"> 
								<tr>
									<td colspan=5 align=center style="font-weight: bold;padding: 5px; background-color: #A9D08E;">CHEMISTRY RESULTS</td>
								</tr>
								<tr>
									<td class="itemHeader">TEST</td>
									<td class="itemHeader" align=center>RESULT</td>
									<td class="itemHeader" align=center>UNIT</td>
									<td class="itemHeader" align=center></td>
									<td class="itemHeader">NORMAL VALUES</td>
								</tr>';
            
								$resultQuery = $con->dbquery("SELECT `code`, attribute,unit, `value` FROM lab_singleresult WHERE so_no = '$_REQUEST[so_no]' AND pid = '$outerRow[pid]';");
			
								while($resultRow = $resultQuery->fetch_array()) {
									$html .= '<tr>
									<td class="itemRows">'.$resultRow['attribute'].'</td>
									<td class="itemRows" align=center>'.$resultRow['value'].'</td>
									<td class="itemRows" align=center>'.$resultRow['unit'].'</td>
									<td class="itemRows" align=center>'.$con->checkChemValues($con->calculateAge($soDate,$outerRow['birthdate']),$outerRow['xgender'],$resultRow['code'],$resultRow['value']).'</td>
									<td class="itemRows">'. checkLimits($resultRow['code'],$con->calculateAge($soDate,$outerRow['birthdate']),$outerRow['xgender']) . '</td>
								</tr>';


								}
			
    						$html .= '</table>
							<table width=100% cellpadding=0 align=center style="font-size: 7pt; margin-top: 20px;">';


								if($CBCmedtechFullname == $STOOLmedtechFullname) {
									$html .= '<tr>
											<td align=center valign=top  valign=top width=50%>'.$CBCmedtechSignature.'<br/>'.$CBCmedtechFullname .'<br/>_________________________________________<br><b>CBC/SE PERFORMED BY - Lic. No. '.$CBCmedtechLicense.'</b></td>
										</tr>';
								} else {
									if($CBCmedtechFullname != '') {
										$html .= '<tr>
												<td align=center valign=top  valign=top width=50%>'.$CBCmedtechSignature.'<br/>'.$CBCmedtechFullname .'<br/>_________________________________________<br><b>CBC PERFORMED BY - Lic. No. '.$CBCmedtechLicense.'</b></td>
											</tr>';
									}

									if($stoolResult > 0) {
										$html .= '<tr>
												<td align=center valign=top  valign=top width=50%>'.$STOOLmedtechSignature.'<br/>'.$STOOLmedtechFullname .'<br/>_________________________________________<br><b>SE PERFORMED BY - Lic. No. '.$STOOLmedtechLicense.'</b></td>
											</tr>';
									}

								}

								/* UA & OTHER Results Performed by One Medtech */
								if($UAmedtechLicense == $otherResult['license_no']) {
									$html .= '<tr>
											<td align=center valign=top  valign=top width=50%>'.$UAmedtechSignature.'<br/>'.$UAmedtechFullname .'<br/>_________________________________________<br><b>UA/CHEMISTRY PERFORMED BY - Lic. No. '.$UAmedtechLicense.'</b></td>
										</tr>';

								} else {
									if($UAmedtechFullname != '') {
										$html .= '<tr>
											<td align=center valign=top  valign=top width=50%>'.$UAmedtechSignature.'<br/>'.$UAmedtechFullname .'<br/>_________________________________________<br><b>UA PERFORMED BY - Lic. No. '.$UAmedtechLicense.'</b></td>
										</tr>';
									}

									if(count($otherResult) > 0) {
										$html .= '<tr>
												<td align=center valign=top  valign=top width=50%>'.$otherResult['signature'].'<br/>'.$otherResult['fullname'] .'<br/>_________________________________________<br><b>CHEMISTRY PERFORMED BY - Lic. No. '.$otherResult['license_no'].'</b></td>
											</tr>';
									}

								}

								if($CBCmedtechFullname != '' || $UAmedtechFullname != '' || count($otherResult) > 0 || $STOOLmedtechFullname != '') {
									$html .= '<tr>
											<td align=center valign=top><img src="../images/signatures/psa-signature.png" align=absmidddle width=105 height=35 /><br/><b>PETER S. AZNAR, M.D, F.P.S.P<br/>___________________________________________<br><b>PATHOLOGIST - Lic. No. 72410</b></td>
										</tr>';
								}
							$html .= '</table>
						</td>
					</tr>
				</table>
				</body>
			</html>
		';
		

		$endOfPage = $mpdf->page + 1;
		$html = html_entity_decode($html);
		$mpdf->WriteHTML($html);
		$mpdf->AddPage();
		

		if(count($xrayResult) > 0) {
			//$mpdf->AddPage();
			list($xrayNo) = $con->getArray("select lotno from lab_samples where code like 'X%' and so_no = '$_REQUEST[so_no]' and pid = '$outerRow[pid]';");

			$html = '
				<html>
				<head>
					<style>
						body { font-family: "Times New Roman", Times, serif; font-size: 11pt; }
					</style>
				</head>
				<body style="margin: 20px;">

				<!--mpdf
				<htmlpageheader name="myheader">
					<table width="100%" cellpadding=0 cellspaing=0>
						<tr><td align=center><img src="../images/prime-care-medgruppe.png" /></td></tr>
					</table>
					<table width=100% cellpadding=2 cellspacing=0 style="font-size:8pt;margin-top:5px;">
						<tr>
							<td width="13%"><b>PATIENT NAME</b></td>
							<td width="40%">:&nbsp;&nbsp;'.$outerRow['pname'].'</td>
							<td width="6%"><b>AGE</b></td>
							<td width="14%">:&nbsp;&nbsp;'. $con->calculateAge($soDate,$outerRow['birthdate']) .'yo</td>
							<td width="12%"><b>No.</b></td>
							<td width="15%">:&nbsp;&nbsp;'.$xrayNo.'</td>
						</tr>
						<tr>
							<td><b>ADDRESS</b></td>
							<td>:&nbsp;&nbsp;' . $myaddress . '</td>
							<td><b>DOB</b></td>
							<td>:&nbsp;&nbsp;'. $outerRow['bday'] .'</td>
							<td><b>GENDER</b></td>
							<td>:&nbsp;&nbsp;'. $outerRow['gender'] .'</td>
						</tr>
						<tr>
							<td><b>COMPANY</b></td>
							<td>:&nbsp;&nbsp;' . $cname . '</td>
							<td></td>
							<td></td>
							<td><b>RESULT DATE</b></td>
							<td>:&nbsp;&nbsp;' . $extractDate . '</td>
						</tr>
						<tr>
							<td colspan=6 style="border-top: 1px solid black;">&nbsp;</td>
						</tr>
					</table>
				</htmlpageheader>
		

				<htmlpagefooter name="myfooter">
				<table width=100% cellpadding=5 style="margin-bottom: 25px;">
					<tr>
						<td align=center valign=top width=50%>'.$xrayResult['encsignature'].'<br/>'.$xrayResult['encname'].'<br/>_________________________________________<br><b>RELEASING ENCODER</b></td>
						<td align=center valign=top  valign=top width=50%>'.$consultantSignature.'<br/>'.$xrayResult['fullname'].', '. $xrayResult['prefix'] .'<br/>_________________________________________<br><b>'.$xrayResult['specialization'].' - Lic. No. '.$xrayResult['license_no'].'</b></td>
					</tr>
				</table>
				<table width=100%>
					<tr><td align=left><barcode size=0.8 code="'.substr($outerRow['trace_no'],0,10).'" type="C128A"></td>
					<td align=right>Date & Time Printed: '.date('m/d/Y h:i:s a').'</td></tr>
				</table>
				</htmlpagefooter>

				<sethtmlpageheader name="myheader" value="on" show-this-page="1" />
				<sethtmlpagefooter name="myfooter" value="on" />
				mpdf-->
				<table width=100% cellpadding=10><tr><td width=100% style="font-size: 15px; font-weight: bold;" align=center>XRAY REPORT</td></tr></table>
				<div id="main">'.$xrayResult['impression'].'</div>
				<div id="resultFooter" style="text-align: left; padding-top: 70px; font-style: italic;">Finding is based only on radiographic interpretation. Clinical correlation is suggested.</div>
				</body>
			</html>';


			$endOfPage = $mpdf->page + 1;
			$html = html_entity_decode($html);
			$mpdf->WriteHTML($html);
			$mpdf->AddPage();
			
			}

		}

//}

$mpdf->DeletePages($endOfPage);
$mpdf->Output();
exit;



?>