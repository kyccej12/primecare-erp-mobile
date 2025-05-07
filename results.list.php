<?php
	session_start();
	include("handlers/_generics.php");
	$o = new _init;
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>Primecare Cebu ERP System Ver. 1.0b</title>
<link href="ui-assets/themes/smoothness/jquery-ui.css" rel="stylesheet" type="text/css" />
<link href="style/style.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="ui-assets/datatables/css/jquery.dataTables.css">
<link rel="stylesheet" type="text/css" href="ui-assets/keytable/css/keyTable.jqueryui.css">
<script type="text/javascript" charset="utf8" src="ui-assets/jquery/jquery-1.12.3.js"></script>
<script type="text/javascript" charset="utf8" src="ui-assets/themes/smoothness/jquery-ui.js"></script>
<script type="text/javascript" charset="utf8" src="ui-assets/datatables/js/jquery.dataTables.js"></script>
<script type="text/javascript" charset="utf8" src="ui-assets/datatables/js/dataTables.jqueryui.js"></script>
<script type="text/javascript" charset="utf8" src="ui-assets/datatables/js/dataTables.select.js"></script>
<script type="text/javascript" charset="utf8" src="ui-assets/keytable/js/dataTables.keyTable.min.js"></script>
<script>

	$(document).ready(function() {

		$("#release_date, #dtf, #dt2, #shiftDate").datepicker();

		var myTable = $('#itemlist').DataTable({
			"keys": true,
			"scrollY":  "300px",
			"select":	'single',
			"pagingType": "full_numbers",
			"bProcessing": true,
			"responsive": true,
			"sAjaxSource": "data/resultlist.php",
			"scroller": true,
			"order": [[ 1, "desc" ]],
			"aoColumns": [
			  { mData: 'id' } ,
			  { mData: 'sono' } ,
			  { mData: 'sodate' },
			  { mData: 'pname' },
			  { mData: 'age' },
			  { mData: 'gender' },
			  { mData: 'customer_name' },
			  { mData: 'procedure' },
              { mData: 'released' },
			  { mData: 'rby' },
			  { mData: 'rdate' },
			  { mData: 'released_to' },
			  { mData: 'code' },
			  { mData: 'serialno' },
			  { mData: 'so_date' },
			  { mData: 'birthdate' },
			  { mData: 'pid' }
			],
			"aoColumnDefs": [
			    { "className": "dt-body-center", "targets": [1,2,4,5,8,9,10]},
			    { "targets": [0,12,13,14,15,16], "visible": false }
            ]
		});
	});
	
	function refreshList() {
		var displayType = $("#displayType").val();
		var url = "data/resultlist.php?displayType="+displayType+"&sid="+Math.random();

		$('#itemlist').DataTable().ajax.url(url).load();
	}

	function releaseResult() {
		var table = $("#itemlist").DataTable();		
		var lid; var stat;
	   	$.each(table.rows('.selected').data(), function() {
		    lid = this["id"];
			code = this['code'];
			so = this['xso'];
			isRelease = this['released'];
	   	});

		if(lid) {

			if(isRelease == 'Yes') {
				parent.sendErrorMessage("It appears that the selected record was already released to Patient or its authorized representative.");
			} else {
				var irelease = $("#releasing").dialog({
					title: "Process Result for Release",
					width: 480,
					resizable: false,
					modal: true,
					buttons: [
						{
							text: "Mark Record as Released",
							icons: { primary: "ui-icon-check" },
							click: function() {
								if(confirm("Are you sure you want to process this result for releasing?") == true) {
									var msg = "";
									if($("#release_to").val() == '') {
										parent.sendErrorMessage("Please identify the recipient of the result that you intend to release");
									} else {
										
										$.post("src/sjerp.php", { mod: "checkOtherResultsForRelease", so_no: so, code: code, sid: Math.random() }, function(data) {
											data = parseInt(data)

											if(data > 0) {
												var disMessage = $("#systemMessage").dialog({ 
												title: "System Message",
												width: "500",
												modal: true,
												resizeable: false,
												buttons: [
														{
															icons: { primary: "ui-icon-copy" },
															text: "Mark Other Available Results as Released",
															click: function() { 
																$.post("src/sjerp.php", { mod: "batchReleaseResult", so_no: so, mode: $("#release_mode").val(), date: $("#release_date").val(), to: $("#release_to").val(), remarks: $("#release_remarks").val(), sid: Math.random() }, function() {
																	alert("Result successfully released to patient!");
																	irelease.dialog("close");
																	disMessage.dialog("close");
																	$("#frmRelease").trigger("reset");

																	refreshList();
																});	
															}
														},
														{
															icons: { primary: "ui-icon-pencil" },
															text: "Release Selected Result Only",
															click: function() { 
																$.post("src/sjerp.php", { mod: "releaseResult", id: lid, code: code, mode: $("#release_mode").val(), date: $("#release_date").val(), to: $("#release_to").val(), remarks: $("#release_remarks").val(), sid: Math.random() }, function() {
																	alert("Result successfully released to patient!");
																	irelease.dialog("close"); 
																	$("#frmRelease").trigger("reset");
																	disMessage.dialog("close");
																	refreshList();
																});	
															}	

														}
													]
												});
											} else {
												$.post("src/sjerp.php", { mod: "releaseResult", id: lid, code: code, mode: $("#release_mode").val(), date: $("#release_date").val(), to: $("#release_to").val(), remarks: $("#release_remarks").val(), sid: Math.random() }, function() {
													alert("Result successfully released to patient!");
													irelease.dialog("close"); $("#frmRelease").trigger("reset");
													refreshList();
												});	
											}
										},"html");
									}

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

		} else {
			parent.sendErrorMessage("Please select result to release...")
		}

	}

	function printResult() {
		var table = $("#itemlist").DataTable();		
	   	$.each(table.rows('.selected').data(), function() {
		    lid = this["id"];
			code = this['code'];
			sono = this['sono'];
			serialno = this['serialno'];
	   	});

		if(lid) {
			parent.printResult(code,sono,serialno);
		} else {
			parent.sendErrorMessage("Please select result to print!")
		}
	}

	function printIndividualResult() {
		var table = $("#itemlist").DataTable();		
	   	$.each(table.rows('.selected').data(), function() {
			lid = this["id"];
		    pid = this["pid"];
			so_no = this['sono'];
	   	});

		if(pid) {
			parent.printIndividualResult(lid,so_no,pid);
		} else {
			parent.sendErrorMessage("Please select result to print!")
		}

	}

	function printBatchResult() {
		var disMessage = $("#printBatch").dialog({ 
			title: "Print Batch Result",
			width: "500",
			modal: true,
			resizeable: false,
			buttons: [
					{
						icons: { primary: "ui-icon-print" },
						text: "Print Results",
						click: function() { 
							window.open("print/batchresult.php?so_no="+$("#mobileSoNo").val()+"&dtf="+$("#dtf").val()+"&dt2="+$("#dt2").val()+"&sid="+Math.random()+"&sid="+Math.random()+"","Batch Result","location=1,status=1,scrollbars=1,width=640,height=720");
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

	function printBatchResultShift() {
		var disMessage = $("#printBatchShift").dialog({ 
			title: "Print Batch Result By Shift",
			width: "500",
			modal: true,
			resizeable: false,
			buttons: [
					{
						icons: { primary: "ui-icon-print" },
						text: "Print Results",
						click: function() { 
							window.open("print/batchresult-shift.php?so_no="+$("#shiftSONo").val()+"&date="+$("#shiftDate").val()+"&shift="+$("#shift").val()+"&sid="+Math.random()+"&sid="+Math.random()+"","Batch Result By Shift","location=1,status=1,scrollbars=1,width=640,height=720");
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

	function unpublishResult() {
		var table = $("#itemlist").DataTable();
		var lid; var code;
		$.each(table.rows('.selected').data(), function() {
			lid = this["id"];
			code = this["code"];
		});

		if(!lid) {
			parent.sendErrorMessage("Please select a result to unpublish...");
		}else {
			if(confirm("Are you sure you want to unpublish this result?") == true) {
				$.post("src/sjerp.php", { mod: "rejectResult", lid: lid, sid: Math.random() }, function() {
					alert("Result successfully unpublished! Please go back to Manage Collected Samples... ");
					refreshList();
				});
			}
		}
	}

	function printBatchHepaResult() {
		var disMessage = $("#printHepaBatch").dialog({ 
			title: "Print Batch Result",
			width: "500",
			modal: true,
			resizeable: false,
			buttons: [
					{
						icons: { primary: "ui-icon-print" },
						text: "Print Hepa A Results",
						click: function() { 
							window.open("print/batchHepaResult.php?so_no="+$("#mobileHepa").val()+"&sid="+Math.random()+"&sid="+Math.random()+"","Batch Result","location=1,status=1,scrollbars=1,width=640,height=720");
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


</script>
<style>
	.dataTables_wrapper {
		display: inline-block;
	    font-size: 11px;
		width: 100%; 
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
				<table width="100%" cellspacing="0" cellpadding="0" style="padding-left: 5px; margin-bottom: 5px;">
					<tr>
						<td>
							<button class="ui-button ui-widget ui-corner-all" onClick="printResult();">
								<span class="ui-icon ui-icon-print"></span> Print Selected Result
							</button>
							<button class="ui-button ui-widget ui-corner-all" onClick="printIndividualResult();">
								<span class="ui-icon ui-icon-print"></span> Print Individual & X-Ray Lab Result
							</button>
							<button class="ui-button ui-widget ui-corner-all" onClick="printBatchResult();">
								<span class="ui-icon ui-icon-print"></span> Print Result by Batch
							</button>
							<button class="ui-button ui-widget ui-corner-all" onClick="printBatchResultShift();">
								<span class="ui-icon ui-icon-print"></span> Print Result By Shift
							</button>
							<button class="ui-button ui-widget ui-corner-all" onClick="printBatchHepaResult();">
								<span class="ui-icon ui-icon-print"></span> Print Batch Hepa A & Anti-HBs Result
							</button>
							<button class="ui-button ui-widget ui-corner-all" onClick="unpublishResult();">
								<img src="images/icons/cancel48.png" width=12 height=12 align=absmiddle />&nbsp;Unpublish Result
							</button>
							<button class="ui-button ui-widget ui-corner-all" onClick="refreshList();">
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
							<th width=6%>DATE</th>
							<th width=12%>PATIENT NAME</th>
                            <th width=5%>AGE</th>
							<th width=5%>SEX</th>
							<th width=15%>COMPANY</th>
							<th>PROCEDURE</th>
							<th width=8%>RELEASED?</th>
                            <th width=8%>RELEASED BY</th>
							<th width=10%>DATE RELEASED</th>
							<th width=12%>RELEASED TO</th>
							<th></th>
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
<div id="batchResult" style="display: none;"></div>
<div id="releasing" style="display: none;">
	<form name="frmRelease" id="frmRelease">
		<table width=100% callpaddin=0 cellspacing=3>
			<tr>
				<td width=35% class="spandix-l">Mode of Releasing :</td>
				<td>
					<select class=gridInput style="width: 80%;" name="release_mode" id="release_mode">
						<option value='PICKUP'>Pickup by Patient</option>
						<option value="EMAILED">Emailed to Patient</option>
						<option value="DELIVERED">Delivered to Patient</option>
					</select>
				</td>
			</tr>
			<tr>
				<td width=35% class="spandix-l">Releasing Date :</td>
				<td><input type="text" class="gridInput" style="width: 80%;" id="release_date" name="release_date" value = "<?php echo date('m/d/Y'); ?>"></td>
			</tr>
		
			<tr>
				<td width=35% class="spandix-l">Released To :</td>
				<td><input type="text" class="gridInput" style="width: 80%;" id="release_to" name="release_to"></td>
			</tr>
			<tr>
				<td width=35% class="spandix-l" valign=top>Other Remarks :</td>
				<td><textarea style="width: 80%;" id="release_remarks" name="release_remarks" rows=3></textarea></td>
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
					<input type="text" class="gridInput" style="width: 80%;" id="mobileSoNo" name="mobileSoNo" value = "">
				</td>
			</tr>
			<tr>
				<td width=35% class="spandix-l">Date from :</td>
				<td>
					<input type="text" class="gridInput" style="width: 80%;" id="dtf" name="dtf" value = "<?php echo date('m/d/Y'); ?>">
				</td>
			</tr>
			<tr>
				<td width=35% class="spandix-l">Date To :</td>
				<td>
					<input type="text" class="gridInput" style="width: 80%;" id="dt2" name="dt2" value = "<?php echo date('m/d/Y'); ?>">
				</td>
			</tr>
		</table>
	</form>
</div>
<div id="printBatchShift" style="display: none;">
	<form name="frmPrintBatchShift" id="frmPrintBatchShift">
		<table width=100% callpaddin=0 cellspacing=3>
			<tr>
				<td width=35% class="spandix-l">Mobile SO # :</td>
				<td>
					<input type="text" class="gridInput" style="width: 80%;" id="shiftSONo" name="shiftSONo" value = "">
				</td>
			</tr>
			<tr>
				<td width=35% class="spandix-l">Date :</td>
				<td>
					<input type="text" class="gridInput" style="width: 80%;" id="shiftDate" name="shiftDate" value = "<?php echo date('m/d/Y'); ?>">
				</td>
			</tr>
			<tr>
				<td width=35% class="spandix-l">Shift :</td>
				<td>
					<select class="gridInput" style="width: 80%;" id="shift" name="shift">
						<option value="">All Shifts</option>
						<option value="1">Day Shift</option>
						<option value="2">Night Shift</option>
					</select>
				</td>
			</tr>
		</table>
	</form>
</div>
<div id="printHepaBatch" style="display: none;">
	<form name="frmPrintBatch" id="frmPrintBatch">
		<table width=100% callpaddin=0 cellspacing=3>
			<tr>
				<td width=35% class="spandix-l">Mobile SO # :</td>
				<td>
					<input type="text" class="gridInput" style="width: 80%;" id="mobileHepa" name="mobileHepa" value = "">
				</td>
			</tr>
		</table>
	</form>
</div>
<div id="systemMessage" title="System Message" style="display: none;">
	<p style="margin-left: 20px; text-align: justify;" id="message">It appears that other results enclosed in this Sales Order are also due for release. Do you wish to consolidate these results and release at once?</span></p>
</div>
</body>
</html>