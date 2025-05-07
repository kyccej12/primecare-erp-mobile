<?php
	session_start();
	include("../lib/mpdf6/mpdf.php");
	include("../handlers/_generics.php");

	$con = new _init;

/* MYSQL QUERIES SECTION */
	$now = date("m/d/Y h:i a");
	$co = $con->getArray("select * from companies where company_id = '$_SESSION[company]';");

	$_ihead = $con->getArray("SELECT DATE_FORMAT(result_date,'%m/%d/%Y') AS rdate, CONCAT(b.lname,', ',b.fname,' ',b.mname) AS pname, b.street,b.brgy,b.city,b.province, IF(b.gender='M','Male','Female') AS gender,b.birthdate,a.result_date,c.fullname AS consultant, c.prefix, c.license_no, c.specialization, c.signature_file, a.serialno, a.procedure, a.impression, a.created_by, a.verified, a.verified_by, d.role, d.signature_file AS encodersignature, b.employer, a.serialno, b.patient_id as pid, a.consultant as conid, a.result_stat as rstat FROM lab_descriptive a LEFT JOIN pccmain.patient_info b ON a.pid = b.patient_id LEFT JOIN pccmobile.options_doctors c ON a.consultant = c.id LEFT JOIN pccmain.user_info d ON a.created_by = d.emp_id WHERE a.so_no = '$_REQUEST[so_no]' AND `code` = '$_REQUEST[code]' AND serialno = '$_REQUEST[serialno]';");
	list($lotno) = $con->getArray("select lotno from lab_samples where serialno = '$_ihead[serialno]';");

	list($cname,$soDate,$resultDate) = $con->getArray("select company,cso_date, date_format(until,'%d %b %Y') from cso_header where cso_no = '$_REQUEST[so_no]';");

	list($employer) = $con->getArray("select employer from pccmain.patient_info where patient_id = '$_ihead[pid]';");

	if($lotno == '') { $lotno = "SO-".$_REQUEST['so_no']; }

	if($_ihead['signature_file'] != '') {
		$consultantSignature = "<img src='../images/signatures/$_ihead[signature_file]' align=absmiddle />";
	} else {
		$consultantSignature = "<img src='../images/signatures/blank.png' align=absmiddle />";	
	}		

	list($brgy) = $con->getArray("SELECT brgyDesc FROM options_brgy WHERE brgyCode = '$_ihead[brgy]';");
    list($ct) = $con->getArray("SELECT citymunDesc FROM options_cities WHERE cityMunCode = '$_ihead[city]';");
    list($prov) = $con->getArray("SELECT provDesc FROM options_provinces WHERE provCode = '$_ihead[province]';");

    if($_ihead['street'] != '') { $myaddress.=$_ihead['street'].", "; }
    if($brgy != "") { $myaddress .= $brgy.", "; }
    if($ct != "") { $myaddress .= $ct.", "; }
    if($prov != "")  { $myaddress .= $prov.", "; }
    $myaddress = substr($myaddress,0,-2);


	if($_ihead['verified_by'] != '') {
        list($medtechSignature,$medtechFullname,$medtechLicense,$medtechRole) = $con->getArray("SELECT if(signature_file != '',concat('<img src=\"../images/signatures/',signature_file,'\" align=absmiddle />'),'<img src=\"../images/signatures/blank.png\" align=absmiddle />') as signature, fullname, license_no, role from user_info where emp_id = '$_ihead[verified_by]';");
    }
/* END OF SQL QUERIES */

$mpdf=new mPDF('win-1252','LETTER','','',15,15,90,30,15,15);
$mpdf->use_embeddedfonts_1252 = true;    // false is default
$mpdf->SetProtection(array('print'));
$mpdf->SetAuthor("PORT80 Solutions");

if($_ihead['verified'] != 'Y') {
	$mpdf->SetWatermarkText('FOR VALIDATION');
	$mpdf->showWatermarkText = true;
}

$mpdf->SetDisplayMode(50);

$html = '
<html>
<head>
	<style>
		body { font-family: "Times New Roman", Times, serif; font-size: 11pt; }
	</style>
</head>
<body>

<!--mpdf
<htmlpageheader name="myheader">
<table width="100%" cellpadding=0 cellspaing=0>
	<tr><td align=center><img src="../images/prime-care-medgruppe.png" /></td></tr>
</table>
<table width=100% cellpadding=2 cellspacing=0 style="font-size: 9pt;margin-top:20px;">
	<tr>
		<td width=100% colspan=4 style="background-color: #cdcdcd; border-top: 1px solid black; border-bottom: 1px solid black;" align=center><b>PATIENT INFORMATION</b></td>
	</tr>
	<tr>
		<td width=20%><b>CASE NO.</b></td>
		<td width=45%>:&nbsp;&nbsp;'.$lotno.'</td>
		<td width=15%><b>DATE</b></td>
		<td width=20%>:&nbsp;&nbsp;'.$_ihead['rdate'].'</td>
	</tr>
	<tr>
		<td><b>PATIENT NAME</b></td>
		<td>:&nbsp;&nbsp;'.$_ihead['pname'].'</td>
		<td><b>GENDER</b></td>
		<td>:&nbsp;&nbsp;'.$_ihead['gender'].'</td>
	</tr>
	<tr>
		<td><b>PATIENT ADDRESS</b></td>
		<td>:&nbsp;&nbsp;' . $myaddress . '</td>
		<td><b>AGE</b></td>
		<td>:&nbsp;&nbsp;'.$con->calculateAge($_ihead['result_date'],$_ihead['birthdate']).'</td>
	</tr>
	<tr>
		<td><b>COMPANY</b></td>
		<td>:&nbsp;&nbsp;' . $cname . '</td>
		<td><b>EXAMINATION</b></td>
		<td>:&nbsp;&nbsp;'.$_ihead['procedure'].'</td>
	</tr>
	<tr>
		<td width="100%" colspan=4 style="padding-top: 30px;" align=center>
			<span style="font-weight: bold; font-size: 14pt; color: #000000;">X-RAY REPORT</span>
		</td>
	</tr>
</table>

</htmlpageheader>

<htmlpagefooter name="myfooter">';

if($_ihead['conid'] != '142') {

$html .= '<table width=100% cellpadding=5 style="margin-bottom: 25px;">
		<tr>
			<td align=center valign=top>'.$medtechSignature.'<br/>'.$medtechFullname.'&nbsp;<br/>_______________________________________________________<br><b>'.$medtechRole.' - Lic. No. '.$medtechLicense.'</b></td>
			<td align=center valign=top>'.$consultantSignature.'<br/>'.$_ihead['consultant'].', '. $_ihead['prefix'] .'<br/>_________________________________________<br><b>'.$_ihead['specialization'].' - Lic. No. '.$_ihead['license_no'].'</b></td>
		</tr>
	</table>';
} else {
	if($_ihead['rstat'] == 'Y') {
		$html .= '<table width=100% cellpadding=5 style="margin-bottom: 25px;">
			<tr>
				<td align=center valign=top>'.$medtechSignature.'<br/>'.$medtechFullname.'&nbsp;<br/>_______________________________________________________<br><b>'.$medtechRole.' - Lic. No. '.$medtechLicense.'</b></td>
				<td align=center valign=top>'.$consultantSignature.'<br/>'.$_ihead['consultant'].', '. $_ihead['prefix'] .'<br/>_________________________________________<br><b>'.$_ihead['specialization'].' - Lic. No. '.$_ihead['license_no'].'</b></td>
			</tr>
		</table>';

	} else {
		$html .= '<table width=100% cellpadding=5 style="margin-bottom: 25px;">
			<tr>
				<td align=center valign=top width=50%></td>
				<td align=center valign=top>'.$consultantSignature.'<br/>'.$_ihead['consultant'].', '. $_ihead['prefix'] .'<br/>_________________________________________<br><b>'.$_ihead['specialization'].' - Lic. No. '.$_ihead['license_no'].'</b></td>
			</tr>
		</table>';

	}
}


$html .= '<table width=100%>
	<tr><td align=left><barcode size=0.8 code="'.$_ihead['trace_no'].'" type="C128A"></td><td align=right>Run Date: '.date('m/d/Y h:i:s a').'</td></tr>
</table>
</htmlpagefooter>

<sethtmlpageheader name="myheader" value="on" show-this-page="1" />
<sethtmlpagefooter name="myfooter" value="on" />
mpdf-->
<div id="main">'.$_ihead['impression'].'</div>
<div id="resultFooter" style="text-align: left; padding-top: 10px; font-style: italic;">Finding is based only on radiographic interpretation. Clinical correlation is suggested.</div>
</body>
</html>
';

$html = html_entity_decode($html);
$mpdf->WriteHTML($html);
$mpdf->Output(); exit;
exit;

mysql_close($con);
?>