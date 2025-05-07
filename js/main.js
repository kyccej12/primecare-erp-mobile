/* Percentage of Screen Size */
var wWidth = $(window).width();
var xWidth = wWidth * 0.8;
var xWidth = wWidth * 0.95;

var monthNames = ["January", "February", "March", "April", "May", "June",
  "July", "August", "September", "October", "November", "December"
];

var singleResultSelection = [
	"PATIENT IS OVERFAST.",
	"RESULT HAS BEEN VERIFIED WITH REPEAT ANALYSIS.",
	"PATIENT IS OVERFAST. RESULT HAS BEEN VERIFIED WITH REPEAT ANALYSIS."
];

var enumResultSelection = [
	"NEGATIVE",
	"POSITIVE",
	"REACTIVE",
	"NON-REACTIVE",
	"WEAKLY REACTIVE"
];

var remarksAll = [
	"Screening Test Only.",
	"Screening Test Only. Please correlate clinically. Suggestive for confirmatory through quantitative testing."
];

// Auto suggest for input field
$(document).ready(function($) {
	$("#sresult_remarks" ).autocomplete({
		source: singleResultSelection, minLength: 0
	}).focus(function() {
		$(this).data("uiAutocomplete").search($(this).val());
	});

	$("#enum_result").autocomplete({
		source: enumResultSelection,
		minLength: 0
		}).focus(function() {
			$(this).data("uiAutocomplete").search($(this).val());
	});
	
	$("#enum_remarks, #hiv_remarks, #sresult_remarks").autocomplete({
		source: remarksAll,
		minLength: 0
	}).focus(function() {
		$(this).data("uiAutocomplete").search($(this).val());
	});
});

function popSaver() {
	$('#popSaver').fadeIn('fast').delay(1000).fadeOut('slow');
}

function stripComma(val) {
	return val.replace(/,/g,"");
}

function kSeparator(val) {
	var val = parseFloat(val);
		val = val.toFixed(2);
	var a = val.split(".");
	var kValue = a[0];
	//if(a[1] == '' || a[1] == 'undefined') { a[1] = '00'; }

	var sRegExp = new RegExp('(-?[0-9]+)([0-9]{3})');
	while(sRegExp.test(kValue)) {
		kValue = kValue.replace(sRegExp, '$1,$2');
	}

	if(a[1] != "") {
		kValue = kValue + "." + a[1]; 
		return kValue;
	} else {
		return kValue + ".00";
	}
}
	
function addCommas(nStr)
{
	nStr += '';
	x = nStr.split('.');
	x1 = x[0];
	x2 = x.length > 1 ? '.' + x[1] : '';
	var rgx = /(\d+)(\d{3})/;
	while (rgx.test(x1)) {
		x1 = x1.replace(rgx, '$1' + ',' + '$2');
	}
	return x1 + x2;
}	

function sendErrorMessage(msg) {
	$("#message").html(msg);
	$("#errorMessage").dialog({
		width: 400,
		resizable: false,
		modal: true,
		buttons: {
			"OK": function() { $(this).dialog("close"); }
		}
	})
}

function showLoaderMessage() {
	$("#loaderMessage").dialog({ show: 'fade', width: 480, height: 180, closable: false, modal: true,  open: function(event, ui) {
        $(".ui-dialog-titlebar-close", ui.dialog | ui).hide();
    }});
}


function showMenu() {
	$("#mainMenu").dialog({show: 'fade', title: "Main Menu", width: 1024, resizable: false }).dialogExtend({
		"closable" : true,
		"maximizable" : false,
		"minimizable" : true
	});
}

function closeDialog(frame) {
	$(frame).dialog("close");
}

/* Users */
function showUsers() {
	var txtHTML = "<iframe id='frmcust' frameborder=0 width='100%' height='100%' src='user.master.php'></iframe>";
	$("#userlist").html(txtHTML);
	$("#userlist").dialog({title: "System Users", width: xWidth, height: 540, resizable: false }).dialogExtend({
		"closable" : true,
	    "maximizable" : false,
	    "minimizable" : true
	});
}

function showChangePass() {
	$("#userChangePass").dialog({ title: "Update Password", width: 480, height: 190, resizable: false, modal: true, buttons: {
					"Update my Password": function() {
						var msg = "";

						if($("#pass1").val() == "" || $("#pass2").val() == "") { msg = msg + "The system cannot accept empty password.<br/>"; }
						if($("#pass1").val() != $("#pass2").val()) { msg = msg + "New Passwords do not match.<br/>"; }
					
						if(msg!="") {
							sendErrorMessage(msg);
						} else {

							$.post("src/sjerp.php", { mod: "changePassword", uid:  $("#myUID").val(), pass: $("#pass1").val(), sid: Math.random() },function() {
								alert("You have successfully updated your password!");
								$("#userChangePass").dialog("close");
							});
						}
					},
					"Continue with the System": function () { $(this).dialog("close"); }
				} }).dialogExtend({
		"closable" : true,
	    "maximizable" : false,
	    "minimizable" : true,
	});
}
function addUser() {
	var txtHTML = "<iframe id='frmcust' frameborder=0 width='100%' height='100%' src='user.details.php'></iframe>";
	$("#userdetails").html(txtHTML);
	$("#userdetails").dialog({title: "System User Info.", width: 400, height: 260, resizable: false }).dialogExtend({
		"closable" : true,
	    "maximizable" : false,
	    "minimizable" : true,
	});
}

function viewUserInfo(eid) {
	var txtHTML = "<iframe id='frmcust' frameborder=0 width='100%' height='100%' src='user.update.php?eid="+eid+"'></iframe>";
	$("#userdetails").html(txtHTML);
	$("#userdetails").dialog({title: "System User Info.", width: 400, height: 260, resizable: false }).dialogExtend({
		"closable" : true,
	    "maximizable" : false,
	    "minimizable" : true,
	});
}


function showUserDetails(uid) {
	var uname;
	$.post("src/sjerp.php", { mod: "getUinfo", uid: uid, sid: Math.random() }, function(data) {
		var txtHTML = "<iframe id='frmcust' frameborder=0 width='100%' height='100%' src='user.rights.php?uid="+uid+"'></iframe>";
		$("#userrights").html(txtHTML);
		$("#userrights").dialog({title: "User Access Rights ("+data+")", width: 560, height: 670, resizable: false}).dialogExtend({
			"closable" : true,
		    "maximizable" : false,
		    "minimizable" : true,
		});
	 },"html");
}

/* Inventory Management */
function showItems(icode) {
	var txtHTML = "<iframe id='frmcust' frameborder=0 width='100%' height='100%' src='items.master.php?sid="+Math.random()+"&icode="+icode+"'></iframe>";
	$("#itemlist").html(txtHTML);
	$("#itemlist").dialog({title: "Supplies & Materials", width: xWidth, height: 500,resizable: false }).dialogExtend({
		"closable" : true,
	    "maximizable" : true,
	    "minimizable" : true
	});
}

function showItemInfo(rid) {
	var txtHTML = "<iframe id='frmcust' frameborder=0 width='100%' height='100%' src='items.details.php?id="+rid+"&mod=1&sid="+Math.random()+"'></iframe>";
	$("#itemdetails").html(txtHTML);
	$("#itemdetails").dialog({title: "Product Details", width: 1120, height: 520, resizable: false }).dialogExtend({
		"closable" : true,
		"maximizable" : false,
		"minimizable" : true
	});
}

/* Patient Info */
function showPatients() {
	var txtHTML = "<iframe id='frmcust' frameborder=0 width='100%' height='100%' src='patient.master.php'></iframe>";
	$("#patientlist").html(txtHTML);
	$("#patientlist").dialog({title: "Patient Archive", width: xWidth, height: 540,resizable: false }).dialogExtend({
		"closable" : true,
	    "maximizable" : false,
	    "minimizable" : true
	});
}

function addPatient() {
	var txtHTML = "<iframe id='frmcust' frameborder=0 width='100%' height='100%' src='patient.details.php?mod=1&sid="+Math.random()+"'></iframe>";
	$("#patientdetails").html(txtHTML);
	$("#patientdetails").dialog({title: "Patient Information", width: 720, height: 920,resizable: false }).dialogExtend({
		"closable" : true,
	    "maximizable" : false,
	    "minimizable" : true
	});
}

function showPatientInfo(pid) {
	var txtHTML = "<iframe id='frmcust' frameborder=0 width='100%' height='100%' src='patient.details.php?mod=1&pid="+pid+"&sid="+Math.random()+"'></iframe>";
	$("#patientdetails").html(txtHTML);
	$("#patientdetails").dialog({title: "Patient Information", width: 720, height: 920,resizable: false }).dialogExtend({
		"closable" : true,
	    "maximizable" : false,
	    "minimizable" : true
	});
}

/* Mobile Sales Order & Registration */
function showCSO(){
	var txtHTML = "<iframe id='frmcust' frameborder=0 width='100%' height='100%' src='cso.list.php'></iframe>";
	$("#csolist").html(txtHTML);
	$("#csolist").dialog({title: "Mobile Service Order Summary", width: xWidth, height: 540, resizable: false }).dialogExtend({
		"closable" : true,
	    "maximizable" : false,
	    "minimizable" : true
	});
}

function viewCSO(cso_no) {
	var txtHTML = "<iframe id='frmcust' frameborder=0 width='100%' height='100%' src='cso.details.php?cso_no="+cso_no+"'></iframe>";
	$("#csodetails").html(txtHTML);
	$("#csodetails").dialog({title: "Mobile Service Order Details", width: 1120, height: 640, resizable: true }).dialogExtend({
		"closable" : true,
	    "maximizable" : true,
	    "minimizable" : true
	});
}

function printCSO(cso_no) {
	var txtHTML = "<iframe id='frmcust' frameborder=0 width='100%' height='100%' src='print/cso.print.php?cso_no="+cso_no+"&sid="+Math.random()+"'></iframe>";
	$("#csoprint").html(txtHTML);
	$("#csoprint").dialog({title: "PRINT >> MOBILE SERVICE ORDER", width: 560, height: 620, resizable: true }).dialogExtend({
		"closable" : true,
	    "maximizable" : true,
	    "minimizable" : true
	});
}

function printCSOChecklist(cso_no) {
	var txtHTML = "<iframe id='frmcust' frameborder=0 width='100%' height='100%' src='print/cso.checklist.php?cso_no="+cso_no+"&sid="+Math.random()+"'></iframe>";
	$("#csochecklist").html(txtHTML);
	$("#csochecklist").dialog({title: "PRINT >> Patient Attendance Checklist", width: 560, height: 620, resizable: true }).dialogExtend({
		"closable" : true,
	    "maximizable" : true,
	    "minimizable" : true
	});
}

function showRegistration() {
	var disDialog = $("#preselectSO").dialog({
		title: "Registration List", 
		width: 480,  
		resizable: false,
		modal: true,
		buttons: {
			"Open Registration List": function () { 
				var txtHTML = "<iframe id='frmRegistration' frameborder=0 width='100%' height='100%' src='registration.php?so_no="+$("#registrationSoNo").val()+"&sid="+Math.random()+"'></iframe>";
				$("#registration").html(txtHTML);
				$("#registration").dialog({
					title: "Registration List", 
					width: xWidth, 
					height: 620, 
					resizable: false 
				});
				disDialog.dialog("close");
			 },
			 "Cancel": function() {
				disDialog.dialog("close");
			 }
		}
	});
}



/* PEME */
function showPEME(){
	var txtHTML = "<iframe id='frmcust' frameborder=0 width='100%' height='100%' src='peme.list.php'></iframe>";
	$("#pemelist").html(txtHTML);
	$("#pemelist").dialog({title: "Physical/Medical Examination Requests", width: xWidth, height: 540, resizable: false }).dialogExtend({
		"closable" : true,
	    "maximizable" : false,
	    "minimizable" : true
	});
}

/* ECG Samples */

function showECGSamples() {
	var txtHTML = "<iframe id='frmcust' frameborder=0 width='100%' height='100%' src='ecgsamples.list.php?sid="+Math.random()+"'></iframe>";
	$("#ecglist").html(txtHTML);
	$("#ecglist").dialog({title: "Manage ECG Samples", width: xWidth, height: 500,resizable: false }).dialogExtend({
		"closable" : true,
	    "maximizable" : true,
	    "minimizable" : true
	});
}

function validateECGResult(lid,code) {
	$("#ecgResult").html("<iframe id='frmEcgResult' frameborder=0 width='100%' height='100%' src='result.ecg.php?lid="+lid+"'></iframe>");
	$("#ecgResult").dialog({
		title: "Write Result",
		width: xWidth,
		height: 695,
		resizeable: false,
		modal: false
	});
}

function printECGResult(so_no,code,serialno) {

	var txtHTML = "<iframe id='prntXrayResult' frameborder=0 width='100%' height='100%' src='print/result.ecg.php?so_no="+so_no+"&code="+code+"&serialno="+serialno+"&sid="+Math.random()+"&sid="+Math.random()+"'></iframe>";
	$("#report5").html(txtHTML);
	$("#report5").dialog({title: "Print - ECG RESULT", width: 560, height: 620, resizable: true }).dialogExtend({
		"closable" : true,
		"maximizable" : true,
		"minimizable" : true
	});

}

/* Xray & Radiology */
function showImgSamples() {
	var txtHTML = "<iframe id='frmcust' frameborder=0 width='100%' height='100%' src='imgsamples.list.php?sid="+Math.random()+"'></iframe>";
	$("#xraylist").html(txtHTML);
	$("#xraylist").dialog({title: "Manage Imaging Samples", width: xWidth, height: 500,resizable: false }).dialogExtend({
		"closable" : true,
	    "maximizable" : true,
	    "minimizable" : true
	});
}

function manageImgResults() {
	var txtHTML = "<iframe id='frmcust' frameborder=0 width='100%' height='100%' src='imgvalidation.php?sid="+Math.random()+"'></iframe>";
	$("#xrayresult").html(txtHTML);
	$("#xrayresult").dialog({title: "Validate Imaging Results", width: xWidth, height: 500,resizable: false }).dialogExtend({
		"closable" : true,
	    "maximizable" : true,
	    "minimizable" : true
	});
}

function xrayTemplateDetails(id) {
	$("#report3").html("<iframe id='xrayTemplate' frameborder=0 width='100%' height='100%' src='xray.templatedetails.php?id="+id+"&sid="+Math.random()+"'></iframe>");
	var dis = $("#report3").dialog({
		title: "X-Ray - Ultrasound Result Templates",
		width: 1024,
		height: 680,
		resizeable: false,
		modal: true,
		buttons: [
			{
				text: "Save Changes Made",
				icons: { primary: "ui-icon-check" },
				click: function() {
					var msg = '';

					if(confirm("Are you sure you want save this data?") == true) {
						var dataString = $('#xrayTemplate').contents().find('#frmXrayTemplate').serialize();
					
						//var dataString = $("#frmDescResult").serialize();
						dataString = "mod=saveXrayTemplate&" + dataString;
						$.ajax({
							type: "POST",
							url: "src/sjerp.php",
							data: dataString,
							success: function() {
								alert("Template Successfully Saved!");
								dis.dialog("close");
								$("#frmDescResult").trigger("reset");
							}
						});
					}
				}
			},
			{
				text: "Mark Template as Inactive",
				icons: { primary: "ui-icon-cancel" },
				click: function() {

				 }
			},
			{
				text: "Close",
				icons: { primary: "ui-icon-closethick" },
				click: function() { $(this).dialog("close"); }
			}
		]
	});
}

function printDescriptiveResult(so_no,code,serialno) {

	var txtHTML = "<iframe id='prntXrayResult' frameborder=0 width='100%' height='100%' src='print/result.xray.php?so_no="+so_no+"&code="+code+"&serialno="+serialno+"&sid="+Math.random()+"&sid="+Math.random()+"'></iframe>";
	$("#report5").html(txtHTML);
	$("#report5").dialog({title: "Print - XRAY RESULT", width: 560, height: 620, resizable: true }).dialogExtend({
		"closable" : true,
		"maximizable" : true,
		"minimizable" : true
	});

}

function showXBook() {

	$("#xraylog_dtf").datepicker();
	$("#xraylog_dt2").datepicker();

	$("#preSelectXbookSummary").dialog({
		title: "X-Ray Log Book",
		width: 480, 
		resizable: false,
		modal: true,
		buttons: {
			"Generate Report to Excel": function() {
				window.open("export/mobileXbookSummary.php?so_no="+$("#xBookSummary").val()+"&dtf="+$("#xraylog_dtf").val()+"&dt2="+$("#xraylog_dt2").val()+"&shift="+$("#xraylog_shift").val()+"&consultant="+$("#xraylog_consultant").val()+"&type="+$("#xraylog_type").val()+"&encode="+$("#xraylog_encoder").val()+"&xraylog_sort="+$("#xraylog_sort").val()+"&sid="+Math.random()+"&sid="+Math.random()+"","X-Ray Log Book","location=1,status=1,scrollbars=1,width=640,height=720");

			},
			"Close": function() {

				this.dialog("close");
			}

		}
	
	});
}

function showXTracker() {

	$("#xtracker_dtf").datepicker();
	$("#xtracker_dt2").datepicker();

	$("#preSelectXTracker").dialog({
		title: "X-Ray Tracker",
		width: 480, 
		resizable: false,
		modal: true,
		buttons: {
			"Generate Report to Excel": function() {
				window.open("export/mobileXtracker.php?so_no="+$("#xTracker").val()+"&dtf="+$("#xtracker_dtf").val()+"&dt2="+$("#xtracker_dt2").val()+"&shift="+$("#xtracker_shift").val()+"&consultant="+$("#xtracker_consultant").val()+"&type="+$("#xtracker_type").val()+"&encode="+$("#xtracker_encoder").val()+"&xtracker_sort="+$("#xtracker_sort").val()+"&sid="+Math.random()+"&sid="+Math.random()+"","X-Ray Tracker","location=1,status=1,scrollbars=1,width=640,height=720");

			},
			"Close": function() {

				this.dialog("close");
			}

		}
	
	});
}

function printECGResult(so_no,code,serialno) {

	var txtHTML = "<iframe id='prntXrayResult' frameborder=0 width='100%' height='100%' src='print/result.ecg.php?so_no="+so_no+"&code="+code+"&serialno="+serialno+"&sid="+Math.random()+"&sid="+Math.random()+"'></iframe>";
	$("#report5").html(txtHTML);
	$("#report5").dialog({title: "Print - ECG RESULT", width: 560, height: 620, resizable: true }).dialogExtend({
		"closable" : true,
		"maximizable" : true,
		"minimizable" : true
	});

}

function showXrayLogbook() {
	var dis = $("#xrayLogBook").dialog({
		title: "Xray Results Logbook", 
		width: 480,
		resizable: false, 
		buttons: [
			{
				icons: { primary: "ui-icon-print" },
				text: "Generate Logbook",
				click: function() { 
					var txtHTML = "<iframe id='printResult' frameborder=0 width='100%' height='100%' src='reports/xray.logbook.php?dtf="+$("#xraylog_dtf").val()+"&dt2="+$("#xraylog_dt2").val()+"&consultant="+$("#xraylog_consultant").val()+"&type="+$("#xraylog_type").val()+"&encode="+$("#xraylog_encoder").val()+"&xraylog_sort="+$("#xraylog_sort").val()+"&sid="+Math.random()+"&sid="+Math.random()+"'></iframe>";
					$("#report5").html(txtHTML);
					$("#report5").dialog({title: "Xray Logbook", width: 560, height: 620, resizable: true }).dialogExtend({
						"closable" : true,
						"maximizable" : true,
						"minimizable" : true
					});
				}
			},
			{
				icons: { primary: "ui-icon-closethick" },
				text: "Close Window",
				click: function() { 
					dis.dialog("close");
				}
			}
		]
	});
}

/**** Lab Functions */
	
function showLabCollection() {
	var txtHTML = "<iframe id='frmcust' frameborder=0 width='100%' height='100%' src='phleb.list.php?sid="+Math.random()+"'></iframe>";
	$("#itemlist").html(txtHTML);
	$("#itemlist").dialog({title: "Phleb Queueing List", width: xWidth, height: 500,resizable: false }).dialogExtend({
		"closable" : true,
	    "maximizable" : true,
	    "minimizable" : true
	});
}

function showServiceInfo(id) {
	var txtHTML = "<iframe id='frmcust' frameborder=0 width='100%' height='100%' src='service.details.php?id="+id+"&mod=1&sid="+Math.random()+"'></iframe>";
	$("#itemdetails").html(txtHTML);
	$("#itemdetails").dialog({title: "Service Details", width: 1120, height: 520, resizable: false }).dialogExtend({
		"closable" : true,
		"maximizable" : false,
		"minimizable" : true
	});
}

function printBarcode(serialno) {
	$.post("src/sjerp.php", { mod: "checkSerialStatus", serialno: serialno, sid: Math.random() }, function(result) {
		if(parseFloat(result['mycount']) > 0) {
			
			var txtHTML = "<iframe id='frmcust' frameborder=0 width='100%' height='100%' src='print/specimenbarcode.php?id="+serialno+"&sid="+Math.random()+"'></iframe>";
			$("#barcode").html(txtHTML);
			$("#barcode").dialog({title: "Print - Barcode", width: 400, height: 200, resizable: false }).dialogExtend({
				"closable" : true,
				"maximizable" : false,
				"minimizable" : true
			});
		
		} else {
			
			sendErrorMessage("It appears that this specimen record hasn't been saved yet.. Please click save and try to print the barcode again.");

		}
	},"json");
}

function printRoutingSlip(pid,cso_no) {
	var txtHTML = "<iframe id='frmcust' frameborder=0 width='100%' height='100%' src='print/routingslip.php?pid="+pid+"&cso_no="+cso_no+"&sid="+Math.random()+"'></iframe>";
	$("#routingslip").html(txtHTML);
	$("#routingslip").dialog({title: "PRINT >> ROUTING SLIP", width: 760, height: 620, resizable: true }).dialogExtend({
		"closable" : true,
		"maximizable" : true,
		"minimizable" : true
	});
}

function showResults() {
	var txtHTML = "<iframe id='frmcust' frameborder=0 width='100%' height='100%' src='results.list.php?sid="+Math.random()+"'></iframe>";
	$("#resultlist").html(txtHTML);
	$("#resultlist").dialog({title: "Results & Releasing", width: xWidth, height: 500,resizable: false }).dialogExtend({
		"closable" : true,
	    "maximizable" : true,
	    "minimizable" : true
	});
}

function showSamples() {
	var txtHTML = "<iframe id='frmcust' frameborder=0 width='100%' height='100%' src='samples.list.php?sid="+Math.random()+"'></iframe>";
	$("#srrlist").html(txtHTML);
	$("#srrlist").dialog({title: "Manage Lab Samples", width: xWidth, height: 500,resizable: false }).dialogExtend({
		"closable" : true,
	    "maximizable" : true,
	    "minimizable" : true
	});
}

function showValidation() {
	var txtHTML = "<iframe id='frmcust' frameborder=0 width='100%' height='100%' src='validation.php?sid="+Math.random()+"'></iframe>";
	$("#srrlist").html(txtHTML);
	$("#srrlist").dialog({title: "Validate Lab Results", width: xWidth, height: 500,resizable: false }).dialogExtend({
		"closable" : true,
	    "maximizable" : true,
	    "minimizable" : true
	});
}

function writeResult(lid,code) {
	switch(code) {
		case "L007":
		case "L015":
		case "L071":
		case "L086":
		case "L087":
		case "L096":	
		case "L099":
		case "L100":
		case "L039":
		case "L146":
			enumResult(lid,code);
		break;
		case "L010":
			cbcResult(lid,code);
		break;
		case "L011":
			bloodChem(lid,code);
		break;
		case "L012":
			uaResult(lid,code);
		break;
		case "L013":
			stoolExam(lid,code);
		break;
		case "L014":
			semenAnalysis(lid,code);
		break;
		case "L041":
			havResult(lid,code);
		break;
		case "L042":
		case "L045":
		case "L043":
		case "L050":
		case "L037":
		case "L051":
		case "L101":
		case "L062":
			pregnancyResult(lid,code);
		break;
		// case "L052":
		// 	lipidResult(lid,code);
		// break;
		case "L040":
		case "L053":
			bloodTyping(lid,code);
		break;
		case "L066":
			syphilisResult(lid,code);
		break;
		case "L064":
			dengueResult(lid,code);
		break;
		case "L075":
			ogttResult(lid,code);
		break;
		case "L044":
			hivResult(lid,code);
		break;
		case "L102":
			lipidResult(lid,code);
		break;
		default:
			singleValueResult(lid,code);
		break;
	}
}

function validateResult(lid,code) {
	switch(code) {
		case "L007":
		case "L015":
		case "L071":
		case "L086":
		case "L087":
		case "L096":	
		case "L099":
		case "L100":
		case "L039":
		case "L146":
			validateEnumResult(lid,code);
		break;
		case "L010":
			validateCbcResult(lid,code);
		break;
		case "L011":
			validateBloodChem(lid,code);
		break;
		case "L012":
			validateUaResult(lid,code);
		break;
		case "L013":
			validateStoolExam(lid,code);
		break;
		case "L014":
			validateSemenAnalysis(lid,code);
		break;
		case "L041":
			validateHavResult(lid,code);
		break;
		case "L042":
		case "L045":
		case "L043":
		case "L050":
		case "L051":
		case "L037":
		case "L101":
		case "L062":
				validatePregnancyResult(lid,code);
		break;
		// case "L052":
		// 	validateLipidResult(lid,code);
		// break;
		case "L040":
		case "L053":
			validateBloodtype(lid,code);
		break;
		case "L066":
			validateSyphilisResult(lid,code);
		break;
		case "L064":
			validateDengueResult(lid,code);
		break;
		case "L075":
			validateOgttResult(lid,code);
		break;
		case "L044":
			validateHivResult(lid,code);
		break;
		case "L102":
			validateLipidResult(lid,code);
		break;
		default:
			validateSingleValueResult(lid,code);
		break;
	}
}

function printResult(code,so_no,serialno,lid) {
	let xCode = code.substring(0,1);
	if(xCode == 'X') {
		var txtHTML = "<iframe id='printResult' frameborder=0 width='100%' height='100%' src='print/result.xray.php?so_no="+so_no+"&code="+code+"&serialno="+serialno+"&sid="+Math.random()+"&sid="+Math.random()+"'></iframe>";
	} else {
		switch(code) {

			case "L010":
				var txtHTML = "<iframe id='printResult' frameborder=0 width='100%' height='100%' src='print/result.cbc.php?so_no="+so_no+"&code="+code+"&serialno="+serialno+"&sid="+Math.random()+"&sid="+Math.random()+"'></iframe>";
			break;
			case "L011":
				var txtHTML = "<iframe id='printResult' frameborder=0 width='100%' height='100%' src='print/result.bloodchem.php?so_no="+so_no+"&code="+code+"&serialno="+serialno+"&sid="+Math.random()+"&sid="+Math.random()+"'></iframe>";
			break;
			case "L012":
				var txtHTML = "<iframe id='printResult' frameborder=0 width='100%' height='100%' src='print/result.ua.php?so_no="+so_no+"&code="+code+"&serialno="+serialno+"&sid="+Math.random()+"&sid="+Math.random()+"'></iframe>";
			break;
			case "L013":
				var txtHTML = "<iframe id='printResult' frameborder=0 width='100%' height='100%' src='print/result.stool.php?so_no="+so_no+"&code="+code+"&serialno="+serialno+"&sid="+Math.random()+"&sid="+Math.random()+"'></iframe>";
			break;
			case "L014":
				semenAnalysis(lid,code);
			break;
			case "L007":
			case "L015":
			case "L071":
			case "L086":
			case "L087":
			case "L100":
			case "L132":
			case "L039":
			case "L146":
				var txtHTML = "<iframe id='printResult' frameborder=0 width='100%' height='100%' src='print/result.enum.php?so_no="+so_no+"&code="+code+"&serialno="+serialno+"&sid="+Math.random()+"&sid="+Math.random()+"'></iframe>";
			break;
			
			case "L041":
				var txtHTML = "<iframe id='printResult' frameborder=0 width='100%' height='100%' src='print/result.hav.php?so_no="+so_no+"&code="+code+"&serialno="+serialno+"&sid="+Math.random()+"&sid="+Math.random()+"'></iframe>";
			break;
			case "L042":
			case "L045":
			case "L043":
			case "L050":
			case "L051":
			case "L037":
			case "L101":
			case "L062":
				var txtHTML = "<iframe id='printResult' frameborder=0 width='100%' height='100%' src='print/result.pt.php?so_no="+so_no+"&code="+code+"&serialno="+serialno+"&sid="+Math.random()+"&sid="+Math.random()+"'></iframe>";
			break;
			// case "L052":
			// 	var txtHTML = "<iframe id='printResult' frameborder=0 width='100%' height='100%' src='print/result.lipidpanel.php?so_no="+so_no+"&code="+code+"&serialno="+serialno+"&sid="+Math.random()+"&sid="+Math.random()+"'></iframe>";
			// break;
			case "L040":
			case "L053":
				var txtHTML = "<iframe id='printResult' frameborder=0 width='100%' height='100%' src='print/result.bt.php?so_no="+so_no+"&code="+code+"&serialno="+serialno+"&sid="+Math.random()+"&sid="+Math.random()+"'></iframe>";
			break;
			case "L066":
				var txtHTML = "<iframe id='printResult' frameborder=0 width='100%' height='100%' src='print/result.syphilis.php?so_no="+so_no+"&code="+code+"&serialno="+serialno+"&sid="+Math.random()+"&sid="+Math.random()+"'></iframe>";
			break;
			case "L064":
				var txtHTML = "<iframe id='printResult' frameborder=0 width='100%' height='100%' src='print/result.dengue.php?so_no="+so_no+"&code="+code+"&serialno="+serialno+"&sid="+Math.random()+"&sid="+Math.random()+"'></iframe>";
			break;
			case "L075":
				var txtHTML = "<iframe id='printResult' frameborder=0 width='100%' height='100%' src='print/result.ogtt.php?so_no="+so_no+"&code="+code+"&serialno="+serialno+"&sid="+Math.random()+"&sid="+Math.random()+"'></iframe>";
			break;
			case "L044":
				var txtHTML = "<iframe id='printResult' frameborder=0 width='100%' height='100%' src='print/result.hiv.php?so_no="+so_no+"&code="+code+"&serialno="+serialno+"&sid="+Math.random()+"&sid="+Math.random()+"'></iframe>";
			break;
			case "O001":
				var txtHTML = "<iframe id='printResult' frameborder=0 width='100%' height='100%' src='print/result.ecg.php?so_no="+so_no+"&code="+code+"&serialno="+serialno+"&sid="+Math.random()+"&sid="+Math.random()+"'></iframe>";
			break;
			case "L102":
				var txtHTML = "<iframe id='printResult' frameborder=0 width='100%' height='100%' src='print/result.lipidpanel.php?so_no="+so_no+"&code="+code+"&serialno="+serialno+"&sid="+Math.random()+"&sid="+Math.random()+"'></iframe>";
			break;
			case "O001":
				var txtHTML = "<iframe id='prntXrayResult' frameborder=0 width='100%' height='100%' src='print/result.ecg.php?so_no="+so_no+"&code="+code+"&serialno="+serialno+"&sid="+Math.random()+"&sid="+Math.random()+"'></iframe>";
			break;
			default:
				var txtHTML = "<iframe id='printResult' frameborder=0 width='100%' height='100%' src='print/result.singlevalue.php?so_no="+so_no+"&code="+code+"&serialno="+serialno+"&sid="+Math.random()+"&sid="+Math.random()+"'></iframe>";
			break;
		}
	}
	
	$("#report3").html(txtHTML);
	$("#report3").dialog({title: "Print Result", width: 560, height: 620, resizable: true }).dialogExtend({
		"closable" : true,
		"maximizable" : true,
		"minimizable" : true
	});

}

function writeImagingResult(lid,code) {
	$("#descResult").html("<iframe id='frmResult' frameborder=0 width='100%' height='100%' src='result.descriptive.php?lid="+lid+"'></iframe>");
	$("#descResult").dialog({
		title: "Write Result",
		width: 1024,
		height: 695,
		resizeable: false,
		modal: false
	});
}

function writeECGResult(lid,code) {
	$("#ecgResult").html("<iframe id='frmResult' frameborder=0 width='100%' height='100%' src='result.ecg.php?lid="+lid+"'></iframe>");
	$("#ecgResult").dialog({
		title: "Validate ECG Result",
		width: 1200,
		height: 695,
		resizeable: false,
		modal: false
	});
}

function lipidResult(lid,code) {

	$("#lipid_date").datepicker();
	
	$.post("src/sjerp.php", {
		mod: "resultSingle",
		submod: "lipidPanel",
		lid: lid,
		sid: Math.random() },
		function(data) {

			$("#lipid_sono").val(data['myso']);
			$("#lipid_sodate").val(data['sodate']);
			$("#lipid_pid").val(data['mypid']);
			$("#lipid_pname").val(data['pname']);
			$("#lipid_gender").val(data['gender']);
			$("#lipid_birthdate").val(data['bday']);
			$("#lipid_age").val(data['age']);
			$("#lipid_patientstat").val(data['patientstatus']);
			$("#lipid_physician").val(data['physician']);
			$("#lipid_procedure").val(data['procedure']);
			$("#lipid_code").val(data['code']);
			$("#lipid_spectype").val(data['sampletype']);
			$("#lipid_serialno").val(data['serialno']);
			$("#lipid_testkit").val(data['testkit']);
			$("#lipid_testkit_lotno").val(data['lotno']);
			$("#lipid_testkit_expiry").val(data['expiry']);
			$("#lipid_extractdate").val(data['exday']);
			$("#lipid_extracttime").val(data['etime']);
			$("#lipid_extractby").val(data['extractby']);
			$("#lipid_cholesterol").val(data['cholesterol']);
			$("#lipid_triglycerides").val(data['triglycerides']);
			$("#lipid_hdl").val(data['hdl']);
			$("#lipid_ldl").val(data['ldl']);
			$("#lipid_vldl").val(data['vldl']);
			$("#lipid_result_by").val(data['performed_by']);
			$("#lipid_remarks").val(data['remarks']);

			var dis = $("#lipidResult").dialog({
				title: "Write Result",
				width: 1024,
				height: 705,
				resizeable: false,
				modal: true,
				buttons: [
					{
						text: "Save Result Pending Validation",
						icons: { primary: "ui-icon-check" },
						click: function() {
							var msg = '';
	
							if(confirm("Are you sure you want save this data?") == true) {
							var dataString = $("#frmLipidResult").serialize();
								dataString = "mod=saveLipidPanel&" + dataString;
								$.ajax({
									type: "POST",
									url: "src/sjerp.php",
									data: dataString,
									success: function() {
										alert("Result Successfully Saved!");
										$("#frmLipidResult").trigger("reset");
										dis.dialog("close");
										showValidation();
				
									}
								});
							}
						}
					},
					{
						text: "Close",
						icons: { primary: "ui-icon-closethick" },
						click: function() { $(this).dialog("close"); }
					}
				]
			});

		},"json"
	);
}

function validateLipidResult(lid,code) {

	$("#lipid_date").datepicker();
	
	$.post("src/sjerp.php", {
		mod: "resultSingle",
		submod: "lipidPanel",
		lid: lid,
		sid: Math.random() },
		function(data) {

			$("#lipid_sono").val(data['myso']);
			$("#lipid_sodate").val(data['sodate']);
			$("#lipid_pid").val(data['mypid']);
			$("#lipid_pname").val(data['pname']);
			$("#lipid_gender").val(data['gender']);
			$("#lipid_birthdate").val(data['bday']);
			$("#lipid_age").val(data['age']);
			$("#lipid_patientstat").val(data['patientstatus']);
			$("#lipid_physician").val(data['physician']);
			$("#lipid_procedure").val(data['procedure']);
			$("#lipid_code").val(data['code']);
			$("#lipid_spectype").val(data['sampletype']);
			$("#lipid_serialno").val(data['serialno']);
			$("#lipid_testkit").val(data['testkit']);
			$("#lipid_testkit_lotno").val(data['lotno']);
			$("#lipid_testkit_expiry").val(data['expiry']);
			$("#lipid_extractdate").val(data['exday']);
			$("#lipid_extracttime").val(data['etime']);
			$("#lipid_extractby").val(data['extractby']);
			$("#lipid_cholesterol").val(data['cholesterol']);
			$("#lipid_triglycerides").val(data['triglycerides']);
			$("#lipid_hdl").val(data['hdl']);
			$("#lipid_ldl").val(data['ldl']);
			$("#lipid_vldl").val(data['vldl']);
			$("#lipid_result_by").val(data['performed_by']);
			$("#lipid_remarks").val(data['remarks']);

			var dis = $("#lipidResult").dialog({
				title: "Validate Lipid Panel Result",
				width: 1024,
				height: 705,
				resizeable: false,
				modal: true,
				buttons: [
					{
						text: "Save Result & Mark as Validated",
						icons: { primary: "ui-icon-check" },
						click: function() {
							var msg = '';
	
							if(confirm("Are you sure you want confirm and validate this result?") == true) {
							var dataString = $("#frmLipidResult").serialize();
								dataString = "mod=validateLipidResult&" + dataString;
								$.ajax({
									type: "POST",
									url: "src/sjerp.php",
									data: dataString,
									success: function() {
										alert("Result Successfully Validated!");
										$("#frmLipidResult").trigger("reset");
										dis.dialog("close");
										showValidation();
				
									}
								});
							}
						}
					},
					{
						text: "Print Result",
						icons: { primary: "ui-icon-print" },
						click: function() {
							
							var so_no = $("#lipid_sono").val();
							var code = $("#lipid_code").val();
							var serialno = $("#lipid_serialno").val();

							var txtHTML = "<iframe id='printSingleValue' frameborder=0 width='100%' height='100%' src='print/result.lipidpanel.php?so_no="+so_no+"&code="+code+"&serialno="+serialno+"&sid="+Math.random()+"&sid="+Math.random()+"'></iframe>";
							$("#report5").html(txtHTML);
							$("#report5").dialog({title: "Result - LIPID PANEL", width: 560, height: 620, resizable: true }).dialogExtend({
								"closable" : true,
								"maximizable" : true,
								"minimizable" : true
							});

						 }
					},
					{
						text: "Close",
						icons: { primary: "ui-icon-closethick" },
						click: function() { $(this).dialog("close"); }
					}
				]
			});

		},"json"
	);
}

function singleValueResult(lid,code) {

	$("#sresult_date").datepicker();
	
	$.post("src/sjerp.php", {
		mod: "resultSingle",
		submod: "labSingle",
		lid: lid,
		sid: Math.random() },
		function(data) {

			$("#sresult_sono").val(data['myso']);
			$("#sresult_sodate").val(data['sodate']);
			$("#sresult_pid").val(data['mypid']);
			$("#sresult_pname").val(data['pname']);
			$("#sresult_gender").val(data['gender']);
			$("#sresult_birthdate").val(data['bday']);
			$("#sresult_age").val(data['age']);
			$("#sresult_patientstat").val(data['patientstatus']);
			$("#sresult_physician").val(data['physician']);
			$("#sresult_procedure").val(data['procedure']);
			$("#sresult_code").val(data['code']);
			$("#sresult_spectype").val(data['sampletype']);
			$("#sresult_serialno").val(data['serialno']);
			$("#sresult_testkit").val(data['testkit']);
			$("#sresult_testkit_lotno").val(data['lotno']);
			$("#sresult_testkit_expiry").val(data['expiry']);
			$("#sresult_extractdate").val(data['exday']);
			$("#sresult_extracttime").val(data['etime']);
			$("#sresult_by").val(data['extractby']);
			$("#sresult_location").val(data['location']);
			$("#sresult_attribute").val(data['attribute']);
			$("#sresult_unit").val(data['unit']);
			$("#sresult_value").val(data['value']);
			$("#sresult_result_by").val(data['performed_by']);
			$("#sresult_remarks").val(data['remarks']);

			var dis = $("#singleValueResult").dialog({
				title: "Write Result",
				width: 1040,
				height: 705,
				resizeable: false,
				modal: true,
				buttons: [
					{
						text: "Save Result Pending Validation",
						icons: { primary: "ui-icon-check" },
						click: function() {
						
							
							if($("#sresult_value").val() == '') {
								parent.sendErrorMessage("Result Value is empty!");
							} else {
								if(confirm("Are you sure you want save this data?") == true) {
									var dataString = $("#frmsingleValue").serialize();
										dataString = "mod=saveSingleValueResult&" + dataString;
										$.ajax({
											type: "POST",
											url: "src/sjerp.php",
											data: dataString,
											success: function() {
											alert("Result Successfully Saved!");
											dis.dialog("close");
											$("#singleValueResult").trigger("reset");
										}
									});
								}
							}

						}
					},
					{
						text: "Close",
						icons: { primary: "ui-icon-closethick" },
						click: function() { $(this).dialog("close"); $("#frmsingleValue").trigger("reset"); }
					}
				]
			});

		},"json"
	);
}

function validateSingleValueResult(lid,code) {

	$.post("src/sjerp.php", {
		mod: "resultSingle",
		submod: "labSingle",
		lid: lid,
		sid: Math.random() },
		function(data) {

			$("#sresult_sono").val(data['myso']);
			$("#sresult_sodate").val(data['sodate']);
			$("#sresult_pid").val(data['mypid']);
			$("#sresult_pname").val(data['pname']);
			$("#sresult_gender").val(data['gender']);
			$("#sresult_birthdate").val(data['bday']);
			$("#sresult_age").val(data['age']);
			$("#sresult_patientstat").val(data['patientstatus']);
			$("#sresult_physician").val(data['physician']);
			$("#sresult_procedure").val(data['procedure']);
			$("#sresult_code").val(data['code']);
			$("#sresult_spectype").val(data['sampletype']);
			$("#sresult_serialno").val(data['serialno']);
			$("#sresult_testkit").val(data['testkit']);
			$("#sresult_testkit_lotno").val(data['lotno']);
			$("#sresult_testkit_expiry").val(data['expiry']);
			$("#sresult_extractdate").val(data['exday']);
			$("#sresult_extracttime").val(data['etime']);
			$("#sresult_by").val(data['extractby']);
			$("#sresult_location").val(data['location']);
			$("#sresult_attribute").val(data['attribute']);
			$("#sresult_unit").val(data['unit']);
			$("#sresult_value").val(data['value']);
			$("#sresult_result_by").val(data['performed_by']);
			$("#sresult_remarks").val(data['remarks']);

			var dis = $("#singleValueResult").dialog({
				title: "Validate Result",
				width: 1040,
				height: 705,
				resizeable: false,
				modal: true,
				buttons: [
					{
						text: "Save Changes & Mark as Validated",
						icons: { primary: "ui-icon-check" },
						click: function() {
						
							
							if($("#sresult_value").val() == '') {
								parent.sendErrorMessage("Result Value is empty!");
							} else {
								if(confirm("Are you sure you want save this data?") == true) {
									var dataString = $("#frmsingleValue").serialize();
										dataString = "mod=validateSingleValueResult&" + dataString;
										$.ajax({
											type: "POST",
											url: "src/sjerp.php",
											data: dataString,
											success: function() {
											alert("Result Successfully Marked as Validated!");
											$("#singleValueResult").trigger("reset");
											dis.dialog("close");
											showValidation();
										}
									});
								}
							}

						}
					},
					{
						text: "Print Result",
						icons: { primary: "ui-icon-print" },
						click: function() {
							
							var so_no = $("#sresult_sono").val();
							var code = $("#sresult_code").val();
							var serialno = $("#sresult_serialno").val();

							var txtHTML = "<iframe id='printSingleValue' frameborder=0 width='100%' height='100%' src='print/result.singlevalue.php?so_no="+so_no+"&code="+code+"&serialno="+serialno+"&sid="+Math.random()+"&sid="+Math.random()+"'></iframe>";
							$("#report5").html(txtHTML);
							$("#report5").dialog({title: "Result - "+ $("#sresult_procedure").val() +"", width: 560, height: 620, resizable: true }).dialogExtend({
								"closable" : true,
								"maximizable" : true,
								"minimizable" : true
							});

						 }
					},
					{
						text: "Close",
						icons: { primary: "ui-icon-closethick" },
						click: function() { $(this).dialog("close"); $("#frmsingleValue").trigger("reset"); }
					}
				]
			});

		},"json"
	);
}

function hivResult(lid,code) {

	$("#hiv_date").datepicker();
	
	$.post("src/sjerp.php", {
		mod: "resultSingle",
		submod: "hivResult",
		lid: lid,
		sid: Math.random() },
		function(data) {

			$("#hiv_sono").val(data['myso']);
			$("#hiv_sodate").val(data['sodate']);
			$("#hiv_pid").val(data['mypid']);
			$("#hiv_pname").val(data['pname']);
			$("#hiv_gender").val(data['gender']);
			$("#hiv_birthdate").val(data['bday']);
			$("#hiv_age").val(data['age']);
			$("#hiv_patientstat").val(data['patientstatus']);
			$("#hiv_physician").val(data['physician']);
			$("#hiv_procedure").val(data['procedure']);
			$("#hiv_code").val(data['code']);
			$("#hiv_spectype").val(data['sampletype']);
			$("#hiv_serialno").val(data['serialno']);
			$("#hiv_extractdate").val(data['exday']);
			$("#hiv_extracttime").val(data['etime']);
			$("#hiv_extractby").val(data['extractby']);
			$("#hiv_one").val(data['hiv_one']);
			$("#hiv_two").val(data['hiv_two']);
			$("#hiv_half").val(data['hiv_half']);

			var dis = $("#hivResult").dialog({
				title: "Write Result",
				width: 540,
				resizeable: false,
				modal: true,
				buttons: [
					{
						text: "Save Result Pending Validation",
						icons: { primary: "ui-icon-check" },
						click: function() {
							var msg = '';
						
								if(confirm("Are you sure you want save this data?") == true) {
									var dataString = $("#frmHivResult").serialize();
										dataString = "mod=saveHivResult&" + dataString;
										$.ajax({
											type: "POST",
											url: "src/sjerp.php",
											data: dataString,
											success: function() {
												alert("Result Successfully Saved!");
												dis.dialog("close");
												$("#frmHivResult").trigger("reset");
										}
									});
								}
							}
					},
					{
						text: "Close",
						icons: { primary: "ui-icon-closethick" },
						click: function() { $(this).dialog("close"); $("#frmHivResult").trigger("reset"); }
					}
				]
			});

		},"json"
	);
}

function validateHivResult(lid,code) {

	$("#hiv_date").datepicker();
	
	$.post("src/sjerp.php", {
		mod: "resultSingle",
		submod: "hivResult",
		lid: lid,
		sid: Math.random() },
		function(data) {

			$("#hiv_sono").val(data['myso']);
			$("#hiv_sodate").val(data['sodate']);
			$("#hiv_pid").val(data['mypid']);
			$("#hiv_pname").val(data['pname']);
			$("#hiv_gender").val(data['gender']);
			$("#hiv_birthdate").val(data['bday']);
			$("#hiv_age").val(data['age']);
			$("#hiv_patientstat").val(data['patientstatus']);
			$("#hiv_physician").val(data['physician']);
			$("#hiv_procedure").val(data['procedure']);
			$("#hiv_code").val(data['code']);
			$("#hiv_spectype").val(data['sampletype']);
			$("#hiv_serialno").val(data['serialno']);
			$("#hiv_extractdate").val(data['exday']);
			$("#hiv_extracttime").val(data['etime']);
			$("#hiv_extractby").val(data['extractby']);
			$("#hiv_one").val(data['hiv_one']);
			$("#hiv_two").val(data['hiv_two']);
			$("#hiv_half").val(data['hiv_half']);

			var dis = $("#hivResult").dialog({
				title: "Validate HIV Result",
				width: 540,
				resizeable: false,
				modal: true,
				buttons: [
					{
						text: "Save Changes & Mark as Validated",
						icons: { primary: "ui-icon-check" },
						click: function() {
							var msg = '';
	
							if(confirm("Are you sure you want confirm and validate this result?") == true) {
							var dataString = $("#frmHivResult").serialize();
								dataString = "mod=validateHivResult&" + dataString;
								$.ajax({
									type: "POST",
									url: "src/sjerp.php",
									data: dataString,
									success: function() {
										alert("Result Successfully Validated!");
										$("#frmHivResult").trigger("reset");
										dis.dialog("close");
										showValidation();
				
									}
								});
							}
						}
					},
					{
						text: "Print Result",
						icons: { primary: "ui-icon-print" },
						click: function() {
							
							var so_no = $("#hiv_sono").val();
							var serialno = $("#hiv_serialno").val();
							var code = $("#hiv_code").val();
							
							var txtHTML = "<iframe id='prntXrayResult' frameborder=0 width='100%' height='100%' src='print/result.hiv.php?so_no="+so_no+"&code="+code+"&serialno="+serialno+"&sid="+Math.random()+"&sid="+Math.random()+"'></iframe>";
							$("#report10").html(txtHTML);
							$("#report10").dialog({title: "Print - HIV Result", width: 560, height: 620, resizable: true }).dialogExtend({
								"closable" : true,
								"maximizable" : true,
								"minimizable" : true
							});
		
		
						 }
					},
					{
						text: "Close",
						icons: { primary: "ui-icon-closethick" },
						click: function() { $(this).dialog("close"); }
					}
				]
			});

		},"json"
	);
}

function syphilisResult(lid,code) {

	$("#enum_date").datepicker();
	
	$.post("src/sjerp.php", {
		mod: "resultSingle",
		submod: "enumResult",
		lid: lid,
		sid: Math.random() },
		function(data) {

			$("#enum_sono").val(data['myso']);
			$("#enum_sodate").val(data['sodate']);
			$("#enum_pid").val(data['mypid']);
			$("#enum_pname").val(data['pname']);
			$("#enum_gender").val(data['gender']);
			$("#enum_birthdate").val(data['bday']);
			$("#enum_age").val(data['age']);
			$("#enum_patientstat").val(data['patientstatus']);
			$("#enum_physician").val(data['physician']);
			$("#enum_procedure").val(data['procedure']);
			$("#enum_code").val(data['code']);
			$("#enum_spectype").val(data['sampletype']);
			$("#enum_serialno").val(data['serialno']);
			$("#enum_testkit").val(data['testkit']);
			$("#enum_testkit_lotno").val(data['lotno']);
			$("#enum_testkit_expiry").val(data['expiry']);
			$("#enum_extractdate").val(data['exday']);
			$("#enum_extracttime").val(data['etime']);
			$("#enum_extractby").val(data['extractby']);
			$("#enum_result").val(data['result']);
			$("#enum_result_by").val(data['performed_by']);
			$("#enum_remarks").val(data['remarks']);

			var dis = $("#enumResult").dialog({
				title: "Write Result",
				width: 540,
				resizeable: false,
				modal: true,
				buttons: [
					{
						text: "Save Result Pending Validation",
						icons: { primary: "ui-icon-check" },
						click: function() {
							var msg = '';
							if($("#enum_result").val() != '') {
								if(confirm("Are you sure you want save this data?") == true) {
									var dataString = $("#frmEnumResult").serialize();
									dataString = "mod=saveEnumResult&" + dataString;
									$.ajax({
										type: "POST",
										url: "src/sjerp.php",
										data: dataString,
										success: function() {
											alert("Result Successfully Saved!");
											$("#frmEnumResult").trigger("reset");
											dis.dialog("close");
										}
									});
								}
							}
						}
					},
					{
						text: "Close",
						icons: { primary: "ui-icon-closethick" },
						click: function() { $(this).dialog("close"); }
					}
				]
			});

		},"json"
	);
}

function validateSyphilisResult(lid,code) {
	
	$.post("src/sjerp.php", {
		mod: "resultSingle",
		submod: "enumResult",
		lid: lid,
		sid: Math.random() },
		function(data) {

			$("#enum_sono").val(data['myso']);
			$("#enum_sodate").val(data['sodate']);
			$("#enum_pid").val(data['mypid']);
			$("#enum_pname").val(data['pname']);
			$("#enum_gender").val(data['gender']);
			$("#enum_birthdate").val(data['bday']);
			$("#enum_age").val(data['age']);
			$("#enum_patientstat").val(data['patientstatus']);
			$("#enum_physician").val(data['physician']);
			$("#enum_procedure").val(data['procedure']);
			$("#enum_code").val(data['code']);
			$("#enum_spectype").val(data['sampletype']);
			$("#enum_serialno").val(data['serialno']);
			$("#enum_testkit").val(data['testkit']);
			$("#enum_testkit_lotno").val(data['lotno']);
			$("#enum_testkit_expiry").val(data['expiry']);
			$("#enum_extractdate").val(data['exday']);
			$("#enum_extracttime").val(data['etime']);
			$("#enum_extractby").val(data['extractby']);
			$("#enum_result").val(data['result']);
			$("#enum_result_by").val(data['performed_by']);
			$("#enum_remarks").val(data['remarks']);

			var dis = $("#enumResult").dialog({
				title: "Validate Result",
				width: 540,
				resizeable: false,
				modal: true,
				buttons: [
					{
						text: "Publish & Mark as Validated",
						icons: { primary: "ui-icon-check" },
						click: function() {
							var msg = '';
	
							if($("#enum_result").val() != '') {
								if(confirm("Are you sure you want save this data?") == true) {
									var dataString = $("#frmEnumResult").serialize();
									dataString = "mod=validateEnumResult&" + dataString;
									$.ajax({
										type: "POST",
										url: "src/sjerp.php",
										data: dataString,
										success: function() {
											alert("Result Successfully Confirmed & Published");
											$("#frmEnumResult").trigger("reset");
											dis.dialog("close");
											showValidation();
										}
									});
								}
							}
						}
					},
					{
						text: "Print Result",
						icons: { primary: "ui-icon-print" },
						click: function() {
							
							var so_no = $("#enum_sono").val();
							var code = $("#enum_code").val();
							var serialno = $("#enum_serialno").val();

							var txtHTML = "<iframe id='printSingleValue' frameborder=0 width='100%' height='100%' src='print/result.syphilis.php?so_no="+$("#enum_sono").val()+"&code="+$("#enum_code").val()+"&serialno="+$("#enum_serialno").val()+"&sid="+Math.random()+"&sid="+Math.random()+"'></iframe>";
							$("#report5").html(txtHTML);
							$("#report5").dialog({title: "Result - "+ $("#enum_procedure").val() +"", width: 560, height: 620, resizable: true }).dialogExtend({
								"closable" : true,
								"maximizable" : true,
								"minimizable" : true
							});

						 }
					},
					{
						text: "Close",
						icons: { primary: "ui-icon-closethick" },
						click: function() { $(this).dialog("close"); }
					}
				]
			});

		},"json"
	);
}

function havResult(lid,code) {

	$("#hav_date").datepicker();
	
	$.post("src/sjerp.php", {
		mod: "resultSingle",
		submod: "enumResult",
		lid: lid,
		sid: Math.random() },
		function(data) {

			$("#hav_sono").val(data['myso']);
			$("#hav_sodate").val(data['sodate']);
			$("#hav_pid").val(data['mypid']);
			$("#hav_pname").val(data['pname']);
			$("#hav_gender").val(data['gender']);
			$("#hav_birthdate").val(data['bday']);
			$("#hav_age").val(data['age']);
			$("#hav_patientstat").val(data['patientstatus']);
			$("#hav_physician").val(data['physician']);
			$("#hav_procedure").val(data['procedure']);
			$("#hav_code").val(data['code']);
			$("#hav_spectype").val(data['sampletype']);
			$("#hav_serialno").val(data['serialno']);
			$("#hav_extractdate").val(data['exday']);
			$("#hav_extracttime").val(data['etime']);
			$("#hav_extractby").val(data['extractby']);
			$("#hav_result").val(data['result']);
			$("#hav_remarks").val(data['remarks']);

			var dis = $("#havResult").dialog({
				title: "Write Result",
				width: 540,
				resizeable: false,
				modal: true,
				buttons: [
					{
						text: "Save Result Pending Validation",
						icons: { primary: "ui-icon-check" },
						click: function() {
							var msg = '';
	
							if(confirm("Are you sure you want save this data?") == true) {
							var dataString = $("#frmHavResult").serialize();
								dataString = "mod=saveHavResult&" + dataString;
								$.ajax({
									type: "POST",
									url: "src/sjerp.php",
									data: dataString,
									success: function() {
										alert("Result Successfully Saved!");
										$("#frmHavResult").trigger("reset");
										dis.dialog("close");
				
									}
								});
							}
						}
					},
					{
						text: "Close",
						icons: { primary: "ui-icon-closethick" },
						click: function() { $(this).dialog("close"); }
					}
				]
			});

		},"json"
	);
}

function validateHavResult(lid,code) {

	$("#hav_date").datepicker();
	
	$.post("src/sjerp.php", {
		mod: "resultSingle",
		submod: "enumResult",
		lid: lid,
		sid: Math.random() },
		function(data) {

			$("#hav_sono").val(data['myso']);
			$("#hav_sodate").val(data['sodate']);
			$("#hav_pid").val(data['mypid']);
			$("#hav_pname").val(data['pname']);
			$("#hav_gender").val(data['gender']);
			$("#hav_birthdate").val(data['bday']);
			$("#hav_age").val(data['age']);
			$("#hav_patientstat").val(data['patientstatus']);
			$("#hav_physician").val(data['physician']);
			$("#hav_procedure").val(data['procedure']);
			$("#hav_code").val(data['code']);
			$("#hav_spectype").val(data['sampletype']);
			$("#hav_serialno").val(data['serialno']);
			$("#hav_extractdate").val(data['exday']);
			$("#hav_extracttime").val(data['etime']);
			$("#hav_extractby").val(data['extractby']);
			$("#hav_result").val(data['result']);
			$("#hav_remarks").val(data['remarks']);

			var dis = $("#havResult").dialog({
				title: "Validate HAV IgG/IgM Result",
				width: 540,
				resizeable: false,
				modal: true,
				buttons: [
					{
						text: "Save Changes & Mark as Validated",
						icons: { primary: "ui-icon-check" },
						click: function() {
							var msg = '';
	
							if(confirm("Are you sure you want confirm and validate this result?") == true) {
							var dataString = $("#frmHavResult").serialize();
								dataString = "mod=validateHavResult&" + dataString;
								$.ajax({
									type: "POST",
									url: "src/sjerp.php",
									data: dataString,
									success: function() {
										alert("Result Successfully Validated!");
										$("#frmHavResult").trigger("reset");
										dis.dialog("close");
										showValidation();
				
									}
								});
							}
						}
					},
					{
						text: "Print Result",
						icons: { primary: "ui-icon-print" },
						click: function() {
							
							var so_no = $("#hav_sono").val();
							var serialno = $("#hav_serialno").val();
							var code = $("#hav_code").val();
							
							var txtHTML = "<iframe id='prntXrayResult' frameborder=0 width='100%' height='100%' src='print/result.hav.php?so_no="+so_no+"&code="+code+"&serialno="+serialno+"&sid="+Math.random()+"&sid="+Math.random()+"'></iframe>";
							$("#report1").html(txtHTML);
							$("#report1").dialog({title: "Print - HAV IgG/IgM Result", width: 560, height: 620, resizable: true }).dialogExtend({
								"closable" : true,
								"maximizable" : true,
								"minimizable" : true
							});
		
		
						 }
					},
					{
						text: "Close",
						icons: { primary: "ui-icon-closethick" },
						click: function() { $(this).dialog("close"); }
					}
				]
			});

		},"json"
	);
}

function enumResult(lid,code) {

	$("#enum_date").datepicker();
	
	$.post("src/sjerp.php", {
		mod: "resultSingle",
		submod: "enumResult",
		lid: lid,
		sid: Math.random() },
		function(data) {

			$("#enum_sono").val(data['myso']);
			$("#enum_sodate").val(data['sodate']);
			$("#enum_pid").val(data['mypid']);
			$("#enum_pname").val(data['pname']);
			$("#enum_gender").val(data['gender']);
			$("#enum_birthdate").val(data['bday']);
			$("#enum_age").val(data['age']);
			$("#enum_patientstat").val(data['patientstatus']);
			$("#enum_physician").val(data['physician']);
			$("#enum_procedure").val(data['procedure']);
			$("#enum_code").val(data['code']);
			$("#enum_spectype").val(data['sampletype']);
			$("#enum_serialno").val(data['serialno']);
			$("#enum_testkit").val(data['testkit']);
			$("#enum_testkit_lotno").val(data['lotno']);
			$("#enum_testkit_expiry").val(data['expiry']);
			$("#enum_extractdate").val(data['exday']);
			$("#enum_extracttime").val(data['etime']);
			$("#enum_extractby").val(data['extractby']);
			$("#enum_result").val(data['result']);
			$("#enum_result_by").val(data['performed_by']);
			$("#enum_remarks").val(data['remarks']);

			var dis = $("#enumResult").dialog({
				title: "Write Result",
				width: 1024,
				height: 690,
				resizeable: false,
				modal: true,
				buttons: [
					{
						text: "Save Result Pending Validation",
						icons: { primary: "ui-icon-check" },
						click: function() {
							var msg = '';
							if($("#enum_result").val() != '') {
								if(confirm("Are you sure you want save this data?") == true) {
									var dataString = $("#frmEnumResult").serialize();
									dataString = "mod=saveEnumResult&" + dataString;
									$.ajax({
										type: "POST",
										url: "src/sjerp.php",
										data: dataString,
										success: function() {
											alert("Result Successfully Saved!");
											$("#frmEnumResult").trigger("reset");
											dis.dialog("close");
											showValidation();
										}
									});
								}
							}
						}
					},
					{
						text: "Close",
						icons: { primary: "ui-icon-closethick" },
						click: function() { $(this).dialog("close"); }
					}
				]
			});

		},"json"
	);
}

function validateEnumResult(lid,code) {
	
	$.post("src/sjerp.php", {
		mod: "resultSingle",
		submod: "enumResult",
		lid: lid,
		sid: Math.random() },
		function(data) {

			$("#enum_sono").val(data['myso']);
			$("#enum_sodate").val(data['sodate']);
			$("#enum_pid").val(data['mypid']);
			$("#enum_pname").val(data['pname']);
			$("#enum_gender").val(data['gender']);
			$("#enum_birthdate").val(data['bday']);
			$("#enum_age").val(data['age']);
			$("#enum_patientstat").val(data['patientstatus']);
			$("#enum_physician").val(data['physician']);
			$("#enum_procedure").val(data['procedure']);
			$("#enum_code").val(data['code']);
			$("#enum_spectype").val(data['sampletype']);
			$("#enum_serialno").val(data['serialno']);
			$("#enum_testkit").val(data['testkit']);
			$("#enum_testkit_lotno").val(data['lotno']);
			$("#enum_testkit_expiry").val(data['expiry']);
			$("#enum_extractdate").val(data['exday']);
			$("#enum_extracttime").val(data['etime']);
			$("#enum_extractby").val(data['extractby']);
			$("#enum_result").val(data['result']);
			$("#enum_result_by").val(data['performed_by']);
			$("#enum_remarks").val(data['remarks']);

			var dis = $("#enumResult").dialog({
				title: "Validate Result",
				width: 1024,
				height: 690,
				resizeable: false,
				modal: true,
				buttons: [
					{
						text: "Save & Confirm Result",
						icons: { primary: "ui-icon-check" },
						click: function() {
							var msg = '';
	
							if($("#enum_result").val() != '') {
								if(confirm("Are you sure you want save this data?") == true) {
									var dataString = $("#frmEnumResult").serialize();
									dataString = "mod=validateEnumResult&" + dataString;
									$.ajax({
										type: "POST",
										url: "src/sjerp.php",
										data: dataString,
										success: function() {
											alert("Result Successfully Confirmed & Published");
											$("#enumResult").trigger("reset");
											dis.dialog("close");
											showValidation();
										}
									});
								}
							}
						}
					},
					{
						text: "Print Result",
						icons: { primary: "ui-icon-print" },
						click: function() {
							
							var so_no = $("#sresult_sono").val();
							var code = $("#sresult_code").val();
							var serialno = $("#sresult_serialno").val();

							var txtHTML = "<iframe id='printSingleValue' frameborder=0 width='100%' height='100%' src='print/result.enum.php?so_no="+$("#enum_sono").val()+"&code="+$("#enum_code").val()+"&serialno="+$("#enum_serialno").val()+"&sid="+Math.random()+"&sid="+Math.random()+"'></iframe>";
							$("#report5").html(txtHTML);
							$("#report5").dialog({title: "Result - "+ $("#enum_procedure").val() +"", width: 560, height: 620, resizable: true }).dialogExtend({
								"closable" : true,
								"maximizable" : true,
								"minimizable" : true
							});

						 }
					},
					{
						text: "Close",
						icons: { primary: "ui-icon-closethick" },
						click: function() { $(this).dialog("close"); }
					}
				]
			});

		},"json"
	);
}

function dengueResult(lid,code) {

	$("#sresult_date").datepicker();
	
	$.post("src/sjerp.php", {
		mod: "resultSingle",
		submod: "dengueResultView",
		lid: lid,
		sid: Math.random() },
		function(data) {

			$("#dengue_sono").val(data['myso']);
			$("#dengue_sodate").val(data['sodate']);
			$("#dengue_pid").val(data['mypid']);
			$("#dengue_pname").val(data['pname']);
			$("#dengue_gender").val(data['gender']);
			$("#dengue_birthdate").val(data['bday']);
			$("#dengue_age").val(data['age']);
			$("#dengue_patientstat").val(data['patientstatus']);
			$("#dengue_physician").val(data['physician']);
			$("#dengue_procedure").val(data['procedure']);
			$("#dengue_code").val(data['code']);
			$("#dengue_spectype").val(data['sampletype']);
			$("#dengue_serialno").val(data['serialno']);
			$("#dengue_extractdate").val(data['exday']);
			$("#dengue_extracttime").val(data['etime']);
			$("#dengue_extractby").val(data['extractby']);
			$("#dengue_ag").val(data['dengue_ag']);
			$("#dengue_igg").val(data['dengue_igg']);
			$("#dengue_igm").val(data['dengue_igm']);

			var dis = $("#dengueResult").dialog({
				title: "Write Result",
				width: 540,
				resizeable: false,
				modal: true,
				buttons: [
					{
						text: "Save Result Pending Validation",
						icons: { primary: "ui-icon-check" },
						click: function() {
							var msg = '';
						
								if(confirm("Are you sure you want save this data?") == true) {
									var dataString = $("#frmDengueResult").serialize();
										dataString = "mod=saveDengueResult&" + dataString;
										$.ajax({
											type: "POST",
											url: "src/sjerp.php",
											data: dataString,
											success: function() {
												alert("Result Successfully Saved!");
												dis.dialog("close");
												$("#frmDengueResult").trigger("reset");
										}
									});
								}
							}
					},
					{
						text: "Close",
						icons: { primary: "ui-icon-closethick" },
						click: function() { $(this).dialog("close"); $("#frmsingleValue").trigger("reset"); }
					}
				]
			});

		},"json"
	);
}

function validateDengueResult(lid,code) {

	$("#dengue_date").datepicker();
	
	$.post("src/sjerp.php", {
		mod: "resultSingle",
		submod: "dengueResultView",
		lid: lid,
		sid: Math.random() },
		function(data) {

			$("#dengue_sono").val(data['myso']);
			$("#dengue_sodate").val(data['sodate']);
			$("#dengue_pid").val(data['mypid']);
			$("#dengue_pname").val(data['pname']);
			$("#dengue_gender").val(data['gender']);
			$("#dengue_birthdate").val(data['bday']);
			$("#dengue_age").val(data['age']);
			$("#dengue_patientstat").val(data['patientstatus']);
			$("#dengue_physician").val(data['physician']);
			$("#dengue_procedure").val(data['procedure']);
			$("#dengue_code").val(data['code']);
			$("#dengue_spectype").val(data['sampletype']);
			$("#dengue_serialno").val(data['serialno']);
			$("#dengue_extractdate").val(data['exday']);
			$("#dengue_extracttime").val(data['etime']);
			$("#dengue_extractby").val(data['extractby']);
			$("#dengue_ag").val(data['dengue_ag']);
			$("#dengue_igg").val(data['dengue_igg']);
			$("#dengue_igm").val(data['dengue_igm']);

			var dis = $("#dengueResult").dialog({
				title: "Validate Dengue Result",
				width: 540,
				resizeable: false,
				modal: true,
				buttons: [
					{
						text: "Save Changes & Mark as Validated",
						icons: { primary: "ui-icon-check" },
						click: function() {
							var msg = '';
	
							if(confirm("Are you sure you want confirm and validate this result?") == true) {
							var dataString = $("#frmDengueResult").serialize();
								dataString = "mod=validateDengueResult&" + dataString;
								$.ajax({
									type: "POST",
									url: "src/sjerp.php",
									data: dataString,
									success: function() {
										alert("Result Successfully Validated!");
										$("#frmDengueResult").trigger("reset");
										dis.dialog("close");
										showValidation();
				
									}
								});
							}
						}
					},
					{
						text: "Print Result",
						icons: { primary: "ui-icon-print" },
						click: function() {
							
							var so_no = $("#dengue_sono").val();
							var serialno = $("#dengue_serialno").val();
							var code = $("#dengue_code").val();
							
							var txtHTML = "<iframe id='prntXrayResult' frameborder=0 width='100%' height='100%' src='print/result.dengue.php?so_no="+so_no+"&code="+code+"&serialno="+serialno+"&sid="+Math.random()+"&sid="+Math.random()+"'></iframe>";
							$("#report10").html(txtHTML);
							$("#report10").dialog({title: "Print - Dengue Result", width: 560, height: 620, resizable: true }).dialogExtend({
								"closable" : true,
								"maximizable" : true,
								"minimizable" : true
							});
		
		
						 }
					},
					{
						text: "Close",
						icons: { primary: "ui-icon-closethick" },
						click: function() { $(this).dialog("close"); }
					}
				]
			});

		},"json"
	);
}

function cbcResult(lid,code) {

	$("#cbcResult").html("<iframe id='frmCbcResult' frameborder=0 width='100%' height='100%' src='result.cbc.php?lid="+lid+"'></iframe>");
	
	var dis = $("#cbcResult").dialog({
		title: "Write Result",
		width: 1024,
		height: 690,
		resizeable: false,
		modal: true,
		buttons: [
			{
				text: "Save Result Pending Validation",
				icons: { primary: "ui-icon-check" },
				click: function() {
					var msg = '';

					if(confirm("Are you sure you want save this data?") == true) {
						var msg = '';
						
						if($('#frmCbcResult').contents().find("#wbc").val() == '' ) { msg = msg + "- Invalid or Empty Value for <b>WBC</b> count<br/>"; }
						if($('#frmCbcResult').contents().find("#rbc").val() == '' ) { msg = msg + "- Invalid or Empty Value for <b>RBC</b> count<br/>"; }
						if($('#frmCbcResult').contents().find("#hemoglobin").val() == '' ) { msg = msg + "- Invalid or Empty Value for <b>Hemoglobin</b> count<br/>"; }
						if($('#frmCbcResult').contents().find("#hematocrit").val() == '' ) { msg = msg + "- Invalid or Empty Value for <b>Hematocrit</b> count<br/>"; }
						if($('#frmCbcResult').contents().find("#platelate").val() == '' ) { msg = msg + "- Invalid or Empty Value for <b>Platelate</b> count<br/>"; }

						var totalDifferential = parseFloat($('#frmCbcResult').contents().find("#neutrophils").val()) + parseFloat($('#frmCbcResult').contents().find("#lymphocytes").val()) + parseFloat($('#frmCbcResult').contents().find("#monocytes").val()) + parseFloat($('#frmCbcResult').contents().find("#eosinophils").val()) + parseFloat($('#frmCbcResult').contents().find("#basophils").val());

						if(totalDifferential != 100) { msg = msg + "- <b>Total Differential Count</b> != <b>100%</b><br/>"; }


						if(msg != '') {
							parent.sendErrorMessage(msg);
						} else {
							var dataString = $('#frmCbcResult').contents().find('#frmCBCResult').serialize();
							dataString = "mod=saveCBCResult&" + dataString;
							$.ajax({
								type: "POST",
								url: "src/sjerp.php",
								data: dataString,
								success: function() {
									alert("Result Successfully Saved!");
									dis.dialog("close");
									$("#frmCBCResult").trigger("reset");
								}
							});
						}
					}
				}
			},
			{
				text: "Print Result",
				icons: { primary: "ui-icon-print" },
				click: function() {
					
					var so_no = $('#frmCbcResult').contents().find('#cbc_sono').val();
					var serialno = $('#frmCbcResult').contents().find('#cbc_serialno').val();

					var txtHTML = "<iframe id='prntXrayResult' frameborder=0 width='100%' height='100%' src='print/result.cbc.php?so_no="+so_no+"&code="+code+"&serialno="+serialno+"&sid="+Math.random()+"&sid="+Math.random()+"'></iframe>";
					$("#report3").html(txtHTML);
					$("#report3").dialog({title: "Print - CBC RESULT", width: 560, height: 620, resizable: true }).dialogExtend({
						"closable" : true,
						"maximizable" : true,
						"minimizable" : true
					});


				 }
			},
			{
				text: "Close",
				icons: { primary: "ui-icon-closethick" },
				click: function() { $(this).dialog("close"); }
			}
		]
	});
}

function validateCbcResult(lid,code) {

	$("#cbcResult").html("<iframe id='frmCbcResult' frameborder=0 width='100%' height='100%' src='result.cbc.php?lid="+lid+"'></iframe>");
	
	var dis = $("#cbcResult").dialog({
		title: "Validate Result",
		width: 1024,
		height: 690,
		resizeable: false,
		modal: true,
		buttons: [
			{
				text: "Save Changes & Mark Result as Validated",
				icons: { primary: "ui-icon-check" },
				click: function() {
					var msg = '';

					if(confirm("Are you sure you want save this data?") == true) {
						var dataString = $('#frmCbcResult').contents().find('#frmCBCResult').serialize();
					
						//var dataString = $("#frmDescResult").serialize();
						dataString = "mod=validateCBCResult&" + dataString;
						$.ajax({
							type: "POST",
							url: "src/sjerp.php",
							data: dataString,
							success: function() {
								alert("Result Successfully Marked as Validated!");
								showValidation();
								dis.dialog("close");
							}
						});
					}
				}
			},
			{
				text: "Print Result",
				icons: { primary: "ui-icon-print" },
				click: function() {
					
					var so_no = $('#frmCbcResult').contents().find('#cbc_sono').val();
					var serialno = $('#frmCbcResult').contents().find('#cbc_serialno').val();

					var txtHTML = "<iframe id='prntXrayResult' frameborder=0 width='100%' height='100%' src='print/result.cbc.php?so_no="+so_no+"&code="+code+"&serialno="+serialno+"&sid="+Math.random()+"&sid="+Math.random()+"'></iframe>";
					$("#report3").html(txtHTML);
					$("#report3").dialog({title: "Print - CBC RESULT", width: 560, height: 620, resizable: true }).dialogExtend({
						"closable" : true,
						"maximizable" : true,
						"minimizable" : true
					});


				 }
			},
			{
				text: "Close",
				icons: { primary: "ui-icon-closethick" },
				click: function() { $(this).dialog("close"); }
			}
		]
	});
}

function bloodChem(lid,code) {
	
	$("#bloodChemResult").html("<iframe id='frmBloodChem' frameborder=0 width='100%' height='100%' src='result.bloodchem.php?lid="+lid+"'></iframe>");
	
	$("#bloodChemResult").dialog({
		title: "Write Result",
		width: 1024,
		height: 920,
		resizeable: false,
		modal: true,
		buttons: [
			{
				text: "Save Result Pending Validation",
				icons: { primary: "ui-icon-check" },
				click: function() {
					var msg = '';

					if(confirm("Are you sure you want save this data?") == true) {
						var dataString = $('#frmBloodChem').contents().find('#frmBloodChemResult').serialize();
						dataString = "mod=saveBloodChem&" + dataString;
						$.ajax({
							type: "POST",
							url: "src/sjerp.php",
							data: dataString,
							success: function() {
								alert("Result Successfully Saved!");
							}
						});
					}
				}
			},
			{
				text: "Print Result",
				icons: { primary: "ui-icon-print" },
				click: function() {
					
					var so_no = $('#frmBloodChem').contents().find('#bloodchem_sono').val();
					var serialno = $('#frmBloodChem').contents().find('#bloodchem_serialno').val();

					var txtHTML = "<iframe id='prntXrayResult' frameborder=0 width='100%' height='100%' src='print/result.bloodchem.php?so_no="+so_no+"&code="+code+"&serialno="+serialno+"&sid="+Math.random()+"&sid="+Math.random()+"'></iframe>";
					$("#report3").html(txtHTML);
					$("#report3").dialog({title: "Print - BLOOD CHEMISTRY RESULT", width: 560, height: 620, resizable: true }).dialogExtend({
						"closable" : true,
						"maximizable" : true,
						"minimizable" : true
					});


				 }
			},
			{
				text: "Close",
				icons: { primary: "ui-icon-closethick" },
				click: function() { $(this).dialog("close"); }
			}
		]
	});
}

function validateBloodChem(lid,code) {
	
	$("#bloodChemResult").html("<iframe id='frmBloodChem' frameborder=0 width='100%' height='100%' src='result.bloodchem.php?lid="+lid+"'></iframe>");
	
	var dis = $("#bloodChemResult").dialog({
		title: "Write Result",
		width: 1024,
		height: 920,
		resizeable: false,
		modal: true,
		buttons: [
			{
				text: "Save Changes & Mark Result as Validated",
				icons: { primary: "ui-icon-check" },
				click: function() {
					var msg = '';

					if(confirm("Are you sure you want save this data?") == true) {
						var dataString = $('#frmBloodChem').contents().find('#frmBloodChemResult').serialize();
						dataString = "mod=validateBloodChem&" + dataString;
						$.ajax({
							type: "POST",
							url: "src/sjerp.php",
							data: dataString,
							success: function() {
								alert("Result Successfully Marked as Validated!");
								showValidation();
								dis.dialog("close");
							}
						});
					}
				}
			},
			{
				text: "Print Result",
				icons: { primary: "ui-icon-print" },
				click: function() {
					
					var so_no = $('#frmBloodChem').contents().find('#bloodchem_sono').val();
					var serialno = $('#frmBloodChem').contents().find('#bloodchem_serialno').val();

					var txtHTML = "<iframe id='prntXrayResult' frameborder=0 width='100%' height='100%' src='print/result.bloodchem.php?so_no="+so_no+"&code="+code+"&serialno="+serialno+"&sid="+Math.random()+"&sid="+Math.random()+"'></iframe>";
					$("#report3").html(txtHTML);
					$("#report3").dialog({title: "Print - BLOOD CHEMISTRY RESULT", width: 560, height: 620, resizable: true }).dialogExtend({
						"closable" : true,
						"maximizable" : true,
						"minimizable" : true
					});


				 }
			},
			{
				text: "Close",
				icons: { primary: "ui-icon-closethick" },
				click: function() { $(this).dialog("close"); }
			}
		]
	});
}

/* Blood Chem Consolidated */
function writeChemistryResult(so_no) {
	
	$("#bloodChemResult").html("<iframe id='frmBloodChem' frameborder=0 width='100%' height='100%' src='result.bloodchem.conso.php?so_no="+so_no+"'></iframe>");
	
	$("#bloodChemResult").dialog({
		title: "Write Result",
		width: 1024,
		height: 920,
		resizeable: false,
		modal: true,
		buttons: [
			{
				text: "Save Result Pending Validation",
				icons: { primary: "ui-icon-check" },
				click: function() {
					var msg = '';

					if(confirm("Are you sure you want save this data?") == true) {
						var dataString = $('#frmBloodChem').contents().find('#frmBloodChemResult').serialize();
						dataString = "mod=saveBloodChem&" + dataString;
						$.ajax({
							type: "POST",
							url: "src/sjerp.php",
							data: dataString,
							success: function() {
								alert("Result Successfully Saved!");
							}
						});
					}
				}
			},
			{
				text: "Print Result",
				icons: { primary: "ui-icon-print" },
				click: function() {
					
					var so_no = $('#frmBloodChem').contents().find('#bloodchem_sono').val();
					var serialno = $('#frmBloodChem').contents().find('#bloodchem_serialno').val();

					var txtHTML = "<iframe id='prntXrayResult' frameborder=0 width='100%' height='100%' src='print/result.bloodchem.php?so_no="+so_no+"&code="+code+"&serialno="+serialno+"&sid="+Math.random()+"&sid="+Math.random()+"'></iframe>";
					$("#report3").html(txtHTML);
					$("#report3").dialog({title: "Print - BLOOD CHEMISTRY RESULT", width: 560, height: 620, resizable: true }).dialogExtend({
						"closable" : true,
						"maximizable" : true,
						"minimizable" : true
					});


				 }
			},
			{
				text: "Close",
				icons: { primary: "ui-icon-closethick" },
				click: function() { $(this).dialog("close"); }
			}
		]
	});
}


function uaResult(lid,code) {
	
	$("#uaResult").html("<iframe id='frmUA' frameborder=0 width='100%' height='100%' src='result.ua.php?lid="+lid+"'></iframe>");
	
	$("#uaResult").dialog({
		title: "Write Result",
		width: 1024,
		height: 960,
		resizeable: false,
		modal: true,
		buttons: [
			{
				text: "Save Result Pending Validation",
				icons: { primary: "ui-icon-check" },
				click: function() {
					var msg = '';

					if(confirm("Are you sure you want save this data?") == true) {
						var dataString = $('#frmUA').contents().find('#frmUrinalysisReport').serialize();
						dataString = "mod=saveUAReport&" + dataString;
						$.ajax({
							type: "POST",
							url: "src/sjerp.php",
							data: dataString,
							success: function() {
								alert("Result Successfully Saved!");
								//showValidation();
								showSamples();
								dis.dialog("close");
								
							}
						});
					}
				}
			},
			// {
			// 	text: "Print Result",
			// 	icons: { primary: "ui-icon-print" },
			// 	click: function() {
					
			// 		var so_no = $('#frmUA').contents().find('#ua_sono').val();
			// 		var serialno = $('#frmUA').contents().find('#ua_serialno').val();

			// 		var txtHTML = "<iframe id='prntXrayResult' frameborder=0 width='100%' height='100%' src='print/result.ua.php?so_no="+so_no+"&code="+code+"&serialno="+serialno+"&sid="+Math.random()+"&sid="+Math.random()+"'></iframe>";
			// 		$("#report1").html(txtHTML);
			// 		$("#report1").dialog({title: "Print - Uranilysis (UA)", width: 560, height: 620, resizable: true }).dialogExtend({
			// 			"closable" : true,
			// 			"maximizable" : true,
			// 			"minimizable" : true
			// 		});


			// 	 }
			// },
			{
				text: "Close",
				icons: { primary: "ui-icon-closethick" },
				click: function() { $(this).dialog("close"); }
			}
		]
	});
}

function validateUaResult(lid,code) {
	
	$("#uaResult").html("<iframe id='frmUA' frameborder=0 width='100%' height='100%' src='result.ua.php?lid="+lid+"'></iframe>");
	
	var dis = $("#uaResult").dialog({
		title: "Validate Result",
		width: 1024,
		height: 720,
		resizeable: false,
		modal: true,
		buttons: [
			{
				text: "Save Changes & Mark Resullt as Validated",
				icons: { primary: "ui-icon-check" },
				click: function() {
					var msg = '';

					if(confirm("Are you sure you want save this data?") == true) {
						var dataString = $('#frmUA').contents().find('#frmUrinalysisReport').serialize();
						dataString = "mod=validateUAReport&" + dataString;
						$.ajax({
							type: "POST",
							url: "src/sjerp.php",
							data: dataString,
							success: function() {
								alert("Result Successfully Marked as Validated!");
								showValidation();
								dis.dialog("close");
							}
						});
					}
				}
			},
			{
				text: "Print Result",
				icons: { primary: "ui-icon-print" },
				click: function() {
					
					var so_no = $('#frmUA').contents().find('#ua_sono').val();
					var serialno = $('#frmUA').contents().find('#ua_serialno').val();

					var txtHTML = "<iframe id='prntXrayResult' frameborder=0 width='100%' height='100%' src='print/result.ua.php?so_no="+so_no+"&code="+code+"&serialno="+serialno+"&sid="+Math.random()+"&sid="+Math.random()+"'></iframe>";
					$("#report1").html(txtHTML);
					$("#report1").dialog({title: "Print - Urinalysis (UA)", width: 560, height: 620, resizable: true }).dialogExtend({
						"closable" : true,
						"maximizable" : true,
						"minimizable" : true
					});


				 }
			},
			{
				text: "Close",
				icons: { primary: "ui-icon-closethick" },
				click: function() { $(this).dialog("close"); }
			}
		]
	});
}


function stoolExam(lid,code) {
	
	$("#stoolResult").html("<iframe id='frmStoolExam' frameborder=0 width='100%' height='100%' src='result.stool.php?lid="+lid+"'></iframe>");
	
	$("#stoolResult").dialog({
		title: "Write Result",
		width: 1024,
		height: 680,
		resizeable: false,
		modal: true,
		buttons: [
			{
				text: "Save Result Pending Validation",
				icons: { primary: "ui-icon-check" },
				click: function() {
					var msg = '';

					if(confirm("Are you sure you want save this data?") == true) {
						var dataString = $('#frmStoolExam').contents().find('#frmStoolReport').serialize();
						dataString = "mod=saveStoolExam&" + dataString;
						$.ajax({
							type: "POST",
							url: "src/sjerp.php",
							data: dataString,
							success: function() {
								alert("Result Successfully Saved!");
								showSamples();
								dis.dialog("close");
							}
						});
					}
				}
			},
			// {
			// 	text: "Print Result",
			// 	icons: { primary: "ui-icon-print" },
			// 	click: function() {
					
			// 		var so_no = $('#frmStoolExam').contents().find('#stool_sono').val();
			// 		var serialno = $('#frmStoolExam').contents().find('#stool_serialno').val();

			// 		var txtHTML = "<iframe id='prntXrayResult' frameborder=0 width='100%' height='100%' src='print/result.stool.php?so_no="+so_no+"&code="+code+"&serialno="+serialno+"&sid="+Math.random()+"&sid="+Math.random()+"'></iframe>";
			// 		$("#report2").html(txtHTML);
			// 		$("#report2").dialog({title: "Print - Stool Exam", width: 560, height: 620, resizable: true }).dialogExtend({
			// 			"closable" : true,
			// 			"maximizable" : true,
			// 			"minimizable" : true
			// 		});


			// 	 }
			// },
			{
				text: "Close",
				icons: { primary: "ui-icon-closethick" },
				click: function() { $(this).dialog("close"); }
			}
		]
	});
}

function validateStoolExam(lid,code) {
	
	$("#stoolResult").html("<iframe id='frmStoolExam' frameborder=0 width='100%' height='100%' src='result.stool.php?lid="+lid+"'></iframe>");
	
	var dis = $("#stoolResult").dialog({
		title: "Write Result",
		width: 1024,
		height: 680,
		resizeable: false,
		modal: true,
		buttons: [
			{
				text: "Confirm & Validate Result",
				icons: { primary: "ui-icon-check" },
				click: function() {
					var msg = '';

					if(confirm("Are you sure you want confirm and publish this result?") == true) {
						var dataString = $('#frmStoolExam').contents().find('#frmStoolReport').serialize();
						dataString = "mod=validateStoolExam&" + dataString;
						$.ajax({
							type: "POST",
							url: "src/sjerp.php",
							data: dataString,
							success: function() {
								alert("Result Successfully Marked as Validated!");
								showValidation();
								dis.dialog("close");
							}
						});
					}
				}
			},
			{
				text: "Print Result",
				icons: { primary: "ui-icon-print" },
				click: function() {
					
					var so_no = $('#frmStoolExam').contents().find('#stool_sono').val();
					var serialno = $('#frmStoolExam').contents().find('#stool_serialno').val();

					var txtHTML = "<iframe id='prntXrayResult' frameborder=0 width='100%' height='100%' src='print/result.stool.php?so_no="+so_no+"&code="+code+"&serialno="+serialno+"&sid="+Math.random()+"&sid="+Math.random()+"'></iframe>";
					$("#report2").html(txtHTML);
					$("#report2").dialog({title: "Print - Stool Exam", width: 560, height: 620, resizable: true }).dialogExtend({
						"closable" : true,
						"maximizable" : true,
						"minimizable" : true
					});


				 }
			},
			{
				text: "Close",
				icons: { primary: "ui-icon-closethick" },
				click: function() { $(this).dialog("close"); }
			}
		]
	});
}

function semenAnalysis(lid,code) {
	
	$("#semAnalReport").html("<iframe id='frmSemenAnalysis' frameborder=0 width='100%' height='100%' src='result.sar.php?lid="+lid+"'></iframe>");
	
	$("#stoolResult").dialog({
		title: "Write Result",
		width: 1024,
		height: 680,
		resizeable: false,
		modal: true,
		buttons: [
			{
				text: "Save Result Pending Validation",
				icons: { primary: "ui-icon-check" },
				click: function() {
					var msg = '';

					if(confirm("Are you sure you want save this data?") == true) {
						var dataString = $('#frmSemenAnalysis').contents().find('#frmSemenAnalysisReport').serialize();
						dataString = "mod=saveSemenAnalysis&" + dataString;
						$.ajax({
							type: "POST",
							url: "src/sjerp.php",
							data: dataString,
							success: function() {
								alert("Result Successfully Saved!");
							}
						});
					}
				}
			},
			{
				text: "Print Result",
				icons: { primary: "ui-icon-print" },
				click: function() {
					
					var so_no = $('#frmSemenAnalysis').contents().find('#semen_sono').val();
					var serialno = $('#frmSemenAnalysis').contents().find('#semen_serialno').val();

					var txtHTML = "<iframe id='prntXrayResult' frameborder=0 width='100%' height='100%' src='print/result.sar.php?so_no="+so_no+"&code="+code+"&serialno="+serialno+"&sid="+Math.random()+"&sid="+Math.random()+"'></iframe>";
					$("#report2").html(txtHTML);
					$("#report2").dialog({title: "Print - Stool Exam", width: 560, height: 620, resizable: true }).dialogExtend({
						"closable" : true,
						"maximizable" : true,
						"minimizable" : true
					});


				 }
			},
			{
				text: "Close",
				icons: { primary: "ui-icon-closethick" },
				click: function() { $(this).dialog("close"); }
			}
		]
	});
}

function pregnancyResult(lid,code) {

	$("#pt_date").datepicker();
	
	$.post("src/sjerp.php", {
		mod: "resultSingle",
		submod: "enumResult",
		lid: lid,
		sid: Math.random() },
		function(data) {

			$("#pt_sono").val(data['myso']);
			$("#pt_sodate").val(data['sodate']);
			$("#pt_pid").val(data['mypid']);
			$("#pt_pname").val(data['pname']);
			$("#pt_gender").val(data['gender']);
			$("#pt_birthdate").val(data['bday']);
			$("#pt_age").val(data['age']);
			$("#pt_patientstat").val(data['patientstatus']);
			$("#pt_physician").val(data['physician']);
			$("#pt_procedure").val(data['procedure']);
			$("#pt_code").val(data['code']);
			$("#pt_spectype").val(data['sampletype']);
			$("#pt_serialno").val(data['serialno']);
			$("#pt_extractdate").val(data['exday']);
			$("#pt_extracttime").val(data['etime']);
			$("#pt_extractby").val(data['extractby']);
			$("#pt_result").val(data['result']);
			$("#pt_remarks").val(data['remarks']);

			var dis = $("#pregnancyResult").dialog({
				title: "Write Result",
				width: 540,
				resizeable: false,
				modal: true,
				buttons: [
					{
						text: "Save Result Pending Validation",
						icons: { primary: "ui-icon-check" },
						click: function() {
							var msg = '';
	
							if(confirm("Are you sure you want save this data?") == true) {
							var dataString = $("#frmPregnancyResult").serialize();
								dataString = "mod=savePregnancyResult&" + dataString;
								$.ajax({
									type: "POST",
									url: "src/sjerp.php",
									data: dataString,
									success: function() {
										alert("Result Successfully Saved!");
										$("#frmPregnancyResult").trigger("reset");
										dis.dialog("close");
										showValidation();
				
									}
								});
							}
						}
					},
					{
						text: "Close",
						icons: { primary: "ui-icon-closethick" },
						click: function() { $(this).dialog("close"); }
					}
				]
			});

		},"json"
	);
}

function validatePregnancyResult(lid,code) {
	
	$.post("src/sjerp.php", {
		mod: "resultSingle",
		submod: "enumResult",
		lid: lid,
		sid: Math.random() },
		function(data) {

			$("#pt_sono").val(data['myso']);
			$("#pt_sodate").val(data['sodate']);
			$("#pt_pid").val(data['mypid']);
			$("#pt_pname").val(data['pname']);
			$("#pt_gender").val(data['gender']);
			$("#pt_birthdate").val(data['bday']);
			$("#pt_age").val(data['age']);
			$("#pt_patientstat").val(data['patientstatus']);
			$("#pt_physician").val(data['physician']);
			$("#pt_procedure").val(data['procedure']);
			$("#pt_code").val(data['code']);
			$("#pt_spectype").val(data['sampletype']);
			$("#pt_serialno").val(data['serialno']);
			$("#pt_extractdate").val(data['exday']);
			$("#pt_extracttime").val(data['etime']);
			$("#pt_extractby").val(data['extractby']);
			$("#pt_result").val(data['result']);
			$("#pt_remarks").val(data['remarks']);

			var dis = $("#pregnancyResult").dialog({
				title: "Validate Result",
				width: 540,
				resizeable: false,
				modal: true,
				buttons: [
					{
						text: "Save & Confirm Result",
						icons: { primary: "ui-icon-check" },
						click: function() {
							var msg = '';
	
							if(confirm("Are you sure you want confirm and publish this result?") == true) {
							var dataString = $("#frmPregnancyResult").serialize();
								dataString = "mod=validatePregnancyResult&" + dataString;
								$.ajax({
									type: "POST",
									url: "src/sjerp.php",
									data: dataString,
									success: function() {
										alert("Result Successfully Confirmed & Published");
										showValidation();
										dis.dialog("close");
									}
								});
							}
						}
					},
					{
						text: "Print Result",
						icons: { primary: "ui-icon-print" },
						click: function() {
							
							var so_no = $("#sresult_sono").val();
							var code = $("#sresult_code").val();
							var serialno = $("#sresult_serialno").val();

							var txtHTML = "<iframe id='printPregnancyResult' frameborder=0 width='100%' height='100%' src='print/result.pt.php?so_no="+$("#pt_sono").val()+"&code="+$("#pt_code").val()+"&serialno="+$("#pt_serialno").val()+"&sid="+Math.random()+"&sid="+Math.random()+"'></iframe>";
							$("#report15").html(txtHTML);
							$("#report15").dialog({title: "Result - "+ $("#pt_procedure").val() +"", width: 560, height: 620, resizable: true }).dialogExtend({
								"closable" : true,
								"maximizable" : true,
								"minimizable" : true
							});

						 }
					},
					{
						text: "Close",
						icons: { primary: "ui-icon-closethick" },
						click: function() { $(this).dialog("close"); }
					}
				]
			});

		},"json"
	);
}

function lipidResult(lid,code) {

	$("#lipid_date").datepicker();
	
	$.post("src/sjerp.php", {
		mod: "resultSingle",
		submod: "lipidPanel",
		lid: lid,
		sid: Math.random() },
		function(data) {

			$("#lipid_sono").val(data['myso']);
			$("#lipid_sodate").val(data['sodate']);
			$("#lipid_pid").val(data['mypid']);
			$("#lipid_pname").val(data['pname']);
			$("#lipid_gender").val(data['gender']);
			$("#lipid_birthdate").val(data['bday']);
			$("#lipid_age").val(data['age']);
			$("#lipid_patientstat").val(data['patientstatus']);
			$("#lipid_physician").val(data['physician']);
			$("#lipid_procedure").val(data['procedure']);
			$("#lipid_code").val(data['code']);
			$("#lipid_spectype").val(data['sampletype']);
			$("#lipid_serialno").val(data['serialno']);
			$("#lipid_testkit").val(data['testkit']);
			$("#lipid_testkit_lotno").val(data['lotno']);
			$("#lipid_testkit_expiry").val(data['expiry']);
			$("#lipid_extractdate").val(data['exday']);
			$("#lipid_extracttime").val(data['etime']);
			$("#lipid_extractby").val(data['extractby']);
			$("#lipid_cholesterol").val(data['cholesterol']);
			$("#lipid_triglycerides").val(data['triglycerides']);
			$("#lipid_hdl").val(data['hdl']);
			$("#lipid_ldl").val(data['ldl']);
			$("#lipid_vldl").val(data['vldl']);
			$("#lipid_result_by").val(data['performed_by']);
			$("#lipid_remarks").val(data['remarks']);

			var dis = $("#lipidResult").dialog({
				title: "Write Result",
				width: 1024,
				height: 705,
				resizeable: false,
				modal: true,
				buttons: [
					{
						text: "Save Result Pending Validation",
						icons: { primary: "ui-icon-check" },
						click: function() {
							var msg = '';
	
							if(confirm("Are you sure you want save this data?") == true) {
							var dataString = $("#frmLipidResult").serialize();
								dataString = "mod=saveLipidPanel&" + dataString;
								$.ajax({
									type: "POST",
									url: "src/sjerp.php",
									data: dataString,
									success: function() {
										alert("Result Successfully Saved!");
										$("#frmLipidResult").trigger("reset");
										dis.dialog("close");
										showValidation();
				
									}
								});
							}
						}
					},
					{
						text: "Close",
						icons: { primary: "ui-icon-closethick" },
						click: function() { $(this).dialog("close"); }
					}
				]
			});

		},"json"
	);
}

function ogttResult(lid,code) {

	$("#ogtt_date").datepicker();
	
	$.post("src/sjerp.php", {
		mod: "resultSingle",
		submod: "ogttResult",
		lid: lid,
		sid: Math.random() },
		function(data) {

			$("#ogtt_sono").val(data['myso']);
			$("#ogtt_sodate").val(data['sodate']);
			$("#ogtt_pid").val(data['mypid']);
			$("#ogtt_pname").val(data['pname']);
			$("#ogtt_gender").val(data['gender']);
			$("#ogtt_birthdate").val(data['bday']);
			$("#ogtt_age").val(data['age']);
			$("#ogtt_patientstat").val(data['patientstatus']);
			$("#ogtt_physician").val(data['physician']);
			$("#ogtt_procedure").val(data['procedure']);
			$("#ogtt_code").val(data['code']);
			$("#ogtt_spectype").val(data['sampletype']);
			$("#ogtt_serialno").val(data['serialno']);
			$("#ogtt_extractdate").val(data['exday']);
			$("#ogtt_extracttime").val(data['etime']);
			$("#ogtt_extractby").val(data['extractby']);
			$("#ogtt_fasting").val(data['fasting']);
			$("#ogtt_uglucose").val(data['fasting_uglucose']);
			$("#ogttFirstHr").val(data['first_hr']);
			$("#first_hr_uglucose").val(data['first_hr_uglucose']);
			$("#second_hr").val(data['second_hr']);
			$("#second_hr_uglucose").val(data['second_hr_uglucose']);

			var dis = $("#ogttResult").dialog({
				title: "Write Result",
				width: 540,
				resizeable: false,
				modal: true,
				buttons: [
					{
						text: "Save Result Pending Validation",
						icons: { primary: "ui-icon-check" },
						click: function() {
							var msg = '';
	
							if(confirm("Are you sure you want save this data?") == true) {
							var dataString = $("#frmOgttResult").serialize();
								dataString = "mod=saveOgttResult&" + dataString;
								$.ajax({
									type: "POST",
									url: "src/sjerp.php",
									data: dataString,
									success: function() {
										alert("Result Successfully Saved!");
										$("#frmOgttResult").trigger("reset");
										dis.dialog("close");
				
									}
								});
							}
						}
					},
					{
						text: "Close",
						icons: { primary: "ui-icon-closethick" },
						click: function() { $(this).dialog("close"); }
					}
				]
			});

		},"json"
	);
}


function validateOgttResult(lid,code) {

	$("#ogtt_date").datepicker();
	
	$.post("src/sjerp.php", {
		mod: "resultSingle",
		submod: "ogttResult",
		lid: lid,
		sid: Math.random() },
		function(data) {

			$("#ogtt_sono").val(data['myso']);
			$("#ogtt_sodate").val(data['sodate']);
			$("#ogtt_pid").val(data['mypid']);
			$("#ogtt_pname").val(data['pname']);
			$("#ogtt_gender").val(data['gender']);
			$("#ogtt_birthdate").val(data['bday']);
			$("#ogtt_age").val(data['age']);
			$("#ogtt_patientstat").val(data['patientstatus']);
			$("#ogtt_physician").val(data['physician']);
			$("#ogtt_procedure").val(data['procedure']);
			$("#ogtt_code").val(data['code']);
			$("#ogtt_spectype").val(data['sampletype']);
			$("#ogtt_serialno").val(data['serialno']);
			$("#ogtt_extractdate").val(data['exday']);
			$("#ogtt_extracttime").val(data['etime']);
			$("#ogtt_extractby").val(data['extractby']);
			$("#ogtt_fasting").val(data['fasting']);
			$("#ogtt_uglucose").val(data['fasting_uglucose']);
			$("#ogttFirstHr").val(data['first_hr']);
			$("#first_hr_uglucose").val(data['first_hr_uglucose']);
			$("#second_hr").val(data['second_hr']);
			$("#second_hr_uglucose").val(data['second_hr_uglucose']);

			var dis = $("#ogttResult").dialog({
				title: "Validate OGTT Result",
				width: 540,
				resizeable: false,
				modal: true,
				buttons: [
					{
						text: "Validate & Publish Result",
						icons: { primary: "ui-icon-check" },
						click: function() {
							var msg = '';
	
							if(confirm("Are you sure you want confirm and validate this result?") == true) {
							var dataString = $("#frmOgttResult").serialize();
								dataString = "mod=validateOgttResult&" + dataString;
								$.ajax({
									type: "POST",
									url: "src/sjerp.php",
									data: dataString,
									success: function() {
										alert("Result Successfully Validated!");
										$("#frmOgttResult").trigger("reset");
										dis.dialog("close");
										showValidation();
				
									}
								});
							}
						}
					},
					{
						text: "Print Result",
						icons: { primary: "ui-icon-print" },
						click: function() {
							
							var so_no = $("#ogtt_sono").val();
							var serialno = $("#ogtt_serialno").val();
							var code = $("#ogtt_code").val();
							
							var txtHTML = "<iframe id='prntOgttResult' frameborder=0 width='100%' height='100%' src='print/result.ogtt.php?so_no="+so_no+"&code="+code+"&serialno="+serialno+"&sid="+Math.random()+"&sid="+Math.random()+"'></iframe>";
							$("#report1").html(txtHTML);
							$("#report1").dialog({title: "Print - OGTT TEST 75G", width: 560, height: 620, resizable: true }).dialogExtend({
								"closable" : true,
								"maximizable" : true,
								"minimizable" : true
							});
		
		
						 }
					},
					{
						text: "Close",
						icons: { primary: "ui-icon-closethick" },
						click: function() { $(this).dialog("close"); }
					}
				]
			});

		},"json"
	);
}

function bloodTyping(lid,code) {

	$("#btype_date").datepicker();
	
	$.post("src/sjerp.php", {
		mod: "resultSingle",
		submod: "bloodType",
		lid: lid,
		sid: Math.random() },
		function(data) {

			$("#btype_sono").val(data['myso']);
			$("#btype_sodate").val(data['sodate']);
			$("#btype_pid").val(data['mypid']);
			$("#btype_pname").val(data['pname']);
			$("#btype_gender").val(data['gender']);
			$("#btype_birthdate").val(data['bday']);
			$("#btype_age").val(data['age']);
			$("#btype_patientstat").val(data['patientstatus']);
			$("#btype_physician").val(data['physician']);
			$("#btype_procedure").val(data['procedure']);
			$("#btype_code").val(data['code']);
			$("#btype_spectype").val(data['sampletype']);
			$("#btype_serialno").val(data['serialno']);
			$("#btype_testkit").val(data['testkit']);
			$("#btype_testkit_lotno").val(data['lotno']);
			$("#btype_testkit_expiry").val(data['expiry']);
			$("#btype_extractdate").val(data['exday']);
			$("#btype_extracttime").val(data['etime']);
			$("#btype_extractby").val(data['extractby']);
			$("#btype_result").val(data['result']);
			$("#btype_rh").val(data['rh']);
			$("#btype_result_by").val(data['performed_by']);
			$("#btype_remarks").val(data['remarks']);

			var dis = $("#bloodtypeResult").dialog({
				title: "Write Result",
				width: 1040,
				height: 705,
				resizeable: false,
				modal: true,
				buttons: [
					{
						text: "Save Result Pending Validation",
						icons: { primary: "ui-icon-check" },
						click: function() {
							var msg = '';
	
							if(confirm("Are you sure you want save this data?") == true) {
							var dataString = $("#frmBloodType").serialize();
								dataString = "mod=saveBloodType&" + dataString;
								$.ajax({
									type: "POST",
									url: "src/sjerp.php",
									data: dataString,
									success: function() {
										alert("Result Successfully Saved!");
										dis.dialog("close");
										$("#frmBloodType").trigger("reset");
									}
								});
							}
						}
					},
					{
						text: "Close",
						icons: { primary: "ui-icon-closethick" },
						click: function() { $(this).dialog("close"); }
					}
				]
			});

		},"json"
	);
}

function validateBloodtype(lid,code) {

	$("#btype_date").datepicker();
	
	$.post("src/sjerp.php", {
		mod: "resultSingle",
		submod: "bloodType",
		lid: lid,
		sid: Math.random() },
		function(data) {

			$("#btype_sono").val(data['myso']);
			$("#btype_sodate").val(data['sodate']);
			$("#btype_pid").val(data['mypid']);
			$("#btype_pname").val(data['pname']);
			$("#btype_gender").val(data['gender']);
			$("#btype_birthdate").val(data['bday']);
			$("#btype_age").val(data['age']);
			$("#btype_patientstat").val(data['patientstatus']);
			$("#btype_physician").val(data['physician']);
			$("#btype_procedure").val(data['procedure']);
			$("#btype_code").val(data['code']);
			$("#btype_spectype").val(data['sampletype']);
			$("#btype_serialno").val(data['serialno']);
			$("#btype_testkit").val(data['testkit']);
			$("#btype_testkit_lotno").val(data['lotno']);
			$("#btype_testkit_expiry").val(data['expiry']);
			$("#btype_extractdate").val(data['exday']);
			$("#btype_extracttime").val(data['etime']);
			$("#btype_extractby").val(data['extractby']);
			$("#btype_result").val(data['result']);
			$("#btype_rh").val(data['rh']);
			$("#btype_result_by").val(data['performed_by']);
			$("#btype_remarks").val(data['remarks']);

			var dis = $("#bloodtypeResult").dialog({
				title: "Validate Result",
				width: 1040,
				height: 705,
				resizeable: false,
				modal: true,
				buttons: [
					{
						text: "Save Changes & Mark as Validated",
						icons: { primary: "ui-icon-check" },
						click: function() {
							var msg = '';
	
							if(confirm("Are you sure you want confirm and publish this result?") == true) {
							var dataString = $("#frmBloodType").serialize();
								dataString = "mod=validateBloodType&" + dataString;
								$.ajax({
									type: "POST",
									url: "src/sjerp.php",
									data: dataString,
									success: function() {
										alert("Result Successfully Saved!");
										$("#frmBloodType").trigger("reset");
										dis.dialog("close");
										showValidation();
									}
								});
							}
						}
					},
					{
						text: "Print Result",
						icons: { primary: "ui-icon-print" },
						click: function() {
							
							var so_no = $("#btype_sono").val();
							var serialno = $("#btype_serialno").val();
							var code = $("#btype_code").val();
							
							var txtHTML = "<iframe id='prntBTResult' frameborder=0 width='100%' height='100%' src='print/result.bt.php?so_no="+so_no+"&code="+code+"&serialno="+serialno+"&sid="+Math.random()+"&sid="+Math.random()+"'></iframe>";
							$("#report1").html(txtHTML);
							$("#report1").dialog({title: "Print - Blood Typing Result", width: 560, height: 620, resizable: true }).dialogExtend({
								"closable" : true,
								"maximizable" : true,
								"minimizable" : true
							});
		
		
						 }
					},
					{
						text: "Close",
						icons: { primary: "ui-icon-closethick" },
						click: function() { $(this).dialog("close"); }
					}
				]
			});

		},"json"
	);
}

function showUsers() {
	var txtHTML = "<iframe id='frmcust' frameborder=0 width='100%' height='100%' src='user.master.php'></iframe>";
	$("#userlist").html(txtHTML);
	$("#userlist").dialog({title: "System Users", width: xWidth, height: 540, resizable: false }).dialogExtend({
		"closable" : true,
	    "maximizable" : false,
	    "minimizable" : true
	});
}

function showChangePass() {
	$("#userChangePass").dialog({ title: "Update Password", width: 480, height: 190, resizable: false, modal: true, buttons: {
					"Update my Password": function() {
						var msg = "";

						if($("#pass1").val() == "" || $("#pass2").val() == "") { msg = msg + "The system cannot accept empty password.<br/>"; }
						if($("#pass1").val() != $("#pass2").val()) { msg = msg + "New Passwords do not match.<br/>"; }
					
						if(msg!="") {
							sendErrorMessage(msg);
						} else {

							$.post("src/sjerp.php", { mod: "changePassword", uid:  $("#myUID").val(), pass: $("#pass1").val(), sid: Math.random() },function() {
								alert("You have successfully updated your password!");
								$("#userChangePass").dialog("close");
							});
						}
					},
					"Continue with the System": function () { $(this).dialog("close"); }
				} }).dialogExtend({
		"closable" : true,
	    "maximizable" : false,
	    "minimizable" : true,
	});
}

function showUserDetails(uid) {
	var uname;
	$.post("src/sjerp.php", { mod: "getUinfo", uid: uid, sid: Math.random() }, function(data) {
		var txtHTML = "<iframe id='frmcust' frameborder=0 width='100%' height='100%' src='user.rights.php?uid="+uid+"'></iframe>";
		$("#userrights").html(txtHTML);
		$("#userrights").dialog({title: "User Access Rights ("+data+")", width: 560, height: 670, resizable: false}).dialogExtend({
			"closable" : true,
		    "maximizable" : false,
		    "minimizable" : true,
		});
	 },"html");
}

function collectVitals(so_no,pid) {
	$("#pemeresult").html("<iframe id='frmPEME' frameborder=0 width='100%' height='100%' src='result.peme.php?so_no="+so_no+"&pid="+pid+"&sid="+Math.random()+"'></iframe>");
	$("#pemeresult").dialog({
		title: "Physical/Medical Examination Form",
		width: xWidth,
		height: 720,
		resizeable: false,
		modal: false,
		buttons: [
			{
				text: "Save Data",
				icons: { primary: "ui-icon-check" },
				click: function() {
					var msg = '';

					if(confirm("Are you sure you want save this data?") == true) {
						var dataString = $('#frmPEME').contents().find('#frmVitals').serialize();
						dataString = "mod=saveVitals&" + dataString;
						$.ajax({
							type: "POST",
							url: "src/sjerp.php",
							data: dataString,
							success: function() {
								alert("Result Successfully Saved!");
							}
						});
					}
				}
			},
			{
				text: "Attach Patient's Signature",
				icons: { primary: "ui-icon-pencil" },
				click: function() {
					
					document.getElementById('frmPEME').contentWindow.captureMySignature();

				}
			},
			{
				text: "Capture Photo",
				icons: { primary: "ui-icon-contact" },
				click: function() {

					$("#cameraFrame").html("<iframe id='frmCamera' frameborder=0 width='100%' height='100%' src='maniniyot.php?so_no="+so_no+"&pid="+pid+"&sid="+Math.random()+"'></iframe>");


					$("#cameraFrame").dialog({
						title: "Capture Photo",
						width: 400,
						height: 420,
						resizeable: false,
						modal: false
					}); 
				}
			},
			{
				text: "Print Form",
				icons: { primary: "ui-icon-print" },
				click: function() {
					
					var so_no = $('#frmPEME').contents().find('#pe_sono').val();
					var pid = $('#frmPEME').contents().find('#pe_pid').val();

					var txtHTML = "<iframe id='prntXrayResult' frameborder=0 width='100%' height='100%' src='print/result.peme.php?so_no="+so_no+"&pid="+pid+"&sid="+Math.random()+"&sid="+Math.random()+"'></iframe>";
					$("#report1").html(txtHTML);
					$("#report1").dialog({title: "Print - Physical/Medical Examinition Form", width: 560, height: 620, resizable: true }).dialogExtend({
						"closable" : true,
						"maximizable" : true,
						"minimizable" : true
					});


				 }
			},
			{
				text: "Close",
				icons: { primary: "ui-icon-closethick" },
				click: function() { $(this).dialog("close"); }
			}
		]
	});
}

function autoSavePEMEData() {
	var dataString = $('#frmPEME').contents().find('#frmVitals').serialize();
		dataString = "mod=saveVitals&" + dataString;
	
		$.ajax({
			type: "POST",
			url: "src/sjerp.php",
			data: dataString,
			success: function() {
				popSaver();
			}
		});
}

function printIndividualResult(lid,so_no,pid) {

	var txtHTML = "<iframe id='prntXrayResult' frameborder=0 width='100%' height='100%' src='print/individual.result.php?so_no="+so_no+"&pid="+pid+"&record_id="+lid+"&sid="+Math.random()+"&sid="+Math.random()+"'></iframe>";
	$("#report5").html(txtHTML);
	$("#report5").dialog({title: "Print - Individual Lab Result", width: 560, height: 620, resizable: true }).dialogExtend({
		"closable" : true,
		"maximizable" : true,
		"minimizable" : true
	});

}

function showLabSummary() {
	$("#summaryDate").datepicker();
	$("#soSummary").dialog({
		title: "Mobile Result Summary",
		width: 480, 
		resizable: false,
		modal: true,
		buttons: {
			"Generate Report in Excel Format": function() {
				window.open("export/mobilesummary.php?so_no="+$("#summarySoNo").val()+"&date="+$("#summaryDate").val()+"&shift="+$("#summaryShift").val()+"&sid="+Math.random()+"","Mobile Result Summary","location=1,status=1,scrollbars=1,width=640,height=720");

			},
			"Close": function() {

				$(this).dialog("close");
			}

		}
	
	});
}

function showCensus() {

	$("#censusDate").datepicker();

	$("#mobileCensus").dialog({
		title: "Mobile Census",
		width: 480, 
		resizable: false,
		modal: true,
		buttons: {
			"Generate Report in Excel Format": function() {
				if($("#censusSoNo").val() != '') {
					window.open("export/census.php?so_no="+$("#censusSoNo").val()+"&date="+$("#censusDate").val()+"&shift="+$("#censusShift").val()+"&status="+$("#censusStatus").val()+"&package="+$("#censusPackage").val()+"&sid="+Math.random()+"","Mobile Census","location=1,status=1,scrollbars=1,width=640,height=720");
				} else {
					parent.sendErrorMessage("Please Specify Mobile SO No. to generate this report.");

				}
			},
			"Close": function() {

				$(this).dialog("close");
			}

		}
	
	});
}

function getPackageList(so_no) {
	$.post("src/sjerp.php", { mod: "getPackageList", so_no: so_no, sid: Math.random() }, function(list) {
		$("#censusPackage").html(list);
	},"html");
}

function showMaxCensus() {
	$("#mobile_d8").datepicker();
	$("#mobileMaxCensus").dialog({
		title: "Maxicare Mobile Census",
		width: 480, 
		resizable: false,
		modal: true,
		buttons: {
			"Generate Report in Excel Format": function() {
				window.open("export/censusMaxicare.php?so_no="+$("#censusMax").val()+"&dt="+$("#mobile_dt").val()+"&sid="+Math.random()+"","Maxicare Mobile Census","location=1,status=1,scrollbars=1,width=640,height=720");

			},
			"Close": function() {

				$(this).dialog("close");
			}

		}
	
	});
}

function showProcessed() {
	$("#proc_sodate").datepicker();
	$("#processedSummary").dialog({
		title: "Summary of Patients Processed",
		width: 480, 
		resizable: false,
		modal: true,
		buttons: {
			"Generate Report": function() {
				var txtHTML = "<iframe id='prntXrayResult' frameborder=0 width='100%' height='100%' src='print/processed.php?so_no="+$("#proc_sono").val()+"&date="+$("#proc_sodate").val()+"&shift="+$("#proc_shift").val()+"&sid="+Math.random()+"&sid="+Math.random()+"'></iframe>";
				$("#report5").html(txtHTML);
				$("#report5").dialog({title: "Summary of Patients Processed", width: 560, height: 620, resizable: true }).dialogExtend({
					"closable" : true,
					"maximizable" : true,
					"minimizable" : true
				});
			},
			"Close": function() {

				$(this).dialog("close");
			}

		}
	
	});
}


/* INVENTORY MANAGEMENT */
function showSRR() {
	var txtHTML = "<iframe id='frmcust' frameborder=0 width='100%' height='100%' src='srr.list.php'></iframe>";
	$("#srrlist").html(txtHTML);
	$("#srrlist").dialog({title: "Stocks Return Slip Summary", width: xWidth, height: 540, resizable: false }).dialogExtend({
		"closable" : true,
	    "maximizable" : false,
	    "minimizable" : true
	});
}

function viewSRR(srr_no) {
	var txtHTML = "<iframe id='frmcust' frameborder=0 width='100%' height='100%' src='srr.details.php?srr_no="+srr_no+"'></iframe>";
	$("#srrdetails").html(txtHTML);
	$("#srrdetails").dialog({title: "Stocks Return Slip Details", width: 1120, height: 560, resizable: true }).dialogExtend({
		"closable" : true,
	    "maximizable" : true,
	    "minimizable" : true
	});
}

function printSRR(srr_no,uid,rePrint) {
	var txtHTML = "<iframe id='frmcust' frameborder=0 width='100%' height='100%' src='print/srr.print.php?srr_no="+srr_no+"&uid="+uid+"&rePrint="+rePrint+"&sid="+Math.random()+"'></iframe>";
	$("#srrprint").html(txtHTML);
	$("#srrprint").dialog({title: "PRINT >> STOCKS RETURN SLIP", width: 560, height: 620, resizable: true }).dialogExtend({
		"closable" : true,
	    "maximizable" : true,
	    "minimizable" : true
	});
}

function showPhy() {
	var txtHTML = "<iframe id='frmcust' frameborder=0 width='100%' height='100%' src='phy.list.php'></iframe>";
	$("#phylist").html(txtHTML);
	$("#phylist").dialog({title: "Physical Inventory Summary", width: xWidth, height: 540, resizable: false }).dialogExtend({
		"closable" : true,
	    "maximizable" : false,
	    "minimizable" : true
	});
}

function viewPhy(doc_no) {
	var txtHTML = "<iframe id='frmcust' frameborder=0 width='100%' height='100%' src='phy.details.php?doc_no="+doc_no+"'></iframe>";
	$("#phydetails").html(txtHTML);
	$("#phydetails").dialog({title: "Physical Inventory Form", width: 1120, height: 560, resizable: true }).dialogExtend({
		"closable" : true,
	    "maximizable" : true,
	    "minimizable" : true
	});
}


function printPhy(doc_no) {
	var txtHTML = "<iframe id='frmcust' frameborder=0 width='100%' height='100%' src='print/phy.print.php?doc_no="+doc_no+"&sid="+Math.random()+"'></iframe>";
	$("#srrprint").html(txtHTML);
	$("#srrprint").dialog({title: "PRINT >> Physical Inventory Form", width: 560, height: 620, resizable: true }).dialogExtend({
		"closable" : true,
	    "maximizable" : true,
	    "minimizable" : true
	});
}

function showSW() {
	var txtHTML = "<iframe id='frmcust' frameborder=0 width='100%' height='100%' src='sw.list.php'></iframe>";
	$("#swlist").html(txtHTML);
	$("#swlist").dialog({title: "Stocks Withdrawal Slip Summary", width: xWidth, height: 540, resizable: false }).dialogExtend({
		"closable" : true,
	    "maximizable" : false,
	    "minimizable" : true
	});
}

function viewSW(sw_no) {
	var txtHTML = "<iframe id='frmcust' frameborder=0 width='100%' height='100%' src='sw.details.php?sw_no="+sw_no+"'></iframe>";
	$("#swdetails").html(txtHTML);
	$("#swdetails").dialog({title: "Stocks Withdrawal Slip Details", width: 1120, height: 560, resizable: true }).dialogExtend({
		"closable" : true,
	    "maximizable" : true,
	    "minimizable" : true
	});
}

function printSW(sw_no,uid,rePrint) {
	var txtHTML = "<iframe id='frmcust' frameborder=0 width='100%' height='100%' src='print/sw.print.php?sw_no="+sw_no+"&uid="+uid+"&rePrint="+rePrint+"&sid="+Math.random()+"'></iframe>";
	$("#srrprint").html(txtHTML);
	$("#srrprint").dialog({title: "PRINT >> STOCKS WITHDRAWAL SLIP", width: 560, height: 620, resizable: true }).dialogExtend({
		"closable" : true,
	    "maximizable" : true,
	    "minimizable" : true
	});
}

function showSTR() {
	var txtHTML = "<iframe id='frmcust' frameborder=0 width='100%' height='100%' src='str.list.php'></iframe>";
	$("#strlist").html(txtHTML);
	$("#strlist").dialog({title: "Stocks Transfer Receipt Summary", width: xWidth, height: 540, resizable: false }).dialogExtend({
		"closable" : true,
	    "maximizable" : false,
	    "minimizable" : true
	});
}

function viewSTR(str_no) {
	var txtHTML = "<iframe id='frmcust' frameborder=0 width='100%' height='100%' src='str.details.php?str_no="+str_no+"'></iframe>";
	$("#strdetails").html(txtHTML);
	$("#strdetails").dialog({title: "Stocks Transfer Receipt Details", width: xWidth, height: 560, resizable: true }).dialogExtend({
		"closable" : true,
	    "maximizable" : true,
	    "minimizable" : true
	});
}

function printSTR(str_no,uid,rePrint) {
	var txtHTML = "<iframe id='frmcust' frameborder=0 width='100%' height='100%' src='print/str.print.php?str_no="+str_no+"&uid="+uid+"&rePrint="+rePrint+"&sid="+Math.random()+"'></iframe>";
	$("#srrprint").html(txtHTML);
	$("#srrprint").dialog({title: "PRINT >> STOCKS TRANSFER RECEIPT", width: 560, height: 620, resizable: true }).dialogExtend({
		"closable" : true,
	    "maximizable" : true,
	    "minimizable" : true
	});
}

function showIBook() {
	$("#ibook_dtf").datepicker(); $("#ibook_dt2").datepicker(); 
	$("#inventorybook").dialog({title: "Inventory Summary", width: 480 }).dialogExtend({
		"closable" : true,
	    "maximizable" : false,
	    "minimizable" : true
	});
}

function processInventory() {
	var txtHTML = "<iframe id='frmcust' frameborder=0 width='100%' height='100%' src='ibook.php?group="+$("#ibook_group").val()+"&dtf="+$("#ibook_dtf").val()+"&dt2="+$("#ibook_dt2").val()+"'></iframe>";
	$("#ibook").html(txtHTML);
	$("#ibook").dialog({title: "Inventory Summary", width: xWidth, height: 540, resizable: false }).dialogExtend({
		"closable" : true,
	    "maximizable" : false,
	    "minimizable" : true
	});
}

function jumpIBookPage(page,stxt,group,dtf,dt2) {
	var txtHTML = "<iframe id='frmcust' frameborder=0 width='100%' height='100%' src='ibook.php?page="+page+"&searchtext="+stxt+"&group="+group+"&dtf="+dtf+"&dt2="+dt2+"'></iframe>";
	$("#ibook").html(txtHTML);
	$("#ibook").dialog({title: "Inventory Summary", width: xWidth, height: 540, resizable: false }).dialogExtend({
		"closable" : true,
	    "maximizable" : false,
	    "minimizable" : true
	});
}

function viewStockcard(item_code,lot_no,expiry,dtf,dt2) {
	var txtHTML = "<iframe id='frmcust' frameborder=0 width='100%' height='100%' src='stockcard.php?item_code="+item_code+"&lot_no="+lot_no+"&expiry="+expiry+"&dtf="+dtf+"&dt2="+dt2+"'></iframe>";
	$("#stockcard").html(txtHTML);
	$("#stockcard").dialog({title: "Inventory Stockcard", width: xWidth, height: 540, resizable: false }).dialogExtend({
		"closable" : true,
	    "maximizable" : false,
	    "minimizable" : true
	});
}

function exportStockcard(item_code,unit,dtf,dt2) {
	window.open("export/stockard.php?item_code="+item_code+"&unit="+unit+"&dtf="+dtf+"&dt2="+dt2+"&sid="+Math.random()+"","Inventory Stockcard","location=1,status=1,scrollbars=1,width=640,height=720");
}

function exportInventoryNow() {
	window.open("export/ibook.php?group="+$("#ibook_group").val()+"&dtf="+$("#ibook_dtf").val()+"&dt2="+$("#ibook_dt2").val()+"&sid="+Math.random()+"","Inventory Book","location=1,status=1,scrollbars=1,width=640,height=720");
}
