<?php 
	
	session_start();
	require_once "handlers/_generics.php";
	
    $o = new _init;

    $a = $o->getArray("SELECT record_id AS id, LPAD(a.so_no,6,0) AS myso,DATE_FORMAT(a.so_date,'%m/%d/%Y') AS sodate, a.so_date, b.birthdate, LPAD(a.pid,6,0) AS mypid,CONCAT(b.lname,', ',b.fname,' ',b.mname) AS pname,IF(b.gender='M','Male','Female') AS gender, DATE_FORMAT(b.birthdate,'%m/%d/%Y') AS bday,a.code,a.procedure,sampletype,serialno,DATE_FORMAT(extractdate,'%m/%d/%Y') AS exday,TIME_FORMAT(extractime,'%h:%i %p') AS etime,extractby,a.location FROM lab_samples a  LEFT JOIN pccmain.patient_info b ON a.pid = b.patient_id WHERE a.record_id = '$_REQUEST[lid]';");
    $b = $o->getArray("select * from lab_cbcresult where serialno = '$a[serialno]';");

    if(count($b) == 0) {
        $b = $o->getArray("select * from lab_cbcresult_temp where serialno = '$a[serialno]';");
    }
?>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<title>Primecare Cebu Mobile System Ver. 1.0b</title>
	<link rel="icon" type="image/x-icon" href="favicon.ico" />
	<link href="ui-assets/themes/smoothness/jquery-ui.css" rel="stylesheet" type="text/css" />
	<link href="ui-assets/texteditor/jquery-te-1.4.0.css" rel="stylesheet" type="text/css" />
	<link href="style/style.css" rel="stylesheet" type="text/css" />
	<script language="javascript" src="ui-assets/jquery/jquery-1.12.3.js"></script>
	<script language="javascript" src="ui-assets/themes/smoothness/jquery-ui.js"></script>
	<script language="javascript" src="ui-assets/texteditor/jquery-te-1.4.0.min.js"></script>
	<script language="javascript" src="js/main.js?sid=<?php echo uniqid(); ?>"></script>
    <script>
        $(function() { $("#cbc_date").datepicker(); });

        $(document).on('keydown', 'input[pattern]', function(e){
            var input = $(this);
            var oldVal = input.val();
            var regex = new RegExp(input.attr('pattern'), 'g');

            setTimeout(function(){
                var newVal = input.val();
                if(!regex.test(newVal)){
                input.val(oldVal); 
                }
            }, 1);
        });
    </script>
</head>
<body>
    <form name="frmCBCResult" id="frmCBCResult"> 
        <table width=100% cellpadding=0 cellspacing=0 valign=top>
         <tr>
             <td width=35% valign=top>
                <table width=100% cellspacing=0 cellpadding=0><tr><td width=100% class=gridHead align=center>PATIENT & ORDER INFORMATION</td></tr></table>
                <table width=100% cellpadding=0 cellspacing=0 style="border: 1px solid #cdcdcd; padding: 10px; margin-bottom: 5px;">
                    <tr><td height=3></td></tr>
                    <tr>
                        <td align="left" width="35%"  class="bareBold" style="padding-left: 15px;">SO #&nbsp;:</td>
                        <td align=left>
                            <input class="gridInput" style="width:100%;" type=text name="cbc_sono" id="cbc_sono" value="<?php echo $a['myso']; ?>">
                        </td>				
                    </tr>
                    <tr><td height=3></td></tr>
                    <tr>
                        <td align="left" width="35%"  class="bareBold" style="padding-left: 15px;">Service Order Date&nbsp;:</td>
                        <td align=left>
                            <input class="gridInput" style="width:100%;" type=text name="cbc_sodate" id="cbc_sodate" value="<?php echo $a['sodate']; ?>">
                        </td>				
                    </tr>
                    <tr><td height=3></td></tr>
                    <tr>
                        <td align="left" width="35%" class="bareBold" style="padding-left: 15px;">Patient ID&nbsp;:</td>
                        <td align=left>
                            <input type="text" class="gridInput" style="width:100%;" name="cbc_pid" id="cbc_pid" value="<?php echo $a['mypid']; ?>">
                        </td>				
                    </tr>
                    <tr><td height=3></td></tr>
                    <tr>
                        <td align="left" width="35%"  class="bareBold" style="padding-left: 15px;">Result Date&nbsp;:</td>
                        <td align=left>
                            <input class="gridInput" style="width:100%;" type=text name="cbc_date" id="cbc_date" value="<?php if($rdate !='') { echo $rdate; } else { echo date('m/d/Y'); } ?>">
                        </td>				
                    </tr>
                    <tr><td height=3></td></tr>
                    <tr>
                        <td align="left" width="35%" class="bareBold" style="padding-left: 15px;">Patient Name&nbsp;:</td>
                        <td align=left>
                            <input type="text" class="gridInput" style="width:100%;" name="cbc_pname" id="cbc_pname" value="<?php echo $a['pname']; ?>">
                        </td>				
                    </tr>
                    <tr><td height=3></td></tr>

                    <tr>
                        <td align="left" width="35%" class="bareBold" style="padding-left: 15px;">Gender&nbsp;:</td>
                        <td align=left>
                            <input type="text" class="gridInput" style="width:100%;" name="cbc_gender" id="cbc_gender" value="<?php echo $a['gender']; ?>">
                        </td>				
                    </tr>
                    <tr><td height=3></td></tr>
                    <tr>
                        <td align="left" width="35%" class="bareBold" style="padding-left: 15px;">Birthdate&nbsp;:</td>
                        <td align=left>
                            <input type="text" class="gridInput" style="width:100%;" name="cbc_birthdate" id="cbc_birthdate" value="<?php echo $a['bday']; ?>">
                        </td>				
                    </tr>
                    <tr><td height=3></td></tr>
                    <tr>
                        <td align="left" width="35%" class="bareBold" style="padding-left: 15px;">Age&nbsp;:</td>
                        <td align=left>
                            <input type="text" class="gridInput" style="width:100%;" name="cbc_age" id="cbc_age" value="<?php echo $o->calculateAge($a['so_date'],$a['birthdate']); ?>">
                        </td>				
                    </tr>
                    <tr><td height=3></td></tr>
                    <tr>
                        <td align="left" width="35%" class="bareBold" style="padding-left: 15px;">Patient Status&nbsp;:</td>
                        <td align=left>
                            <input type="text" class="gridInput" style="width:100%;" name="cbc_patientstat" id="cbc_patientstat" value="<?php echo $a['patientstatus']; ?>">
                        </td>				
                    </tr>
                    <tr><td height=3></td></tr>
                    <tr>
                        <td align="left" width="35%" class="bareBold" style="padding-left: 15px;">Requesting Physician&nbsp;:</td>
                        <td align=left>
                            <input type="text" class="gridInput" style="width:100%;" name="cbc_physician" id="cbc_physician" value="<?php echo $a['physician']; ?>">
                        </td>				
                    </tr>
                    <tr><td height=3></td></tr>
                </table>
                <table width=100% cellspacing=0 cellpadding=0><tr><td width=100% class=gridHead align=center>SAMPLE DETAILS</td></tr></table>
                <table width=100% cellpadding=0 cellspacing=0 style="border: 1px solid #cdcdcd; padding: 10px;">
                    <tr>
                        <td align="left" width="35%" class="bareBold" style="padding-left: 15px;">Test or Procedure&nbsp;:</td>
                        <td align=left>
                            <input type="text" class="gridInput" style="width:100%;" name="cbc_procedure" id="cbc_procedure" value="<?php echo $a['procedure']; ?>">
                        </td>				
                    </tr>
                    <tr><td height=3></td></tr>
                    <tr>
                        <td align="left" width="35%" class="bareBold" style="padding-left: 15px;">Procedure Code&nbsp;:</td>
                        <td align=left>
                            <input type="text" class="gridInput" style="width:100%;" name="cbc_code" id="cbc_code" value="<?php echo $a['code']; ?>">
                        </td>				
                    </tr>
                    <tr><td height=3></td></tr>
                    <tr>
                        <td align="left" width="35%" class="bareBold" style="padding-left: 15px;">Specimen Type&nbsp;:</td>
                        <td align=left>
                            <select class="gridInput" style="width:100%;" name="cbc_spectype" id="cbc_spectype">
                                <?php
                                    $iun = $o->dbquery("select id,sample_type from options_sampletype;");
                                    while(list($aa,$ab) = $iun->fetch_array()) {
                                        echo "<option value='$aa'";
                                        if($aa == $a['sampletype']) { echo "selected"; }
                                       echo ">$ab</option>";
                                    }
                                ?>
                            </select>
                        </td>				
                    </tr>
                    <tr><td height=3></td></tr>
                    <tr>
                        <td align="left" width="35%" class="bareBold" style="padding-left: 15px;">Sample Serial No.&nbsp;:</td>
                        <td align=left>
                            <input type="text" class="gridInput" style="width:100%;" name="cbc_serialno" id="cbc_serialno" value="<?php echo $a['serialno']; ?>">
                        </td>				
                    </tr>
                    <tr><td height=3></td></tr>
                    <tr>
                        <td align="left" width="35%" class="bareBold" style="padding-left: 15px;">Date Extracted&nbsp;:</td>
                        <td align=left>
                            <input type="text" class="gridInput" style="width:100%;" name="cbc_extractdate" id="cbc_extractdate" value="<?php echo $a['exday']; ?>">
                        </td>				
                    </tr>
                    <tr><td height=3></td></tr>
                    <tr>
                        <td align="left" width="35%" class="bareBold" style="padding-left: 15px;">Time Extracted&nbsp;:</td>
                        <td align=left>
                
                            <input type="text" class="gridInput" style="width:100%;" name="cbc_extracttime" id="cbc_extracttime" value="<?php echo $a['etime']; ?>" readonly>

                        </td>				
                    </tr>
                    <tr><td height=3></td></tr>
                    <tr>
                        <td align="left" width="35%" class="bareBold" style="padding-left: 15px;">Extracted By&nbsp;:</td>
                        <td align=left>
                            <input type="text" class="gridInput" style="width:100%;" name="cbc_extractby" id="cbc_extractby" value="<?php echo $a['extractby']; ?>" readonly>
                        </td>				
                    </tr>
                    <tr><td height=3></td></tr>
                    <tr>
                        <td align="left" width="35%" class="bareBold" style="padding-left: 15px;">Phleb/Imaging Site&nbsp;:</td>
                        <td align=left>
                            <select class="gridInput" style="width:100%;" name="cbc_location" id="cbc_location">
                                <?php
                                    $iun = $o->dbquery("select id,location from lab_locations;");
                                    while(list($aa,$ab) = $iun->fetch_array()) {
                                        echo "<option value='$aa' ";
                                        if($aa == $a['location']) { echo "selected"; }
                                        echo ">$ab</option>";
                                    }
                                ?>
                            </select>
                        </td>				
                    </tr>
                </table>   
            </td>
            <td width=1%>&nbsp;</td>
            <td width=64% valign=top >
                 <table width=100% cellspacing=0 cellpadding=0><tr><td width=100% class=gridHead align=center>RESULT DETAILS</td></tr></table>
                 <table width=100% cellpadding=0 cellspacing=3 class="td_content">
                    
                 <tr>
                        <td align="left" width=25% class="bareBold" style="padding-left: 15px; font-weight: bold;"></td>
                        <td align="left" width=25% class="bareBold" style="padding-left: 15px; font-weight: bold;"></td>
                        <td align="center" width=25% class="bareBold" style="padding-left: 15px; font-weight: bold;">UNIT</td>
                        <td align="left" width=25% class="bareBold" style="padding-left: 15px; font-weight: bold;">REFERENCE RANGE</td>
                    </tr>


                    <tr>
                        <td align="left" width=25% class="bareBold" style="padding-left: 15px; font-weight: bold;">WBC&nbsp;:</td>
                        <td align=left width=20%>
                            <input type="text" class="gridInput" style="width:100%;" name="wbc" id="wbc" pattern="^\d*(\.\d{0,2})?$" value="<?php echo number_format($b['wbc'],2); ?>">
                        </td>
                        <td align="center" class="bareBold" style="padding-left: 15px;">10^9/L</td>	
                        <td align="left" class="bareBold" style="padding-left: 15px;">5.00-10.00</td>	
                    </tr>
      
                    
                    <tr>
                        <td align="left" class="bareBold" style="padding-left: 15px; font-weight: bold;" valign=top>RBC&nbsp;:</td>
                        <td align=left valign=top>
                            <input type="text" class="gridInput" style="width:100%;" name="rbc" id="rbc" pattern="^\d*(\.\d{0,2})?$" value="<?php echo number_format($b['rbc'],2); ?>">
                        </td>
                        <td align="center" class="bareBold" style="padding-left: 15px;">10^9/L</td>	
                        <td align="left" class="bareBold" style="padding-left: 15px;">2.00-7.00</td>	
                    </tr>

                    <tr>
                        <td align="left" class="bareBold" style="padding-left: 15px; font-weight: bold;">Hemoglobin&nbsp;:</td>
                        <td align=left>
                            <input type="text" class="gridInput" style="width:100%;" name="hemoglobin" id="hemoglobin" pattern="^\d*(\.\d{0,2})?$" value="<?php echo number_format($b['hemoglobin'],2); ?>">
                        </td>
                        <td align="center" class="bareBold" style="padding-left: 15px;">g/dL</td>	
                        <td align="left" class="bareBold" style="padding-left: 15px;">12.00-16.00</td>	
                    </tr>
                    <tr>
                        <td align="left" class="bareBold" style="padding-left: 15px; font-weight: bold;">Hematocrit&nbsp;:</td>
                        <td align=left>
                            <input type="text" class="gridInput" style="width:100%;" name="hematocrit" id="hematocrit" pattern="^\d*(\.\d{0,2})?$" value="<?php echo number_format($b['hematocrit'],2); ?>">
                        </td>
                        <td align="center" class="bareBold" style="padding-left: 15px;">%</td>	
                        <td align="left" class="bareBold" style="padding-left: 15px;">37.0-50.0</td>
                    </tr>
                    <tr>
                        <td align="left" width=25% class="bareBold" style="padding-left: 15px; font-weight: bold;">MCV&nbsp;:</td>
                        <td align=left width=20%>
                            <input type="text" class="gridInput" style="width:100%;" name="mcv" id="mcv" pattern="^\d*(\.\d{0,2})?$" value="<?php echo number_format($b['mcv'],2); ?>">
                        </td>
                        <td align="center" class="bareBold" style="padding-left: 15px;">fL</td>	
                        <td align="left" class="bareBold" style="padding-left: 15px;">80.0-110.0</td>
                    </tr>
                    <tr>
                        <td align="left" width=25% class="bareBold" style="padding-left: 15px; font-weight: bold;">MCH&nbsp;:</td>
                        <td align=left width=20%>
                            <input type="text" class="gridInput" style="width:100%;" name="mch" id="mch" pattern="^\d*(\.\d{0,2})?$" value="<?php echo number_format($b['mch'],2); ?>">
                        </td>
                        <td align="center" class="bareBold" style="padding-left: 15px;">g/dL</td>	
                        <td align="left" class="bareBold" style="padding-left: 15px;">28.0-32.0</td>
                    </tr>
                    <tr>
                        <td align="left" width=25% class="bareBold" style="padding-left: 15px; font-weight: bold;">MCHC&nbsp;:</td>
                        <td align=left width=20%>
                            <input type="text" class="gridInput" style="width:100%;" name="mchc" id="mchc" pattern="^\d*(\.\d{0,2})?$" value="<?php echo number_format($b['mchc'],2); ?>">
                        </td>
                        <td align="center" class="bareBold" style="padding-left: 15px;">g/dL</td>	
                        <td align="left" class="bareBold" style="padding-left: 15px;">32.0-37.0</td>
                    </tr>
                    <tr><td height=5>&nbsp;</td></tr>
                    <tr>
                        <td align="left" colspan=3 class="bareBold" style="padding-left: 15px; font-weight: bold;">Differential Count&nbsp;:</td>
                    </tr>
                    <tr>
                        <td align="left" class="bareBold" style="padding-left: 35px;">Neutrophils&nbsp;:</td>
                        <td align=left>
                            <input type="text" class="gridInput" style="width:100%;" name="neutrophils" id="neutrophils" pattern="^\d*(\.\d{0,2})?$" value="<?php echo number_format($b['neutrophils'],2); ?>">
                        </td>
                        <td align="center" class="bareBold" style="padding-left: 15px;">%</td>	
                        <td align="left" class="bareBold" style="padding-left: 15px;">51.0-67.0</td>	
                    </tr>
                    <tr>
                        <td align="left" class="bareBold" style="padding-left: 35px;">Lymphocytes&nbsp;:</td>
                        <td align=left>
                            <input type="text" class="gridInput" style="width:100%;" name="lymphocytes" id="lymphocytes" pattern="^\d*(\.\d{0,2})?$" value="<?php echo number_format($b['lymphocytes'],2); ?>">
                        </td>
                        <td align="center" class="bareBold" style="padding-left: 15px;">%</td>	
                        <td align="left" class="bareBold" style="padding-left: 15px;">21.0-35.0</td>
                    </tr>
                    <tr>
                        <td align="left" class="bareBold" style="padding-left: 35px;">Monocytes&nbsp;:</td>
                        <td align=left>
                            <input type="text" class="gridInput" style="width:100%;" name="monocytes" id="monocytes" pattern="^\d*(\.\d{0,2})?$" value="<?php echo number_format($b['monocytes'],2); ?>">
                        </td>
                        <td align="center" class="bareBold" style="padding-left: 15px;">%</td>	
                        <td align="left" class="bareBold" style="padding-left: 15px;">2.0-5.0</td>
                    </tr>
                    <tr>
                        <td align="left" class="bareBold" style="padding-left: 35px;">Eosinophils&nbsp;:</td>
                        <td align=left>
                            <input type="text" class="gridInput" style="width:100%;" name="eosinophils" id="eosinophils" pattern="^\d*(\.\d{0,2})?$" value="<?php echo number_format($b['eosinophils'],2); ?>">
                        </td>
                        <td align="center" class="bareBold" style="padding-left: 15px;">%</td>	
                        <td align="left" class="bareBold" style="padding-left: 15px;">1.0-4.0</td>
                    </tr>
                    <tr>
                        <td align="left" class="bareBold" style="padding-left: 35px;">Basophils&nbsp;:</td>
                        <td align=left>
                            <input type="text" class="gridInput" style="width:100%;" name="basophils" id="basophils" pattern="^\d*(\.\d{0,2})?$" value="<?php echo number_format($b['basophils'],2); ?>">
                        </td>
                        <td align="center" class="bareBold" style="padding-left: 15px;">%</td>	
                        <td align="left" class="bareBold" style="padding-left: 15px;">0.0-1.0</td>
                    </tr>
                    <tr>
                        <td align="left" width=25% class="bareBold" style="padding-left: 15px; font-weight: bold;">Platelet Count&nbsp;:</td>
                        <td align=left width=20%>
                            <input type="text" class="gridInput" style="width:100%;" name="platelate" id="platelate" pattern="^\d*(\.\d{0,0})?$" value="<?php echo number_format($b['platelate']); ?>">
                        </td>
                        <td align="center" class="bareBold" style="padding-left: 15px;">10^9/L</td>	
                        <td align="left" class="bareBold" style="padding-left: 15px;">140-440</td>
                    </tr>
                    <tr>
                        <td align="left" width=25% class="bareBold" style="padding-left: 15px; font-weight: bold;" valign=top>Remarks&nbsp;:</td>
                        <td align=left width=75% colspan=3>
                            <textarea name="remarks" id="remarks" style="width: 90%;" rows=3><?php echo $b['remarks']; ?></textarea>
                        </td>
                    </tr>
                    <tr>
                        <td align="left" width=25% class="bareBold" style="padding-left: 15px; font-weight: bold;">Result Summary&nbsp;:</td>
                        <td align=left width=20%>
                            <select name="result_stat" id="result_stat" style="width: 100%;" class="gridInput">
                                <option value='Y' <?php if($b['result_stat'] == 'Y') { echo "selected"; } ?>> Within Normal Values </option>
                                <option value='N' <?php if($b['result_stat'] == 'N') { echo "selected"; } ?>> With Findings </option>

                            </select>
                        </td>
                        <td align="center" class="bareBold" style="padding-left: 15px;"></td>	
                        <td align="left" class="bareBold" style="padding-left: 15px;"></td>
                    </tr>
                    <tr><td height=45>&nbsp;</td></tr>
                </table>
            </td>
        </tr>
    </table>              
</form>
</body>
</html>