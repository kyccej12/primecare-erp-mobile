<?php
	session_start();
	include("../lib/mpdf6/mpdf.php");
	include("../handlers/_generics.php");

	$con = new _init;

/* MYSQL QUERIES SECTION */
	$now = date("m/d/Y h:i a");
	$co = $con->getArray("select * from companies where company_id = '$_SESSION[company]';");

	$_ihead = $con->getArray("SELECT a.serialno, a.pname, b.customer_name AS company, b.customer_address, DATE_FORMAT(a.result_date, '%m/%d/%Y') AS rdate, c.street, c.brgy, c.city, c.province,FLOOR(DATEDIFF(b.cso_date,c.birthdate)/364.25) AS age, IF(c.gender='M','Male','Female') AS gender, c.birthdate, a.result_date as so_date, a.created_by, a.procedure, b.trace_no, a.code, a.remarks, a.pid FROM lab_singleresult a LEFT JOIN cso_header b ON a.so_no = b.cso_no LEFT JOIN pccmain.patient_info c ON c.patient_id = a.pid WHERE a.so_no = '$_REQUEST[so_no]' and `code` = '$_REQUEST[code]' and serialno = '$_REQUEST[serialno]';");
    $b = $con->getArray("SELECT attribute, concat(`value`,unit) as val, verified, verified_by FROM lab_singleresult WHERE so_no = '$_REQUEST[so_no]' and `code` = '$_REQUEST[code]' and serialno = '$_REQUEST[serialno]';");	
	$c = $con->getArray("SELECT CONCAT(min_value,' - ',`max_value`,`unit`) as limits FROM lab_testvalues WHERE `code` = '$_ihead[code]';");		






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
	
	if($b['verified_by'] != '') {
        list($medtechSignature,$medtechFullname,$medtechLicense,$medtechRole) = $con->getArray("SELECT if(signature_file != '',concat('<img src=\"../images/signatures/',signature_file,'\" align=absmiddle />'),'<img src=\"../images/signatures/blank.png\" align=absmiddle />') as signature, fullname, license_no, role from user_info where emp_id = '$b[verified_by]';");
    }

	// list($brgy) = $o->getArray("SELECT brgyDesc FROM options_brgy WHERE brgyCode = '$_ihead[brgy]';");
    // list($ct) = $o->getArray("SELECT citymunDesc FROM options_cities WHERE cityMunCode = '$_ihead[city]';");
    // list($prov) = $o->getArray("SELECT provDesc FROM options_provinces WHERE provCode = '$_ihead[province]';");

    // if($_ihead['street'] != '') { $myaddress.=$_ihead['street'].", "; }
    // if($brgy != "") { $myaddress .= $brgy.", "; }
    // if($ct != "") { $myaddress .= $ct.", "; }
    // if($prov != "")  { $myaddress .= $prov.", "; }
    // $myaddress = substr($myaddress,0,-2);

/* END OF SQL QUERIES */

$mpdf=new mPDF('win-1252','LETTER','','',10,10,75,30,5,5);
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
            padding:10px;border:1px solid black; text-align: center; font-weight: bold;
        }

        .itemResult {
            padding:20px;border:1px solid black;text-align: center;
        }

		.itemRows {
            padding:5px;border:1px solid black;text-align: left;
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
			<td width=20%><b>CASE NO.</b></td>
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
			<td><b>COMPANY NAME</b></td>
			<td>:&nbsp;&nbsp;' . $_ihead['company'] . '</td>
			<td><b>AGE</b></td>
			<td>:&nbsp;&nbsp;'.$_ihead['age'].'</td>
		</tr>
		<tr>
			<td><b>COMPANY ADDRESS</b></td>
			<td>:&nbsp;&nbsp;' .$_ihead['customer_address']. '</td>
			<td></td>
			<td></td>
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
<div id="main">
	<table width=60% cellpadding=0 cellspacing=0 align=center style="margin: 5px;">
        <tr><td align=center><span style="font-size: 12pt; font-weight: bold;">CHEMISTRY REPORT</span></td></tr>
    </table>

	<table width=80% cellpadding=0 cellspacing=0 align=center style="border-collapse: collapse; font-size: 15px;">
            <tr>
                <td class="itemHeader">TEST</td>
                <td class="itemHeader">RESULT</td>
				<td class="itemHeader">UNIT</td>
                <td class="itemHeader">NORMAL VALUES</td>
            </tr>';
            
			$resultQuery = $con->dbquery("SELECT `code`, attribute,unit, `value` FROM lab_singleresult WHERE so_no = '$_REQUEST[so_no]' AND pid = '$_ihead[pid]';");
			
			while($resultRow = $resultQuery->fetch_array()) {
				$html .= '<tr>
                <td class="itemRows">'.$resultRow['attribute'].'</td>
                <td class="itemRows" align=center>'.$resultRow['value'].'</td>
				<td class="itemRows" align=center>'.$resultRow['unit'].'</td>
                <td class="itemRows">'. checkLimits($resultRow['code'],$con->calculateAge($_ihead['so_date'],$_ihead['birthdate']),$_ihead['xgender']) . '</td>
            </tr>';


			}
			
			
			

    $html .= '</table>
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