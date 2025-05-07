<?php 
	session_start();
	require_once "handlers/_generics.php";
	
	$o = new _init;
	//if(isset($_SESSION['authkey'])) { $exception = $o->validateKey(); if($o->exception != 0) {	$URL = $HTTP_REFERER . "login/index.php?exception=" . $o->exception; } } else { $URL = $HTTP_REFERER . "login"; }
	if(isset($_SESSION['m_authkey'])) { $exception = $o->validateKey(); if($o->exception != 0) {	$URL = $HTTP_REFERER . "login/index.php?exception=" . $o->exception; } } else { $URL = $HTTP_REFERER . "login"; }
	
	if($URL) { header("Location: $URL"); };

	switch($_SESSION['type']) {
		case "2":
			$type = "Medical Examiner &raquo;";
		break;
		case "3":
			$type = "Medical Evaluator &raquo;";
		break;
		default:
			$type = "PCC Staff";
		break;
	}

?>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8"/> 
	<!--meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"-->
	<title>Prime Care Cebu Inc. Mobile System Version 1.0b</title>
	<link rel="icon" type="image/x-icon" href="favicon.ico" />
	<link href="ui-assets/themes/redmond/jquery-ui.css" rel="stylesheet" type="text/css" />
	<link href="style/style.css" rel="stylesheet" type="text/css" />
	<link href="style/dropMenu.css" rel="stylesheet" type="text/css" />
	<script language="javascript" src="ui-assets/jquery/jquery-1.12.3.js"></script>
	<script language="javascript" src="ui-assets/themes/redmond/jquery-ui.js"></script>
	<script language="javascript" src="js/jquery.dialogextend.js"></script>
	<script language="javascript" src="js/main.js?<?php echo uniqid(); ?>"></script>
	<script language="javascript" src="js/dropMenu.js"></script>
	<script>
		/* Query for new file from Machines */
		myInterval = setInterval(checkSession, 20000);


		function checkSession() {
			$.post("src/sjerp.php",{mod: "checkSession", sid: Math.random() }, function(ret) {
				if(ret == 'NotOk') {
					$("#errorMessage2").dialog({ width: 400, resizable: false, modal: true,	buttons: {
							"OK": function() { window.location.href = "logout.php"; }
						}
					}).dialogExtend({
						"closable" : false,
						"maximizable" : false,
						"minimizable" : false
					});
				}
			},"html");
			
		}

	</script>
</head>
<body bgcolor="#ffffff" leftmargin="0" bottommargin="0" rightmargin="0" topmargin="0" <?php if($o->cpass == 'Y') { echo "onLoad=\"showChangePass();\""; } ?> style="background: url(images/wallpaper-6.jpg); background-size: 100% 100%; background-repeat: no-repeat;">
 <table height="100%" width="100%" border="0" cellspacing="0" cellpadding="0" >
	<tr class="ui-dialog-title ui-widget-header">
		<td colspan=2 height=37 style="padding-left: 3px;">
			<a href="#" onclick="javascript: showMenu();"><img src="images/icons/button-menu.png" width=24 height=24 align=absmiddle></a>
		</td>
		<td align=right style="padding-right: 10px;"><img src="images/icons/user.png" align=absmiddle border=0 width=18 height=18 /><span style="font-size: 11px; font-weight: bold; color: #ffffff;">&nbsp;&nbsp;<?php echo $o->getUname($_SESSION['m_userid']) . ' &raquo; ' . $type . ' @ SO # ' . $_SESSION['so_no']; ?>&nbsp;&nbsp;&nbsp;|</span>&nbsp;<a href="logout.php" style="font-size: 12px; font-weight: bold; color: #ffffff; text-decoration: none;" title="Click to Logout"><img src="images/button-logout.png" align=absmiddle border=0 width=24 height=24 />Logout</a></td>
	</tr>
	<tr height=90%>
		<td colspan=3>
			<table width="100%" height="100%" align="center" valign=middle>
				<tr>
					<td align=center>
						
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td colspan=3>
			<table width="100%" height="100%" cellpadding=0 cellspacing=0 align="center" valign=middle>
				<tr bgcolor="#0e5284">
					<td align=center style="font-family: arial, helvetica, sans-serif; color: #fefefe; font-size: 11px; height: 15px; font-weight: bold;">&copy; 2022 Prime Care Cebu Incorporated</td>
				</tr>
			</table>
		</td>
	</tr>
 </table>
<?php
	include("includes/lab_menu.php");
	include("includes/labDialogs.php");
 ?>

<div id="userrights" style="display: none;"></div>
<div id="userdetails" style="display: none;"></div>
<div id="userlist" style="display: none;"></div>
<div id="samplelist" style="display: none;"></div>
<div id="itemlist" style="display: none;"></div>
<div id="itemdetails" style="display: none;"></div>
<div id="srrlist" style="display: none;"></div>
<div id="srrdetails" style="display: none;"></div>
<div id="srrprint" style="display: none;"></div>
<div id="swlist" style="display: none;"></div>
<div id="swdetails" style="display: none;"></div>
<div id="strlist" style="display: none;"></div>
<div id="strdetails" style="display: none;"></div>
<div id="strprint" style="display: none;"></div>
<div id="phylist" style="display: none;"></div>
<div id="phydetails" style="display: none;"></div>
<div id="phyprint" style="display: none;"></div>
<div id="ibook" style="display: none;"></div>
<div id="stockcard" style="display: none;"></div>
<div id="changepass" style="display: none;"></div>
<div id="csolist" style="display: none;"></div>
<div id="csodetails" style="display: none;"></div>
<div id="csoprint" style="display: none;"></div>
<div id="csochecklist" style="display: none;"></div>
<div id="patientlist" style="display: none;"></div>
<div id="patientdetails" style="display: none;"></div>
<div id="pemelist" style="display: none;"></div>
<div id="pemeresult" style="display: none;"></div>
<div id="resultlist" style="display: none;"></div>
<div id="xraylist" style="display: none;"></div>
<div id="xrayresult" style="display: none;"></div>
<div id="serviceslist" style="display: none;"></div>
<div id="servicesdetails" style="display: none;"></div>
<div id="signaturepad" style="display: none;"></div>
<div id="descResult" name="descResult" style="display: none;"></div>
<div id="cbcResult" name="cbcResult" style="display: none;"></div>
<div id="bloodChemResult" name="bloodChemResult" style="display: none;"></div>
<div id="uaResult" name="uaResult" style="display: none;"></div>
<div id="stoolResult" name="stoolResult" style="display: none;"></div>
<div id="semAnalReport" name="semAnalReport" style="display: none;"></div>
<div id="barcode" name="barcode" style="display: none;"></div>
<div id="registration" name="registration" style="display: none;"></div>
<div id="routingslip" name="routingslip" style="display: none;"></div>
<div id="ecglist" style="display: none;"></div>
<div id="ecgResult" name="ecgResult" style="display: none;"></div>

<?php for($rpt = 1; $rpt <= 10; $rpt++) { echo "<div id=\"report$rpt\" style=\"display: none;\"></div>"; } ?>

<div id="loaderMessage" style="display: none;">
	<table width=100%>
		<tr>
			<td align=center style="color:grey; padding-top: 40px; font-size: 11px;"><img src="images/ajax-loader.gif" align=absmiddle>&nbsp;Please wait while the system is processing your request...</td>
		</tr>
	</table>
</div>
<div id="errorMessage" title="Error Message" style="display: none;">
	<p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span><b>Unable to continue due to the following error(s):</b></p>
	<p style="margin-left: 20px; text-align: justify;" id="message"></span></p>
</div>
<div id="errorMessage2" title="Error Message" style="display: none;">
	<p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span><b>Invalid Session Information Detected.</b></p>
	<p style="margin-left: 20px; text-align: justify;" id="message2">The system detects an invalid user session. To verify your identity, you will be required to Login and issue the correct credential.</span></p>
</div>
<div id="mainLoading" style="display:none; width:100%;height:100%;position:absolute;top:0;margin:auto;"> 
	<div style="background-color:white;width:10%;height:20%;;margin:auto;position:relative;top:100;">
		<img style="display:block;margin-left:auto;margin-right:auto;" src="images/ajax-loader.gif" width=128 height=128 align=absmiddle /> 
	</div>
	<div id="mainLoading2" style="background-color:white;width:100%;height:100%;position:absolute;top:0;margin:auto;opacity:0.5;"> </div>
</div>
<div class="suggestionsBox" id="suggestions" style="display: none;">
	<div class="suggestionList" id="autoSuggestionsList">&nbsp;</div>
</div>
<div id="cameraFrame" style="display: none;"></div>
</body>
</html>