<?php
	session_start();
?>
<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<title>Medgruppe Polyclinics & Diagnostic Center, Inc.</title>
	<link href="ui-assets/themes/smoothness/jquery-ui.css" rel="stylesheet" type="text/css" />
	<link href="style/style.css" rel="stylesheet" type="text/css" />
	<link rel="stylesheet" type="text/css" href="ui-assets/datatables/css/jquery.dataTables.css">
	<script type="text/javascript" charset="utf8" src="ui-assets/jquery/jquery-1.12.3.js"></script>
	<script type="text/javascript" charset="utf8" src="ui-assets/datatables/js/jquery.dataTables.js"></script>
	<script type="text/javascript" charset="utf8" src="ui-assets/datatables/js/dataTables.jqueryui.js"></script>
	<script type="text/javascript" charset="utf8" src="ui-assets/datatables/js/dataTables.select.js"></script>
	<script>

		function viewSW() {
			var table = $("#itemlist").DataTable();
			var arr = [];
		    $.each(table.rows('.selected').data(), function() { arr.push(this["docno"]); });
			
			if(!arr[0]) {
				parent.sendErrorMessage("Please select record to view.");
			} else {
				parent.viewSW(arr[0]);
			}
		}
		$(document).ready(function() {
			$('#itemlist').dataTable({
				"ajax": {
					"url": "data/swlist.php",
					"method": "POST"
				},
				"scrollY":  "350px",
				"select":	'single',
				"pagingType": "full_numbers",
				"aoColumns": [
				  { mData: 'docno' } ,
				  { mData: 'sdate' },
				  { mData: 'withdrawn_by' },
				  { mData: 'remarks' },
				  { mData: 'amount' },
				  { mData: 'status' }
				],
				"aoColumnDefs": [
					{ className: "dt-body-center", "targets": [0,1,5] },
					{ className: "dt-body-right", "targets": [4] },
					{ "targets": [4], "visible": false }
				],
				"order": [[ 1, "desc" ]]
			});
		});

		function refresh() {
			$('#itemlist').DataTable().ajax.url("data/swlist.php?sid="+Math.random()+"").load();
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
<body bgcolor="#ffffff" leftmargin="0" bottommargin="0" rightmargin="0" topmargin="0" >
	<table width=100% cellpadding=0 cellspacing=0 style="padding-left: 5px; margin-bottom: 2px;">
		<tr>
			<td align=left>
				<a href="#" onClick="parent.viewSW('');" class="topClickers"><img src="images/icons/add-2.png" width=18 height=18 align=absmiddle />&nbsp;New Record</a>&nbsp;
				<a href="#" onClick="viewSW();" class="topClickers"><img src="images/icons/bill.png" width=18 height=18 align=absmiddle />&nbsp;View/Edit Record</a>
				<a href="#" onClick="refresh();" class="topClickers"><img src="images/icons/refresh.png" width=18 height=18 align=absmiddle />&nbsp;Reload List</a>
			</td>
		</tr>
	</table>
	<table id="itemlist" style="font-size:11px;">
		<thead>
			<tr>
				<th width=8%>DOC #</th>
				<th width=10%>DOC DATE</th>
				<th width=20%>WITHDRAWN BY</th>
				<th>DOCUMENT REMARKS</th>
				<th width=12%>AMOUNT</th>
				<th width=12%>DOC STATUS</th>
			</tr>
		</thead>
	</table>
</body>
</html>