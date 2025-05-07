<?php
	session_start();
	//ini_set("display_errors","On");
	require_once "../handlers/_generics.php";
	
	$con = new _init;
	$date = date('Y-m-d');
	$bid = 1;
	//$uid = $_SESSION['userid'];
	$uid = $_SESSION['m_userid'];

	switch($_POST['mod']) {
		
		/* Miscellaneous */
		case "getCities":
			$cq = $con->dbquery("select citymunCode, citymunDesc from pccmain.options_cities where provCode = '$_POST[pid]';");
			while(list($cid,$ctname) = $cq->fetch_array()) {
				echo "<option value='$cid'>$ctname</option>\n";
			}
		break;
		case "getBrgy":
			$cq = $con->dbquery("select brgyCode, brgyDesc from pccmain.options_brgy where citymunCode = '$_POST[city]';");
			echo "<option value='0'>- Not Applicable -</option>\n";
			while(list($cid,$ctname) = $cq->fetch_array()) {
				echo "<option value='$cid'>$ctname</option>\n";
			}
		break;
		case "getSections":
			$vg = $con->dbquery("select section_code, section_name from pccmain.options_sections where parent_dept = '$_POST[dept]';");
			echo "<option value=''>-N/A-</option>\n";
			while(list($scode,$sname) = $vg->fetch_array()) {
				echo "<option value='$scode'>$sname</option>\n";
			}
		break;

		/* Patient Archive */
		case "savePatientInfo":
			if($_POST['pid'] != '') {
				$con->dbquery("UPDATE IGNORE pccmain.patient_info SET lname = '".$con->escapeString(htmlentities($_POST['p_lname']))."',fname = '".$con->escapeString(htmlentities($_POST['p_fname']))."',mname = '".$con->escapeString(htmlentities($_POST['p_mname']))."',suffix = '$_POST[p_suffix]',gender = '$_POST[p_gender]', pwd = '$_POST[p_pwd]', birthdate = '".$con->formatDate($_POST['p_bday'])."', birthplace = '".$con->escapeString(htmlentities($_POST['p_birthplace']))."', nationality = '$_POST[nation]',cstat = '$_POST[p_cstat]',spouse_lname = '".$con->escapeString(htmlentities($_POST['s_lname']))."',spouse_fname = '".$con->escapeString(htmlentities($_POST['s_fname']))."',spouse_mname = '".$con->escapeString(htmlentities($_POST['s_mname']))."',spouse_suffix = '$_POST[s_suffix]',spouse_birthdate = '".$con->formatDate($_POST['s_bday'])."',mobile_no = '$_POST[p_mobileno]',tel_no = '$_POST[p_telephone]',email_add = '$_POST[p_email]',guardian = '".$con->escapeString(htmlentities($_POST['p_guardian']))."',street = '".$con->escapeString(htmlentities($_POST['p_street']))."',brgy = '$_POST[p_brgy]',city = '$_POST[p_city]',province = '$_POST[p_province]',phic_no = '$_POST[p_phic]',occupation = '$_POST[p_occupation]',employer = '".$con->escapeString(htmlentities($_POST['p_employer']))."',emp_street = '".$con->escapeString(htmlentities($_POST['e_street']))."',emp_brgy = '$_POST[e_brgy]',emp_city = '$_POST[e_city]',emp_province = '$_POST[e_province]', emp_telno = '$_POST[e_telno]', cabinet_no = '$_POST[cabinet_no]', drawer_no = '$_POST[drawer_no]', folder_no = '$_POST[folder_no]', hmo_provider = '" . htmlentities($_POST['p_hmo']) . "', hmo_no = '$_POST[p_hmo_no]', hmo_expiry = '".$con->formatDate($_POST['p_hmo_expiry'])."', emp_idno = '$_POST[p_idno]', updated_by = '$uid',updated_on = now() where patient_id = '$_POST[pid]';");
				
				/* Update Sales Order, Official Receipt & SOA Patient Name */
				$myaddress = '';
				$patient = $con->getArray("SELECT CONCAT(lname,', ',fname,', ',mname,' ',suffix) AS `name`, LPAD(patient_id,6,'0') AS cid, street, brgy, city, province FROM pccmain.patient_info WHERE patient_id = '$_POST[pid]';");
			
				list($brgy) = $con->getArray("SELECT brgyDesc FROM options_brgy WHERE brgyCode = '$row[brgy]';");
				list($ct) = $con->getArray("SELECT citymunDesc FROM options_cities WHERE cityMunCode = '$row[city]';");
				list($prov) = $con->getArray("SELECT provDesc FROM options_provinces WHERE provCode = '$row[province]';");
			
				if($patient['street'] != '') { $myaddress.=$patient['street'].", "; }
				if($brgy != "") { $myaddress.=$brgy.", "; }
				if($ct != "") { $myaddress.=$ct.", "; }
				if($prov != "")  { $myaddress.=$prov.", "; }
				$myaddress = substr($myaddress,0,-2);

			} else {
				$con->dbquery("INSERT IGNORE pccmain.patient_info (lname,fname,mname,suffix,gender,birthdate,birthplace,pwd,nationality,cstat,spouse_lname,spouse_fname,spouse_mname,spouse_suffix,spouse_birthdate,mobile_no,tel_no,email_add,guardian,street,brgy,city,province,phic_no,occupation,employer,emp_street,emp_brgy,emp_city,emp_province,emp_telno,cabinet_no,drawer_no,folder_no,hmo_provider,hmo_no,hmo_expiry,emp_idno,created_by,created_on) VALUES ('".$con->escapeString(htmlentities($_POST['p_lname']))."','".$con->escapeString(htmlentities($_POST['p_fname']))."','".$con->escapeString(htmlentities($_POST['p_mname']))."','$_POST[p_suffix]','$_POST[p_gender]','".$con->formatDate($_POST['p_bday'])."','".$con->escapeString(htmlentities($_POST['p_birthplace']))."','$_POST[p_pwd]','$_POST[p_naation]','$_POST[p_cstat]','".$con->escapeString(htmlentities($_POST['s_lname']))."','".$con->escapeString(htmlentities($_POST['s_fname']))."','".$con->escapeString(htmlentities($_POST['s_mname']))."','$_POST[s_suffix]','".$con->formatDate($_POST['s_bday'])."','$_POST[p_mobileno]','$_POST[p_telephone]','$_POST[p_email]','".$con->escapeString(htmlentities($_POST['p_guardian']))."','".$con->escapeString(htmlentities($_POST['p_street']))."','$_POST[p_brgy]','$_POST[p_city]','$_POST[p_province]','$_POST[p_phic]','$_POST[p_occupation]','".$con->escapeString(htmlentities($_POST['p_employer']))."','".$con->escapeString(htmlentities($_POST['e_street']))."','$_POST[e_brgy]','$_POST[e_city]','$_POST[e_province]','$_POST[e_telno]','$_POST[cabinet_no]','$_POST[drawer_no]','$_POST[folder_no]','$_POST[p_hmo]','$_POST[p_hmo_no]','".$con->formatDate($_POST['p_hmo_expiry'])."','$_POST[p_idno]','$uid',now());");
			}
		break;

		/* Services */
		case "checkServiceDupCode":
			if($_POST['rid'] != "") { $f = " and id != '$_POST[rid]'"; }
			list($isExist) = $con->getArray("select count(*) from pccmain.services_master where code = '$_POST[item_code]' $f;");
			if($isExist == 0) { echo "NODUPLICATE"; }
		break;
		
		case "getServiceCode":
			list($scode) = $con->getArray("select `code` from options_servicecat where id = '$_POST[mid]';");
			list($series) = $con->getArray("SELECT LPAD(IFNULL(MAX(series+1),1),3,0) FROM (SELECT TRIM(LEADING '0' FROM(SUBSTRING(`code`,2,3))) AS series FROM pccmain.services_master WHERE category = '$_POST[mid]') a;");		
			echo $scode.$series;
		break;

		case "getServiceSubgroup":
			$a = $con->dbquery("select id, subcategory from options_servicesubcat where parent_id = '$_POST[parent]';");
			echo "<option value='0'>- Not Applicable -</option>";
			while(list($y,$z) = $a->fetch_array()) {
				echo "<option value = '$y'>$z</option>";
			}
		break;

		case "saveSInfo":
			if($_POST['rid'] != '') {
				$con->dbquery("update ignore pccmain.services_master set `code` = '$_POST[item_code]', barcode = '$_POST[item_barcode]', `description` = '".$con->escapeString($_POST['item_description'])."', fulldescription = '".$con->escapeString($_POST['item_fdescription'])."',category = '$_POST[item_category]',subcategory = '$_POST[item_sgroup]', rev_acct = '$_POST[rev_acct]', unit = '$_POST[item_unit]', unit_cost = '".$con->formatDigit($_POST['item_unitcost'])."', unit_price = '".$con->formatDigit($_POST['item_unitprice'])."', with_specimen = '$_POST[with_specimen]', sample_type = '$_POST[sample_type]', with_subtests = '$_POST[with_subtests]', with_bom = '$_POST[with_bom]', lab_tat = '$_POST[lab_tat]', collection_tat = '".$con->formatDigit($_POST['collection_tat'])."', accession_tat = '".$con->formatDigit($_POST['accession_tat'])."', processing_tat = '".$con->formatDigit($_POST['processing_tat'])."', result_tat = '$_POST[result_tat]', container_type = '$_POST[container_type]', result_type = '$_POST[result_type]', updated_by = '$uid', updated_on = now() where id = '$_POST[rid]';");
			} else {
				$con->dbquery("INSERT IGNORE INTO pccmain.services_master (`code`,barcode,`description`,fulldescription,category,subcategory,rev_acct,unit,unit_cost,unit_price,with_specimen,sample_type,with_subtests,with_bom,lab_tat,collection_tat,accession_tat,processing_tat,result_tat,container_type,result_type,created_by,created_on) VALUES ('$_POST[item_code]','$_POST[item_barcode]','".$con->escapeString($_POST['item_description'])."','".$con->escapeString($_POST['item_fdescription'])."','$_POST[item_category]','$_POST[item_sgroup]','$_POST[rev_acct]','$_POST[item_unit]','".$con->formatDigit($_POST['item_unitcost'])."','".$con->formatDigit($_POST['item_unitprice'])."','$_POST[with_specimen]','$_POST[sample_type]','$_POST[with_subtests]','$_POST[with_bom]','$_POST[lab_tat]','".$con->formatDigit($_POST['collection_tat'])."','".$con->formatDigit($_POST['accession_tat'])."','".$con->formatDigit($_POST['processing_tat'])."','$_POST[result_tat]','$_POST[container_type]','$_POST[result_type]','$uid',now());");
			}
		break;

		case "checkifBoM":
			$e = $con->getArray("select count(*) from services_bom where `code` = '$_POST[scode]' and item_code = '$_POST[icode]';");
			if($e[0] == 0) { echo "ok"; }
		break;

		case "newBOM":
			$con->dbquery("insert ignore into services_bom (`code`,item_code,description,unit,qty,unit_cost,amount,remarks,created_by,created_on) values ('$_POST[scode]','$_POST[icode]','".$con->escapeString($_POST['description'])."','$_POST[unit]','".$con->formatDigit($_POST['qty'])."','".$con->formatDigit($_POST['cost'])."','".$con->formatDigit($_POST['amount'])."','".$con->escapeString($_POST['remarks'])."','$uid',now());");
		break;

		case "retrieveBoM":
			echo json_encode($con->getArray("select *, format(unit_cost,2) as ucost, format(amount,2) as amt from services_bom where record_id = '$_POST[rid]';"));
		break;

		case "updateBoM":
			$con->dbquery("update ignore services_bom set qty = '".$con->formatDigit($_POST['qty'])."', amount = '".$con->formatDigit($_POST['amount'])."', remarks = '".$con->escapeString($_POST['remarks'])."', updated_by = '$uid', updated_on = now() where record_id = '$_POST[rid]';");
		break;

		case "removeBoM":
			$con->dbquery("delete from services_bom where record_id = '$_POST[rid]';");
		break;

		case "addSublist":
			$con->dbquery("INSERT IGNORE INTO services_subtests (`parent`,`code`,`description`) values ('$_POST[parent]','$_POST[code]','".$con->escapeString($_POST['description'])."');");
		break;

		case "removeSublist":
			$con->dbquery("DELETE FROM services_subtests where record_id = '$_POST[lid]';");
		break;

		case "addAttribute":
			$con->dbquery("INSERT IGNORE INTO lab_testvalues (`code`,`attribute_type`,`attribute`,`unit`,`min_value`,`max_value`,`critical_low_value`,`critical_high_value`,`descriptive_value`) values ('$_POST[parent]','$_POST[attr_type]','" . $con->escapeString($_POST['attr']) . "','$_POST[unit]','$_POST[min]','$_POST[max]','$_POST[low]','$_POST[high]','". $con->escapeString($_POST['desc']) . "');");
		break;

		case "retrieveTestValues":
			echo json_encode($con->getArray("select * from lab_testvalues where record_id = '$_POST[lid]';"));
		break;

		case "updateAttribute":
			$con->dbquery("UPDATE IGNORE lab_testvalues set attribute_type = '$_POST[attr_type]', attribute = '" . $con->escapeString($_POST['attr']) . "', unit = '$_POST[unit]', min_value = '$_POST[min]', max_value = '$_POST[max]', critical_low_value = '$_POST[low]', critical_high_value = '$_POST[high]', descriptive_value = '". $con->escapeString($_POST['desc']) . "', updated_by = '$uid', updated_on = now() where record_id = '$_POST[lid]';");
		break;

		case "removeAttribute":
			$con->dbquery("DELETE FROM lab_testvalues where record_id = '$_POST[lid]';");
		break;

		case "saveXrayTemplate":
			if($_POST['tempid'] == '') {
				$con->dbquery("INSERT IGNORE INTO xray_templates (title,template,xray_type,template_owner,created_on) VALUES ('".htmlentities($_POST['template_title'])."','".htmlentities($_POST['template_details'])."','$_POST[template_type]','".htmlentities($_POST['template_owner'])."',now());");
			} else {
				$con->dbquery("UPDATE IGNORE xray_templates set title = '".htmlentities($_POST['template_title'])."', template = '".htmlentities($_POST['template_details'])."', xray_type = '$_POST[template_type]', template_owner = '".htmlentities($_POST['template_owner'])."', updated_by = '$uid', updated_on = now() where id = '$_POST[tempid]';");
			}
		break;

		case "attachLabSampleFile":
			$uploadDir = "../images/attachments/";
			$filePathUploadDir = "images/attachments/";

			$fileName = $_FILES['att_file']['name'];
			$tmpName = $_FILES['att_file']['tmp_name'];
			
			
			if($fileName!='') {

				/* CHANGE UNIQUE FILENAME TO PREVENT DUPLICATION */
				$ext = substr(strrchr($fileName, "."), 1);
				$randName = md5(rand() * time());
				$newFileName = $randName . "." . $ext;
				$filePath = $uploadDir . $newFileName;
				$result = move_uploaded_file($tmpName, $filePath);
			
				$fileUploadPath = $filePathUploadDir . $newFileName;
				
				//echo "update lab_samples set with_file = 'Y', file_title = '".$con->escapeString($_POST['att_title'])."', file_remarks = '".$con->escapeString($_POST['att_remarks'])."', file_path = '$fileUploadPath' where so_no = '$_POST[att_sono]' and `code` = '$_POST[att_code]' and serialno = '$_POST[att_serialno]';";
				$con->dbquery("update lab_samples set with_file = 'Y', file_title = '".$con->escapeString($_POST['att_title'])."', file_remarks = '".$con->escapeString($_POST['att_remarks'])."', file_path = '$fileUploadPath' where so_no = '$_POST[att_sono]' and `code` = '$_POST[att_code]' and serialno = '$_POST[att_serialno]';");
				$con->updateLabSampleStatus($_POST['att_sono'],'O001',$_POST['att_serialno'],'3',$bid,$uid);	

			}


		break;

		case "openAttachment":
			list($file) = $con->getArray("select CONCAT('<img src=\"',file_path,'\" width=100% height=100% />') from lab_samples where so_no = '$_POST[so_no]' and pid = '$_POST[pid]' and `code` = '$_POST[code]';");
			echo $file;
		break;

		case "retrieveOrderForSample":
			$a = $con->getArray("SELECT LPAD(a.so_no,6,0) AS so, DATE_FORMAT(a.so_date,'%m/%d/%Y') AS sodate, LPAD(a.pid,6,0) AS pid, CONCAT(b.lname,', ',b.fname,' ',b.mname) AS patient_name, IF(b.gender='M','Male','Female') AS gender, DATE_FORMAT(b.birthdate,'%m/%d/%Y') AS birthdate, YEAR(a.so_date) - YEAR(b.birthdate) AS age, IF(a.parent_code!=a.code,CONCAT(c.description,' :: ',a.procedure),a.procedure) AS particulars, a.code, a.parent_code, a.sampletype AS sample_type, c.container_type, b.mobile_no, b.email_add, d.sample_type AS xsample FROM lab_samples a LEFT JOIN pccmain.patient_info b ON a.pid = b.patient_id LEFT JOIN pccmain.services_master c ON a.code = c.code LEFT JOIN pccmain.options_sampletype d ON a.sampletype = d.id WHERE a.so_no = '$_POST[sono]' AND a.code = '$_POST[code]' and a.pid = '$_POST[pid]';");

			$series = $con->getArray("SELECT serialno from lab_samples where `code` = '$_POST[code]' and pid = '$_POST[pid]' and so_no = '$_POST[sono]';");
			if($series[0] == 0) {
				list($code) = $con->getArray("select `sn_code` from pccmain.options_sampletype where id = '$a[sample_type]';");
				$series = $con->getArray("SELECT concat('$code',LPAD(IFNULL(MAX(series+1),1),8,0),'M') as series FROM (SELECT TRIM(LEADING '0' FROM SUBSTRING(`serialno`,2,8)) AS series FROM lab_samples WHERE sampletype = '$a[sample_type]') a;");
			}
			
			/* Count for tests that uses similar container & sample type */
			$scount = $con->getArray("select count(*) as samplecount from lab_samples where so_no = '$_POST[sono]' and samplecontainer = '$a[container_type]' and sampletype = '$a[sample_type]' and extracted = 'N' and code != '$a[code]';");
			$result = array_merge($a,$series,$scount);

			echo json_encode($result);
		break;

		case "retrieveSameSample":
			$sQuery = $con->dbquery("select `code`, `procedure` from lab_samples where so_no = '$_POST[sono]' and sampletype = '$_POST[stype]' and samplecontainer = '$_POST[ctype]' and extracted = 'N' and code != '$_POST[code]' and pid = '$_POST[pid]' order by `procedure`;");
		
			$html  = '<fieldset name="sameTests" id="sameTest" style="padding:5px;">
						<legend class="bareBold" style="font-size: 9px;">Use sample for the following request: </legend>
						';
			while($sRow = $sQuery->fetch_array()) {
				$html .= '<input type="checkbox" id="othercodes[]" name="othercodes[]" value="' . $sRow['code'] . '" checked>&nbsp;<span class="bareBold">'.$sRow['procedure'].'</span><br/>';

			}
			$html .= '</fieldset>';
			echo $html;
		break;

		case "saveSample":
		
			$tmpdate = explode(" ",$_POST['phleb_date']);
			$extractDate = $con->formatDate(trim($tmpdate[0]));
			$extractTime = $tmpdate[1];


			$phlebtime = $_POST['phleb_hr'] . ":" . $_POST['phleb_min'] . ":00";
			$con->dbquery("UPDATE IGNORE lab_samples set extracted = 'Y', serialno = '$_POST[phleb_serialno]', testkit = '$_POST[phleb_testkit]', lotno = '$_POST[phleb_testkit_lotno]', expiry = '$_POST[phleb_testkit_expiry]', extractdate = '$extractDate', extractime = '$extractTime', extractby = '".htmlentities($_POST['phleb_by'])."', `location` = '$_POST[phleb_location]', remarks = '".$con->escapeString(htmlentities($_POST['phleb_remarks']))."', `status` = '1', updated_by = '$uid', updated_on = now() WHERE `code` = '$_POST[phleb_code]' and pid = '$_POST[phleb_pid]' and so_no = '$_POST[phleb_sono]';");
			
			/* Check if other samples */
			if(count($_POST['othercodes']) > 0) {
				foreach($_POST['othercodes'] as $scode) {
					$con->dbquery("UPDATE IGNORE lab_samples set extracted = 'Y', serialno = '$_POST[phleb_serialno]', testkit = '$_POST[phleb_testkit]', lotno = '$_POST[phleb_testkit_lotno]', expiry = '$_POST[phleb_testkit_expiry]', extractdate = '$extractDate', extractime = '$extractTime', extractby = '".$con->escapeString(htmlentities($_POST['phleb_by']))."', `location` = '$_POST[phleb_location]', remarks = '".$con->escapeString(htmlentities($_POST['phleb_remarks']))."', `status` = '1', updated_by = '$uid', updated_on = now() WHERE `code` = '$scode' and so_no = '$_POST[phleb_sono]' and pid = '$_POST[phleb_pid]';");
				}
			}
	
		break;

		case "getPrintedLabel":
			list($labelPath) = $con->getArray("select label_path from cso_details where line_id = '$_POST[id]';");
			echo $labelPath;
		break;

		case "checkScannedSerialNo":
			$serialno = substr(trim($_POST['serialno']),0,10);
			list($count) = $con->getArray("select count(*) from lab_samples where serialno = '$serialno';");
			echo $count;
		break;

		case "tagScannedSerialNo":
			$patientData = $con->getArray("SELECT a.serialno, CONCAT(b.lname,'^',b.fname,'^',b.mname) as pname, `procedure`, DATE_FORMAT(b.birthdate,'%d %b %Y') AS bdate, b.gender, if(extracted != 'Y',DATE_FORMAT(NOW(),'%m/%d/%Y %H:%i %p'),DATE_FORMAT(concat(extractdate,' ',extractime),'%m/%d/%Y %H:%i %p')) AS tstamp, a.extracted FROM lab_samples a LEFT JOIN patient_info b ON a.pid = b.patient_id WHERE a.serialno = '$_POST[serialno]';");
			echo json_encode($patientData);

			if($patientData['extracted'] != 'Y') {
				list($extractby) = $con->getArray("select fullname from pccmain.user_info where emp_id = '$uid';");
				$con->dbquery("UPDATE IGNORE lab_samples SET extracted = 'Y', extractdate = DATE_FORMAT(now(),'%Y-%m-%d'), extractime =  DATE_FORMAT(NOW(),'%H:%i:00'), extractby = '" . $extractby . "' where serialno = '$_POST[serialno]';");
			}

		break;

		case "checkSerialStatus":
			echo json_encode($con->getArray("select count(*) as mycount from lab_samples where serialno = '$_POST[serialno]';"));
		break;

		case "retrieveSample":
			echo json_encode($con->getArray("SELECT b.patient_name AS pname,`code`,`procedure`,sampletype,serialno, location, DATE_FORMAT(extractdate,'%m/%d/%Y') AS exdate,  SUBSTR(extractime,1,2) AS hr, SUBSTR(extractime,4,2) AS MIN, extractby FROM lab_samples a LEFT JOIN so_header b ON a.so_no = b.so_no AND a.branch = b.branch WHERE a.record_id = '$_POST[lid]';"));
		break;

		case "rejectSample":
			$con->dbquery("update lab_samples set status = '2', rejection_remarks = '".$con->escapeString(htmlentities())."', updated_by = '$uid', updated_on = now() where record_id = '$_POST[lid]';");
		break;

		case "resultSingle":

			//$a = $con->getArray("SELECT record_id AS id, LPAD(a.so_no,6,0) AS myso,DATE_FORMAT(b.so_date,'%m/%d/%Y') AS sodate, LPAD(b.patient_id,6,0) AS mypid,b.patient_name AS pname, YEAR(b.so_date) - YEAR(c.birthdate) AS age,IF(c.gender='M','Male','Female') AS gender, DATE_FORMAT(c.birthdate,'%m/%d/%Y') AS bday,e.patientstatus,b.physician,a.code,a.procedure,sampletype,serialno,DATE_FORMAT(extractdate,'%m/%d/%Y') AS exday,TIME_FORMAT(extractime,'%h:%i %p') AS etime,extractby,a.location,a.testkit,a.lotno,date_format(a.expiry,'%m/%d/%Y') as expiry FROM lab_samples a LEFT JOIN so_header b ON a.so_no = b.so_no AND a.branch = b.branch LEFT JOIN pccmain.patient_info c ON b.patient_id = c.patient_id LEFT JOIN pccmain.options_patientstat e ON b.patient_stat = e.id WHERE a.record_id = '$_POST[lid]';");
			$a = $con->getArray("SELECT record_id AS id, LPAD(a.so_no,6,0) AS myso,DATE_FORMAT(b.cso_date,'%m/%d/%Y') AS sodate, LPAD(b.customer_code,6,0) AS cid,LPAD(c.patient_id,6,0) AS mypid, b.customer_name AS cname,CONCAT(c.lname,', ',c.fname,' ',c.mname) AS pname,YEAR(b.cso_date) - YEAR(c.birthdate) AS age,IF(c.gender='M','Male','Female') AS gender, DATE_FORMAT(c.birthdate,'%m/%d/%Y') AS bday,e.patientstatus,a.code,a.procedure,sampletype,serialno,DATE_FORMAT(extractdate,'%m/%d/%Y') AS exday,TIME_FORMAT(extractime,'%h:%i %p') AS etime,extractby,a.location,a.testkit,a.lotno,DATE_FORMAT(a.expiry,'%m/%d/%Y') AS expiry FROM lab_samples a LEFT JOIN cso_header b ON a.so_no = b.cso_no AND a.branch = b.branch LEFT JOIN pccmain.patient_info c ON a.pid = c.patient_id LEFT JOIN pccmain.options_patientstat e ON b.status = e.id WHERE a.record_id = '$_POST[lid]';");
			
			switch($_POST['submod']) {
				case "labSingle":
					list($isCount) = $con->getArray("select count(*) from lab_singleResult where so_no = '$a[myso]' and branch = '$bid' and code = '$a[code]' and serialno = '$a[serialno]';");
					if($isCount > 0) {
						$b = $con->getArray("SELECT attribute,unit,lower_value as `min_value`,upper_value as `max_value`,`value`,performed_by,remarks FROM lab_singleResult WHERE so_no = '$a[myso]' and branch = '$bid' and code = '$a[code]' and serialno = '$a[serialno]';");	
					} else {
						$b = $con->getArray("SELECT attribute,unit,`min_value`,`max_value`,'' as `value`,'' as remarks FROM lab_testvalues WHERE `code` = '$a[code]';");
					}
				break;
				case "enumResult":
					$b = $con->getArray("select patient_stat, result, performed_by, remarks from lab_enumresult where so_no = '$a[myso]' and branch = '$bid' and serialno = '$a[serialno]' and code = '$a[code]';");
				break;
				case "bloodType":
					$b = $con->getArray("select patient_stat, result, rh, performed_by, remarks from lab_bloodtyping where so_no = '$a[myso]' and branch = '$bid' and serialno = '$a[serialno]' and code = '$a[code]';");
					if(!$b) { $b = array("rh"=>'Positive',"result"=>'A+'); }
				break;
				case "lipidPanel":
					$b = $con->getArray("select cholesterol,triglycerides,hdl,ldl,vldl,sgpt,performed_by,remarks from lab_lipidpanel where so_no = '$a[myso]' and branch = '$bid' and serialno = '$a[serialno]';");
				break;
				case "ogttResult":
					$b = $con->getArray("select fasting,fasting_uglucose,first_hr,first_hr_uglucose,second_hr,second_hr_uglucose from lab_ogtt where so_no = '$a[myso]' and branch = '$bid' and serialno = '$a[serialno]';");
				break;
				case "dengueResultView":
					$b = $con->getArray("select dengue_ag,dengue_igg,dengue_igm from lab_dengue where so_no = '$a[myso]' and branch = '$bid' and serialno = '$a[serialno]';");
				break;
				case "hivResult":
					$b = $con->getArray("select hiv_one,hiv_two,hiv_half from lab_hiv where so_no = '$a[myso]' and branch = '$bid' and serialno = '$a[serialno]';");
				break;
				case "lipidPanel":
					$b = $con->getArray("select cholesterol,triglycerides,hdl,ldl,vldl,sgpt,performed_by,remarks from lab_lipidpanel where so_no = '$a[myso]' and branch = '$bid' and serialno = '$a[serialno]';");
				break;
				
			}

			if(count($b) > 0) {
				echo json_encode(array_merge($a,$b));
			} else { echo json_encode($a); }
		break;

		case "changeCbcMachine":
			$con->dbquery("UPDATE IGNORE lab_samples set machine = '$_POST[machine]' where serialno = '$_POST[serialno]' and so_no = '$_POST[so_no]' and pid = '$_POST[pid]';");
			echo "UPDATE IGNORE lab_samples set machine = '$_POST[machine]' where serialno = '$_POST[serialno]' and so_no = '$_POST[so_no]' and pid = '$_POST[pid]';";
			/* Check if result is available */
			list($isCount) = $con->getArray("select count(*) from lab_cbcresult where serialno = '$_POST[serialno]' and so_no = '$_POST[so_no]' and pid = '$_POST[pid]';");
			if($isCount > 0) {
				$con->dbquery("UPDATE IGNORE lab_cbcresult set machine = '$_POST[machine]' where serialno = '$_POST[serialno]' and so_no = '$_POST[so_no]' and pid = '$_POST[pid]';");
			}

		break;

		case "saveEnumResult":
			list($cnt) = $con->getArray("select count(*) from lab_enumresult where so_no = '$_POST[enum_sono]' and branch = '$bid' and code = '$_POST[enum_code]' and serialno = '$_POST[enum_serialno]';");
			if($cnt > 0) {
				$con->dbquery("update ignore lab_enumresult set result = '$_POST[enum_result]', remarks = '".$con->escapeString($_POST['enum_remarks']) . "' where so_no = '$_POST[enum_sono]' and branch = '$bid' and code = '$_POST[enum_code]' and serialno = '$_POST[enum_serialno]';");
			} else {
				$con->dbquery("INSERT ignore INTO lab_enumresult (branch,so_no,pid,pname,`procedure`,result_date,sampletype,serialno,code,result,remarks,performed_by,created_by,created_on) VALUES ('$bid','$_POST[enum_sono]','$_POST[enum_pid]','$_POST[enum_pname]','$_POST[enum_procedure]','".$con->formatDate($_POST['enum_date'])."','$_POST[enum_spectype]','$_POST[enum_serialno]','$_POST[enum_code]','$_POST[enum_result]','".$con->escapeString($_POST['enum_remarks'])."','$_POST[enum_result_by]','$uid',now());");
				//echo "INSERT INTO lab_enumresult (branch,so_no,pid,pname,`procedure`,result_date,sampletype,serialno,code,result,remarks,performed_by,created_by,created_on) VALUES ('$bid','$_POST[enum_sono]','$_POST[enum_pid]','$_POST[enum_pname]','$_POST[enum_procedure]','".$con->formatDate($_POST['enum_date'])."','$_POST[enum_spectype]','$_POST[enum_serialno]','$_POST[enum_code]','$_POST[enum_result]','".$con->escapeString($_POST['enum_remarks'])."','$_POST[enum_result_by]','$uid',now())";
			}
			$con->updateLabSampleStatus($_POST['enum_sono'],$_POST['enum_code'],$_POST['enum_serialno'],'3',$bid,$uid);
		break;

		case "validateEnumResult":
			$con->dbquery("update ignore lab_enumresult set result = '$_POST[enum_result]', remarks = '".$con->escapeString($_POST['enum_remarks']) . "' where so_no = '$_POST[enum_sono]' and branch = '$bid' and code = '$_POST[enum_code]' and serialno = '$_POST[enum_serialno]';");
			$con->validateResult("lab_enumresult",$_POST['enum_sono'],$_POST['enum_code'],$_POST['enum_serialno'],$bid,$uid);
			$con->updateLabSampleStatus($_POST['enum_sono'],$_POST['enum_code'],$_POST['enum_serialno'],'4',$bid,$uid);	
			$con->dbquery("update so_details set result_available = 'Y' where so_no = '$_POST[enum_sono]' and branch = '$bid' and code = '$_POST[enum_code]' and sample_serialno = '$_POST[enum_serialno]';");
		break;

		case "saveECGResult":
			list($cnt) = $con->getArray("select count(*) from lab_ecgresult where so_no = '$_POST[ecg_sono]' and branch = '$bid' and code = '$_POST[ecg_code]' and serialno = '$_POST[ecg_serialno]';");
			if($cnt>0) {
				$con->dbquery("UPDATE IGNORE lab_ecgresult SET impression = '".$con->escapeString(htmlentities($_POST['ecg_impression']))."', consultant = '".htmlentities($_POST['ecg_consultant'])."', result_date = '".$con->formatDate($_POST['ecg_date'])."', updated_by='$uid',updated_on = now() where so_no = '$_POST[ecg_sono]' and branch = '$bid' and serialno = '$_POST[ecg_serialno]';");
			} else {
				$con->dbquery("INSERT IGNORE INTO lab_ecgresult (branch,so_no,pid,result_date,patient_stat,consultant,serialno,`code`,`procedure`,impression,created_by,created_on) values ('$bid','$_POST[ecg_sono]','$_POST[ecg_pid]','".$con->formatDate($_POST['ecg_date'])."','$_POST[ecg_patientstat]','".htmlentities($_POST['ecg_consultant'])."','$_POST[ecg_serialno]','$_POST[ecg_code]','".$con->escapeString(htmlentities($_POST['ecg_procedure']))."','".$con->escapeString(htmlentities($_POST['ecg_impression']))."','$uid',now());");
			}
		break;

		case "validateECGResult":
			
			$con->validateResult("lab_ecgresult",$_POST['ecg_sono'],$_POST['ecg_code'],$_POST['ecg_serialno'],$bid,$uid);
			$con->updateLabSampleStatus($_POST['ecg_sono'],$_POST['ecg_code'],$_POST['ecg_serialno'],'4',$bid,$uid);	
			$con->dbquery("update ignore so_details set result_available = 'Y' where so_no = '$_POST[ecg_sono]' and branch = '$bid' and serialno = '$_POST[ecg_serialno]';");
		
		break;

		// Lipid Panel

		case "saveLipidPanel":
			list($cnt) = $con->getArray("select count(*) from lab_lipidpanel where so_no = '$_POST[lipid_sono]' and branch = '$bid' and serialno = '$_POST[lipid_serialno]';");
			if($cnt > 0) {
				$con->dbquery("update ignore lab_lipidpanel set cholesterol = '$_POST[lipid_cholesterol]', triglycerides = '$_POST[lipid_triglycerides]', hdl = '$_POST[lipid_hdl]', ldl = '$_POST[lipid_ldl]', vldl = '$_POST[lipid_vldl]', performed_by= '$_POST[lipid_result_by]', remarks = '".$con->escapeString(htmlentities($_POST['lipid_remarks']))."', updated_by = '$uid', updated_on = now() where so_no = '$_POST[lipid_sono]' and branch = '$bid' and code = '$_POST[lipid_code]' and serialno = '$_POST[lipid_serialno]';");
			} else {
				$con->dbquery("INSERT IGNORE INTO lab_lipidpanel (so_no,branch,pid,pname,result_date,sampletype,serialno,cholesterol,triglycerides,hdl,ldl,vldl,performed_by,remarks,created_by,created_on) VALUES ('$_POST[lipid_sono]','$bid','$_POST[lipid_pid]','$_POST[lipid_pname]','".$con->formatDate($_POST['lipid_date'])."','$_POST[lipid_spectype]','$_POST[lipid_serialno]','$_POST[lipid_cholesterol]','$_POST[lipid_triglycerides]','$_POST[lipid_hdl]','$_POST[lipid_ldl]','$_POST[lipid_vldl]','$_POST[lipid_result_by]','".$con->escapeString(htmlentities($_POST['lipid_remarks']))."','$uid',NOW());");
			}
			$con->updateLabSampleStatus($_POST['lipid_sono'],$_POST['lipid_code'],$_POST['lipid_serialno'],'3',$bid,$uid);
		break;

		case "validateLipidResult":
			$con->dbquery("update ignore lab_lipidpanel set cholesterol = '$_POST[lipid_cholesterol]', triglycerides = '$_POST[lipid_triglycerides]', hdl = '$_POST[lipid_hdl]', ldl = '$_POST[lipid_ldl]', vldl = '$_POST[lipid_vldl]', performed_by = '$_POST[lipid_result_by]', remarks = '".$con->escapeString(htmlentities($_POST['lipid_remarks']))."', updated_by = '$uid', updated_on = now() where so_no = '$_POST[lipid_sono]' and branch = '$bid' and code = '$_POST[lipid_code]' and serialno = '$_POST[lipid_serialno]';");
			$con->validateResult("lab_lipidpanel",$_POST['lipid_sono'],$_POST['lipid_code'],$_POST['lipid_serialno'],$bid,$uid);
			$con->updateLabSampleStatus($_POST['lipid_sono'],$_POST['lipid_code'],$_POST['lipid_serialno'],'4',$bid,$uid);	
			$con->dbquery("update so_details set result_available = 'Y' where so_no = '$_POST[lipid_sono]' and branch = '$bid' and code = '$_POST[lipid_code]' and sample_serialno = '$_POST[lipid_serialno]';");
		break;

		// save hiv result
		case "saveHivResult":
			list($cnt) = $con->getArray("select count(*) from lab_hiv where so_no = '$_POST[hiv_sono]' and branch = '$bid' and serialno = '$_POST[hiv_serialno]';");
			if($cnt > 0) {
				$con->dbquery("update ignore lab_hiv set hiv_one = '$_POST[hiv_one]', hiv_two = '$_POST[hiv_two]', hiv_half = '$_POST[hiv_half]', updated_by = '$uid', updated_on = now() where so_no = '$_POST[hiv_sono]' and branch = '$bid' and serialno = '$_POST[hiv_serialno]';");
			} else {
				$con->dbquery("INSERT IGNORE INTO lab_hiv (so_no,branch,result_date,sampletype,serialno,hiv_one,hiv_two,hiv_half,created_by,created_on) VALUES ('$_POST[hiv_sono]', '$bid', '".$con->formatDate($_POST['hiv_date'])."','$_POST[hiv_spectype]', '$_POST[hiv_serialno]', '$_POST[hiv_one]', '$_POST[hiv_two]', '$_POST[hiv_half]','$uid',NOW());");
			}
			$con->updateLabSampleStatus($_POST['hiv_sono'],$_POST['hiv_code'],$_POST['hiv_serialno'],'3',$bid,$uid);
		break;

		// validation hiv result
		case "validateHivResult":
			$con->dbquery("update ignore lab_hiv set hiv_one = '$_POST[hiv_one]', hiv_two = '$_POST[hiv_two]', hiv_half = '$_POST[hiv_half]', updated_by = '$uid', updated_on = now() where so_no = '$_POST[hiv_sono]' and branch = '$bid' and serialno = '$_POST[hiv_serialno]';");
			$con->validateResult("lab_hiv",$_POST['hiv_sono'],$_POST['hiv_code'],$_POST['hiv_serialno'],$bid,$uid);
			$con->updateLabSampleStatus($_POST['hiv_sono'],$_POST['hiv_code'],$_POST['hiv_serialno'],'4',$bid,$uid);
			$con->dbquery("update so_details set result_available = 'Y' where so_no = '$_POST[hiv_sono]' and branch = '$bid' and code = '$_POST[hiv_code]';");
		break;
		
		// dengue result
		case "saveDengueResult":
			list($cnt) = $con->getArray("select count(*) from lab_dengue where so_no = '$_POST[dengue_sono]' and branch = '$bid' and serialno = '$_POST[dengue_serialno]';");
			if($cnt > 0) {
				$con->dbquery("UPDATE IGNORE lab_dengue set dengue_ag = '$_POST[dengue_ag]', dengue_igg = '$_POST[dengue_igg]', dengue_igm = '$_POST[dengue_igm]', updated_by = '$uid', updated_on = now() where so_no = '$_POST[dengue_sono]' and branch = '$bid' and serialno = '$_POST[dengue_serialno]';");
			} else {
				$con->dbquery("INSERT IGNORE INTO lab_dengue (so_no,branch,result_date,sampletype,serialno,dengue_ag,dengue_igg,dengue_igm,created_by,created_on) VALUES ('$_POST[dengue_sono]', '$bid', '".$con->formatDate($_POST['dengue_date'])."', '$_POST[dengue_spectype]','$_POST[dengue_serialno]','$_POST[dengue_ag]', '$_POST[dengue_igg]', '$_POST[dengue_igm]','$uid',NOW());");
			}
			$con->updateLabSampleStatus($_POST['dengue_sono'],$_POST['dengue_code'],$_POST['dengue_serialno'],'3',$bid,$uid);
		break;
		// dengue validate
		case "validateDengueResult":
			$con->dbquery("UPDATE IGNORE lab_dengue set dengue_ag = '$_POST[dengue_ag]', dengue_igg = '$_POST[dengue_igg]', dengue_igm = '$_POST[dengue_igm]', updated_by = '$uid', updated_on = now() where so_no = '$_POST[dengue_sono]' and branch = '$bid' and serialno = '$_POST[dengue_serialno]';");
			$con->validateResult("lab_dengue",$_POST['dengue_sono'],$_POST['dengue_code'],$_POST['dengue_serialno'],$bid,$uid);
			$con->updateLabSampleStatus($_POST['dengue_sono'],$_POST['dengue_code'],$_POST['dengue_serialno'],'4',$bid,$uid);
			$con->dbquery("UPDATE so_details set result_available = 'Y' where so_no = '$_POST[dengue_sono]' and branch = '$bid' and code = '$_POST[dengue_code]';");
		break;

		// saving hav igg/igm result
		case "saveHavResult":
			list($cnt) = $con->getArray("select count(*) from lab_enumresult where so_no = '$_POST[hav_so]' and branch = '$bid' and code = '$_POST[hav_code]' and serialno = '$_POST[hav_serialno]';");
			if($cnt > 0) {
				$con->dbquery("UPDATE lab_enumresult set result = '$_POST[hav_result]', result_date = '".$con->formatDate($_POST['hav_date'])."', remarks = '".$con->escapeString($_POST['hav_remarks'])."' where so_no = '$_POST[hav_sono]' and branch = '$bid' and code = '$_POST[hav_code]' and serialno = '$_POST[hav_serialno]';");
			} else {
				$con->dbquery("INSERT INTO lab_enumresult (branch,so_no,result_date,sampletype,serialno,code,result,remarks,created_by,created_on) VALUES ('$bid','$_POST[hav_sono]','".$con->formatDate($_POST['hav_date'])."','$_POST[hav_spectype]', '$_POST[hav_serialno]', '$_POST[hav_code]', '$_POST[hav_result]', '".$con->escapeString($_POST['hav_remarks'])."', '$uid',now());");
			}
			$con->updateLabSampleStatus($_POST['hav_sono'],$_POST['hav_code'],$_POST['hav_serialno'],'3',$bid,$uid);
		break;

		// validating hav igg/igm result
		case "validateHavResult":
			$con->dbquery("UPDATE lab_enumresult set result = '$_POST[hav_result]', result_date = '".$con->formatDate($_POST['hav_date'])."', remarks = '".$con->escapeString($_POST['hav_remarks'])."' where so_no = '$_POST[hav_sono]' and branch = '$bid' and code = '$_POST[hav_code]' and serialno = '$_POST[hav_serialno]';");
			$con->validateResult("lab_enumresult",$_POST['hav_sono'],$_POST['hav_code'],$_POST['hav_serialno'], $bid,$uid);
			$con->updateLabSampleStatus($_POST['hav_sono'],$_POST['hav_code'],$_POST['hav_serialno'],'4',$bid,$uid);
			$con->dbquery("update so_details set result_available = 'Y' where so_no = '$_POST[hav_sono]' and branch = '$bid' and code = '$_POST[hav_code]' and sample_serialno = '$_POST[hav_serialno]';");
		break;
		
		case "saveLipidPanel":
			list($cnt) = $con->getArray("select count(*) from lab_lipidpanel where so_no = '$_POST[lipid_sono]' and branch = '$bid' and serialno = '$_POST[lipid_serialno]';");
			if($cnt > 0) {
				$con->dbquery("update ignore lab_lipidpanel set cholesterol = '$_POST[lipid_cholesterol]', triglycerides = '$_POST[lipid_triglycerides]', hdl = '$_POST[lipid_hdl]', ldl = '$_POST[lipid_ldl]', vldl = '$_POST[lipid_vldl]', performed_by= '$_POST[lipid_result_by]', remarks = '".$con->escapeString(htmlentities($_POST['lipid_remarks']))."', updated_by = '$uid', updated_on = now() where so_no = '$_POST[lipid_sono]' and branch = '$bid' and code = '$_POST[lipid_code]' and serialno = '$_POST[lipid_serialno]';");
			} else {
				$con->dbquery("INSERT IGNORE INTO lab_lipidpanel (so_no,branch,pid,pname,result_date,sampletype,serialno,cholesterol,triglycerides,hdl,ldl,vldl,performed_by,remarks,created_by,created_on) VALUES ('$_POST[lipid_sono]','$bid','$_POST[lipid_pid]','$_POST[lipid_pname]','".$con->formatDate($_POST['lipid_date'])."','$_POST[lipid_spectype]','$_POST[lipid_serialno]','$_POST[lipid_cholesterol]','$_POST[lipid_triglycerides]','$_POST[lipid_hdl]','$_POST[lipid_ldl]','$_POST[lipid_vldl]','$_POST[lipid_result_by]','".$con->escapeString(htmlentities($_POST['lipid_remarks']))."','$uid',NOW());");
			}
			$con->updateLabSampleStatus($_POST['lipid_sono'],$_POST['lipid_code'],$_POST['lipid_serialno'],'3',$bid,$uid);
		break;

		case "validateLipidResult":
			$con->dbquery("update ignore lab_lipidpanel set cholesterol = '$_POST[lipid_cholesterol]', triglycerides = '$_POST[lipid_triglycerides]', hdl = '$_POST[lipid_hdl]', ldl = '$_POST[lipid_ldl]', vldl = '$_POST[lipid_vldl]', performed_by = '$_POST[lipid_result_by]', remarks = '".$con->escapeString(htmlentities($_POST['lipid_remarks']))."', updated_by = '$uid', updated_on = now() where so_no = '$_POST[lipid_sono]' and branch = '$bid' and code = '$_POST[lipid_code]' and serialno = '$_POST[lipid_serialno]';");
			$con->validateResult("lab_lipidpanel",$_POST['lipid_sono'],$_POST['lipid_code'],$_POST['lipid_serialno'],$bid,$uid);
			$con->updateLabSampleStatus($_POST['lipid_sono'],$_POST['lipid_code'],$_POST['lipid_serialno'],'4',$bid,$uid);	
			$con->dbquery("update so_details set result_available = 'Y' where so_no = '$_POST[lipid_sono]' and branch = '$bid' and code = '$_POST[lipid_code]' and sample_serialno = '$_POST[lipid_serialno]';");
		break;

		case "saveOgttResult":
			list($cnt) = $con->getArray("select count(*) from lab_ogtt where so_no = '$_POST[ogtt_sono]' and branch = '$bid' and serialno = '$_POST[ogtt_serialno]';");
			if($cnt > 0) {
				$con->dbquery("update ignore lab_ogtt set fasting = '$_POST[ogtt_fasting]', fasting_uglucose = '$_POST[ogtt_uglucose]', first_hr = '$_POST[ogttFirstHr]', first_hr_uglucose = '$_POST[first_hr_uglucose]', second_hr = '$_POST[second_hr]', second_hr_uglucose = '$_POST[second_hr_uglucose]', updated_by = '$uid', updated_on = now() where so_no = '$_POST[ogtt_sono]' and branch = '$bid' and code = '$_POST[ogtt_code]' and serialno = '$_POST[ogtt_serialno]';");
			} else {
				$con->dbquery("INSERT IGNORE INTO lab_ogtt (so_no,branch,result_date,sampletype,serialno,fasting,fasting_uglucose,first_hr,first_hr_uglucose,second_hr,second_hr_uglucose,created_by,created_on) VALUES ('$_POST[ogtt_sono]', '$bid', '".$con->formatDate($_POST['ogtt_date'])."', '$_POST[ogtt_spectype]','$_POST[ogtt_serialno]','".$con->formatDigit($_POST['ogtt_fasting'])."', '$_POST[ogtt_uglucose]', '$_POST[ogttFirstHr]','$_POST[first_hr_uglucose]','".$con->formatDigit($_POST['second_hr'])."','$_POST[second_hr_uglucose]','$uid',NOW());");
			}
			$con->updateLabSampleStatus($_POST['ogtt_sono'],$_POST['ogtt_code'],$_POST['ogtt_serialno'],'3',$bid,$uid);
		break;

		case "validateOgttResult":
			$con->dbquery("update ignore lab_ogtt set fasting = '$_POST[ogtt_fasting]', fasting_uglucose = '$_POST[ogtt_uglucose]', first_hr = '$_POST[ogttFirstHr]', first_hr_uglucose = '$_POST[first_hr_uglucose]', second_hr = '$_POST[second_hr]', second_hr_uglucose = '$_POST[second_hr_uglucose]';");
			$con->validateResult("lab_ogtt",$_POST['ogtt_sono'],$_POST['ogtt_code'],$_POST['ogtt_serialno'],$bid,$uid);
			$con->updateLabSampleStatus($_POST['ogtt_sono'],$_POST['ogtt_code'],$_POST['ogtt_serialno'],'4',$bid,$uid);
			$con->dbquery("update so_details set result_available = 'Y' where so_no = '$_POST[ogtt_sono]' and branch = '$bid' and code = '$_POST[ogtt_code]' and sample_serialno = '$_POST[ogtt_serialno]';");
		break;

		case "saveBloodType":
			list($cnt) = $con->getArray("select count(*) from lab_bloodtyping where so_no = '$_POST[btype_sono]' and branch = '$bid' and code = '$_POST[btype_code]' and serialno = '$_POST[btype_serialno]';");
			if($cnt > 0) {
				$con->dbquery("UPDATE lab_bloodtyping set patient_stat = '$_POST[btype_patientstat]', result = '$_POST[btype_result]', rh = '$_POST[btype_rh]', performed_by = '$_POST[btype_result_by]', result_date = '".$con->formatDate($_POST['btype_date'])."', remarks = '".$con->escapeString($_POST['btype_remarks']) . "' where so_no = '$_POST[btype_sono]' and branch = '$bid' and code = '$_POST[btype_code]' and serialno = '$_POST[btype_serialno]';");
			} else {
				$con->dbquery("INSERT INTO lab_bloodtyping (branch,so_no,pid,pname,result_date,patient_stat,sampletype,serialno,code,result,rh,performed_by,remarks,created_by,created_on) VALUES ('$bid','$_POST[btype_sono]','$_POST[btype_pid]','$_POST[btype_pname]','".$con->formatDate($_POST['btype_date'])."','$_POST[btype_patientstat]','$_POST[btype_spectype]','$_POST[btype_serialno]','$_POST[btype_code]','$_POST[btype_result]','$_POST[btype_rh]','$_POST[btype_result_by]','".$con->escapeString($_POST['btype_remarks'])."','$uid',now());");
			}
			$con->updateLabSampleStatus($_POST['btype_sono'],$_POST['btype_code'],$_POST['btype_serialno'],'3',$bid,$uid);
		break;

		case "validateBloodType":
			$con->dbquery("UPDATE lab_bloodtyping set patient_stat = '$_POST[btype_patientstat]', result = '$_POST[btype_result]', rh = '$_POST[btype_rh]', performed_by = '$_POST[btype_result_by]', remarks = '".$con->escapeString($_POST['btype_remarks']) . "' where so_no = '$_POST[btype_sono]' and branch = '$bid' and code = '$_POST[btype_code]' and serialno = '$_POST[btype_serialno]';");
			$con->validateResult("lab_bloodtyping",$_POST['btype_sono'],$_POST['btype_code'],$_POST['btype_serialno'],$bid,$uid);
			$con->updateLabSampleStatus($_POST['btype_sono'],$_POST['btype_code'],$_POST['btype_serialno'],'4',$bid,$uid);	
			$con->dbquery("update so_details set result_available = 'Y' where so_no = '$_POST[enum_sono]' and branch = '$bid' and code = '$_POST[btype_code]' and sample_serialno = '$_POST[btype_serialno]';");
		break;

		case "savePregnancyResult":
			list($cnt) = $con->getArray("select count(*) from lab_enumresult where so_no = '$_POST[pt_sono]' and branch = '$bid' and code = '$_POST[pt_code]' and serialno = '$_POST[pt_serialno]';");
			if($cnt > 0) {
				$con->dbquery("UPDATE lab_enumresult set result = '$_POST[pt_result]', result_date = '".$con->formatDate($_POST['pt_date'])."', remarks = '".$con->escapeString($_POST['pt_remarks']) . "' where so_no = '$_POST[pt_sono]' and branch = '$bid' and code = '$_POST[pt_code]' and serialno = '$_POST[pt_serialno]';");
			} else {
				$con->dbquery("INSERT INTO lab_enumresult (branch,so_no,pid,result_date,sampletype,serialno,code,result,remarks,created_by,created_on) VALUES ('$bid','$_POST[pt_sono]','$_POST[pt_pid]','".$con->formatDate($_POST['pt_date'])."','$_POST[pt_spectype]','$_POST[pt_serialno]','$_POST[pt_code]','$_POST[pt_result]','".$con->escapeString($_POST['pt_remarks'])."','$uid',now());");
			}
			$con->updateLabSampleStatus($_POST['pt_sono'],$_POST['pt_code'],$_POST['pt_serialno'],'3',$bid,$uid);
		break;

		case "validatePregnancyResult":
			$con->dbquery("UPDATE lab_enumresult set result = '$_POST[pt_result]', result_date = '".$con->formatDate($_POST['pt_date'])."', remarks = '".$con->escapeString($_POST['pt_remarks']) . "' where so_no = '$_POST[pt_sono]' and branch = '$bid' and code = '$_POST[pt_code]' and serialno = '$_POST[pt_serialno]';");
			$con->validateResult("lab_enumresult",$_POST['pt_sono'],$_POST['pt_code'],$_POST['pt_serialno'],$bid,$uid);
			$con->updateLabSampleStatus($_POST['pt_sono'],$_POST['pt_code'],$_POST['pt_serialno'],'4',$bid,$uid);	
			$con->dbquery("update so_details set result_available = 'Y' where so_no = '$_POST[pt_sono]' and branch = '$bid' and code = '$_POST[pt_code]' and sample_serialno = '$_POST[pt_serialno]';");
		
		break;

		case "saveSingleValueResult":

			list($cnt) = $con->getArray("select count(*) from lab_singleresult where so_no = '$_POST[sresult_sono]' and branch = '$bid' and code = '$_POST[sresult_code]' and serialno = '$_POST[sresult_serialno]';");
			if($cnt>0) {
				$con->dbquery("UPDATE IGNORE lab_singleresult SET `attribute`='$_POST[sresult_attribute]',`value`='$_POST[sresult_value]',lower_value='$_POST[sresult_lowerlimit]',upper_value='$_POST[sresult_upperlimit]',performed_by='$_POST[sresult_result_by]',remarks='".$con->escapeString($_POST['sresult_remarks'])."', updated_by='$uid',updated_on = now() where so_no = '$_POST[sresult_sono]' and branch = '$bid' and code = '$_POST[sresult_code]' and serialno = '$_POST[sresult_serialno]';");
			} else {
				$con->dbquery("INSERT IGNORE INTO lab_singleresult (branch,so_no,pid,pname,result_date,sampletype,serialno,`code`,`procedure`,`attribute`,unit,`value`,lower_value,upper_value,performed_by,remarks,created_by,created_on) values ('$bid','$_POST[sresult_sono]','$_POST[sresult_pid]','$_POST[sresult_pname]','".$con->formatDate($_POST['sresult_date'])."','$_POST[sresult_spectype]','$_POST[sresult_serialno]','$_POST[sresult_code]','".$con->escapeString(htmlentities($_POST['sresult_procedure']))."','$_POST[sresult_attribute]','$_POST[sresult_unit]','$_POST[sresult_value]','$_POST[sresult_lowerlimit]','$_POST[sresult_upperlimit]','$_POST[sresult_result_by]','".$con->escapeString($_POST['sresult_remarks'])."','$uid',now());");
				$con->updateLabSampleStatus($_POST['sresult_sono'],$_POST['sresult_code'],$_POST['sresult_serialno'],'3',$bid,$uid);
			}
		break;

		case "validateSingleValueResult":
			$con->dbquery("UPDATE IGNORE lab_singleresult SET result_date = '".$con->formatDate($_POST['sresult_date'])."', `attribute`='$_POST[sresult_attribute]',`value`='$_POST[sresult_value]',performed_by = '$_POST[sresult_result_by]', remarks='".$con->escapeString($_POST['sresult_remarks'])."', updated_by='$uid',updated_on = now() where so_no = '$_POST[sresult_sono]' and branch = '$bid' and code = '$_POST[sresult_code]' and serialno = '$_POST[sresult_serialno]';");
			$con->validateResult("lab_singleresult",$_POST['sresult_sono'],$_POST['sresult_code'],$_POST['sresult_serialno'],$bid,$uid);
			$con->updateLabSampleStatus($_POST['sresult_sono'],$_POST['sresult_code'],$_POST['sresult_serialno'],'4',$bid,$uid);	
			$con->dbquery("update so_details set result_available = 'Y' where so_no = '$_POST[sresult_sono]' and branch = '$bid' and code = '$_POST[sresult_code]' and sample_serialno = '$_POST[sresult_serialno]';");	
		break;

		case "saveDescResult":
			list($cnt) = $con->getArray("select count(*) from lab_descriptive where serialno = '$_POST[desc_serialno]';");
			if($cnt>0) {
				$con->dbquery("UPDATE IGNORE lab_descriptive SET  pid = '$_POST[desc_pid]', impression = '".$con->escapeString(htmlentities($_POST['desc_impression']))."', physician = '".htmlentities($_POST['desc_physician'])."', consultant = '".htmlentities($_POST['desc_consultant'])."', result_stat = '$_POST[desc_resultstat]', result_date = '".$con->formatDate($_POST['desc_date'])."', updated_by='$uid',updated_on = now() where so_no = '$_POST[desc_sono]' and serialno = '$_POST[desc_serialno]';");
				$con->dbquery("UPDATE IGNORE lab_samples set lotno = '$_POST[desc_xray_no]' where so_no = '$_POST[desc_sono]' and serialno = '$_POST[desc_serialno]';");
			} else {

				/* Check Again if Serial Exists */
				list($cnt) = $con->getArray("select count(*) from lab_descriptive where serialno = '$_POST[desc_serialno]';");

				$con->dbquery("INSERT IGNORE INTO lab_descriptive (so_no,pid,result_date,sampletype,serialno,`code`,`procedure`,impression,physician,consultant,result_stat,created_by,created_on) values ('$_POST[desc_sono]','$_POST[desc_pid]','".$con->formatDate($_POST['desc_date'])."','$_POST[desc_spectype]','$_POST[desc_serialno]','$_POST[desc_code]','".$con->escapeString(htmlentities($_POST['desc_procedure']))."','".$con->escapeString(htmlentities($_POST['desc_impression']))."','".htmlentities($_POST['desc_physician'])."','".htmlentities($_POST['desc_consultant'])."','$_POST[desc_resultstat]','$uid',now());");
				$con->updateLabSampleStatus($_POST['desc_sono'],$_POST['desc_code'],$_POST['desc_serialno'],'3',$bid,$uid);	
			}
		break;

		case "validateDescResult":		
			$con->dbquery("update ignore lab_descriptive set verified = 'Y', verified_by = '$uid', verified_on = now() where so_no = '$_POST[desc_sono]' and code = '$_POST[desc_code]' and serialno = '$_POST[desc_serialno]';");
			$con->dbquery("update ignore lab_samples set `status` = '4' where serialno = '$_POST[desc_serialno]' and `code` = '$_POST[desc_code]';");	
		break;

		case "invalidateDescResult":
			$con->dbquery("update ignore lab_descriptive set verified = 'N', updated_by = '$uid', updated_on = now() where so_no = '$_POST[desc_sono]' and `code` = '$_POST[desc_code]' and serialno = '$_POST[desc_serialno]';");
			$con->dbquery("update ignore lab_samples set `status` = '3', updated_by = '$uid', updated_on = now() where so_no = '$_POST[desc_sono]' and `code` = '$_POST[desc_code]' and serialno = '$_POST[desc_serialno]';");
		break;

		case "rejectXrayRequest":
			$con->dbquery("update ignore lab_samples set `status` = '2', rejection_remarks = '".$con->escapeString($_POST['remarks'])."', updated_by = '$uid', updated_on = now() where serialno = '$_POST[sn]';");
		break;

		case "saveCBCResult":
			list($cnt) = $con->getArray("select count(*) from lab_cbcresult where so_no = '$_POST[cbc_sono]' and branch = '$bid' and serialno = '$_POST[cbc_serialno]';");
			if($cnt > 0) {
				$con->dbquery("update ignore lab_cbcresult set machine= '$_POST[cbc_machine]', result_date = '".$con->formatDate($_POST['cbc_date']) ."', wbc = '".$con->formatDigit($_POST['wbc'])."',rbc = '".$con->formatDigit($_POST['rbc'])."',hemoglobin = '".$con->formatDigit($_POST['hemoglobin'])."', hematocrit = '".$con->formatDigit($_POST['hematocrit'])."', neutrophils = '".$con->formatDigit($_POST['neutrophils'])."', lymphocytes = '".$con->formatDigit($_POST['lymphocytes'])."', monocytes = '".$con->formatDigit($_POST['monocytes'])."',eosinophils = '".$con->formatDigit($_POST['eosinophils'])."', basophils = '".$con->formatDigit($_POST['basophils'])."', platelate = '".$con->formatDigit($_POST['platelate'])."', mcv = '".$_POST['mcv']."', mch = '$_POST[mch]', mchc = '$_POST[mchc]', remarks = '".$con->escapeString(htmlentities($_POST['remarks']))."', result_stat = '$_POST[result_stat]', updated_by = '$uid', updated_on = now() where so_no = '$_POST[cbc_sono]' and branch = '$bid' and serialno = '$_POST[cbc_serialno]';");
			} else {
				$con->dbquery("insert ignore into lab_cbcresult (so_no,pid,branch,result_date,sampletype,serialno,machine,wbc,rbc,hemoglobin,hematocrit,neutrophils,lymphocytes,monocytes,eosinophils,basophils,platelate,mcv,mch,mchc,remarks,result_stat,created_by,created_on) values ('$_POST[cbc_sono]','$_POST[cbc_pid]','$bid','".$con->formatDate($_POST['cbc_sodate'])."','$_POST[cbc_spectype]','$_POST[cbc_serialno]','$_POST[cbc_machine]','".$con->formatDigit($_POST['wbc'])."','".$con->formatDigit($_POST['rbc'])."','".$con->formatDigit($_POST['hemoglobin'])."','".$con->formatDigit($_POST['hematocrit'])."','".$con->formatDigit($_POST['neutrophils'])."','".$con->formatDigit($_POST['lymphocytes'])."','".$con->formatDigit($_POST['monocytes'])."','".$con->formatDigit($_POST['eosinophils'])."','".$con->formatDigit($_POST['basophils'])."','".$con->formatDigit($_POST['platelate'])."','" . $_POST['mcv'] . "','" . $_POST['mch'] . "','" . $_POST['mchc'] . "','".$con->escapeString(htmlentities($_POST['remarks']))."','$_POST[result_stat]','$uid',now());");
			}

			$con->dbquery("update ignore lab_samples set `status` = '3' where serialno = '$_POST[cbc_serialno]' and `code` = '$_POST[cbc_code]';");	
	
		break;

		case "validateCBCResult":
			/* Update Status of Lab Sample */
			$con->dbquery("update ignore lab_cbcresult set machine= '$_POST[cbc_machine]', result_date = '".$con->formatDate($_POST['cbc_date']) ."', wbc = '".$con->formatDigit($_POST['wbc'])."',rbc = '".$con->formatDigit($_POST['rbc'])."',hemoglobin = '".$con->formatDigit($_POST['hemoglobin'])."', hematocrit = '".$con->formatDigit($_POST['hematocrit'])."', neutrophils = '".$con->formatDigit($_POST['neutrophils'])."', lymphocytes = '".$con->formatDigit($_POST['lymphocytes'])."', monocytes = '".$con->formatDigit($_POST['monocytes'])."',eosinophils = '".$con->formatDigit($_POST['eosinophils'])."', basophils = '".$con->formatDigit($_POST['basophils'])."', platelate = '".$con->formatDigit($_POST['platelate'])."', mcv = '".$_POST['mcv']."', mch = '$_POST[mch]', mchc = '$_POST[mchc]', remarks = '".$con->escapeString(htmlentities($_POST['remarks']))."', result_stat = '$_POST[result_stat]', verified = 'Y', verified_by = '$uid', verified_on = now() where so_no = '$_POST[cbc_sono]' and branch = '$bid' and serialno = '$_POST[cbc_serialno]';");
			$con->dbquery("update ignore lab_samples set `status` = '4' where serialno = '$_POST[cbc_serialno]' and `code` = '$_POST[cbc_code]';");	
		break;

		case "saveBloodChem":
			list($cnt) = $con->getArray("select count(*) from lab_bloodchem where so_no = '$_POST[bloodchem_sono]' and branch = '$bid' and serialno = '$_POST[bloodchem_serialno]';");
			if($cnt > 0) {
				$con->dbquery("UPDATE IGNORE lab_bloodchem SET result_date = '".$con->formatDate($_POST['bloodchem_date'])."',glucose='".$con->formatDigit($_POST['glucose'])."',uric = '".$con->formatDigit($_POST['uric'])."',bun = '".$con->formatDigit($_POST['bun'])."',creatinine = '".$con->formatDigit($_POST['creatinine'])."', cholesterol = '".$con->formatDigit($_POST['cholesterol'])."',triglycerides = '".$con->formatDigit($_POST['triglycerides'])."',hdl = '".$con->formatDigit($_POST['hdl'])."',ldl = '".$con->formatDigit($_POST['ldl'])."',vldl = '".$con->formatDigit($_POST['vldl'])."',sgot = '".$con->formatDigit($_POST['sgot'])."',sgpt = '".$con->formatDigit($_POST['sgpt'])."',alkaline = '".$con->formatDigit($_POST['alkaline'])."',bilirubin = '".$con->formatDigit($_POST['bilirubin'])."',bilirubin_direct = '".$con->formatDigit($_POST['bilirubin_direct'])."',bilirubin_indirect = '".$con->formatDigit($_POST['bilirubin_indirect'])."',protein = '".$con->formatDigit($_POST['protein'])."',albumin = '".$con->formatDigit($_POST['albumin'])."',globulin = '".$con->formatDigit($_POST['globulin'])."',agratio = '".$con->formatDigit($_POST['agratio'])."',electrolytes_na = '".$con->formatDigit($_POST['electrolytes_na'])."',electrolytes_k = '".$con->formatDigit($_POST['electrolytes_k'])."',electrolytes_ci = '".$con->formatDigit($_POST['electrolytes_ci'])."',calcium = '".$con->formatDigit($_POST['calcium'])."',phosphorus = '".$con->formatDigit($_POST['phosphorus'])."',ggt = '".$con->formatDigit($_POST['ggt'])."',remarks = '".$con->escapeString(htmlentities($_POST['remarks']))."',updated_by = '$uid',updated_on = NOW() where so_no = '$_POST[bloodchem_sono]' and branch = '$uid' and serialno = '$_POST[bloodchem_serialno]';");
			} else {
				$con->dbquery("INSERT INTO lab_bloodchem (so_no,branch,result_date,sampletype,serialno,glucose,uric,bun,creatinine,cholesterol,triglycerides,hdl,ldl,vldl,sgot,sgpt,alkaline,bilirubin,bilirubin_direct,bilirubin_indirect,protein,albumin,globulin,agratio,electrolytes_na,electrolytes_k,electrolytes_ci,calcium,phosphorus,ggt,remarks,created_by,created_on) VALUES ('$_POST[bloodchem_sono]','$bid','".$con->formatDate($_POST['bloodchem_date'])."','$_POST[bloodchem_spectype]','$_POST[bloodchem_serialno]','".$con->formatDigit($_POST['glucose'])."','".$con->formatDigit($_POST['uric'])."','".$con->formatDigit($_POST['bun'])."','".$con->formatDigit($_POST['creatinine'])."','".$con->formatDigit($_POST['cholesterol'])."','".$con->formatDigit($_POST['triglycerides'])."','".$con->formatDigit($_POST['hdl'])."','".$con->formatDigit($_POST['ldl'])."','".$con->formatDigit($_POST['vldl'])."','".$con->formatDigit($_POST['sgot'])."','".$con->formatDigit($_POST['sgpt'])."','".$con->formatDigit($_POST['alkaline'])."','".$con->formatDigit($_POST['bilirubin'])."','".$con->formatDigit($_POST['bilirubin_direct'])."','".$con->formatDigit($_POST['bilirubin_indirect'])."','".$con->formatDigit($_POST['protein'])."','".$con->formatDigit($_POST['albumin'])."','".$con->formatDigit($_POST['globulin'])."','".$con->formatDigit($_POST['agratio'])."','".$con->formatDigit($_POST['electrolytes_na'])."','".$con->formatDigit($_POST['electrolytes_k'])."','".$con->formatDigit($_POST['electrolytes_ci'])."','".$con->formatDigit($_POST['calcium'])."','".$con->formatDigit($_POST['phosphorus'])."','".$con->formatDigit($_POST['ggt'])."','".$con->escapeString(htmlentities($_POST['remarks']))."','$uid',NOW());");
			}

			/* Update Status of Lab Sample */
			$con->updateLabSampleStatus($_POST['bloodchem_sono'],$_POST['bloodchem_code'],$_POST['bloodchem_serialno'],'3',$bid,$uid);	
		break;

		case "validateBloodChem":
			$con->dbquery("UPDATE IGNORE lab_bloodchem SET result_date = '".$con->formatDate($_POST['bloodchem_date'])."',glucose='".$con->formatDigit($_POST['glucose'])."',uric = '".$con->formatDigit($_POST['uric'])."',bun = '".$con->formatDigit($_POST['bun'])."',creatinine = '".$con->formatDigit($_POST['creatinine'])."', cholesterol = '".$con->formatDigit($_POST['cholesterol'])."',triglycerides = '".$con->formatDigit($_POST['triglycerides'])."',hdl = '".$con->formatDigit($_POST['hdl'])."',ldl = '".$con->formatDigit($_POST['ldl'])."',vldl = '".$con->formatDigit($_POST['vldl'])."',sgot = '".$con->formatDigit($_POST['sgot'])."',sgpt = '".$con->formatDigit($_POST['sgpt'])."',alkaline = '".$con->formatDigit($_POST['alkaline'])."',bilirubin = '".$con->formatDigit($_POST['bilirubin'])."',bilirubin_direct = '".$con->formatDigit($_POST['bilirubin_direct'])."',bilirubin_indirect = '".$con->formatDigit($_POST['bilirubin_indirect'])."',protein = '".$con->formatDigit($_POST['protein'])."',albumin = '".$con->formatDigit($_POST['albumin'])."',globulin = '".$con->formatDigit($_POST['globulin'])."',agratio = '".$con->formatDigit($_POST['agratio'])."',electrolytes_na = '".$con->formatDigit($_POST['electrolytes_na'])."',electrolytes_k = '".$con->formatDigit($_POST['electrolytes_k'])."',electrolytes_ci = '".$con->formatDigit($_POST['electrolytes_ci'])."',calcium = '".$con->formatDigit($_POST['calcium'])."',phosphorus = '".$con->formatDigit($_POST['phosphorus'])."',ggt = '".$con->formatDigit($_POST['ggt'])."',remarks = '".$con->escapeString(htmlentities($_POST['remarks']))."',updated_by = '$uid',updated_on = NOW() where so_no = '$_POST[bloodchem_sono]' and branch = '$uid' and serialno = '$_POST[bloodchem_serialno]';");
			$con->validateResult("lab_bloodchem",$_POST['bloodchem_sono'],$_POST['bloodchem_code'],$_POST['bloodchem_serialno'],$bid,$uid);
			$con->updateLabSampleStatus($_POST['bloodchem_sono'],$_POST['bloodchem_code'],$_POST['bloodchem_serialno'],'4',$bid,$uid);	
		break;

		case "saveUAReport":
			// list($cnt) = $con->getArray("select count(*) from lab_uaresult where so_no = '$_POST[ua_sono]' and serialno = '$_POST[ua_serialno]';");
			// if($cnt > 0) {
			// 	$con->dbquery("UPDATE IGNORE lab_uaresult SET pid = '$_POST[ua_pid]', result_date = '".$con->formatDate($_POST['ua_date'])."',color = '$_POST[color]',appearance = '$_POST[appearance]',ph = '$_POST[ph]',gravity = '$_POST[gravity]',blood = '$_POST[blood]',bilirubin = '$_POST[bilirubin]',urobilinogen = '$_POST[urobilinogen]',ketone = '$_POST[ketone]',protein = '$_POST[protein]',nitrite = '$_POST[nitrite]',glucose = '$_POST[glucose]',leukocyte = '$_POST[luekocyte]',rbc_hpf = '$_POST[rbc_hpf]',wbc_hpf ='$_POST[wbc_hpf]',yeast = '$_POST[yeast]',mucus_thread = '$_POST[mucus_thread]',bacteria = '$_POST[bacteria]',squamous = '$_POST[squamous]',bladder = '$_POST[bladder]',renal = '$_POST[renal]',hyaline = '$_POST[hyaline]',coarse_granular = '$_POST[coarse_granular]',fine_granular = '$_POST[fine_granular]', casts_wbc = '$_POST[casts_wbc]',casts_rbc = '$_POST[casts_rbc]',amorphous_urates = '$_POST[amorphous_urates]',calcium_oxalate = '$_POST[calcium_oxalate]',uric_acid = '$_POST[uric_acid]',amorphous_po4 = '$_POST[amorphous_po4]',triple_phosphates = '$_POST[triple_phosphates]',remarks = '".$con->escapeString(htmlentities($_POST['remarks']))."', result_stat = '$_POST[result_stat]', updated_by = '$uid',updated_on = NOW() WHERE serialno = '$_POST[ua_serialno]';");
			// } else {
			// 	$con->dbquery("INSERT IGNORE INTO lab_uaresult (so_no,pid,result_date,sampletype,serialno,color,appearance,ph,gravity,blood,bilirubin,urobilinogen,ketone,protein,nitrite,glucose,leukocyte,rbc_hpf,wbc_hpf,yeast,mucus_thread,bacteria,squamous,bladder,renal,hyaline,coarse_granular,fine_granular,casts_wbc,casts_rbc,amorphous_urates,calcium_oxalate,uric_acid,amorphous_po4,triple_phosphates,remarks,result_stat,created_by,created_on) VALUES ('$_POST[ua_sono]','$_POST[ua_pid]','".$con->formatDate($_POST['ua_date'])."','$_POST[ua_spectype]','$_POST[ua_serialno]','$_POST[color]','$_POST[appearance]','$_POST[ph]','$_POST[gravity]','$_POST[blood]','$_POST[bilirubin]','$_POST[urobilinogen]','$_POST[ketone]','$_POST[protein]','$_POST[nitrite]','$_POST[glucose]','$_POST[leukocyte]','$_POST[rbc_hpf]','$_POST[wbc_hpf]','$_POST[yeast]','$_POST[mucus_thread]','$_POST[bacteria]','$_POST[squamous]','$_POST[bladder]','$_POST[renal]','$_POST[hyaline]','$_POST[coarse_granular]','$_POST[fine_granular]','$_POST[casts_wbc]','$_POST[casts_rbc]','$_POST[amorphous_urates]','$_POST[calcium_oxalate]','$_POST[uric_acid]','$_POST[amorphous_po4]','$_POST[triple_phosphates]','".$con->escapeString(htmlentities($_POST['remarks']))."','$_POST[result_stat]','$uid',NOW());");
			// }

			list($cnt) = $con->getArray("select count(*) from lab_uaresult where so_no = '$_POST[ua_sono]' and serialno = '$_POST[ua_serialno]';");
			if($cnt > 0) {
				$con->dbquery("UPDATE lab_uaresult SET pid = '$_POST[ua_pid]', result_date = '".$con->formatDate($_POST['ua_date'])."', color = '$_POST[color]', appearance = '$_POST[appearance]', leukocytes = '$_POST[leukocytes]', nitrite = '$_POST[nitrite]', urobilinogen = '$_POST[urobilinogen]', blood = '$_POST[blood]', ketone = '$_POST[ketone]', bilirubin = '$_POST[bilirubin]', ph = '$_POST[ph]',gravity = '$_POST[gravity]', glucose = '$_POST[glucose]', protein = '$_POST[protein]', rbc_hpf = '$_POST[rbc_hpf]', wbc_hpf = '$_POST[wbc_hpf]', squamous = '$_POST[squamous]', casts = '$_POST[casts]',mucus_thread = '$_POST[mucus_thread]',bacteria = '$_POST[bacteria]',crystals = '$_POST[crystals]',amorphous_urates = '$_POST[amorphous_urates]', amorphous_po4 = '$_POST[amorphous_po4]',remarks = '".$con->escapeString(htmlentities($_POST['remarks']))."',others = '".$con->escapeString(htmlentities($_POST['others']))."',result_stat = '$_POST[result_stat]', updated_by = '$uid',updated_on = now() where serialno = '$_POST[ua_serialno]';");
			} else {
				$con->dbquery("INSERT INTO lab_uaresult (so_no,pid,result_date,sampletype,serialno,color,appearance,leukocytes,nitrite,urobilinogen,blood,ketone,bilirubin,ph,gravity,glucose,protein,rbc_hpf,wbc_hpf,squamous,casts,mucus_thread,bacteria,crystals,amorphous_urates,amorphous_po4,remarks,others,result_stat,created_by,created_on) values ('$_POST[ua_sono]','$_POST[ua_pid]','".$con->formatDate($_POST['ua_date'])."','$_POST[ua_spectype]','$_POST[ua_serialno]','$_POST[color]','$_POST[appearance]','$_POST[leukocytes]','$_POST[nitrite]','$_POST[urobilinogen]','$_POST[blood]','$_POST[ketone]','$_POST[bilirubin]','$_POST[ph]','$_POST[gravity]','$_POST[glucose]','$_POST[protein]','$_POST[rbc_hpf]','$_POST[wbc_hpf]','$_POST[squamous]','$_POST[casts]','$_POST[mucus_thread]','$_POST[bacteria]','$_POST[crystals]','$_POST[amorphous_urates]','$_POST[amorphous_po4]','".$con->escapeString(htmlentities($_POST['remarks']))."','".$con->escapeString(htmlentities($_POST['others']))."','$_POST[result_stat]','$uid',now());");
				echo "INSERT INTO lab_uaresult (so_no,pid,result_date,sampletype,serialno,color,appearance,leukocytes,nitrite,urobilinogen,blood,ketone,bilirubin,ph,gravity,glucose,protein,rbc_hpf,wbc_hpf,squamous,casts,mucus_thread,bacteria,crystals,amorphous_urates,amorphous_po4,remarks,others,result_stat,created_by,created_on) values ('$_POST[ua_sono]','$_POST[ua_pid]','".$con->formatDate($_POST['ua_date'])."','$_POST[ua_spectype]','$_POST[ua_serialno]','$_POST[color]','$_POST[appearance]','$_POST[leukocytes]','$_POST[nitrite]','$_POST[urobilinogen]','$_POST[blood]','$_POST[ketone]','$_POST[bilirubin]','$_POST[ph]','$_POST[gravity]','$_POST[glucose]','$_POST[protein]','$_POST[rbc_hpf]','$_POST[wbc_hpf]','$_POST[squamous]','$_POST[casts]','$_POST[mucus_thread]','$_POST[bacteria]','$_POST[crystals]','$_POST[amorphous_urates]','$_POST[amorphous_po4]','".$con->escapeString(htmlentities($_POST['remarks']))."','".$con->escapeString(htmlentities($_POST['others']))."','$_POST[result_stat]','$uid',now());";
			}

			$con->dbquery("update ignore lab_samples set `status` = '3' where serialno = '$_POST[ua_serialno]' and `code` = 'L012' and pid = '$_POST[ua_pid]';");	
		break;

		case "validateUAReport":
			//$con->dbquery("UPDATE IGNORE lab_uaresult SET result_date = '".$con->formatDate($_POST['ua_date'])."',color = '$_POST[color]',appearance = '$_POST[appearance]',ph = '$_POST[ph]',gravity = '$_POST[gravity]',blood = '$_POST[blood]',bilirubin = '$_POST[bilirubin]',urobilinogen = '$_POST[urobilinogen]',ketone = '$_POST[ketone]',protein = '$_POST[protein]',nitrite = '$_POST[nitrite]',glucose = '$_POST[glucose]',leukocyte = '$_POST[luekocyte]',rbc_hpf = '$_POST[rbc_hpf]',wbc_hpf ='$_POST[wbc_hpf]',yeast = '$_POST[yeast]',mucus_thread = '$_POST[mucus_thread]',bacteria = '$_POST[bacteria]',squamous = '$_POST[squamous]',bladder = '$_POST[bladder]',renal = '$_POST[renal]',hyaline = '$_POST[hyaline]',coarse_granular = '$_POST[coarse_granular]',casts_wbc = '$_POST[casts_wbc]',casts_rbc = '$_POST[casts_rbc]',amorphous_urates = '$_POST[amorphous_urates]',calcium_oxalate = '$_POST[calcium_oxalate]',uric_acid = '$_POST[uric_acid]',amorphous_po4 = '$_POST[amorphous_po4]',triple_phosphates = '$_POST[triple_phosphates]',remarks = '".$con->escapeString(htmlentities($_POST['remarks']))."', verified = 'Y', verified_by = '$uid', verified_on = now() WHERE serialno = '$_POST[ua_serialno]';");
			$con->dbquery("UPDATE lab_uaresult SET result_date = '".$con->formatDate($_POST['ua_date'])."', color = '$_POST[color]', appearance = '$_POST[appearance]', leukocytes = '$_POST[leukocytes]', nitrite = '$_POST[nitrite]', urobilinogen = '$_POST[urobilinogen]', blood = '$_POST[blood]', ketone = '$_POST[ketone]', bilirubin = '$_POST[bilirubin]', ph = '$_POST[ph]',gravity = '$_POST[gravity]', glucose = '$_POST[glucose]', protein = '$_POST[protein]', rbc_hpf = '$_POST[rbc_hpf]', wbc_hpf = '$_POST[wbc_hpf]', squamous = '$_POST[squamous]', casts = '$_POST[casts]',mucus_thread = '$_POST[mucus_thread]',bacteria = '$_POST[bacteria]',crystals = '$_POST[crystals]',amorphous_urates = '$_POST[amorphous_urates]', amorphous_po4 = '$_POST[amorphous_po4]',remarks = '".$con->escapeString(htmlentities($_POST['remarks']))."',others = '".$con->escapeString(htmlentities($_POST['others']))."',verified = 'Y', verified_by = '$uid', verified_on = now() WHERE serialno = '$_POST[ua_serialno]';");

			$con->dbquery("update ignore lab_samples set `status` = '4' where serialno = '$_POST[ua_serialno]' and `code` = '$_POST[ua_code]';");	
		break;

		case "saveStoolExam":
			
			list($cnt) = $con->getArray("select count(*) from lab_stoolexam where so_no = '$_POST[stool_sono]' and branch = '$bid' and serialno = '$_POST[stool_serialno]';");
			if($cnt > 0) {
				$con->dbquery("UPDATE lab_stoolexam SET pid = '$_POST[stool_pid]', pname = '".htmlentities($_POST['stool_pname'])."', result_date = '".$con->formatDate($_POST['stool_date'])."', color = '$_POST[color]', consistency = '$_POST[consistency]', blood = '$_POST[blood]', mucus = '$_POST[mucus]',parasites = '$_POST[parasites]', rbc = '$_POST[rbc_hpf]', wbc = '$_POST[wbc_hpf]', ova_parasites = '$_POST[ova_parasites]',bacteria = '$_POST[bacteria]', globules = '$_POST[globules]', yeast_cells = '$_POST[yeast_cells]', occult_blood = '$_POST[occult_blood]', remarks = '".$con->escapeString(htmlentities($_POST['remarks']))."',updated_by = '$uid', updated_on = now() where so_no = '$_POST[stool_sono]' and branch  = '$bid' and serialno = '$_POST[stool_serialno]';");
			} else {
				$con->dbquery("INSERT INTO lab_stoolexam (so_no,branch,pid,pname,result_date,sampletype,serialno,color,consistency,blood,mucus,parasites,rbc,wbc,ova_parasites,bacteria,globules,yeast_cells,occult_blood,remarks,created_by,created_on) VALUES ('$_POST[stool_sono]','$bid','$_POST[stool_pid]','" . htmlentities($_POST['stool_pname']) . "','".$con->formatDate($_POST['stool_date'])."','$_POST[stool_spectype]','$_POST[stool_serialno]','$_POST[color]','$_POST[consistency]','$_POST[blood]','$_POST[mucus]','$_POST[parasites]','$_POST[rbc_hpf]','$_POST[wbc_hpf]','$_POST[ova_parasites]','$_POST[bacteria]','$_POST[globules]','$_POST[yeast_cells]','$_POST[occult_blood]','".$con->escapeString(htmlentities($_POST['remarks']))."','$uid',now());");
			}

			/* Update Status of Lab Sample */
			$con->dbquery("UPDATE IGNORE lab_samples set `status` = '3' where serialno = '$_POST[stool_serialno]' and `code` = '$_POST[stool_code]';");	
		break;


		case "validateStoolExam":
			$con->dbquery("UPDATE lab_stoolexam SET pid = '$_POST[stool_pid]', pname = '".htmlentities($_POST['stool_pname'])."', result_date = '".$con->formatDate($_POST['stool_date'])."', color = '$_POST[color]', consistency = '$_POST[consistency]', blood = '$_POST[blood]', mucus = '$_POST[mucus]',parasites = '$_POST[parasites]', rbc = '$_POST[rbc_hpf]', wbc = '$_POST[wbc_hpf]', ova_parasites = '$_POST[ova_parasites]',bacteria = '$_POST[bacteria]', globules = '$_POST[globules]', yeast_cells = '$_POST[yeast_cells]', occult_blood = '$_POST[occult_blood]', remarks = '".$con->escapeString(htmlentities($_POST['remarks']))."', verified = 'Y', verified_by = '$uid', verified_on = now() where so_no = '$_POST[stool_sono]' and branch  = '$bid' and serialno = '$_POST[stool_serialno]';");
			$con->dbquery("UPDATE IGNORE lab_samples set `status` = '4' where serialno = '$_POST[stool_serialno]' and `code` = '$_POST[stool_code]';");	
		break;

		case "releaseResult":
			$con->dbquery("update lab_samples set released = 'Y', released_by = '$uid', release_date = '" . $con->formatDate($_POST['date']) . "', release_mode = '$_POST[mode]', release_remarks = '" . $con->escapeString($_POST['remarks']) . "', released_to = '" . $con->escapeString(htmlentities($_POST['remarks'])) . "' where record_id = '$_POST[id]';");
		break;

		case "saveVitals":
			
			$pmh = '';
			foreach($_POST['pe_medhistory'] as $mval) {
				$mh .= $mval . ",";
			}

			list($uid) = $con->getArray("select id from options_doctors where uid = '$uid';");

			switch($_SESSION['type']) {
				/* USER IS EXAMINER */
				case "2":
					$updateString = ", examined_by = '$uid', examined_on = now() ";
				break;
				default:
					$updateString = ", updated_by = '$uid', updated_on = now() ";	
				break;

			}

			
			if($mh!='') { $pmh = substr($mh,0,-1); }
			
			$sqlQuery = "UPDATE IGNORE peme SET pe_type = '$_POST[pe_type]', contactno = '$_POST[pe_contact]', temp = '$_POST[pe_temp]', pulse = '$_POST[pe_pr]', rr = '$_POST[pe_rr]', bp = '$_POST[pe_bp]', ht = '$_POST[pe_ht]', wt = '$_POST[pe_wt]', lefteye = '$_POST[pe_lefteye]', righteye = '$_POST[pe_righteye]', jaegerleft = '$_POST[j_lefteye]', jaegerright = '$_POST[j_righteye]', bmi = '$_POST[pe_bmi]', with_glasses = '$_POST[pe_glasses]', bmi_category = '$_POST[pe_bmitype]', pm_history = '$pmh', pm_others = '" . htmlentities($_POST['pm_others']) . "', fm_history = '" . htmlentities($_POST['pe_famhistory']) . "', pv_hospitalization = '" . htmlentities($_POST['pe_hospitalization']) . "', current_med = '$_POST[pe_current_med]', mens_history = '$_POST[pe_menshistory]', parity = '$_POST[pe_parity]', lmp = '$_POST[pe_lmp]', contraceptives = '$_POST[pe_contra]', smoker = '$_POST[pe_smoker]', pregnant = '$_POST[pe_pregnant]', alcoholic = '$_POST[pe_alcoholic]', drugs = '$_POST[pe_drugs]', hs_normal = '$_POST[pe_hs_normal]', hs_findings = '$_POST[pe_hs_findings]', ee_normal = '$_POST[pe_ee_normal]', ee_findings = '$_POST[pe_ee_findings]', sa_normal = '$_POST[pe_sa_normal]', sa_findings = '$_POST[pe_sa_findings]', nose_normal = '$_POST[pe_nose_normal]', nose_findings = '$_POST[pe_nose_findings]', lungs_normal = '$_POST[pe_lungs_normal]', lungs_findings = '$_POST[pe_lungs_findings]', heart_normal = '$_POST[pe_heart_normal]', heart_findings = '$_POST[pe_heart_findings]', abdomen_normal = '$_POST[pe_abdomen_normal]', abdomen_findings = '$_POST[pe_abdomen_findings]', genitals_normal = '$_POST[pe_genitals_normal]', genitals_findings = '$_POST[pe_genitals_findings]', mouth_normal = '$_POST[pe_mouth_normal]', mouth_findings = '$_POST[pe_mouth_findings]', extr_normal = '$_POST[pe_extr_normal]', extr_findings = '$_POST[pe_extr_findings]', neck_normal = '$_POST[pe_neck_normal]', neck_findings = '$_POST[pe_neck_findings]', ref_normal = '$_POST[pe_ref_normal]', ref_findings = '$_POST[pe_ref_findings]', check_normal = '$_POST[pe_check_normal]', check_findings = '$_POST[pe_check_findings]', bpe_normal = '$_POST[pe_bpe_normal]', bpe_findings = '$_POST[pe_bpe_findings]', rect_normal = '$_POST[pe_rect_normal]', rect_findings = '$_POST[pe_rect_findings]', chest_normal = '$_POST[pe_chest_normal]', chest_findings = '$_POST[pe_chest_findings]', cbc_normal = '$_POST[pe_cbc_normal]', cbc_findings = '$_POST[pe_cbc_findings]', ua_normal = '$_POST[pe_ua_findings_normal]', ua_findings = '$_POST[pe_ua_findings]', se_normal = '$_POST[pe_se_normal]', se_findings = '$_POST[pe_se_findings]', dt_normal = '$_POST[pe_dt_normal]', dt_findings = '$_POST[pe_dt_findings]', ecg_normal = '$_POST[pe_ecg_normal]', ecg_findings = '$_POST[pe_ecg_findings]',pap_normal = '$_POST[pe_papsmear_normal]', pap_findings = '$_POST[pe_pap_findings]', others1_name = '$_POST[pe_others1]', others1_normal = '$_POST[pe_others1_normal]', others1_findings = '$_POST[pe_others1_findings]', others2_name = '$_POST[pe_others2]', others2_normal = '$_POST[pe_others2_normal]', others2_findings = '$_POST[pe_others2_findings]', pe_fit = '$_POST[pe_fit]', classification = '$_POST[pe_class]', class_b = '$_POST[pe_class_b]', class_b_remarks1 = '$_POST[pe_class_b_remarks1]', class_b_remarks2 = '$_POST[pe_class_b_remarks2]', class_c = '$_POST[pe_class_c]', class_c_remarks1 = '$_POST[pe_class_c_remarks1]', class_c_remarks2 = '$_POST[pe_class_c_remarks2]', pending_remarks = '$_POST[pe_eval_remarks]', overall_remarks  = '$_POST[pe_remarks]',pre_examined_by = '$_POST[pe_pre_examined_by]',pre_examined_on = now() $updateString WHERE so_no = '$_POST[pe_sono]' AND pid = '$_POST[pe_pid]';";
			$con->dbquery($sqlQuery);
			$con->dbquery("update ignore peme set status = 'Finalized' where examined_by != '' and so_no = '$_POST[pe_sono]' AND pid = '$_POST[pe_pid]';");
		
		break;

		case "saveSignature":

			list($prevPath) = $con->getArray("select signature_path from peme where so_no = '$_POST[so_no]' and pid = '$_POST[pid]';");
			if($prevPath != '') {
				unlink($prevPath);
			}

			$ranString = $con->generateRandomString(32);
			$ranString .= $_POST['so_no'] . $_POST['pid'];

			$path = "../images/signatures/peme/";
			$image_parts=explode(";base64,",$_POST['jsonSignature']);
			$image_type_aux=explode("image/",$image_parts[0]);
			$image_type=$image_type_aux[1];
			$image_base64=base64_decode($image_parts[1]);
			$file .= $path . $ranString . '.png';
			file_put_contents($file,$image_base64);

			$con->dbquery("update ignore peme set signature_path = '$file' where so_no = '$_POST[so_no]' and pid = '$_POST[pid]';");
			//echo "update ignore peme set signature_path = '$file' where so_no = '$_POST[so_no]' and pid = '$_POST[pid]';";

		break;

		case "savePhoto":

			list($prevPath) = $con->getArray("select photo_path from peme where so_no = '$_POST[so_no]' and pid = '$_POST[pid]';");
			if($prevPath != '') {
				unlink($prevPath);
			}

			$ranString = $con->generateRandomString(32);
			$ranString .= $_POST['so_no'] . $_POST['pid'];

			$path = "../images/photos/peme/";
			$image_parts=explode(";base64,",$_POST['jsonSignature']);
			$image_type_aux=explode("image/",$image_parts[0]);
			$image_type=$image_type_aux[1];
			$image_base64=base64_decode($image_parts[1]);
			$file .= $path . $ranString . '.png';
			file_put_contents($file,$image_base64);

			$con->dbquery("update ignore peme set photo_path = '$file' where so_no = '$_POST[so_no]' and pid = '$_POST[pid]';");
			//echo "update ignore peme set signature_path = '$file' where so_no = '$_POST[so_no]' and pid = '$_POST[pid]';";

		break;

		case "checkPEResult":
			$res = $con->getArray("select record_id as lid,serialno from lab_samples where pid = '$_POST[pid]' and `code` = '$_POST[code]' and so_no = '$_POST[so_no]' and extracted = 'Y';");
			echo json_encode($res);
		break;

		case "rejectResult":
			$con->dbquery("update lab_samples set status = '1', updated_by = '$uid', updated_on = now() where record_id = '$_POST[lid]';");
		break;

		case "invalidateECGResult":
			$con->dbquery("update ignore lab_ecgresult set verified = 'N', verified_by = '', verified_on = '' where so_no = '$_POST[ecg_sono]' and branch = '$bid' and code = '$_POST[ecg_code]' and serialno = '$_POST[ecg_serialno]';");
			$con->dbquery("update ignore lab_samples set `status` = '1', updated_by = '$uid', updated_on = now() where so_no = '$_POST[ecg_sono]' and branch = '$bid' and code = '$_POST[ecg_code]' and serialno = '$_POST[ecg_serialno]';");
		break;



		/* USERS DATA */
		case "getUinfo":
			list($uname) = $con->getArray("select fullname from user_info where emp_id = '$_POST[uid]';");
			echo $uname;
		break;
		case "checkUname":
			list($count) = $con->getArray("select count(*) from user_info where username = '$_POST[uname]';"); echo $count;
		break;
		case "checkUnameUID":
			list($count) = $con->getArray("select count(*) from user_info where username = '$_POST[uname]' and emp_id!='$_POST[uid]';"); echo $count;
		break;

		case "getUserDetails":
			$u1 = $con->getArray("select *,if(signature_file!='',concat('<img src=\"images/signatures/',signature_file,'\" align=absmiddle />'),'<img src=\"images/signatures/blank.png\" align=absmiddle />') as signaturefile from user_info where emp_id = '$_POST[uid]';");
			$u2 = array("xfullname"=>html_entity_decode($u1['fullname']));
			$u3 = array_merge($u1,$u2);
			echo json_encode($u3);
		break;

		case "updateUser":
			$uploadDir = "../images/signatures/";

			$fileName = $_FILES['signatureFile']['name'];
			$tmpName = $_FILES['signatureFile']['tmp_name'];
			
			
			if($fileName!='') {

				/* CHANGE UNIQUE FILENAME TO PREVENT DUPLICATION */
				$ext = substr(strrchr($fileName, "."), 1);
				$randName = md5(rand() * time());
				$newFileName = $randName . "." . $ext;
				$filePath = $uploadDir . $newFileName;
				$result = move_uploaded_file($tmpName, $filePath);
			
				$signatureFile = ",signature_file = '$newFileName' ";

			}

			$con->dbquery("UPDATE IGNORE user_info SET username = '$_POST[uname]', fullname = '".htmlentities($_POST['fname'])."', user_type = '$_POST[utype]', r_type = '$_POST[rtype]', role = '".$con->escapeString($_POST['urole'])."', license_no = '$_POST[license_no]', email = '$_POST[uemail]' $signatureFile WHERE emp_id = '$_POST[uid]';");
			
		break;

		case "newUser":
			$uploadDir = "../images/signatures/";

			$fileName = $_FILES['new_signatureFile']['name'];
			$tmpName = $_FILES['new_signatureFile']['tmp_name'];
			
			
			if($fileName!='') {

				/* CHANGE UNIQUE FILENAME TO PREVENT DUPLICATION */
				$ext = substr(strrchr($fileName, "."), 1);
				$randName = md5(rand() * time());
				$newFileName = $randName . "." . $ext;
				$filePath = $uploadDir . $newFileName;
				$result = move_uploaded_file($tmpName, $filePath);
			}

			$con->dbquery("INSERT IGNORE INTO user_info (username,`password`,fullname,user_type,r_type,email,`role`,license_no,signature_file) value ('$_POST[new_uname]',md5('$_POST[new_pass1]'),'".$con->escapeString(htmlentities($_POST['new_fname']))."','$_POST[new_utype]','$_POST[new_rtype]','$_POST[new_uemail]','".$con->escapeString($_POST['new_urole'])."','$_POST[new_license_no]','$newFileName');");

		break;

		case "deleteUser":
			$h = $con->getArray("select username, fullname from user_info where emp_id = '$_POST[uid]';");
			$con->trailer("USER MANAGEMENT","USER INFO DELETED, User ID: $_POST[uid], Username: $h[username], Full Name: $h[fullname]");
			$con->dbquery("delete from user_info where emp_id = '$_POST[uid]';");
			$con->dbquery("delete from user_rights where UID = '$_POST[uid]';");
		break;
		case "checkOldPass":
			list($count) = $con->getArray("select count(*) from user_info where emp_id='$_POST[uid]' and password=md5('$_POST[old_pass]');");	
			if($count>0) { echo "Ok"; } else { echo "noOk"; }
		break;
		case "changePassword":
			$con->dbquery("update ignore user_info set password=md5('$_POST[pass]'), require_change_pass='N' where emp_id='$_POST[uid]';");
			$con->trailer("USER MANAGEMENT","PASSWORD FOR UID $_POST[uid] was updated");
		break;
		case "resetPassword":
			$con->dbquery("update ignore user_info set password=md5('123456'), require_change_pass='Y' where emp_id='$_POST[uid]';");
			//$con->trailer("USER MANAGEMENT","PASSWORD FOR UID $_POST[uid] was reset");
		break;
		case "insertRights":
			list($module,$id) = explode("|",$_REQUEST['val']);
			if($_REQUEST['push'] == "N") { 
				$xfind = $con->getArray("select count(*) from user_rights where UID='$_POST[uid]' and MENU_MODULE='$module' and MENU_ID='$id';");
				if($xfind[0] > 0) { 
					$con->dbquery("delete from user_rights where UID='$_POST[uid]' and MENU_MODULE='$module' and MENU_ID='$id';"); 
					//$con->trailer("USER MANAGEMENT","RIGHTS REMOVED FOR UID $_POST[uid] -> SUBMENU ID # $id");
				}
			} else {
				$xfind = $con->getArray("select count(*) from user_rights where UID='$_POST[uid]' and MENU_MODULE='$module' and MENU_ID='$id';");
				if($xfind[0] == 0) { 
					$con->dbquery("insert ignore into user_rights (UID,MENU_MODULE,MENU_ID) values ('$_REQUEST[uid]','$module','$id');"); 
					//$con->trailer("USER MANAGEMENT","RIGHTS ADDED TO UID $_POST[uid] -> SUBMENU ID # $id");
				}
			}
		break;
		case "tagCompany":
			$con->dbquery("update user_info set `$_POST[val]` = '$_POST[push]' where emp_id = '$_POST[uid]';");
			echo "update user_info set `$_POST[val]` = '$_POST[push]' where emp_id = '$_POST[uid]';";
		break;

		case "checkSPass":
			if($_POST['pass'] == 'e10adc3949ba59abbe56e057f20f883e') { echo "ok"; }
		break;	

		case "getPackageList":
			$option = "<option value=''>All</option>";

			$listQuery = $con->dbquery("select distinct `code`,`description` from cso_details where cso_no = '$_POST[so_no]';");
			while($listRow = $listQuery->fetch_array()) {
				$option .= "<option value='$listRow[0]'>$listRow[1]</option>";
			}

			echo $option;
		break;

		case "checkSession":
			//$uid = $_SESSION['m_userid'];
			if($uid == 0 || $uid == '') {
				echo "NotOk";
			}
		break;


	}
?>