<?php
	session_start();
	include("../lib/mpdf6/mpdf.php");
	include("../handlers/_generics.php");

	$o = new _init;

/* MYSQL QUERIES SECTION */
	$now = date("m/d/Y h:i a");
	$co = $o->getArray("select * from companies where company_id = '$_SESSION[company]';");

	$_ihead = $o->getArray("SELECT LPAD(a.so_no,6,0) AS myso, DATE_FORMAT(result_date,'%m/%d/%Y') AS rdate, CONCAT(b.lname,', ',b.fname,' ',b.mname) AS pname, b.street, b.brgy, b.city, b.province, IF(b.gender='M','Male','Female') AS gender, a.result_date, b.birthdate,b.employer, a.serialno, a.created_by FROM lab_uaresult a LEFT JOIN pccmain.patient_info b ON a.pid = b.patient_id WHERE a.so_no = '$_REQUEST[so_no]' AND serialno = '$_REQUEST[serialno]';");
    $b = $o->getArray("select * from lab_uaresult where serialno = '$_ihead[serialno]';");
    $c = $o->getArray("select trace_no from cso_details where cso_no='$_ihead[myso]';");
    if($b['verified_by'] != '') {
        list($medtechSignature,$medtechFullname,$medtechLicense,$medtechRole) = $o->getArray("SELECT if(signature_file != '',concat('<img src=\"../images/signatures/',signature_file,'\" align=absmiddle />'),'<img src=\"../images/signatures/blank.png\" align=absmiddle />') as signature, fullname, license_no, role from user_info where emp_id = '$b[verified_by]';");
    }

    list($brgy) = $o->getArray("SELECT brgyDesc FROM options_brgy WHERE brgyCode = '$_ihead[brgy]';");
    list($ct) = $o->getArray("SELECT citymunDesc FROM options_cities WHERE cityMunCode = '$_ihead[city]';");
    list($prov) = $o->getArray("SELECT provDesc FROM options_provinces WHERE provCode = '$_ihead[province]';");

    if($_ihead['street'] != '') { $myaddress.=$_ihead['street'].", "; }
    if($brgy != "") { $myaddress .= $brgy.", "; }
    if($ct != "") { $myaddress .= $ct.", "; }
    if($prov != "")  { $myaddress .= $prov.", "; }
    $myaddress = substr($myaddress,0,-2);

	$casts = '';

	if($b['hyaline'] != '') {
		$casts .= "HYALINE: " . $b['hyaline'] . "<br/>";
	}

	if($b['coarse_granular'] != '') {
		$casts .= "COARSE GRANULAR: " . $b['coarse_granular'] . "<br/>";
	}

	if($b['fine_granular'] != '') {
		$casts .= "FINE GRANULAR: " . $b['fine_granular'] . "<br/>";
	}

	if($b['casts_wbc'] != '') {
		$casts .= "WBC CASTS: " . $b['casts_wbc'] . "<br/>";
	}

	if($b['casts_rbc'] != '') {
		$casts .= "RBC CASTS: " . $b['casts_rbc'] . "<br/>";
	}

	$crystals = '';

	if($b['calcium_oxalate'] != '') {
		$crystals .= "CALCIUM OXALATE: " . $b['calcium_oxalate'] . "<br/>";
	}
    
	if($b['triple_phosphates'] != '') {
		$crystals .= "TRIPLE PHOSPHATES: " . $b['triple_phosphates'] . "<br/>";
	}

	if($b['uric_acid'] != '') {
		$crystals .= "URIC ACID: " . $b['uric_acid'] . "<br/>";
	}

/* END OF SQL QUERIES */

$mpdf=new mPDF('win-1252','letter','','',10,10,45,30,10,10);
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
		<td width="100%" style="padding-top: 10px;" align=center>
			<span style="font-weight: bold; font-size: 12pt; color: #000000;">LABORATORY DEPARTMENT</span>
		</td>
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
<table width=100% cellpadding=2 cellspacing=0 style="font-size: 10pt;margin-top:20px;">
    <tr>
        <td width=100% colspan=4 style="background-color: #cdcdcd; border-top: 1px solid black; border-bottom: 1px solid black;" align=center><b>PATIENT INFORMATION</b></td>
    </tr>
	<tr>
		<td width=25%><b>CASE NO.</b></td>
		<td width=45%>:&nbsp;&nbsp;'.$_ihead['myso'].'</td>
		<td width=15%><b>DATE</b></td>
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
		<td>:&nbsp;&nbsp;'.$o->calculateAge($_ihead['result_date'],$_ihead['birthdate']).'</td>
	</tr>
    <tr>
		<td><b>COMPANY</b></td>
		<td>:&nbsp;&nbsp;' . $_ihead['employer'] . '</td>
		<td></td>
		<td></td>
	</tr>
    <tr>
        <td width="100%" colspan=4 style="padding-top: 15px;" align=center>
         <span style="font-weight: bold; font-size: 12pt; color: #000000; text-decoration: underline;">&nbsp;&nbsp;&nbsp;URINALYSIS (UA)&nbsp;&nbsp;&nbsp;</span>
        </td>
    </tr>
</table>

<table width=100% cellpadding=0 cellspacing=5 align=center style="padding-left: 100px; font-size: 8pt;">
		<tr>
			<td align="left" colspan=3  style="padding-left: 15px;"><b>MACROSCOPIC&nbsp;:</b></td>
		</tr>
		<tr>
			<td align="left" width=30%></td>
			<td align=center width=40%></td>
			<td align="left" width=30%></td>	
		</tr>
		<tr>
			<td align="left" style="padding-left: 35px;">Color</td>
			<td align=center style="border-bottom: 1px solid black;vertical-align: top;">'. $b['color'] . '</td>
			<td align="left"></td>	
		</tr>
		<tr>
			<td align="left" style="padding-left: 35px;">Appearance</td>
			<td align=center style="border-bottom: 1px solid black;vertical-align: top;">'. $b['appearance'] . '</td>
			<td align="left"></td>	
		</tr>';

		if($b['leukocytes'] != '' && $b['nitrite'] != '' && $b['urobilinogen'] != '') {
		$html .= '<tr>
			<td align="left" style="padding-left: 35px;">Leukocytes</td>
			<td align=center style="border-bottom: 1px solid black;vertical-align: top;">'. $b['leukocytes'] . '</td>
			<td align="left"></td>	
		</tr>
		<tr>
			<td align="left" style="padding-left: 35px;">Nitrite</td>
			<td align=center style="border-bottom: 1px solid black;vertical-align: top;">'. $b['nitrite'] . '</td>
			<td align="left"></td>	
		</tr>
		<tr>
			<td align="left" style="padding-left: 35px;">Urobilinogen</td>
			<td align=center style="border-bottom: 1px solid black;vertical-align: top;">'. $b['urobilinogen'] . '</td>
			<td align="left"></td>	
		</tr>';
		}
		$html .=	'<tr>
			<td align="left" style="padding-left: 35px;">Protein</td>
			<td align=center style="border-bottom: 1px solid black;">'. $b['protein'] . '</td>
			<td align="left"></td>	
		</tr>
		<tr>
			<td align="left" style="padding-left: 35px;">pH</td>
			<td align=center tyle="border-bottom: 1px solid black;">'. $b['ph'] . '</td>
			<td align="left"></td>	
		</tr>';
		if($b['leukocytes'] != '' && $b['nitrite'] != '' && $b['urobilinogen'] != '') {
			$html .='<tr>
			<td align="left" style="padding-left: 35px;">Blood</td>
			<td align=center style="border-bottom: 1px solid black;">'. $b['blood'] . '</td>
			<td align="left"></td>	
		</tr>';
		}
	$html .=	'<tr>
			<td align="left" style="padding-left: 35px;">Specific Gravity</td>
			<td align=center style="border-bottom: 1px solid black;">'. $b['gravity'] . '</td>
			<td align="left"></td>	
		</tr>';

		if($b['leukocytes'] != '' && $b['nitrite'] != '' && $b['urobilinogen'] != '') {
	$html .=	'<tr>
			<td align="left" style="padding-left: 35px;">Ketone</td>
			<td align=center style="border-bottom: 1px solid black;">'. $b['ketone'] . '</td>
			<td align="left"></td>	
		</tr>
		<tr>
			<td align="left" style="padding-left: 35px;">Bilirubin</td>
			<td align=center style="border-bottom: 1px solid black;">'. $b['bilirubin'] . '</td>
			<td align="left"></td>	
		</tr>';
		}
	$html .=	'<tr>
			<td align="left" style="padding-left: 35px;">Glucose</td>
			<td align=center style="border-bottom: 1px solid black;">'. $b['glucose'] . '</td>
			<td align="left"></td>	
		</tr>
		<tr><td height=5>&nbsp;</td></tr>
		<tr>
			<td align="left" colspan=3  style="padding-left: 15px;"><b>MICROSCOPIC&nbsp;:</b></td>
		</tr>
		<tr>
			<td align="left" style="padding-left: 35px;">RBC&nbsp;:</td>
			<td align=center style="border-bottom: 1px solid black;">'. $b['rbc_hpf'] . '</td>
			<td align="left">/HPF</td>	
		</tr>
		<tr>
			<td align="left" style="padding-left: 35px;">WBC&nbsp;:</td>
			<td align=center style="border-bottom: 1px solid black;">'. $b['wbc_hpf'] . '</td>
			<td align="left">/HPF</td>	
		</tr>
		<tr>
			<td align="left" style="padding-left: 35px;">Epith. Cells&nbsp;:</td>
			<td align=center style="border-bottom: 1px solid black;">'. $b['squamous'] . '</td>
			<td align="left"></td>
		</tr>
		<tr>
			<td align="left" style="padding-left: 35px;">Casts&nbsp;:</td>
			<td align=center style="border-bottom: 1px solid black;">'. $b['casts'] . '</td>
			<td align="left"></td>	
		</tr>
		<tr>
			<td align="left" style="padding-left: 35px;">Mucus Threads&nbsp;:</td>
			<td align=center style="border-bottom: 1px solid black;">'. $b['mucus_thread'] . '</td>
			<td align="left"></td>	
		</tr>
		<tr>
			<td align="left" style="padding-left: 35px;">Bacteria&nbsp;:</td>
			<td align=center style="border-bottom: 1px solid black;">'. $b['bacteria'] . '</td>
			<td align="left"></td>	
		</tr>
		<tr>
			<td align="left" style="padding-left: 35px;">Crystals&nbsp;:</td>
			<td align=center style="border-bottom: 1px solid black;">'. $b['crystals'] . '</td>
			<td align="left"></td>	
		</tr>
		<tr>
			<td align="left" style="padding-left: 35px;">Amorphous (Urates)&nbsp;:</td>
			<td align=center style="border-bottom: 1px solid black;">'. $b['amorphous_urates'] . '</td>
			<td align="left"></td>	
		</tr>
		<tr>
			<td align="left" style="padding-left: 35px;">Amorphous (PO<sub>4</sub>)&nbsp;:</td>
			<td align=center style="border-bottom: 1px solid black;">'. $b['amorphous_po4'] . '</td>
			<td align="left"></td>	
		</tr>
		<tr><td height=3>&nbsp;</td></tr>
		<tr>
			<td align="left" style="padding-left: 15px;" valign=top><b>Note&nbsp;:</b></td>
			<td align=left width=70% colspan=2 style="border-bottom: 1px solid black;">'. $b['remarks'] . '</td>
		</tr>
		<tr>
			<td align="left" style="padding-left: 15px;" valign=top><b>Others&nbsp;:</b></td>
			<td align=left width=70% colspan=2 style="border-bottom: 1px solid black;">'. $b['others'] . '</td>
		
		</tr>
	</table>

</body>
</html>
';

$html = html_entity_decode($html);
$mpdf->WriteHTML($html);
$mpdf->Output(); exit;
exit;
?>