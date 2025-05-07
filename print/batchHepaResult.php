<?php
	session_start();
    //ini_set("display_errors","on");
	ini_set("max_execution_time",0);
	ini_set("memory_limit",-1);
	include("../lib/mpdf6/mpdf.php");
	include("../handlers/_generics.php");

	$con = new _init;

/* MYSQL QUERIES SECTION */
	$now = date("m/d/Y h:i a");
	$co = $con->getArray("select * from companies where company_id = '$_SESSION[company]';");


	$mpdf=new mPDF('win-1252','FOLIO-H','','',10,10,70,30,5,5);
	$mpdf->use_embeddedfonts_1252 = true;    // false is default
	$mpdf->SetProtection(array('print'));
	$mpdf->SetAuthor("PORT80 Solutions");
	$searchString = '';

	$outerQuery = $con->dbquery("SELECT c.patient_id, d.company, d.location AS comp_addr, DATE_FORMAT(result_date,'%m/%d/%Y') AS rdate, a.so_no, FLOOR(DATEDIFF(d.cso_date,c.birthdate)/364.25) AS age, a.branch, b.pname, c.brgy, c.city, c.province, IF(c.gender='M','Male','Female') AS gender, c.gender AS xgender, c.birthdate, e.serialno, a.`code`, a.result, a.remarks, a.created_by, b.trace_no, a.verified, a.verified_by, e.extractdate  FROM lab_enumresult a LEFT JOIN cso_header d ON a.so_no = d.cso_no LEFT JOIN lab_samples e ON e.serialno = a.serialno LEFT JOIN pccmain.patient_info c ON e.pid = c.patient_id LEFT JOIN cso_details b ON e.pid = b.pid AND a.branch = b.branch WHERE a.so_no = '$_REQUEST[so_no]' AND a.verified = 'Y' GROUP BY b.pname;");
	while($_ihead = $outerQuery->fetch_array()) {

		list($cname,$soDate) = $con->getArray("select customer_name,cso_date from cso_header where cso_no = '$_REQUEST[so_no]';");
		$a = $con->getArray("select trace_no from cso_details where cso_no = '$_ihead[so_no]';");

		list($resuldate) = $con->getArray("select date_format(extractdate,'%m/%d/%Y') from lab_samples where so_no = '$_REQUEST[so_no]' and pid = '$_ihead[patient_id]';");

        // $b = $con->getArray("SELECT attribute, concat(`value`,unit) as val, verified, verified_by FROM lab_singleresult WHERE so_no = '$_ihead[so_no]' and branch = '$_ihead[branchid]' and code = '$_ihead[code]' and serialno = '$_ihead[serialno]';");	
        // $c = $con->getArray("SELECT CONCAT(min_value,' - ',`max_value`,`unit`) as limits FROM lab_testvalues WHERE `code` = '$_ihead[code]';");

		$myaddress = '';
		list($brgy) = $con->getArray("SELECT brgyDesc FROM options_brgy WHERE brgyCode = '$_ihead[brgy]';");
		list($ct) = $con->getArray("SELECT citymunDesc FROM options_cities WHERE cityMunCode = '$_ihead[city]';");
		list($prov) = $con->getArray("SELECT provDesc FROM options_provinces WHERE provCode = '$_ihead[province]';");
	
		if($_ihead['street'] != '') { $myaddress.=$_ihead['street'].", "; }
		if($brgy != "") { $myaddress .= $brgy.", "; }
		if($ct != "") { $myaddress .= $ct.", "; }
		if($prov != "")  { $myaddress .= $prov.", "; }
		$myaddress = substr($myaddress,0,-2);

        list($procedure) = $con->getArray("SELECT `description` FROM services_master WHERE `code` = '$_ihead[code]';");	
	
		if($_ihead['verified_by'] != '') {
			list($medtechSignature,$medtechFullname,$medtechLicense,$medtechRole) = $con->getArray("SELECT if(signature_file != '',concat('<img src=\"../images/signatures/',signature_file,'\" align=absmiddle />'),'<img src=\"../images/signatures/blank.png\" align=absmiddle />') as signature, fullname, license_no, role from user_info where emp_id = '$_ihead[verified_by]';");
		}

        $hbsResult = $con->getArray("select *, verified_by from lab_enumresult where `code` = 'L146' and so_no = '$_REQUEST[so_no]';");
		if($hbsResult['verified_by'] != '') {
			list($HBSmedtechSignature,$HBSmedtechFullname,$HBSmedtechLicense,$HBSmedtechRole) = $con->getArray("SELECT if(signature_file != '',concat('<img src=\"../images/signatures/',signature_file,'\" align=absmiddle width=105 height=35 />'),'<img src=\"../images/signatures/blank.png\" align=absmiddle width=105 height=35 />') as signature, fullname, license_no, role from user_info where emp_id = '$hbsResult[verified_by]';");
		}


	/* END OF SQL QUERIES */

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
    
            #items td { border: 1px solid; text-align: center; }
        </style>
    </head>
    <body>
    
    <!--mpdf
    <htmlpageheader name="myheader">
    <table width="100%" cellpadding=0 cellspaing=0>
        <tr><td align=center><img src="../images/doc-header.jpg" /></td></tr>
    
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
                <td width=15%>:&nbsp;&nbsp;'.$resuldate.'</td>
            </tr>
            <tr>
                <td><b>PATIENT NAME</b></td>
                <td>:&nbsp;&nbsp;'.$_ihead['pname'].'</td>
                <td><b>GENDER</b></td>
                <td>:&nbsp;&nbsp;'.$_ihead['gender'].'</td>
            </tr>
            <tr>
                <td><b>COMPANY NAME</b></td>
                <td>:&nbsp;&nbsp;' . $cname . '</td>
                <td><b>AGE</b></td>
                <td>:&nbsp;&nbsp;'.$_ihead['age'].'</td>
            </tr>
            <tr>
                <td><b>COMPANY ADDRESS</b></td>
                <td>:&nbsp;&nbsp;' .$_ihead['comp_addr']. '</td>
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
        <tr><td align=left><barcode size=0.8 code="'.substr($_ihead['trace_no'],0,10).'" type="C128A"></td><td align=right>Date & Time Printed: '.date('m/d/Y h:i:s a').'</td></tr>
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
            <tr><td width=100% align=center><span style="font-size: 14pt; font-weight: bold; font-style: italic;">'.$_ihead['result'].'</span></td></tr>
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

	$endOfPage = $mpdf->page + 1;
	$html = html_entity_decode($html);
	$mpdf->WriteHTML($html);
	$mpdf->AddPage();

    if(count($hbsResult) > 0) {
    
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
            
                    #items td { border: 1px solid; text-align: center; }
                </style>
            </head>
            <body>
            
            <!--mpdf
            <htmlpageheader name="myheader">
            <table width="100%" cellpadding=0 cellspaing=0>
                <tr><td align=center><img src="../images/doc-header.jpg" /></td></tr>
            
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
                        <td width=15%>:&nbsp;&nbsp;'.$resuldate.'</td>
                    </tr>
                    <tr>
                        <td><b>PATIENT NAME</b></td>
                        <td>:&nbsp;&nbsp;'.$_ihead['pname'].'</td>
                        <td><b>GENDER</b></td>
                        <td>:&nbsp;&nbsp;'.$_ihead['gender'].'</td>
                    </tr>
                    <tr>
                        <td><b>COMPANY NAME</b></td>
                        <td>:&nbsp;&nbsp;' . $cname . '</td>
                        <td><b>AGE</b></td>
                        <td>:&nbsp;&nbsp;'.$_ihead['age'].'</td>
                    </tr>
                    <tr>
                        <td><b>COMPANY ADDRESS</b></td>
                        <td>:&nbsp;&nbsp;' .$_ihead['comp_addr']. '</td>
                        <td></td>
                        <td></td>
                    </tr>
            </table>
            
            </htmlpageheader>
            
            <htmlpagefooter name="myfooter">
            <table width=100% cellpadding=5 style="margin-bottom: 25px;">
                <tr>
                    <td align=center valign=top>'.$HBSmedtechSignature.'<br/><b>'.$HBSmedtechFullname.'<br/>___________________________________________<br>'.$HBSmedtechRole.'<br/>License No. '.$HBSmedtechLicense.'</b></td>
                    <td align=center valign=top><img src="../images/signatures/psa-signature.png" align=absmidddle /><br/><b>PETER S. AZNAR, M.D, F.P.S.P<br/>___________________________________________<br><b>PATHOLOGIST</b><br><span style="font-size: 7pt;">PRC LICENSE NO. 72410</span></td>
                    </tr>
            </table>
            <table width=100%>
                <tr><td align=left><barcode size=0.8 code="'.substr($_ihead['trace_no'],0,10).'" type="C128A"></td><td align=right>Date & Time Printed: '.date('m/d/Y h:i:s a').'</td></tr>
            </table>
            </htmlpagefooter>
            
            <sethtmlpageheader name="myheader" value="on" show-this-page="1" />
            <sethtmlpagefooter name="myfooter" value="on" />
            mpdf-->
            <div id="main">
                <table width=60% cellpadding=0 cellspacing=0 align=center style="margin: 5px;">
                    <tr><td align=center><span style="font-size: 12pt; font-weight: bold;">'.$hbsResult['procedure'].'</span></td></tr>
                </table>
                <table width=60% cellpadding=0 cellspacing=0 align=center style="border:1px solid black; padding: 10px;">
                    <tr><td width=100% align=center><span style="font-size: 14pt; font-weight: bold; font-style: italic;">'.$hbsResult['result'].'</span></td></tr>
                </table>
                <table width=60% align=center style="margin-top: 5px; font-size: 9pt; font-style: italic;">
                    <tr>
                        <td align=left width=18%><b>REMARKS :</b></td>
                        <td align=left width=82% style="border-bottom: 1px solid black;">'.$hbsResult['remarks'].'</td>
                    </tr>
                </table>    
            </div>

        </body>
        </html>
        ';
 

    $endOfPage = $mpdf->page + 1;
	$html = html_entity_decode($html);
	$mpdf->WriteHTML($html);
	$mpdf->AddPage();
                
    }

}

$mpdf->DeletePages($endOfPage);
$mpdf->Output();
exit;



?>