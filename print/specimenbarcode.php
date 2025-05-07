<?php
    session_start();
	require_once("../lib/mpdf6/mpdf.php");
	require_once("../handlers/_generics.php");

    $con = new _init;
    $serialno = $_GET['id'];
   // $a = $con->getArray("SELECT a.code, CONCAT(b.lname,', ',b.fname,' ',b.mname) AS pname, DATE_FORMAT(b.birthdate,'%m/%d/%y') AS bday, a.so_date, b.birthdate,b.gender,CONCAT(DATE_FORMAT(extractdate,'%m/%d/%Y'),' ',TIME_FORMAT(extractime,'%h:%i %p')) AS tstamp, c.sample_type FROM lab_samples a LEFT JOIN pccmain.patient_info b ON a.pid = b.patient_id LEFT JOIN pccmain.options_sampletype c ON a.sampletype = c.id WHERE a.serialno = '$serialno';");
    $a = $con->getArray("SELECT a.code, a.pid, a.code, CONCAT(b.lname,', ',b.fname,' ',b.mname) AS pname, DATE_FORMAT(b.birthdate,'%m/%d/%y') AS bday, a.so_date, b.birthdate,b.gender,CONCAT(DATE_FORMAT(extractdate,'%m/%d/%Y'),' ',TIME_FORMAT(extractime,'%h:%i %p')) AS tstamp, c.sample_type, a.`code`, `procedure`, e.subcategory FROM lab_samples a LEFT JOIN pccmain.patient_info b ON a.pid = b.patient_id LEFT JOIN pccmain.options_sampletype c ON a.sampletype = c.id LEFT JOIN pccmain.services_master d ON a.code = d.code LEFT JOIN pccmain.options_servicesubcat e ON d.subcategory = e.id WHERE a.serialno = '$serialno';");

    if($a['code'] == 'L047') { $bSize = '1.2'; } else { $bSize = '0.85'; }

    $mpdf=new mPDF('win-1252','BARCODE','','',0,0,4.2,0,0,0);
    $mpdf->use_embeddedfonts_1252 = true;    // false is default
    $mpdf->setAutoTopMargin='stretch';
    $mpdf->setAutoBottomMargin='stretch';
    $mpdf->use_kwt = true;
    $mpdf->SetProtection(array('print'));
    $mpdf->SetAuthor("Opon Medical Diagnostic Corporation");
    $mpdf->SetDisplayMode(100);

     $useDescription = array('L012','L013');

        if(in_array($a['code'],$useDescription)) {
            $procedure = $a['procedure']; 
        } else {
            $procedure = $a['subcategory'];
        }

    if($a['code'] != 'L047') {

        $html = '<html>
                <head>
                    <title>Specimen Barcode</title>
                    <style>
                        body {
                            font-family: sans;
                            font-size: 5.5pt;
                        }    
                    </style>
                </head>
                <body>
                    <table width=100% cellpadding=0 cellspacing=0  style="font-weight: bold;">
                        <tr>
                            <td align=left colspan=2>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$a['pname'].'^' . $a['gender'] . '^'.$con->calculateAge($a['so_date'],$a['birthdate']).'</td>
                        </tr>
                        <tr><td colspan=2 align=center><barcode code="'.$serialno.'" type="C128A" height="1.2" size="0.85"></td></tr>
                        <tr>
                            <td colspan=2>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'. strtoupper($procedure) . '</td>
                        </tr>
                        <tr>
                            <td colspan=2>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Date/Time: '.$a['tstamp'].'</td>
                        </tr>
                        <tr>
                            <td colspan=2>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Sample No.: '.$serialno.'</td>
                        </tr>   
                    </table>
                </body>
            </html>';
    } else {
        $html = '<html>
            <head>
                <title>Specimen Barcode</title>
                <style>
                    body {
                        font-family: sans;
                        font-size: 5.5pt;
                    }    
                </style>
            </head>
            <body>
                <table width=100% cellpadding=0 cellspacing=0  style="font-weight: bold;">
                    <tr><td colspan=2 align=center><barcode code="'.$serialno.'" type="C128A" height="1.2" size="0.85"></td></tr>
                    <tr>
                        <td align=left>&nbsp;&nbsp;&nbsp;'.$a['tstamp'].'</td>
                        <td width=50% align=right>'. $serialno . '&nbsp;&nbsp;&nbsp;</td>
                    </tr>    
                </table>
            </body>
        </html>';
        
    }

 $mpdf->WriteHTML($html);
 $mpdf->Output();
 exit;
?>