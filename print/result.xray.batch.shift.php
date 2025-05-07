<?php
	session_start();
	//ini_set("display_errors","on");
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


	$mpdf=new mPDF('win-1252','LETTER','','',8,8,50,5,5,5);
	$mpdf->use_embeddedfonts_1252 = true;    // false is default
	$mpdf->SetProtection(array('print'));
	$mpdf->SetAuthor("PORT80 Solutions");
	$searchString = '';

	list($cname,$soDate,$resultDate) = $con->getArray("select company,cso_date, date_format(until,'%d %b %Y') from cso_header where cso_no = '$_REQUEST[so_no]';");

	$outerQuery = $con->dbquery("SELECT a.pid, CONCAT(b.lname,', ',b.fname,' ',b.mname) AS pname, b.birthdate, DATE_FORMAT(b.birthdate,'%d %b %Y') AS bday, IF(b.gender='M','Male','Female') AS gender, b.street, b.brgy, b.city, b.province, b.gender AS xgender, a.barcode, c.extractdate FROM cso_details a LEFT JOIN pccmain.patient_info b ON a.pid = b.patient_id LEFT JOIN lab_samples c ON a.pid = c.pid WHERE a.cso_no = '92' AND c.`code` LIKE '%X%' AND a.processed_on BETWEEN '2023-11-09 18:00:01' AND '2023-11-10 03:00:00' AND c.extractdate != '' GROUP BY a.pid ORDER BY b.lname ASC, b.fname, b.mname;");
	while($outerRow = $outerQuery->fetch_array()) {

		list($extractDate) = $con->getArray("select date_format(extractdate,'%m/%d/%Y') from lab_samples where so_no = '$_REQUEST[so_no]' and pid = '$outerRow[pid]';");


		$age = $con->calculateAge($soDate,$outerRow['birthdate']);

		$myaddress = '';
		list($brgy) = $con->getArray("SELECT brgyDesc FROM options_brgy WHERE brgyCode = '$outerRow[brgy]';");
		list($ct) = $con->getArray("SELECT citymunDesc FROM options_cities WHERE cityMunCode = '$outerRow[city]';");
		list($prov) = $con->getArray("SELECT provDesc FROM options_provinces WHERE provCode = '$outerRow[province]';");
	
		if($outerRow['street'] != '') { $myaddress.=$outerRow['street'].", "; }
		if($brgy != "") { $myaddress .= $brgy.", "; }
		if($ct != "") { $myaddress .= $ct.", "; }
		if($prov != "")  { $myaddress .= $prov.", "; }
		$myaddress = substr($myaddress,0,-2);

		/* Xray Result */
		$xrayResult = $con->getArray("select impression, consultant, b.signature_file, b.fullname, b.prefix, b.specialization, b.license_no, c.fullname as encname, if(c.signature_file != '',concat('<img src=\"../images/signatures/',c.signature_file,'\" align=absmiddle />'),'<img src=\"../images/signatures/blank.png\" align=absmiddle />') as encsignature, a.consultant as conid, a.result_stat as rstat from lab_descriptive a left join options_doctors b on a.consultant = b.id left join user_info c on a.created_by = c.emp_id where so_no = '$_REQUEST[so_no]' and pid = '$outerRow[pid]' limit 1;");
		if($xrayResult['signature_file'] != '') {
			$consultantSignature = "<img src='../images/signatures/$xrayResult[signature_file]' align=absmiddle />";
		} else {
			$consultantSignature = "<img src='../images/signatures/blank.png' align=absmiddle />";	
		}


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
				<body>';

		if(count($xrayResult) > 0) {

			list($xrayNo) = $con->getArray("select lotno from lab_samples where code like 'X%' and so_no = '$_REQUEST[so_no]' and pid = '$outerRow[pid]' limit 1;");

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
		

				<htmlpagefooter name="myfooter">';

				if($xrayResult['conid'] != '142') {

	$html .=	'<table width=100% cellpadding=5 style="margin-bottom: 25px;">
					<tr>
						<td align=center valign=top width=50%>'.$xrayResult['encsignature'].'<br/>'.$xrayResult['encname'].'<br/>_________________________________________<br><b>RELEASING ENCODER</b></td>
						<td align=center valign=top  valign=top width=50%>'.$consultantSignature.'<br/>'.$xrayResult['fullname'].', '. $xrayResult['prefix'] .'<br/>_________________________________________<br><b>'.$xrayResult['specialization'].' - Lic. No. '.$xrayResult['license_no'].'</b></td>
					</tr>
				</table>';
				}else {
					if($xrayResult['rstat'] == 'Y') {
	$html .= '			<table width=100% cellpadding=5 style="margin-bottom: 25px;">
							<tr>
								<td align=center valign=top width=50%>'.$xrayResult['encsignature'].'<br/>'.$xrayResult['encname'].'<br/>_________________________________________<br><b>RELEASING ENCODER</b></td>
								<td align=center valign=top  valign=top width=50%>'.$consultantSignature.'<br/>'.$xrayResult['fullname'].', '. $xrayResult['prefix'] .'<br/>_________________________________________<br><b>'.$xrayResult['specialization'].' - Lic. No. '.$xrayResult['license_no'].'</b></td>
							</tr>
						</table>';
					}else {
	$html .=			'<table width=100% cellpadding=5 style="margin-bottom: 25px;">
							<tr>
								<td align=center valign=top width=50%></td>
								<td align=center valign=top  valign=top width=50%>'.$consultantSignature.'<br/>'.$xrayResult['fullname'].', '. $xrayResult['prefix'] .'<br/>_________________________________________<br><b>'.$xrayResult['specialization'].' - Lic. No. '.$xrayResult['license_no'].'</b></td>
							</tr>
						</table>';
					}
				}
				
	$html .=			'<table width=100%>
					<tr><td align=left><barcode size=0.8 code="'.$_ihead['trace_no'].'" type="C128A"></td><td align=right>Date & Time Printed: '.date('m/d/Y h:i:s a').'</td></tr>
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