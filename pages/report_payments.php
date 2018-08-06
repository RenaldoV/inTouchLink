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
        }, function(start, end, label) {
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

        $('input[name="daterange"]').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
        });

        $('input[name="daterange"]').on('cancel.daterangepicker', function(ev, picker) {
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
                        Payments Report
                    </h3>
                </div>
            </div>
            <form id="frmparameters" name="frmparameters" method="post" action="index.php?p=report_payments&a=s">
                <div class="row">
                    <div class="col-sm-4">
                        <label for="cmbstore">Store</label>
                    </div>
                    <div class="col-sm-8">
                        <input name="radStores" type="radio" value="store" checked='checked' hidden/>
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
                <div class="row" style="margin-top: 5px;">
                    <div class="col-sm-4"><label for="date">Specific Date</label></div>
                    <div class="col-sm-8">
                        <div class="input-group">
                            <span class="input-group-addon">
                                <input name="radDate" type="radio" value="date" <?php if($radDate == "date") {echo "checked='checked'";} ?>  onclick="Javascript: SetSpecificDateFocus();">
                            </span>
                            <input class="form-control" type="text" name="date" id="date" value="<?php echo $_SESSION['date'] ?>" placeholder="Specific Date"/>
                        </div><!-- /input-group -->
                    </div>
                </div>
                <div class="row" style="margin-top: 5px;">
                    <div class="col-sm-4"><label for="daterange">Date Range</label></div>
                    <div class="col-sm-8">
                        <div class="input-group">
                              <span class="input-group-addon">
                                <input name="radDate" type="radio" value="daterange" onclick="Javascript: SetSpecificDateRangeFocus();" <?php if($radDate == "daterange") {echo "checked='checked'";} ?> />
                              </span>
                            <input class="form-control" type="text" name="daterange" id="daterange" value="<?php echo $_SESSION['daterange'] ?>" placeholder="Date Range"/>
                        </div><!-- /input-group -->
                    </div>
                </div>
                <input name="Submit" type="submit" class="btn btn-default" value="Submit"
                       style="color: white; background-color: #007CC4"/>
            </form>
        </div>
    </div>
</div>


<div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
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

        if ($dataavailable == true) {
            ?>
            <?php
            // REPORT OUTPUT STARTS HERE
            // ******************************************************************
            // Excel Export. Initialize
            // *
            set_time_limit(30000);
            require_once "classes/excelexport/class.writeexcel_workbook.inc.php";
            require_once "classes/excelexport/class.writeexcel_worksheet.inc.php";
            $fname = tempnam("/tmp", "demo.xls");
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
            $RightNumberTotalBold =& $workbook->addformat(array(
                bold => 1,
                color => '007CC4',
                size => 11,
                merge => 1,
                font => 'Arial',
                align => "right"
            )); // Create new font style
            $NormalLeftAlign =& $workbook->addformat(array(
                bold => 0,
                color => '007CC4',
                size => 10,
                merge => 1,
                font => 'Arial',
                align => "left"
            )); // Create new font style
            $NormalRightAlign =& $workbook->addformat(array(
                bold => 0,
                color => '007CC4',
                size => 10,
                merge => 1,
                font => 'Arial',
                align => "right"
            )); // Create new font style

            // *******************************************************************
            if ($radDate == "date") {
                $result = GetSummaryTotals($_SESSION["dateyear"] . "/" . $_SESSION["datemonth"] . "/" . $_SESSION["dateday"], $_SESSION["store"]);
                $sumIDResult = GetSumIDforWithDateRange("'" . $_SESSION["dateyear"] . "/" . $_SESSION["datemonth"] . "/" . $_SESSION["dateday"] . "'", $_SESSION["store"]);
            }
            if ($radDate == "daterange") {
                $result = GetSummaryTotalsWithDateRange($_SESSION["daterangestring"], $_SESSION["store"]);
                $sumIDResult = GetSumIDforWithDateRange($_SESSION["daterangestring"], $_SESSION["store"]);
            }
            $row = mysql_fetch_array($result);


            if (mysql_num_rows($sumIDResult) > 0) {
                $sumidrow = mysql_fetch_array($sumIDResult);
                $sumid = "'" . $sumidrow["sumid"] . "'";
            }
            while ($sumidrow = mysql_fetch_array($sumIDResult)) {
                $sumid = $sumid . ",'" . $sumidrow["sumid"] . "'";
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
                    $excelreport_title = GetStoreName($_SESSION["store"]); // EXCEL
                }
                ?>
            </h3>
            <h5 class="text-center">for date</h5>
            <h5 style="color:#757575" class="text-center">
                <?php
                if ($radDate == "date") {
                    echo $_SESSION["datemonth"] . "/" . $_SESSION["dateday"] . "/" . $_SESSION["dateyear"];
                    $excelreport_title = $excelreport_title . " for date " . $_SESSION["dateday"] . "/" . $_SESSION["datemonth"] . "/" . $_SESSION["dateyear"]; // EXCEL
                }
                if ($radDate == "daterange") {
                    echo $_SESSION["datefromday"] . "/" . $_SESSION["datefrommonth"] . "/" . $_SESSION["datefromyear"] . " to " . $_SESSION["datetoday"] . "/" . $_SESSION["datetomonth"] . "/" . $_SESSION["datetoyear"];
                    $excelreport_title = $excelreport_title . " for date " . $_SESSION["datefromday"] . "/" . $_SESSION["datefrommonth"] . "/" . $_SESSION["datefromyear"] . " to " . $_SESSION["datetoday"] . "/" . $_SESSION["datetomonth"] . "/" . $_SESSION["datetoyear"]; // EXCEL
                }
                ?>
            </h5>
            <?php
            // ************************************
            // EXCEL - Set up report title
            // *
            // Report Title
            $title = "Comps Report for ";
            $headings = array($title, '');
            $worksheet->write_row('D6', $headings, $heading);
            // Report Specs
            $$excelreport_title = $excelreport_title;
            $headings = array($excelreport_title, '');
            $worksheet->write_row('D7', $headings, $heading);
            $num1_format =& $workbook->addformat(array(num_format => '0.00'));  //Basic number format
            // ************************************

            /// -------------------- PAYMENTS  --------------------------
            $result = GetPayments($sumid);
            // ************************************************
            // Excel
            // Write Table Headers for GROUP
            $title = "Comp Type";
            $headings = array($title, '');
            $worksheet->write_row('C10', $headings, $heading2);

            $title = "Qty";
            $headings = array($title, '');
            $worksheet->write_row('E10', $headings, $RightNumberTotalBold);

            $title = "Amount";
            $headings = array($title, '');
            $worksheet->write_row('F10', $headings, $RightNumberTotalBold);

            $title = "% Total";
            $headings = array($title, '');
            $worksheet->write_row('G10', $headings, $RightNumberTotalBold);

            $rownumber = 11;
            ?>
            <div class="col-sm-12">
                <hr/>
                <h4 style="color:#757575" class="text-center">Payments</h4>
                <table class="table table-condensed">
                <?php
                // Initialize vars
                $oldcategoryname = "";
                $categoryname = "";
                $totalqty = 0;
                $totalamount = 0;
                $totalgrat = 0;
                $totaltip = 0;
                $totaltotal = 0;

                while ($row = mysql_fetch_array($result)) {

                    //Set category name if none set yet

                    if ($oldcategoryname == "") {
                        $oldcategoryname = $row["pmttype"];
                        $categoryname = $row["pmttype"];
                        $firstcat = true;
                    }
                    $categoryname = $row["pmttype"];
                    if ($categoryname <> $oldcategoryname || $firstcat == true) { // Write Header if new
                        if ($firstcat != true) {
                            ?>
                            <tr>
                                <td height="31" bgcolor="#F2F2F2" class="style3"><strong>Total</strong></td>
                                <td height="31" bgcolor="#F2F2F2" class="style3">
                                    <div align="right"><strong><?php echo $totalqty;
                                            $totalqty = 0; ?></strong></div>
                                </td>
                                <td height="31" bgcolor="#F2F2F2" class="style3">
                                    <div align="right"><strong><?php echo number_format($totalamount, 2, '.', '');
                                            $totalamount = 0; ?></strong></div>
                                </td>
                                <td height="31" bgcolor="#F2F2F2" class="style3">
                                    <div align="right"><strong><?php echo number_format($totalgrat, 2, '.', '');
                                            $totalgrat = 0; ?></strong></div>
                                </td>
                                <td height="31" bgcolor="#F2F2F2" class="style3">
                                    <div align="right"><strong><?php echo number_format($totaltip, 2, '.', '');
                                            $totaltip = 0; ?></strong></div>
                                </td>
                                <td height="31" bgcolor="#F2F2F2" class="style3">
                                    <div align="right"><strong><?php echo number_format($totaltotal, 2, '.', '');
                                            $totaltotal = 0; ?></strong></div>
                                </td>
                                <td height="31" bgcolor="#F2F2F2" class="style3">&nbsp;</td>
                            </tr>
                            <?php
                        }
                        ?>
                        <tr style="border-top: none; border-bottom: none;">
                            <td style="border-top: none; border-bottom: none;" colspan="7" class="NormalText" height="30" ></td>
                        </tr>
                        <?php
                        $oldcategoryname = $categoryname;
                        echo "<tr><td colspan='7' style='color: white' bgcolor='#007CC4'>" . $row["pmttype"] . "</td></tr>";
                        ?>
                        <tr>
                            <td bgcolor="#F2F2F2" class="NormalText"><strong>Check #</strong></td>
                            <td bgcolor="#F2F2F2" class="NormalText">
                                <div align="right"><strong>Qty</strong></div>
                            </td>
                            <td bgcolor="#F2F2F2" class="NormalText">
                                <div align="right"><strong>Amount</strong></div>
                            </td>
                            <td bgcolor="#F2F2F2" class="NormalText">
                                <div align="right"><strong>Grat</strong></div>
                            </td>
                            <td bgcolor="#F2F2F2" class="NormalText">
                                <div align="right"><strong>Tip</strong></div>
                            </td>
                            <td bgcolor="#F2F2F2" class="NormalText">
                                <div align="right"><strong>Total</strong></div>
                            </td>
                            <td bgcolor="#F2F2F2" class="NormalText">
                                <div align="left"><strong> Employee </strong></div>
                            </td>
                        </tr>

                        <?php
                    } // end writing header
                    ?>
                    <tr>
                        <td width="11%" class="NormalText"><?php echo $row["checknum"]; ?></td>
                        <td width="6%" class="NormalText">
                            <div align="right"><?php echo $row["qty"];
                                $totalqty = $totalqty + $row["qty"]; ?></div>
                        </td>
                        <td width="13%" class="NormalText">
                            <div align="right"><?php echo $row["amount"];
                                $totalamount = $totalamount + $row["amount"]; ?></div>
                        </td>
                        <td width="13%" class="NormalText">
                            <div align="right"><?php echo $row["grat"];
                                $totalgrat = $totalgrat + $row["grat"]; ?></div>
                        </td>
                        <td width="14%" class="NormalText">
                            <div align="right"><?php echo $row["tip"];
                                $totaltip = $totaltip + $row["tip"]; ?></div>
                        </td>
                        <td width="14%" class="NormalText">
                            <div align="right"><?php echo $row["total"];
                                $totaltotal = $totaltotal + $row["total"]; ?></div>
                        </td>
                        <td width="29%" class="NormalText">
                            <div align="left"><?php
                                if (strlen($row["emp"]) > 35) {
                                    echo substr($row["emp"], 0, 35) . "..."; // shorten
                                } else {
                                    echo $row["emp"];
                                }
                                $firstcat = false;
                                ?></div>
                        </td>
                    </tr>
                    <?php
                    // ************************************************
                    // Excel
                    $title = $row["compname"];
                    $headings = array($title, '');
                    $worksheet->write_row('C' . $rownumber, $headings, $NormalLeftAlign);

                    $title = $row["cbrcompcount"];
                    $headings = array($title, '');
                    $worksheet->write_row('E' . $rownumber, $headings, $NormalRightAlign);

                    $title = $row["cbrcompamount"];
                    $headings = array($title, '');
                    $worksheet->write_row('F' . $rownumber, $headings, $num1_format);

                    $title = $row["cbrcomppercentage"];
                    $headings = array($title, '');
                    $worksheet->write_row('G' . $rownumber, $headings, $num1_format);

                    $rownumber++;

                }
                ?>
                <tr>
                    <td height="31" bgcolor="#F2F2F2" class="NormalText bold"><strong>Total</strong></td>
                    <td height="31" bgcolor="#F2F2F2" class="NormalText bold">
                        <div align="right"><strong><?php echo $totalqty;
                                $totalqty = 0; ?></strong></div>
                    </td>
                    <td height="31" bgcolor="#F2F2F2" class="NormalText bold">
                        <div align="right"><strong><?php echo number_format($totalamount, 2, '.', '');
                                $totalamount = 0; ?></strong></div>
                    </td>
                    <td height="31" bgcolor="#F2F2F2" class="NormalText bold">
                        <div align="right"><strong><?php echo number_format($totalgrat, 2, '.', '');
                                $totalgrat = 0; ?></strong></div>
                    </td>
                    <td height="31" bgcolor="#F2F2F2" class="NormalText bold">
                        <div align="right"><strong><?php echo number_format($totaltip, 2, '.', '');
                                $totaltip = 0; ?></strong></div>
                    </td>
                    <td height="31" bgcolor="#F2F2F2" class="NormalText bold">
                        <div align="right"><strong><?php echo number_format($totaltotal, 2, '.', '');
                                $totaltotal = 0; ?></strong></div>
                    </td>
                    <td height="31" bgcolor="#F2F2F2" class="NormalText bold">&nbsp;</td>
                </tr>
            </table>
                <?php
                $resulttotals = GetPaymentSummaryTotals($sumid);
                $rowtotals = mysql_fetch_array($resulttotals);
                $summarytotal = $rowtotals["total"];
                $result = GetPaymentsSummary($sumid);
                ?>
                <hr/>
                <h4 style="color:#757575" class="text-center">Payments Summary</h4>
                <table class="table table-condensed">
                    <tr>
                        <td bgcolor="#F2F2F2" class="NormalText"><strong>Payment Type</strong></td>
                        <td bgcolor="#F2F2F2" class="NormalText"><div align="right"><strong>Qty</strong></div></td>
                        <td bgcolor="#F2F2F2" class="NormalText"><div align="right"><strong>Amount</strong></div></td>
                        <td bgcolor="#F2F2F2" class="NormalText"><div align="right"><strong>Grat</strong></div></td>
                        <td bgcolor="#F2F2F2" class="NormalText"><div align="right"><strong>Tip</strong></div></td>
                        <td bgcolor="#F2F2F2" class="NormalText"><div align="right"><strong>Total</strong></div></td>
                        <td bgcolor="#F2F2F2" class="NormalText"><div align="right"><strong>% Total</strong></div></td>
                    </tr>
                    <?php

                    $qty = 0;
                    $amount = 0;
                    $grat = 0;
                    $tip = 0;
                    $total = 0;

                    while($row = mysql_fetch_array($result)) { ?>
                        <tr>
                            <td class="NormalText"><?php echo $row["pmttype"]; ?></td>
                            <td class="NormalText"><div align="right"><?php echo $row["qty"]; $qty = $qty + $row["qty"]; ?></div></td>
                            <td class="NormalText"><div align="right"><?php echo $row["amount"];  $amount= $amount + $row["amount"];?></div></td>
                            <td class="NormalText"><div align="right"><?php echo $row["grat"]; $grat = $grat + $row["grat"]; ?></div></td>
                            <td class="NormalText"><div align="right"><?php echo $row["tip"];  $tip = $tip + $row["tip"];?></div></td>
                            <td class="NormalText"><div align="right"><?php echo $row["total"];  $total = $total + $row["total"];?></div></td>
                            <td class="NormalText"><div align="right"><?php echo number_format(($row["total"]/$summarytotal) * 100, 2, '.', ''); ?></div></td>
                        </tr>

                    <?php }    ?>
                    <tr>
                        <td class="NormalText  bold"><strong>Total Summary</strong></td>
                        <td bgcolor="#F2F2F2" class="NormalText bold"><div align="right"><strong><?php echo $qty; ?></strong></div></td>
                        <td bgcolor="#F2F2F2" class="NormalText bold"><div align="right"><strong><?php echo $amount; ?></strong></div></td>
                        <td bgcolor="#F2F2F2" class="NormalText bold"><div align="right"><strong><?php echo $grat; ?></strong></div></td>
                        <td bgcolor="#F2F2F2" class="NormalText bold"><div align="right"><strong><?php echo $tip; ?></strong></div></td>
                        <td bgcolor="#F2F2F2" class="NormalText bold"><div align="right"><strong><?php echo $total; ?></strong></div></td>
                        <td bgcolor="#F2F2F2" class="NormalText bold"><div align="right"><strong><?php echo "100.00"; ?></strong></div></td>
                    </tr>
                </table>
            </div>
            <div class="col-sm-12">
                <hr/>
                <h4 style="color:#757575" class="text-center">Comps Breakdown</h4>
                <?php
                // -------------------------- COMPS BREAKDOWN --------------------------------
                // LOGIC
                // 1. Split by chcompname
                // 2. Split by chchecknum
                // 3. List ciitemname
                $resulttotal =  GetCompsSummaryGrandTotal($sumid);
                $rowtotal = mysql_fetch_array($resulttotal);
                $result = GetCompsBreakdown($sumid);
                ?>
                <table width="100%" border="0" cellpadding="2">
                    <?php
                    $catname = "";
                    $oldcatname = "";
                    $firstcat = "";

                    $checknumber = "";
                    $oldchecknumber = "";
                    $firstcheck = "";

                    $checkqty = 0;
                    $checkamount = 0;
                    $checkpercent = 0;

                    while($row = mysql_fetch_array($result)) { // MAIN LOOP

                        if($oldcatname == "") { // Set first cat
                            $oldcatname = $row["chcompname"];
                            $catname = $row["chcompname"];
                            $firstcat = true;
                        }
                        $catname = $row["chcompname"]; // Set current cat.
// Write Cat header if required ------------------
                        if($catname <> $oldcatname && $firstcat == false) {
                            ?>
                            <tr>
                                <td height="16" colspan="9" class="NormalText"><hr size="1" /></td>
                            </tr>
                            <tr>
                                <td colspan="3" class="NormalText"><div align="left"><strong><?php echo $oldcatname." "; ?>Total</strong></div></td>
                                <td class="NormalText">&nbsp;</td>
                                <td bgcolor="#F2F2F2" class="NormalText"><div align="right"><strong><?php echo $checkqty; $checkqty = 0;?></strong></div></td>
                                <td bgcolor="#F2F2F2" class="NormalText"><div align="right"><strong><?php echo number_format($checkamount, 2, '.', ''); $checkamount = 0;?></strong></div></td>
                                <td bgcolor="#F2F2F2" class="NormalText"><div align="right"><strong><?php echo number_format($checkpercent, 2, '.', '') ; $checkpercent = 0;?></strong></div></td>
                                <td class="NormalText">&nbsp;</td>
                                <td class="NormalText">&nbsp;</td>
                            </tr>
                            <tr>
                                <td height="20" colspan="9" class="NormalText"><hr size="1" /></td>
                            </tr>
                            <?php
                        }
                        if($catname <> $oldcatname || $firstcat == true) {
                            ?>
                            <tr>
                                <td height="31" colspan="9" bgcolor="#007CCF" class="BreadCrumb"><?php echo $row["chcompname"]; ?></td>
                            </tr>

                            <?php
                            $firstcat = false;
                            $oldcatname = $catname;
                        }
// Check Number line ------------------------------------------------
                        if($oldchecknumber == "") { // Set first check
                            $oldchecknumber = $row["chchecknum"];
                            $checknumber = $row["chchecknum"];
                            $firstcheck = true;
                        }
                        $checknumber = $row["chchecknum"]; // Set current cat.
                        if($checknumber <> $oldchecknumber || $firstcheck == true) {
                            ?>
                            <tr>
                                <td height="21" colspan="9" class="NormalText">&nbsp;</td>
                            </tr>
                            <tr>
                                <td width="9%" height="21" bgcolor="#F2F2F2" class="NormalText"><strong>Chk#</strong></td>
                                <td width="7%" bgcolor="#F2F2F2" class="NormalText"><div align="center"><strong>Time</strong></div></td>
                                <td width="22%" bgcolor="#F2F2F2" class="NormalText"><strong>Name</strong></td>
                                <td width="7%" bgcolor="#F2F2F2" class="NormalText"><div align="center"><strong>Unit</strong></div></td>
                                <td width="6%" bgcolor="#F2F2F2" class="NormalText"><div align="right"><strong>Qty</strong></div></td>
                                <td width="8%" bgcolor="#F2F2F2" class="NormalText"><div align="right"><strong>Amount</strong></div></td>
                                <td width="8%" bgcolor="#F2F2F2" class="NormalText"><div align="right"><strong>% Tot</strong></div></td>
                                <td width="15%" bgcolor="#F2F2F2" class="NormalText"><strong>Employee</strong></td>
                                <td width="18%" bgcolor="#F2F2F2" class="NormalText"><strong>Manager</strong></td>
                            </tr>
                            <tr>
                                <td bgcolor="#F2F2F2" class="NormalText"><?php echo $row["chchecknum"]; ?></td>
                                <td bgcolor="#F2F2F2" class="NormalText"><div align="center"><?php echo $row["chtime"]; ?></div>		      </td>
                                <td bgcolor="#F2F2F2" class="NormalText"><?php echo $row["chcustname"]; ?></td>
                                <td bgcolor="#F2F2F2" class="NormalText"><div align="right"><?php echo $row["chunit"]; ?></div></td>
                                <td bgcolor="#F2F2F2" class="NormalText"><div align="right"><?php echo $row["chquantity"]; $checkqty = $checkqty + $row["chquantity"] ; ?></div></td>
                                <td bgcolor="#F2F2F2" class="NormalText"><div align="right"><?php echo $row["chamount"]; $checkamount = $checkamount + $row["chamount"] ;?></div></td>
                                <td bgcolor="#F2F2F2" class="NormalText"><div align="right"><?php
                                        if($rowtotal["amount"] > 0) {
                                            $pertemp = ($row["chamount"]/$rowtotal["amount"])*100;
                                            echo number_format(($row["chamount"]/$rowtotal["amount"])*100, 2, '.', '');;
                                            $checkpercent = $checkpercent + $pertemp ;
                                        } else {
                                            echo '0.00';
                                        }
                                        ?></div></td>
                                <td bgcolor="#F2F2F2" class="NormalText"><?php echo $row["chemployee"]; ?></td>
                                <td bgcolor="#F2F2F2" class="NormalText"><?php echo $row["chmanager"]; ?></td>
                            </tr>

                            <?php
                            $oldchecknumber = $checknumber;
                            $firstcheck = false;
                        } // Check number line
                        ?>
                        <tr>
                            <td class="NormalText">&nbsp;</td>
                            <td colspan="2" class="NormalText"><?php echo $row["ciitemname"]; ?></td>
                            <td colspan="2" class="NormalText"><div align="right"><?php echo $row["ciitemamount"]; ?></div></td>
                            <td class="NormalText">&nbsp;</td>
                            <td colspan="3" class="NormalText">&nbsp;</td>
                        </tr>

                        <?php
                    } // MAIN LOOP
                    ?>
                    <tr>
                        <td height="16" colspan="9" class="NormalText"><hr size="1" /></td>
                    </tr>
                    <tr>
                        <td colspan="3" class="NormalText bold"><div align="left"><strong><?php echo $oldcatname." "; ?>Total</strong></div></td>
                        <td class="NormalText">&nbsp;</td>
                        <td bgcolor="#F2F2F2" class="NormalText bold"><div align="right"><strong><?php echo $checkqty; $checkqty = 0;?></strong></div></td>
                        <td bgcolor="#F2F2F2" class="NormalText bold"><div align="right"><strong><?php echo number_format($checkamount, 2, '.', ''); $checkamount = 0;?></strong></div></td>
                        <td bgcolor="#F2F2F2" class="NormalText bold"><div align="right"><strong>
                                    <?php
                                    if($rowtotal["amount"] > 0) {
                                        echo number_format($checkpercent, 2, '.', '') ;
                                        $checkpercent = 0;
                                    } else {
                                        echo '0.00';
                                    }
                                    ?></strong></div></td>
                        <td class="NormalText">&nbsp;</td>
                        <td class="NormalText">&nbsp;</td>
                    </tr>
                    <tr>
                        <td height="20" colspan="9" class="NormalText"><hr size="1" /></td>
                    </tr>
                </table>

                <div align="center">
                    <?php
                    // COMPS BREAKDOWN DONE
                    $resulttotal =  GetCompsSummaryGrandTotal($sumid);
                    $rowtotal = mysql_fetch_array($resulttotal);

                    // COMP SUMMARY ----------------------------------------------------
                    $result = GetCompsSummaryTotals($sumid);
                    ?>
                </div>
            </div>
            <div class="col-sm-12">
                <hr/>
                <h4 style="color:#757575" class="text-center">Comps Summary</h4>
                <table class="table table-condensed">
                    <tr>
                        <td width="53%" bgcolor="#007CCF" class="NormalText"><span
                                    class="style1"><strong>Comp Type</strong></span></td>
                        <td width="12%" bgcolor="#007CCF" class="NormalText">
                            <div align="right" class="style1"><strong>Qty</strong></div>
                        </td>
                        <td width="16%" bgcolor="#007CCF" class="NormalText">
                            <div align="right" class="style1"><strong>Amount</strong></div>
                        </td>
                        <td width="19%" bgcolor="#007CCF" class="NormalText">
                            <div align="right" class="style1"><strong>% Total</strong></div>
                        </td>
                    </tr>
                    <?php
                    $qty = 0;
                    $amount = 0;
                    $totalpercent = 0;

                    while ($row = mysql_fetch_array($result)) { ?>
                        <tr>
                            <td class="NormalText"><?php echo $row["chcompname"]; ?></td>
                            <td class="NormalText">
                                <div align="right"><?php echo $row["qty"];
                                    $qty = $qty + $row["qty"]; ?></div>
                            </td>
                            <td class="NormalText">
                                <div align="right"><?php echo number_format($row["amount"], 2, '.', '');
                                    $amount = $amount + $row["amount"]; ?></div>
                            </td>
                            <td class="NormalText">
                                <div align="right"><?php
                                    if ($rowtotal["amount"] > 0) {
                                        echo number_format(($row["amount"] / $rowtotal["amount"]) * 100, 2, '.', '');
                                    } else {
                                        echo '0.00';
                                    }
                                    ?></div>
                            </td>
                        </tr>

                    <?php } ?>
                    <tr>
                        <td bgcolor="#F2F2F2" class="NormalText bold"><strong>Total</strong></td>
                        <td bgcolor="#F2F2F2" class="NormalText bold">
                            <div align="right"><strong><?php echo $qty; ?></strong></div>
                        </td>
                        <td bgcolor="#F2F2F2" class="NormalText bold">
                            <div align="right"><strong><?php echo number_format($amount, 2, '.', ''); ?></strong></div>
                        </td>
                        <td bgcolor="#F2F2F2" class="NormalText bold">
                            <div align="right"><strong><?php echo "100.00" ?></strong></div>
                        </td>
                    </tr>
                </table>
            </div>
            <div class="col-sm-1 col-sm-offset-11">
                        <span class="glyphicon glyphicon-print"
                              onMouseUp="NewWindow('pages/report_payments_print.php?<?php echo "radDate=".$radDate."&dateday=".$_SESSION["dateday"]."&datemonth=".$_SESSION["datemonth"]."&dateyear=".$_SESSION["dateyear"]."&store=".str_replace("'", "^", $_SESSION["store"])."&a=".$a."&datefromday=".$_SESSION["datefromday"]."&datefrommonth=".$_SESSION["datefrommonth"]."&datefromyear=".$_SESSION["datefromyear"]."&datetoday=".$_SESSION["datetoday"]."&datetomonth=".$_SESSION["datetomonth"]."&grpid=".$grpid."&radStores=".$radStores."&datetoyear=".$_SESSION["datetoyear"]; ?>','report_payments','650','500','yes');return false;"></span></a>
            </div>
            <?php
        } else { ?>
      <p align="center" class="NormalText"><br />
        <span class="style2">No results were returned for that query.<br />
        Please try different parameters. </span></p>
<?php }
    }
    ?>
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

    function SetAllGroupsFocus() {
        $("#cmbstoregroup").prop('disabled', true);
    }

    function SetSpecificGroupFocus() {
        $("#cmbstoregroup").prop('disabled', false);
    }

    <?php

    if($radStores == "store" || $radStores == null) {
        echo "SetAllGroupsFocus();";
    }
    if($radStores == "storegroup") {
        echo "SetSpecificGroupFocus();";
    }

    if($radDate == "date") {
        echo "SetSpecificDateFocus();";
    }
    if($radDate == "daterange") {
        echo "SetSpecificDateRangeFocus();";
    }

    ?>


</script>
