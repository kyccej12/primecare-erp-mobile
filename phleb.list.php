<?php
	session_start();
	include('handlers/_generics.php');
	$o = new _init();
	$searchString = '';

	if($_REQUEST['isSearch'] == 'Y') {
		$searchString = "?searchText=" . $_REQUEST['searchText'];
	}

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>Medgruppe Polyclinics & Diagnostic Center, Inc.</title>
<link href="ui-assets/themes/smoothness/jquery-ui.css" rel="stylesheet" type="text/css" />
<link href="style/jquery.timepicker.css" rel="stylesheet" type="text/css" />
<link href="style/style.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="ui-assets/datatables/css/jquery.dataTables.css">
<link rel="stylesheet" type="text/css" href="ui-assets/keytable/css/keyTable.jqueryui.css">

<script type="text/javascript" charset="utf8" src="ui-assets/jquery/jquery-1.12.3.js"></script>
<script type="text/javascript" charset="utf8" src="ui-assets/barcodescanner/jquery.scannerdetection.js"></script>
<script type="text/javascript" charset="utf8" src="ui-assets/themes/smoothness/jquery-ui.js"></script>
<script type="text/javascript" charset="utf8" src="ui-assets/jquery/jquery.timepicker.js"></script>
<script type="text/javascript" charset="utf8" src="ui-assets/datatables/js/jquery.dataTables.js"></script>
<script type="text/javascript" charset="utf8" src="ui-assets/datatables/js/dataTables.jqueryui.js"></script>
<script type="text/javascript" charset="utf8" src="ui-assets/datatables/js/dataTables.select.js"></script>
<script type="text/javascript" charset="utf8" src="ui-assets/datatables/js/page.jumpToData().js"></script>
<script type="text/javascript" charset="utf8" src="ui-assets/keytable/js/dataTables.keyTable.min.js"></script>
<script>

	$(document).ready(function() {

		var myTable = $('#itemlist').DataTable({
			"keys": true,
			"scrollY":  "310px",
			"select":	'single',
			"pagingType": "full_numbers",
			"pageLength": 100,
			"bProcessing": true,
			"responsive": true,
			"sAjaxSource": "data/phleblist.php",
			"scroller": true,
			"order": [[8,'desc'],[3, "asc"]],
			<?php if($_GET['code'] != 'undefined') { ?>
				"initComplete": function() {
					this.api().page.jumpToData("<?php echo $_GET['code']; ?>",1);
				},
			<?php } ?>
			"aoColumns": [
			  { mData: 'id' } ,
			  { mData: 'so' } ,
			  { mData: 'sdate' },
			  { mData: 'pname' },
			  { mData: 'gender' },
			  { mData: 'age' }, 
			  { mData: 'code' },
			  { mData: 'procedure' },
			  { mData: 'so_date' },
			  { mData: 'birthdate' },
			  { mData: 'pid' },
			],
			"aoColumnDefs": [
			    { "className": "dt-body-center", "targets": [1,2,4,5,6]},
			    { "targets": [0,8,9,10], "visible": false }
            ]
		});

		$('#itemlist tbody').on('dblclick', 'tr', function () {
			var data = myTable.row(this).data();
			parent.collectSample(data['so'],data['code'],data['pid']);

		});

		$("#phleb_date").datetimepicker();
		$('#phleb_by').autocomplete({
			source:'suggestEmployee.php', 
			minLength:3
		});

		
		$(document).scannerDetection({
			preventDefault: false,
			endChar: [13],
			stopPropagation: false,
			onComplete: function(e,data) {
				validScan = true;
				scanBarcode();
				processSample(e);
			}
			
		});

	});
	
	function scanBarcode() {
		$("#scan_status").focus();
		$("#scanningArea").dialog({
			title: "Scan Barcode",
			width: 720,
			resizable: false,
			modal: true
		});
	}

	function processSample(serialno) {
		$.post("src/sjerp.php", { mod: "checkScannedSerialNo", serialno: serialno, sid: Math.random() }, function(data) {
			var count = parseFloat(data);

			if(count > 0) {
				$.post("src/sjerp.php", { mod: "tagScannedSerialNo", serialno: serialno, sid: Math.random() }, function(data) {
					$("#scan_sampleno").html(data['serialno']);
					$("#scan_pname").html(data['pname']);
					$("#scan_gender").html(data['gender']);
					$("#scan_dob").html(data['bdate']);
					$("#scan_test").html(data['procedure']);
					$("#scan_tstamp").html(data['tstamp']);
					

					if(data['extracted'] == 'Y') {
						$("#scan_status").val("Barcode Already Been Processed!");
					} else {
						$("#scan_status").val("Barcode Successfully Processed!");
					}

					$("#scan_status").focus();

				},"json");
			} else {
				parent.sendErrorMessage("Barcode Not Found!");
			}
		},"html");
	}


	function editRecord(){
		var table = $("#itemlist").DataTable();		
		var arr = [];
	   $.each(table.rows('.selected').data(), function() {
		   arr.push(this["id"]);
	   });
	  
		if(!arr[0] || arr[0] == "undefined") {
			parent.sendErrorMessage("Please select a record from the list, and once highlighted, click \"<b><i>Edit Selected Record</i></b>\" again...");
		} else {
			parent.showServiceInfo(arr[0]);	
		}
	}

	function refreshList() {
		$('#itemlist').DataTable().ajax.url("data/phleblist.php").load();
	}

	function collectSample() {
		var table = $("#itemlist").DataTable();		
	
		var sono; 
		var code; 
	   	var pid;
		$.each(table.rows('.selected').data(), function() {
			sono = this['so'];
			code = this['code'];
			pid = this['pid'];
	   	});


		$.post("src/sjerp.php", {
			mod: "retrieveOrderForSample",
			code: code,
			sono: sono,
			pid: pid,
			sid: Math.random() },
			function(data) {


				var pdetails = decodeURIComponent(data['patient_name']) + " ^ " + data['gender'] + " ^ " + data['age'] + "YO";
				var procedure = data['code'] + " ^ " + decodeURIComponent(data['particulars']);
				var sample = data['xsample'] + " ^ " + data['series'];
				var sodetails = data['so'] + " ^ " + data['sodate'];

				$("#phleb_sono").val(data['so']);
				$("#phleb_sodetails").val(sodetails);
				$("#phleb_pid").val(data['pid']);
				$("#phleb_pname").val(pdetails);
				$("#phleb_physician").val(data['physician']);
				$("#phleb_procedure").val(procedure);
				$("#phleb_sampledetails").val(sample);
				$("#phleb_code").val(data['code']);
				$("#phleb_parentcode").val(data['parent_code']);
				$("#phleb_spectype").val(data['sample_type']);
				$("#phleb_serialno").val(data['series']);
				$("#phleb_containertype").val(data['container_type']);

				/* Retrieve List of tests that may be using the sample specimen/smaple */
				var scount = parseFloat(data['samplecount']);
				if(scount > 0) {
					$.post("src/sjerp.php", { mod: "retrieveSameSample", sono: data['so'], code: data['code'], ctype: data['container_type'], stype: data['sample_type'], pid: pid, sid: Math.random() }, function(reshtml) {
						$("#sampleField").html(reshtml);
					},"html");

				} else { $("#sampleField").html(''); }




				var dis = $("#sampleFormDetails").dialog({
					title: "Specimen Collection Details",
					width: 640,
					resizeable: false,
					modal: true,
					buttons: [
						{
							text: "Save Changes Made",
							icons: { primary: "ui-icon-check" },
							click: function() {
								var msg = '';
								if($("#phleb_by").val() == "") { msg = msg + "- Please identify the person who took this specimen<br/>"; }
								
								if(msg != '') {
									parent.sendErrorMessage(msg);
								} else {
									if(confirm("Are you sure you want save this data?") == true) {
										var dataString = $("#frmSample").serialize();
										dataString = "mod=saveSample&" + dataString;
										$.ajax({
											type: "POST",
											url: "src/sjerp.php",
											data: dataString,
											success: function() {
		
												if(confirm("Extraction details successfully saved! Do you want to print Barcode Label for the extracted sample now?") == true) {
													$("#sampleFormDetails").trigger("reset");
													dis.dialog("close");
													refreshList();
													parent.printBarcode($("#phleb_serialno").val());
												}
											}
										});
									}
								}

							}
						},
						{
							text: "Print Barcode",
							icons: { primary: "ui-icon-print" },
							click: function() { parent.printBarcode($("#phleb_serialno").val()); }
						},
						{
							text: "Close",
							icons: { primary: "ui-icon-closethick" },
							click: function() { $(this).dialog("close"); $('#sampleDetails').trigger("reset"); }
						}
					]
				});

			},"json"
		);
	}

	function displayPending() {
		document.frmDisplayPending.submit();
	}

	function searchRecord() {
		$("#mainLoading").css("z-index","999");
		$("#mainLoading").show();

		var stxt = $("#stxt").val();
		document.frmSearch.searchtext.value = stxt;
		document.frmSearch.submit();
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
	<table width="100%" cellspacing="0" cellpadding="0" style="padding-left: 5px; margin-bottom: 2px;">
		<tr>
			<td width=50%>
				<button class="ui-button ui-widget ui-corner-all" onClick="collectSample();">
					<img src="images/icons/syringe.png" width=12 height=12 align=absmiddle /> Collect Sample
				</button>
				<button class="ui-button ui-widget ui-corner-all" onClick="scanBarcode();">
				<img src="images/icons/barcode-scanner.png" width=12 height=12 align=absmiddle /> Scan Barcode
				</button>
				<button class="ui-button ui-widget ui-corner-all" onClick="refreshList();">
					<span class="ui-icon ui-icon-refresh"></span> Display Current
				</button>
			</td>
			<!--td align=right>
				<input name="stxt" id="stxt" type="text" class="gridInput" style="width: 240px; height: 24px;" value="<?php echo $_REQUEST['searchtext']; ?>" placeholder="Search Record">
				<button class="ui-button ui-widget ui-corner-all" onClick="javascript: searchRecord();">
					<span class="ui-icon ui-icon-search"></span> Search Record
				</button>
			</td-->
		</tr>
	</table>
	<table class="cell-border" id="itemlist" style="font-size:11px;">
		<thead>
			<tr>
				<th></th>
				<th width=5%>SO #</th>
				<th width=10%>SO DATE</th>
				<th width=15%>PATIENT NAME</th>
				<th width=10%>GENDER</th>
				<th width=10%>AGE</th>
				<th width=10%>CODE</th>
				<th>PROCEDURE</th>
				<th></th>
				<th></th>
			</tr>
		</thead>
	</table>
</div>
<div id="sampleFormDetails" style="display: none;">
	<form name="frmSample" id="frmSample">
		<input type="hidden" name = "phleb_sono" id = "phleb_sono">
        <input type="hidden" name = "phleb_parentcode" id = "phleb_parentcode">
		<input type="hidden" name = "phleb_code" id = "phleb_code">
		<input type="hidden" name = "phleb_pid" id = "phleb_pid">
		<input type="hidden" name = "phleb_spectype" id = "phleb_spectype">
		<input type="hidden" name = "phleb_serialno" id = "phleb_serialno">
		<table width=100% cellpadding=0 cellspacing=0 style="border: 1px solid #cdcdcd; padding: 10px;">
			<tr>
				<td align="right" width="30%"  class="bareBold" style="padding-right: 15px;">Sales Order&nbsp;:</td>
				<td align=left colspan=2>
					<input class="gridInput" style="width:100%;" type=text name="phleb_sodetails" id="phleb_sodetails" readonly>
				</td>				
			</tr>
			<tr><td height=3></td></tr>
			<tr>
				<td align="right" width="30%" class="bareBold" style="padding-right: 15px;">Patient Details&nbsp;:</td>
				<td align=left colspan=2>
					<input type="text" class="gridInput" style="width:100%;" name="phleb_pname" id="phleb_pname" readonly>
				</td>				
			</tr>
			<tr><td height=3></td></tr>
			<tr>
				<td align="right" width="30%" class="bareBold" style="padding-right: 15px;">Requesting Physician&nbsp;:</td>
				<td align=left colspan=2>
					<input type="text" class="gridInput" style="width:100%;" name="phleb_physician" id="phleb_physician">
				</td>					
			</tr>
			<tr><td height=3></td></tr>
			<tr>
				<td align="right" width="30%" class="bareBold" style="padding-right: 15px;">Requested Procedure&nbsp;:</td>
				<td align=left>
					<input type="text" class="gridInput" style="width:95%;" name="phleb_procedure" id="phleb_procedure" readonly>
				</td>			
				<td rowspan=20 id="sampleField" title="Other lab requests from customer that may use the same sample." valign=top>


				</td>		
			</tr>
			<tr><td height=3></td></tr>
			<tr>
				<td align="right" width="30%" class="bareBold" style="padding-right: 15px;">Sample Details&nbsp;:</td>
				<td align=left width=30%>
					<input type="text" class="gridInput" style="width:95%;" name="phleb_sampledetails" id="phleb_sampledetails" readonly>
				</td>	
					
			</tr>
			<tr><td height=3></td></tr>
			<tr>
				<td align="right" width="30%" class="bareBold" style="padding-right: 15px;">Preferred Container&nbsp;:</td>
				<td align=left>
					<select class="gridInput" style="width:95%;" name="phleb_containertype" id="phleb_containertype">
						<?php
							$cquery = $o->dbquery("select id,`type` from options_containers;");
							while(list($cid,$ctype) = $cquery->fetch_array()) {
								echo "<option value='$cid'>$ctype</option>";
							}
						?>
					</select>
				</td>				
			</tr>
			<tr><td height=3></td></tr>
			<tr>
				<td align="right" width="30%" class="bareBold" style="padding-right: 15px;">Date & Time of Collection&nbsp;:</td>
				<td align=left>
					<input type="text" class="gridInput" style="width:95%;" name="phleb_date" id="phleb_date" value="<?php echo date('m/d/Y H:i'); ?>">
				</td>				
			</tr>
			<tr><td height=3></td></tr>
			<tr>
				<td align="right" width="30%" class="bareBold" style="padding-right: 15px;">Test Kit Vendor&nbsp;:</td>
				<td align=left>
					<input type="text" class="gridInput" style="width:95%;" name="phleb_testkit" id="phleb_testkit">
				</td>				
			</tr>
			<tr><td height=3></td></tr>
			<tr>
				<td align="right" width="30%" class="bareBold" style="padding-right: 15px;">Lot # (If Applicable)&nbsp;:</td>
				<td align=left>
					<input type="text" class="gridInput" style="width:95%;" name="phleb_testkit_lotno" id="phleb_testkit_lotno">
				</td>				
			</tr>
			<tr><td height=3></td></tr>
			<tr>
				<td align="right" width="30%" class="bareBold" style="padding-right: 15px;">Expiry Date&nbsp;:</td>
				<td align=left>
					<input type="date" class="gridInput" style="width:95%;" name="phleb_testkit_expiry" id="phleb_testkit_expiry">
				</td>				
			</tr>
			<tr><td height=3></td></tr>
			<tr>
				<td align="right" width="30%" class="bareBold" style="padding-right: 15px;">Extraction Site&nbsp;:</td>
				<td align=left>
					<select class="gridInput" style="width:95%;" name="phleb_location" id="phleb_location">
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
				<td align="right" width="30%" class="bareBold" style="padding-right: 15px;">Extracted By&nbsp;:</td>
				<td align=left>
					<input type="text" class="inputSearch2" style="width:95%;padding-left:22px;" name="phleb_by" id="phleb_by">
				</td>				
			</tr>
			<tr><td height=3></td></tr>
			<tr>
				<td align="right" width="30%" class="bareBold" style="padding-right: 15px;" valign=top>Remarks/Memo&nbsp;:</td>
				<td align=left colspan=2>
					<textarea name="phleb_remarks" id="phleb_remarks" style="width:100%;" rows=1></textarea>
				</td>				
			</tr>
		</table>
	</form>
</div>

<div id="scanningArea" name="scanningArea" style="display: none;">
	<table width=100% style="padding: 20px;">
		<tr>
			<td width=30% align=center>
				<img src="images/icons/scanBarcode.gif" width=80% height=80%></img><br/>
				<span class="bareGray">Click Icon to Scan Next Sample</span>

			<td>
			<td style="border-left: 1px solid #cdcdcd;" >
				<table width=100% cellpadding=3>
					<tr>
						<td width=30% class="bareBold">Sample No.</td>
						<td>:&nbsp;&nbsp;&nbsp;&nbsp;<span id="scan_sampleno" class="spandix-l"></span></td>
					</tr>
					<tr>
						<td width=30% class="bareBold">Sample Status</td>
						<td>:&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" style="background: none; border: none; width: 80%; font-weight: bold;" id="scan_status" readonly></td>
					</tr>
					<tr>
						<td class="bareBold">Patient Name</td>
						<td>:&nbsp;&nbsp;&nbsp;&nbsp;<span id="scan_pname" class="spandix-l"></span></td>
					</tr>
					<tr>
						<td class="bareBold">Gender</td>
						<td>:&nbsp;&nbsp;&nbsp;&nbsp;<span id="scan_gender" class="spandix-l"></span></td>
					</tr>
					<tr>
						<td class="bareBold">Date of Birth</td>
						<td>:&nbsp;&nbsp;&nbsp;&nbsp;<span id="scan_dob" class="spandix-l"></span></td>
					</tr>
					<tr>
						<td class="bareBold">Test</td>
						<td>:&nbsp;&nbsp;&nbsp;&nbsp;<span id="scan_test" class="spandix-l"></span></td>
					</tr>
					<tr>
						<td class="bareBold">Date & Time of Collection</td>
						<td>:&nbsp;&nbsp;&nbsp;&nbsp;<span id="scan_tstamp" class="spandix-l"></span></td>
					</tr>
				</table>
			<td>
		</tr>
		
	</table>

</div>

<div id="mainLoading" style="display:none; width:100%;height:100%;position:absolute;top:0;margin:auto;"> 
	<div style="background-color:white;width:10%;height:20%;;margin:auto;position:relative;top:100;">
		<img style="display:block;margin-left:auto;margin-right:auto;" src="images/ajax-loader.gif" width=100 height=100 align=absmiddle /> 
	</div>
	<div id="mainLoading2" style="background-color:white;width:100%;height:100%;position:absolute;top:0;margin:auto;opacity:0.8;"> </div>
</div>

<form name="frmDisplayPending" id="frmDisplayPending" action="phleb.list.php" method="POST">
	<input type="hidden" name="displayPending" id="displayPending" value="Y">
</form>

<form name="frmSearch" id="frmSearch" action="phleb.list.php" method="POST">
	<input type="hidden" name="isSearch" id="isSearch" value="Y">
	<input type="hidden" name="searchtext" id="searchtext" value="<?php echo $_REQUEST['searchtext']; ?>">
</form>

</body>
</html>