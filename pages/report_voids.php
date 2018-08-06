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
                        Voids Report
                    </h3>
                </div>
            </div>
            <form id="frmparameters" name="frmparameters" method="post" action="index.php?p=report_voids&a=s"
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


<div class="col-lg-10 col-lg-offset-1">
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
            // ==============================================================================
            // REPORT OUTPUT STARTS HERE ====================================================
            // Excel Export. Initialize
            set_time_limit(30000);
            require_once "classes/excelexport/class.writeexcel_workbook.inc.php";
            require_once "classes/excelexport/class.writeexcel_worksheet.inc.php";
            $fname = tempnam("/tmp", GenerateReferenceNumber() . ".xls");
            $workbook =& new writeexcel_workbook($fname);
            $worksheet =& $workbook->addworksheet('Report');
            // Set Columns widths
            $worksheet->set_column('A:B', 0.10);
            $worksheet->set_column('C:C', 5.5); // First usuable column
            $worksheet->set_column('D:D', 13.29);
            $worksheet->set_column('E:E', 11.71);
            $worksheet->set_column('F:F', 15.71);
            $worksheet->set_column('G:G', 5.57);
            $worksheet->set_column('H:H', 21);
            $worksheet->set_column('I:I', 6.86);


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
                size => 9,
                merge => 1,
                font => 'Arial',
                align => "left"
            )); // Create new font style
            $LeftNormalTotalBold =& $workbook->addformat(array(
                bold => 1,
                color => '007CC4',
                size => 9,
                merge => 1,
                font => 'Arial',
                align => "left"
            )); // Create new font style
            $RightNumberTotalBold =& $workbook->addformat(array(
                bold => 1,
                color => '007CC4',
                size => 9,
                merge => 1,
                font => 'Arial',
                align => "right"
            )); // Create new font style
            $NormalLeftAlign =& $workbook->addformat(array(
                bold => 0,
                color => '007CC4',
                size => 9,
                merge => 1,
                font => 'Arial',
                align => "left"
            )); // Create new font style
            $NormalRightAlign =& $workbook->addformat(array(
                bold => 0,
                color => '007CC4',
                size => 9,
                merge => 1,
                font => 'Arial',
                align => "right"
            )); // Create new font style

            $color_background_blue =& $workbook->addformat(array(
                fg_color => 22,
            )); // Create new font style

// *******************************************************************
            if ($radDate == "date") {
                $sumIDResult = GetSumIDforWithDateRange("'" . $_SESSION["dateyear"] . "/" . $_SESSION["datemonth"] . "/" . $_SESSION["dateday"] . "'", $_SESSION["store"]);
            }
            if ($radDate == "daterange") {
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
                    echo $_SESSION["dateday"] . "/" . $_SESSION["datemonth"] . "/" . $_SESSION["dateyear"];
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
            // Report Title
            $title = "Voids Report for ";
            $headings = array($title, '');
            $worksheet->write_row('E6', $headings, $heading);

            // Report Specs
            $$excelreport_title = $excelreport_title;
            $headings = array($excelreport_title, '');
            $worksheet->write_row('E7', $headings, $heading);
            $num1_format =& $workbook->addformat(array(num_format => '0.00'));  //Basic number format
            // ************************************
            ?>

            <table class="table table-condensed">
                <tr>
                    <td class="NormalText text-left" bgcolor="#F2F2F2"><strong>Check # </strong></td>
                    <td class="NormalText text-left" bgcolor="#F2F2F2"><strong>Menu Item </strong></td>
                    <td class="NormalText text-left" bgcolor="#F2F2F2"><strong>Reason</strong></td>
                    <td class="NormalText text-left" bgcolor="#F2F2F2"><strong>Manager</strong></td>
                    <td class="NormalText text-left" bgcolor="#F2F2F2"><strong>Time</strong></td>
                    <td class="NormalText text-left" bgcolor="#F2F2F2"><strong>Server</strong></td>
                    <td class="NormalText text-right" bgcolor="#F2F2F2"><strong>Amt</strong></td>
                </tr>

                <?php
                // EXCEL
                // ************************************************
                // Excel
                // Write Table Headers for GROUP
                $title = "Check#";
                $headings = array($title, '');
                $worksheet->write_row('C10', $headings, $heading2);

                $title = "Menu Item";
                $headings = array($title, '');
                $worksheet->write_row('D10', $headings, $heading2);

                $title = "Reason";
                $headings = array($title, '');
                $worksheet->write_row('E10', $headings, $heading2);

                $title = "Manager";
                $headings = array($title, '');
                $worksheet->write_row('F10', $headings, $heading2);

                $title = "Time";
                $headings = array($title, '');
                $worksheet->write_row('G10', $headings, $heading2);

                $title = "Server";
                $headings = array($title, '');
                $worksheet->write_row('H10', $headings, $heading2);

                $title = "Amt";
                $headings = array($title, '');
                $worksheet->write_row('I10', $headings, $heading2);

                $rownumber = 11;
                // end excel

                $result = GetVoidsReport($sumid);
                $voidtotal = 0;
                $voidcounter = 0.00;
                $showtotal = 0;
                $showtotalmanager = 0;
                $server = "null"; // Reset server
                $manager = "null"; // Reset manager
                $voidmanagertotal = 0;
                $voidcountermanager = 0.00;
                $voidgrandtotal = 0;
                $voidgrandcounter = 0;

                while ($row = mysql_fetch_array($result)) {
                    $showtotal = 0;
                    if ($server == "null" && $manager == "null") { // Set first server and manager
                        $server = $row["vbrserver"];
                        $manager = $row["vbrmanager"];
                    }
                    if ($server != $row["vbrserver"]) {//Existing server has changed
                        $showtotal = 1;
                        $server = $row["vbrserver"];
                    }
                    if ($manager != $row["vbrmanager"]) {//Existing manager has changed

                        $showtotalmanager = 1;
                        $manager = $row["vbrmanager"];
                    }
                    // SHOW SERVER TOTAL
                    if ($showtotal == 1) {
                        ?>
                        <tr>
                            <td colspan="6" bgcolor="#F2F2F2" class="style3 style4">
                                <div align="right">Total voids for <?php echo $prev_server; ?>
                                    by <?php echo $prev_manager; ?> (<?php echo $voidcounter; ?>)
                                </div>
                                <?php
                                // EXCEL ------------------------------------
                                $rownumber++;
                                $title = "Total voids for $prev_server by $prev_manager ($voidcounter)";
                                $headings = array($title, '');
                                $worksheet->write_row('H' . $rownumber, $headings, $RightNumberTotalBold);
                                ?>
                                <div align="center"></div>
                            </td>
                            <td bgcolor="#F2F2F2" class="style5">
                                <div align="right"><?php echo number_format($voidtotal, 2);
                                    // EXCEL ------------------------------------
                                    $title = number_format($voidtotal, 2);
                                    $headings = array($title, '');
                                    $worksheet->write_row('I' . $rownumber, $headings, $num1_format);
                                    ?></div>
                                <div align="right"></div>
                            </td>
                        </tr>
                        <?php
                        $voidcounter = 0.00;
                        $voidtotal = 0.00;
                    }

// SHOW MANAGER TOTAL
                    if ($showtotalmanager == 1) {
                        ?>
                        <tr>
                            <td colspan="6" bgcolor="#007CC4" class="style3 style4 style1">
                                <div align="right" class="style1">Total voids for manager <?php echo $prev_manager; ?>
                                    (<?php echo $voidcountermanager; ?>)
                                </div>

                            </td>
                            <td bgcolor="#007CC4" class="style3 style4 style1">
                                <div align="right">
                                    <?php
                                    echo number_format($voidmanagertotal, 2);
                                    // EXCEL ------------------------------------
                                    $rownumber++;

                                    $title = "Total voids for manager $prev_manager ($voidcountermanager)";
                                    $headings = array($title, '');
                                    $worksheet->write_row('H' . $rownumber, $headings, $RightNumberTotalBold);

                                    $title = number_format($voidmanagertotal, 2);
                                    $headings = array($title, '');
                                    $worksheet->write_row('I' . $rownumber, $headings, $num1_format);

                                    $rownumber++;

                                    ?></div>

                            </td>
                        </tr>
                        <?php
                        $showtotalmanager = 0;
                        $voidcountermanager = 0.00;
                        $voidtotal = 0.00;
                        $voidmanagertotal = 0;
                    }
                    ?>
                    <tr>
                        <td class="style3"><?php echo $row["vbrcheckno"]; ?></td>
                        <td class="style3"><?php echo $row["vbrmenuitem"]; ?></td>
                        <td class="style3"><?php echo $row["vbrreason"]; ?></td>
                        <td class="style3"><?php echo $row["vbrmanager"]; ?></td>
                        <td class="style3">
                            <div align="left"><?php echo $row["vbrtime"]; ?></div>
                        </td>
                        <td class="style3"><?php echo $row["vbrserver"]; ?></td>
                        <td class="style3">
                            <div align="right"><?php echo $row["vbramount"]; ?></div>
                        </td>
                    </tr>
                    <?php
                    // ************************************************
                    // Excel

                    $rownumber++;

                    $title = $row["vbrcheckno"];
                    $headings = array($title, '');
                    $worksheet->write_row('C' . $rownumber, $headings, $NormalLeftAlign);

                    $title = $row["vbrmenuitem"];
                    $headings = array($title, '');
                    $worksheet->write_row('D' . $rownumber, $headings, $NormalLeftAlign);

                    $title = $row["vbrreason"];
                    $headings = array($title, '');
                    $worksheet->write_row('E' . $rownumber, $headings, $NormalLeftAlign);

                    $title = $row["vbrmanager"];
                    $headings = array($title, '');
                    $worksheet->write_row('F' . $rownumber, $headings, $NormalLeftAlign);

                    $title = $row["vbrtime"];
                    $headings = array($title, '');
                    $worksheet->write_row('G' . $rownumber, $headings, $NormalLeftAlign);

                    $title = $row["vbrserver"];
                    $headings = array($title, '');
                    $worksheet->write_row('H' . $rownumber, $headings, $NormalLeftAlign);

                    $title = $row["vbramount"];
                    $headings = array($title, '');
                    $worksheet->write_row('I' . $rownumber, $headings, $num1_format);

                    // ----------------------------------------------

                    $voidtotal = $voidtotal + $row["vbramount"];
                    $voidmanagertotal = $voidmanagertotal + $row["vbramount"];
                    $voidgrandtotal = $voidgrandtotal + $row["vbramount"];

                    $voidcounter = $voidcounter + 1;
                    $voidcountermanager = $voidcountermanager + 1;
                    $voidgrandcounter = $voidgrandcounter + 1;
                    $prev_server = $row["vbrserver"];
                    $prev_manager = $row["vbrmanager"];

                }
                // SHOWS FINAL SERVER FOR PAGE
                ?>
                <tr>
                    <td colspan="6" bgcolor="#F2F2F2" class="style3 style4">
                        <div align="right">Total voids for <?php echo $prev_server; ?> by <?php echo $prev_manager; ?>
                            (<?php echo $voidcounter; ?>)
                        </div>
                    </td>
                    <td bgcolor="#F2F2F2" class="style3 style4">
                        <div align="right">
                            <div align="right">
                                <?php
                                echo number_format($voidtotal, 2);

                                // EXCEL ------------------------------------
                                $rownumber++;
                                $title = "Total voids for $prev_server by $prev_manager ($voidcounter)";
                                $headings = array($title, '');
                                $worksheet->write_row('H' . $rownumber, $headings, $RightNumberTotalBold);

                                // EXCEL ------------------------------------
                                $title = number_format($voidtotal, 2);
                                $headings = array($title, '');
                                $worksheet->write_row('I' . $rownumber, $headings, $num1_format);
                                ?></div>
                    </td>
                </tr>
                <?php
                // SHOWS FINAL MANAGER FOR PAGE
                ?>
                <tr>
                    <td colspan="6" bgcolor="#007CC4" class="style3 style4 style1">
                        <div align="right" class="style1">Total voids for manager <?php echo $prev_manager; ?>
                            (<?php echo $voidcountermanager; ?>)
                        </div>
                        <div align="center"></div>
                    </td>
                    <td bgcolor="#007CC4" class="style3 style4 style1">
                        <div align="right">
                            <?php
                            echo number_format($voidmanagertotal, 2);

                            // EXCEL ------------------------------------
                            $rownumber++;
                            $title = "Total voids for manager $prev_manager ($voidcountermanager)";
                            $headings = array($title, '');
                            $worksheet->write_row('H' . $rownumber, $headings, $RightNumberTotalBold);

                            // EXCEL ------------------------------------
                            $title = number_format($voidmanagertotal, 2);
                            $headings = array($title, '');
                            $worksheet->write_row('I' . $rownumber, $headings, $num1_format);

                            $rownumber++;
                            ?></div>
                        <div align="right"></div>
                    </td>
                </tr>
                <tr>
                    <td colspan="5" class="NormalText bold">
                        <div align="left"><strong>Voids Grand Total </strong></div>
                    </td>
                    <td class="NormalText bold">
                        <div align="right"><strong>(<?php echo $voidgrandcounter; ?>)</strong></div>
                    </td>
                    <td class="NormalText bold">
                        <div align="right"><strong>
                                <?php
                                echo number_format($voidgrandtotal, 2);
                                // EXCEL ------------------------------------
                                $rownumber++;

                                $title = "Voids Grand Total ( $voidgrandcounter )";
                                $headings = array($title, '');
                                $worksheet->write_row('H' . $rownumber, $headings, $RightNumberTotalBold);

                                // EXCEL ------------------------------------
                                $title = number_format($voidgrandtotal, 2);
                                $headings = array($title, '');
                                $worksheet->write_row('I' . $rownumber, $headings, $num1_format);

                                ?></strong></div>
                    </td>
                </tr>
            </table>
            <div class="row">
                <div class="col-sm-1 col-sm-offset-10">
                    <a href="classes/excelexport/excelexport.php?fname=<?php echo $fname; ?>" target="_blank">
                        <i class="fas fa-file-excel fa-3x"></i>
                    </a>
                </div>
                <div class="col-sm-1">
                        <span class="glyphicon glyphicon-print"
                              onMouseUp="NewWindow('pages/report_voids_print.php?<?php echo "radDate=" . $radDate . "&dateday=" . $_SESSION["dateday"] . "&datemonth=" . $_SESSION["datemonth"] . "&dateyear=" . $_SESSION["dateyear"] . "&store=" . str_replace("'", "^", $_SESSION["store"]) . "&a=" . $a . "&datefromday=" . $_SESSION["datefromday"] . "&datefrommonth=" . $_SESSION["datefrommonth"] . "&datefromyear=" . $_SESSION["datefromyear"] . "&datetoday=" . $_SESSION["datetoday"] . "&datetomonth=" . $_SESSION["datetomonth"] . "&grpid=" . $grpid . "&radStores=" . $radStores . "&datetoyear=" . $_SESSION["datetoyear"]; ?>','report_voids','650','500','yes');return false;"></span></a>
                </div>
            </div>
            <?php
            // ************************************************************
            // Excel Export - Close up document
            // Write Footer
            $rownumber = $rownumber + 3; // Add some whitespace
            $worksheet->insert_bitmap('a' . $rownumber, 'classes/excelexport/report_footer.bmp', 16, 8); // Write Footer
            $workbook->close(); // Close the workbook
            // ************************************************************
            ?>
            <?php
        } else { ?>
            <p align="center" class="NormalText"><br/>
                <span class="style2">No results were returned for that query.<br/>
        Please try different parameters. </span></p></td>
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

    if ($radStores == "store") {
        echo "SetStoreFocus();";
    }

    if ($radStores == Null) {
        echo "SetStoreFocus(); document.frmparameters.radStores.checked = true;";
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
