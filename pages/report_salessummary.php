<link href="../style.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css"/>
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.10/css/all.css"
      integrity="sha384-+d0P83n9kaQMCwj8F4RJB66tzIwOKmrdb46+porD/OvrJ+37WqIM7UoBtwHO6Nlg" crossorigin="anonymous">
<style type="text/css">
    <!--
    .style1 {
        color: #FFFFFF
    }

    .style2 {
        color: #006600;
        font-weight: bold;
    }

    -->
    .fas {
        color: #0f0f0f;
    }
    .fas:hover {
        color: #007CC4;
    }
    .glyphicon.glyphicon-print {
        color: #0f0f0f;
        font-size: 40px;
    }
    .glyphicon.glyphicon-print:hover {
        color: #007CC4;
    }
</style>

<script>
    $(function () {
        var yesterday = new Date();
        yesterday.setDate(yesterday.getDate() - 1);
        var dd = yesterday.getDate();
        var mm = yesterday.getMonth() + 1; //January is 0!

        var yyyy = yesterday.getFullYear();
        if (dd < 10) {
            dd = '0' + dd;
        }
        if (mm < 10) {
            mm = '0' + mm;
        }
        yesterday = mm + '/' + dd + '/' + yyyy;

        $('input[name="daterange"]').daterangepicker({
            opens: 'right',
            "maxDate": yesterday,
            autoUpdateInput: false,
            locale: {
                cancelLabel: 'Clear'
            }
        }, function (start, end, label) {
            console.log("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
        });

        $('input[name="date"]').daterangepicker({
            opens: 'right',
            "maxDate": yesterday,
            singleDatePicker: true,
            showDropdowns: true
        }, function (start, end, label) {
            console.log("A new date selection was made: " + start.format('YYYY-MM-DD'));
        });

        $('input[name="daterange"]').on('apply.daterangepicker', function (ev, picker) {
            $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
        });

        $('input[name="daterange"]').on('cancel.daterangepicker', function (ev, picker) {
            $(this).val('');
        });
    });
</script>

<div class='col-sm-12'>
    <div class="row form-row">
        <div class="col-sm-8 col-sm-offset-2 formdiv">
            <div class="row">
                <div class="col-sm-12">
                    <h3 style="text-transform: uppercase;" class="text-left">
                        Sales Summary Report
                    </h3>
                </div>
            </div>
            <form id="frmparameters" name="frmparameters" method="post" action="index.php?p=report_salessummary&a=s"
                  onSubmit="return CheckDateRange(this);">
                <div class="row">
                    <div class="col-sm-4">
                        <label for="cmbstore">Store</label>
                    </div>
                    <div class="col-sm-8">
                        <div class="input-group">
                        <span class="input-group-addon">
                            <input name="radStores" type="radio" value="store" <?php if ($radStores == "store") {
                                echo "checked='checked'";
                            } ?> onclick="Javascript: SetStoreFocus();"/>
                        </span>
                            <select name="cmbstore" class="form-control" id="cmbstore">
                                <?php
                                $result = GetStoresThatUserCanAccess($_SESSION["usrid"]);
                                while ($row = mysql_fetch_array($result)) {
                                    $output = "<option value='" . $row["strid"] . "'";
                                    if ($_SESSION["cmbstore"] == $row["strid"]) {
                                        $output = $output . " selected ";
                                    }
                                    $output = $output . ">" . $row["strname"] . "</option>";
                                    echo $output;
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row" style="margin-top: 5px;">
                    <div class="col-sm-4">
                        <label for="cmbstoregroup">Store Group</label>
                    </div>
                    <div class="col-sm-8">
                        <div class="input-group">
                        <span class="input-group-addon">
                            <input name="radStores" type="radio"
                                   value="storegroup" <?php if ($radStores == "storegroup") {
                                echo "checked='checked'";
                            } ?> onclick="Javascript: SetStoreGroupFocus();"/>
                        </span>
                            <select name="cmbstoregroup" class="form-control" id="cmbstoregroup">
                                <?php
                                $result = GetStoreGroupsThatUserCanAccess($_SESSION["usrid"]);
                                while ($row = mysql_fetch_array($result)) {
                                    $output = "<option value='" . $row["grpid"] . "'";
                                    if ($_SESSION["cmbstoregroup"] == $row["grpid"]) {
                                        $output = $output . " selected ";
                                    }
                                    $output = $output . ">" . $row["grpname"] . "</option>";
                                    echo $output;
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row" style="margin-top: 5px;">
                    <div class="col-sm-4"><label for="date">Specific Date</label></div>
                    <div class="col-sm-8">
                        <div class="input-group">
                        <span class="input-group-addon">
                            <input name="radDate" type="radio" value="date" <?php if ($radDate == "date") {
                                echo "checked='checked'";
                            } ?> onclick="Javascript: SetSpecificDateFocus();">
                        </span>
                            <input class="form-control" type="text" name="date" id="date"
                                   value="<?php echo $_SESSION['date'] ?>" placeholder="Specific Date"/>
                        </div><!-- /input-group -->
                    </div>
                </div>
                <div class="row" style="margin-top: 5px;">
                    <div class="col-sm-4"><label for="daterange">Date Range</label></div>
                    <div class="col-sm-8">
                        <div class="input-group">
                          <span class="input-group-addon">
                            <input name="radDate" type="radio" value="daterange"
                                   onclick="Javascript: SetSpecificDateRangeFocus();" <?php if ($radDate == "daterange") {
                                echo "checked='checked'";
                            } ?> />
                          </span>
                            <input class="form-control" type="text" name="daterange" id="daterange"
                                   value="<?php echo $_SESSION['daterange'] ?>" placeholder="Date Range"/>
                        </div><!-- /input-group -->
                    </div>
                </div>
                <div class="row" style="margin-top: 5px;">
                    <div class="col-sm-2" style="padding-right: 0px;">
                        <label for="cmbdayofweek">Day of Week</label>
                    </div>
                    <div class="col-sm-4">
                        <select name="cmbdayofweek" class="form-control" id="cmbdayofweek">
                            <option <?php if ($_SESSION["dayofweek"] == "All Days") {
                                echo "selected='selected'";
                            } ?> value="All Days">All Days
                            </option>
                            <option <?php if ($_SESSION["dayofweek"] == "Monday") {
                                echo "selected='selected'";
                            } ?> value="Monday">Monday
                            </option>
                            <option <?php if ($_SESSION["dayofweek"] == "Tuesday") {
                                echo "selected='selected'";
                            } ?> value="Tuesday">Tuesday
                            </option>
                            <option <?php if ($_SESSION["dayofweek"] == "Wednesday") {
                                echo "selected='selected'";
                            } ?> value="Wednesday">Wednesday
                            </option>
                            <option <?php if ($_SESSION["dayofweek"] == "Thursday") {
                                echo "selected='selected'";
                            } ?> value="Thursday">Thursday
                            </option>
                            <option <?php if ($_SESSION["dayofweek"] == "Friday") {
                                echo "selected='selected'";
                            } ?> value="Friday">Friday
                            </option>
                            <option <?php if ($_SESSION["dayofweek"] == "Saturday") {
                                echo "selected='selected'";
                            } ?> value="Saturday">Saturday
                            </option>
                            <option <?php if ($_SESSION["dayofweek"] == "Sunday") {
                                echo "selected='selected'";
                            } ?> value="Sunday">Sunday
                            </option>
                        </select>
                    </div>
                    <div class="col-sm-2" style="padding: 0px;">
                        <label for="cmbdataexpetionorder">Report Option</label>
                    </div>
                    <div class="col-sm-4">
                        <select name="cmbReportOption" class="form-control" id="cmbReportOption">
                            <option value="Option 1">Option 1</option>
                            <option value="Option 2">Option 2</option>
                        </select>
                    </div>
                </div>
                <input name="Submit" type="submit" class="btn btn-default" value="Submit"
                       style="color: white; background-color: #007CC4"/>
            </form>
        </div>
    </div>
</div>
<div class="col-sm-6 col-sm-offset-3">
    <?php

    // -------------- See if any data is in Summary Table -----------------------
    if ($a == 's') {
        $dataavailable = true;
        if ($radDate == 'date' && IsReportAvailableForStore($_SESSION["dateyear"] . "/" . $_SESSION["datemonth"] . "/" . $_SESSION["dateday"], $_SESSION["store"]) != 'true') {
            $dataavailable = false;
        }
        if ($radDate == 'daterange' && IsReportAvailableForStoreInDateRange($_SESSION["daterangestring"], $_SESSION["store"]) != 'true') {
            $dataavailable = false;
        }
        // ----------- END OF DATA AVAILABILITY CHECK -------------------------------

        if ($dataavailable == true){

            ?>
            <?php
            // REPORT OUTPUT STARTS HERE

            // ******************************************************************
            // Excel Export. Initialize
            // *
            set_time_limit(30000);
            require_once "classes/excelexport/class.writeexcel_workbook.inc.php";
            require_once "classes/excelexport/class.writeexcel_worksheet.inc.php";
            $fname = tempnam("/tmp", GenerateReferenceNumber() . ".xls");
            $workbook =& new writeexcel_workbook($fname);
            $worksheet =& $workbook->addworksheet('Report');
            // Set Columns widths
            $worksheet->set_column('A:B', 1);
            $worksheet->set_column('C:C', 20);
            $worksheet->set_column('D:J', 14.75);
            $worksheet->insert_bitmap('a1', 'classes/excelexport/report_header.bmp', 16, 8); // Write Header
            // Set styles
            $heading =& $workbook->addformat(array(
                bold => 1,
                color => '007CC4',
                size => 12,
                merge => 1,
                font => 'Arial'
            )); // Create new font style
            $heading2 =& $workbook->addformat(array(
                bold => 1,
                color => '007CC4',
                size => 11,
                merge => 1,
                font => 'Arial',
                align => "left"
            )); // Create new font style

            // *******************************************************************

            if ($radDate == "date") {
                $sumIDResult = GetSumIDforWithDateRange("'" . $_SESSION["dateyear"] . "/" . $_SESSION["datemonth"] . "/" . $_SESSION["dateday"] . "'", $_SESSION["store"]);
            }
            if ($radDate == "daterange") {
                $sumIDResult = GetSumIDforWithDateRange($_SESSION["daterangestring"], $_SESSION["store"]);
                // echo $_SESSION["daterangestring"];

            }
            if (mysql_num_rows($sumIDResult) > 0) {
                $sumidrow = mysql_fetch_array($sumIDResult);
                $sumid = "'" . $sumidrow["sumid"] . "'";
            }
            while ($sumidrow = mysql_fetch_array($sumIDResult)) {
                $sumid = $sumid . ",'" . $sumidrow["sumid"] . "'";
            }

            // Trim SUMID's if day of week selected
            //echo $_SESSION["dayofweek"];
            if ($radDate == "daterange" && $_SESSION["dayofweek"] != "All Days") {
                //echo "here";
                $sumid = ReturnDayOfWeekSUMIDs($sumid);
            }

            //Report Option 1 (Standard)
            if ($_POST['cmbReportOption'] == "Option 1") {

                if ($radDate == "date") {
                    $result = GetSummaryTotals($sumid);
                }
                if ($radDate == "daterange") {
                    $result = GetSummaryTotalsWithDateRange($sumid);
                }
                $_SESSION['ReportOption'] = 1;
            }

            //Report Option 2 (Net Sales + Comps)
            if ($_POST['cmbReportOption'] == "Option 2") {

                if ($radDate == "date") {
                    $result = GetSummaryTotalsOp2($sumid);
                }
                if ($radDate == "daterange") {
                    $result = GetSummaryTotalsWithDateRangeOp2($sumid);
                }
                $_SESSION['ReportOption'] = 2;
            }


            $row = mysql_fetch_array($result);

            $resultadditional = GetSummaryTotalAdditions($sumid);
            $rowadditional = mysql_fetch_array($resultadditional);

            if ($rowadditional["adcharges"] == null) {
                $additionalchargers = "0.00";
            } else {
                $additionalchargers = $rowadditional["adcharges"];
            }

            if ($rowadditional["ordercharges"] == null) {
                $ordercharges = "0.00";
            } else {
                $ordercharges = $rowadditional["ordercharges"];
            }

            if ($rowadditional["promos"] == null) {
                $promos = "0.00";
            } else {
                $promos = $rowadditional["promos"];
            }

            if ($rowadditional["refunds"] == null) {
                $refunds = "0.00";
            } else {
                $refunds = $rowadditional["refunds"];
            }

            ?>

            <h3 style="text:#007CC4" class="text-center">
                <?php
                if ($radStores == "store") {
                    echo GetStoreName($_SESSION["store"]);
                    $excelreport_title = GetStoreName($_SESSION["store"]); // EXCEL
                }
                if ($radStores == "storegroup") {
                    echo GetStoreGroupName($grpid);
                    $excelreport_title = GetStoreName($_SESSION["store"]) . $_SESSION["storegroupscount"]; // EXCEL
                }
                ?>
                <?php
                if ($radStores == "storegroup") {
                    echo $_SESSION["storegroupscount"];
                }
                ?>
            </h3>
            <h5 class="text-center">for date</h5>
            <?php
            if ($radDate == "date") {
                echo '<h5 style="color:#757575" class="text-center">' . $_SESSION["date"] . '</h5>';
                $excelreport_title = $excelreport_title . " for date " . $_SESSION["dateday"] . "/" . $_SESSION["datemonth"] . "/" . $_SESSION["dateyear"]; // EXCEL
            }
            if ($radDate == "daterange") {
                echo '<h5 style="color:#757575" class="text-center">' . $_SESSION["daterange"] . '</h5>';
                $excelreport_title = $excelreport_title . " for date " . $_SESSION["datefromday"] . "/" . $_SESSION["datefrommonth"] . "/" . $_SESSION["datefromyear"] . " to " . $_SESSION["datetoday"] . "/" . $_SESSION["datetomonth"] . "/" . $_SESSION["datetoyear"]; // EXCEL
            }
            // ************************************
            // EXCEL - Set up report title
            // *
            // Report Title
            $title = "Sales Summary Report for ";
            $headings = array($title, '');
            $worksheet->write_row('D6', $headings, $heading);
            // Report Specs
            $$excelreport_title = $excelreport_title;
            if ($_SESSION["dayofweek"] != "All Days") {
                echo " (" . $_SESSION["dayofweek"] . "s only)";
                $excelreport_title = $excelreport_title . " (" . $_SESSION["dayofweek"] . "s only)";
            }
            $headings = array($excelreport_title, '');
            $worksheet->write_row('D7', $headings, $heading);
            $num1_format =& $workbook->addformat(array(num_format => '0.00'));  //Basic number format
            // ************************************
            ?>

            <hr size="1"/>
            <div class="row">
                <div class="col-sm-8 col-sm-offset-2">
                    <h4 class="text-center" style="color:#757575"> Summary</h4>
                    <table class="table table-striped table-condensed ">
                        <tr>
                            <td width="60%" bgcolor="#F2F2F2" class="NormalText"><strong>Gross Sales </strong></td>
                            <td width="40%" class="NormalText">
                                <div align="right"><?php echo $row["sumgrosssales"];
                                    // ************************************************
                                    // Excel
                                    $title = "Gross Sales";
                                    $headings = array($title, '');
                                    $worksheet->write_row('D9', $headings, $heading2);
                                    $worksheet->write('F9', $row["sumgrosssales"], $num1_format);
                                    ?></div>
                            </td>
                        </tr>
                        <tr>
                            <td bgcolor="#F2F2F2" class="NormalText"><strong>Nett Sales </strong></td>
                            <td class="NormalText">
                                <div align="right"><?php echo $row["sumnettsales"];
                                    // ************************************************
                                    // Excel
                                    $title = "Nett Sales";
                                    $headings = array($title, '');
                                    $worksheet->write_row('D10', $headings, $heading2);
                                    $worksheet->write('F10', $row["sumnettsales"], $num1_format);
                                    ?></div>
                            </td>
                        </tr>
                        <tr>
                            <td bgcolor="#F2F2F2" class="NormalText"><strong>Banking Sales </strong></td>
                            <td class="NormalText">
                                <div align="right"><?php echo $row["sumbankingsales"];
                                    // ************************************************
                                    // Excel
                                    $title = "Banking Sales";
                                    $headings = array($title, '');
                                    $worksheet->write_row('D11', $headings, $heading2);
                                    $worksheet->write('F11', $row["sumbankingsales"], $num1_format);

                                    ?></div>
                            </td>
                        </tr>
                    </table>
                    <br/>
                    <table class="table table-striped .table-condensed .table-bordered">
                        <tr>
                            <td bgcolor="#F2F2F2" class="NormalText"><strong>Additional Charges</strong></td>
                            <td class="NormalText">
                                <div align="right"><?php echo $additionalchargers;
                                    // ************************************************
                                    // Excel
                                    $title = "Additional Charges";
                                    $headings = array($title, '');
                                    $worksheet->write_row('D13', $headings, $heading2);
                                    $worksheet->write('F13', $additionalchargers, $num1_format);
                                    ?></div>
                            </td>
                        </tr>
                        <tr>
                            <td bgcolor="#F2F2F2" class="NormalText"><strong>Order Charges</strong></td>
                            <td class="NormalText">
                                <div align="right"><?php echo $ordercharges;
                                    // ************************************************
                                    // Excel
                                    $title = "Order Charges";
                                    $headings = array($title, '');
                                    $worksheet->write_row('D14', $headings, $heading2);
                                    $worksheet->write('F14', $ordercharges, $num1_format);
                                    ?></div>
                            </td>
                        </tr>
                        <tr>
                            <td bgcolor="#F2F2F2" class="NormalText"><strong>Promos</strong></td>
                            <td class="NormalText">
                                <div align="right"><?php echo $promos;
                                    // ************************************************
                                    // Excel
                                    $title = "Promos";
                                    $headings = array($title, '');
                                    $worksheet->write_row('D15', $headings, $heading2);
                                    $worksheet->write('F15', $promos, $num1_format);
                                    ?></div>
                            </td>
                        </tr>
                        <tr>
                            <td bgcolor="#F2F2F2" class="NormalText"><strong>Refunds</strong></td>
                            <td class="NormalText">
                                <div align="right"><?php echo $refunds;
                                    // ************************************************
                                    // Excel
                                    $title = "Refunds";
                                    $headings = array($title, '');
                                    $worksheet->write_row('D16', $headings, $heading2);
                                    $worksheet->write('F16', $refunds, $num1_format);
                                    ?></div>
                            </td>
                        </tr>
                        <tr>
                            <td width="60%" bgcolor="#F2F2F2" class="NormalText"><strong>Comps</strong></td>
                            <td width="40%" class="NormalText">
                                <div align="right"><?php echo $row["sumcomps"];
                                    // ************************************************
                                    // Excel
                                    $title = "Comps";
                                    $headings = array($title, '');
                                    $worksheet->write_row('D17', $headings, $heading2);
                                    $worksheet->write('F17', $row["sumcomps"], $num1_format);
                                    ?></div>
                            </td>
                        </tr>
                        <tr>
                            <td bgcolor="#F2F2F2" class="NormalText">
                                <strong><?php echo $_SESSION["usrpref_taxtype"]; ?></strong></td>
                            <td class="NormalText">
                                <div align="right"><?php echo $row["sumvat"];
                                    // ************************************************
                                    // Excel
                                    $title = $_SESSION["usrpref_taxtype"];
                                    $headings = array($title, '');
                                    $worksheet->write_row('D18', $headings, $heading2);
                                    $worksheet->write('F18', $row["sumvat"], $num1_format);
                                    ?></div>
                            </td>
                        </tr>
                        <tr>
                            <td bgcolor="#F2F2F2" class="NormalText"><strong>Voids</strong></td>
                            <td class="NormalText">
                                <div align="right"><?php echo $row["sumvoids"];
                                    // ************************************************
                                    // Excel
                                    $title = "Voids";
                                    $headings = array($title, '');
                                    $worksheet->write_row('D19', $headings, $heading2);
                                    $worksheet->write('F19', $row["sumvoids"], $num1_format);
                                    ?></div>
                            </td>
                        </tr>
                        <tr>
                            <td bgcolor="#F2F2F2" class="NormalText"><strong>Head Count </strong></td>
                            <td class="NormalText">
                                <div align="right"><?php
                                    if ((string)$row["sumheadcount"] == Null) {
                                        echo "0";
                                    } else {
                                        echo $row["sumheadcount"];
                                    }

                                    // ************************************************
                                    // Excel
                                    $title = "Head Count";
                                    $headings = array($title, '');
                                    $worksheet->write_row('D20', $headings, $heading2);
                                    $worksheet->write('F20', $row["sumheadcount"], $num1_format);
                                    ?></div>
                            </td>
                        </tr>
                        <tr>
                            <td bgcolor="#F2F2F2" class="NormalText"><strong>Ave Per Head
                                    (excl <?php echo $_SESSION["usrpref_taxtype"]; ?>) </strong></td>
                            <td class="NormalText">
                                <div align="right">
                                    <?php
                                    // Calculate the Avg per Head ex Vat
                                    if ($row["sumheadcount"] < 1) {
                                        $amount = "0";
                                        echo $amount;
                                    } else {
                                        $amount = round($row["sumnettsales"] / $row["sumheadcount"], 2);
                                        echo $amount;
                                    }

                                    // ************************************************
                                    // Excel
                                    $title = "Ave Per Head (excl " . $_SESSION["usrpref_taxtype"] . ")";
                                    $headings = array($title, '');
                                    $worksheet->write_row('D21', $headings, $heading2);
                                    $worksheet->write('F21', $amount, $num1_format);
                                    ?></div>
                            </td>
                        </tr>
                        <tr>
                            <td bgcolor="#F2F2F2" class="NormalText"><strong>Ave Per Head
                                    (incl <?php echo $_SESSION["usrpref_taxtype"]; ?>) </strong></td>
                            <td class="NormalText">
                                <div align="right">
                                    <?php
                                    // Calculate the Avg per Head incl Vat
                                    if ($row["sumheadcount"] < 1) {
                                        $amount = "0";
                                        echo $amount;
                                    } else {
                                        // South Africa is 14% vat, Australia is 10% GST
                                        if ($_SESSION["usrpref_taxtype"] == "Vat") {

                                            $amount = number_format(($row["sumnettsales"] / $row["sumheadcount"]) * 1.14, 2, '.', '');
                                        }
                                        if ($_SESSION["usrpref_taxtype"] == "Gst") {
                                            $amount = number_format(($row["sumnettsales"] / $row["sumheadcount"]) * 1.10, 2, '.', '');
                                        }
                                        echo $amount;
                                    }
                                    // ************************************************
                                    // Excel
                                    $title = "Ave Per Head (incl " . $_SESSION["usrpref_taxtype"] . ")";
                                    $headings = array($title, '');
                                    $worksheet->write_row('D22', $headings, $heading2);
                                    $worksheet->write('F22', $amount, $num1_format);
                                    ?></div>
                            </td>
                        </tr>
                        <tr>
                            <td bgcolor="#F2F2F2" class="NormalText"><strong>Check Count </strong></td>
                            <td class="NormalText">
                                <div align="right"><?php echo $row["sumcheckcount"];
                                    // ************************************************
                                    // Excel
                                    $title = "Check Count";
                                    $headings = array($title, '');
                                    $worksheet->write_row('D23', $headings, $heading2);
                                    $worksheet->write('F23', $row["sumcheckcount"], $num1_format);
                                    ?></div>
                            </td>
                        </tr>
                        <tr>
                            <td bgcolor="#F2F2F2" class="NormalText"><strong>Ave Per Check
                                    (excl <?php echo $_SESSION["usrpref_taxtype"]; ?>) </strong></td>
                            <td class="NormalText">
                                <div align="right">
                                    <?php
                                    // Calculate the Avg per Head ex Vat
                                    if ($row["sumcheckcount"] < 1) {
                                        $amount = "0.00";
                                        echo $amount;
                                    } else {
                                        $amount = round($row["sumnettsales"] / $row["sumcheckcount"], 2);
                                        echo number_format($amount, "2", ".", "");
                                    }

                                    // ************************************************
                                    // Excel
                                    $title = "Ave Per Check (excl " . $_SESSION["usrpref_taxtype"] . ")";
                                    $headings = array($title, '');
                                    $worksheet->write_row('D24', $headings, $heading2);
                                    $worksheet->write('F24', $amount, $num1_format);
                                    ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td bgcolor="#F2F2F2" class="NormalText"><strong>Ave Per Check
                                    (incl <?php echo $_SESSION["usrpref_taxtype"]; ?>) </strong></td>
                            <td class="NormalText">
                                <div align="right">
                                    <?php
                                    // Calculate the Avg per Head incl Vat
                                    if ($row["sumheadcount"] < 1) {
                                        $amount = "0";
                                        echo $amount;
                                    } else {
                                        // South Africa is 14% vat, Australia is 10% GST
                                        if ($_SESSION["usrpref_taxtype"] == "Vat") {

                                            $amount = number_format(($row["sumnettsales"] / $row["sumcheckcount"]) * 1.14, 2, '.', '');
                                        }
                                        if ($_SESSION["usrpref_taxtype"] == "Gst") {
                                            $amount = number_format(($row["sumnettsales"] / $row["sumcheckcount"]) * 1.10, 2, '.', '');
                                        }
                                        echo $amount;
                                    }
                                    // ************************************************
                                    // Excel
                                    $title = "Ave Per Check (incl " . $_SESSION["usrpref_taxtype"] . ")";
                                    $headings = array($title, '');
                                    $worksheet->write_row('D25', $headings, $heading2);
                                    $worksheet->write('F25', $amount, $num1_format);
                                    ?>
                                </div>
                            </td>
                        </tr>
                    </table>
                    <?php

                    $result = GetSalesByCategory($sumid);

                    ?>
                    <h4 class="text-center" style="color:#757575">Sales by Category (Excl.
                        <?php echo $_SESSION["usrpref_taxtype"];

                        // ************************************************
                        // Excel
                        $title = "Sales by Category (Excl. " . $_SESSION["usrpref_taxtype"];
                        $headings = array($title, '');
                        $worksheet->write_row('D27', $headings, $heading);

                        ?>)
                    </h4>
                    <table class="table table-striped .table-condensed .table-bordered">
                        <?php
                        //* Excel
                        $rownumber = 29;
                        while ($row = mysql_fetch_array($result)) { ?>
                            <tr>
                                <td width="60%" bgcolor="#F2F2F2" class="NormalText">
                                    <strong><?php echo $row["sbccategoryname"]; ?></strong></td>
                                <td width="40%" class="NormalText">
                                    <div align="right"><?php echo $row["sbcamount"]; ?></div>
                                </td>
                            </tr>
                            <?php
// ************************************************
// Excel
                            $title = $row["sbccategoryname"];
                            $headings = array($title, '');
                            $worksheet->write_row('D' . $rownumber, $headings, $heading2);

                            $title = $row["sbcamount"];
                            $headings = array($title, '');
                            $worksheet->write_row('F' . $rownumber, $headings, $num1_format);
                            $rownumber++;

                        } ?>
                    </table>
                </div>
            </div>
            <?php

            $result = GetPaymentBreakdown($sumid);

            ?>
            <div align="center">
                <h4 style="color:#757575">Payments Breakdown</h4>
            </div>
            <?php
            // ********************************************
            // Excel
            $rownumber++;
            $title = "Payments Breakdown";
            $headings = array($title, '');
            $worksheet->write_row('D' . $rownumber, $headings, $heading);
            $rownumber++;

            ?>
            <table class="table table-striped .table-condensed .table-bordered" style="margin-bottom: 0px;">
                <tr>
                    <td width="26%" bgcolor="#F2F2F2" class="NormalText">
                        <div align="left"><strong>Payment Method </strong></div>
                    </td>
                    <td width="19%" bgcolor="#F2F2F2" class="NormalText">
                        <div align="right"><strong>Amount</strong></div>
                    </td>
                    <td width="18%" bgcolor="#F2F2F2" class="NormalText">
                        <div align="right"><strong>Charge Tips </strong></div>
                    </td>
                    <td width="19%" bgcolor="#F2F2F2" class="NormalText">
                        <div align="right"><strong>AutoGratuity</strong></div>
                    </td>
                    <td width="18%" bgcolor="#F2F2F2" class="NormalText">
                        <div align="right"><strong>Sales</strong></div>
                    </td>
                </tr>
                <?php
                // ********************************************
                // Excel
                //* Write Headings

                $title = "Payment Method";
                $headings = array($title, '');
                $worksheet->write_row('C' . $rownumber, $headings, $heading2);

                $title = "Amount";
                $headings = array($title, '');
                $worksheet->write_row('D' . $rownumber, $headings, $heading2);

                $title = "Charge Tips";
                $headings = array($title, '');
                $worksheet->write_row('E' . $rownumber, $headings, $heading2);

                $title = "AutoGratuity";
                $headings = array($title, '');
                $worksheet->write_row('F' . $rownumber, $headings, $heading2);

                $title = "Sale";
                $headings = array($title, '');
                $worksheet->write_row('G' . $rownumber, $headings, $heading2);
                $rownumber++;

                // Make graph array;
                $payment_values = array();
                $payment_labels = array();

                while ($row = mysql_fetch_array($result)) {
                    ?>
                    <tr>
                        <td class="NormalText"><?php echo $row["pbrpaymenttype"]; ?></td>
                        <td>
                            <div align="right" class="NormalText"><?php echo $row["pbramount"]; ?></div>
                        </td>
                        <td>
                            <div align="right" class="NormalText"><?php echo $row["pbrchargetips"]; ?></div>
                        </td>
                        <td>
                            <div align="right" class="NormalText"><?php echo $row["pbrautogratuity"]; ?></div>
                        </td>
                        <td>
                            <div align="right" class="NormalText"><?php echo $row["pbrsales"]; ?></div>
                        </td>
                    </tr>
                    <?php

// ************************************************
// Excel
                    $title = $row["pbrpaymenttype"];
                    $headings = array($title, '');
                    $worksheet->write_row('C' . $rownumber, $headings, $heading2);

                    $title = $row["pbramount"];
                    $headings = array($title, '');
                    $worksheet->write_row('D' . $rownumber, $headings, $num1_format);

                    $title = $row["pbrchargetips"];
                    $headings = array($title, '');
                    $worksheet->write_row('E' . $rownumber, $headings, $num1_format);

                    $title = $row["pbrautogratuity"];
                    $headings = array($title, '');
                    $worksheet->write_row('F' . $rownumber, $headings, $num1_format);

                    $title = $row["pbrsales"];
                    $headings = array($title, '');
                    $worksheet->write_row('G' . $rownumber, $headings, $num1_format);

                    $rownumber++;

                }

                ?>
            </table>
            <br/>
            <table width="738" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td width="738">
                        <?php
                        // ************************************************************
                        // Excel Export - Close up document
                        // Write Footer
                        $worksheet->insert_bitmap('a' . $rownumber, 'classes/excelexport/report_footer.bmp', 16, 8); // Write Footer
                        $workbook->close(); // Close the workbook
                        // ************************************************************
                        ?>
                        <div align="center"></div>
                    </td>
                </tr>
            </table>
            <div class="col-sm-1 col-sm-offset-10 text-center">
                <a href="classes/excelexport/excelexport.php?fname=<?php echo $fname; ?>" target="_blank">
                    <i class="fas fa-file-excel fa-3x"></i>
                </a>
            </div>
            <div class="col-sm-1">
                <a href="Javascript:;"><span class="glyphicon glyphicon-print"
                                             onMouseUp="NewWindow('pages/report_salessummary_print.php?<?php echo "radDate=" . $radDate . "&dateday=" . $_SESSION["dateday"] . "&datemonth=" . $_SESSION["datemonth"] . "&dateyear=" . $_SESSION["dateyear"] . "&store=" . str_replace("'", "^", $_SESSION["store"]) . "&a=" . $a . "&datefromday=" . $_SESSION["datefromday"] . "&datefrommonth=" . $_SESSION["datefrommonth"] . "&datefromyear=" . $_SESSION["datefromyear"] . "&datetoday=" . $_SESSION["datetoday"] . "&datetomonth=" . $_SESSION["datetomonth"] . "&grpid=" . $grpid . "&radStores=" . $radStores . "&datetoyear=" . $_SESSION["datetoyear"]; ?>','report_salessummary','650','500','yes');return false;"></span></a>
            </div>
            <br/>
            <p align="left" class="NormalText"><span class="NormalHeading">Calcuation Methods:</span><br/>
                <strong>Gross Sales</strong> - Less Voids Surch. Order Charges Add Chgs<br/>
                <strong>Nett Sales</strong> - Less Voids, Comps, Promos, Taxes, Surch. Order charges Add Chgs<br/>
                <strong>Banking Sales</strong> - Less Voids, Comps, Promos, Surch. Order Charges Add Chgs</p>
            <p align="left" class="NormalText"><span class="NormalHeading">Report Options 1 :</span><br/>
                <strong>Gross Sales</strong> - Less Voids Surch. Order Charges Add Chgs<br/>
                <strong>Nett Sales</strong> - Less Voids, Comps, Promos, Taxes, Surch. Order charges Add Chgs<br/>
                <strong>Banking Sales</strong> - Less Voids, Comps, Promos, Surch. Order Charges Add Chgs</p>
            <p align="left" class="NormalText"><span class="NormalHeading">Report Options 2 :</span><br/>
                <strong>Gross Sales</strong> - Less Voids Surch. Order Charges Add Chgs<br/>
                <strong>Nett Sales</strong> - Less Voids, Taxes<br/>
                <strong>Banking Sales</strong> - Less Voids, Comps, Promos, Surch. Order Charges Add Chgs<br/>
            </p>
            <?php
        } else {
            ?>
            <p align="center" class="NormalText"><br/>
                <span class="style2">No results were returned for that query.<br/>
    Please try different parameters. </span></p>
        <?php }
    } ?>
</div>

<script language="JavaScript1.2">

    function SetSpecificDateFocus() {
        $("#date").prop('disabled', false);
        $("#daterange").prop('disabled', true);
        $("#date").parent().removeClass('disabled');
        $("#daterange").parent().addClass('disabled');
    }

    function SetSpecificDateRangeFocus() {
        $("#date").prop('disabled', true);
        $("#daterange").prop('disabled', false);
        $("#date").parent().addClass('disabled');
        $("#daterange").parent().removeClass('disabled');
    }

    function SetStoreFocus() {
        $("#cmbstore").prop('disabled', false);
        $("#cmbstoregroup").prop('disabled', true);
        $("#cmbstore").parent().removeClass('disabled');
        $("#cmbstoregroup").parent().addClass('disabled');
    }
    function SetStoreGroupFocus() {
        $("#cmbstore").prop('disabled', true);
        $("#cmbstoregroup").prop('disabled', false);
        $("#cmbstore").parent().addClass('disabled');
        $("#cmbstoregroup").parent().removeClass('disabled');
    }

    <?php

    if ($radStores == "store" || $radStores == null) {
        echo "SetStoreFocus();";
    }
    if ($radStores == "storegroup") {
        echo "SetStoreGroupFocus();";
    }

    if ($radDate == "date") {
        echo "SetSpecificDateFocus();";
    }
    if ($radDate == "daterange") {
        echo "SetSpecificDateRangeFocus();";
    }

    ?>

    function CheckDateRange(somevar) {

        var result = true;


// CHECK DATE RANGE
        if (document.frmparameters.radDate[1].checked == true) {
            if (document.frmparameters.datefromyear.value > document.frmparameters.datetoyear.value) { //1. FromYear cannot be bigger than ToYear
                result = false;
            }

            if (document.frmparameters.datefromyear.value == document.frmparameters.datetoyear.value && document.frmparameters.datefrommonth.value > document.frmparameters.datetomonth.value) { //2. FromMonth cannot be bigger than ToMonth if FromYear = ToYear
                result = false;
            }

            if (document.frmparameters.datefromyear.value == document.frmparameters.datetoyear.value && document.frmparameters.datefrommonth.value == document.frmparameters.datetomonth.value && document.frmparameters.datefromday.value >= document.frmparameters.datetoday.value) {//3. FromDay cannot be bigger than ToDay when FromMonth = ToMonth and FromYear = ToYear.
                result = false;
            }

            // CHECK FROM DATE COMPARED TO LAST DATA DATE

            if (document.frmparameters.datefromyear.value > document.frmparameters.hiddenyesteryear.value) {
                result = false;
            }

            if (document.frmparameters.datefromyear.value == document.frmparameters.hiddenyesteryear.value && document.frmparameters.datefrommonth.value > document.frmparameters.hiddenyestermonth.value) {
                result = false;
            }

            if (document.frmparameters.datefromyear.value == document.frmparameters.hiddenyesteryear.value && document.frmparameters.datefrommonth.value == document.frmparameters.hiddenyestermonth.value && document.frmparameters.datefromday.value > document.frmparameters.hiddenyesterday.value) {
                result = false;
            }

            if (result == false) {
                alert('Please ensure that your From Date is before your To Date and that your From date is not later than yesterday');
            }

        }
        return result;

    }

</script>
