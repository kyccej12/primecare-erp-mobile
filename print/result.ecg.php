<?php
	session_start();
	include("../lib/mpdf6/mpdf.php");
	include("../handlers/_generics.php");

	$con = new _init;

/* MYSQL QUERIES SECTION */
	$now = date("m/d/Y h:i a");
	$co = $con->getArray("select * from companies where company_id = '1';");

	$_ihead = $con->getArray("SELECT record_id AS id, LPAD(a.so_no,6,0) AS myso,DATE_FORMAT(a.so_date,'%m/%d/%Y') AS sodate, a.so_date, b.birthdate, LPAD(a.pid,6,0) AS mypid,CONCAT(b.lname,', ',b.fname,' ',b.mname) AS pname, FLOOR(DATEDIFF(a.so_date,b.birthdate)/365.25) AS age, IF(b.gender='M','Male','Female') AS gender, DATE_FORMAT(b.birthdate,'%m/%d/%Y') AS bday,a.code,a.procedure,sampletype,serialno,DATE_FORMAT(extractdate,'%m/%d/%Y') AS exday,TIME_FORMAT(extractime,'%h:%i %p') AS etime,extractby,a.location, b.street, b.brgy, b.city, b.province,b.employer,a.lotno FROM lab_samples a  LEFT JOIN pccmain.patient_info b ON a.pid = b.patient_id WHERE a.so_no= '$_REQUEST[so_no]' and  a.code = '$_REQUEST[code]' and a.serialno = '$_REQUEST[serialno]';");
    $b = $con->getArray("SELECT *,verified_by,consultant,date_format(result_date,'%m/%d/%Y') as rdate FROM lab_ecgresult WHERE serialno = '$_ihead[serialno]';");
    
    if($b['verified_by'] != '') {
        list($encoderSignature,$encoderFullname,$encoderLicense,$encoderRole) = $con->getArray("SELECT if(signature_file != '',concat('<img src=\"../images/signatures/',signature_file,'\" align=absmiddle />'),'<img src=\"../images/signatures/blank.png\" align=absmiddle />') as signature, fullname, license_no, role from user_info where emp_id = '$b[verified_by]';");
    }

	list($cname,$myaddress,$soDate,$resultDate) = $con->getArray("select if(company = '',customer_name,company), if(company = '',customer_address,location), cso_date, date_format(until,'%d %b %Y') from cso_header where cso_no = '$_REQUEST[so_no]';");


    list($traceno) = $con->getArray("select trace_no from cso_header where cso_no= '$_ihead[myso]';");

	list($physician,$serialno) = $con->getArray("select physician, serialno from lab_samples where serialno = '$b[serialno]';");
	if($_ihead['lot_no'] == '') { $lotno = $b['serialno']; }
	
	list($file) = $con->getArray("select CONCAT('../',file_path) from lab_samples where so_no = '$b[so_no]' and `code` = '$b[code]' and serialno = '$b[serialno]';");

    if($b['consultant'] != '') {
        list($consultantSignature,$consultantFullname,$consultantPrefix,$consultantRole) = $con->getArray("SELECT IF(signature_file != '',CONCAT('<img src=\"../images/signatures/',signature_file,'\" align=absmiddle />'),'<img src=\"../images/signatures/blank.png\" align=absmiddle />') AS signature, fullname, prefix, specialization FROM options_doctors WHERE id = '$b[consultant]';");
    }


	list($brgy) = $con->getArray("SELECT brgyDesc FROM options_brgy WHERE brgyCode = '$_ihead[brgy]';");
    list($ct) = $con->getArray("SELECT citymunDesc FROM options_cities WHERE cityMunCode = '$_ihead[city]';");
    list($prov) = $con->getArray("SELECT provDesc FROM options_provinces WHERE provCode = '$_ihead[province]';");

    if($_ihead['street'] != '') { $myaddress.=$_ihead['street'].", "; }
    if($brgy != "") { $myaddress .= $brgy.", "; }
    if($ct != "") { $myaddress .= $ct.", "; }
    if($prov != "")  { $myaddress .= $prov.", "; }
    $myaddress = substr($myaddress,0,-2);

/* END OF SQL QUERIES */

$mpdf=new mPDF('win-1252','LETTER','','',10,10,80,30,10,10);
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
		body {font-family: arial; font-size: 10pt; }
	</style>
</head>
<body>

<!--mpdf
<htmlpageheader name="myheader">
<table width="100%" cellpadding=0 cellspaing=0>
	<tr><td align=center><img src="../images/doc-header.jpg" /></td></tr>
</table>
<table width=100% cellpadding=2 cellspacing=0 style="font-size:8pt;margin-top:10px;">
	<tr>
		<td width=100% colspan=4 style="background-color: #cdcdcd; border-top: 1px solid black; border-bottom: 1px solid black;" align=center><b>PATIENT INFORMATION</b></td>
	</tr>
	<tr>
		<td width=20%><b>ECG NO.</b></td>
		<td width=35%>:&nbsp;&nbsp;'.$serialno.'</td>
		<td width=20%><b>DATE</b></td>
		<td width=25%>:&nbsp;&nbsp;'.$b['rdate'].'</td>
	</tr>
	<tr>
		<td><b>PATIENT NAME</b></td>
		<td>:&nbsp;&nbsp;'.$_ihead['pname'].'</td>
		<td><b>GENDER</b></td>
		<td>:&nbsp;&nbsp;'.$_ihead['gender'].'</td>
	</tr>
	<tr>
		<td><b>ADDRESS</b></td>
		<td>:&nbsp;&nbsp;' . $myaddress . '</td>
		<td><b>AGE</b></td>
		<td>:&nbsp;&nbsp;'.$_ihead['age'].'yo</td>
	</tr>
	<tr>
		<td><b>REQUESTING PHYSICIAN</b></td>
		<td>:&nbsp;&nbsp;'.$physician.'</td>
		<td><b>EXAMINATION</b></td>
		<td>:&nbsp;&nbsp;'.$b['procedure'].'</td>
	</tr>
	<tr>
		<td><b>EMPLOYER</b></td>
		<td>:&nbsp;&nbsp;'.$cname.'</td>
		<td><b>PATIENT STATUS</b></td>
		<td>:&nbsp;&nbsp;'.$b['patient_stat'].'</td>
	</tr>
	<tr>
		<td><b>PREVIOUS ECG DATE</b></td>
		<td>:&nbsp;&nbsp;'.$b['ecg_prev_date'].'</td>
	</tr>
	<tr>
		<td width=100% colspan=3 align=left><b>SIGNIFICANT MEDICATION</b>&nbsp;:&nbsp;&nbsp;'.$a['medication'].'</td>
	</tr>
	<tr>
		<td height=5></td>
	</tr>
	<tr>
		<td width="100%" colspan=4 align=center style="padding-top:2px;">
			<span style="font-weight: bold; font-size: 14pt; color: #000000;">ELECTROCARDIOGRAPHIC REPORT</span>
		</td>
	</tr>
</table>

</htmlpageheader>

<htmlpagefooter name="myfooter">
<table width=100% cellpadding=5>
	<tr>
		<td width=50% align=center valign=top><br/><br><br/><b></b></td>
		<td align=center valign=top>'.$consultantSignature.'<br/>'.$consultantFullname.', '. $consultantPrefix .'<br/>___________________________________________<br><b>'.$consultantRole.'<br/></b></td>
	</tr>
	</table>
<table width=100% style="font-size: 8pt;">
	<tr><td align=left><barcode size=0.8 code="'. substr($traceno,0,10) .'" type="C128A"></td><td align=right>Run Date: '.date('m/d/Y h:i:s a').'</td></tr>
</table>
</htmlpagefooter>

<sethtmlpageheader name="myheader" value="on" show-this-page="1" />
<sethtmlpagefooter name="myfooter" value="on" />
mpdf-->
	<table width="100%" cellpadding=0 cellspaing=0>
		<tr><td align=center><img style="height:90%" src='.$file.' /></td></tr>
	</table>
	<table width=100% cellpadding=2 cellspacing=0 style="font-size:8pt;">
		<tr>
			<td width=100% colspan=4 style="background-color: #cdcdcd; border-top: 1px solid black; border-bottom: 1px solid black; font-size: 16px;" align=center><b>ECG DIAGNOSIS</b></td>
		</tr>
	</table>
<div id="main" style="text-align: justify;padding-top:10px; font-size: 20px;">'.$b['impression'].'</div>
</body>
</html>
';

$html = html_entity_decode($html);
$mpdf->WriteHTML($html);
$mpdf->Output(); exit;
exit;

mysql_close($con);
?>