<?php
	session_start();
	include("../lib/mpdf6/mpdf.php");
	include("../handlers/_generics.php");

	$con = new _init;

/* MYSQL QUERIES SECTION */
	$now = date("m/d/Y h:i a");
	$co = $con->getArray("select * from companies where company_id = '$_SESSION[company]';");

	$_ihead = $con->getArray("SELECT LPAD(a.so_no,6,0) AS myso, pid, DATE_FORMAT(result_date,'%m/%d/%Y') AS rdate, CONCAT(b.lname,', ',b.fname,' ',b.mname) AS pname, b.street, b.brgy, b.city, b.province, IF(b.gender='M','Male','Female') AS gender, a.result_date, b.birthdate,b.employer, a.serialno, a.created_by FROM lab_lipidpanel a LEFT JOIN pccmain.patient_info b ON a.pid = b.patient_id WHERE a.so_no = '$_REQUEST[so_no]' AND serialno = '$_REQUEST[serialno]';");
	$b = $con->getArray("select * from lab_lipidpanel where serialno = '$_ihead[serialno]';");
	$c = $con->getArray("select trace_no from cso_details where pid = '$_ihead[pid]';");

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

/* END OF SQL QUERIES */

$mpdf=new mPDF('win-1252','FOLIO-H','','',10,10,75,30,5,5);
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
		body {font-family: sans-serif; font-size: 10px; }
        .itemHeader {
            padding:5px;border:1px solid black; text-align: center; font-weight: bold;
        }

        .itemResult {
            padding:5px;border:1px solid black;text-align: center;
        }

        #items td { border: 1px solid; text-align: center; }
	</style>
</head>
<body>

<!--mpdf
<htmlpageheader name="myheader">
<table width="100%" cellpadding=0 cellspading=0>
	<tr><td align=center><img src="../images/prime-care-medgruppe.png" /></td></tr>

    <tr>
		<td width="100%" style="padding-top: 20px;" align=center>
			<span style="font-weight: bold; font-size: 12pt; color: #000000;">LABORATORY DEPARTMENT</span>
		</td>
	</tr>

</table>
<table width=100% cellpadding=2 cellspacing=0 style="font-size: 10pt;">
	<tr>
		<td width=100% colspan=4 style="background-color: #cdcdcd; border-top: 1px solid black; border-bottom: 1px solid black;" align=center><b>PATIENT INFORMATION</b></td>
	</tr>
	<tr>
		<td width=25%><b>CASE NO.</b></td>
		<td width=40%>:&nbsp;&nbsp;'.$_ihead['serialno'].'</td>
		<td width=20%><b>DATE</b></td>
		<td width=15%>:&nbsp;&nbsp;'.$_ihead['rdate'].'</td>
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
		<td>:&nbsp;&nbsp;'.$con->calculateAge($_ihead['result_date'],$_ihead['birthdate']).'yo</td>
	</tr>
	<tr>
		<td><b>COMPANY NAME</b></td>
		<td>:&nbsp;&nbsp;'.$_ihead['employer'].'</td>
		<td><b>EXAMINATION</b></td>
		<td>:&nbsp;&nbsp;Lipid Panel</td>
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
	<tr><td align=left><barcode size=0.8 code="'.$c['trace_no'].'" type="C128A"></td><td align=right>Run Date: '.date('m/d/Y h:i:s a').'</td></tr>
</table>
</htmlpagefooter>

<sethtmlpageheader name="myheader" value="on" show-this-page="1" />
<sethtmlpagefooter name="myfooter" value="on" />
mpdf-->
<div id="main">
    <table width=80% cellpadding=0 cellspacing=0 align=center style="border-collapse: collapse;">

            <tr>
                <td class="itemHeader">TEST</td>
                <td class="itemHeader">RESULT</td>
				<td class="itemHeader">UNIT</td>
                <td class="itemHeader">NORMAL REFERENCE</td>
            </tr>
            <tr>
                <td class="itemResult">TOTAL CHOLESTEROL</td>
                <td class="itemResult">'.$b['cholesterol'].'</td>
				<td class="itemResult">mg/dL</td>
                <td class="itemResult">'.$con->getAttribute('L019',$_ihead['birthdate'],$_ihead['gender']).'</td>
            </tr>
			<tr>
				<td class="itemResult">TRIGLYCERIDES</td>
				<td class="itemResult">'.$b['triglycerides'].'</td>
				<td class="itemResult">mg/dL</td>
				<td class="itemResult">'.$con->getAttribute('L032',$_ihead['birthdate'],$_ihead['gender']).'</td>
			</tr>
			<tr>
				<td class="itemResult">HDL</td>
				<td class="itemResult">'.$b['hdl'].'</td>
				<td class="itemResult">mg/dL</td>
				<td class="itemResult">'.$con->getAttribute('L018',$_ihead['birthdate'],$_ihead['gender']).'</td>
			</tr>
			<tr>
				<td class="itemResult">LDL</td>
				<td class="itemResult">'.$b['ldl'].'</td>
				<td class="itemResult">mg/dL</td>
				<td class="itemResult">80 - 200 mg/dL</td>
			</tr>
			<tr>
				<td class="itemResult">VLDL</td>
				<td class="itemResult">'.$b['vldl'].'</td>
				<td class="itemResult">mg/dL</td>
				<td class="itemResult">5 - 40 mg/dL</td>
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