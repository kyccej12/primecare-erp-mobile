<?php
	session_start();
	ini_set("max_execution_time",0);
	ini_set("memory_limit",-1);

	//ini_set("display_errors","On");

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

	function buakValue($val) {

		$num = explode(".",$val);

		if($num[1]) {
			return number_format($val,2);
		} else {
			return $val;
		}
	}


/* MYSQL QUERIES SECTION */
	$now = date("m/d/Y h:i a");
	$co = $con->getArray("select * from companies where company_id = '$_SESSION[company]';");


	$mpdf=new mPDF('win-1252','LETTER','','',8,8,50,5,5,5);
	$mpdf->use_embeddedfonts_1252 = true;    // false is default
	$mpdf->SetProtection(array('print'));
	$mpdf->SetAuthor("Post80 Business Solutions");
	$mpdf->SetDisplayMode(100);

    list($cname,$myaddress,$soDate,$resultDate) = $con->getArray("select if(company = '',customer_name,company), if(company = '',customer_address,location), cso_date, date_format(until,'%d %b %Y') from cso_header where cso_no = '$_REQUEST[so_no]';");
    $outerRow = $con->getArray("SELECT distinct a.pid, CONCAT(b.lname,', ',b.fname,' ',b.mname) AS pname, b.birthdate, DATE_FORMAT(b.birthdate,'%d %b %Y') AS bday, DATE_FORMAT(FROM_DAYS(DATEDIFF(c.extractdate,b.birthdate)), '%Y') + 0 AS age, IF(b.gender='M','Male','Female') AS gender, b.street, b.brgy, b.city, b.province, b.gender AS xgender, a.barcode, DATE_FORMAT(c.extractdate,'%m/%d/%Y') AS extractdate,  b.employer, a.trace_no FROM cso_details a LEFT JOIN pccmain.patient_info b ON a.pid = b.patient_id LEFT JOIN lab_samples c ON a.pid = c.pid WHERE a.cso_no = '$_REQUEST[so_no]' AND a.pid = '$_REQUEST[pid]' GROUP BY a.pid limit 1;");

	list($extractDateNonXray) = $con->getArray("select date_format(extractdate,'%m/%d/%Y') from lab_samples where so_no = '$_REQUEST[so_no]' and pid = '$outerRow[pid]' and `code` not like 'X%' GROUP BY so_no, pid;");
	list($extractDateXray) = $con->getArray("select date_format(extractdate,'%m/%d/%Y') from lab_samples where so_no = '$_REQUEST[so_no]' and pid = '$outerRow[pid]' and `code` like 'X%' GROUP BY so_no, pid;");
	$age = $con->calculateAge($soDate,$outerRow['birthdate']);
	
	$CBCmedtechFullname = '';
	$CBCmedtechSignature = '';
	$CBCmedtechLicense = '';
	$UAmedtechFullname = '';
	$UAmedtechSignature = '';
	$UAmedtechLicense = '';


	/* CBC Result */
	list($cbcSN) = $con->getArray("select serialno from lab_samples where pid = '$outerRow[pid]' and `code` = 'L010' and so_no = '$_REQUEST[so_no]';");
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
	$xrayResult = $con->getArray("select impression, consultant, b.signature_file, b.fullname, b.prefix, b.specialization, b.license_no, c.fullname as encname, if(c.signature_file != '',concat('<img src=\"../images/signatures/',c.signature_file,'\" align=absmiddle width=105 height=35 />'),'<img src=\"../images/signatures/blank.png\" align=absmiddle width=105 height=35 />') as encsignature from lab_descriptive a left join options_doctors b on a.consultant = b.id left join pccmain.user_info c on a.created_by = c.emp_id where so_no = '$_REQUEST[so_no]' and pid = '$outerRow[pid]' limit 1;");
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
						<td width="14%">:&nbsp;&nbsp;'. $outerRow['age'] .'yo</td>
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
						<td>:&nbsp;&nbsp;' . $extractDateNonXray . '</td>
					</tr>
					<tr>
						<td colspan=6 style="border-top: 1px solid black;">&nbsp;</td>
					</tr>
				</table>
			</htmlpageheader>

			<htmlpagefooter name="myfooter">
				<table width=100%>
					<tr><td align=left><barcode size=0.8 code="'.$outerRow['trace_no'].'" type="C128A"></td><td align=right>Date & Time Printed: '.date('m/d/Y h:i:s a').'</td></tr>
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
								<td align="left" style="padding-left: 15px;">WBC '.$con->checkCBCValues($age,$outerRow['xgender'],"WBC",$cbcResult['wbc']).'</td>
								<td align=center style="border-bottom: 1px solid black;vertical-align: top;">'. number_format($cbcResult['wbc']) . '/mm^3</td>
								<td align="left" style="padding-left: 15px;">'.$con->getCBCAttribute($age,$outerRow['xgender'],"WBC").'</td>	
							</tr>
						
							<tr>
								<td align="left" style="padding-left: 15px;" valign=top>RBC '.$con->checkCBCValues($age,$outerRow['xgender'],"RBC",$cbcResult['rbc']).'</td>
								<td align=center style="border-bottom: 1px solid black;" valign=top>'. $cbcResult['rbc'] . ' x 10^6/mm^3</td>
								<td align="left" style="padding-left: 15px;" valign=top>'.$con->getCBCAttribute($age,$outerRow['xgender'],"RBC").'</td>	
							</tr>
						
							<tr>
								<td align="left" style="padding-left: 15px;">Hemoglobin '.$con->checkCBCValues($age,$outerRow['xgender'],"HEMOGLOBIN",$cbcResult['hemoglobin']).'</td>
								<td align=center style="border-bottom: 1px solid black;">'. $cbcResult['hemoglobin'] . ' gm%</td>
								<td align="left" style="padding-left: 15px;">'. $con->getCBCAttribute($age,$outerRow['xgender'],"HEMOGLOBIN") . '</td>	
							</tr>
							<tr>
								<td align="left" style="padding-left: 15px;">Hematocrit '.$con->checkCBCValues($age,$outerRow['xgender'],"HEMATOCRIT",$cbcResult['hematocrit']).'</td>
								<td align=center style="border-bottom: 1px solid black;">'. $cbcResult['hematocrit'] . ' vol%</td>
								<td align="left" style="padding-left: 15px;">'. $con->getCBCAttribute($age,$outerRow['xgender'],"HEMATOCRIT").'</td>	
							</tr>
							<tr><td height=5>&nbsp;</td></tr>
							<tr>
								<td align="left" colspan=3  style="padding-left: 15px;"><b>Differential Count&nbsp;:</b></td>
							</tr>
							<tr>
								<td align="left" style="padding-left: 35px;">Neutrophils '.$con->checkCBCValues($age,$outerRow['xgender'],"NEUTROPHILS",$cbcResult['neutrophils']).'</td>
								<td align=center style="border-bottom: 1px solid black;">'. ROUND($cbcResult['neutrophils']) . '%</td>
								<td align="left" style="padding-left: 15px;">'.$con->getCBCAttribute($age,$outerRow['xgender'],"NEUTROPHILS").'</td>	
							</tr>
							<tr>
								<td align="left" style="padding-left: 35px;">Lymphocytes '.$con->checkCBCValues($age,$outerRow['xgender'],"LYMPHOCYTES",$cbcResult['lymphocytes']).'</td>
								<td align=center style="border-bottom: 1px solid black;">'. ROUND($cbcResult['lymphocytes']) . '%</td>
								<td align="left" style="padding-left: 15px;">'.$con->getCBCAttribute($age,$outerRow['xgender'],"LYMPHOCYTES").'</td>	
							</tr>
							<tr>
								<td align="left" style="padding-left: 35px;">Monocytes '.$con->checkCBCValues($age,$outerRow['xgender'],"MONOCYTES",$cbcResult['monocytes']).'</td>
								<td align=center style="border-bottom: 1px solid black;">'. ROUND($cbcResult['monocytes']) . '%</td>
								<td align="left" style="padding-left: 15px;">'.$con->getCBCAttribute($age,$outerRow['xgender'],"MONOCYTES").'</td>	
							</tr>
							<tr>
								<td align="left" style="padding-left: 35px;">Eosinophils '.$con->checkCBCValues($age,$outerRow['xgender'],"EOSINOPHILS",$cbcResult['eosinophils']).'</td>
								<td align=center style="border-bottom: 1px solid black;">'. ROUND($cbcResult['eosinophils']) . '%</td>
								<td align="left" style="padding-left: 15px;">'.$con->getCBCAttribute($age,$outerRow['xgender'],"EOSINOPHILS").'</td>	
							</tr>
							<tr>
								<td align="left" style="padding-left: 35px;">Basophils '.$con->checkCBCValues($age,$outerRow['xgender'],"BASOPHILS",$cbcResult['basophils']).'</td>
								<td align=center style="border-bottom: 1px solid black;">'. ROUND($cbcResult['basophils']) . '%</td>
								<td align="left" style="padding-left: 15px;">'.$con->getCBCAttribute($age,$outerRow['xgender'],"BASOPHILS").'</td>	
							</tr>
							<tr>
								<td align="left" style="padding-left: 15px;">Platelate Count '.$con->checkCBCValues($age,$outerRow['xgender'],"PLATELATE",$cbcResult['platelate']).'</td>
								<td align=center style="border-bottom: 1px solid black;">'. number_format($cbcResult['platelate']) . '/mm^3</td>
								<td align="left" style="padding-left: 15px;">'.$con->getCBCAttribute($age,$outerRow['xgender'],"PLATELATE").'</td>	
							</tr>
							<tr>
								<td align="left" style="padding-left: 15px;" valign=top>Remarks</td>
								<td align=left colspan=2 style="border-bottom: 1px solid black;">'. $cbcResult['remarks'] . '</td>
							</tr>
						</table>';

					$html .= '</td>

					<td width=50% valign=top>

						<table width=100% cellpadding=0 align=center style="font-size: 9pt;"> 
							<tr>
								<td colspan=4 align=center style="font-weight: bold;padding: 5px; background-color: #A9D08E;">URINALYSIS</td>
							</tr>
							<tr>
								<td align="left" colspan=3  style="padding-left: 5px;"><b>MACROSCOPIC&nbsp;:</b></td>
							</tr>
							<tr>
								<td align="left" width=40%></td>
								<td align=center width=40%></td>
								<td align="left" width=20%></td>	
							</tr>
							<tr>
								<td align="left" style="padding-left: 10px;">Color</td>
								<td align=center style="border-bottom: 1px solid black;vertical-align: top;">'. $uaResult['color'] . '</td>
								<td align="left"></td>	
							</tr>
							<tr>
								<td align="left" style="padding-left: 10px;">Appearance</td>
								<td align=center style="border-bottom: 1px solid black;vertical-align: top;">'. $uaResult['appearance'] . '</td>
								<td align="left"></td>	
							</tr>
							<tr>
								<td align="left" style="padding-left: 10px;">pH</td>
								<td align=center style="border-bottom: 1px solid black;">'. $uaResult['ph'] . '</td>
								<td align="left"></td>	
							</tr>
							<tr>
								<td align="left" style="padding-left: 10px;">Specific Gravity</td>
								<td align=center style="border-bottom: 1px solid black;">'. $uaResult['gravity'] . '</td>
								<td align="left"></td>	
							</tr>
							<tr>
								<td align="left" style="padding-left: 10px;">Glucose</td>
								<td align=center style="border-bottom: 1px solid black;">'. $uaResult['glucose'] . '</td>
								<td align="left"></td>	
							</tr>
							<tr>
								<td align="left" style="padding-left: 10px;">Protein</td>
								<td align=center style="border-bottom: 1px solid black;">'. $uaResult['protein'] . '</td>
								<td align="left"></td>	
							</tr>
							<tr>
								<td align="left" colspan=3  style="padding-left: 5px;"><b>MICROSCOPIC&nbsp;:</b></td>
							</tr>
							<tr>
								<td align="left" style="padding-left: 10px;">RBC / hpf&nbsp;:</td>
								<td align=center style="border-bottom: 1px solid black;">'. $uaResult['rbc_hpf'] . '</td>
								<td align="left"></td>	
							</tr>
							<tr>
								<td align="left" style="padding-left: 10px;">WBC / hpf&nbsp;:</td>
								<td align=center style="border-bottom: 1px solid black;">'. $uaResult['wbc_hpf'] . '</td>
								<td align="left"></td>	
							</tr>
							<tr>
								<td align="left" style="padding-left: 10px;">Epith. Cells&nbsp;:</td>
								<td align=center style="border-bottom: 1px solid black;">'. $uaResult['squamous'] . '</td>
								<td align="left"></td>	
							</tr>
							<tr>
								<td align="left" style="padding-left: 10px;">Casts&nbsp;:</td>
								<td align=center style="border-bottom: 1px solid black;">'. $casts . '</td>
								<td align="left"></td>	
							</tr>
							<tr>
								<td align="left" style="padding-left: 10px;">Mucus Threads&nbsp;:</td>
								<td align=center style="border-bottom: 1px solid black;">'. $uaResult['mucus_thread'] . '</td>
								<td align="left"></td>	
							</tr>
							<tr>
								<td align="left" style="padding-left: 10px;">Bacteria&nbsp;:</td>
								<td align=center style="border-bottom: 1px solid black;">'. $uaResult['bacteria'] . '</td>
								<td align="left"></td>	
							</tr>
							<tr>
								<td align="left" style="padding-left: 10px;">Crystals&nbsp;:</td>
								<td align=center style="border-bottom: 1px solid black;">'. $crystals . '</td>
								<td align="left"></td>	
							</tr>
							<tr>
								<td align="left" style="padding-left: 10px;">Amorphous (Urates)&nbsp;:</td>
								<td align=center style="border-bottom: 1px solid black;">'. $uaResult['amorphous_urates'] . '</td>
								<td align="left"></td>	
							</tr>
							<tr>
								<td align="left" style="padding-left: 10px;">Amorphous (PO<sub>4</sub>)&nbsp;:</td>
								<td align=center style="border-bottom: 1px solid black;">'. $uaResult['amorphous_po4'] . '</td>
								<td align="left"></td>	
							</tr>
							<tr>
								<td align="left" style="padding-left: 5px;" valign=top><b>Note&nbsp;:</b></td>
								<td align=left width=70% colspan=2 style="border-bottom: 1px solid black;">'. $uaResult['remarks'] . '</td>
							</tr>
							<tr>
								<td align="left" style="padding-left: 5px;" valign=top><b>Others&nbsp;:</b></td>
								<td align=left width=70% colspan=2 style="border-bottom: 1px solid black;">'. $uaResult['others'] . '</td>
							
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
					<td width=50% valign=middle>';

						
						
						$html .= '<table width=100% cellpadding=0 align=center style="font-size: 7pt; margin-top: 20px;">';


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

	$html = html_entity_decode($html);
	$mpdf->WriteHTML($html);
	//$mpdf->AddPage();
	
	/* CHEMISTRY RESULT AS ANOTHER PAGE */
	list($chemCount) = $con->getArray("select count(*) from (SELECT DISTINCT a.code, a.procedure FROM lab_samples a LEFT JOIN pccmain.services_master b ON a.code = b.code WHERE b.category = '1' AND so_no = '$_REQUEST[so_no]' and a.pid = '$outerRow[pid]') a;");
	if(count($chemCount) > 0) {
		$mpdf->addPage();
		
		/* Results Query */
		$b = $con->getArray("SELECT verified, verified_by FROM lab_singleresult WHERE so_no = '$_REQUEST[so_no]' and pid = '$outerRow[pid]';");
		$lipidRes = $con->getArray("SELECT a.* FROM lab_lipidpanel a WHERE a.so_no = '$_REQUEST[so_no]' and a.pid = '$outerRow[pid]';");

		if($b['verified_by'] != '') {
			list($medtechSignature,$medtechFullname,$medtechLicense,$medtechRole) = $con->getArray("SELECT if(signature_file != '',concat('<img src=\"../images/signatures/',signature_file,'\" align=absmiddle />'),'<img src=\"../images/signatures/blank.png\" align=absmiddle />') as signature, fullname, license_no, role from user_info where emp_id = '$b[verified_by]';");
		}



		$html = '<html>
		<head>
			<style>
				body {font-family: arial; font-size: 10pt; }
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
					<td width="14%">:&nbsp;&nbsp;'. $outerRow['age'] .'yo</td>
					<td width="12%"><b>No.</b></td>
					<td width="15%">&nbsp;&nbsp;MOB-'.$_REQUEST['so_no'].'-'.$outerRow['pid'].'</td>
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
					<td>:&nbsp;&nbsp;' . $extractDateNonXray . '</td>
				</tr>
				<tr>
					<td colspan=6 style="border-top: 1px solid black;">&nbsp;</td>
				</tr>
			</table>
		</htmlpageheader>


		<htmlpagefooter name="myfooter">
			<table width=100% cellpadding=5 style="margin-bottom: 25px;">
				<tr>
				<td align=center valign=top>'.$medtechSignature.'<br/><b>'.$medtechFullname.'<br/>___________________________________________<br>'.$medtechRole.'<br/>License No. '.$medtechLicense.'</b></td>
				<td align=center valign=top><img src="../images/signatures/psa-signature.png" align=absmidddle /><br/><b>PETER S. AZNAR, M.D, F.P.S.P<br/>___________________________________________<br><b>PATHOLOGIST</b><br><span style="font-size: 7pt;">PRC LICENSE NO. 72410</span></td>
				</tr>
			</table>
			<table width=100%>
				<tr><td align=left><barcode size=0.8 code="'.$_ihead['trace_no'].'" type="C128A"></td><td align=right>Run Date: '.date('m/d/Y h:i:s a').'</td></tr>
			</table>
		</htmlpagefooter>

		<sethtmlpageheader name="myheader" value="on" show-this-page="1" />
		<sethtmlpagefooter name="myfooter" value="on" />
		mpdf-->
		
		<table width=60% cellpadding=0 cellspacing=0 align=center style="margin: 5px;">
        <tr><td align=center><span style="font-size: 12pt; font-weight: bold;">CHEMISTRY REPORT</span></td></tr>
    </table>

	<table width=80% cellpadding=0 cellspacing=0 align=center style="border-collapse: collapse; font-size: 15px;">
            <tr>
                <td class="itemHeader">TEST</td>
                <td class="itemHeader" align=center>RESULT</td>
				<td class="itemHeader" align=center>UNIT</td>
                <td class="itemHeader">NORMAL VALUES</td>
            </tr>';

			$rbs = $con->getArray("SELECT `code`, attribute,unit, `value` FROM lab_singleresult WHERE `code` = 'L174' and so_no = '$_REQUEST[so_no]' AND pid = '$outerRow[pid]';");
			if(count($rbs) > 0) {
				$html .= '<tr>
				     <td class="itemRows">Glucose/RBS</td>
				     <td class="itemRows" align=center>'.$rbs['value'].'</td>
				 	<td class="itemRows" align=center>'.$rbs['unit'].'</td>
				     <td class="itemRows">'. checkLimits($rbs['code'],$con->calculateAge($_ihead['so_date'],$_ihead['birthdate']),$_ihead['xgender']) . '</td>
				 </tr>';
			}
            
			$fbs = $con->getArray("SELECT `code`, attribute,unit, `value` FROM lab_singleresult WHERE `code` = 'L113' and so_no = '$_REQUEST[so_no]' AND pid = '$outerRow[pid]';");
			if(count($fbs) > 0) {
				$html .= '<tr>
				     <td class="itemRows">Glucose/FBS</td>
				     <td class="itemRows" align=center>'.$fbs['value'].'</td>
				 	<td class="itemRows" align=center>'.$fbs['unit'].'</td>
				     <td class="itemRows">'. checkLimits($fbs['code'],$con->calculateAge($_ihead['so_date'],$_ihead['birthdate']),$outerRow['xgender']) . '</td>
				 </tr>';
			}
			
			$lipidRes = $con->getArray("SELECT cholesterol, triglycerides, hdl, ldl, vldl  FROM lab_lipidpanel WHERE so_no = '$_REQUEST[so_no]' AND pid = '$outerRow[pid]';");
			if(count($lipidRes) > 0) {
				$html .= '<tr>
					<td style="padding:5px;">Lipid Panel</td>
					<td align=center>&nbsp;</td>
					<td align=center>&nbsp;</td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td class="itemRows">Total Cholesterol</td>
					<td class="itemRows" align=center>'.$lipidRes['cholesterol'].'</td>
					<td class="itemRows" align=center>'.$fbs['unit'].'</td>
					<td class="itemRows">'.$con->getAttribute('L019',$con->age,$outerRow['xgender']).'</td>
				</tr>
				<tr>
					<td class="itemRows">Triglycerides</td>
					<td class="itemRows" align=center>'.$lipidRes['triglycerides'].'</td>
					<td class="itemRows" align=center>'.$fbs['unit'].'</td>
					<td class="itemRows">'.$con->getAttribute('L032',$con->age,$outerRow['xgender']).'</td>
				</tr>
				<tr>
					<td class="itemRows">HDL</td>
					<td class="itemRows" align=center>'.$lipidRes['hdl'].'</td>
					<td class="itemRows" align=center>'.$fbs['unit'].'</td>
					<td class="itemRows">35-60 mg/dL</td>
				</tr>
				<tr>
					<td class="itemRows">LDL</td>
					<td class="itemRows" align=center>'.$lipidRes['ldl'].'</td>
					<td class="itemRows" align=center>'.$fbs['unit'].'</td>
					<td class="itemRows">70-180 mg/dL</td>
				</tr>
				<tr>
					<td class="itemRows">VLDL</td>
					<td class="itemRows" align=center>'.$lipidRes['vldl'].'</td>
					<td class="itemRows" align=center>'.$fbs['unit'].'</td>
					<td class="itemRows">0-40 mg/dL</td>
				</tr>
				<tr>
					<td style="padding:5px;">&nbsp;</td>
					<td align=center>&nbsp;</td>
					<td align=center>&nbsp;</td>
					<td>&nbsp;</td>
				</tr>';
				}

				$hba1c = $con->getArray("SELECT `code`, attribute,unit, `value` FROM lab_singleresult WHERE `code` = 'L022' and so_no = '$_REQUEST[so_no]' AND pid = '$outerRow[pid]';");
				if(count($hba1c) > 0) {
					$html .= '<tr>
						<td class="itemRows">SGPT</td>
						<td class="itemRows" align=center>'.buakValue($hba1c['value']).'</td>
						<td class="itemRows" align=center>'.$hba1c['unit'].'</td>
						<td class="itemRows">'. checkLimits($hba1c['code'],$con->calculateAge($soDate,$outerRow['birthdate'],$outerRow['xgender']),$outerRow['xgender']) . '</td>
						</tr>';
				}

				$sgptalt = $con->getArray("SELECT `code`, attribute,unit, `value` FROM lab_singleresult WHERE `code` = 'L029' and so_no = '$_REQUEST[so_no]' AND pid = '$outerRow[pid]';");
				if(count($sgptalt) > 0) {
					$html .= '<tr>
						<td class="itemRows">SGPT/ALT</td>
						<td class="itemRows" align=center>'.buakValue($sgptalt['value']).'</td>
						<td class="itemRows" align=center>'.$sgptalt['unit'].'</td>
						<td class="itemRows">'. checkLimits($sgptalt['code'],$con->calculateAge($soDate,$outerRow['birthdate'],$outerRow['xgender']),$outerRow['xgender']) . '</td>
						</tr>';
				}

				$ast = $con->getArray("SELECT `code`, attribute,unit, `value` FROM lab_singleresult WHERE `code` = 'L028' and so_no = '$_REQUEST[so_no]' AND pid = '$outerRow[pid]';");
				if(count($ast) > 0) {
					$html .= '<tr>
						<td class="itemRows">AST</td>
						<td class="itemRows" align=center>'.buakValue($ast['value']).'</td>
						<td class="itemRows" align=center>'.$ast['unit'].'</td>
						<td class="itemRows">'. checkLimits($ast['code'],$con->calculateAge($soDate,$outerRow['birthdate'],$outerRow['xgender']),$outerRow['xgender']) . '</td>
						</tr>';
				}

				$bun = $con->getArray("SELECT `code`, attribute,unit, `value` FROM lab_singleresult WHERE `code` = 'L005' and so_no = '$_REQUEST[so_no]' AND pid = '$outerRow[pid]';");
				if(count($bun) > 0) {
					$html .= '<tr>
						<td class="itemRows">BUN</td>
						<td class="itemRows" align=center>'.buakValue($bun['value']).'</td>
						<td class="itemRows" align=center>'.$bun['unit'].'</td>
						<td class="itemRows">'. checkLimits($bun['code'],$con->calculateAge($soDate,$outerRow['birthdate'],$outerRow['xgender']),$outerRow['xgender']) . '</td>
						</tr>';
				}

				$bua = $con->getArray("SELECT `code`, attribute,unit, `value` FROM lab_singleresult WHERE `code` = 'L004' and so_no = '$_REQUEST[so_no]' AND pid = '$outerRow[pid]';");
				if(count($bua) > 0) {
					$html .= '<tr>
						<td class="itemRows">BUA</td>
						<td class="itemRows" align=center>'.buakValue($bua['value']).'</td>
						<td class="itemRows" align=center>'.$bua['unit'].'</td>
						<td class="itemRows">'. checkLimits($bua['code'],$con->calculateAge($soDate,$outerRow['birthdate'],$outerRow['xgender']),$outerRow['xgender']) . '</td>
						</tr>';
				}

				$crea = $con->getArray("SELECT `code`, attribute,unit, `value` FROM lab_singleresult WHERE `code` = 'L020' and so_no = '$_REQUEST[so_no]' AND pid = '$outerRow[pid]';");
				if(count($crea) > 0) {
					$html .= '<tr>
						<td class="itemRows">CREATININE</td>
						<td class="itemRows" align=center>'.buakValue($crea['value']).'</td>
						<td class="itemRows" align=center>'.$crea['unit'].'</td>
						<td class="itemRows">'. checkLimits($crea['code'],$con->calculateAge($soDate,$outerRow['birthdate'],$outerRow['xgender']),$outerRow['xgender']) . '</td>
						</tr>';
				}

				$alp = $con->getArray("SELECT `code`, attribute,unit, `value` FROM lab_singleresult WHERE `code` = 'L016' and so_no = '$_REQUEST[so_no]' AND pid = '$outerRow[pid]';");
				if(count($alp) > 0) {
					$html .= '<tr>
						<td class="itemRows">ALP</td>
						<td class="itemRows" align=center>'.buakValue($alp['value']).'</td>
						<td class="itemRows" align=center>'.$alp['unit'].'</td>
						<td class="itemRows">'. checkLimits($alp['code'],$con->calculateAge($soDate,$outerRow['birthdate'],$outerRow['xgender']),$outerRow['xgender']) . '</td>
						</tr>';
				}

				$potassium = $con->getArray("SELECT `code`, attribute,unit, `value` FROM lab_singleresult WHERE `code` = 'L026' and so_no = '$_REQUEST[so_no]' AND pid = '$outerRow[pid]';");
				if(count($potassium) > 0) {
					$html .= '<tr>
						<td class="itemRows">POTASSIUM</td>
						<td class="itemRows" align=center>'.buakValue($potassium['value']).'</td>
						<td class="itemRows" align=center>'.$potassium['unit'].'</td>
						<td class="itemRows">'. checkLimits($potassium['code'],$con->calculateAge($soDate,$outerRow['birthdate'],$outerRow['xgender']),$outerRow['xgender']) . '</td>
						</tr>';
				}

			$html .= '</table>
			<table width=60% align=center style="margin-top: 5px; font-size: 9pt; font-style: italic;">
				<tr>
					<td align=left width=18%><b>REMARKS :</b></td>
					<td align=left width=82% style="border-bottom: 1px solid black;">'.$_ihead['remarks'].'</td>
				</tr>
			</table>
		
		';

		$endOfPage = $mpdf->page + 1;
		$html = html_entity_decode($html);
		$mpdf->WriteHTML($html);

	}

	if(count($xrayResult) > 0) {
		$mpdf->AddPage();
		list($xrayNo) = $con->getArray("select lotno from lab_samples where code like '%X%' and so_no = '$_REQUEST[so_no]' and pid = '$outerRow[pid]' limit 1;");

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
						<td width="14%">:&nbsp;&nbsp;'. $outerRow['age'] .'yo</td>
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
						<td>:&nbsp;&nbsp;' . $extractDateXray . '</td>
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
				<tr><td align=left><barcode size=0.8 code="'.$outerRow['trace_no'].'" type="C128A"></td><td align=right>Date & Time Printed: '.date('m/d/Y h:i:s a').'</td></tr>
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
		

	}
//}

$mpdf->Output();
exit;
exit;



?>