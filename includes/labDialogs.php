<div id="sampleDetails" style="display: none; padding: 20px;">
    <form name="frmSample" id="frmSample">
        <input type="hidden" name = "phleb_parentcode" id = "phleb_parentcode">
        <table width=100% cellspacing=0 cellpadding=0><tr><td width=100% class=gridHead align=center>PATIENT & ORDER INFORMATION</td></tr></table>
        <table width=100% cellpadding=0 cellspacing=0 style="border: 1px solid #cdcdcd; padding: 10px; margin-bottom: 5px;">
            <tr><td height=3></td></tr>
            <tr>
                <td align="left" width="35%"  class="bareBold" style="padding-right: 15px;">Service Order No.&nbsp;:</td>
                <td align=left>
                    <input class="gridInput" style="width:100%;" type=text name="phleb_sono" id="phleb_sono">
                </td>				
            </tr>
            <tr><td height=3></td></tr>
            <tr>
                <td align="left" width="35%"  class="bareBold" style="padding-right: 15px;">Service Order Date&nbsp;:</td>
                <td align=left>
                    <input class="gridInput" style="width:100%;" type=text name="phleb_sodate" id="phleb_sodate">
                </td>				
            </tr>
            <tr><td height=3></td></tr>
            <tr>
                <td align="left" width="35%" class="bareBold" style="padding-right: 15px;">Patient ID&nbsp;:</td>
                <td align=left>
                    <input type="text" class="gridInput" style="width:100%;" name="phleb_pid" id="phleb_pid">
                </td>				
            </tr>
            <tr><td height=3></td></tr>
            <tr>
                <td align="left" width="35%" class="bareBold" style="padding-right: 15px;">Patient Name&nbsp;:</td>
                <td align=left>
                    <input type="text" class="gridInput" style="width:100%;" name="phleb_pname" id="phleb_pname">
                </td>				
            </tr>
            <tr><td height=3></td></tr>
            <tr>
                <td align="left" width="35%" class="bareBold" style="padding-right: 15px;">Patient Address&nbsp;:</td>
                <td align=left>
                    <input type="text" class="gridInput" style="width:100%;" name="phleb_paddr" id="phleb_paddr">
                </td>				
            </tr>
            <tr><td height=3></td></tr>
            <tr>
                <td align="left" width="35%" class="bareBold" style="padding-right: 15px;">Contact Nos.&nbsp;:</td>
                <td align=left>
                    <input type="text" class="gridInput" style="width:100%;" name="phleb_contactno" id="phleb_contactno">
                </td>				
            </tr>
            <tr><td height=3></td></tr>
            <tr>
                <td align="left" width="35%" class="bareBold" style="padding-right: 15px;">Email Address&nbsp;:</td>
                <td align=left>
                    <input type="text" class="gridInput" style="width:100%;" name="phleb_email" id="phleb_email">
                </td>				
            </tr>
            <tr><td height=3></td></tr>
            <tr>
                <td align="left" width="35%" class="bareBold" style="padding-right: 15px;">Gender&nbsp;:</td>
                <td align=left>
                    <input type="text" class="gridInput" style="width:100%;" name="phleb_gender" id="phleb_gender">
                </td>				
            </tr>
            <tr><td height=3></td></tr>
            <tr>
                <td align="left" width="35%" class="bareBold" style="padding-right: 15px;">Birthdate&nbsp;:</td>
                <td align=left>
                    <input type="text" class="gridInput" style="width:100%;" name="phleb_birthdate" id="phleb_birthdate">
                </td>				
            </tr>
            <tr><td height=3></td></tr>
            <tr>
                <td align="left" width="35%" class="bareBold" style="padding-right: 15px;">Age&nbsp;:</td>
                <td align=left>
                    <input type="text" class="gridInput" style="width:100%;" name="phleb_age" id="phleb_age">
                </td>				
            </tr>
            <tr><td height=3></td></tr>
            <tr>
                <td align="left" width="35%" class="bareBold" style="padding-right: 15px;">Patient Status&nbsp;:</td>
                <td align=left>
                    <input type="text" class="gridInput" style="width:100%;" name="phleb_patientstat" id="phleb_patientstat" readonly>
                </td>				
            </tr>
            <tr><td height=3></td></tr>
            <tr>
                <td align="left" width="35%" class="bareBold" style="padding-right: 15px;">Requesting Physician&nbsp;:</td>
                <td align=left>
                    <input type="text" class="gridInput" style="width:100%;" name="phleb_physician" id="phleb_physician">
                </td>				
            </tr>
            <tr><td height=3></td></tr>
        </table>
        <table width=100% cellspacing=0 cellpadding=0><tr><td width=100% class=gridHead align=center>SAMPLE DETAILS</td></tr></table>
        <table width=100% cellpadding=0 cellspacing=0 style="border: 1px solid #cdcdcd; padding: 10px;">
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
                <td align="left" width="35%" class="bareBold" style="padding-right: 15px;">Test Kit Info (If Applicable)&nbsp;:</td>
                <td align=left>
                    <input type="text" class="gridInput" style="width:100%;" name="phleb_testkit" id="phleb_testkit">
                </td>				
            </tr>
            <tr><td height=3></td></tr>
            <tr>
                <td align="left" width="35%" class="bareBold" style="padding-right: 15px;">Lot No. (If Applicable)&nbsp;:</td>
                <td align=left>
                    <input type="text" class="gridInput" style="width:100%;" name="phleb_testkit_lotno" id="phleb_testkit_lotno">
                </td>				
            </tr>
            <tr><td height=3></td></tr>
            <tr>
                <td align="left" width="35%" class="bareBold" style="padding-right: 15px;">Expiry (If Applicable)&nbsp;:</td>
                <td align=left>
                    <input type="text" class="gridInput" style="width:100%;" name="phleb_testkit_expiry" id="phleb_testkit_expiry">
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
                <td align="left" width="35%" class="bareBold" style="padding-right: 15px;" valign=top>Memo or Remarks&nbsp;:</td>
                <td align=left>
                    <textarea name="phleb_remarks" id="phleb_remarks" style="width:100%;" rows=3></textarea>
                </td>				
            </tr>
        </table>
    </form>
</div>

<div id="singleValueResult" style="display: none;">
    <form name="frmsingleValue" id="frmsingleValue">
        <table width=100% cellpadding=0 cellspacing=0 valign=top>
            <tr>
                <td width=44% valign=top>           
                    <table width=100% cellspacing=0 cellpadding=0><tr><td width=100% class=gridHead align=center>PATIENT & ORDER INFORMATION</td></tr></table>
                    <table width=100% cellpadding=0 cellspacing=0 style="border: 1px solid #cdcdcd; padding: 10px; margin-bottom: 5px;">
                        <tr><td height=3></td></tr>
                        <tr>
                            <td align="left" width="35%"  class="bareBold" style="padding-right: 15px;">Service Order No.&nbsp;:</td>
                            <td align=left>
                                <input class="gridInput" style="width:100%;" type=text name="sresult_sono" id="sresult_sono" readonly>
                            </td>				
                        </tr>
                        <tr><td height=3></td></tr>
                        <tr>
                            <td align="left" width="35%"  class="bareBold" style="padding-right: 15px;">Service Order Date&nbsp;:</td>
                            <td align=left>
                                <input class="gridInput" style="width:100%;" type=text name="sresult_sodate" id="sresult_sodate" readonly>
                            </td>				
                        </tr>
                        <tr><td height=3></td></tr>
                        <tr>
                            <td align="left" width="35%"  class="bareBold" style="padding-right: 15px;">Result Date&nbsp;:</td>
                            <td align=left>
                                <input class="gridInput" style="width:100%;" type=text name="sresult_date" id="sresult_date" value="<?php echo date('m/d/Y'); ?>">
                            </td>				
                        </tr>
                        <tr><td height=3></td></tr>
                        <tr>
                            <td align="left" width="35%" class="bareBold" style="padding-right: 15px;">Patient ID&nbsp;:</td>
                            <td align=left>
                                <input type="text" class="gridInput" style="width:100%;" name="sresult_pid" id="sresult_pid" readonly>
                            </td>				
                        </tr>
                        <tr><td height=3></td></tr>
                        <tr>
                            <td align="left" width="35%" class="bareBold" style="padding-right: 15px;">Patient Name&nbsp;:</td>
                            <td align=left>
                                <input type="text" class="gridInput" style="width:100%;" name="sresult_pname" id="sresult_pname" readonly>
                            </td>				
                        </tr>
                        <tr><td height=3></td></tr>

                        <tr>
                            <td align="left" width="35%" class="bareBold" style="padding-right: 15px;">Gender&nbsp;:</td>
                            <td align=left>
                                <input type="text" class="gridInput" style="width:100%;" name="sresult_gender" id="sresult_gender" readonly>
                            </td>				
                        </tr>
                        <tr><td height=3></td></tr>
                        <tr>
                            <td align="left" width="35%" class="bareBold" style="padding-right: 15px;">Birthdate&nbsp;:</td>
                            <td align=left>
                                <input type="text" class="gridInput" style="width:100%;" name="sresult_birthdate" id="sresult_birthdate" readonly>
                            </td>				
                        </tr>
                        <tr><td height=3></td></tr>
                        <tr>
                            <td align="left" width="35%" class="bareBold" style="padding-right: 15px;">Age&nbsp;:</td>
                            <td align=left>
                                <input type="text" class="gridInput" style="width:100%;" name="sresult_age" id="sresult_age" readonly>
                            </td>				
                        </tr>
                        <tr><td height=3></td></tr>
                        <tr>
                            <td align="left" width="35%" class="bareBold" style="padding-right: 15px;">Patient Status&nbsp;:</td>
                            <td align=left>
                                <input type="text" class="gridInput" style="width:100%;" name="sresult_patientstat" id="sresult_patientstat" readonly>
                            </td>				
                        </tr>
                        <tr><td height=3></td></tr>
                        <tr>
                            <td align="left" width="35%" class="bareBold" style="padding-right: 15px;">Requesting Physician&nbsp;:</td>
                            <td align=left>
                                <input type="text" class="gridInput" style="width:100%;" name="sresult_physician" id="sresult_physician">
                            </td>				
                        </tr>
                        <tr><td height=3></td></tr>
                    </table>
                    <table width=100% cellspacing=0 cellpadding=0><tr><td width=100% class=gridHead align=center>SAMPLE DETAILS</td></tr></table>
                    <table width=100% cellpadding=0 cellspacing=0 style="border: 1px solid #cdcdcd; padding: 10px;">
                        <tr>
                            <td align="left" width="35%" class="bareBold" style="padding-right: 15px;">Test or Procedure&nbsp;:</td>
                            <td align=left>
                                <input type="text" class="gridInput" style="width:100%;" name="sresult_procedure" id="sresult_procedure" readonly>
                            </td>				
                        </tr>
                        <tr><td height=3></td></tr>
                        <tr>
                            <td align="left" width="35%" class="bareBold" style="padding-right: 15px;">Procedure Code&nbsp;:</td>
                            <td align=left>
                                <input type="text" class="gridInput" style="width:100%;" name="sresult_code" id="sresult_code" readonly>
                            </td>				
                        </tr>
                        <tr><td height=3></td></tr>
                        <tr>
                            <td align="left" width="35%" class="bareBold" style="padding-right: 15px;">Specimen Type&nbsp;:</td>
                            <td align=left>
                                <select class="gridInput" style="width:100%; font-size: 11px;" name="sresult_spectype" id="sresult_spectype">
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
                                <input type="text" class="gridInput" style="width:100%;" name="sresult_serialno" id="sresult_serialno" readonly>
                            </td>				
                        </tr>
                        <tr><td height=3></td></tr>
                        <tr>
                            <td align="left" width="35%" class="bareBold" style="padding-right: 15px;">Date Extracted&nbsp;:</td>
                            <td align=left>
                                <input type="text" class="gridInput" style="width:100%;" name="sresult_extractdate" id="sresult_extractdate" readonly>
                            </td>				
                        </tr>
                        <tr><td height=3></td></tr>
                        <tr>
                            <td align="left" width="35%" class="bareBold" style="padding-right: 15px;">Time Extracted&nbsp;:</td>
                            <td align=left>
                            <input type="text" class="gridInput" style="width:100%;" name="sresult_extracttime" id="sresult_extracttime" readonly>
                            </td>				
                        </tr>
                        <tr><td height=3></td></tr>
                        <tr>
                            <td align="left" width="35%" class="bareBold" style="padding-right: 15px;">Test Kit Type (If Applicable)&nbsp;:</td>
                            <td align=left>
                                <input type="text" class="gridInput" style="width:100%;" name="sresult_testkit" id="sresult_testkit" readonly>
                            </td>				
                        </tr>
                        <tr><td height=3></td></tr>
                        <tr>
                            <td align="left" width="35%" class="bareBold" style="padding-right: 15px;">Lot No. (If Applicable)&nbsp;:</td>
                            <td align=left>
                                <input type="text" class="gridInput" style="width:100%;" name="sresult_testkit_lotno" id="sresult_testkit_lotno" readonly>
                            </td>				
                        </tr>
                        <tr><td height=3></td></tr>
                        <tr>
                            <td align="left" width="35%" class="bareBold" style="padding-right: 15px;">Expiry (If Applicable&nbsp;:</td>
                            <td align=left>
                                <input type="text" class="gridInput" style="width:100%;" name="sresult_testkit_expiry" id="sresult_testkit_expiry" readonly>
                            </td>				
                        </tr>
                        <tr><td height=3></td></tr>
                        <tr>
                            <td align="left" width="35%" class="bareBold" style="padding-right: 15px;">Extracted By&nbsp;:</td>
                            <td align=left>
                                <input type="text" class="gridInput" style="width:100%;" name="sresult_by" id="sresult_by" readonly>
                            </td>				
                        </tr>
                        <tr><td height=3></td></tr>
                        <tr>
                            <td align="left" width="35%" class="bareBold" style="padding-right: 15px;">Phleb/Imaging Site&nbsp;:</td>
                            <td align=left>
                                <select class="gridInput" style="width:100%; font-size: 11px;" name="sresult_location" id="sresult_location">
                                    <?php
                                        $iun = $o->dbquery("select id,location from lab_locations;");
                                        while(list($aa,$ab) = $iun->fetch_array()) {
                                            echo "<option value='$aa'>$ab</option>";
                                        }
                                    ?>
                                </select>
                            </td>				
                        </tr>
                    </table>
                </td>
                <td width=1%>&nbsp;</td>
                <td width=64% valign=top>                  
                    <table width=100% cellspacing=0 cellpadding=0><tr><td width=100% class=gridHead align=center>RESULT DETAILS</td></tr></table>
                    <table width=100% cellpadding=0 cellspacing=0 style="border: 1px solid #cdcdcd; padding: 10px;" class="td_content">
                        <tr><td height=3></td></tr>
                        <tr>
                            <td align="left" width="35%" class="bareBold" style="padding-right: 15px;">Result Attribute&nbsp;:</td>
                            <td align=left>
                                <input type="text" class="gridInput" style="width:100%;" name="sresult_attribute" id="sresult_attribute" readonly>
                            </td>				
                        </tr>
                        <tr><td height=3></td></tr>               
                        <tr>
                            <td align="left" width="35%" class="bareBold" style="padding-right: 15px;">Unit of Measure (UoM)&nbsp;:</td>
                            <td align=left>
                                <input type="text" class="gridInput" style="width:100%;" name="sresult_unit" id="sresult_unit">
                            </td>				
                        </tr>
                        <tr><td height=3></td></tr>
                        <tr>
                            <td align="left" width="35%" class="bareBold" style="padding-right: 15px;">Result Value&nbsp;:</td>
                            <td align=left>
                                <input type="text" class="gridInput" style="width:100%;" name="sresult_value" id="sresult_value">
                            </td>				
                        </tr>
                        <tr><td height=3></td></tr>
                        <tr>
                            <td align="left" width="35%" class="bareBold" style="padding-right: 15px;">Test Performed By&nbsp;:</td>
                            <td align=left>
                                <select name="sresult_result_by" id="sresult_result_by" class="gridInput" style="width:100%; font-size: 11px;">
                                    <option value="">- Not Applicable -</option>
                                    <?php
                                        $pbyQuery = $o->dbquery("select emp_id, fullname from user_info where role like '%MEDICAL TECH%';");
                                        while($pbyRow = $pbyQuery->fetch_array()) {
                                            echo "<option value = '$pbyRow[0]'>$pbyRow[1]</option>";
                                        }
                                    ?>
                                </select>
                            </td>				
                        </tr>
                        <tr><td height=3></td></tr>
                        <tr>
                            <td align="left" width="35%" class="bareBold" style="padding-right: 15px;" valign=top>Other Notable Remarks&nbsp;:</td>
                            <td align=left>
                                <textarea name="sresult_remarks" id="sresult_remarks" style="width:100%;" rows=3></textarea>
                            </td>				
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </form>
</div>

<div id="enumResult" style="display: none;">
    <form name="frmEnumResult" id="frmEnumResult">
        <table width=100% cellpadding=0 cellspacing=0 valign=top>
            <tr>
                <td width=44% valign=top>  
                    <table width=100% cellspacing=0 cellpadding=0><tr><td width=100% class=gridHead align=center>PATIENT & ORDER INFORMATION</td></tr></table>
                    <table width=100% cellpadding=0 cellspacing=0 style="border: 1px solid #cdcdcd; padding: 10px; margin-bottom: 5px;">
                        <tr><td height=3></td></tr>
                        <tr>
                            <td align="left" width="35%"  class="bareBold" style="padding-right: 15px;">Service Order No.&nbsp;:</td>
                            <td align=left>
                                <input class="gridInput" style="width:100%;" type=text name="enum_sono" id="enum_sono" readonly>
                            </td>				
                        </tr>
                        <tr><td height=3></td></tr>
                        <tr>
                            <td align="left" width="35%"  class="bareBold" style="padding-right: 15px;">Service Order Date&nbsp;:</td>
                            <td align=left>
                                <input class="gridInput" style="width:100%;" type=text name="enum_sodate" id="enum_sodate" readonly>
                            </td>				
                        </tr>
                        <tr><td height=3></td></tr>
                        <tr>
                            <td align="left" width="35%" class="bareBold" style="padding-right: 15px;">Patient ID&nbsp;:</td>
                            <td align=left>
                                <input type="text" class="gridInput" style="width:100%;" name="enum_pid" id="enum_pid" readonly>
                            </td>				
                        </tr>
                        <tr><td height=3></td></tr>
                        <tr>
                            <td align="left" width="35%" class="bareBold" style="padding-right: 15px;">Patient Name&nbsp;:</td>
                            <td align=left>
                                <input type="text" class="gridInput" style="width:100%;" name="enum_pname" id="enum_pname" readonly>
                            </td>				
                        </tr>
                        <tr><td height=3></td></tr>

                        <tr>
                            <td align="left" width="35%" class="bareBold" style="padding-right: 15px;">Gender&nbsp;:</td>
                            <td align=left>
                                <input type="text" class="gridInput" style="width:100%;" name="enum_gender" id="enum_gender" readonly>
                            </td>				
                        </tr>
                        <tr><td height=3></td></tr>
                        <tr>
                            <td align="left" width="35%" class="bareBold" style="padding-right: 15px;">Birthdate&nbsp;:</td>
                            <td align=left>
                                <input type="text" class="gridInput" style="width:100%;" name="enum_birthdate" id="enum_birthdate" readonly>
                            </td>				
                        </tr>
                        <tr><td height=3></td></tr>
                        <tr>
                            <td align="left" width="35%" class="bareBold" style="padding-right: 15px;">Age&nbsp;:</td>
                            <td align=left>
                                <input type="text" class="gridInput" style="width:100%;" name="enum_age" id="enum_age" readonly>
                            </td>				
                        </tr>
                        <tr><td height=3></td></tr>
                        <tr>
                            <td align="left" width="35%" class="bareBold" style="padding-right: 15px;">Patient Status&nbsp;:</td>
                            <td align=left>
                                <input type="text" class="gridInput" style="width:100%;" name="enum_patientstat" id="enum_patientstat" readonly>
                            </td>				
                        </tr>
                        <tr><td height=3></td></tr>
                        <tr>
                            <td align="left" width="35%" class="bareBold" style="padding-right: 15px;">Requesting Physician&nbsp;:</td>
                            <td align=left>
                                <input type="text" class="gridInput" style="width:100%;" name="enum_physician" id="enum_physician" readonly>
                            </td>				
                        </tr>
                        <tr><td height=3></td></tr>
                    </table>
                    <table width=100% cellspacing=0 cellpadding=0><tr><td width=100% class=gridHead align=center>SAMPLE DETAILS</td></tr></table>
                    <table width=100% cellpadding=0 cellspacing=0 style="border: 1px solid #cdcdcd; padding: 10px;">
                        <tr>
                            <td align="left" width="35%" class="bareBold" style="padding-right: 15px;">Test or Procedure&nbsp;:</td>
                            <td align=left>
                                <input type="text" class="gridInput" style="width:100%;" name="enum_procedure" id="enum_procedure" readonly>
                            </td>				
                        </tr>
                        <tr><td height=3></td></tr>
                        <tr>
                            <td align="left" width="35%" class="bareBold" style="padding-right: 15px;">Procedure Code&nbsp;:</td>
                            <td align=left>
                                <input type="text" class="gridInput" style="width:100%;" name="enum_code" id="enum_code" readonly>
                            </td>				
                        </tr>
                        <tr><td height=3></td></tr>
                        <tr>
                            <td align="left" width="35%" class="bareBold" style="padding-right: 15px;">Specimen Type&nbsp;:</td>
                            <td align=left>
                                <select class="gridInput" style="width:100%;" name="enum_spectype" id="enum_spectype">
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
                                <input type="text" class="gridInput" style="width:100%;" name="enum_serialno" id="enum_serialno" readonly>
                            </td>				
                        </tr>
                        <tr><td height=3></td></tr>
                        <tr>
                            <td align="left" width="35%" class="bareBold" style="padding-right: 15px;">Date Extracted&nbsp;:</td>
                            <td align=left>
                                <input type="text" class="gridInput" style="width:100%;" name="enum_extractdate" id="enum_extractdate" readonly>
                            </td>				
                        </tr>
                        <tr><td height=3></td></tr>
                        <tr>
                            <td align="left" width="35%" class="bareBold" style="padding-right: 15px;">Time Extracted&nbsp;:</td>
                            <td align=left>
                    
                                <input type="text" class="gridInput" style="width:100%;" name="enum_extracttime" id="enum_extracttime" readonly>

                            </td>				
                        </tr>
                        <tr><td height=3></td></tr>
                        <tr>
                            <td align="left" width="35%" class="bareBold" style="padding-right: 15px;">Method&nbsp;:</td>
                            <td align=left>
                                <input type="text" class="gridInput" style="width:100%;" name="enum_method" id="enum_method">
                            </td>				
                        </tr>
                        <tr><td height=3></td></tr>
                        <tr>
                            <td align="left" width="35%" class="bareBold" style="padding-right: 15px;">Test Kit Type (If Applicable)&nbsp;:</td>
                            <td align=left>
                                <input type="text" class="gridInput" style="width:100%;" name="enum_testkit" id="enum_testkit">
                            </td>				
                        </tr>
                        <tr><td height=3></td></tr>
                        <tr>
                            <td align="left" width="35%" class="bareBold" style="padding-right: 15px;">Lot No. (If Applicable)&nbsp;:</td>
                            <td align=left>
                                <input type="text" class="gridInput" style="width:100%;" name="enum_testkit_lotno" id="enum_testkit_lotno">
                            </td>				
                        </tr>
                        <tr><td height=3></td></tr>
                        <tr>
                            <td align="left" width="35%" class="bareBold" style="padding-right: 15px;">Expiry (If Applicable&nbsp;:</td>
                            <td align=left>
                                <input type="date" class="gridInput" style="width:100%;" name="enum_testkit_expiry" id="enum_testkit_expiry">
                            </td>				
                        </tr>
                        <tr><td height=3></td></tr>
                        <tr>
                            <td align="left" width="35%" class="bareBold" style="padding-right: 15px;">Extracted By&nbsp;:</td>
                            <td align=left>
                                <input type="text" class="gridInput" style="width:100%;" name="enum_extractby" id="enum_extractby" readonly>
                            </td>				
                        </tr>
                        <tr><td height=3></td></tr>
                        <tr>
                            <td align="left" width="35%" class="bareBold" style="padding-right: 15px;">Phleb/Imaging Site&nbsp;:</td>
                            <td align=left>
                                <select class="gridInput" style="width:100%;" name="enum_location" id="enum_location">
                                    <?php
                                        $iun = $o->dbquery("select id,location from lab_locations;");
                                        while(list($aa,$ab) = $iun->fetch_array()) {
                                            echo "<option value='$aa'>$ab</option>";
                                        }
                                    ?>
                                </select>
                            </td>				
                        </tr>
                    </table>
                </td>
                <td width=1%>&nbsp;</td>
                <td width=64% valign=top >                  
                    <table width=100% cellspacing=0 cellpadding=0><tr><td width=100% class=gridHead align=center>RESULT DETAILS</td></tr></table>
                    <table width=100% cellpadding=0 cellspacing=0 class="td_content">
                        <tr>
                            <td align="left" width="35%"  class="bareBold" style="padding-right: 15px;">Result Date&nbsp;:</td>
                            <td align=left>
                                <input class="gridInput" style="width:100%;" type=text name="enum_date" id="enum_date" value="<?php echo date('m/d/Y'); ?>">
                            </td>				
                        </tr>
                        <tr><td height=3></td></tr>
                        <tr>
                            <td align="left" width="35%" class="bareBold" style="padding-right: 15px;">Result&nbsp;:</td>
                            <td align=left>
                                <input type="text" class="gridInput" style="width:100%;" name="enum_result" id="enum_result">
                            </td>				
                        </tr>
                        <tr><td height=3></td></tr>
                        <tr>
                            <td align="left" width="35%" class="bareBold" style="padding-right: 15px;">Test Performed By&nbsp;:</td>
                            <td align=left>
                                <select name="enum_result_by" id="enum_result_by" class="gridInput" style="width:100%">
                                    <option value="">- Not Applicable -</option>
                                    <?php
                                        $pbyQuery = $o->dbquery("select emp_id, fullname from user_info where role like '%MEDICAL TECH%';");
                                        while($pbyRow = $pbyQuery->fetch_array()) {
                                            echo "<option value = '$pbyRow[0]'>$pbyRow[1]</option>";
                                        }
                                    ?>
                                </select>
                            </td>				
                        </tr>
                        <tr><td height=3></td></tr>
                        <tr>
                            <td align="left" width="35%" class="bareBold" style="padding-right: 15px;" valign=top>Other Notable Remarks&nbsp;:</td>
                            <td align=left>
                                <textarea name="enum_remarks" id="enum_remarks" style="width:100%;" rows=3></textarea>
                            </td>				
                        </tr>
                    </table>
                </td>
            </tr>
        </table>       
    </form>
</div>

<div id="hivResult" style="display: none;">
    <form name="frmHivResult" id="frmHivResult">  
    <table width=100% cellspacing=0 cellpadding=0><tr><td width=100% class=gridHead align=center>PATIENT & ORDER INFORMATION</td></tr></table>
        <table width=100% cellpadding=0 cellspacing=0 style="border: 1px solid #cdcdcd; padding: 10px; margin-bottom: 5px;">
            <tr><td height=3></td></tr>
            <tr>
                <td align="left" width="35%"  class="bareBold" style="padding-right: 15px;">Service Order No.&nbsp;:</td>
                <td align=left>
                    <input class="gridInput" style="width:100%;" type=text name="hiv_sono" id="hiv_sono">
                </td>				
            </tr>
            <tr><td height=3></td></tr>
            <tr>
                <td align="left" width="35%"  class="bareBold" style="padding-right: 15px;">Service Order Date&nbsp;:</td>
                <td align=left>
                    <input class="gridInput" style="width:100%;" type=text name="hiv_sodate" id="hiv_sodate">
                </td>				
            </tr>
            <tr><td height=3></td></tr>
            <tr>
                <td align="left" width="35%" class="bareBold" style="padding-right: 15px;">Patient ID&nbsp;:</td>
                <td align=left>
                    <input type="text" class="gridInput" style="width:100%;" name="hiv_pid" id="hiv_pid">
                </td>				
            </tr>
            <tr><td height=3></td></tr>
            <tr>
                <td align="left" width="35%" class="bareBold" style="padding-right: 15px;">Patient Name&nbsp;:</td>
                <td align=left>
                    <input type="text" class="gridInput" style="width:100%;" name="hiv_pname" id="hiv_pname">
                </td>				
            </tr>
            <tr><td height=3></td></tr>

            <tr>
                <td align="left" width="35%" class="bareBold" style="padding-right: 15px;">Gender&nbsp;:</td>
                <td align=left>
                    <input type="text" class="gridInput" style="width:100%;" name="hiv_gender" id="hiv_gender">
                </td>				
            </tr>
            <tr><td height=3></td></tr>
            <tr>
                <td align="left" width="35%" class="bareBold" style="padding-right: 15px;">Birthdate&nbsp;:</td>
                <td align=left>
                    <input type="text" class="gridInput" style="width:100%;" name="hiv_birthdate" id="hiv_birthdate">
                </td>				
            </tr>
            <tr><td height=3></td></tr>
            <tr>
                <td align="left" width="35%" class="bareBold" style="padding-right: 15px;">Age&nbsp;:</td>
                <td align=left>
                    <input type="text" class="gridInput" style="width:100%;" name="hiv_age" id="hiv_age">
                </td>				
            </tr>
            <tr><td height=3></td></tr>
            <tr>
                <td align="left" width="35%" class="bareBold" style="padding-right: 15px;">Patient Status&nbsp;:</td>
                <td align=left>
                    <input type="text" class="gridInput" style="width:100%;" name="hiv_patientstat" id="hiv_patientstat" readonly>
                </td>				
            </tr>
            <tr><td height=3></td></tr>
            <tr>
                <td align="left" width="35%" class="bareBold" style="padding-right: 15px;">Requesting Physician&nbsp;:</td>
                <td align=left>
                    <input type="text" class="gridInput" style="width:100%;" name="hiv_physician" id="hiv_physician">
                </td>				
            </tr>
            <tr><td height=3></td></tr>
        </table>
        <table width=100% cellspacing=0 cellpadding=0><tr><td width=100% class=gridHead align=center>SAMPLE DETAILS</td></tr></table>
        <table width=100% cellpadding=0 cellspacing=0 style="border: 1px solid #cdcdcd; padding: 10px;">
            <tr>
                <td align="left" width="35%" class="bareBold" style="padding-right: 15px;">Test or Procedure&nbsp;:</td>
                <td align=left>
                    <input type="text" class="gridInput" style="width:100%;" name="hiv_procedure" id="hiv_procedure">
                </td>				
            </tr>
            <tr><td height=3></td></tr>
            <tr>
                <td align="left" width="35%" class="bareBold" style="padding-right: 15px;">Procedure Code&nbsp;:</td>
                <td align=left>
                    <input type="text" class="gridInput" style="width:100%;" name="hiv_code" id="hiv_code">
                </td>				
            </tr>
            <tr><td height=3></td></tr>
            <tr>
                <td align="left" width="35%" class="bareBold" style="padding-right: 15px;">Specimen Type&nbsp;:</td>
                <td align=left>
                    <select class="gridInput" style="width:100%;" name="hiv_spectype" id="hiv_spectype">
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
                    <input type="text" class="gridInput" style="width:100%;" name="hiv_serialno" id="hiv_serialno">
                </td>				
            </tr>
            <tr><td height=3></td></tr>
            <tr>
                <td align="left" width="35%" class="bareBold" style="padding-right: 15px;">Date Extracted&nbsp;:</td>
                <td align=left>
                    <input type="text" class="gridInput" style="width:100%;" name="hiv_extractdate" id="hiv_extractdate">
                </td>				
            </tr>
            <tr><td height=3></td></tr>
            <tr>
                <td align="left" width="35%" class="bareBold" style="padding-right: 15px;">Time Extracted&nbsp;:</td>
                <td align=left>
        
                    <input type="text" class="gridInput" style="width:100%;" name="hiv_extracttime" id="hiv_extracttime" readonly>

                </td>				
            </tr>
            <tr><td height=3></td></tr>               
            <tr>
                <td align="left" width="35%" class="bareBold" style="padding-right: 15px;">Extracted By&nbsp;:</td>
                <td align=left>
                    <input type="text" class="gridInput" style="width:100%;" name="hiv_extractby" id="hiv_extractby" readonly>
                </td>				
            </tr>
            <tr><td height=3></td></tr>
            <tr>
                <td align="left" width="35%" class="bareBold" style="padding-right: 15px;">Phleb/Imaging Site&nbsp;:</td>
                <td align=left>
                    <select class="gridInput" style="width:100%;" name="hiv_location" id="hiv_location">
                    <?php
                            $iun = $o->dbquery("select id,location from lab_locations;");
                            while(list($aa,$ab) = $iun->fetch_array()) {
                                echo "<option value='$aa'>$ab</option>";
                            }
                        ?>
                    </select>
                </td>				
            </tr>
        </table>                  
        <table width=100% cellspacing=0 cellpadding=0><tr><td width=100% class=gridHead align=center>RESULT DETAILS</td></tr></table>
        <table width=100% cellpadding=0 cellspacing=0 style="border: 1px solid #cdcdcd; padding: 10px;">
            <tr>
                <td align="left" width="35%"  class="bareBold" style="padding-right: 15px;">Result Date&nbsp;:</td>
                <td align=left>
                    <input class="gridInput" style="width:100%;" type=text name="hiv_date" id="hiv_date" value="<?php echo date('m/d/Y'); ?>">
                </td>				
            </tr>
            <tr><td height=3></td></tr>
            <tr>
                <td align="left" width="35%" class="bareBold" style="padding-right: 15px;">HIV-1&nbsp;:</td>
                <td align=left>
                <select name="hiv_one" id="hiv_one" class="gridInput" style="width:100%;">
                        <option value="POSITIVE">POSITIVE</option>
                        <option value="NEGATIVE">NEGATIVE</option>
                    </select>
                </td>				
            </tr>
            <tr><td height=3></td></tr> 
            <tr>
                <td align="left" width="35%" class="bareBold" style="padding-right: 15px;">HIV-2&nbsp;:</td>
                <td align=left>
                <select name="hiv_two" id="hiv_two" class="gridInput" style="width:100%;">
                        <option value="POSITIVE">POSITIVE</option>
                        <option value="NEGATIVE">NEGATIVE</option>
                    </select>
                </td>				
            </tr>
            <tr><td height=3></td></tr>
            <tr>
                <td align="left" width="35%" class="bareBold" style="padding-right: 15px;">HIV-1/2&nbsp;:</td>
                    <td align=left>                 
                        <select name="hiv_half" id="hiv_half" class="gridInput" style="width:100%;">
                            <option value="POSITIVE">POSITIVE</option>
                            <option value="NEGATIVE">NEGATIVE</option>
                        </select>
                    </td>				
            </tr>
        </table>
    </form>
</div>

<div id="pregnancyResult" style="display: none;">
    <form name="frmPregnancyResult" id="frmPregnancyResult">  
    <table width=100% cellspacing=0 cellpadding=0><tr><td width=100% class=gridHead align=center>PATIENT & ORDER INFORMATION</td></tr></table>
        <table width=100% cellpadding=0 cellspacing=0 style="border: 1px solid #cdcdcd; padding: 10px; margin-bottom: 5px;">
            <tr><td height=3></td></tr>
            <tr>
                <td align="left" width="35%"  class="bareBold" style="padding-right: 15px;">Service Order No.&nbsp;:</td>
                <td align=left>
                    <input class="gridInput" style="width:100%;" type=text name="pt_sono" id="pt_sono">
                </td>				
            </tr>
            <tr><td height=3></td></tr>
            <tr>
                <td align="left" width="35%"  class="bareBold" style="padding-right: 15px;">Service Order Date&nbsp;:</td>
                <td align=left>
                    <input class="gridInput" style="width:100%;" type=text name="pt_sodate" id="pt_sodate">
                </td>				
            </tr>
            <tr><td height=3></td></tr>
            <tr>
                <td align="left" width="35%" class="bareBold" style="padding-right: 15px;">Patient ID&nbsp;:</td>
                <td align=left>
                    <input type="text" class="gridInput" style="width:100%;" name="pt_pid" id="pt_pid">
                </td>				
            </tr>
            <tr><td height=3></td></tr>
            <tr>
                <td align="left" width="35%" class="bareBold" style="padding-right: 15px;">Patient Name&nbsp;:</td>
                <td align=left>
                    <input type="text" class="gridInput" style="width:100%;" name="pt_pname" id="pt_pname">
                </td>				
            </tr>
            <tr><td height=3></td></tr>

            <tr>
                <td align="left" width="35%" class="bareBold" style="padding-right: 15px;">Gender&nbsp;:</td>
                <td align=left>
                    <input type="text" class="gridInput" style="width:100%;" name="pt_gender" id="pt_gender">
                </td>				
            </tr>
            <tr><td height=3></td></tr>
            <tr>
                <td align="left" width="35%" class="bareBold" style="padding-right: 15px;">Birthdate&nbsp;:</td>
                <td align=left>
                    <input type="text" class="gridInput" style="width:100%;" name="pt_birthdate" id="pt_birthdate">
                </td>				
            </tr>
            <tr><td height=3></td></tr>
            <tr>
                <td align="left" width="35%" class="bareBold" style="padding-right: 15px;">Age&nbsp;:</td>
                <td align=left>
                    <input type="text" class="gridInput" style="width:100%;" name="pt_age" id="pt_age">
                </td>				
            </tr>
            <tr><td height=3></td></tr>
            <tr>
                <td align="left" width="35%" class="bareBold" style="padding-right: 15px;">Patient Status&nbsp;:</td>
                <td align=left>
                    <input type="text" class="gridInput" style="width:100%;" name="pt_patientstat" id="pt_patientstat" readonly>
                </td>				
            </tr>
            <tr><td height=3></td></tr>
            <tr>
                <td align="left" width="35%" class="bareBold" style="padding-right: 15px;">Requesting Physician&nbsp;:</td>
                <td align=left>
                    <input type="text" class="gridInput" style="width:100%;" name="pt_physician" id="pt_physician">
                </td>				
            </tr>
            <tr><td height=3></td></tr>
        </table>
        <table width=100% cellspacing=0 cellpadding=0><tr><td width=100% class=gridHead align=center>SAMPLE DETAILS</td></tr></table>
        <table width=100% cellpadding=0 cellspacing=0 style="border: 1px solid #cdcdcd; padding: 10px;">
            <tr>
                <td align="left" width="35%" class="bareBold" style="padding-right: 15px;">Test or Procedure&nbsp;:</td>
                <td align=left>
                    <input type="text" class="gridInput" style="width:100%;" name="pt_procedure" id="pt_procedure">
                </td>				
            </tr>
            <tr><td height=3></td></tr>
            <tr>
                <td align="left" width="35%" class="bareBold" style="padding-right: 15px;">Procedure Code&nbsp;:</td>
                <td align=left>
                    <input type="text" class="gridInput" style="width:100%;" name="pt_code" id="pt_code">
                </td>				
            </tr>
            <tr><td height=3></td></tr>
            <tr>
                <td align="left" width="35%" class="bareBold" style="padding-right: 15px;">Specimen Type&nbsp;:</td>
                <td align=left>
                    <select class="gridInput" style="width:100%;" name="pt_spectype" id="pt_spectype">
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
                    <input type="text" class="gridInput" style="width:100%;" name="pt_serialno" id="pt_serialno">
                </td>				
            </tr>
            <tr><td height=3></td></tr>
            <tr>
                <td align="left" width="35%" class="bareBold" style="padding-right: 15px;">Date Extracted&nbsp;:</td>
                <td align=left>
                    <input type="text" class="gridInput" style="width:100%;" name="pt_extractdate" id="pt_extractdate">
                </td>				
            </tr>
            <tr><td height=3></td></tr>
            <tr>
                <td align="left" width="35%" class="bareBold" style="padding-right: 15px;">Time Extracted&nbsp;:</td>
                <td align=left>
        
                    <input type="text" class="gridInput" style="width:100%;" name="pt_extracttime" id="pt_extracttime" readonly>

                </td>				
            </tr>
            <tr><td height=3></td></tr>               
            <tr>
                <td align="left" width="35%" class="bareBold" style="padding-right: 15px;">Extracted By&nbsp;:</td>
                <td align=left>
                    <input type="text" class="gridInput" style="width:100%;" name="pt_extractby" id="pt_extractby" readonly>
                </td>				
            </tr>
            <tr><td height=3></td></tr>
            <tr>
                <td align="left" width="35%" class="bareBold" style="padding-right: 15px;">Phleb/Imaging Site&nbsp;:</td>
                <td align=left>
                    <select class="gridInput" style="width:100%;" name="pt_location" id="pt_location">
                        <?php
                            $iun = $o->dbquery("select id,location from lab_locations;");
                            while(list($aa,$ab) = $iun->fetch_array()) {
                                echo "<option value='$aa'>$ab</option>";
                            }
                        ?>
                    </select>
                </td>				
            </tr>
        </table>                  
        <table width=100% cellspacing=0 cellpadding=0><tr><td width=100% class=gridHead align=center>RESULT DETAILS</td></tr></table>
        <table width=100% cellpadding=0 cellspacing=0 style="border: 1px solid #cdcdcd; padding: 10px;">
            <tr>
                <td align="left" width="35%"  class="bareBold" style="padding-right: 15px;">Result Date&nbsp;:</td>
                <td align=left>
                    <input class="gridInput" style="width:100%;" type=text name="pt_date" id="pt_date" value="<?php echo date('m/d/Y'); ?>">
                </td>				
            </tr>
            <tr><td height=3></td></tr>
            <tr><td height=3></td></tr>
            <tr>
                <td align="left" width="35%" class="bareBold" style="padding-right: 15px;">Result&nbsp;:</td>
                <td align=left>
                    <input class="gridInput" style="width:100%;" type=text name="pt_result" id="pt_result">
                </td>				
            </tr>
            <tr><td height=3></td></tr>
            <tr>
                <td align="left" width="35%" class="bareBold" style="padding-right: 15px;" valign=top>Other Notable Remarks&nbsp;:</td>
                <td align=left>
                    <textarea name="pt_remarks" id="pt_remarks" style="width:100%;" rows=3></textarea>
                </td>				
            </tr>
        </table>
    </form>
</div>

<div id="lipidResult" style="display: none;">
    <form name="frmLipidResult" id="frmLipidResult">
        <table width=100% cellpadding=0 cellspacing=0 valign=top>
            <tr>
                <td width=44% valign=top>  
                    <table width=100% cellspacing=0 cellpadding=0><tr><td width=100% class=gridHead align=center>PATIENT & ORDER INFORMATION</td></tr></table>
                        <table width=100% cellpadding=0 cellspacing=0 style="border: 1px solid #cdcdcd; padding: 10px; margin-bottom: 5px;">
                            <tr><td height=3></td></tr>
                            <tr>
                                <td align="left" width="35%"  class="bareBold" style="padding-right: 15px;">Service Order No.&nbsp;:</td>
                                <td align=left>
                                    <input class="gridInput" style="width:100%;" type=text name="lipid_sono" id="lipid_sono">
                                </td>				
                            </tr>
                            <tr><td height=3></td></tr>
                            <tr>
                                <td align="left" width="35%"  class="bareBold" style="padding-right: 15px;">Service Order Date&nbsp;:</td>
                                <td align=left>
                                    <input class="gridInput" style="width:100%;" type=text name="lipid_sodate" id="lipid_sodate">
                                </td>				
                            </tr>
                            <tr><td height=3></td></tr>
                            <tr>
                                <td align="left" width="35%" class="bareBold" style="padding-right: 15px;">Patient ID&nbsp;:</td>
                                <td align=left>
                                    <input type="text" class="gridInput" style="width:100%;" name="lipid_pid" id="lipid_pid">
                                </td>				
                            </tr>
                            <tr><td height=3></td></tr>
                            <tr>
                                <td align="left" width="35%" class="bareBold" style="padding-right: 15px;">Patient Name&nbsp;:</td>
                                <td align=left>
                                    <input type="text" class="gridInput" style="width:100%;" name="lipid_pname" id="lipid_pname">
                                </td>				
                            </tr>
                            <tr><td height=3></td></tr>

                            <tr>
                                <td align="left" width="35%" class="bareBold" style="padding-right: 15px;">Gender&nbsp;:</td>
                                <td align=left>
                                    <input type="text" class="gridInput" style="width:100%;" name="lipid_gender" id="lipid_gender">
                                </td>				
                            </tr>
                            <tr><td height=3></td></tr>
                            <tr>
                                <td align="left" width="35%" class="bareBold" style="padding-right: 15px;">Birthdate&nbsp;:</td>
                                <td align=left>
                                    <input type="text" class="gridInput" style="width:100%;" name="lipid_birthdate" id="lipid_birthdate">
                                </td>				
                            </tr>
                            <tr><td height=3></td></tr>
                            <tr>
                                <td align="left" width="35%" class="bareBold" style="padding-right: 15px;">Age&nbsp;:</td>
                                <td align=left>
                                    <input type="text" class="gridInput" style="width:100%;" name="lipid_age" id="lipid_age">
                                </td>				
                            </tr>
                            <tr><td height=3></td></tr>
                            <tr>
                                <td align="left" width="35%" class="bareBold" style="padding-right: 15px;">Patient Status&nbsp;:</td>
                                <td align=left>
                                    <input type="text" class="gridInput" style="width:100%;" name="lipid_patientstat" id="lipid_patientstat" readonly>
                                </td>				
                            </tr>
                            <tr><td height=3></td></tr>
                            <tr>
                                <td align="left" width="35%" class="bareBold" style="padding-right: 15px;">Requesting Physician&nbsp;:</td>
                                <td align=left>
                                    <input type="text" class="gridInput" style="width:100%;" name="lipid_physician" id="lipid_physician">
                                </td>				
                            </tr>
                            <tr><td height=3></td></tr>
                        </table>
                        <table width=100% cellspacing=0 cellpadding=0><tr><td width=100% class=gridHead align=center>SAMPLE DETAILS</td></tr></table>
                        <table width=100% cellpadding=0 cellspacing=0 style="border: 1px solid #cdcdcd; padding: 10px;">
                            <tr>
                                <td align="left" width="35%" class="bareBold" style="padding-right: 15px;">Test or Procedure&nbsp;:</td>
                                <td align=left>
                                    <input type="text" class="gridInput" style="width:100%;" name="lipid_procedure" id="lipid_procedure">
                                </td>				
                            </tr>
                            <tr><td height=3></td></tr>
                            <tr>
                                <td align="left" width="35%" class="bareBold" style="padding-right: 15px;">Procedure Code&nbsp;:</td>
                                <td align=left>
                                    <input type="text" class="gridInput" style="width:100%;" name="lipid_code" id="lipid_code">
                                </td>				
                            </tr>
                            <tr><td height=3></td></tr>
                            <tr>
                                <td align="left" width="35%" class="bareBold" style="padding-right: 15px;">Specimen Type&nbsp;:</td>
                                <td align=left>
                                    <select class="gridInput" style="width:100%;" name="lipid_spectype" id="lipid_spectype">
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
                                    <input type="text" class="gridInput" style="width:100%;" name="lipid_serialno" id="lipid_serialno">
                                </td>				
                            </tr>
                            <tr><td height=3></td></tr>
                            <tr>
                                <td align="left" width="35%" class="bareBold" style="padding-right: 15px;">Date Extracted&nbsp;:</td>
                                <td align=left>
                                    <input type="text" class="gridInput" style="width:100%;" name="lipid_extractdate" id="lipid_extractdate">
                                </td>				
                            </tr>
                            <tr><td height=3></td></tr>
                            <tr>
                                <td align="left" width="35%" class="bareBold" style="padding-right: 15px;">Time Extracted&nbsp;:</td>
                                <td align=left>
                        
                                    <input type="text" class="gridInput" style="width:100%;" name="lipid_extracttime" id="lipid_extracttime" readonly>

                                </td>				
                            </tr>
                            <tr><td height=3></td></tr>
                            <tr>
                                <td align="left" width="35%" class="bareBold" style="padding-right: 15px;">Test Kit Type (If Applicable)&nbsp;:</td>
                                <td align=left>
                                    <input type="text" class="gridInput" style="width:100%;" name="lipid_testkit" id="lipid_testkit" readonly>
                                </td>				
                            </tr>
                            <tr><td height=3></td></tr>
                            <tr>
                                <td align="left" width="35%" class="bareBold" style="padding-right: 15px;">Lot No. (If Applicable)&nbsp;:</td>
                                <td align=left>
                                    <input type="text" class="gridInput" style="width:100%;" name="lipid_testkit_lotno" id="lipid_testkit_lotno" readonly>
                                </td>				
                            </tr>
                            <tr><td height=3></td></tr>
                            <tr>
                                <td align="left" width="35%" class="bareBold" style="padding-right: 15px;">Expiry (If Applicable&nbsp;:</td>
                                <td align=left>
                                    <input type="text" class="gridInput" style="width:100%;" name="lipid_testkit_expiry" id="lipid_testkit_expiry" readonly>
                                </td>				
                            </tr>
                            <tr><td height=3></td></tr>               
                            <tr>
                                <td align="left" width="35%" class="bareBold" style="padding-right: 15px;">Extracted By&nbsp;:</td>
                                <td align=left>
                                    <input type="text" class="gridInput" style="width:100%;" name="lipid_extractby" id="lipid_extractby" readonly>
                                </td>				
                            </tr>
                            <tr><td height=3></td></tr>
                            <tr>
                                <td align="left" width="35%" class="bareBold" style="padding-right: 15px;">Phleb/Imaging Site&nbsp;:</td>
                                <td align=left>
                                    <select class="gridInput" style="width:100%;" name="lipid_location" id="lipid_location">
                                        <?php
                                            $iun = $o->dbquery("select id,location from lab_locations;");
                                            while(list($aa,$ab) = $iun->fetch_array()) {
                                                echo "<option value='$aa'>$ab</option>";
                                            }
                                        ?>
                                    </select>
                                </td>				
                            </tr>
                        </table>
                    </td>
                    <td width=1%>&nbsp;</td>
                    <td width=64% valign=top >                  
                    <table width=100% cellspacing=0 cellpadding=0><tr><td width=100% class=gridHead align=center>RESULT DETAILS</td></tr></table>
                    <table width=100% cellpadding=0 cellspacing=0 class="td_content">
                        <tr>
                            <td align="left" width="35%"  class="bareBold" style="padding-right: 15px;">Result Date&nbsp;:</td>
                            <td align=left>
                                <input class="gridInput" style="width:100%;" type=text name="lipid_date" id="lipid_date" value="<?php echo date('m/d/Y'); ?>">
                            </td>				
                        </tr>
                        <tr><td height=3></td></tr>
                        <tr>
                            <td align="left" width="35%" class="bareBold" style="padding-right: 15px;">Total Cholesterol (mg/dL)&nbsp;:</td>
                            <td align=left>
                                <input class="gridInput" style="width:100%;" type=text name="lipid_cholesterol" id="lipid_cholesterol">
                            </td>				
                        </tr>
                        <tr><td height=3></td></tr>
                        <tr>
                            <td align="left" width="35%" class="bareBold" style="padding-right: 15px;">Triglycerides (mg/dL)&nbsp;:</td>
                            <td align=left>
                                <input class="gridInput" style="width:100%;" type=text name="lipid_triglycerides" id="lipid_triglycerides">
                            </td>				
                        </tr>
                        <tr><td height=3></td></tr>
                        <tr>
                            <td align="left" width="35%" class="bareBold" style="padding-right: 15px;">HDL (mg/dL)&nbsp;:</td>
                            <td align=left>
                                <input class="gridInput" style="width:100%;" type=text name="lipid_hdl" id="lipid_hdl">
                            </td>				
                        </tr>
                        <tr><td height=3></td></tr>
                        <tr>
                            <td align="left" width="35%" class="bareBold" style="padding-right: 15px;">LDL (mg/dL)&nbsp;:</td>
                            <td align=left>
                                <input class="gridInput" style="width:100%;" type=text name="lipid_ldl" id="lipid_ldl">
                            </td>				
                        </tr>
                        <tr><td height=3></td></tr>
                        <tr>
                            <td align="left" width="35%" class="bareBold" style="padding-right: 15px;">VLDL (mg/dL)&nbsp;:</td>
                            <td align=left>
                                <input class="gridInput" style="width:100%;" type=text name="lipid_vldl" id="lipid_vldl">
                            </td>				
                        </tr>
                        <tr><td height=3></td></tr>
                        <tr>
                            <td align="left" width="35%" class="bareBold" style="padding-right: 15px;">Test Performed By&nbsp;:</td>
                            <td align=left>
                                <select name="lipid_result_by" id="lipid_result_by" class="gridInput" style="width:100%">
                                    <option value="">- Not Applicable -</option>
                                    <?php
                                        $pbyQuery = $o->dbquery("select emp_id, fullname from user_info where role like '%MEDICAL TECH%';");
                                        while($pbyRow = $pbyQuery->fetch_array()) {
                                            echo "<option value = '$pbyRow[0]'>$pbyRow[1]</option>";
                                        }
                                    ?>
                                </select>
                            </td>				
                        </tr>
                        <tr><td height=3></td></tr>
                        <tr>
                            <td align="left" width="35%" class="bareBold" style="padding-right: 15px;" valign=top>Other Notable Remarks&nbsp;:</td>
                            <td align=left>
                                <textarea name="lipid_remarks" id="lipid_remarks" style="width:100%;" rows=3></textarea>
                            </td>				
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </form>
</div>

<div id="ogttResult" style="display: none;">
    <form name="frmOgttResult" id="frmOgttResult">  
    <table width=100% cellspacing=0 cellpadding=0><tr><td width=100% class=gridHead align=center>PATIENT & ORDER INFORMATION</td></tr></table>
        <table width=100% cellpadding=0 cellspacing=0 style="border: 1px solid #cdcdcd; padding: 10px; margin-bottom: 5px;">
            <tr><td height=3></td></tr>
            <tr>
                <td align="left" width="35%"  class="bareBold" style="padding-right: 15px;">Service Order No.&nbsp;:</td>
                <td align=left>
                    <input class="gridInput" style="width:100%;" type=text name="ogtt_sono" id="ogtt_sono">
                </td>				
            </tr>
            <tr><td height=3></td></tr>
            <tr>
                <td align="left" width="35%"  class="bareBold" style="padding-right: 15px;">Service Order Date&nbsp;:</td>
                <td align=left>
                    <input class="gridInput" style="width:100%;" type=text name="ogtt_sodate" id="ogtt_sodate">
                </td>				
            </tr>
            <tr><td height=3></td></tr>
            <tr>
                <td align="left" width="35%" class="bareBold" style="padding-right: 15px;">Patient ID&nbsp;:</td>
                <td align=left>
                    <input type="text" class="gridInput" style="width:100%;" name="ogtt_pid" id="ogtt_pid">
                </td>				
            </tr>
            <tr><td height=3></td></tr>
            <tr>
                <td align="left" width="35%" class="bareBold" style="padding-right: 15px;">Patient Name&nbsp;:</td>
                <td align=left>
                    <input type="text" class="gridInput" style="width:100%;" name="ogtt_pname" id="ogtt_pname">
                </td>				
            </tr>
            <tr><td height=3></td></tr>

            <tr>
                <td align="left" width="35%" class="bareBold" style="padding-right: 15px;">Gender&nbsp;:</td>
                <td align=left>
                    <input type="text" class="gridInput" style="width:100%;" name="ogtt_gender" id="ogtt_gender">
                </td>				
            </tr>
            <tr><td height=3></td></tr>
            <tr>
                <td align="left" width="35%" class="bareBold" style="padding-right: 15px;">Birthdate&nbsp;:</td>
                <td align=left>
                    <input type="text" class="gridInput" style="width:100%;" name="ogtt_birthdate" id="ogtt_birthdate">
                </td>				
            </tr>
            <tr><td height=3></td></tr>
            <tr>
                <td align="left" width="35%" class="bareBold" style="padding-right: 15px;">Age&nbsp;:</td>
                <td align=left>
                    <input type="text" class="gridInput" style="width:100%;" name="ogtt_age" id="ogtt_age">
                </td>				
            </tr>
            <tr><td height=3></td></tr>
            <tr>
                <td align="left" width="35%" class="bareBold" style="padding-right: 15px;">Patient Status&nbsp;:</td>
                <td align=left>
                    <input type="text" class="gridInput" style="width:100%;" name="ogtt_patientstat" id="ogtt_patientstat" readonly>
                </td>				
            </tr>
            <tr><td height=3></td></tr>
            <tr>
                <td align="left" width="35%" class="bareBold" style="padding-right: 15px;">Requesting Physician&nbsp;:</td>
                <td align=left>
                    <input type="text" class="gridInput" style="width:100%;" name="ogtt_physician" id="ogtt_physician">
                </td>				
            </tr>
            <tr><td height=3></td></tr>
        </table>
        <table width=100% cellspacing=0 cellpadding=0><tr><td width=100% class=gridHead align=center>SAMPLE DETAILS</td></tr></table>
        <table width=100% cellpadding=0 cellspacing=0 style="border: 1px solid #cdcdcd; padding: 10px;">
            <tr>
                <td align="left" width="35%" class="bareBold" style="padding-right: 15px;">Test or Procedure&nbsp;:</td>
                <td align=left>
                    <input type="text" class="gridInput" style="width:100%;" name="ogtt_procedure" id="ogtt_procedure">
                </td>				
            </tr>
            <tr><td height=3></td></tr>
            <tr>
                <td align="left" width="35%" class="bareBold" style="padding-right: 15px;">Procedure Code&nbsp;:</td>
                <td align=left>
                    <input type="text" class="gridInput" style="width:100%;" name="ogtt_code" id="ogtt_code">
                </td>				
            </tr>
            <tr><td height=3></td></tr>
            <tr>
                <td align="left" width="35%" class="bareBold" style="padding-right: 15px;">Specimen Type&nbsp;:</td>
                <td align=left>
                    <select class="gridInput" style="width:100%;" name="ogtt_spectype" id="ogtt_spectype">
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
                    <input type="text" class="gridInput" style="width:100%;" name="ogtt_serialno" id="ogtt_serialno">
                </td>				
            </tr>
            <tr><td height=3></td></tr>
            <tr>
                <td align="left" width="35%" class="bareBold" style="padding-right: 15px;">Date Extracted&nbsp;:</td>
                <td align=left>
                    <input type="text" class="gridInput" style="width:100%;" name="ogtt_extractdate" id="ogtt_extractdate">
                </td>				
            </tr>
            <tr><td height=3></td></tr>
            <tr>
                <td align="left" width="35%" class="bareBold" style="padding-right: 15px;">Time Extracted&nbsp;:</td>
                <td align=left>
        
                    <input type="text" class="gridInput" style="width:100%;" name="ogtt_extracttime" id="ogtt_extracttime" readonly>

                </td>				
            </tr>
            <tr><td height=3></td></tr>               
            <tr>
                <td align="left" width="35%" class="bareBold" style="padding-right: 15px;">Extracted By&nbsp;:</td>
                <td align=left>
                    <input type="text" class="gridInput" style="width:100%;" name="ogtt_extractby" id="ogtt_extractby" readonly>
                </td>				
            </tr>
            <tr><td height=3></td></tr>
            <tr>
                <td align="left" width="35%" class="bareBold" style="padding-right: 15px;">Phleb/Imaging Site&nbsp;:</td>
                <td align=left>
                    <select class="gridInput" style="width:100%;" name="ogtt_location" id="ogtt_location">
                    <?php
                            $iun = $o->dbquery("select id,location from lab_locations;");
                            while(list($aa,$ab) = $iun->fetch_array()) {
                                echo "<option value='$aa'>$ab</option>";
                            }
                        ?>
                    </select>
                </td>				
            </tr>
        </table>                  
        <table width=100% cellspacing=0 cellpadding=0><tr><td width=100% class=gridHead align=center>RESULT DETAILS</td></tr></table>
        <table width=100% cellpadding=0 cellspacing=0 style="border: 1px solid #cdcdcd; padding: 10px;">
            <tr>
                <td align="left" width="35%"  class="bareBold" style="padding-right: 15px;">Result Date&nbsp;:</td>
                <td align=left>
                    <input class="gridInput" style="width:100%;" type=text name="ogtt_date" id="ogtt_date" value="<?php echo date('m/d/Y'); ?>">
                </td>				
            </tr>
            <tr><td height=3></td></tr>
            <tr>
                <td align="left" width="35%" class="bareBold" style="padding-right: 15px;">Fasting (mg/dL)&nbsp;:</td>
                <td align=left><input class="gridInput" style="width:100%;" type=text name="ogtt_fasting" id="ogtt_fasting"></td>				
            </tr>
            <tr><td height=3></td></tr>
            <tr>
                <td align="left" width="35%" class="bareBold" style="padding-right: 15px;">Urine Glucose&nbsp;:</td>
                <td align=left>
                    <input class="gridInput" style="width:100%;" type=text name="ogtt_uglucose" id="ogtt_uglucose">
                </td>				
            </tr>
            <tr><td height=3></td></tr>
            <tr>
                <td align="left" width="35%" class="bareBold" style="padding-right: 15px;">1<sup>st</sup>&nbsp;Hour (mg/dL)&nbsp;:</td>
                <td align=left>
                    <input class="gridInput" style="width:100%;" type=text name="ogttFirstHr" id="ogttFirstHr">
                </td>				
            </tr>
            <tr><td height=3></td></tr>
            <tr>
                <td align="left" width="35%" class="bareBold" style="padding-right: 15px;">1<sup>st</sup>&nbsp;Hour (Urine Glucose)&nbsp;:</td>
                <td align=left>                 
                    <input class="gridInput" style="width:100%;" type=text name="first_hr_uglucose" id="first_hr_uglucose">
                </td>				
            </tr>
            <tr><td height=3></td></tr>
            <tr>
                <td align="left" width="35%" class="bareBold" style="padding-right: 15px;">2<sup>nd</sup>&nbsp;Hour (mg/dL)&nbsp;:</td>
                <td align=left>
                    <input class="gridInput" style="width:100%;" type=text name="second_hr" id="second_hr">
                </td>				
            </tr>
            <tr><td height=3></td></tr>
            <tr>
                <td align="left" width="35%" class="bareBold" style="padding-right: 15px;">2<sup>nd</sup>&nbsp;Hour (Urine Glucose)&nbsp;:</td>
                <td align=left>
                    <input class="gridInput" style="width:100%;" type=text name="second_hr_uglucose" id="second_hr_uglucose">
                </td>				
            </tr>
            <tr><td height=3></td></tr>
        </table>
    </form>
</div>

<div id="bloodtypeResult" style="display: none;">
    <form name="frmBloodType" id="frmBloodType">
        <table width=100% cellpadding=0 cellspacing=0 valign=top>
            <tr>
               <td width=44% valign=top>  
                    <table width=100% cellspacing=0 cellpadding=0><tr><td width=100% class=gridHead align=center>PATIENT & ORDER INFORMATION</td></tr></table>
                    <table width=100% cellpadding=0 cellspacing=0 style="border: 1px solid #cdcdcd; padding: 10px; margin-bottom: 5px;">
                        <tr><td height=3></td></tr>
                        <tr>
                            <td align="left" width="35%"  class="bareBold" style="padding-right: 15px;">Service Order No.&nbsp;:</td>
                            <td align=left>
                                <input class="gridInput" style="width:100%;" type=text name="btype_sono" id="btype_sono">
                            </td>				
                        </tr>
                        <tr><td height=3></td></tr>
                        <tr>
                            <td align="left" width="35%"  class="bareBold" style="padding-right: 15px;">Service Order Date&nbsp;:</td>
                            <td align=left>
                                <input class="gridInput" style="width:100%;" type=text name="btype_sodate" id="btype_sodate">
                            </td>				
                        </tr>
                        <tr><td height=3></td></tr>
                        <tr>
                            <td align="left" width="35%" class="bareBold" style="padding-right: 15px;">Patient ID&nbsp;:</td>
                            <td align=left>
                                <input type="text" class="gridInput" style="width:100%;" name="btype_pid" id="btype_pid">
                            </td>				
                        </tr>
                        <tr><td height=3></td></tr>
                        <tr>
                            <td align="left" width="35%" class="bareBold" style="padding-right: 15px;">Patient Name&nbsp;:</td>
                            <td align=left>
                                <input type="text" class="gridInput" style="width:100%;" name="btype_pname" id="btype_pname">
                            </td>				
                        </tr>
                        <tr><td height=3></td></tr>

                        <tr>
                            <td align="left" width="35%" class="bareBold" style="padding-right: 15px;">Gender&nbsp;:</td>
                            <td align=left>
                                <input type="text" class="gridInput" style="width:100%;" name="btype_gender" id="btype_gender">
                            </td>				
                        </tr>
                        <tr><td height=3></td></tr>
                        <tr>
                            <td align="left" width="35%" class="bareBold" style="padding-right: 15px;">Birthdate&nbsp;:</td>
                            <td align=left>
                                <input type="text" class="gridInput" style="width:100%;" name="btype_birthdate" id="btype_birthdate">
                            </td>				
                        </tr>
                        <tr><td height=3></td></tr>
                        <tr>
                            <td align="left" width="35%" class="bareBold" style="padding-right: 15px;">Age&nbsp;:</td>
                            <td align=left>
                                <input type="text" class="gridInput" style="width:100%;" name="btype_age" id="btype_age">
                            </td>				
                        </tr>
                        <tr><td height=3></td></tr>
                        <tr>
                            <td align="left" width="35%" class="bareBold" style="padding-right: 15px;">Patient Status&nbsp;:</td>
                            <td align=left>
                                <input type="text" class="gridInput" style="width:100%;" name="btype_patientstat" id="btype_patientstat">
                            </td>				
                        </tr>
                        <tr><td height=3></td></tr>
                        <tr>
                            <td align="left" width="35%" class="bareBold" style="padding-right: 15px;">Requesting Physician&nbsp;:</td>
                            <td align=left>
                                <input type="text" class="gridInput" style="width:100%;" name="btype_physician" id="btype_physician">
                            </td>				
                        </tr>
                        <tr><td height=3></td></tr>
                    </table>
                    <table width=100% cellspacing=0 cellpadding=0><tr><td width=100% class=gridHead align=center>SAMPLE DETAILS</td></tr></table>
                    <table width=100% cellpadding=0 cellspacing=0 style="border: 1px solid #cdcdcd; padding: 10px;">
                        <tr>
                            <td align="left" width="35%" class="bareBold" style="padding-right: 15px;">Test or Procedure&nbsp;:</td>
                            <td align=left>
                                <input type="text" class="gridInput" style="width:100%;" name="btype_procedure" id="btype_procedure">
                            </td>				
                        </tr>
                        <tr><td height=3></td></tr>
                        <tr>
                            <td align="left" width="35%" class="bareBold" style="padding-right: 15px;">Procedure Code&nbsp;:</td>
                            <td align=left>
                                <input type="text" class="gridInput" style="width:100%;" name="btype_code" id="btype_code">
                            </td>				
                        </tr>
                        <tr><td height=3></td></tr>
                        <tr>
                            <td align="left" width="35%" class="bareBold" style="padding-right: 15px;">Specimen Type&nbsp;:</td>
                            <td align=left>
                                <select class="gridInput" style="width:100%;" name="btype_spectype" id="btype_spectype">
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
                                <input type="text" class="gridInput" style="width:100%;" name="btype_serialno" id="btype_serialno">
                            </td>				
                        </tr>
                        <tr><td height=3></td></tr>
                        <tr>
                            <td align="left" width="35%" class="bareBold" style="padding-right: 15px;">Date Extracted&nbsp;:</td>
                            <td align=left>
                                <input type="text" class="gridInput" style="width:100%;" name="btype_extractdate" id="btype_extractdate">
                            </td>				
                        </tr>
                        <tr><td height=3></td></tr>
                        <tr>
                            <td align="left" width="35%" class="bareBold" style="padding-right: 15px;">Time Extracted&nbsp;:</td>
                            <td align=left>
                    
                                <input type="text" class="gridInput" style="width:100%;" name="btype_extracttime" id="btype_extracttime" readonly>

                            </td>				
                        </tr>
                        <tr><td height=3></td></tr>
                        <tr>
                            <td align="left" width="35%" class="bareBold" style="padding-right: 15px;">Test Kit Type (If Applicable)&nbsp;:</td>
                            <td align=left>
                                <input type="text" class="gridInput" style="width:100%;" name="btype_testkit" id="btype_testkit" readonly>
                            </td>				
                        </tr>
                        <tr><td height=3></td></tr>
                        <tr>
                            <td align="left" width="35%" class="bareBold" style="padding-right: 15px;">Lot No. (If Applicable)&nbsp;:</td>
                            <td align=left>
                                <input type="text" class="gridInput" style="width:100%;" name="btype_testkit_lotno" id="btype_testkit_lotno" readonly>
                            </td>				
                        </tr>
                        <tr><td height=3></td></tr>
                        <tr>
                            <td align="left" width="35%" class="bareBold" style="padding-right: 15px;">Expiry (If Applicable&nbsp;:</td>
                            <td align=left>
                                <input type="text" class="gridInput" style="width:100%;" name="btype_testkit_expiry" id="btype_testkit_expiry" readonly>
                            </td>				
                        </tr>
                        <tr><td height=3></td></tr>
                        <tr>
                            <td align="left" width="35%" class="bareBold" style="padding-right: 15px;">Extracted By&nbsp;:</td>
                            <td align=left>
                                <input type="text" class="gridInput" style="width:100%;" name="btype_extractby" id="btype_extractby" readonly>
                            </td>				
                        </tr>
                        <tr><td height=3></td></tr>
                        <tr>
                            <td align="left" width="35%" class="bareBold" style="padding-right: 15px;">Collection Site&nbsp;:</td>
                            <td align=left>
                                <select class="gridInput" style="width:100%;" name="btype_location" id="btype_location">
                                    <?php
                                        $iun = $o->dbquery("select id,location from lab_locations;");
                                        while(list($aa,$ab) = $iun->fetch_array()) {
                                            echo "<option value='$aa'>$ab</option>";
                                        }
                                    ?>
                                </select>
                            </td>				
                        </tr>
                    </table>
                </td>
                <td width=1%>&nbsp;</td>
                <td width=64% valign=top >                            
                    <table width=100% cellspacing=0 cellpadding=0><tr><td width=100% class=gridHead align=center>RESULT DETAILS</td></tr></table>
                    <table width=100% cellpadding=0 cellspacing=0 class="td_content"">
                        <tr>
                            <td align="left" width="35%"  class="bareBold" style="padding-right: 15px;">Result Date&nbsp;:</td>
                            <td align=left>
                                <input class="gridInput" style="width:100%;" type=text name="btype_date" id="btype_date" value="<?php echo date('m/d/Y'); ?>">
                            </td>				
                        </tr>
                        <tr><td height=3></td></tr>
                        <tr><td height=3></td></tr>
                        <tr>
                            <td align="left" width="35%" class="bareBold" style="padding-right: 15px;">Result&nbsp;:</td>
                            <td align=left>
                                <select name="btype_result" id="btype_result" class="gridInput" style="width:100%">
                                    <?php
                                        $btQuery = $o->dbquery("select bloodType from options_bloodtypes;");
                                        while($btRow = $btQuery->fetch_array()) {
                                            echo "<option value='$btRow[0]'>$btRow[0]</option>";
                                        }
                                    ?>
                                </select>
                            </td>				
                        </tr>
                        <tr><td height=3></td></tr>
                        <tr>
                            <td align="left" width="35%" class="bareBold" style="padding-right: 15px;">Rh&nbsp;:</td>
                            <td align=left>
                                <select name="btype_rh" id="btype_rh" class="gridInput" style="width:100%">
                                    <option value='Positive'>Positive</option>
                                    <option value='Negative'>Negative</option>
                                </select>
                            </td>				
                        </tr>
                        <tr><td height=3></td></tr>
                        <tr>
                            <td align="left" width="35%" class="bareBold" style="padding-right: 15px;">Test Performed By&nbsp;:</td>
                            <td align=left>
                                <select name="btype_result_by" id="btype_result_by" class="gridInput" style="width:100%">
                                    <option value="">- Not Applicable -</option>
                                    <?php
                                        $pbyQuery = $o->dbquery("select emp_id, fullname from user_info where role like '%MEDICAL TECH%';");
                                        while($pbyRow = $pbyQuery->fetch_array()) {
                                            echo "<option value = '$pbyRow[0]'>$pbyRow[1]</option>";
                                        }
                                    ?>
                                </select>
                            </td>				
                        </tr>
                        <tr><td height=3></td></tr>
                        <tr>
                            <td align="left" width="35%" class="bareBold" style="padding-right: 15px;" valign=top>Other Notable Remarks&nbsp;:</td>
                            <td align=left>
                                <textarea name="btype_remarks" id="btype_remarks" style="width:100%;" rows=3></textarea>
                            </td>				
                        </tr>
                    </table>
                </td>
             </tr>
        </table>
    </form>
</div>

<div id="havResult" style="display: none;">
    <form name="frmHavResult" id="frmHavResult">  
    <table width=100% cellspacing=0 cellpadding=0><tr><td width=100% class=gridHead align=center>PATIENT & ORDER INFORMATION</td></tr></table>
        <table width=100% cellpadding=0 cellspacing=0 style="border: 1px solid #cdcdcd; padding: 10px; margin-bottom: 5px;">
            <tr><td height=3></td></tr>
            <tr>
                <td align="left" width="35%"  class="bareBold" style="padding-right: 15px;">Service Order No.&nbsp;:</td>
                <td align=left>
                    <input class="gridInput" style="width:100%;" type=text name="hav_sono" id="hav_sono">
                </td>				
            </tr>
            <tr><td height=3></td></tr>
            <tr>
                <td align="left" width="35%"  class="bareBold" style="padding-right: 15px;">Service Order Date&nbsp;:</td>
                <td align=left>
                    <input class="gridInput" style="width:100%;" type=text name="hav_sodate" id="hav_sodate">
                </td>				
            </tr>
            <tr><td height=3></td></tr>
            <tr>
                <td align="left" width="35%" class="bareBold" style="padding-right: 15px;">Patient ID&nbsp;:</td>
                <td align=left>
                    <input type="text" class="gridInput" style="width:100%;" name="hav_pid" id="hav_pid">
                </td>				
            </tr>
            <tr><td height=3></td></tr>
            <tr>
                <td align="left" width="35%" class="bareBold" style="padding-right: 15px;">Patient Name&nbsp;:</td>
                <td align=left>
                    <input type="text" class="gridInput" style="width:100%;" name="hav_pname" id="hav_pname">
                </td>				
            </tr>
            <tr><td height=3></td></tr>

            <tr>
                <td align="left" width="35%" class="bareBold" style="padding-right: 15px;">Gender&nbsp;:</td>
                <td align=left>
                    <input type="text" class="gridInput" style="width:100%;" name="hav_gender" id="hav_gender">
                </td>				
            </tr>
            <tr><td height=3></td></tr>
            <tr>
                <td align="left" width="35%" class="bareBold" style="padding-right: 15px;">Birthdate&nbsp;:</td>
                <td align=left>
                    <input type="text" class="gridInput" style="width:100%;" name="hav_birthdate" id="hav_birthdate">
                </td>				
            </tr>
            <tr><td height=3></td></tr>
            <tr>
                <td align="left" width="35%" class="bareBold" style="padding-right: 15px;">Age&nbsp;:</td>
                <td align=left>
                    <input type="text" class="gridInput" style="width:100%;" name="hav_age" id="hav_age">
                </td>				
            </tr>
            <tr><td height=3></td></tr>
            <tr>
                <td align="left" width="35%" class="bareBold" style="padding-right: 15px;">Patient Status&nbsp;:</td>
                <td align=left>
                    <input type="text" class="gridInput" style="width:100%;" name="hav_patientstat" id="hav_patientstat" readonly>
                </td>				
            </tr>
            <tr><td height=3></td></tr>
            <tr>
                <td align="left" width="35%" class="bareBold" style="padding-right: 15px;">Requesting Physician&nbsp;:</td>
                <td align=left>
                    <input type="text" class="gridInput" style="width:100%;" name="hav_physician" id="hav_physician">
                </td>				
            </tr>
            <tr><td height=3></td></tr>
        </table>
        <table width=100% cellspacing=0 cellpadding=0><tr><td width=100% class=gridHead align=center>SAMPLE DETAILS</td></tr></table>
        <table width=100% cellpadding=0 cellspacing=0 style="border: 1px solid #cdcdcd; padding: 10px;">
            <tr>
                <td align="left" width="35%" class="bareBold" style="padding-right: 15px;">Test or Procedure&nbsp;:</td>
                <td align=left>
                    <input type="text" class="gridInput" style="width:100%;" name="hav_procedure" id="hav_procedure">
                </td>				
            </tr>
            <tr><td height=3></td></tr>
            <tr>
                <td align="left" width="35%" class="bareBold" style="padding-right: 15px;">Procedure Code&nbsp;:</td>
                <td align=left>
                    <input type="text" class="gridInput" style="width:100%;" name="hav_code" id="hav_code">
                </td>				
            </tr>
            <tr><td height=3></td></tr>
            <tr>
                <td align="left" width="35%" class="bareBold" style="padding-right: 15px;">Specimen Type&nbsp;:</td>
                <td align=left>
                    <select class="gridInput" style="width:100%;" name="hav_spectype" id="hav_spectype">
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
                    <input type="text" class="gridInput" style="width:100%;" name="hav_serialno" id="hav_serialno">
                </td>				
            </tr>
            <tr><td height=3></td></tr>
            <tr>
                <td align="left" width="35%" class="bareBold" style="padding-right: 15px;">Date Extracted&nbsp;:</td>
                <td align=left>
                    <input type="text" class="gridInput" style="width:100%;" name="hav_extractdate" id="hav_extractdate">
                </td>				
            </tr>
            <tr><td height=3></td></tr>
            <tr>
                <td align="left" width="35%" class="bareBold" style="padding-right: 15px;">Time Extracted&nbsp;:</td>
                <td align=left>
        
                    <input type="text" class="gridInput" style="width:100%;" name="hav_extracttime" id="hav_extracttime" readonly>

                </td>				
            </tr>
            <tr><td height=3></td></tr>               
            <tr>
                <td align="left" width="35%" class="bareBold" style="padding-right: 15px;">Extracted By&nbsp;:</td>
                <td align=left>
                    <input type="text" class="gridInput" style="width:100%;" name="hav_extractby" id="hav_extractby" readonly>
                </td>				
            </tr>
            <tr><td height=3></td></tr>
            <tr>
                <td align="left" width="35%" class="bareBold" style="padding-right: 15px;">Phleb/Imaging Site&nbsp;:</td>
                <td align=left>
                    <select class="gridInput" style="width:100%;" name="hav_location" id="hav_location">
                        <?php
                            $iun = $o->dbquery("select id,location from lab_locations;");
                            while(list($aa,$ab) = $iun->fetch_array()) {
                                echo "<option value='$aa'>$ab</option>";
                            }
                        ?>
                    </select>
                </td>				
            </tr>
        </table>                  
        <table width=100% cellspacing=0 cellpadding=0><tr><td width=100% class=gridHead align=center>RESULT DETAILS</td></tr></table>
        <table width=100% cellpadding=0 cellspacing=0 style="border: 1px solid #cdcdcd; padding: 10px;">
            <tr>
                <td align="left" width="35%"  class="bareBold" style="padding-right: 15px;">Result Date&nbsp;:</td>
                <td align=left>
                    <input class="gridInput" style="width:100%;" type=text name="hav_date" id="hav_date" value="<?php echo date('m/d/Y'); ?>">
                </td>				
            </tr>
            <tr><td height=3></td></tr>
            <tr><td height=3></td></tr>
            <tr>
                <td align="left" width="35%" class="bareBold" style="padding-right: 15px;">Result&nbsp;:</td>
                <td align=left>
                    <input class="gridInput" style="width:100%;" type=text name="hav_result" id="hav_result">
                </td>				
            </tr>
            <tr><td height=3></td></tr>
            <tr>
                <td align="left" width="35%" class="bareBold" style="padding-right: 15px;" valign=top>Other Notable Remarks&nbsp;:</td>
                <td align=left>
                    <textarea name="hav_remarks" id="hav_remarks" style="width:100%;" rows=3></textarea>
                </td>				
            </tr>
        </table>
    </form>
</div>

<div id="dengueResult" style="display: none;">
    <form name="frmDengueResult" id="frmDengueResult">  
    <table width=100% cellspacing=0 cellpadding=0><tr><td width=100% class=gridHead align=center>PATIENT & ORDER INFORMATION</td></tr></table>
        <table width=100% cellpadding=0 cellspacing=0 style="border: 1px solid #cdcdcd; padding: 10px; margin-bottom: 5px;">
            <tr><td height=3></td></tr>
            <tr>
                <td align="left" width="35%"  class="bareBold" style="padding-right: 15px;">Service Order No.&nbsp;:</td>
                <td align=left>
                    <input class="gridInput" style="width:100%;" type=text name="dengue_sono" id="dengue_sono">
                </td>				
            </tr>
            <tr><td height=3></td></tr>
            <tr>
                <td align="left" width="35%"  class="bareBold" style="padding-right: 15px;">Service Order Date&nbsp;:</td>
                <td align=left>
                    <input class="gridInput" style="width:100%;" type=text name="dengue_sodate" id="dengue_sodate">
                </td>				
            </tr>
            <tr><td height=3></td></tr>
            <tr>
                <td align="left" width="35%" class="bareBold" style="padding-right: 15px;">Patient ID&nbsp;:</td>
                <td align=left>
                    <input type="text" class="gridInput" style="width:100%;" name="dengue_pid" id="dengue_pid">
                </td>				
            </tr>
            <tr><td height=3></td></tr>
            <tr>
                <td align="left" width="35%" class="bareBold" style="padding-right: 15px;">Patient Name&nbsp;:</td>
                <td align=left>
                    <input type="text" class="gridInput" style="width:100%;" name="dengue_pname" id="dengue_pname">
                </td>				
            </tr>
            <tr><td height=3></td></tr>

            <tr>
                <td align="left" width="35%" class="bareBold" style="padding-right: 15px;">Gender&nbsp;:</td>
                <td align=left>
                    <input type="text" class="gridInput" style="width:100%;" name="dengue_gender" id="dengue_gender">
                </td>				
            </tr>
            <tr><td height=3></td></tr>
            <tr>
                <td align="left" width="35%" class="bareBold" style="padding-right: 15px;">Birthdate&nbsp;:</td>
                <td align=left>
                    <input type="text" class="gridInput" style="width:100%;" name="dengue_birthdate" id="dengue_birthdate">
                </td>				
            </tr>
            <tr><td height=3></td></tr>
            <tr>
                <td align="left" width="35%" class="bareBold" style="padding-right: 15px;">Age&nbsp;:</td>
                <td align=left>
                    <input type="text" class="gridInput" style="width:100%;" name="dengue_age" id="dengue_age">
                </td>				
            </tr>
            <tr><td height=3></td></tr>
            <tr>
                <td align="left" width="35%" class="bareBold" style="padding-right: 15px;">Patient Status&nbsp;:</td>
                <td align=left>
                    <input type="text" class="gridInput" style="width:100%;" name="dengue_patientstat" id="dengue_patientstat" readonly>
                </td>				
            </tr>
            <tr><td height=3></td></tr>
            <tr>
                <td align="left" width="35%" class="bareBold" style="padding-right: 15px;">Requesting Physician&nbsp;:</td>
                <td align=left>
                    <input type="text" class="gridInput" style="width:100%;" name="dengue_physician" id="dengue_physician">
                </td>				
            </tr>
            <tr><td height=3></td></tr>
        </table>
        <table width=100% cellspacing=0 cellpadding=0><tr><td width=100% class=gridHead align=center>SAMPLE DETAILS</td></tr></table>
        <table width=100% cellpadding=0 cellspacing=0 style="border: 1px solid #cdcdcd; padding: 10px;">
            <tr>
                <td align="left" width="35%" class="bareBold" style="padding-right: 15px;">Test or Procedure&nbsp;:</td>
                <td align=left>
                    <input type="text" class="gridInput" style="width:100%;" name="dengue_procedure" id="dengue_procedure">
                </td>				
            </tr>
            <tr><td height=3></td></tr>
            <tr>
                <td align="left" width="35%" class="bareBold" style="padding-right: 15px;">Procedure Code&nbsp;:</td>
                <td align=left>
                    <input type="text" class="gridInput" style="width:100%;" name="dengue_code" id="dengue_code">
                </td>				
            </tr>
            <tr><td height=3></td></tr>
            <tr>
                <td align="left" width="35%" class="bareBold" style="padding-right: 15px;">Specimen Type&nbsp;:</td>
                <td align=left>
                    <select class="gridInput" style="width:100%;" name="dengue_spectype" id="dengue_spectype">
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
                    <input type="text" class="gridInput" style="width:100%;" name="dengue_serialno" id="dengue_serialno">
                </td>				
            </tr>
            <tr><td height=3></td></tr>
            <tr>
                <td align="left" width="35%" class="bareBold" style="padding-right: 15px;">Date Extracted&nbsp;:</td>
                <td align=left>
                    <input type="text" class="gridInput" style="width:100%;" name="dengue_extractdate" id="dengue_extractdate">
                </td>				
            </tr>
            <tr><td height=3></td></tr>
            <tr>
                <td align="left" width="35%" class="bareBold" style="padding-right: 15px;">Time Extracted&nbsp;:</td>
                <td align=left>
        
                    <input type="text" class="gridInput" style="width:100%;" name="dengue_extracttime" id="dengue_extracttime" readonly>

                </td>				
            </tr>
            <tr><td height=3></td></tr>               
            <tr>
                <td align="left" width="35%" class="bareBold" style="padding-right: 15px;">Extracted By&nbsp;:</td>
                <td align=left>
                    <input type="text" class="gridInput" style="width:100%;" name="dengue_extractby" id="dengue_extractby" readonly>
                </td>				
            </tr>
            <tr><td height=3></td></tr>
            <tr>
                <td align="left" width="35%" class="bareBold" style="padding-right: 15px;">Phleb/Imaging Site&nbsp;:</td>
                <td align=left>
                    <select class="gridInput" style="width:100%;" name="dengue_location" id="dengue_location">
                    <?php
                            $iun = $o->dbquery("select id,location from lab_locations;");
                            while(list($aa,$ab) = $iun->fetch_array()) {
                                echo "<option value='$aa'>$ab</option>";
                            }
                        ?>
                    </select>
                </td>				
            </tr>
        </table>                  
        <table width=100% cellspacing=0 cellpadding=0><tr><td width=100% class=gridHead align=center>RESULT DETAILS</td></tr></table>
        <table width=100% cellpadding=0 cellspacing=0 style="border: 1px solid #cdcdcd; padding: 10px;">
            <tr>
                <td align="left" width="35%"  class="bareBold" style="padding-right: 15px;">Result Date&nbsp;:</td>
                <td align=left>
                    <input class="gridInput" style="width:100%;" type=text name="dengue_date" id="dengue_date" value="<?php echo date('m/d/Y'); ?>">
                </td>				
            </tr>
            <tr><td height=3></td></tr>
            <tr>
                <td align="left" width="35%" class="bareBold" style="padding-right: 15px;">Dengue&nbsp;NS1&nbsp;AG&nbsp;:</td>
                <td align=left>
                <select name="dengue_ag" id="dengue_ag" class="gridInput" style="width:100%;">
                    <?php $dengue_ag = $o->getArray("select * from lab_dengue where so_no = '$a[myso]' and serialno = '$a[serialno]' and branch = '$_SESSION[branchid]';"); ?>
                        <option value="POSITIVE" <?php if($dengue_ag['dengue_ag'] == 'POSITIVE') { echo "selected"; } ?>>POSITIVE</option>
                        <option value="NEGATIVE" <?php if($dengue_ag['dengue_ag'] == 'NEGATIVE') { echo "selected"; } ?>>NEGATIVE</option>
                    </select>
                </td>				
            </tr>
            <tr><td height=3></td></tr> 
            <tr>
                <td align="left" width="35%" class="bareBold" style="padding-right: 15px;">Dengue&nbsp;IgG&nbsp;:</td>
                <td align=left>
                <select name="dengue_igg" id="dengue_igg" class="gridInput" style="width:100%;">
                    <?php $dengue_igg = $o->getArray("select * from lab_dengue where so_no = '$a[myso]' and serialno = '$a[serialno]' and branch = '$_SESSION[branchid]';"); ?>
                        <option value="POSITIVE" <?php if($dengue_igg['dengue_igg'] == 'POSITIVE') { echo "selected"; } ?>>POSITIVE</option>
                        <option value="NEGATIVE" <?php if($dengue_igg['dengue_igg'] == 'NEGATIVE') { echo "selected"; } ?>>NEGATIVE</option>
                    </select>
                </td>				
            </tr>
            <tr><td height=3></td></tr>
            <tr>
            <td align="left" width="35%" class="bareBold" style="padding-right: 15px;">Dengue&nbsp;IgM&nbsp;:</td>
                <td align=left>                 
                    <select name="dengue_igm" id="dengue_igm" class="gridInput" style="width:100%;">
                    <?php $dengue_igm = $o->getArray("select * from lab_dengue where so_no = '$a[myso]' and serialno = '$a[serialno]' and branch = '$_SESSION[branchid]';"); ?>
                        <option value="POSITIVE" <?php if($dengue_igm['dengue_igm'] == 'POSITIVE') { echo "selected"; } ?>>POSITIVE</option>
                        <option value="NEGATIVE" <?php if($dengue_igm['dengue_igm'] == 'NEGATIVE') { echo "selected"; } ?>>NEGATIVE</option>
                    </select>
                </td>				
            </tr>
            <tr><td height=3></td></tr>
        </table>
    </form>
</div>

<div id="vitals" style="display: none;">
	<form name="frmVitals" id="frmVitals">
		<table width=100% cellpadding=0 cellspacing=0 style="boder-collpase: collapse;">
            <tr><td colspan=8 align=center><img src="images/doc-header.jpg" width=85% height=85% align=absmiddle /></td></tr>
			<tr>
				<td colspan=4 align=center class=bebottom>
					<input type="radio" id="pe_type" name="pe_type" value="APE">&nbsp;<span class="spadix-l">Annual Physical Examination</span>
				</td>
				<td colspan=4 align=center class=bebottom>
					<input type="radio" id="type" name="type" value="PE">&nbsp;<span class="spadix-l">Pre-Employment Requirements</span>
				</td>
			</tr>
			<tr><td height=4></td></tr>
			<tr>
				<td width=8% class="bebottom" >Last Name :</td>
				<td width=17% class="bebottom">	
					<input type="text" name="pe_lname" id="pe_lname" style="border: none; font-size: 11px; font-weight: bold;">
				</td>
				<td width=8% class="bebottom">First Name :</td>
				<td width=17% class="bebottom">	
					<input type="text" name="pe_fname" id="pe_fname" style="border: none; font-size: 11px; font-weight: bold;" >
				</td>
				<td width=8% class="bebottom">Middle Name :</td>
				<td width=17% class="bebottom">	
					<input type="text" name="pe_mname" id="pe_mname" style="border: none; font-size: 11px; font-weight: bold;">
				</td>
				<td width=8% class="bebottom">Date :</td>
				<td width=17% class="bebottom">	
					<input type="text" name="pe_date" id="pe_date" style="border: none; font-size: 11px; font-weight: bold;" value="<?php echo date('m/d/Y'); ?>">
				</td>
			</tr>
            <tr>
				<td class="bebottom" >Address :</td>
				<td class="bebottom">	
					<input type="text" name="pe_address" id="pe_address" style="border: none; font-size: 11px;width: 98%; font-weight: bold;">
				</td>
				<td class="bebottom">Age :</td>
				<td class="bebottom">	
					<input type="text" name="pe_age" id="pe_age" style="border: none; font-size: 11px;width: 98%; font-weight: bold;" >
				</td>
				<td class="bebottom">Civil Status :</td>
				<td class="bebottom">	
					<input type="text" name="pe_cstatus" id="pe_cstatus" style="border: none; font-size: 11px; width: 98%; font-weight: bold;">
				</td>
				<td class="bebottom">Gender :</td>
				<td class="bebottom">	
					<input type="text" name="pe_gender" id="pe_gender" style="border: none; font-size: 11px; width: 98%; font-weight: bold;">
				</td>
			</tr>
            <tr>
				<td class="bebottom" >Place of Birth :</td>
				<td class="bebottom">	
					<input type="text" name="pe_pob" id="pe_pob" style="border: none; font-size: 11px; width: 98%; font-weight: bold;" >
				</td>
				<td class="bebottom">Date of Birth :</td>
				<td class="bebottom">	
					<input type="text" name="pe_dob" id="pe_dob" style="border: none; font-size: 11px; width: 98%; font-weight: bold;" >
				</td>
				<td class="bebottom">Insurance :</td>
				<td class="bebottom" colspan=3>	
					<input type="text" name="pe_insurance" id="pe_insurance" style="border: none; font-size: 11px; width: 98%; font-weight: bold;">
				</td>
			</tr>
            <tr>
				<td class="bebottom" >Occupation :</td>
				<td class="bebottom">	
					<input type="text" name="pe_occ" id="pe_occ" style="border: none; font-size: 11px; width: 98%;" >
				</td>
				<td class="bebottom">Company :</td>
				<td class="bebottom">	
					<input type="text" name="pe_comp" id="pe_comp" style="border: none; font-size: 11px; width: 98%;" >
				</td>
				<td class="bebottom">Tel/Mobile # :</td>
				<td class="bebottom" colspan=3>	
					<input type="text" name="pe_contact" id="pe_contact" style="border: none; font-size: 11px; width: 98%;">
				</td>
			</tr>
		</table>
        <table width=100% cellpadding=5><tr><td align=center><span style="font-size: 10pt; font-weight: bold;">PHYSICAL EXAMINATION</span></td></tr></table>
        <table width=100% cellspacing=0 cellpadding=3>
            <tr>
                <td class="spandix-l" align=left colspan=3>
                    Temp: <input type="text" name="pe_temp" id="pe_temp" style="border-top: none; border-left: none; border-right: none; border-bottom: 1px solid black; font-size: 11px; width: 80px;font-weight: bold;" ><sup>0</sup>C&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    PR:  <input type="text" name="pe_pr" id="pe_pr" style="border-top: none; border-left: none; border-right: none; border-bottom: 1px solid black; font-size: 11px; width: 80px;font-weight: bold;" >bpm&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    RR:  <input type="text" name="pe_rr" id="pe_rr" style="border-top: none; border-left: none; border-right: none; border-bottom: 1px solid black; font-size: 11px; width: 80px;font-weight: bold;" >bpm&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    BP:  <input type="text" name="pe_bp" id="pe_bp" style="border-top: none; border-left: none; border-right: none; border-bottom: 1px solid black; font-size: 11px; width: 80px;font-weight: bold;" >mm/HG&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    Ht:  <input type="text" name="pe_ht" id="pe_ht" style="border-top: none; border-left: none; border-right: none; border-bottom: 1px solid black; font-size: 11px; width: 80px;font-weight: bold;" >cm&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
                    Wt:  <input type="text" name="pe_wt" id="pe_wt" style="border-top: none; border-left: none; border-right: none; border-bottom: 1px solid black; font-size: 11px; width: 80px;font-weight: bold;" >kgs    
               </td>
            </tr>
            <tr>
                <td class="spandix-l" align=left>
                    Visual Acuity: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    Right Eye:  <input type="text" name="pe_lefteye" id="pe_lefteye" style="border-top: none; border-left: none; border-right: none; border-bottom: 1px solid black; font-size: 11px; width: 80px;font-weight: bold;" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    Left Eye:  <input type="text" name="pe_righteye" id="pe_righteye" style="border-top: none; border-left: none; border-right: none; border-bottom: 1px solid black; font-size: 11px; width: 80px;font-weight: bold;" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    BMI:  <input type="text" name="pe_bmi" id="pe_bmi" style="border-top: none; border-left: none; border-right: none; border-bottom: 1px solid black; font-size: 11px; width: 80px;font-weight: bold;" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
               </td>
               <td class="spandix-l"d><input type=radio name="pe_bmitype" id="pe_bmitype" value="Underweight">&nbsp;Underweight</td>
               <td class="spandix-l"><input type=radio name="pe_bmitype" id="pe_bmitype" value="Overweight">&nbsp;Overweight</td>
            </tr> 
            <tr>
                <td class="spandix-l" align=center>
                    
                </td>
               <td class="spandix-l" ><input type=radio name="pe_bmitype" id="pe_bmitype" value="Normal">&nbsp;Normal Weight</td>
               <td class="spandix-l"><input type=radio name="pe_bmitype" id="pe_bmitype" value="Obese">&nbsp;Obese</td>
            </tr>                    
        </table>
        <table width=100% cellpadding=5><tr><td align=center><span style="font-size: 10pt; font-weight: bold;">MEDICAL HISTORY</span></td></tr></table>
        <table width=100% cellpadding=3>
            <tr>
                <td width=12% class="spandix-l" valign=top>Past Medical History :</td>
                <td align=right>
                    <table width=100% cellpadding=2 cellspacing=0>
                        <tr>
                            <td width=33% valign=top>
                                <?php
                                    $medh1 = $o->dbquery("select id, history from options_medicalhistory order by id limit 0,10");
                                    while($medh1_row = $medh1->fetch_array()) {
                                        echo '<input type="checkbox" name="medhistory[]" id="medhistory[]" value="'.$medh1_row[0].'">&nbsp;&nbsp;<span class="spandix-l">'.$medh1_row[1].'</span><br/>';
                                    }
                                ?>
                            </td>
                                      
                            <td width=33% valign=top>
                                 <?php
                                    $medh2 = $o->dbquery("select id, history from options_medicalhistory order by id limit 10,10");
                                    while($medh2_row = $medh2->fetch_array()) {
                                        echo '<input type="checkbox" name="medhistory[]" id="medhistory[]" value="'.$medh2_row[0].'">&nbsp;&nbsp;<span class="spandix-l">'.$medh2_row[1].'</span><br/>';
                                    }
                                ?>      
                            </td>

                            <td width=33% valign=top>
                                <?php
                                    $medh3 = $o->dbquery("select id, history from options_medicalhistory order by id limit 20,10");
                                    while($medh3_row = $medh3->fetch_array()) {
                                        echo '<input type="checkbox" name="medhistory[]" id="medhistory[]" value="'.$medh3_row[0].'">&nbsp;&nbsp;<span class="spandix-l">'.$medh3_row[1].'</span><br/>';
                                    }
                                ?>       
                            </td>
                        </tr>

                    </table>
                </td>
            </tr>
            <tr>
                <td class="spandix-l">Family History :</td>
                <td align=right><input type="text" name="pe_famhistory" id="pe_famhistory" style="border-top: none; border-left: none; border-right: none; border-bottom: 1px solid black; font-size: 11px; width: 98%;font-weight: bold;" ></td>
            </tr>
            <tr>
                <td class="spandix-l">Previous Hospitalization :</td>
                <td align=right><input type="text" name="pe_hospitalization" id="pe_hospitalization" style="border-top: none; border-left: none; border-right: none; border-bottom: 1px solid black; font-size: 11px; width: 98%;font-weight: bold;" ></td>
            </tr>
            <tr>
                <td colspan=2 class="spandix-l">
                    Menstrual History: <input type="text" name="pe_menshistory" id="pe_menshistory" style="border-top: none; border-left: none; border-right: none; border-bottom: 1px solid black; font-size: 11px; width: 80px;font-weight: bold;" >y.o&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    Parity:  <input type="text" name="pe_parity" id="pe_parity" style="border-top: none; border-left: none; border-right: none; border-bottom: 1px solid black; font-size: 11px; width: 80px;font-weight: bold;" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    LMP:  <input type="text" name="pe_lmp" id="pe_lmp" style="border-top: none; border-left: none; border-right: none; border-bottom: 1px solid black; font-size: 11px; width: 80px;font-weight: bold;" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    Contraceptive Use:  <input type="text" name="pe_contra" id="pe_contra" style="border-top: none; border-left: none; border-right: none; border-bottom: 1px solid black; font-size: 11px; width: 160px;font-weight: bold;" >      
                </td>
            </tr>
        </table>
        <table width=100% cellpadding=5 cellspacing=0 style="border-collapse: collapse; font-size: 11px;">
            <tr>
                <td width=15% align=center style="border: 1px solid black; font-weight: bold;">Review of Systems</td>
                <td width=8% align=center style="border: 1px solid black; font-weight: bold;">Normal</td>
                <td width=27% align=center style="border: 1px solid black; font-weight: bold;">Findings</td>
                <td width=15% align=center style="border: 1px solid black; font-weight: bold;">Review of Systems</td>
                <td width=8% align=center style="border: 1px solid black; font-weight: bold;">Normal</td>
                <td width=27% align=center style="border: 1px solid black; font-weight: bold;">Findings</td>
            </tr>
            <tr>
                <td style="border: 1px solid black;">Head & Scalp</td>
                <td style="border: 1px solid black;" align=center><input type="checkbox" name="pe_hs_normal" id="pe_hs_normal" value="Y"></td>
                <td style="border: 1px solid black;"><input type="text" style="width: 100%; border: none;" name="pe_hs_findings" id="pe_hs_findings"></td>
                <td style="border: 1px solid black;">Lungs</td>
                <td style="border: 1px solid black;" align=center><input type="checkbox" name="pe_lungs_normal" id="pe_lungs_normal" value="Y"></td>
                <td style="border: 1px solid black;"><input type="text" style="width: 100%; border: none;" name="pe_lungs_findings" id="pe_lungs_findings"></td>
            </tr>
            <tr>
                <td style="border: 1px solid black;">Eyes & Ears</td>
                <td style="border: 1px solid black;" align=center><input type="checkbox" name="pe_ee_normal" id="pe_ee_normal" value="Y"></td>
                <td style="border: 1px solid black;"><input type="text" style="width: 100%; border: none;" name="pe_ee_findings" id="pe_ee_findings"></td>
                <td style="border: 1px solid black;">Heart</td>
                <td style="border: 1px solid black;" align=center><input type="checkbox" name="pe_heart_normal" id="pe_heart_normal" value="Y"></td>
                <td style="border: 1px solid black;"><input type="text" style="width: 100%; border: none;" name="pe_heart_findings" id="pe_heart_findings"></td>
            </tr>
            <tr>
                <td style="border: 1px solid black;">Skin/Allergy</td>
                <td style="border: 1px solid black;" align=center><input type="checkbox" name="pe_sa_normal" id="pe_sa_normal" value="Y"></td>
                <td style="border: 1px solid black;"><input type="text" style="width: 100%; border: none;" name="pe_sa_findings" id="pe_sa_findings"></td>
                <td style="border: 1px solid black;">Abdomen</td>
                <td style="border: 1px solid black;" align=center><input type="checkbox" name="pe_abdomen_normal" id="pe_abdomen_normal" value="Y"></td>
                <td style="border: 1px solid black;"><input type="text" style="width: 100%; border: none;" name="pe_abdomen_findings" id="pe_abdomen_findings"></td>
            </tr>
            <tr>
                <td style="border: 1px solid black;">Nose/Sinuses</td>
                <td style="border: 1px solid black;" align=center><input type="checkbox" name="pe_nose_normal" id="pe_nose_normal" value="Y"></td>
                <td style="border: 1px solid black;"><input type="text" style="width: 100%; border: none;" name="pe_nose_findings" id="pe_nose_findings"></td>
                <td style="border: 1px solid black;">Genitals</td>
                <td style="border: 1px solid black;" align=center><input type="checkbox" name="pe_genitals_normal" id="pe_genitals_normal" value="Y"></td>
                <td style="border: 1px solid black;"><input type="text" style="width: 100%; border: none;" name="pe_genitals_findings" id="pe_genitals_findings"></td>
            </tr>
            <tr>
                <td style="border: 1px solid black;">Mouth/Teeth/Tongue</td>
                <td style="border: 1px solid black;" align=center><input type="checkbox" name="pe_mouth_normal" id="pe_mouth_normal" value="Y"></td>
                <td style="border: 1px solid black;"><input type="text" style="width: 100%; border: none;" name="pe_mouth_findings" id="pe_mouth_findings"></td>
                <td style="border: 1px solid black;">Extremities</td>
                <td style="border: 1px solid black;" align=center><input type="checkbox" name="pe_extr_normal" id="pe_extr_normal" value="Y"></td>
                <td style="border: 1px solid black;"><input type="text" style="width: 100%; border: none;" name="pe_extr_findings" id="pe_extr_findings"></td>
            </tr>
            <tr>
                <td style="border: 1px solid black;">Neck/Nodes</td>
                <td style="border: 1px solid black;" align=center><input type="checkbox" name="pe_neck_normal" id="pe_neck_normal" value="Y"></td>
                <td style="border: 1px solid black;"><input type="text" style="width: 100%; border: none;" name="pe_neck_findings" id="pe_neck_findings"></td>
                <td style="border: 1px solid black;">Reflexes</td>
                <td style="border: 1px solid black;" align=center><input type="checkbox" name="pe_ref_normal" id="pe_ref_normal" value="Y"></td>
                <td style="border: 1px solid black;"><input type="text" style="width: 100%; border: none;" name="pe_ref_findings" id="pe_ref_findings"></td>
            </tr>
            <tr>
                <td style="border: 1px solid black;">Check/Breast</td>
                <td style="border: 1px solid black;" align=center><input type="checkbox" name="pe_check_normal" id="pe_check_normal" value="Y"></td>
                <td style="border: 1px solid black;"><input type="text" style="width: 100%; border: none;" name="pe_check_findings" id="pe_check_findings"></td>
                <td style="border: 1px solid black;">BPE</td>
                <td style="border: 1px solid black;" align=center><input type="checkbox" name="pe_bpe_normal" id="pe_bpe_normal" value="Y"></td>
                <td style="border: 1px solid black;"><input type="text" style="width: 100%; border: none;" name="pe_bpe_findings" id="pe_bpe_findings"></td>
            </tr>
            <tr>
                <td style="border: 1px solid black;"></td>
                <td style="border: 1px solid black;"></td>
                <td style="border: 1px solid black;"></td>
                <td style="border: 1px solid black;">Rectal</td>
                <td style="border: 1px solid black;" align=center><input type="checkbox" name="pe_rect_normal" id="pe_rect_normal" value="Y"></td>
                <td style="border: 1px solid black;"><input type="text" style="width: 100%; border: none;" name="pe_rect_findings" id="pe_rect_findings"></td>
            </tr>
        </table>
        <table width=100% cellpadding=5 cellspacing=0 style="border-collapse: collapse; font-size: 11px; margin-top: 10px;">
            <tr>
                <td width=15% align=center style="border: 1px solid black; font-weight: bold;">Laboratory</td>
                <td width=8% align=center style="border: 1px solid black; font-weight: bold;">Normal</td>
                <td width=27% align=center style="border: 1px solid black; font-weight: bold;">Findings</td>
                <td width=15% align=center style="border: 1px solid black; font-weight: bold;">Review of Systems</td>
                <td width=8% align=center style="border: 1px solid black; font-weight: bold;">Normal</td>
                <td width=27% align=center style="border: 1px solid black; font-weight: bold;">Findings</td>
            </tr>
            <tr>
                <td style="border: 1px solid black;">Chest X-Ray</td>
                <td style="border: 1px solid black;" align=center><input type="checkbox" name="pe_chest_normal" id="pe_chest_normal" value="Y"></td>
                <td style="border: 1px solid black;"><input type="text" style="width: 100%; border: none;" name="pe_chest_findings" id="pe_chest_findings"></td>
                <td style="border: 1px solid black;">ECG</td>
                <td style="border: 1px solid black;" align=center><input type="checkbox" name="pe_ecg_normal" id="pe_ecg_normal" value="Y"></td>
                <td style="border: 1px solid black;"><input type="text" style="width: 100%; border: none;" name="pe_ecg_findings" id="pe_ecg_findings"></td>
            </tr>
            <tr>
                <td style="border: 1px solid black;">CBC</td>
                <td style="border: 1px solid black;" align=center><input type="checkbox" name="pe_check_normal" id="pe_cbc_normal" value="Y"></td>
                <td style="border: 1px solid black;"><input type="text" style="width: 100%; border: none;" name="pe_cbc_findings" id="pe_cbc_findings"></td>
                <td style="border: 1px solid black;">OTHER PROCEDURES:</td>
                <td style="border: 1px solid black;"></td>
                <td style="border: 1px solid black;"></td>
            </tr>
            <tr>
                <td style="border: 1px solid black;">Urinalysis</td>
                <td style="border: 1px solid black;" align=center><input type="checkbox" name="pe_ua_normal" id="pe_ua_normal" value="Y"></td>
                <td style="border: 1px solid black;"><input type="text" style="width: 100%; border: none;" name="pe_ua_findings" id="pe_ua_findings"></td>
                <td style="border: 1px solid black;"><input type="text" style="width: 100%; border: none;" name="pe_others1" id="pe_others1"></td>
                <td style="border: 1px solid black;" align=center><input type="checkbox" name="pe_others1_normal" id="pe_others1_normal" value="Y"></td>
                <td style="border: 1px solid black;"><input type="text" style="width: 100%; border: none;" name="pe_others1_findings" id="pe_others1_findings"></td>
            </tr>
            <tr>
                <td style="border: 1px solid black;">Fecalysis</td>
                <td style="border: 1px solid black;" align=center><input type="checkbox" name="pe_se_normal" id="pe_se_normal" value="Y"></td>
                <td style="border: 1px solid black;"><input type="text" style="width: 100%; border: none;" name="pe_se_findings" id="pe_se_findings"></td>
                <td style="border: 1px solid black;"><input type="text" style="width: 100%; border: none;" name="pe_others2" id="pe_others2"></td>
                <td style="border: 1px solid black;" align=center><input type="checkbox" name="pe_others2_normal" id="pe_others2_normal" value="Y"></td>
                <td style="border: 1px solid black;"><input type="text" style="width: 100%; border: none;" name="pe_others2_findings" id="pe_others2_findings"></td>
            </tr>
            <tr>
                <td style="border: 1px solid black;">Drug Test</td>
                <td style="border: 1px solid black;" align=center><input type="checkbox" name="pe_dt_normal" id="pe_dt_normal" value="Y"></td>
                <td style="border: 1px solid black;"><input type="text" style="width: 100%; border: none;" name="pe_dt_findings" id="pe_dt_findings"></td>
                <td style="border: 1px solid black;"><input type="text" style="width: 100%; border: none;" name="pe_others3" id="pe_others3"></td>
                <td style="border: 1px solid black;" align=center><input type="checkbox" name="pe_others3_normal" id="pe_others3_normal" value="Y"></td>
                <td style="border: 1px solid black;"><input type="text" style="width: 100%; border: none;" name="pe_others3_findings" id="pe_others3_findings"></td>
            </tr>
        </table>
        <table width=100% cellpadding=5 cellspacing=0>
            <tr><td colspan=2 class="spandix-l">I Hereby Certify that I have examined and found the employee to be <select class=gridInput name="pe_fit" id="pe_fit"><option value="FIT">FIT</option><option value="UNFIT">UNFIT</option></select> for employment.<br/><b>CLASSIFICATION:</b></td></tr>                
            <tr>
                <td width=20% style="padding-left: 5%;" class="spandix-l"><input type="radio" name="pe_class" id="pe_class" value="A">&nbsp;&nbsp;CLASS A</td>
                <td width=80% class="spandix-l">
                    Physically fit for all types of work
                   </td>
            </tr>
            <tr>
                <td width=20% style="padding-left: 5%;" class="spandix-l" valign=top><input type="radio" name="pe_class" id="pe_class" value="B">&nbsp;&nbsp;CLASS B</td>
                <td width=80% class="spandix-l">Physically fit for all types of work
                <br/>
                    Has Minor ailment/defect. Easily curable or offers no handicap to applied.
                    <br/>
                    <input type="radio" name="pe_class_b" id="pe_class_b" value="1">&nbsp;&nbsp;Needs Treatment Correction : &nbsp;&nbsp;&nbsp;&nbsp;<input type="text" name="pe_class_b_remarks1" id="pe_class_b_remarks1" style="border-top: none; border-left: none; border-right: none; border-bottom: 1px solid black; font-size: 11px; width:370px;" >
                    <br/>
                    <input type="radio" name="pe_class_b" id="pe_class_b" value="2">&nbsp;&nbsp;Treatment Optional For : &nbsp;&nbsp;<input type="text" name="pe_class_b_remarks_2" id="pe_class_b_remarks_2" style="border-top: none; border-left: none; border-right: none; border-bottom: 1px solid black; font-size: 11px; width: 400px;" >
                
                </td>
            </tr>
            <tr>
                <td width=20% style="padding-left: 5%;" class="spandix-l" valign=top><input type="radio" name="pe_class" id="pe_class" value="C">&nbsp;&nbsp;CLASS C</td>
                <td width=80% class="spandix-l">Physically fit for less strenous type of work. Has minor ailments/defects.
                <br/>
                    Easily curable or offers no handicap to job applied.
                    <br/>
                    <input type="radio" name="pe_class_c" id="pe_class_c" value="1">&nbsp;&nbsp;Needs Treatment Correction : &nbsp;&nbsp;&nbsp;&nbsp;<input type="text" name="pe_class_c_remarks1" id="pe_class_c_remarks1" style="border-top: none; border-left: none; border-right: none; border-bottom: 1px solid black; font-size: 11px; width:370px;" >
                    <br/>
                    <input type="radio" name="pe_class_c" id="pe_class_c" value="2">&nbsp;&nbsp;Treatment Optional For : &nbsp;&nbsp;<input type="text" name="pe_class_c_remarks_2" id="pe_class_c_remarks_2" style="border-top: none; border-left: none; border-right: none; border-bottom: 1px solid black; font-size: 11px; width: 400px;" >
                
                </td>
            </tr>
            <tr>
                <td width=20% style="padding-left: 5%;" class="spandix-l"><input type="radio" name="pe_class" id="pe_class" value="D">&nbsp;&nbsp;CLASS D</td>
                <td width=80% class="spandix-l">
                    Employment at the risk and discretion of the management
                </td>
            </tr>
            <tr>
                <td width=20% style="padding-left: 5%;" class="spandix-l"><input type="radio" name="pe_class" id="pe_class" value="E">&nbsp;&nbsp;CLASS E</td>
                <td width=80% class="spandix-l">
                    Unfit for Employment
                </td>
            </tr>
            <tr>
                <td width=20% style="padding-left: 5%;" class="spandix-l"><input type="radio" name="pe_class" id="pe_class" value="PENDING">&nbsp;&nbsp;PENDING</td>
                <td width=80% class="spandix-l">
                    For further evaluation of: &nbsp;&nbsp;&nbsp;<input type="text" name="pe_eval_remarks" id="pe_eval_remarks" style="border-top: none; border-left: none; border-right: none; border-bottom: 1px solid black; font-size: 11px; width: 380px;" >
                </td>
            </tr>
            <tr><td colspan=2 class="spandix-l">Remarks: <input type="text" name="pe_remarks" id="pe_remarks" style="border-top: none; border-left: none; border-right: none; border-bottom: 1px solid black; font-size: 11px; width: 70%;" ></td></tr>
        </table>
	</form>
</div>

<div id="preselectSO" style="display: none;">

	<table border="0" cellpadding="0" cellspacing="0" width=100%>
		<tr><td height=4></td></tr>
		<tr>
			<td width=50%><span class="spandix-l">Mobile Service Order # :</span></td>
			<td>
				<?php
					if($_SESSION['so_no'] != '') {
						echo '<input type="text" class="gridInput" style="width: 100%" name="registrationSoNo" id="registrationSoNo" value="'.str_pad($_SESSION['so_no'],6,0,STR_PAD_LEFT).'" readonly>';

					} else {

						echo '<select name="registrationSoNo" id="registrationSoNo" class="gridInput" style="width: 100%; font-size: 10px;">';
						$mobileSoQuery = $o->dbquery("select distinct cso_no from cso_header order by cso_no desc;");
						while($mRow = $mobileSoQuery->fetch_array()) {
							echo '<option value = "'.$mRow[0].'">'.str_pad($mRow[0],6,0,STR_PAD_LEFT).'</option>';
						}

						echo '</select>';
					}
				?>
			</td>
		</tr>
	</table>

</div>

<div id="soSummary" style="display: none;">

	<table border="0" cellpadding="0" cellspacing="0" width=100%>
		<tr><td height=4></td></tr>
		<tr>
			<td width=50%><span class="spandix-l">Mobile Service Order # :</span></td>
			<td>
				<?php
					if($_SESSION['so_no'] != '') {
						echo '<input type="text" class="gridInput" style="width: 100%" name="summarySoNo" id="summarySoNo" value="'.str_pad($_SESSION['so_no'],6,0,STR_PAD_LEFT).'" readonly>';

					} else {

						echo '<select name="summarySoNo" id="summarySoNo" class="gridInput" style="width: 100%; font-size: 10px;">';
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
            <td class="spandix-l">Date :</td>
            <td>
                <input type="text" class="gridInput" style="width: 100%;" id="summaryDate" name="summaryDate" value = "" placeholder = "Specify if applciable">
            </td>
        </tr>
        <tr><td height=2></td></tr>
        <tr>
            <td class="spandix-l">Shift (IF Applicable):</td>
            <td>
                <select class="gridInput" style="width: 100%; font-size: 11px;" id="summaryShift" name="summaryShift">
                    <option value="">- All -</option>
                    <option value="1">Day Shift</option>
                    <option value="2">Night Shift</option>
                </select>
            </td>
        </tr>
	</table>

</div>

<div id="mobileCensus" style="display: none;">

	<table border="0" cellpadding="0" cellspacing="0" width=100%>
		<tr><td height=4></td></tr>
		<tr>
			<td width=50%><span class="spandix-l">Mobile Service Order # :</span></td>
			<td>
				<?php
					if($_SESSION['so_no'] != '') {
						echo '<input type="text" class="gridInput" style="width: 100%" name="censusSoNo" id="censusSoNo" value="'.str_pad($_SESSION['so_no'],6,0,STR_PAD_LEFT).'" readonly>';

					} else {

						echo '<select name="censusSoNo" id="censusSoNo" class="gridInput" style="width: 100%; font-size: 10px;" onChange="javascript: getPackageList(this.value);">
                                <option value=\'\'>Select Mobile SO Number</option>
                            ';
						$censusSoQuery = $o->dbquery("select distinct cso_no from cso_header order by cso_no desc;");
						while($cRow = $censusSoQuery->fetch_array()) {
							echo '<option value = "'.$cRow[0].'">'.str_pad($cRow[0],6,0,STR_PAD_LEFT).'</option>';
						}

						echo '</select>';
					}
				?>
			</td>
		</tr>
        <tr><td height=3></td></tr>
        <tr>
			<td class="spandix-l">Date</td>
			<td><input type="text" id="censusDate" name="censusDate" class="gridInput" style="width: 100%;" value="" placeholder="Specify Date if Applicable"></td>
		</tr>
        <tr><td height=3></td></tr>
        <tr>
			<td class="spandix-l">Shift :</td>
			<td>
				<select name="censusShift" id="censusShift" class="gridInput" style="width: 100%; font-size: 11px;">
					<option value=''>Not Applicable</option>
					<option value='1'>Day Shift</option>
                    <option value='2'>Night Shift</option>
				</select>
			</td>
		</tr>
        <tr><td height=3></td></tr>
        <tr>
			<td class="spandix-l">Package :</td>
			<td>
				<select name="censusPackage" id="censusPackage" class="gridInput" style="width: 100%; font-size: 11px;">
					<option value=''>All</option>
					<?php
                        if($_SESSION['so_no'] != '') {
                            $cpQuery = $o->dbquery("select distinct `code`,`description` from cso_details where cso_no = '$_SESSION[so_no]' order by `description` asc;");
                            while($cpRow = $cpQuery->fetch_array()) {
                                echo "<option value='$cpRow[0]'>$cpRow[1]</option>";
                            }
                        }
                    ?>
				</select>
			</td>
		</tr>
        <tr><td height=3></td></tr>
        <tr>
			<td class="spandix-l">Status :</td>
			<td>
				<select name="censusStatus" id="censusStatus" class="gridInput" style="width: 100%; font-size: 11px;">
                    <option value=''>- All -</option>
					<option value='1'>Completed</option>
					<option value='2'>For Completion</option>
                    <option value='3'>No Show</option>
				</select>
			</td>
		</tr>
	</table>

</div>

<div id="mobileMaxCensus" style="display: none;">

	<table border="0" cellpadding="0" cellspacing="0" width=100%>
		<tr><td height=4></td></tr>
		<tr>
			<td width=50%><span class="spandix-l">Maxicare Service Order # :</span></td>
			<td>
				<?php
					if($_SESSION['so_no'] != '') {
						echo '<input type="text" class="gridInput" style="width: 100%" name="censusMax" id="censusMax" value="'.str_pad($_SESSION['so_no'],6,0,STR_PAD_LEFT).'" readonly>';

					} else {

						echo '<select name="censusMax" id="censusMax" class="gridInput" style="width: 100%; font-size: 10px;">';
						$censusSoQuery = $o->dbquery("select distinct cso_no from cso_header order by cso_no desc;");
						while($cRow = $censusSoQuery->fetch_array()) {
							echo '<option value = "'.$cRow[0].'">'.str_pad($cRow[0],6,0,STR_PAD_LEFT).'</option>';
						}

						echo '</select>';
					}
				?>
			</td>
		</tr>
        <tr><td height=3></td></tr>
        <tr>
			<td width=35% class="spandix-l">Date :</td>
				<td>
					<input type="text" class="gridInput" placeholder="Leave blank to display all" style="width: 100%;" id="mobile_dt" name="mobile_dt" value = "<?php echo date('m/d/Y'); ?>">
				</td>
			</tr>
		</tr>
	</table>

</div>

<div id="processedSummary" style="display: none;">

	<table border="0" cellpadding="0" cellspacing="0" width=100%>
		<tr><td height=4></td></tr>
		<tr>
			<td width=50%><span class="spandix-l">Service Order # :</span></td>
			<td>
				<?php
					if($_SESSION['so_no'] != '') {
						echo '<input type="text" class="gridInput" style="width: 100%" name="proc_sono" id="proc_sono" value="'.str_pad($_SESSION['so_no'],6,0,STR_PAD_LEFT).'" readonly>';

					} else {

						echo '<select name="proc_sono" id="proc_sono" class="gridInput" style="width: 100%; font-size: 10px;" >';
						$censusSoQuery = $o->dbquery("select distinct cso_no from cso_header order by cso_no desc;");
						while($cRow = $censusSoQuery->fetch_array()) {
							echo '<option value = "'.$cRow[0].'">'.str_pad($cRow[0],6,0,STR_PAD_LEFT).'</option>';
						}

						echo '</select>';
					}
				?>
			</td>
		</tr>
        <tr><td height=3></td></tr>
        <tr>
			<td width=35% class="spandix-l">Date :</td>
				<td>
					<input type="text" class="gridInput" style="width: 100%;" id="proc_sodate" name="proc_sodate" value = "<?php echo date('m/d/Y'); ?>">
				</td>
			</tr>
		</tr>
        <tr><td height=3></td></tr>
        <tr>
			<td width=35% class="spandix-l">Shift :</td>
			<td>
				<select name="proc_shift" id="proc_shift" class="gridInput" style="width: 100%; font-size: 11px;">
					<option value=''>All Shifts</option>
					<option value='1'>Day Shift</option>
                    <option value='2'>Night Shift</option>
				</select>
			</td>
		</tr>
	</table>

</div>

<div id="preSelectXbookSummary" style="display: none;">

	<table border="0" cellpadding="0" cellspacing="0" width=100%>
		<tr><td height=4></td></tr>
		<tr>
			<td width=50%><span class="spandix-l">Mobile Service Order # :</span></td>
			<td>
				<?php
					if($_SESSION['so_no'] != '') {
						echo '<input type="text" class="gridInput" style="width: 100%" name="xBookSummary" id="xBookSummary" value="'.str_pad($_SESSION['so_no'],6,0,STR_PAD_LEFT).'" readonly>';

					} else {

						echo '<select name="xBookSummary" id="xBookSummary" class="xBookSummary" style="width: 100%; font-size: 10px;">';
						$mobileSoQuery = $o->dbquery("select distinct cso_no from cso_header order by cso_no desc;");
						while($mRow = $mobileSoQuery->fetch_array()) {
							echo '<option value = "'.$mRow[0].'">'.str_pad($mRow[0],6,0,STR_PAD_LEFT).'</option>';
						}

						echo '</select>';
					}
				?>
			</td>
		</tr>
        <tr><td height=3></td></tr>
        <tr>
			<td width=35% class="spandix-l">Result Date Coverage :</td>
			<td><input type="text" id="xraylog_dtf" name="xraylog_dtf" class="gridInput"  style="width: 100%;" value="<?php echo date('m/d/Y'); ?>"></td>
		</tr>
        <tr><td height=3></td></tr>
        <tr>
			<td width=35% class="spandix-l"></td>
			<td><input type="text" id="xraylog_dt2" name="xraylog_dt2" class="gridInput" style="width: 100%;" value="<?php echo date('m/d/Y'); ?>"></td>
		</tr>
        <tr><td height=3></td></tr>
        <tr>
			<td width=35% class="spandix-l">Shift :</td>
			<td>
				<select name="xraylog_shift" id="xraylog_shift" class="gridInput" style="width: 100%; font-size: 11px;" onChange = "javascript: if(this.value != '')  { $('#xraylog_dt2').val($('#xraylog_dtf').val()); }">
					<option value=''>Not Applicable</option>
					<option value='1'>Day Shift</option>
                    <option value='2'>Night Shift</option>
				</select>
			</td>
		</tr>
        <tr><td height=3></td></tr>
        <tr>
			<td width=35% class="spandix-l">Consultant :</td>
			<td>
				<select name="xraylog_consultant" id="xraylog_consultant" class="gridInput" style="width: 100%; font-size: 11px;">
					<option value=''>All Consultants</option>
					<?php
						$query = $o->dbquery("select id, fullname from options_doctors where id in ('1','2','3') order by fullname;");
						while($d = $query->fetch_array()) {
							echo "<option value='$d[0]'>$d[1]</option>";
						}
					?>			
				</select>
			</td>
		</tr>
        <tr><td height=3></td></tr>
        <tr>
			<td width=35% class="spandix-l">Result Type :</td>
			<td>
				<select name="xraylog_type" id="xraylog_type" class="gridInput" style="width: 100%; font-size: 11px;">
					<option value=''>All</option>
					<option value='1'>Normal</option>
					<option value='2'>With Findings</option>
				</select>
				</select>
			</td>
		</tr>
        <tr><td height=3></td></tr>
        <tr>
			<td width=35% class="spandix-l">Encoder :</td>
			<td>
				<select name="xraylog_encoder" id="xraylog_encoder" class="gridInput" style="width: 100%; font-size: 11px;">
					<option value=''>All Encoders</option>
					<?php
						$equery = $o->dbquery("SELECT emp_id, fullname FROM user_info WHERE role LIKE '%encod%' ORDER BY fullname;");
						while($e = $equery->fetch_array()) {
							echo "<option value='$e[0]'>$e[1]</option>";
						}
					?>			
				</select>
			</td>
		</tr>
        <tr><td height=3></td></tr>
        <tr>
			<td width=35% class="spandix-l">Sort By :</td>
			<td>
				<select name="xraylog_sort" id="xraylog_sort" class="gridInput" style="width: 100%; font-size: 11px;">
					<option value='1'>Patient Name</option>
					<option value='2'>By X-Ray No. (Ascending)</option>
					<option value='3'>By Company Name</option>
				</select>
				</select>
			</td>
		</tr>
	</table>

</div>

<div id="preSelectXTracker" style="display: none;">

	<table border="0" cellpadding="0" cellspacing="0" width=100%>
		<tr><td height=4></td></tr>
		<tr>
			<td width=50%><span class="spandix-l">Mobile Service Order # :</span></td>
			<td>
				<?php
					if($_SESSION['so_no'] != '') {
						echo '<input type="text" class="gridInput" style="width: 100%" name="xTracker" id="xTracker" value="'.str_pad($_SESSION['so_no'],6,0,STR_PAD_LEFT).'" readonly>';

					} else {

						echo '<select name="xTracker" id="xTracker" class="xTracker" style="width: 100%; font-size: 10px;">';
						$mobileSoQuery = $o->dbquery("select distinct cso_no from cso_header order by cso_no desc;");
						while($mRow = $mobileSoQuery->fetch_array()) {
							echo '<option value = "'.$mRow[0].'">'.str_pad($mRow[0],6,0,STR_PAD_LEFT).'</option>';
						}

						echo '</select>';
					}
				?>
			</td>
		</tr>
        <tr><td height=3></td></tr>
        <tr>
			<td width=35% class="spandix-l">Result Date Coverage :</td>
			<td><input type="text" id="xtracker_dtf" name="xtracker_dtf" class="gridInput"  style="width: 100%;" value="<?php echo date('m/d/Y'); ?>"></td>
		</tr>
        <tr><td height=3></td></tr>
        <tr>
			<td width=35% class="spandix-l"></td>
			<td><input type="text" id="xtracker_dt2" name="xtracker_dt2" class="gridInput" style="width: 100%;" value="<?php echo date('m/d/Y'); ?>"></td>
		</tr>
        <tr><td height=3></td></tr>
        <tr>
			<td width=35% class="spandix-l">Shift :</td>
			<td>
				<select name="xtracker_shift" id="xtracker_shift" class="gridInput" style="width: 100%; font-size: 11px;" onChange = "javascript: if(this.value != '')  { $('#xtracker_dt2').val($('#xtracker_dtf').val()); }">
					<option value=''>Not Applicable</option>
					<option value='1'>Day Shift</option>
                    <option value='2'>Night Shift</option>
				</select>
			</td>
		</tr>
        <tr><td height=3></td></tr>
        <tr>
			<td width=35% class="spandix-l">Consultant :</td>
			<td>
				<select name="xtracker_consultant" id="xtracker_consultant" class="gridInput" style="width: 100%; font-size: 11px;">
					<option value=''>All Consultants</option>
					<?php
						$query = $o->dbquery("select id, fullname from options_doctors where specialization in ('RADIOLOGIST') order by fullname;");
						while($d = $query->fetch_array()) {
							echo "<option value='$d[0]'>$d[1]</option>";
						}
					?>			
				</select>
			</td>
		</tr>
        <tr><td height=3></td></tr>
        <tr>
			<td width=35% class="spandix-l">Result Type :</td>
			<td>
				<select name="xtracker_type" id="xtracker_type" class="gridInput" style="width: 100%; font-size: 11px;">
					<option value=''>All</option>
					<option value='1'>Normal</option>
					<option value='2'>With Findings</option>
				</select>
				</select>
			</td>
		</tr>
        <tr><td height=3></td></tr>
        <tr>
			<td width=35% class="spandix-l">Encoder :</td>
			<td>
				<select name="xtracker_encoder" id="xtracker_encoder" class="gridInput" style="width: 100%; font-size: 11px;">
					<option value=''>All Encoders</option>
					<?php
						$equery = $o->dbquery("SELECT emp_id, fullname FROM user_info WHERE role LIKE '%encod%' ORDER BY fullname;");
						while($e = $equery->fetch_array()) {
							echo "<option value='$e[0]'>$e[1]</option>";
						}
					?>			
				</select>
			</td>
		</tr>
        <tr><td height=3></td></tr>
        <tr>
			<td width=35% class="spandix-l">Sort By :</td>
			<td>
				<select name="xtracker_sort" id="xtracker_sort" class="gridInput" style="width: 100%; font-size: 11px;">
					<option value='1'>Patient Name</option>
					<option value='2'>By X-Ray No. (Ascending)</option>
					<option value='3'>By Company Name</option>
				</select>
				</select>
			</td>
		</tr>
	</table>

</div>

<div id="userChangePass" style="display: none;">
	<form name="frmPass" id="frmPass">
		<input type="hidden" name="myUID" id="myUID" value="<?php echo $_SESSION['userid']; ?>">
		<table border="0" cellpadding="0" cellspacing="0" width=100%>
			<tr><td height=4></td></tr>
			<tr>
				<td width=35%><span class="spandix-l">New Password :</span></td>
				<td>
					<input type="password" id="pass1" class="nInput" style="width: 80%;"  />
				</td>
			</tr>
			<tr><td height=4></td></tr>
			<tr>
				<td width=35%><span class="spandix-l">Confirm New Password :</span></td>
				<td>
					<input type="password" id="pass2" class="nInput" style="width: 80%;" />
				</td>
			</tr>
			</table>
	</form>
</div>
