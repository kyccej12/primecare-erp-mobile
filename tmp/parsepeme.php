<?php
    include("../handlers/_generics.php");
    $con = new _init();

    /* Send Request to Nursing Station for PEME
    $gQuery = $con->dbquery("SELECT a.cso_no, a.cso_date, b.pid, b.code AS parentcode, b.code, b.description AS `procedure`, c.birthplace, c.occupation AS occu, c. employer AS compname, c.mobile_no AS contactno FROM cso_header a LEFT JOIN cso_details b ON a.trace_no = b.trace_no LEFT JOIN pccmain.patient_info c ON b.pid = c.patient_id WHERE a.cso_no = '2' AND b.code IN ('O009') UNION ALL SELECT a.cso_no, a.cso_date, b.pid, b.code AS parentcode, e.code, e.description AS `procedure`,  c.birthplace, c.occupation AS occu, c. employer AS compname, c.mobile_no AS contactno FROM cso_header a LEFT JOIN cso_details b ON a.trace_no = b.trace_no LEFT JOIN pccmain.patient_info c ON b.pid = c.patient_id LEFT JOIN pccmain.services_master d ON b.code = d.code LEFT JOIN pccmain.services_subtests e ON e.parent = d.code  WHERE a.cso_no = '2' AND e.code IN ('O009') AND  d.with_subtests = 'Y';");
    while($hRow = $gQuery->fetch_array()) {
        list($pemeCount) = $con->getArray("select count(*) from peme where so_no = '$hRow[cso_no]' and code = '$hRow[code]' and pid = '$hRow[pid]';");
        if($pemeCount == 0) {
            $con->dbquery("INSERT IGNORE INTO peme (so_no,branch,so_date,parentcode,code,`procedure`,pid,pob,occu,compname,contactno) values ('$hRow[cso_no]','1','$hRow[cso_date]','$hRow[parentcode]','$hRow[code]','$hRow[procedure]','$hRow[pid]','" . $con->escapeString(htmlentities($hRow['birthplace'])) . "','$hRow[occu]','" . $con->escapeString(htmlentities($hRow['compname'])) . "','$hRow[contactno]');");
        }
    }  */

?>