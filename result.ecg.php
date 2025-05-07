<?php 
	
	session_start();
	require_once "handlers/_generics.php";
	
    $o = new _init;

    $a = $o->getArray("SELECT record_id AS id, LPAD(a.so_no,6,0) AS myso,DATE_FORMAT(a.so_date,'%m/%d/%Y') AS sodate, a.so_date, b.birthdate, LPAD(a.pid,6,0) AS mypid,CONCAT(b.lname,', ',b.fname,' ',b.mname) AS pname,IF(b.gender='M','Male','Female') AS gender, DATE_FORMAT(b.birthdate,'%m/%d/%Y') AS bday,YEAR(a.so_date) - YEAR(b.birthdate) AS age,a.code,a.procedure,sampletype,serialno,DATE_FORMAT(extractdate,'%m/%d/%Y') AS exday,TIME_FORMAT(extractime,'%h:%i %p') AS etime,extractby,a.location,a.machine FROM lab_samples a  LEFT JOIN pccmain.patient_info b ON a.pid = b.patient_id WHERE a.record_id = '$_REQUEST[lid]';");
    list($file) = $o->getArray("select file_path from lab_samples where so_no = '$a[myso]' and branch = '1' and `code` = '$a[code]' and serialno = '$a[serialno]';");
    $b = $o->getArray("select *, DATE_FORMAT(prev_ecg_date,'%m/%d/%Y') AS prev_ecg_date from lab_ecgresult where so_no = '$a[myso]' and branch = '1' and `code` = '$a[code]' and serialno = '$a[serialno]';");

    list($verified) = $o->getArray("select verified from lab_ecgresult where so_no = '$a[myso]' and branch = '1' and code = '$a[code]' and serialno = '$a[serialno]';");
?>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<title>Prime Care Cebu, Inc.</title>
	<link rel="icon" type="image/x-icon" href="favicon.ico" />
	<link href="ui-assets/themes/smoothness/jquery-ui.css" rel="stylesheet" type="text/css" />
    <link href="ui-assets/texteditor/jquery-te-1.4.0_edited.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" type="text/css" href="ui-assets/datatables/css/jquery.dataTables.css">
	<link href="style/style.css" rel="stylesheet" type="text/css" />
	<script language="javascript" src="ui-assets/jquery/jquery-1.12.3.js"></script>
	<script language="javascript" src="ui-assets/themes/smoothness/jquery-ui.js"></script>
    <script language="javascript" src="ui-assets/texteditor/jquery-te-1.4.0.min.js"></script>
    <script type="text/javascript" charset="utf8" src="ui-assets/datatables/js/jquery.dataTables.js"></script>
	<script type="text/javascript" charset="utf8" src="ui-assets/datatables/js/dataTables.jqueryui.js"></script>
	<script type="text/javascript" charset="utf8" src="ui-assets/datatables/js/dataTables.select.js"></script>
	
	
    <script>
        $(function() { 
            $("#ecg_date, #prev_ecg_date").datepicker();
            $("#ecg_impression").jqte();

            var patientStat = [
                "OLD",
                "NEW",
            ];

            var impressionSelection = [
                "SINUS RHYTHM WITHIN NORMAL LIMITS",
            ];

            $("#ecg_stat").autocomplete({
                 source: patientStat,
                 minLength: 0
            }).focus(function() {
                $(this).data("uiAutocomplete").search($(this).val());
            }); 

            $("#ecg_impression").autocomplete({
                 source: impressionSelection,
                 minLength: 0
            }).focus(function() {
                $(this).data("uiAutocomplete").search($(this).val());
            }); 

            var myTable = $('#itemlist').DataTable({
                "keys": true,
                "scrollY":  "210px",
                "select":	'single',
                "pagingType": "full_numbers",
                "bProcessing": true,
                "responsive": true,
                "scroller": true,
                "aoColumnDefs": [
                    { "className": "dt-body-center", "targets": [2]},
                    { "targets": [0], "visible": false }
                ]
            });

            $('#itemlist tbody').on('dblclick', 'tr', function () {
                parent.writeECGResult(lid,code); 
            });

            <?php 
                if($verified == 'Y') {
				    echo "$(\"#frmECGResult :input:not([name=setPrint], [name=setClose], [name=setUnpublish])\").prop('disabled',true);";
			    } 
            ?>

        });

        function saveResult() {
            if(confirm("Are you sure you want save this data?") == true) {
                var dataString = $('#frmECGResult').serialize();
                    dataString = "mod=saveECGResult&" + dataString;
                $.ajax({
                    type: "POST",
                    url: "src/sjerp.php",
                    data: dataString,
                    success: function() {
                        alert("Result Successfully Saved!");
                        dis.dialog("close");
                        $("#frmECGResult").trigger("reset");
                    }
                });
            }   
        }

        function printResult() {
            var so_no = $('#ecg_sono').val();
            var code = $('#ecg_code').val();
            var serialno = $('#ecg_serialno').val();
             
            parent.printECGResult(so_no,code,serialno);

        }

        function publishResult() {
            $.post("src/sjerp.php", { 
                mod: "validateECGResult", 
                ecg_sono: $("#ecg_sono").val(), 
                ecg_code: $("#ecg_code").val(), 
                ecg_serialno: $("#ecg_serialno").val(), 
                sid: Math.random() }, function() {
                window.location.reload();
            });
        }

        function unpublishResult() {
            $.post("src/sjerp.php", { 
                mod: "invalidateECGResult", 
                ecg_sono: $("#ecg_sono").val(), 
                ecg_code: $("#ecg_code").val(), 
                ecg_serialno: $("#ecg_serialno").val(), 
                sid: Math.random() }, function() {
                window.location.reload();
            });
        }
    </script>
    <style>
		.dataTables_wrapper {
			/* display: inline-block; */
			font-size: 11px;
			width: 100%; 
		}
		
		table.dataTable tr.odd { background-color: #f5f5f5;  }
		table.dataTable tr.even { background-color: white; }
		.dataTables_filter input { width: 250px; }
	</style>
</head>
<body>

<form name="frmECGResult" id="frmECGResult"> 
    <table width=100% cellpadding=0 cellspacing=0 valign=top>
        <tr>
            <td width=25% valign=top>
                <table width=100% cellspacing=0 cellpadding=0><tr><td width=100% class=gridHead align=center>PATIENT & ORDER INFORMATION</td></tr></table>
                <table width=100% cellpadding=0 cellspacing=0 style="border: 1px solid #cdcdcd; padding: 10px; margin-bottom: 5px;">
                    <tr><td height=3></td></tr>
                    <tr>
                        <td align="left" width="35%"  class="bareBold" style="padding-right: 15px;">SO No.&nbsp;:</td>
                        <td align=left>
                            <input class="gridInput" style="width:100%;" type=text name="ecg_sono" id="ecg_sono" value="<?php echo $a['myso']; ?>">
                        </td>				
                    </tr>
                    <tr><td height=3></td></tr>
                    <tr>
                        <td align="left" width="35%"  class="bareBold" style="padding-right: 15px;">SO Date&nbsp;:</td>
                        <td align=left>
                            <input class="gridInput" style="width:100%;" type=text name="ecg_sodate" id="ecg_sodate" value="<?php echo $a['sodate']; ?>">
                        </td>				
                    </tr>
                    <tr><td height=3></td></tr>
                    <tr>
                        <td align="left" width="35%" class="bareBold" style="padding-right: 15px;">Patient ID&nbsp;:</td>
                        <td align=left>
                            <input type="text" class="gridInput" style="width:100%;" name="ecg_pid" id="ecg_pid" value="<?php echo $a['mypid']; ?>">
                        </td>				
                    </tr>
                    <tr><td height=3></td></tr>
                    <input type="hidden" name="ecg_code" id="ecg_code" value="<?php echo $a['code']; ?>">
                    <input type="hidden" name="ecg_serialno" id="ecg_serialno" value="<?php echo $a['serialno']; ?>">
                    <input type="hidden" name="ecg_procedure" id="ecg_procedure" value="<?php echo $a['procedure']; ?>">
                    <input type="hidden" name="ecg_sampletype" id="ecg_sampletype" value="<?php echo $a['sampletype']; ?>">
                    <tr>
                        <td align="left" width="35%"  class="bareBold" style="padding-right: 15px;">Result Date&nbsp;:</td>
                        <td align=left>
                            <input class="gridInput" style="width:100%;" type=text name="ecg_date" id="ecg_date" value="<?php if($rdate !='') { echo $rdate; } else { echo $a['exday']; } ?>">
                        </td>				
                    </tr>
                    <tr><td height=3></td></tr>
                    <tr>
                        <td align="left" width="35%" class="bareBold" style="padding-right: 15px;">Patient Name&nbsp;:</td>
                        <td align=left>
                            <input type="text" class="gridInput" style="width:100%;" name="ecg_pname" id="ecg_pname" value="<?php echo $a['pname']; ?>">
                        </td>				
                    </tr>
                    <tr><td height=3></td></tr>

                    <tr>
                        <td align="left" width="35%" class="bareBold" style="padding-right: 15px;">Gender&nbsp;:</td>
                        <td align=left>
                            <input type="text" class="gridInput" style="width:100%;" name="ecg_gender" id="ecg_gender" value="<?php echo $a['gender']; ?>">
                        </td>				
                    </tr>
                    <tr><td height=3></td></tr>
                    <tr>
                        <td align="left" width="35%" class="bareBold" style="padding-right: 15px;">Birthdate&nbsp;:</td>
                        <td align=left>
                            <input type="text" class="gridInput" style="width:100%;" name="ecg_birthdate" id="ecg_birthdate" value="<?php echo $a['bday']; ?>">
                        </td>				
                    </tr>
                    <tr><td height=3></td></tr>
                    <tr>
                        <td align="left" width="35%" class="bareBold" style="padding-right: 15px;">Patient Status&nbsp;:</td>
                        <td align=left>
                            <input type="text" class="gridInput" style="width:100%;" name="ecg_patientstat" id="ecg_patientstat" value="A.P.E">
                        </td>				
                    </tr>
                    <tr><td height=3></td></tr>
                    <tr>
                        <td align="left" width="35%" class="bareBold" style="padding-right: 15px;">Occupation&nbsp;:</td>
                        <td align=left>
                            <input type="text" class="gridInput" style="width:100%;" name="ecg_occupation" id="ecg_occupation" value="<?php echo $b['occupation']; ?>">
                        </td>				
                    </tr>
                    <tr><td height=3></td></tr>
                    <tr>
                        <td align="left" width="35%" class="bareBold" style="padding-right: 15px;">Consultant&nbsp;:</td>
                        <td align=left>
                           <select name="ecg_consultant" id="ecg_consultant" class="gridInput" style="width: 100%; font-size: 11px;">
                                <?php
                                    $query = $o->dbquery("SELECT id, fullname FROM options_doctors WHERE specialization = 'CARDIOLOGIST' ORDER BY fullname;");
                                    while($d = $query->fetch_array()) {
                                        echo "<option value='$d[0]' ";
                                        if($b['consultant'] == $d[0]) { echo "selected"; }
                                        echo ">$d[1]</option>";
                                    }
                                ?>
                            </select>
                        </td>				
                    </tr>
                    <tr><td height=3></td></tr>
                    <tr>
                        <td align="left" width="35%" class="bareBold" style="padding-right: 15px;">New or Existing Patient?&nbsp;:</td>
                        <td align=left>
                            <input type="text" class="gridInput" style="width:100%;" name="ecg_stat" id="ecg_stat" value="<?php echo $b['stat']; ?>">
                        </td>				
                    </tr>
                    <tr><td height=3></td></tr>
                    <tr>
                        <td align="left" width="35%" class="bareBold" style="padding-right: 15px;">Previous ECG Date&nbsp;:</td>
                        <td align=left>
                            <input type="text" class="gridInput" style="width:100%;" name="prev_ecg_date" id="prev_ecg_date" value="<?php echo $b['prev_ecg_date']; ?>">
                        </td>				
                    </tr>
                    <tr><td height=3></td></tr>
                    <tr>
                        <td align="left" width=35% class="bareBold" style="padding-right: 15px;" valign=top>Diagnosis&nbsp;:</td>
                        <td align=left width=75% colspan=3>
                            <textarea name="diagnosis" id="diagnosis" style="width: 100%;" rows=6><?php echo $b['diagnosis']; ?></textarea>
                        </td>
                    </tr>
                    <tr><td height=3></td></tr>
                    <tr>
                        <td align="left" width=35% class="bareBold" style="padding-right: 15px;" valign=top>Significant Medication&nbsp;:</td>
                        <td align=left width=75% colspan=3>
                            <textarea name="medication" id="medication" style="width: 100%;" rows=6><?php echo $b['medication']; ?></textarea>
                        </td>
                    </tr>
                    <tr><td height=8></td></tr>
                </table>
            </td>
            <td width=30% valign=top style="padding-left:5px;">
                <table width=100% cellspacing=0 cellpadding=0><tr><td width=100% class=gridHead align=center>ECG FINDINGS</td></tr></table>
                <table width=100% cellpadding=0 cellspacing=0 style="border: 1px solid #cdcdcd; padding: 10px; margin-bottom: 5px;">
                    <tr><td height=3></td></tr>
                    <tr>
                        <td align="left" width="35%"  class="bareBold" style="padding-right: 15px;">RATE&nbsp;:</td>
                        <td align=left>
                            <input class="gridInput" style="width:100%;" type=text name="ecg_rate" id="ecg_rate" value="<?php echo $b['rate']; ?>">
                        </td>				
                    </tr>
                    <tr><td height=3></td></tr>
                    <tr>
                        <td align="left" width="35%"  class="bareBold" style="padding-right: 15px;">RHYTHM&nbsp;:</td>
                        <td align=left>
                            <input class="gridInput" style="width:100%;" type=text name="ecg_rhythm" id="ecg_rhythm" value="<?php echo $b['rhythm']; ?>">
                        </td>				
                    </tr>
                    <tr><td height=3></td></tr>
                    <tr>
                        <td align="left" width="35%"  class="bareBold" style="padding-right: 15px;">P-R INTERVAL&nbsp;:</td>
                        <td align=left>
                            <input class="gridInput" style="width:100%;" type=text name="ecg_interval" id="ecg_interval" value="<?php echo $b['printerval']; ?>">
                        </td>				
                    </tr>
                    <tr><td height=3></td></tr>
                    <tr>
                        <td align="left" width="35%"  class="bareBold" style="padding-right: 15px;">QRS COMPLEX&nbsp;:</td>
                        <td align=left>
                            <input class="gridInput" style="width:100%;" type=text name="ecg_qrscomplex" id="ecg_qrscomplex" value="<?php echo $b['qrscomplex']; ?>">
                        </td>				
                    </tr>
                    <tr><td height=3></td></tr>
                    <tr>
                        <td align="left" width="35%"  class="bareBold" style="padding-right: 15px;">QTC&nbsp;:</td>
                        <td align=left>
                            <input class="gridInput" style="width:100%;" type=text name="ecg_qtc" id="ecg_qtc" value="<?php echo $b['qtc']; ?>">
                        </td>				
                    </tr>
                    <tr><td height=3></td></tr>
                    <tr>
                        <td align="left" width="35%"  class="bareBold" style="padding-right: 15px;">QTI&nbsp;:</td>
                        <td align=left>
                            <input class="gridInput" style="width:100%;" type=text name="ecg_qti" id="ecg_qti" value="<?php echo $b['qti']; ?>">
                        </td>				
                    </tr>
                    <tr><td height=3></td></tr>
                    <tr>
                        <td align="left" width="35%"  class="bareBold" style="padding-right: 15px;">AXIS&nbsp;:</td>
                        <td align=left>
                            <input class="gridInput" style="width:100%;" type=text name="ecg_axis" id="ecg_axis" value="<?php echo $b['axis']; ?>">
                        </td>				
                    </tr>
                    <tr><td height=3></td></tr>
                </table>
                <table width=100% cellspacing=0 cellpadding=0><tr><td width=100% class=gridHead align=center>ECG DIAGNOSIS</td></tr></table>
                <table width=100% height=20% cellpadding=0 cellspacing=0 style="border: 1px solid #cdcdcd; padding: 10px;">
                    <tr>
                        <td width=100%>
                            <textarea name="ecg_impression" id="ecg_impression" style="height:10%" ><?php echo html_entity_decode($b['impression']); ?></textarea><br/>
                        </td>				
                    </tr>
                </table>   
            </td>
            <td width=45% valign=top style="padding-left:5px;">  
                <iframe  style="border-style: none;" src="<?php echo $file ?>" width="100%" height="480px"></iframe>
            </td>
        </tr>
    </table>
    <table width=100% style="margin-top: 2px; border-top: 1px solid #e9e9e9;">
        <td align=right style="padding-top: 5px;">
        <?php if($verified != 'Y') { ?>

            <button type=button class="ui-button ui-widget ui-corner-all" onClick="saveResult();">
                <span class="ui-icon ui-icon-disk"></span> Save Changes Made
            </button>
            <button type=button class="ui-button ui-widget ui-corner-all" onClick="publishResult();">
                <span class="ui-icon ui-icon-check"></span> Publish Result
            </button>
        <?php } else { ?>
            <button type=button class="ui-button ui-widget ui-corner-all" name="setUnpublish" id="setUnpublish" onClick="unpublishResult();">
                    <span class="ui-icon ui-icon-cancel"></span> Unpublish Result
                </button>
        <?php } ?>
            <button type=button name="setPrint" id="setPrint" class="ui-button ui-widget ui-corner-all" onClick="printResult();">
                <span class="ui-icon ui-icon-print"></span> Print Result
            </button>
            <button type=button name="setClose" id="setClose" class="ui-button ui-widget ui-corner-all" onClick="parent.closeDialog('#ecgResult');">
                <span class="ui-icon ui-icon-closeThick"></span> Close
            </button>                      
        </td>
    </table>
</form>
<form name="loadTemplate" id="loadTemplate" action="result.ecg.php" method="_GET">
    <input type="hidden" name="lid" id="lid" value="<?php echo $_REQUEST['lid']; ?>">
    <input type="hidden" name="tid" id="tid" value="">
    <input type="hidden" name="consultant" id="consultant">
</form>
<div name="resultTemplates" id="resultTemplates" style="display: none;">
    <table width=100% id="itemlist" style="font-size:11px;">
        <thead>
            <tr>
                <th></th>
                <th width=65>Template Title</th>
                <th width=20%>Type</th>
                <th width=25%>Radiologist</th>
            </tr>
        </thead>
        <tbody>
            <?php
                $i = $o->dbquery("SELECT a.id, title, if(xray_type=1,'Upper Extremities','Lower Extremities') as xray_type, b.fullname FROM xray_templates a left join options_doctors b on a.template_owner = b.id ORDER BY b.fullname, title;");
                while($tdetails = $i->fetch_array()) {
                    echo "<tr>
                        <td>$tdetails[0]</td>
                        <td>$tdetails[1]</td>
                        <td>$tdetails[2]</td>
                        <td>$tdetails[3]</td>
                    </tr>";
                }
           ?>
        </tbody>
    </table>                           
</div>

</body>
</html>