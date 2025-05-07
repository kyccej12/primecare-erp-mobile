<?php

    session_start();
    // ini_set("display_errors","on");
    require_once("lib/mpdf6/mpdf.php");
    include("handlers/_generics.php");
    $con = new _init();

    ini_set("max_execution_time",0);
    ini_set("memory_limit",-1);

    $mpdf=new mPDF('win-1252','BARCODE','','',0,0,4.2,0,0,0);
    $mpdf->use_embeddedfonts_1252 = true;    // false is default
    $mpdf->setAutoTopMargin='stretch';
    $mpdf->setAutoBottomMargin='stretch';
    $mpdf->use_kwt = true;
    $mpdf->SetProtection(array('print'));
    $mpdf->SetAuthor("Primecare Cebu");

    /* NON CHEMISTRY */

    $a = $con->dbquery("SELECT a.so_no, a.pid, CONCAT(b.lname,'^',b.fname) AS pname, a.code, `procedure`, sampletype, DATE_FORMAT(b.birthdate,'%d %b %Y') AS bdate, b.birthdate, a.so_date, b.gender, NOW() AS `timestamp`, DATE_FORMAT(NOW(),'%m/%d/%Y %H:%i %p') AS tstamp, d.subcategory FROM lab_samples a LEFT JOIN  pccmain.patient_info b ON a.pid = b.patient_id LEFT JOIN pccmain.services_master c ON a.code = c.code LEFT JOIN pccmain.options_servicesubcat d ON c.subcategory = d.id WHERE a.pid = '$_POST[pid]' AND so_no = '$_POST[cso_no]' AND c.category IN ('1','2') AND c.subcategory != '1';");
    while($b = $a->fetch_array()) {
        
        list($code) = $con->getArray("select `sn_code` from pccmain.options_sampletype where id = '$b[sampletype]';");
		list($serialno) = $con->getArray("SELECT concat('$code',LPAD(IFNULL(MAX(series+1),1),8,0),'M') as series FROM (SELECT TRIM(LEADING '0' FROM SUBSTRING(`serialno`,2,8)) AS series FROM lab_samples WHERE sampletype = '$b[sampletype]') a;");

        $useDescription = array('L012','L013');

        if(in_array($b['code'],$useDescription)) {
            $procedure = $b['procedure']; 
        } else {
            $procedure = $b['subcategory'];
        }


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
                            <td align=left colspan=2>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$b['pname'].'^' . $b['gender'] . '^'.$con->calculateAge($b['so_date'],$b['birthdate']).'</td>
                        </tr>
                        <tr><td colspan=2 align=center><barcode code="'.$serialno.'" type="C128A" height="1.2" size="0.85"></td></tr>
                        <tr>
                            <td colspan=2>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'. strtoupper($procedure) . '</td>
                        </tr>   
                        <tr>
                            <td colspan=2>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Sample No.: '.$serialno.'</td>
                        </tr>  
                    </table>
                </body>
            </html>';

        $endOfPage = $mpdf->page + 1;
        $html = html_entity_decode($html);
        $mpdf->WriteHTML($html);
        $mpdf->AddPage();

        /* Update Lab Samples for Serial No. */
        $con->dbquery("UPDATE IGNORE lab_samples set serialno = '$serialno', processed_by = '$_SESSION[userid]', processed_on = '$b[timestamp]' where `code` = '$b[code]' and pid = '$b[pid]' and so_no = '$b[so_no]';");

    }

    /* CHEMISTRY REQUESTS */
    $c = $con->dbquery("SELECT a.so_no, a.pid, CONCAT(b.lname,'^',b.fname) AS pname, DATE_FORMAT(b.birthdate,'%d %b %Y') AS bdate, b.birthdate, a.so_date, b.gender, NOW() AS `timestamp`, DATE_FORMAT(NOW(),'%m/%d/%Y %H:%i %p') AS tstamp, d.subcategory, a.sampletype FROM lab_samples a LEFT JOIN pccmain.patient_info b ON a.pid = b.patient_id LEFT JOIN pccmain.services_master c ON a.code = c.code LEFT JOIN pccmain.options_servicesubcat d ON c.subcategory = d.id WHERE a.pid = '$_POST[pid]' AND so_no = '$_POST[cso_no]' AND c.category = '1' AND c.subcategory = '1' GROUP BY c.subcategory;");
    while($d = $c->fetch_array()) {
        
        list($code) = $con->getArray("select `sn_code` from pccmain.options_sampletype where id = '$d[sampletype]';");
		list($serialno) = $con->getArray("SELECT concat('$code',LPAD(IFNULL(MAX(series+1),1),8,0),'M') as series FROM (SELECT TRIM(LEADING '0' FROM SUBSTRING(`serialno`,2,8)) AS series FROM lab_samples WHERE sampletype = '$d[sampletype]') a;");

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
                            <td align=left colspan=2>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$d['pname'].'^' . $d['gender'] . '^'.$con->calculateAge($d['so_date'],$d['birthdate']).'</td>
                        </tr>
                        <tr><td colspan=2 align=center><barcode code="' . $serialno . '" type="C128A" height="1.2" size="0.85"></td></tr>
                        <tr>
                            <td colspan=2>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'. strtoupper($d['subcategory']) . '</td>
                        </tr>   
                        <tr>
                            <td colspan=2>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Sample No.: '. $serialno . '</td>
                        </tr>  
                    </table>
                </body>
            </html>';

        $endOfPage = $mpdf->page + 1;
        $html = html_entity_decode($html);
        $mpdf->WriteHTML($html);
        $mpdf->AddPage();

        /* Update Lab Samples for Serial No. */
        $con->dbquery("UPDATE IGNORE lab_samples set serialno = '$serialno', processed_by = '$_SESSION[userid]', processed_on = '$d[timestamp]' where `code` IN (SELECT `code` from pccmain.services_master WHERE subcategory = '1') and pid = '$d[pid]' and so_no = '$d[so_no]';");

    }



    $mpdf->DeletePages($endOfPage);
    //list($lname) = $con->getArray("select lname from pccmain.patient_info where patient_id = '$_POST[pid]';");
    $filename = "images/labels/" . $_POST['cso_no'] . "_" . $_POST['pid'] . ".pdf";

    $mpdf->WriteHTML($html);
    $mpdf->Output($filename,'F');

     /* Update CSO that a barcode has already been provided */
     $con->dbquery("UPDATE IGNORE cso_details set `barcode` = 'Y', label_path = '$filename', processed_on = now() where cso_no = '$_POST[cso_no]' and pid = '$_POST[pid]';");


    echo $filename;

?>