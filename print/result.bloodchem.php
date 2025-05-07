<?php
	session_start();
	include("../lib/mpdf6/mpdf.php");
	include("../handlers/_generics.php");

	$con = new _init;

/* MYSQL QUERIES SECTION */
	$now = date("m/d/Y h:i a");
	$co = $con->getArray("select * from companies where company_id = '$_SESSION[company]';");

	$_ihead = $con->getArray("SELECT lpad(b.so_no,6,0) as myso, DATE_FORMAT(result_date,'%m/%d/%Y') AS rdate, b.patient_name, b.patient_address, IF(c.gender='M','Male','Female') AS gender, YEAR(so_date)-YEAR(c.birthdate) AS age, b.physician,d.patientstatus,a.serialno,a.created_by, b.trace_no FROM lab_bloodchem a LEFT JOIN so_header b ON a.so_no = b.so_no AND a.branch = b.branch LEFT JOIN patient_info c ON b.patient_id = c.patient_id left join options_patientstat d on b.patient_stat = d.id WHERE a.so_no = '$_REQUEST[so_no]' AND serialno = '$_REQUEST[serialno]' AND a.branch = '$_SESSION[branchid]';");
    $b = $con->getArray("SELECT * FROM lab_bloodchem WHERE so_no = '$_ihead[myso]' AND serialno = '$_ihead[serialno]' AND branch = '$_SESSION[branchid]';");
    //$con->dbquery("insert into traillog (branch,user_id,`timestamp`,ipaddress,module,`action`,doc_no) values ('$_SESSION[branchid]','$_SESSION[userid]',now(),'$_SERVER[REMOTE_ADDR]','SO','SALES ORDER # $_REQUEST[so_no] WAS PRINTED BY USER','$_REQUEST[so_no]');");
			
/* END OF SQL QUERIES */

function checkValue($val) {
    if($val > 0 || $val != '') {
        return "&#10003;";
    }
}


$mpdf=new mPDF('win-1252','letter','','',10,10,90,30,10,10);
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
            padding:20px;border:1px solid black;text-align: center;
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
		<td width="100%" style="padding-top: 30px;" align=center>
			<span style="font-weight: bold; font-size: 12pt; color: #000000;">LABORATORY DEPARTMENT</span>
		</td>
	</tr>

</table>
<table width=100% cellpadding=2 cellspacing=0 style="font-size: 9px;margin-top:20px;">
	<tr>
		<td width=100% colspan=4 style="background-color: #cdcdcd; border-top: 1px solid black; border-bottom: 1px solid black;" align=center><b>PATIENT INFORMATION</b></td>
	</tr>
	<tr>
		<td width=20%><b>SO NO.</b></td>
		<td width=30%>:&nbsp;&nbsp;'.$_ihead['myso'].'</td>
		<td width=20%><b>DATE</b></td>
		<td width=30%>:&nbsp;&nbsp;'.$_ihead['rdate'].'</td>
	</tr>
	<tr>
		<td><b>PATIENT NAME</b></td>
		<td>:&nbsp;&nbsp;'.$_ihead['patient_name'].'</td>
		<td><b>GENDER</b></td>
		<td>:&nbsp;&nbsp;'.$_ihead['gender'].'</td>
	</tr>
	<tr>
		<td><b>PATIENT ADDRESS</b></td>
		<td>:&nbsp;&nbsp;' . $_ihead['patient_address'] . '</td>
		<td><b>AGE</b></td>
		<td>:&nbsp;&nbsp;'.$_ihead['age'].'</td>
	</tr>
	<tr>
		<td><b>REQUESTING PHYSICIAN</b></td>
		<td>:&nbsp;&nbsp;'.$_ihead['physician'].'</td>
		<td><b>PATIENT STATUS</b></td>
		<td>:&nbsp;&nbsp;'.$_ihead['patientstatus'].'</td>
	</tr>
    <tr>
        <td width="100%" colspan=4 style="padding-top: 15px;" align=center>
         <span style="font-weight: bold; font-size: 12pt; color: #000000; text-decoration: underline;">&nbsp;&nbsp;&nbsp;BLOOD CHEMISTRY&nbsp;&nbsp;&nbsp;</span>
        </td>
    </tr>
</table>

</htmlpageheader>

<htmlpagefooter name="myfooter">
<table width=100% cellpadding=5 style="margin-top: 25px;">
	<tr>
		<td width=50% align=center><br>___________________________________________________<br/><b>MEDICAL TECHONOLOGIST</b></td>
		<td align=center><br/><span style="font-size: 10px;"><br/>___________________________________________________<br><b>PATHOLOGIST</b><br><span style="font-size: 7pt;">PRC LICENSE NO. 72410</span></td>
	</tr>
</table>
<table width=100%>
	<tr><td align=left><barcode size=0.8 code="'.$_ihead['trace_no'].'" type="C128A"></td><td align=right>Run Date: '.date('m/d/Y h:i:s a').'</td></tr>
</table>
</htmlpagefooter>

<sethtmlpageheader name="myheader" value="on" show-this-page="1" />
<sethtmlpagefooter name="myfooter" value="on" />
mpdf-->

<table width=90% cellpadding=0 cellspacing=5 align=center>
<tr>
    <td align="left" width=25%></td>
    <td align=center width=20%></td>
    <td align="left" colspan=2  style="padding-left: 15px;"><b>NORMAL VALUES</b></td>	
</tr>
<tr>
    <td align="left" width=25%  style="padding-left: 15px;">( ) Glucose/FBS</td>
    <td align=center width=20% style="border-bottom: 1px solid black;vertical-align: top;">'. $b['glucose'] . ' mg/dL</td>
    <td align="left" colspan=2  style="padding-left: 15px;">70 - 110 mg/dL</td>	
</tr>

<tr>
    <td align="left"  style="padding-left: 15px;" valign=top>( ) Uric Acid</td>
    <td align=center width=20% style="border-bottom: 1px solid black;vertical-align: top;">'. $b['uric'] . ' mg/dL</td>
    <td align="left" colspan=2 style="padding-left: 15px;" valign=top>3 - 6.5 mg/dL</td>	
</tr>

<tr>
    <td align="left" style="padding-left: 15px;">( ) Blood Urea Nitrogen (BUN)</td>
    <td align=center width=20% style="border-bottom: 1px solid black;">'. $b['bun'] . ' mg/dL</td>
    <td align="left" colspan=2 style="padding-left: 15px;">7 - 23.2 mg/dL</td>	
</tr>
<tr>
    <td align="left"  style="padding-left: 15px;">( ) Creatinine</td>
    <td align=center width=20% style="border-bottom: 1px solid black;">'. $b['creatinine'] . ' mg/dL</td>
    <td align="left" colspan=2 style="padding-left: 15px;">0.6 - 1.3 mg/dL</td>	
</tr>
<tr>
    <td align="left"  style="padding-left: 15px;">( ) Total Cholesterol</td>
    <td align=center width=20% style="border-bottom: 1px solid black;">'. $b['cholesterol'] . ' mg/dL</td>
    <td align="left" colspan=2 style="padding-left: 15px;">120 - 200 mg/dL</td>	
</tr>
<tr>
    <td align="left"  style="padding-left: 15px;">( ) Triglycerides</td>
    <td align=center width=20% style="border-bottom: 1px solid black;">'. $b['triglycerides'] . ' mg/dL</td>
    <td align="left" colspan=2 style="padding-left: 15px;"><b>F:</b> 0 - 135 mg/dL&nbsp;&nbsp;&nbsp;&nbsp;<b>M:</b> 0 - 160 mg/dL</td>	
</tr>
<tr>
    <td align="left"  style="padding-left: 15px;">( ) HDL - Chol</td>
    <td align=center width=20% style="border-bottom: 1px solid black;">'. $b['hdl'] . ' mg/dL</td>
    <td align="left" colspan=2 style="padding-left: 15px;">35 - 60 mg/dL</td>	
</tr>
<tr>
    <td align="left"  style="padding-left: 15px;">( ) LDL - Chol</td>
    <td align=center width=20% style="border-bottom: 1px solid black;">'. $b['ldl'] . ' mg/dL</td>
    <td align="left" colspan=2 style="padding-left: 15px;">70 - 180 mg/dL</td>	
</tr>
<tr>
    <td align="left"  style="padding-left: 15px;">( ) VLDL</td>
    <td align=center width=20% style="border-bottom: 1px solid black;">'. $b['vldl'] . ' mg/dL</td>
    <td align="left" colspan=2 style="padding-left: 15px;">0 - 40 mg/dL</td>	
</tr>
<tr><td height=5>&nbsp;</td></tr>
<tr>
    <td align="left" colspan=3  style="padding-left: 15px;"><b>Enzymes&nbsp;:</b></td>
</tr>
<tr>
    <td align="left"  style="padding-left: 35px;">SGOT/AST&nbsp;:</td>
    <td align=center width=20% style="border-bottom: 1px solid black;">'. $b['sgot'] . ' U/L</td>
    <td align="left" colspan=2 style="padding-left: 15px;">6 - 38 U/L</td>	
</tr>
<tr>
    <td align="left"  style="padding-left: 35px;">SGPT&nbsp;:</td>
    <td align=center width=20% style="border-bottom: 1px solid black;">'. $b['sgpt'] . ' U/L</td>
    <td align="left" colspan=2 style="padding-left: 15px;">0 - 35 U/L</td>	
</tr>
<tr>
    <td align="left"  style="padding-left: 35px;">Alkaline Phosphate&nbsp;:</td>
    <td align=center width=20% style="border-bottom: 1px solid black;">'. $b['alkaline'] . ' U/L</td>
    <td align="left" colspan=2 style="padding-left: 15px;">100 - 290 U/L</td>	
</tr>
<tr>
    <td align="left"  style="padding-left: 15px;">( ) Total Bilirubin</td>
    <td align=center width=20% style="border-bottom: 1px solid black;">'. $b['bilirubin'] . ' mg/dL</td>
    <td align="left" colspan=2 style="padding-left: 15px;">0.1 - 1.2 mg/dL</td>	
</tr>
<tr>
    <td align="left"  style="padding-left: 35px;">Direct Bilirubin&nbsp;:</td>
    <td align=center width=20% style="border-bottom: 1px solid black;">'. $b['bilirubin_direct'] . ' mg/dL</td>
    <td align="left" colspan=2 style="padding-left: 15px;">0 - 0.3 mg/dL</td>	
</tr>
<tr>
    <td align="left"  style="padding-left: 35px;">Indirect Bilirubin&nbsp;:</td>
    <td align=center width=20% style="border-bottom: 1px solid black;">'. $b['bilirubin_indirect'] . ' mg/dL</td>
    <td align="left" colspan=2 style="padding-left: 15px;">0.1 - 1.0 mg/dL</td>	
</tr>
<tr>
    <td align="left"  style="padding-left: 15px;">( ) Total Protein</td>
    <td align=center width=20% style="border-bottom: 1px solid black;">'. $b['protein'] . ' mg/dL</td>
    <td align="left" colspan=2 style="padding-left: 15px;">0.1 - 1.2 mg/dL</td>	
</tr>
<tr>
    <td align="left"  style="padding-left: 35px;">Albumin&nbsp;:</td>
    <td align=center width=20% style="border-bottom: 1px solid black;">'. $b['albumin'] . ' g/dL</td>
    <td align="left" colspan=2 style="padding-left: 15px;">3.8 - 5.1 g/dL</td>	
</tr>
<tr>
    <td align="left"  style="padding-left: 35px;">Glubolin&nbsp;:</td>
    <td align=center width=20% style="border-bottom: 1px solid black;">'. $b['globulin'] . ' g/dL</td>
    <td align="left" colspan=2 style="padding-left: 15px;">2.8 - 3.2 g/dL</td>	
</tr>
<tr>
    <td align="left"  style="padding-left: 35px;">A/G Ratio&nbsp;:</td>
    <td align=center width=20% style="border-bottom: 1px solid black;">'. $b['agratio'] . '</td>
    <td align="left" colspan=2 style="padding-left: 15px;">1.1 - 1.8</td>	
</tr>
<tr>
    <td align="left" colspan=3  style="padding-left: 15px;"><b>Electrolytes&nbsp;:</b></td>
</tr>
<tr>
    <td align="left"  style="padding-left: 35px;">Na&nbsp;:</td>
    <td align=center width=20% style="border-bottom: 1px solid black;">'. $b['electrolytes_na'] . ' mmol/L</td>
    <td align="left" colspan=2 style="padding-left: 15px;">135 - 143 mmol/L</td>	
</tr>
<tr>
    <td align="left"  style="padding-left: 35px;">K&nbsp;:</td>
    <td align=center width=20% style="border-bottom: 1px solid black;">'. $b['electrolytes_k'] . ' mmol/L</td>
    <td align="left" colspan=2 style="padding-left: 15px;">3.5 - 5.3 mmol/L</td>	
</tr>
<tr>
    <td align="left"  style="padding-left: 35px;">CI&nbsp;:</td>
    <td align=center width=20% style="border-bottom: 1px solid black;">'. $b['electrolytes_ci'] . ' mmol/L</td>
    <td align="left" colspan=2 style="padding-left: 15px;">95 - 107 mmol/L</td>	
</tr>
<tr>
    <td align="left" width=25%  style="padding-left: 15px;">Calcium&nbsp;:</td>
    <td align=center width=20% style="border-bottom: 1px solid black;">'. $b['calcium'] . ' mg/dL</td>
    <td align="left" colspan=2 style="padding-left: 15px;">8.6 - 10.3 mg/dL</td>	
</tr>
<tr>
    <td align="left" width=25%  style="padding-left: 15px;">Phosphorus&nbsp;:</td>
    <td align=center width=20% style="border-bottom: 1px solid black;">'. $b['phosphorus'] . ' mg/dL</td>
    <td align="left" colspan=2 style="padding-left: 15px;">2.7 - 4.5 mg/dL</td>	
</tr>
<tr>
    <td align="left" width=25%  style="padding-left: 15px;">GGT&nbsp;:</td>
    <td align=center width=20% style="border-bottom: 1px solid black;">'. $b['ggt'] . ' U/L</td>
    <td align="left" colspan=2 style="padding-left: 15px;">5 - 45 U/L</td>	
</tr>
<tr>
    <td align="left" width=25%  style="padding-left: 15px;" valign=top>NOTE&nbsp;:</td>
    <td align=left colspan=2 style="border-bottom: 1px solid black;">'. $b['remarks'] . '</td>
    <td width=25%></td>
</tr>
</table>

</body>
</html>
';

$html = html_entity_decode($html);
$mpdf->WriteHTML($html);
$mpdf->Output(); exit;
exit;

mysql_close($con);
?>