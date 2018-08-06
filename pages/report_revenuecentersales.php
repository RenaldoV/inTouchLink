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
                        Revenue Center Sales Report
                    </h3>
                </div>
            </div>
            <form id="frmparameters" name="frmparameters" method="post" action="index.php?p=report_revenuecentersales&a=s"
                  onSubmit="return CheckDateRange(this);">
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
                <input name="Submit" type="submit" class="btn btn-default" value="Submit"
                       style="color: white; background-color: #007CC4"/>
            </form>
        </div>
    </div>
</div>

<div class="col-sm-8 col-sm-offset-2 col-md-10 col-md-offset-1 col-lg-8 col-lg-offset-2">
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
            $title = "Sales Summary Report for ";
            $headings = array($title, '');
            $worksheet->write_row('D6', $headings, $heading);
            // Report Specs
            $$excelreport_title = $excelreport_title;
            $headings = array($excelreport_title, '');
            $worksheet->write_row('D7', $headings, $heading);
            $num1_format =& $workbook->addformat(array(num_format => '0.00'));  //Basic number format
            // ************************************
            ?>
            <?php // START - NET SALES BY CATEGORY  ///////////////////////////////////////   ?>
            <div class="col-sm-12">
                <hr/>
                <h4 style="color:#757575" class="text-center">Net Sales By Category</h4>
                <table class="table table-condensed">
                    <tr>
                        <td width="239" height="18" bgcolor="#487CC4" class="NormalText" style="color: white">
                            <span class="style4">Category</span>
                        </td>
                        <?php
                        // CREATE THE COLUMS FOR EACH REVENUE CENTER
                        $result = GetRevenueCenterNames($sumid);
                        $revenuecentercount = mysql_num_rows($result);
                        while($row = mysql_fetch_array($result)) {
                            ?>
                            <td width="94" bgcolor="#487CC4" class="NormalText" style="color: white">
                                <div align="right" class="style4">
                                    <?php echo $row["revenuecentername"]; ?>
                                </div>
                            </td>
                        <?php  } // Colums created ?>
                        <td width="69" bgcolor="#487CC4" class="NormalText" style="color: white">
                            <div align="right" class="style4"> Total</div>
                        </td>
                    </tr>
                    <?php
                    // ROW - WHILE IN SAME SALES CAT
                    $result = GetNetSalesByCategory($sumid); // Get all records

                    $totalarray = array();
                    while ($row = mysql_fetch_array($result)) {
                        ?>
                        <tr>
                            <td height="23" class="NormalText">
                                <div align="left"><strong><?php echo $row["salescatname"]; ?></strong></div>
                            </td>
                            <?php
                            $cattotal = 0;
                            for ($i = 0; $i < $revenuecentercount; $i++) { // Go through revenue center count while in same sales category
                                ?>
                                <td class="NormalText">
                                    <div align="right">
                                        <?php echo $row["netsales"];
                                        $cattotal = $cattotal + $row["netsales"];
                                        $grandtotal = $grandtotal + $row["netsales"];
                                        $totalarray[$i] = $totalarray[$i] + $row["netsales"];
                                        ?>
                                    </div>
                                </td>
                                <?php
                                if ($i < $revenuecentercount - 1) {
                                    $row = mysql_fetch_array($result);
                                }
                            } ?>
                            <td class="NormalText">
                                <div align="right"><?php echo number_format($cattotal, 2, '.', ''); ?>
                                </div>
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                    <tr>
                        <td height="23" bgcolor="#F2F2F2" class="NormalText bold"><strong>Totals</strong></td>

                        <?php for ($i = 0; $i < $revenuecentercount; $i++) { ?>
                            <td bgcolor="#F2F2F2" class="NormalText bold">
                                <div align="right"><?php echo number_format($totalarray[$i], 2, '.', ''); ?></div>
                            </td>
                            <?php
                        } ?>

                        <td bgcolor="#F2F2F2" class="NormalText bold">
                            <div align="right"><?php echo number_format($grandtotal, 2, '.', ''); ?></div>
                        </td>
                    </tr>
                </table>
            </div>
            <?php // END - NET SALES BY CATEGORY  ///////////////////////////////////////   ?>

            <?php // START - NET SALES BY CATEGORY (NON SALES CATEGORIES) ///////////////////////////////////////   ?>
            <div class="col-sm-12">
                <h4 style="color:#757575" class="text-center">Net Sales By Category(Non Sales Categories)</h4>
                <table class="table table-condensed">
                    <tr>
                        <td width="240" height="18" bgcolor="#487CC4" class="NormalText" style="color: white">
                            <span class="style4">Category</span>
                        </td>
                        <?php
                        // CREATE THE COLUMS FOR EACH REVENUE CENTER
                        $result = GetRevenueCenterNames($sumid);
                        $revenuecentercount = mysql_num_rows($result);
                        while ($row = mysql_fetch_array($result)) {
                            ?>
                            <td width="94" bgcolor="#487CC4" class="NormalText" style="color: white">
                                <div align="right" class="style4"><?php echo $row["revenuecentername"]; ?></div>
                            </td>
                        <?php } // Colums created ?>
                        <td width="87" bgcolor="#487CC4" class="NormalText" style="color: white">
                            <div align="right" class="style4"> Total</div>
                        </td>
                    </tr>
                    <?php
                    // ROW - WHILE IN SAME SALES CAT
                    // Reset Totals
                    $grandtotal = 0;
                    $result = GetNetSalesByCategoryNonSalesCat($sumid);
                    $totalarray = array();
                    while ($row = mysql_fetch_array($result)) {
                        ?>
                        <tr>
                            <td height="23" class="NormalText">
                                <div align="left"><strong><?php echo $row["nscname"]; ?></strong></div>
                            </td>
                            <?php
                            $cattotal = 0;
                            for ($i = 0; $i < $revenuecentercount; $i++) {
                                ?>
                                <td class="NormalText">
                                    <div align="right">
                                        <?php echo $row["netsales"];
                                        $cattotal = $cattotal + $row["netsales"];
                                        $grandtotal = $grandtotal + $row["netsales"];
                                        $totalarray[$i] = $totalarray[$i] + $row["netsales"];
                                        ?>
                                    </div>
                                </td>
                                <?php
                                if ($i < $revenuecentercount - 1) {
                                    $row = mysql_fetch_array($result);
                                }
                            } ?>

                            <td class="NormalText">
                                <div align="right"><?php echo number_format($cattotal, 2, '.', ''); ?>
                                </div>
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                    <tr>
                        <td height="23" bgcolor="#F2F2F2" class="NormalText bold"><strong>Totals</strong></td>

                        <?php for ($i = 0; $i < $revenuecentercount; $i++) {
                            ?>
                            <td bgcolor="#F2F2F2" class="NormalText bold">
                                <div align="right"><?php echo number_format($totalarray[$i], 2, '.', ''); ?></div>
                            </td>
                            <?php
                        } ?>


                        <td bgcolor="#F2F2F2" class="NormalText bold">
                            <div align="right"><?php echo number_format($grandtotal, 2, '.', ''); ?></div>
                        </td>
                    </tr>
                </table>
            </div>
            <?php // END - NET SALES BY CATEGORY (NON SALES CATEGORIES) ///////////////////////////////////////   ?>

            <?php // START - NET SALES BY DAY PART ///////////////////////////////////////   ?>
            <div class="col-sm-12">
                <h4 style="color:#757575" class="text-center">Net Sales By Day Part</h4>
                <table class="table table-condensed">
                    <tr>
                        <td width="240" height="18" bgcolor="#487CC4" class="NormalText" style="color: white">
                            <span class="style4">Day Part</span>
                        </td>
                        <?php
                        // Reset Totals
                        $grandtotal = 0;
                        $cattotal = 0;
                        unset($totalarray);
                        // CREATE THE COLUMS FOR EACH REVENUE CENTER
                        $result = GetRevenueCenterNames($sumid);
                        $revenuecentercount = mysql_num_rows($result);
                        while ($row = mysql_fetch_array($result)) {
                            ?>
                            <td width="94" bgcolor="#487CC4" class="NormalText" style="color: white">
                                <div align="right" class="style4"><?php echo $row["revenuecentername"]; ?></div>
                            </td>
                        <?php } // Colums created ?>
                        <td width="87" bgcolor="#487CC4" class="NormalText" style="color: white">
                            <div align="right" class="style4"> Total</div>
                        </td>
                    </tr>
                    <?php
                    // ROW - WHILE IN SAME SALES CAT
                    $result = GetNetSalesByDayPart($sumid);
                    $totalarray = array();
                    while ($row = mysql_fetch_array($result)) {
                        ?>
                        <tr>
                            <td height="23" class="NormalText">
                                <div align="left"><strong><?php echo $row["dpname"]; ?></strong></div>
                            </td>
                            <?php
                            $cattotal = 0;
                            for ($i = 0; $i < $revenuecentercount; $i++) {
                                ?>
                                <td class="NormalText">
                                    <div align="right">
                                        <?php
                                        echo $row["netsales"];
                                        $cattotal = $cattotal + $row["netsales"];
                                        $grandtotal = $grandtotal + $row["netsales"];
                                        $totalarray[$i] = $totalarray[$i] + $row["netsales"];
                                        ?> </div>
                                </td>
                                <?php
                                if ($i < $revenuecentercount - 1) {
                                    $row = mysql_fetch_array($result);
                                }
                            } ?>
                            <td class="NormalText">
                                <div align="right"><?php echo number_format($cattotal, 2, '.', ''); ?> </div>
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                    <tr>
                        <td height="23" bgcolor="#F2F2F2" class="NormalText bold"><strong>Totals</strong></td>
                        <?php for ($i = 0; $i < $revenuecentercount; $i++) {
                            ?>
                            <td bgcolor="#F2F2F2" class="NormalText bold">
                                <div align="right"><?php echo number_format($totalarray[$i], 2, '.', ''); ?></div>
                            </td>
                            <?php
                        } ?>
                        <td bgcolor="#F2F2F2" class="NormalText bold">
                            <div align="right"><?php echo number_format($grandtotal, 2, '.', ''); ?></div>
                        </td>
                    </tr>
                </table>
            </div>
            <?php // END - NET SALES BY DAY PART ///////////////////////////////////////   ?>

            <?php // START - BANKING SALES BY DAY PART ///////////////////////////////////////   ?>
            <div class="col-sm-12">
                <h4 style="color:#757575" class="text-center">Banking Sales By Day Part</h4>
                <table class="table table-condensed">
                    <tr>
                        <td width="240" height="18" bgcolor="#487CC4" class="NormalText" style="color: white">
                            <span class="style4">Day Part</span>
                        </td>
                        <?php
                        // Reset Totals
                        $grandtotal = 0;
                        $cattotal = 0;
                        unset($totalarray);
                        // CREATE THE COLUMS FOR EACH REVENUE CENTER
                        $result = GetRevenueCenterNames($sumid);
                        $revenuecentercount = mysql_num_rows($result);
                        while ($row = mysql_fetch_array($result)) {
                            ?>
                            <td width="94" bgcolor="#487CC4" class="NormalText" style="color: white">
                                <div align="right" class="style4"><?php echo $row["revenuecentername"]; ?></div>
                            </td>
                        <?php } // Colums created ?>
                        <td width="87" bgcolor="#487CC4" class="NormalText" style="color: white">
                            <div align="right" class="style4"> Total</div>
                        </td>
                    </tr>
                    <?php
                    // ROW - WHILE IN SAME SALES CAT
                    $result = GetBankingSalesByDayPart($sumid);
                    $totalarray = array();
                    while ($row = mysql_fetch_array($result)) {
                        ?>
                        <tr>
                            <td height="23" class="NormalText">
                                <div align="left"><strong><?php echo $row["dpname"]; ?></strong></div>
                            </td>
                            <?php
                            $cattotal = 0;
                            for ($i = 0; $i < $revenuecentercount; $i++) {
                                ?>
                                <td class="NormalText">
                                    <div align="right"> <?php echo $row["bankingsales"];
                                        $cattotal = $cattotal + $row["bankingsales"];
                                        $grandtotal = $grandtotal + $row["bankingsales"];
                                        $totalarray[$i] = $totalarray[$i] + $row["bankingsales"];
                                        ?> </div>
                                </td>
                                <?php
                                if ($i < $revenuecentercount - 1) {
                                    $row = mysql_fetch_array($result);
                                }
                            } ?>
                            <td class="NormalText">
                                <div align="right"><?php echo number_format($cattotal, 2, '.', ''); ?> </div>
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                    <tr>
                        <td height="23" bgcolor="#F2F2F2" class="NormalText bold"><strong>Totals</strong></td>
                        <?php for ($i = 0; $i < $revenuecentercount; $i++) {
                            ?>
                            <td bgcolor="#F2F2F2" class="NormalText bold">
                                <div align="right"><?php echo number_format($totalarray[$i], 2, '.', ''); ?></div>
                            </td>
                            <?php
                        } ?>
                        <td bgcolor="#F2F2F2" class="NormalText bold">
                            <div align="right"><?php echo number_format($grandtotal, 2, '.', ''); ?></div>
                        </td>
                    </tr>
                </table>
            </div>
            <?php // END - BANKING SALES BY DAY PART ///////////////////////////////////////   ?>

            <?php // START - NUMBER OF GUESTS ///////////////////////////////////////   ?>
            <div class="col-sm-12">
                <h4 style="color:#757575" class="text-center">Number of Guests</h4>
                <table class="table table-condensed">
                    <tr>
                        <td width="240" height="18" bgcolor="#487CC4" class="NormalText" style="color: white">
                            <span class="style4">Day Part</span>
                        </td>
                        <?php
                        // Reset Totals
                        $grandtotal = 0;
                        $cattotal = 0;
                        unset($totalarray);

                        // CREATE THE COLUMS FOR EACH REVENUE CENTER
                        $result = GetRevenueCenterNames($sumid);
                        $revenuecentercount = mysql_num_rows($result);
                        while ($row = mysql_fetch_array($result)) {
                            ?>
                            <td width="98" bgcolor="#487CC4" class="NormalText" style="color: white">
                                <div align="right" class="style4"><?php echo $row["revenuecentername"]; ?></div>
                            </td>
                        <?php } // Colums created ?>
                        <td width="87" bgcolor="#487CC4" class="NormalText" style="color: white">
                            <div align="right" class="style4"> Total</div>
                        </td>
                    </tr>
                    <?php
                    // ROW - WHILE IN SAME SALES CAT

                    $result = GetGuestsByDayPart($sumid);

                    $totalarray = array();
                    while($row = mysql_fetch_array($result)) {
                        ?>
                        <tr>
                            <td height="23" class="NormalText"><div align="left"><strong><?php echo $row["dpname"];?></strong></div></td>
                            <?php
                            $cattotal = 0;
                            for($i=0;$i<$revenuecentercount;$i++) {
                                ?>
                                <td class="NormalText"><div align="right">
                                        <?php
                                        echo $row["headcount"];
                                        $cattotal = $cattotal + $row["headcount"];
                                        $grandtotal = $grandtotal + $row["headcount"];
                                        $totalarray[$i] = $totalarray[$i] + $row["headcount"];
                                        ?> </div></td>
                                <?php
                                if($i < $revenuecentercount - 1) {
                                    $row = mysql_fetch_array($result);
                                }
                            } ?>
                            <td class="NormalText"><div align="right"><?php echo number_format($cattotal, 0, '.', ''); ?> </div></td>
                        </tr>
                        <?php
                    }
                    ?>
                    <tr>
                        <td height="23" bgcolor="#F2F2F2" class="NormalText bold"><strong>Totals</strong></td>
                        <?php   for($i=0;$i<$revenuecentercount;$i++) {
                            ?>
                            <td bgcolor="#F2F2F2" class="NormalText bold"><div align="right"><?php echo number_format($totalarray[$i], 0, '.', ''); ?></div></td>
                            <?php
                        } ?>
                        <td bgcolor="#F2F2F2" class="NormalText bold"><div align="right"><?php echo number_format($grandtotal, 0, '.', ''); ?></div></td>
                    </tr>
                </table>
            </div>
            <?php // END - NUMBER OF GUESTS ///////////////////////////////////////   ?>

            <?php // START - NUMBER OF CHECKS ///////////////////////////////////////   ?>
            <div class="col-sm-12">
                <h4 style="color:#757575" class="text-center">Number of Checks</h4>
                <table class="table table-condensed">
                    <tr>
                        <td width="240" height="18" bgcolor="#487CC4" class="NormalText" style="color: white">
                            <span class="style4">Day Part</span>
                        </td>
                        <?php
                        // Reset Totals
                        $grandtotal = 0;
                        $cattotal = 0;
                        unset($totalarray);
                        // CREATE THE COLUMS FOR EACH REVENUE CENTER
                        $result = GetRevenueCenterNames($sumid);
                        $revenuecentercount = mysql_num_rows($result);
                        while ($row = mysql_fetch_array($result)) {
                            ?>
                            <td width="98" bgcolor="#487CC4" class="NormalText" style="color: white">
                                <div align="right" class="style4"><?php echo $row["revenuecentername"]; ?></div>
                            </td>
                        <?php } // Colums created ?>
                        <td width="87" bgcolor="#487CC4" class="NormalText" style="color: white">
                            <div align="right" class="style4"> Total</div>
                        </td>
                    </tr>
                    <?php
                    // ROW - WHILE IN SAME SALES CAT
                    $result = GetCheckCountByDayPart($sumid);
                    $totalarray = array();
                    while ($row = mysql_fetch_array($result)) {
                        ?>
                        <tr>
                            <td height="23" class="NormalText">
                                <div align="left"><strong><?php echo $row["dpname"]; ?></strong></div>
                            </td>
                            <?php
                            $cattotal = 0;
                            for ($i = 0; $i < $revenuecentercount; $i++) {
                                ?>
                                <td class="NormalText">
                                    <div align="right"> <?php echo $row["checkcount"];
                                        $cattotal = $cattotal + $row["checkcount"];
                                        $grandtotal = $grandtotal + $row["checkcount"];
                                        $totalarray[$i] = $totalarray[$i] + $row["checkcount"];
                                        ?> </div>
                                </td>
                                <?php
                                if ($i < $revenuecentercount - 1) {
                                    $row = mysql_fetch_array($result);
                                }
                            } ?>
                            <td class="NormalText">
                                <div align="right"><?php echo number_format($cattotal, 0, '.', ''); ?> </div>
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                    <tr>
                        <td height="23" bgcolor="#F2F2F2" class="NormalText bold"><strong>Totals</strong></td>
                        <?php for ($i = 0; $i < $revenuecentercount; $i++) {
                            ?>
                            <td bgcolor="#F2F2F2" class="NormalText bold">
                                <div align="right"><?php echo number_format($totalarray[$i], 0, '.', ''); ?></div>
                            </td>
                            <?php
                        } ?>
                        <td bgcolor="#F2F2F2" class="NormalText bold">
                            <div align="right"><?php echo number_format($grandtotal, 0, '.', ''); ?></div>
                        </td>
                    </tr>
                </table>
            </div>
            <?php // END - NUMBER OF CHECKS ///////////////////////////////////////   ?>

            <?php // START -TAXES BY TAX ID ///////////////////////////////////////   ?>
            <div class="col-sm-12">
                <h4 style="color:#757575" class="text-center">Taxes By Tax ID</h4>
                <table class="table table-condensed">
                    <tr>
                        <td width="240" height="18" bgcolor="#487CC4" class="NormalText" style="color: white">
                            <span class="style4">Tax</span>
                        </td>
                        <?php
                        // Reset Totals
                        $grandtotal = 0;
                        $cattotal = 0;
                        unset($totalarray);

                        // CREATE THE COLUMS FOR EACH REVENUE CENTER
                        $result = GetRevenueCenterNames($sumid);
                        $revenuecentercount = mysql_num_rows($result);
                        while ($row = mysql_fetch_array($result)) {
                            ?>
                            <td width="98" bgcolor="#487CC4" class="NormalText" style="color: white">
                                <div align="right" class="style4"><?php echo $row["revenuecentername"]; ?></div>
                            </td>
                        <?php } // Colums created ?>
                        <td width="87" bgcolor="#487CC4" class="NormalText" style="color: white">
                            <div align="right" class="style4"> Total</div>
                        </td>
                    </tr>
                    <?php
                    // ROW - WHILE IN SAME SALES CAT
                    $result = GetRevenueCenterTax($sumid);
                    $totalarray = array();
                    while ($row = mysql_fetch_array($result)) {
                        ?>
                        <tr>
                            <td height="23" class="NormalText">
                                <div align="left"><strong><?php echo $row["taxname"]; ?></strong></div>
                            </td>
                            <?php
                            $cattotal = 0;
                            for ($i = 0; $i < $revenuecentercount; $i++) {
                                ?>
                                <td class="NormalText">
                                    <div align="right"> <?php echo $row["taxamount"];
                                        $cattotal = $cattotal + $row["taxamount"];
                                        $grandtotal = $grandtotal + $row["taxamount"];
                                        $totalarray[$i] = $totalarray[$i] + $row["taxamount"];
                                        ?> </div>
                                </td>
                                <?php
                                if ($i < $revenuecentercount - 1) {
                                    $row = mysql_fetch_array($result);
                                }
                            } ?>
                            <td class="NormalText">
                                <div align="right"><?php echo number_format($cattotal, 2, '.', ''); ?> </div>
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                    <tr>
                        <td height="23" bgcolor="#F2F2F2" class="NormalText bold"><strong>Totals</strong></td>
                        <?php for ($i = 0; $i < $revenuecentercount; $i++) {
                            ?>
                            <td bgcolor="#F2F2F2" class="NormalText bold">
                                <div align="right"><?php echo number_format($totalarray[$i], 2, '.', ''); ?></div>
                            </td>
                            <?php
                        } ?>
                        <td bgcolor="#F2F2F2" class="NormalText bold">
                            <div align="right"><?php echo number_format($grandtotal, 2, '.', ''); ?></div>
                        </td>
                    </tr>
                </table>
            </div>
            <?php // END - TAXES BY TAX ID ///////////////////////////////////////   ?>

            <?php // START - COMPS ///////////////////////////////////////   ?>
            <div class="col-sm-12">
                <h4 style="color:#757575" class="text-center">Comps</h4>
                <table class="table table-condensed">
                    <tr>
                        <td width="240" height="18" bgcolor="#487CC4" class="NormalText" style="color: white">
                            <span class="style4">Comp</span>
                        </td>
                        <?php
                        // Reset Totals
                        $grandtotal = 0;
                        $cattotal = 0;
                        unset($totalarray);

                        // CREATE THE COLUMS FOR EACH REVENUE CENTER
                        $result = GetRevenueCenterNames($sumid);
                        $revenuecentercount = mysql_num_rows($result);
                        while ($row = mysql_fetch_array($result)) {
                            ?>
                            <td width="98" bgcolor="#487CC4" class="NormalText" style="color: white">
                                <div align="right" class="style4"><?php echo $row["revenuecentername"]; ?></div>
                            </td>
                        <?php } // Colums created ?>
                        <td width="87" bgcolor="#487CC4" class="NormalText" style="color: white">
                            <div align="right" class="style4"> Total</div>
                        </td>
                    </tr>
                    <?php
                    // ROW - WHILE IN SAME SALES CAT
                    $result = GetRevenueCenterComps($sumid);
                    $totalarray = array();
                    while ($row = mysql_fetch_array($result)) {
                        ?>
                        <tr>
                            <td height="23" class="NormalText">
                                <div align="left"><strong><?php echo $row["compname"]; ?></strong></div>
                            </td>
                            <?php
                            $cattotal = 0;
                            for ($i = 0; $i < $revenuecentercount; $i++) {
                                ?>
                                <td class="NormalText">
                                    <div align="right"> <?php echo $row["compamount"];
                                        $cattotal = $cattotal + $row["compamount"];
                                        $grandtotal = $grandtotal + $row["compamount"];
                                        $totalarray[$i] = $totalarray[$i] + $row["compamount"];
                                        ?> </div>
                                </td>
                                <?php
                                if ($i < $revenuecentercount - 1) {
                                    $row = mysql_fetch_array($result);
                                }
                            } ?>
                            <td class="NormalText">
                                <div align="right"><?php echo number_format($cattotal, 2, '.', ''); ?> </div>
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                    <tr>
                        <td height="23" bgcolor="#F2F2F2" class="NormalText bold"><strong>Totals</strong></td>
                        <?php for ($i = 0; $i < $revenuecentercount; $i++) {
                            ?>
                            <td bgcolor="#F2F2F2" class="NormalText bold">
                                <div align="right"><?php echo number_format($totalarray[$i], 2, '.', ''); ?></div>
                            </td>
                            <?php
                        } ?>
                        <td bgcolor="#F2F2F2" class="NormalText bold">
                            <div align="right"><?php echo number_format($grandtotal, 2, '.', ''); ?></div>
                        </td>
                    </tr>
                </table>
            </div>
            <?php // END - COMPS ///////////////////////////////////////   ?>

            <?php // START - PAYMENTS ///////////////////////////////////////   ?>
            <div class="col-sm-12">
                <h4 style="color:#757575" class="text-center">Payments</h4>
                <table class="table table-condensed">
                    <tr>
                        <td width="240" height="18" bgcolor="#487CC4" class="NormalText" style="color: white">
                            <span class="style4">Payments</span>
                        </td>
                        <?php
                        // Reset Totals
                        $grandtotal = 0;
                        $cattotal = 0;
                        unset($totalarray);

                        // CREATE THE COLUMS FOR EACH REVENUE CENTER
                        $result = GetRevenueCenterNames($sumid);
                        $revenuecentercount = mysql_num_rows($result);
                        while ($row = mysql_fetch_array($result)) {
                            ?>
                            <td width="98" bgcolor="#487CC4" class="NormalText" style="color: white">
                                <div align="right" class="style4"><?php echo $row["revenuecentername"]; ?></div>
                            </td>
                        <?php } // Colums created ?>
                        <td width="87" bgcolor="#487CC4" class="NormalText" style="color: white">
                            <div align="right" class="style4"> Total</div>
                        </td>
                    </tr>
                    <?php
                    // ROW - WHILE IN SAME SALES CAT

                    $result = GetRevenueCenterPayments($sumid);

                    $totalarray = array();
                    while ($row = mysql_fetch_array($result)) {
                        ?>
                        <tr>
                            <td height="23" class="NormalText">
                                <div align="left"><strong><?php echo $row["pmttype"]; ?></strong></div>
                            </td>
                            <?php
                            $cattotal = 0;
                            for ($i = 0; $i < $revenuecentercount; $i++) {
                                ?>
                                <td class="NormalText">
                                    <div align="right">
                                        <?php
                                        echo $row["paymentamount"];
                                        $cattotal = $cattotal + $row["paymentamount"];
                                        $grandtotal = $grandtotal + $row["paymentamount"];
                                        $totalarray[$i] = $totalarray[$i] + $row["paymentamount"];
                                        ?> </div>
                                </td>
                                <?php
                                if ($i < $revenuecentercount - 1) {
                                    $row = mysql_fetch_array($result);
                                }
                            } ?>
                            <td class="NormalText">
                                <div align="right"><?php echo number_format($cattotal, 2, '.', ''); ?> </div>
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                    <tr>
                        <td height="23" bgcolor="#F2F2F2" class="NormalText bold"><strong>Totals</strong></td>
                        <?php for ($i = 0; $i < $revenuecentercount; $i++) {
                            ?>
                            <td bgcolor="#F2F2F2" class="NormalText bold">
                                <div align="right"><?php echo number_format($totalarray[$i], 2, '.', ''); ?></div>
                            </td>
                            <?php
                        } ?>
                        <td bgcolor="#F2F2F2" class="NormalText bold">
                            <div align="right"><?php echo number_format($grandtotal, 2, '.', ''); ?></div>
                        </td>
                    </tr>
                </table>
            </div>
            <?php // END - PAYMENTS ///////////////////////////////////////   ?>

            <?php // START - TIPS ///////////////////////////////////////   ?>
            <div class="col-sm-12">
                <h4 style="color:#757575" class="text-center">Tips</h4>
                <table class="table table-condensed">
                    <tr>
                        <td width="240" height="18" bgcolor="#487CC4" class="NormalText" style="color: white">
                            <span class="style4">Tip Type</span>
                        </td>
                        <?php
                        // Reset Totals
                        $grandtotal = 0;
                        $cattotal = 0;
                        unset($totalarray);

                        // CREATE THE COLUMS FOR EACH REVENUE CENTER
                        $result = GetRevenueCenterNames($sumid);
                        $revenuecentercount = mysql_num_rows($result);
                        while ($row = mysql_fetch_array($result)) {
                            ?>
                            <td width="98" bgcolor="#487CC4" class="NormalText" style="color: white">
                                <div align="right" class="style4"><?php echo $row["revenuecentername"]; ?></div>
                            </td>
                        <?php } // Colums created ?>
                        <td width="87" bgcolor="#487CC4" class="NormalText" style="color: white">
                            <div align="right" class="style4"> Total</div>
                        </td>
                    </tr>
                    <?php
                    // ROW - WHILE IN SAME SALES CAT
                    $result = GetTips($sumid);
                    $totalarray = array();
                    while ($row = mysql_fetch_array($result)) {
                        ?>
                        <tr>
                            <td height="23" class="NormalText">
                                <div align="left"><strong><?php echo $row["pmttype"]; ?></strong></div>
                            </td>
                            <?php
                            $cattotal = 0;
                            for ($i = 0; $i < $revenuecentercount; $i++) {
                                ?>
                                <td class="NormalText">
                                    <div align="right"> <?php echo $row["tips"];
                                        $cattotal = $cattotal + $row["tips"];
                                        $grandtotal = $grandtotal + $row["tips"];
                                        $totalarray[$i] = $totalarray[$i] + $row["tips"];
                                        ?> </div>
                                </td>
                                <?php
                                if ($i < $revenuecentercount - 1) {
                                    $row = mysql_fetch_array($result);
                                }
                            } ?>
                            <td class="NormalText">
                                <div align="right"><?php echo number_format($cattotal, 2, '.', ''); ?> </div>
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                    <tr>
                        <td height="23" bgcolor="#F2F2F2" class="NormalText bold"><strong>Totals</strong></td>
                        <?php for ($i = 0; $i < $revenuecentercount; $i++) {
                            ?>
                            <td bgcolor="#F2F2F2" class="NormalText bold">
                                <div align="right"><?php echo number_format($totalarray[$i], 2, '.', ''); ?></div>
                            </td>
                            <?php
                        } ?>
                        <td bgcolor="#F2F2F2" class="NormalText bold">
                            <div align="right"><?php echo number_format($grandtotal, 2, '.', ''); ?></div>
                        </td>
                    </tr>
                </table>
            </div>
            <?php // END - TIPS/////////////////////////////////////// ?>
            <div class="col-sm-1 col-sm-offset-11">
                        <span class="glyphicon glyphicon-print"
                              onMouseUp="NewWindow('pages/report_revenuecentersales_print.php?<?php echo "radDate=".$radDate."&dateday=".$_SESSION["dateday"]."&datemonth=".$_SESSION["datemonth"]."&dateyear=".$_SESSION["dateyear"]."&store=".str_replace("'", "^", $_SESSION["store"])."&a=".$a."&datefromday=".$_SESSION["datefromday"]."&datefrommonth=".$_SESSION["datefrommonth"]."&datefromyear=".$_SESSION["datefromyear"]."&datetoday=".$_SESSION["datetoday"]."&datetomonth=".$_SESSION["datetomonth"]."&grpid=".$grpid."&radStores=".$radStores."&datetoyear=".$_SESSION["datetoyear"]; ?>','report_salessummary','650','500','yes');return false;"></span></a>
            </div>
            <?php
        } else {
            ?>
            <p align="center" class="NormalText"><br/>
                <span class="style2">No results were returned for that query.<br/>
        Please try different parameters. </span></p>
            <?php
        }
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


    <?php
    if ($radDate == "date") {
        echo "SetSpecificDateFocus();";
    }
    if ($radDate == "daterange") {
        echo "SetSpecificDateRangeFocus();";
    }
    if ($radDate == Null) {
        echo "SetSpecificDateFocus(); document.frmparameters.radDate[0].checked = true;";
    }
    ?>



function CheckDateRange(somevar) {

	 var result = true;


// CHECK DATE RANGE
if(document.frmparameters.radDate[1].checked == true) {
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
 
 
     
	 if(result == false) {
	    alert('Please ensure that your From Date is before your To Date and that your From date is not later than yesterday');
	 } 

}
	 return result;
 
 }
 
</script>
