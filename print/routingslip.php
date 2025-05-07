<?php

    session_start();
    include("../lib/mpdf6/mpdf.php");
    include("../handlers/_generics.php");

    $con = new _init;
    $today = date('Y-m-d');

    $co = $con->getArray("select * from companies where company_id = '1';");

    $_ihead = $con->getArray("SELECT *, LPAD(a.cso_no,6,0) AS sono, DATE_FORMAT(a.cso_date,'%m/%d/%Y') AS d8, b.pid, cso_date FROM cso_header a left join cso_details b on a.cso_no = b.cso_no and a.trace_no = b.trace_no where a.cso_no = '$_REQUEST[cso_no]' and a.branch = '1' and b.pid= '$_REQUEST[pid]';");
    $_p = $con->getArray("SELECT FLOOR(ROUND(DATEDIFF(b.so_date,a.birthdate) / 364.25,2)) AS age, DATE_FORMAT(a.birthdate,'%M %d, %Y') AS bday, IF(a.gender='M','Male','Female') AS gender, IF(a.employer='','WALK-IN',a.employer) AS employer, a.cstat, b.so_date FROM pccmain.patient_info a LEFT JOIN lab_samples b ON a.patient_id = b.pid WHERE a.patient_id = '$_ihead[pid]' and b.so_no = '$_REQUEST[cso_no]' LIMIT 1;");

    list($_pap) = $con->getArray("select count(*) from lab_samples where `procedure` like '%pap%' and so_no = '$_REQUEST[cso_no]' and pid= '$_REQUEST[pid]';");
    list($_ecg) = $con->getArray("select count(*) from lab_samples where `procedure` like '%ecg%' and so_no = '$_REQUEST[cso_no]' and pid= '$_REQUEST[pid]';");

    switch($_p['cstat']) {
        case '1':
            $cstat = 'Single';
        break;
        case '2':
            $cstat = 'Married';
        break;
        case '3':
            $cstat = 'Legally Separated';
        break;
        case '4':
            $cstat = 'Widow/Widower';
        break;
        case '5':
            $cstat = 'Living-in with Partner';
        break;
        default:
            $cstat = 'Single';
        break;
    }

    $mpdf=new mPDF('win-1252','LETTER-H','','',5,5,5,5,3,3);
    $mpdf->use_embeddedfonts_1252 = true;    // false is default
    $mpdf->setAutoTopMargin='stretch';
    $mpdf->setAutoBottomMargin='stretch';
    $mpdf->use_kwt = true;
    $mpdf->SetProtection(array('print'));
    $mpdf->SetAuthor("Opon Medical Diagnostic Corporation");
    $mpdf->SetDisplayMode(40);

    $html = '<html>
                <head>
                    <title>Routing Slip</title>
                    <style>
                        body {
                            font-family: sans-serif;
                            font-size: 10pt;
                        }
                        .bord { border-bottom: 1px solid #000; }
                        .bold { font-weight: bold; }    
                    </style>
                </head>
                <body>
                    <table width="100%" cellpadding=0 cellspaing=0 align=center>
                        <tr>
                            <td align=center><img src="../images/pcc-logo-bkw.png" height=70 align=absmiddle></td>
                        </tr>
                        <tr>
                            <td style="color:#000000; padding-top: 5px;" valign=top align=center>
                                <span style="font-size: 10pt;"><b>'.$co['company_name'].'</b><br/>'.$co['company_address'].'<br/>Information: '.$co['info_num'].'<br/>Marketing: '.$co['mktg_num'].'<br/>Medical Records: '.$co['med_rec_num'].'</span>
                            </td>
                        </tr>
                    </table>
                    <table width="100%" cellpadding=0 cellspaing=0 align=center style="font-weight:bold; margin-top:10px; font-size: 12pt;">
                        <tr>
                            <td align=center>ANNUAL PHYSICAL EXAM (APE ROUTING SLIP)</td>
                        </tr>
                    </table>
                    <table width=90% cellpadding=0 cellspacing=0 align=center style="margin-top:10px;">
                        <tr>
                            <td></td>
                            <td></td>
                            <td class="bold">CONTROL NO.</td>
                            <td>:&nbsp;&nbsp;&nbsp;&nbsp;</td>
                        </tr>
                        <tr>
                            <td class="bold">NAME</td>
                            <td>:&nbsp;&nbsp;&nbsp;&nbsp;'.$_ihead['pname'].'</td>
                            <td class="bold">AGE</td>
                            <td>:&nbsp;&nbsp;&nbsp;&nbsp;'.$_p['age'].'</td>
                        </tr>
                        <tr>
                            <td class="bold">BIRTHDAY</td>
                            <td>:&nbsp;&nbsp;&nbsp;&nbsp;'.$_p['bday'].'</td>
                            <td class="bold">GENDER</td>
                            <td>:&nbsp;&nbsp;&nbsp;&nbsp;'.$_p['gender'].'</td>
                        </tr>
                        <tr>
                            <td class="bold">COMPANY</td>
                            <td>:&nbsp;&nbsp;&nbsp;&nbsp;'.$_ihead['customer_name'].'</td>
                            <td class="bold">CIVIL STATUS</td>
                            <td>:&nbsp;&nbsp;&nbsp;&nbsp;'.$cstat.'</td>
                        </tr>
                        <tr>
                            <td class="bold">PACKAGE</td>
                            <td>:&nbsp;&nbsp;&nbsp;&nbsp;'.$_ihead['description'].'</td>
                            <td></td>
                            <td></td>
                        </tr>
                    </table>
                     <table width=90% cellpadding=1 cellspacing=0 align=center>
                        <tr>
                            <td class="bold">EXACT TIME OF LAST MEAL _________________________________________</td>
                        </tr>
                    </table>

                    <table width=90% cellpadding=2 style="font-size: 11pt; margin-top: 10px;" align=center>
                       <tr>
                            <td width=7%>CBC</td>
                            <td width=40%>____________________________________________</td>';

                            if($_ecg > 0) {
                $html .=        '<td width=7%>ECG</td>
                                 <td width=40%>____________________________________________</td>';
                            } else {
                $html .=        '<td width=7%>&nbsp;</td>
                                 <td width=40%>____________________________________________</td>';
                            }
                        
                $html .=    '</tr>
                       <tr>
                            <td width=7%>UA</td>
                            <td width=40%>____________________________________________</td>';

                            if($_pap > 0) {
                $html .=        '<td width=7%>PAP</td>
                                 <td width=40%>____________________________________________</td>';
                            } else {
                $html .=        '<td width=7%>&nbsp;</td>
                                 <td width=40%>____________________________________________</td>';
                            }
                        
                $html .=    '</tr>
                       <tr>
                            <td width=7%>SE</td>
                            <td width=40%>____________________________________________</td>
                            <td width=7%>OTHER</td>
                            <td width=40%>____________________________________________</td>
                        </tr>
                       <tr>
                            <td width=7%>XRAY</td>
                            <td width=40%>____________________________________________</td>
                            <td width=7%>&nbsp;</td>
                            <td width=40%>____________________________________________</td>
                        </tr>
                       <tr>
                            <td width=7%>PE</td>
                            <td width=40%>____________________________________________</td>
                            <td width=7%>&nbsp;</td>
                            <td width=40%>____________________________________________</td>
                        </tr>
                    </table>
                    <table width=90% cellpadding=2 style="font-size: 10pt; margin-top: 10px; font-style:italic;" align=center>
                        <tr>
                            <td align=center>Please return this part once all exams are complete.</td>
                        </tr>
                        <tr>
                            <td align=center>***************************************************************************************</td>
                        </tr>
                    </table>

                </body>
            </html>';

 $mpdf->WriteHTML($html);
 $mpdf->Output();
 exit;
?>