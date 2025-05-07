<?php
	session_start();
	include("../lib/mpdf6/mpdf.php");
	include("../handlers/_generics.php");

	$con = new _init;

/* MYSQL QUERIES SECTION */
	$now = date("m/d/Y h:i a");
	$co = $con->getArray("select * from companies where company_id = '$_SESSION[company]';");

	//$_ihead = $con->getArray("SELECT LPAD(a.so_no,6,0) AS myso, LPAD(a.pid,6,0) AS pid, DATE_FORMAT(result_date,'%m/%d/%Y') AS rdate, CONCAT(b.lname,', ',b.fname,' ',b.mname) AS pname,FLOOR(DATEDIFF(a.result_date,b.birthdate)/364.25) AS age, b.street, b.brgy, b.city, b.province, IF(b.gender='M','Male','Female') AS gender, a.result_date, b.birthdate,b.employer, a.serialno,a.result, a.rh, a.created_by, a.code, a.remarks,verified,verified_by FROM lab_bloodtyping a LEFT JOIN patient_info b ON a.pid = b.patient_id WHERE a.so_no = '$_REQUEST[so_no]' AND serialno = '$_REQUEST[serialno]';");
	$_ihead = $con->getArray("SELECT a.serialno,LPAD(a.pid,6,0) AS pid, a.pname, b.customer_name AS company, b.customer_address, DATE_FORMAT(a.result_date, '%m/%d/%Y') AS rdate, c.street, c.brgy, c.city, c.province,FLOOR(DATEDIFF(b.cso_date,c.birthdate)/364.25) AS age, IF(c.gender='M','Male','Female') AS gender, c.birthdate, a.created_by, b.trace_no, a.code,a.result,a.rh, a.remarks, verified,verified_by FROM lab_bloodtyping a LEFT JOIN cso_header b ON a.so_no = b.cso_no LEFT JOIN patient_info c ON c.patient_id = a.pid WHERE a.so_no = '$_REQUEST[so_no]' AND serialno = '$_REQUEST[serialno]';");
	$b = $con->getArray("SELECT trace_no FROM cso_details WHERE pid= '$_ihead[pid]';");

	if($_ihead['verified_by'] != '') {
        list($medtechSignature,$medtechFullname,$medtechLicense,$medtechRole) = $con->getArray("SELECT if(signature_file != '',concat('<img src=\"../images/signatures/',signature_file,'\" align=absmiddle />'),'<img src=\"../images/signatures/blank.png\" align=absmiddle />') as signature, fullname, license_no, role from user_info where emp_id = '$_ihead[verified_by]';");
    }

   // list($procedure) = $con->getArray("SELECT `description` FROM services_master WHERE `code` = '$_REQUEST[code]';");

/* END OF SQL QUERIES */

$mpdf=new mPDF('win-1252','FOLIO-H','','',5,5,75,15,5,5);
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
		body {font-family: sans-serif; font-size: 11pt; }
        .itemHeader {
            padding:5px;border:1px solid black; text-align: center; font-weight: bold;
        }

        .itemResult {
            padding:10px;border:1px solid black;text-align: center;
        }

        #items td { border: 1px solid; text-align: center; }
	</style>
</head>
<body>

<!--mpdf
<htmlpageheader name="myheader">
<table width="100%" cellpadding=0 cellspaing=0>
	<tr><td align=center><img src="../images/prime-care-medgruppe.png" /></td></tr>

    <tr>
		<td width="100%" style="padding-top: 5px;" align=center>
			<span style="font-weight: bold; font-size: 12pt; color: #000000;">LABORATORY DEPARTMENT</span>
		</td>
	</tr>

</table>
<table width=100% cellpadding=2 cellspacing=0 style="font-size: 10pt;margin-top:5px;">
	<tr>
		<td width=100% colspan=4 style="background-color: #cdcdcd; border-top: 1px solid black; border-bottom: 1px solid black;" align=center><b>PATIENT INFORMATION</b></td>
	</tr>
	<tr>
		<td width=25%><b>CASE NO.</b></td>
		<td width=40%>:&nbsp;&nbsp;'.$_ihead['serialno'].'</td>
		<td width=25%><b>DATE</b></td>
		<td width=15%>:&nbsp;&nbsp;'.$_ihead['rdate'].'</td>
	</tr>
	<tr>
		<td><b>PATIENT NAME</b></td>
		<td>:&nbsp;&nbsp;'.$_ihead['pname'].'</td>
		<td><b>GENDER</b></td>
		<td>:&nbsp;&nbsp;'.$_ihead['gender'].'</td>
	</tr>
	<tr>
		<td><b>COMPANY NAME</b></td>
		<td>:&nbsp;&nbsp;' . $_ihead['company'] . '</td>
		<td><b>AGE</b></td>
		<td>:&nbsp;&nbsp;'.$_ihead['age'].'yo</td>
	</tr>
	<tr>
		<td><b>COMPANY ADDRESS</b></td>
		<td>:&nbsp;&nbsp;'.$_ihead['customer_address'].'</td>
		<td></td>
		<td></td>
	</tr>
</table>

</htmlpageheader>

<htmlpagefooter name="myfooter">
<table width=100% cellpadding=5>
		<tr>
			<td align=center valign=top>'.$medtechSignature.'<br/><b>'.$medtechFullname.'<br/>___________________________________________<br>'.$medtechRole.'<br/>License No. '.$medtechLicense.'</b></td>
			<td align=center valign=top><img src="../images/signatures/psa-signature.png" align=absmidddle /><br/><b>PETER S. AZNAR, M.D, F.P.S.P<br/>___________________________________________<br><b>PATHOLOGIST</b><br><span style="font-size: 7pt;">PRC LICENSE NO. 72410</span></td>
			</tr>
    <tr><td align=left><barcode size=0.8 code="'.substr($b['trace_no'],0,10).'" type="C128A"></td><td align=right colspan=2>Date & Time Printed : '.date('m/d/Y h:i:s a').'</td></tr>
</table>
</htmlpagefooter>

<sethtmlpageheader name="myheader" value="on" show-this-page="1" />
<sethtmlpagefooter name="myfooter" value="on" />
mpdf-->
<div id="main">
    <table width=60% cellpadding=0 cellspacing=0 align=center style="margin: 5px;">
        <tr><td align=center><span style="font-size: 12pt; font-weight: bold;">BLOOD TYPING (FORWARD TYPING)</span></td></tr>
    </table>
    <table width=60% cellpadding=0 cellspacing=0 align=center style="border:1px solid black; padding: 10px;">
        <tr><td width=100% align=center><span style="font-size: 14pt; font-weight: bold; font-style: italic;">BLOOD TYPE: '.$_ihead['result'].' (Rh '.$_ihead['rh'].')</span></td></tr>
    </table>
    <table width=60% align=center style="margin-top: 5px; font-size: 9pt; font-style: italic;">
        <tr>
            <td align=left width=18%><b>REMARKS :</b></td>
            <td align=left width=82% style="border-bottom: 1px solid black;">'.$_ihead['remarks'].'</td>
        </tr>
    </table>
</div>
</body>
</html>
';

$html = html_entity_decode($html);
$mpdf->WriteHTML($html);
$mpdf->Output(); exit;
exit;

mysql_close($con);
?>