<?php
	session_start();
	include("../lib/mpdf6/mpdf.php");
	include("../handlers/_generics.php");

	$con = new _init;

/* MYSQL QUERIES SECTION */
	$now = date("m/d/Y h:i a");
	$co = $con->getArray("select * from companies where company_id = '$_SESSION[company]';");

	$_ihead = $con->getArray("SELECT record_id AS id, LPAD(a.so_no,6,0) AS myso,DATE_FORMAT(a.so_date,'%m/%d/%Y') AS sodate, a.so_date, b.birthdate, FLOOR(DATEDIFF(a.so_date,b.birthdate)/364.25) AS age, LPAD(a.pid,6,0) AS mypid,CONCAT(b.lname,', ',b.fname,' ',b.mname) AS pname,IF(b.gender='M','Male','Female') AS gender, DATE_FORMAT(b.birthdate,'%m/%d/%Y') AS bday,a.code,a.procedure,sampletype,serialno,DATE_FORMAT(extractdate,'%m/%d/%Y') AS exday,TIME_FORMAT(extractime,'%h:%i %p') AS etime,extractby,a.location, b.street, b.brgy, b.city, b.province,b.employer FROM lab_samples a  LEFT JOIN pccmain.patient_info b ON a.pid = b.patient_id WHERE a.code = '$_REQUEST[code]' AND a.serialno = '$_REQUEST[serialno]';");
    $b = $con->getArray("SELECT *,date_format(result_date,'%m/%d/%Y') as rdate,verified_by,verified_on FROM lab_enumresult WHERE serialno = '$_REQUEST[serialno]';");

	if($b['verified_by'] != '') {
        list($medtechSignature,$medtechFullname,$medtechLicense,$medtechRole) = $con->getArray("SELECT if(signature_file != '',concat('<img src=\"../images/signatures/',signature_file,'\" align=absmiddle />'),'<img src=\"../images/signatures/blank.png\" align=absmiddle />') as signature, fullname, license_no, role from user_info where emp_id = '$b[verified_by]';");
    }
    
	list($brgy) = $con->getArray("SELECT brgyDesc FROM options_brgy WHERE brgyCode = '$_ihead[brgy]';");
    list($ct) = $con->getArray("SELECT citymunDesc FROM options_cities WHERE cityMunCode = '$_ihead[city]';");
    list($prov) = $con->getArray("SELECT provDesc FROM options_provinces WHERE provCode = '$_ihead[province]';");

    if($_ihead['street'] != '') { $myaddress.=$_ihead['street'].", "; }
    if($brgy != "") { $myaddress .= $brgy.", "; }
    if($ct != "") { $myaddress .= $ct.", "; }
    if($prov != "")  { $myaddress .= $prov.", "; }
    $myaddress = substr($myaddress,0,-2);

    list($procedure) = $con->getArray("SELECT `description` FROM pccmain.services_master WHERE `code` = '$_REQUEST[code]';");
	list($traceno) = $con->getArray("select trace_no from cso_details where pid = '$_ihead[mypid]' and cso_no = '$_ihead[myso]';");

/* END OF SQL QUERIES */

$mpdf=new mPDF('win-1252','FOLIO-H','','',5,5,75,5,5,5);
$mpdf->use_embeddedfonts_1252 = true;    // false is default
$mpdf->SetProtection(array('print'));
$mpdf->SetAuthor("PORT80 Solutions");

if($b['verified'] != 'Y') {
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
		<td width=45%>:&nbsp;&nbsp;'.$_ihead['serialno'].'</td>
		<td width=15%><b>DATE</b></td>
		<td width=15%>:&nbsp;&nbsp;'.$b['rdate'].'</td>
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
		<td>:&nbsp;&nbsp;'.$_ihead['age'].'yo</td>
	</tr>
	<tr>
		<td><b>COMPANY</b></td>
		<td>:&nbsp;&nbsp;'.$_ihead['employer'].'</td>
		<td></td>
		<td></td>
	</tr>
</table>

</htmlpageheader>

<htmlpagefooter name="myfooter">
<table width=100% cellpadding=5>
	<tr>
		<td align=center valign=top>'.$medtechSignature.'<br/><b>'.$medtechFullname.'&nbsp;<br/>____________________________________________________________<br>'.$medtechRole.' - License No. '.$medtechLicense.'</b></td>
        <td align=center valign=top><img src="../images/signatures/psa-signature.png" align=absmidddle /><br/><b>PETER S. AZNAR, M.D, F.P.S.P<br/>___________________________________________<br><b>PATHOLOGIST</b><br><span style="font-size: 7pt;">PRC LICENSE NO. 72410</span></td>
	</tr>
    <tr><td align=left><barcode size=0.8 code="'.$traceno.'" type="C128A"></td><td align=right>Date & Time Printed : '.date('m/d/Y h:i:s a').'</td></tr>
</table>
</htmlpagefooter>

<sethtmlpageheader name="myheader" value="on" show-this-page="1" />
<sethtmlpagefooter name="myfooter" value="on" />
mpdf-->
<div id="main">
    <table width=60% cellpadding=0 cellspacing=0 align=center style="margin: 5px;">
        <tr><td align=center><span style="font-size: 12pt; font-weight: bold;">'.$procedure.'</span></td></tr>
    </table>
    <table width=60% cellpadding=0 cellspacing=0 align=center style="border:1px solid black; padding: 10px;">
        <tr><td width=100% align=center><span style="font-size: 14pt; font-weight: bold; font-style: italic;">'.$b['result'].'</span></td></tr>
    </table>
    <table width=60% align=center style="margin-top: 5px; font-size: 9pt; font-style: italic;">
        <tr>
            <td align=left width=18%><b>REMARKS :</b></td>
            <td align=left width=82% style="border-bottom: 1px solid black;">'.$b['remarks'].'</td>
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