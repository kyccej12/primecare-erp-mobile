<?php
	session_start();
	include("handlers/_generics.php");
	$con = new _init;
	
	$so = $con->getArray("SELECT date_format(cso_date,'%m/%d/%Y') as sdate,customer_name, `location`, company, DATE_FORMAT(`from`,'%m/%d/%Y') AS fr, DATE_FORMAT(`until`,'%m/%d/%Y') AS `to` FROM cso_header WHERE cso_no = '$_REQUEST[so_no]';");
	list($isBarcode) = $con->getArray("SELECT count(*) from cso_details where barcode = 'Y' and cso_no = '$_REQUEST[so_no]';");
	list($isNoBarcode) = $con->getArray("SELECT count(*) from cso_details where barcode = 'N' and cso_no = '$_REQUEST[so_no]';");
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<link href="style/style.css" rel="stylesheet" type="text/css" />
	<link href="ui-assets/themes/smoothness/jquery-ui.css" rel="stylesheet" type="text/css" />
	<link href="ui-assets/datatables/css/jquery.dataTables.css" rel="stylesheet" type="text/css" />
	<script type="text/javascript" charset="utf8" src="ui-assets/jquery/jquery-1.12.3.js"></script>
	<script type="text/javascript" charset="utf8" src="ui-assets/themes/smoothness/jquery-ui.js"></script>
	<script type="text/javascript" charset="utf8" src="ui-assets/datatables/js/jquery.dataTables.js"></script>
	<script type="text/javascript" charset="utf8" src="ui-assets/datatables/js/dataTables.jqueryui.js"></script>
	<script type="text/javascript" charset="utf8" src="ui-assets/datatables/js/dataTables.select.js"></script>
	<script type="text/javascript" charset="utf8" src="js/websocket-printer.js"></script>
	<script>
		var sPO = "";

		/* Silent Pringting Function Declaration */
		var printService = new WebSocketPrinter();

		$(document).ready(function() {
			$('#details').DataTable({
				"scrollY":  "300",
				"select":	'single',
				"searching": false,
				"paging": false,
				"bSort" : false,
				"info": false,
				"aoColumnDefs": [
					{ className: "dt-body-center", "targets": [0,2,3,5]},
					{ className: "dt-body-left", "targets": [1,4]},
					{ "targets": [7], "visible": false },
					{ "targets": [8], "visible": false }
				]
			});

		});
		

		function processBarcode() {


			var table = $("#details").DataTable();		
			var cso_no; 
			var pid;
			var status;

			$.each(table.rows('.selected').data(), function() {
				pid = this[0];
				cso_no = this[7];
				status = this[5];
			});

			$.post("cso.datacontrol.php", { mod: "checkBarcode", cso_no: cso_no, pid: pid, sid: Math.random() }, function(stat) {
				if(stat == 'N') {

					$.post("barcoder.php", { cso_no: cso_no, pid: pid, sid: Math.random() }, function(pdfFile) {
						
						$("#mainLoading").css("z-index","999");
						$("#mainLoading").show();

						/* Use this for Preview type printing 
						var txtHTML = "<iframe id='frmcust' frameborder=0 width='100%' height='100%' src='"+pdfFile+"'></iframe>";
						$("#barcode").html(txtHTML);
						$("#barcode").dialog({title: "Print - Barcode", width: 400, height: 200, resizable: false, modal: true });
						*/

						/* For Silent Printing */
						var myURL = "http://" +window.location.host + "/" + pdfFile;
						printService.submit({
							'type': 'LABEL',
							'url': myURL,
						});

						setTimeout(function() {
							location.reload()
						}, 7000);

					},"html");

				} else {
					parent.sendErrorMessage("It appears that a barcode has already been provided for this patient.");
				}

			},"html");


			

		}

		function reprintBarcode() {
			var table = $("#details").DataTable();		
			var rid;

			$.each(table.rows('.selected').data(), function() {
				rid = this[8];
			});


			$.post("src/sjerp.php", { mod: "getPrintedLabel", id: rid, sid: Math.random() },function(pdfFile) {
				
				/* Use this option for preview 
					var txtHTML = "<iframe id='frmcust' frameborder=0 width='100%' height='100%' src='"+pdfFile+"'></iframe>";
					$("#barcode").html(txtHTML);
					$("#barcode").dialog({title: "Print - Barcode", width: 400, height: 200, resizable: false, modal: true });
				*/


					var myURL = "http://" +window.location.host + "/" + pdfFile;
					printService.submit({
						'type': 'LABEL',
						'url': myURL,
					});

				});
		}

		function printRoutingSlip() {

			var table = $("#details").DataTable();		
			var cso_no; 
			var pid;
			var status;

			$.each(table.rows('.selected').data(), function() {
				pid = this[0];
				cso_no = this[7];
				status = this[5];
			});

			if(pid) {
				parent.printRoutingSlip(pid,cso_no);
			} else {
				parent.sendErrorMessage("Please select patient to print routing slip!");
			}

		}

		function reloadList() {
			location.reload();
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
<body bgcolor="#FFFFFF" leftmargin="0" bottommargin="0" rightmargin="0" topmargin="0" >
	<table width=100% style="padding: 10px;" class="td_content">
		<tr>
			<td width=15% class="spandix-l"><b>Mobile SO #</b></td>
			<td align=left>:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $_GET['so_no']; ?></td>
			<td></td>
			<td></td>
		</tr>
		<tr>
			<td width=15% class="spandix-l"><b>SO Date</td>
			<td align=left>:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $so['sdate']; ?></td>
			<td></td>
			<td></td>
		</tr>
		<tr>
			<td width=15% class="spandix-l"><b>Customer</b></td>
			<td align=left>:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $so['customer_name']; ?></td>
			<td></td>
			<td></td>
		</tr>
		<tr>
			<td width=15% class="spandix-l"><b>Company Name</b></td>
			<td align=left>:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $so['company']; ?></td>
			<td></td>
			<td></td>
		</tr>
		<tr>
			<td width=15% class="spandix-l"><b>Mobile Schedule</b></td>
			<td align=left>:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $so['fr'] . " to " . $so['to']; ?></td>
		</tr>
		<tr>
			<td width=15% class="spandix-l"><b>Mobile Site Address</b></td>
			<td align=left>:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $so['location'] ?></td>
		</tr>
	</table>

	<table class="cell-border" id="details">
		<thead>
			<tr>
				<th width=10%>PID</th>
				<th width=25%>PATIENT NAME</th>
				<th width=10%>GENDER</th>
				<th width=10%>BIRTHDATE</th>
				<th width=25%>PACKAGE AVAILED</th>
				<th >REGISTRATION STATUS</th>
				<th >PROCESSED ON</th>
				<th ></th>
				<th ></th>
			</tr>
		</thead>
		<tbody>
		<?php
			$patientQuery = $con->dbquery("SELECT a.line_id as record_id, a.cso_no, a.pid AS pid, a.pname AS pname, DATE_FORMAT(b.birthdate,'%d %b %Y') AS bdate, IF(b.gender='M','Male','Female') AS gender, a.description AS package, IF(barcode = 'Y','Barcode Provided','For Processing') AS `status`,date_format(processed_on,'%m/%d/%Y %h:%i %p') as processed_on FROM cso_details a LEFT JOIN pccmain.patient_info b ON a.pid = b.patient_id WHERE a.cso_no =  '$_REQUEST[so_no]' ORDER BY a.pname ASC, `description` ASC;");
			while($patientRow = $patientQuery->fetch_array()) {
				echo "<tr>
						<td>$patientRow[pid]</td>
						<td>$patientRow[pname]</td>
						<td>$patientRow[gender]</td>
						<td>$patientRow[bdate]</td>
						<td>$patientRow[package]</td>
						<td>$patientRow[status]</td>
						<td>$patientRow[processed_on]</td>
						<td>$patientRow[cso_no]</td>
						<td>$patientRow[record_id]</td>
					</tr>
				";

			}
		?>
		</tbody>
	</table>

    <table width=100% cellpadding=5 cellspacing=0 class="td_content">
		<tr>
			<td align=left>
				<button onClick="processBarcode();" class="buttonding"><img src="images/icons/barcode-scanner.png" width=18 height=18 align=absmiddle />&nbsp;&nbsp;Provide Barcode for Selected Patient</button>
				<button onClick="reprintBarcode();" class="buttonding"><img src="images/icons/print-barcode.png" width=18 height=18 align=absmiddle />&nbsp;&nbsp;Reprint Provided Barcode</button>
				<button onClick="printRoutingSlip();" class="buttonding"><img src="images/icons/account_info.png" width=18 height=18 align=absmiddle />&nbsp;&nbsp;Print Routing Slip</button>
				<button onClick="reloadList();" class="buttonding"><img src="images/icons/refresh.png" width=24 height=24 align=absmiddle />&nbsp;&nbsp;Reload List</b></button>
			</td>
			<td align=right width=50% valign=top>
				No of Pax Already Processed : &nbsp;&nbsp;<input style="width:150px;text-align:right;" type=text name="pax1" id="pax1" value="<?php echo $isBarcode;; ?>" readonly><br/>	
				No of Pax For Processing : &nbsp;&nbsp;<input style="width:150px;text-align:right;" type=text name="pax2" id="pax2" value="<?php echo $isNoBarcode ?>" readonly>		
			</td>
		</tr>
	</table>
	
	<div id="barcode" name="barcode" style="display: none;"></div>

	<div id="mainLoading" style="display:none; width:100%;height:100%;position:absolute;top:0;margin:auto;"> 
		<div style="background-color:white;width:10%;height:20%;;margin:auto;position:relative;top:100;">
			<img style="display:block;margin-left:auto;margin-right:auto;" src="images/ajax-loader.gif" width=100 height=100 align=absmiddle /> 
		</div>
		<div id="mainLoading2" style="background-color:white;width:100%;height:100%;position:absolute;top:0;margin:auto;opacity:0.8;"> </div>
	</div>

	</body>
</html>