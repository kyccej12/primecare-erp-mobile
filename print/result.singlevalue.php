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



	$lipidRes = $con->getArray("SELECT a.*,a.serialno,LPAD(a.pid,6,0) AS pid, a.pname, b.customer_name AS company, b.customer_address, DATE_FORMAT(a.result_date, '%m/%d/%Y') AS rdate, c.street, c.brgy, c.city, c.province,FLOOR(DATEDIFF(b.cso_date,c.birthdate)/364.25) AS age, IF(c.gender='M','Male','Female') AS gender, c.birthdate, a.created_by, b.trace_no FROM lab_lipidpanel a LEFT JOIN cso_header b ON a.so_no = b.cso_no LEFT JOIN patient_info c ON c.patient_id = a.pid WHERE a.so_no = '$_REQUEST[so_no]' AND serialno = '$_REQUEST[serialno]';");



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

			$rbs = $con->getArray("SELECT `code`, attribute,unit, `value` FROM lab_singleresult WHERE `code` = 'L174' and so_no = '$_REQUEST[so_no]' AND pid = '$_ihead[pid]';");
			if(count($rbs) > 0) {
				$html .= '<tr>
				     <td class="itemRows">Glucose/RBS</td>
				     <td class="itemRows" align=center>'.$rbs['value'].'</td>
				 	<td class="itemRows" align=center>'.$rbs['unit'].'</td>
				     <td class="itemRows">'. checkLimits($rbs['code'],$con->calculateAge($_ihead['so_date'],$_ihead['birthdate']),$_ihead['xgender']) . '</td>
				 </tr>';
			}
            
			$fbs = $con->getArray("SELECT `code`, attribute,unit, `value` FROM lab_singleresult WHERE `code` = 'L113' and so_no = '$_REQUEST[so_no]' AND pid = '$_ihead[pid]';");
			if(count($fbs) > 0) {
				$html .= '<tr>
				     <td class="itemRows">Glucose/FBS</td>
				     <td class="itemRows" align=center>'.$fbs['value'].'</td>
				 	<td class="itemRows" align=center>'.$fbs['unit'].'</td>
				     <td class="itemRows">'. checkLimits($fbs['code'],$con->calculateAge($_ihead['so_date'],$_ihead['birthdate']),$_ihead['xgender']) . '</td>
				 </tr>';
			}
			
			$lipidRes = $con->getArray("SELECT cholesterol, triglycerides, hdl, ldl, vldl  FROM lab_lipidpanel WHERE so_no = '$_REQUEST[so_no]' AND pid = '$_ihead[pid]';");
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
                <td class="itemRows">'.$con->getAttribute('L019',$con->age,$_ihead['xgender']).'</td>
            </tr>
			<tr>
                <td class="itemRows">Triglycerides</td>
                <td class="itemRows" align=center>'.$lipidRes['triglycerides'].'</td>
				<td class="itemRows" align=center>'.$fbs['unit'].'</td>
                <td class="itemRows">'.$con->getAttribute('L032',$con->age,$_ihead['xgender']).'</td>
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

			$hba1c = $con->getArray("SELECT `code`, attribute,unit, `value` FROM lab_singleresult WHERE `code` = 'L022' and so_no = '$_REQUEST[so_no]' AND pid = '$_ihead[pid]';");
			if(count($hba1c) > 0) {
				$html .= '<tr>
					<td class="itemRows">SGPT</td>
					<td class="itemRows" align=center>'.$hba1c['value'].'</td>
					<td class="itemRows" align=center>'.$hba1c['unit'].'</td>
					<td class="itemRows">'. checkLimits($hba1c['code'],$con->calculateAge($_ihead['so_date'],$_ihead['birthdate']),$_ihead['xgender']) . '</td>
					</tr>';
			}

			$sgptalt = $con->getArray("SELECT `code`, attribute,unit, `value` FROM lab_singleresult WHERE `code` = 'L029' and so_no = '$_REQUEST[so_no]' AND pid = '$_ihead[pid]';");
			if(count($sgptalt) > 0) {
				$html .= '<tr>
				    <td class="itemRows">SGPT/ALT</td>
					<td class="itemRows" align=center>'.$sgptalt['value'].'</td>
					<td class="itemRows" align=center>'.$sgptalt['unit'].'</td>
					<td class="itemRows">'. checkLimits($sgptalt['code'],$con->calculateAge($_ihead['so_date'],$_ihead['birthdate']),$_ihead['xgender']) . '</td>
					</tr>';
			}

			$ast = $con->getArray("SELECT `code`, attribute,unit, `value` FROM lab_singleresult WHERE `code` = 'L028' and so_no = '$_REQUEST[so_no]' AND pid = '$_ihead[pid]';");
			if(count($ast) > 0) {
				$html .= '<tr>
				    <td class="itemRows">AST</td>
					<td class="itemRows" align=center>'.$ast['value'].'</td>
					<td class="itemRows" align=center>'.$ast['unit'].'</td>
					<td class="itemRows">'. checkLimits($ast['code'],$con->calculateAge($_ihead['so_date'],$_ihead['birthdate']),$_ihead['xgender']) . '</td>
					</tr>';
			}

			$bun = $con->getArray("SELECT `code`, attribute,unit, `value` FROM lab_singleresult WHERE `code` = 'L005' and so_no = '$_REQUEST[so_no]' AND pid = '$_ihead[pid]';");
			if(count($bun) > 0) {
				$html .= '<tr>
				    <td class="itemRows">BUN</td>
					<td class="itemRows" align=center>'.$bun['value'].'</td>
					<td class="itemRows" align=center>'.$bun['unit'].'</td>
					<td class="itemRows">'. checkLimits($bun['code'],$con->calculateAge($_ihead['so_date'],$_ihead['birthdate']),$_ihead['xgender']) . '</td>
					</tr>';
			}

			$bua = $con->getArray("SELECT `code`, attribute,unit, `value` FROM lab_singleresult WHERE `code` = 'L004' and so_no = '$_REQUEST[so_no]' AND pid = '$_ihead[pid]';");
			if(count($bua) > 0) {
				$html .= '<tr>
				    <td class="itemRows">BUA</td>
					<td class="itemRows" align=center>'.$bua['value'].'</td>
					<td class="itemRows" align=center>'.$bua['unit'].'</td>
					<td class="itemRows">'. checkLimits($bua['code'],$con->calculateAge($_ihead['so_date'],$_ihead['birthdate']),$_ihead['xgender']) . '</td>
					</tr>';
			}

			$crea = $con->getArray("SELECT `code`, attribute,unit, `value` FROM lab_singleresult WHERE `code` = 'L020' and so_no = '$_REQUEST[so_no]' AND pid = '$_ihead[pid]';");
			if(count($crea) > 0) {
				$html .= '<tr>
				    <td class="itemRows">CREATININE</td>
					<td class="itemRows" align=center>'.$crea['value'].'</td>
					<td class="itemRows" align=center>'.$crea['unit'].'</td>
					<td class="itemRows">'. checkLimits($crea['code'],$con->calculateAge($_ihead['so_date'],$_ihead['birthdate']),$_ihead['xgender']) . '</td>
					</tr>';
			}

			$alp = $con->getArray("SELECT `code`, attribute,unit, `value` FROM lab_singleresult WHERE `code` = 'L016' and so_no = '$_REQUEST[so_no]' AND pid = '$_ihead[pid]';");
			if(count($alp) > 0) {
				$html .= '<tr>
				    <td class="itemRows">ALP</td>
					<td class="itemRows" align=center>'.$alp['value'].'</td>
					<td class="itemRows" align=center>'.$alp['unit'].'</td>
					<td class="itemRows">'. checkLimits($alp['code'],$con->calculateAge($_ihead['so_date'],$_ihead['birthdate']),$_ihead['xgender']) . '</td>
					</tr>';
			}

			$potassium = $con->getArray("SELECT `code`, attribute,unit, `value` FROM lab_singleresult WHERE `code` = 'L026' and so_no = '$_REQUEST[so_no]' AND pid = '$_ihead[pid]';");
			if(count($potassium) > 0) {
				$html .= '<tr>
				    <td class="itemRows">POTASSIUM</td>
					<td class="itemRows" align=center>'.$potassium['value'].'</td>
					<td class="itemRows" align=center>'.$potassium['unit'].'</td>
					<td class="itemRows">'. checkLimits($potassium['code'],$con->calculateAge($_ihead['so_date'],$_ihead['birthdate']),$_ihead['xgender']) . '</td>
					</tr>';
			}
			// while($resultRow = $resultQuery->fetch_array()) {
			// 	$html .= '<tr>
            //     <td class="itemRows">'.$resultRow['attribute'].'</td>
            //     <td class="itemRows" align=center>'. to_char($resultRow['value'],0).'</td>
			// 	<td class="itemRows" align=center>'.$resultRow['unit'].'</td>
            //     <td class="itemRows">'. checkLimits($resultRow['code'],$con->calculateAge($_ihead['so_date'],$_ihead['birthdate']),$_ihead['xgender']) . '</td>
            // </tr>';
			// }			
			

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