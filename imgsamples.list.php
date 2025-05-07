<?php
	session_start();
	include("handlers/_generics.php");
	$o = new _init;
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>OMDC Prime Medical Diagnostics Corp.</title>
<link href="ui-assets/themes/smoothness/jquery-ui.css" rel="stylesheet" type="text/css" />
<link href="style/style.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="ui-assets/datatables/css/jquery.dataTables.css">
<link rel="stylesheet" type="text/css" href="ui-assets/keytable/css/keyTable.jqueryui.css">
<script type="text/javascript" charset="utf8" src="ui-assets/jquery/jquery-1.12.3.js"></script>
<script type="text/javascript" charset="utf8" src="ui-assets/themes/smoothness/jquery-ui.js"></script>
<script type="text/javascript" charset="utf8" src="ui-assets/datatables/js/jquery.dataTables.js"></script>
<script type="text/javascript" charset="utf8" src="ui-assets/datatables/js/dataTables.jqueryui.js"></script>
<script type="text/javascript" charset="utf8" src="ui-assets/datatables/js/dataTables.select.js"></script>
<script type="text/javascript" charset="utf8" src="ui-assets/datatables/js/page.jumpToData().js"></script>
<script type="text/javascript" charset="utf8" src="ui-assets/keytable/js/dataTables.keyTable.min.js"></script>
<script>

	$(document).ready(function() {

			$("#dtf").datepicker();
            $("#dt2").datepicker(); 


		var myTable = $('#itemlist').DataTable({
			"keys": true,
			"scrollY":  "310px",
			"select":	'single',
			"pagingType": "full_numbers",
			"pageLength": 50,
			"bProcessing": true,
			"responsive": true,
			"sAjaxSource": "data/imgsamples.php",
			"scroller": true,
			<?php if($_GET['code'] != 'undefined') { ?>
				"initComplete": function() {
					this.api().page.jumpToData("<?php echo $_GET['code']; ?>",1);
				},
			<?php } ?>
			"aoColumns": [
			  { mData: 'id' } ,
			  { mData: 'sono' } ,
			  { mData: 'sodate' },
			  { mData: 'pname' },
			  { mData: 'age' },
			  { mData: 'gender' },
			  { mData: 'code' }, 
			  { mData: 'procedure' },
			  { mData: 'sample_type' },
              { mData: 'serialno' },
			  { mData: 'tstamp' },
              { mData: 'status' },
			  { mData: 'ostat' },
			  { mData: 'so_date' },
			  { mData: 'birthdate' }

			],
			"aoColumnDefs": [
			    { "className": "dt-body-center", "targets": [1,2,4,5,6,8,9,10]},
				{ "className": "dt-body-left", "targets": [7,11]},
			    { "targets": [0,12,13,14], "visible": false }
            ]
		});

		$('#itemlist tbody').on('dblclick', 'tr', function () {
			var data = myTable.row(this).data();

			var msg ='';

			if(data['ostat'] == '2') { msg = "It appears this sample has already been marked as \"Rejected\"!"; }
			if(data['ostat'] == '4') { msg = "It appears that a result is already available for the sample."; }

			if(msg != '') {
				parent.sendErrorMessage(msg);
			} else {
				parent.writeResult(data['id'],data['code']);
			}

		});

	});
	
	function refreshList() {
		$('#itemlist').DataTable().ajax.url("data/imgsamples.php").load();
	}

	function writeResult() {
		var table = $("#itemlist").DataTable();		
		var lid; var stat;
	   	$.each(table.rows('.selected').data(), function() {
		    lid = this["id"];
		    stat = this['ostat'];
			code = this['code'];
	   	});

		if(!lid) {
			parent.sendErrorMessage("- Unable to continue as it appears you have not selected any record from the given list yet...");
		} else {
			var msg ='';

			if(stat == '2') { msg = "It appears this radiograph has already been marked as \"Rejected\"!"; }
			if(code == 'O001') { 
				if(stat == '1') { msg = "It appears you have not attached file yet...";
				} 
			}

			if(msg != '') {
				parent.sendErrorMessage(msg);
			} else {
				if(code == 'O001') {
					parent.writeECGResult(lid,code);
				}else {
				parent.writeImagingResult(lid,code);
				}
			}
		}

	}

	function printResult() {
		var table = $("#itemlist").DataTable();		
	
	   	$.each(table.rows('.selected').data(), function() {
		    serialno = this["serialno"];
			code = this['code'];
			so_no = this['sono'];
			status = this['ostat'];
	   	});

		if(serialno == '') {
			parent.sendErrorMessage("Cannot print selected record as it appears result isn't available or you didn't select any records from the give list yet.");
		} else {

			if(status == '3' || status == '4') {
				parent.printResult(code,so_no,serialno) 
				
			} else {
				parent.sendErrorMessage("- It appears that a result isn't availale yet for the selected record.");
			}
		}
	}

	function printBarcode() {
		var table = $("#itemlist").DataTable();		
		var lid; var stat;
	   	$.each(table.rows('.selected').data(), function() {
		    serialno = this["serialno"];

	   	});

		if(!serialno) {
			parent.sendErrorMessage("- It appears you have not selected any record yet for barcode printing...");
		} else {
			var msg ='';

			if(msg != '') {
				parent.sendErrorMessage(msg);
			} else {
				parent.printBarcode(serialno);
			}
		}

	}

	function attachFile() {

		var table = $("#itemlist").DataTable();		
		var so;
		var code;
		var sn;

		$.each(table.rows('.selected').data(), function() {
			so = this["sono"];
			code = this['code'];
			sn = this['serialno'];
		});

		if(!so) {
			parent.sendErrorMessage("Please select a result to associate this file.");
		} else {
			$("#frmAttachFile").trigger("reset");
			var dis = $("#attachFile").dialog({
				title: "Attach File",
				width: 480,
				resizeable: false,
				modal: true,
				buttons: [
					{
						text: "Attach File",
						icons: { primary: "ui-icon-check" },
						click: function() {
							var msg = "";
				
							if($("#att_title").val() == "") { msg = msg + "Please Indicate File TItle<br/>"; }
							if($("#att_file").val() == "") { msg = msg + "Invlaid File.<br/>"; }
						
							document.getElementById("att_sono").value = so;
							document.getElementById("att_code").value = code;
							document.getElementById("att_serialno").value = sn;
							
							if(msg!="") {
								parent.sendErrorMessage(msg);
							} else {

								$.ajax({
									type: "POST",
									url: "src/sjerp.php",
									data: new FormData($('#frmAttachFile')[0]),
									cache: false,
									contentType: false,
									processData: false,
									success: function() {
										alert("File Successfully Successfully Saved!");
										parent.showImgSamples();
									}
								});
							}

						}
							
					},
					{
						text: "Close",
						icons: { primary: "ui-icon-closethick" },
						click: function() { $(this).dialog("close"); $("#frmAttachFile").trigger("reset"); }
					}
				]
			});	
		}
	}

	function printByBatch() {
		var disMessage = $("#printBatch").dialog({ 
			title: "Print X-Ray Batch Result",
			width: "500",
			modal: true,
			resizeable: false,
			buttons: [
					{
						icons: { primary: "ui-icon-print" },
						text: "Print Results",
						click: function() { 
							window.open("print/result.xray.batch.php?so_no="+$("#mobileSoNo").val()+"&dtf="+$("#dtf").val()+"&dt2="+$("#dt2").val()+"&type="+$("#xray_type").val()+"&sort="+$("#printSort").val()+"&sid="+Math.random()+"&sid="+Math.random()+"","Batch Result","location=1,status=1,scrollbars=1,width=640,height=720");
						}
					},
					{
						text: "Close",
						icons: { primary: "ui-icon-closethick" },
						click: function() {
							$(this).dialog("close");
						}
					}
			]
		});

	}

	function rejectSample() {
		var table = $("#itemlist").DataTable();		
		var status;
		var px;
		var sn;

		$.each(table.rows('.selected').data(), function() {
			sn = this['serialno'];
			px = this['pname']
			status = this['status'];
		});

		if(status == 'Result Validated') {
			parent.sendErrorMessage("Unable to continue as it appears that a result for this request has already been validated!");
		} else {

			if(sn == '') {
				parent.sendErrorMessage("You have yet to select w/c request to cancel.");
			} else {

				$("#rejectSerialNo").val(sn);
				$("#rejectPname").val(px);
				var cancelMessage = $("#reject").dialog({ 
					title: "Reject or Cancel Request",
					width: "500",
					modal: true,
					resizeable: false,
					buttons: [
							{
								icons: { primary: "ui-icon-cancel" },
								text: "Cancel/Reject Request",
								click: function() { 
									if($("#rejectRemarks").val() != '') {
										// if(confirm("Are you sure you want to Reject/Cancel this request") == true) {
											$.post("src/sjerp.php", { mod: "rejectXrayRequest", sn: $("#rejectSerialNo").val(), remarks: $("#rejectRemarks").val(), sid: Math.random() }, function() {
												alert("Request Successfully Marked Rejected/Cancelled!");
												parent.showImgSamples();
											});
										//}
									} else {
										parent.sendErrorMessage("Please state reason of rejection or cancellation.");
									}
								}
							},
							{
								text: "Close",
								icons: { primary: "ui-icon-closethick" },
								click: function() {
									$(this).dialog("close");
								}
							}
					]
				});
			}
		}

	}

</script>
<style>
	.dataTables_wrapper {
		display: inline-block;
	    font-size: 11px; padding: 3px;
		width: 99%; 
	}
	
	table.dataTable tr.odd { background-color: #f5f5f5;  }
	table.dataTable tr.even { background-color: white; }
	.dataTables_filter input { width: 250px; }
</style>
</head>
<body bgcolor="#ffffff" leftmargin="0" bottommargin="0" rightmargin="0" topmargin="0">
<div id = "main">
	<table height="100%" width="100%" border="0" cellspacing="0" cellpadding="0" >
		<tr>
			<td style="padding:0px;" valign=top>
			<table width="100%" cellspacing="0" cellpadding="0" style="margin-top: 5px; margin-bottom: 5px;">
					<tr>
						<td>
							<button class="ui-button ui-widget ui-corner-all" onClick="writeResult();">
								<span class="ui-icon ui-icon-pencil"></span> Write or Update Result
							</button>
							<!--button class="ui-button ui-widget ui-corner-all" onClick="printBarcode();">
								<span class="ui-icon ui-icon-print"></span> Print Barcode
							</button-->
							<button class="ui-button ui-widget ui-corner-all" onClick="printResult();">
								<span class="ui-icon ui-icon-print"></span> Print Selected Result
							</button>
							<button class="ui-button ui-widget ui-corner-all" onClick="printByBatch();">
								<span class="ui-icon ui-icon-print"></span> Print Results By Batch
							</button>
							
							<button class="ui-button ui-widget ui-corner-all" onClick="rejectSample();">
								<span class="ui-icon ui-icon-cancel"></span> Reject or Cancel Request
							</button>
							<button class="ui-button ui-widget ui-corner-all" onClick="attachFile();">
								<img src="images/icons/attachments.png" width=12 height=12 align=absmiddle /> Attach Result Image
							</button>
							<button class="ui-button ui-widget ui-corner-all" onClick="parent.showImgSamples()">
								<span class="ui-icon ui-icon-refresh"></span> Reload List
							</button>
						</td>
					</tr>
				</table>
				<table id="itemlist" style="font-size:11px;">
					<thead>
						<tr>
							<th></th>
							<th width=5%>SO #</th>
							<th width=8%>SO DATE</th>
							<th width=15%>PATIENT NAME</th>
                            <th width=5%>AGE</th>
							<th width=7%>GENDER</th>
							<th width=7%>CODE</th>
							<th>REQUESTED PROCEDURE</th>
							<th width=8%>TYPE</th>
                            <th width=8%>SN#</th>
							<th width=10%>TIMESTAMP</th>
                            <th width=8%>STATUS</th>
							<th></th>
							<th></th>
							<th></th>
						</tr>
					</thead>
				</table>
			</td>
		</tr>
	</table>
</div>
<div id="sampleDetails" style="display: none;">
	<form name="frmReject" id="frmReject">
		<table width=100% cellspacing=0 cellpadding=0><tr><td width=100% class=gridHead align=center>SAMPLE DETAILS</td></tr></table>
        <table width=100% cellpadding=0 cellspacing=0 style="border: 1px solid #cdcdcd; padding: 10px;">
			<tr>
                <td align="left" width="35%" class="bareBold" style="padding-right: 15px;">Patient&nbsp;:</td>
                <td align=left>
                    <input type="text" class="gridInput" style="width:100%;" name="phleb_pname" id="phleb_pname">
                </td>				
            </tr>
            <tr><td height=3></td></tr>
			<tr>
                <td align="left" width="35%" class="bareBold" style="padding-right: 15px;">Required Procedure&nbsp;:</td>
                <td align=left>
                    <input type="text" class="gridInput" style="width:100%;" name="phleb_procedure" id="phleb_procedure">
                </td>				
            </tr>
            <tr><td height=3></td></tr>
            <tr>
                <td align="left" width="35%" class="bareBold" style="padding-right: 15px;">Procedure Code&nbsp;:</td>
                <td align=left>
                    <input type="text" class="gridInput" style="width:100%;" name="phleb_code" id="phleb_code">
                </td>				
            </tr>
            <tr><td height=3></td></tr>
            <tr>
                <td align="left" width="35%" class="bareBold" style="padding-right: 15px;">Specimen Type&nbsp;:</td>
                <td align=left>
                    <select class="gridInput" style="width:100%;" name="phleb_spectype" id="phleb_spectype">
                        <?php
                            $iun = $o->dbquery("select id,sample_type from options_sampletype;");
                            while(list($aa,$ab) = $iun->fetch_array()) {
                                echo "<option value='$aa'>$ab</option>";
                            }
                        ?>
                    </select>
                </td>				
            </tr>
            <tr><td height=3></td></tr>
            <tr>
                <td align="left" width="35%" class="bareBold" style="padding-right: 15px;">Sample Serial No.&nbsp;:</td>
                <td align=left>
                    <input type="text" class="gridInput" style="width:100%;" name="phleb_serialno" id="phleb_serialno">
                </td>				
            </tr>
            <tr><td height=3></td></tr>
            <tr>
                <td align="left" width="35%" class="bareBold" style="padding-right: 15px;">Date Extracted&nbsp;:</td>
                <td align=left>
                    <input type="text" class="gridInput" style="width:100%;" name="phleb_date" id="phleb_date" value="<?php echo date('m/d/Y'); ?>">
                </td>				
            </tr>
            <tr><td height=3></td></tr>
            <tr>
                <td align="left" width="35%" class="bareBold" style="padding-right: 15px;">Time Extracted&nbsp;:</td>
                <td align=left>
        
                    <?php
                        $o->timify("phleb",$w="");
                    ?>

                </td>				
            </tr>
            <tr><td height=3></td></tr>
            <tr>
                <td align="left" width="35%" class="bareBold" style="padding-right: 15px;">Extracted By&nbsp;:</td>
                <td align=left>
                    <input type="text" class="inputSearch2" style="width:100%;padding-left:22px;" name="phleb_by" id="phleb_by">
                </td>				
            </tr>
            <tr><td height=3></td></tr>
            <tr>
                <td align="left" width="35%" class="bareBold" style="padding-right: 15px;">Phleb/Imaging Site&nbsp;:</td>
                <td align=left>
                    <select class="gridInput" style="width:100%;" name="phleb_location" id="phleb_location">
                        <?php
                            $iun = $o->dbquery("select id,location from lab_locations;");
                            while(list($aa,$ab) = $iun->fetch_array()) {
                                echo "<option value='$aa'>$ab</option>";
                            }
                        ?>
                    </select>
                </td>				
            </tr>
            <tr><td height=3></td></tr>
            <tr>
                <td align="left" width="35%" class="bareBold" style="padding-right: 15px;" valign=top>Reason of Rejection&nbsp;:</td>
                <td align=left>
                    <textarea name="phleb_remarks" id="phleb_remarks" style="width:100%;" rows=3></textarea>
                </td>				
            </tr>
        </table>
	</form>
</div>
<div id="singleResult" style="display: none;"></div>
<div id="attachFile" name="attachFile" style="display: none;">
	<form enctype="multipart/form-data" name="frmAttachFile" id="frmAttachFile" method="POST">
		<input type="hidden" name="mod" id="mod" value="attachLabSampleFile">
		<input type="hidden" name="att_sono" id="att_sono" value="">
		<input type="hidden" name="att_serialno" id="att_serialno" value="">
		<input type="hidden" name="att_code" id="att_code" value="">
		<table border="0" cellpadding="0" cellspacing="2" width=100%>
			<tr>
				<td width=35%><span class="spandix-l">File Title :</span></td>
				<td>
					<input type="text" name="att_title" id="att_title" class="nInput" style="width: 80%;" />
				</td>
			</tr>
			<tr>
				<td width=35%><span class="spandix-l">File Remarks :</span></td>
				<td>
					<textarea name="att_remarks" id="att_remarks" rows=3 style="width: 80%;"></textarea>
				</td>
			</tr>				
			<tr>
				<td width=35% class="spandix-l">File to Attach: </td>
				<td><input type=file name="att_file" id="att_file" style="width: 80%;"></td>
			</tr>
		</table>
	</form>
</div>
<div id="printBatch" style="display: none;">
	<form name="frmPrintBatch" id="frmPrintBatch">
		<table width=100% callpaddin=0 cellspacing=3>
			<tr>
				<td width=35% class="spandix-l">Mobile SO # :</td>
				<td>
					<?php
					if($_SESSION['so_no'] != '') {
						echo '<input type="text" class="gridInput" style="width: 80%" name="mobileSoNo" id="mobileSoNo" value="'.str_pad($_SESSION['so_no'],6,0,STR_PAD_LEFT).'" readonly>';

					} else {

						echo '<select name="mobileSoNo" id="mobileSoNo" class="gridInput" style="width: 100%; font-size: 10px;">';
						$mobileSoQuery = $o->dbquery("select distinct cso_no from cso_header order by cso_no desc;");
						while($mRow = $mobileSoQuery->fetch_array()) {
							echo '<option value = "'.$mRow[0].'">'.str_pad($mRow[0],6,0,STR_PAD_LEFT).'</option>';
						}

						echo '</select>';
					}
				?>
				</td>
			</tr>
			<tr><td height=2></td></tr>
			<tr>
				<td width=35% class="spandix-l">Date Coverage:</td>
				<td>
					<input type="text" class="gridInput" style="width: 80%;" id="dtf" name="dtf" value = "" placeholder = "Specify if applciable">
				</td>
			</tr>
			<tr><td height=2></td></tr>
			<tr>
				<td width=35% class="spandix-l">&nbsp;</td>
				<td>
					<input type="text" class="gridInput" style="width: 80%;" id="dt2" name="dt2" value = "" placeholder = "Specify if applciable">
				</td>
			</tr>
			<tr><td height=2></td></tr>
			<tr>
				<td width=35% class="spandix-l">Result Type :</td>
				<td>
					<select name="xray_type" id="xray_type" class="gridInput" style="width: 80%; font-size: 11px;">
						<option value=''>All</option>
						<option value='Y'>Normal</option>
						<option value='N'>With Findings</option>
					</select>
				</td>
			</tr>
			<tr>
				<td width=35% class="spandix-l">Sort By :</td>
				<td>
					<select name="printSort" id="printSort" class="gridInput" style="width: 80%; font-size: 11px;">
						<option value='1'>Patient Name</option>
						<option value='2'>By X-Ray No. (Ascending)</option>
						<option value='3'>By Company Name</option>
					</select>
					</select>
				</td>
			</tr>
		</table>
	</form>
</div>

<div id="reject" style="display: none;">
	<form name="frmRejectSample" id="frmPrintBatch">
		<table width=100% callpaddin=0 cellspacing=3>
			<tr>
				<td width=35% class="spandix-l">Serial No. :</td>
				<td>
					<input type="text" class="gridInput" style="width: 80%;" id="rejectSerialNo" name="rejectSerialNo" value = "">
				</td>
			</tr>
			<tr>
				<td width=35% class="spandix-l">Patient Name :</td>
				<td>
					<input type="text" class="gridInput" style="width: 80%;" id="rejectPname" name="rejectPname" value = "">
				</td>
			</tr>
			<tr>
				<td width=35% class="spandix-l">Reason for Rejection/Cancellation :</td>
				<td>
					<textarea name="rejectRemarks" id="rejectRemarks" style="width: 80%;" rows = '3'></textarea>
				</td>
			</tr>
		</table>
	</form>
</div>

</body>
</html>