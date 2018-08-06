<?php
// Save date range variables unique to Growth Comparison Report

if ($_SESSION["datefromday2"] == null) { // No report submitted yet. Set default
    $_SESSION["datefromday2"] = $_SESSION["datefromday"];
    $_SESSION["datefrommonth2"] = $_SESSION["datefrommonth"];
    $_SESSION["datefromyear2"] = $_SESSION["datefromyear"];

    $_SESSION["datetoday2"] = $_SESSION["datetoday"];
    $_SESSION["datetomonth2"] = $_SESSION["datetomonth"];
    $_SESSION["datetoyear2"] = $_SESSION["datetoyear"];
}


?>

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
    .fas.fa-long-arrow-alt-up {
        color: #8bc34a;
    }
    .fas.fa-long-arrow-alt-down {
        color: #f44336;
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
    function CheckDateRange() {

        const monthNames = ["January", "February", "March", "April", "May", "June",
            "July", "August", "September", "October", "November", "December"
        ];
        var result = true;
        var date = new Date();
        var year = date.getFullYear();

        // CHECK DATE RANGE
        if (document.frmparameters.radDate[0].checked == true) {
            if (document.frmparameters.dateyear.value == year) {
                if(parseInt(document.frmparameters.datemonth.value) > date.getMonth() + 1){
                    alert('Please make sure you have not selected a future date.');
                    return false
                }
            }
        }
        return result;

    }
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
            drops: 'up',
            "maxDate": yesterday,
            autoUpdateInput: false,
            locale: {
                cancelLabel: 'Clear'
            }
        }, function (start, end, label) {
            console.log("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
        });

        $('input[name="daterange"]').on('apply.daterangepicker', function (ev, picker) {
            $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
        });

        $('input[name="daterange"]').on('cancel.daterangepicker', function (ev, picker) {
            $(this).val('');
        });

        $('input[name="daterange2"]').daterangepicker({
            opens: 'right',
            drops: 'up',
            "maxDate": yesterday,
            autoUpdateInput: false,
            locale: {
                cancelLabel: 'Clear'
            }
        }, function (start, end, label) {
            console.log("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
        });

        $('input[name="daterange2"]').on('apply.daterangepicker', function (ev, picker) {
            $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
        });

        $('input[name="daterange2"]').on('cancel.daterangepicker', function (ev, picker) {
            $(this).val('');
        });
    });
</script>

<div class='col-sm-12'>
    <div class="row form-row">
        <div class='col-sm-8 col-sm-offset-2 formdiv'>
            <div class="row">
                <div class="col-sm-12">
                    <h3 style="text-transform: uppercase;" class="text-left">
                        Growth Comparison Report
                    </h3>
                </div>
            </div>
            <form id="frmparameters" name="frmparameters" method="post"
                  action="index.php?p=report_growthcomparison&a=s" onSubmit="return CheckDateRange();">
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
                    <div class="col-sm-4">
                        <div class="radio">
                            <label>
                                <input name="radDate" type="radio" value="date"
                                       onclick="Javascript: SetSpecificDateFocus();" <?php if ($radDate == "date") {
                                    echo "checked='checked'";
                                } ?> />
                                <b>Month Comparison</b>
                            </label>
                        </div>
                    </div>
                    <div class="col-sm-5">
                        <select name="datemonth" id="datemonth" class="form-control">
                            <option value="01" <?php if ($_SESSION["datemonth"] == "01") {
                                echo "selected";
                            } ?>>January
                            </option>
                            <option value="02" <?php if ($_SESSION["datemonth"] == "02") {
                                echo "selected";
                            } ?>>February
                            </option>
                            <option value="03" <?php if ($_SESSION["datemonth"] == "03") {
                                echo "selected";
                            } ?>>March
                            </option>
                            <option value="04" <?php if ($_SESSION["datemonth"] == "04") {
                                echo "selected";
                            } ?>>April
                            </option>
                            <option value="05" <?php if ($_SESSION["datemonth"] == "05") {
                                echo "selected";
                            } ?>>May
                            </option>
                            <option value="06" <?php if ($_SESSION["datemonth"] == "06") {
                                echo "selected";
                            } ?>>June
                            </option>
                            <option value="07" <?php if ($_SESSION["datemonth"] == "07") {
                                echo "selected";
                            } ?>>July
                            </option>
                            <option value="08" <?php if ($_SESSION["datemonth"] == "08") {
                                echo "selected";
                            } ?>>August
                            </option>
                            <option value="09" <?php if ($_SESSION["datemonth"] == "09") {
                                echo "selected";
                            } ?>>September
                            </option>
                            <option value="10" <?php if ($_SESSION["datemonth"] == "10") {
                                echo "selected";
                            } ?>>October
                            </option>
                            <option value="11" <?php if ($_SESSION["datemonth"] == "11") {
                                echo "selected";
                            } ?>>November
                            </option>
                            <option value="12" <?php if ($_SESSION["datemonth"] == "12") {
                                echo "selected";
                            } ?>>December
                            </option>
                        </select>
                    </div>
                    <div class="col-sm-3">
                        <select name="dateyear" id="dateyear" class="form-control">
                            <?php
                                $firstYear = (int)date('Y') - 15;
                                $yearNow = (int)date('Y');
                            for($i=$yearNow;$i>=$firstYear;$i--)
                            {
                                $selected = "";
                                $strYear = (string)$i;
                                if($_SESSION["dateyear"] == $strYear) {
                                    $selected = 'selected';
                                }
                                echo '<option value='.$i.' '.$selected.'>'.$i.'</option>';
                            }
                            ?>
                        </select>
                        <input name="dateday" type="hidden" id="dateday" value="01"/>
                    </div>
                </div>
                <div class="row" style="margin-top: 5px;">
                    <div class="col-sm-12">
                        <div class="radio">
                            <label>
                                <input name="radDate" type="radio" value="daterange"
                                       onclick="Javascript: SetSpecificDateRangeFocus();" <?php if ($radDate == "daterange") {
                                    echo "checked='checked'";
                                } ?> />
                                <b>Date Range Comparison</b>
                            </label>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <span>1st Date Range</span>
                    </div>
                    <div class="col-sm-8">
                        <input class="form-control" type="text" name="daterange" id="daterange"
                               value="<?php echo $_SESSION['daterange'] ?>" placeholder="Date Range 1"/>
                    </div>
                    <div class="col-sm-4" style="margin-top: 5px;">
                        <span>2nd Date Range</span>
                    </div>
                    <div class="col-sm-8" style="margin-top: 5px;">
                        <input class="form-control" type="text" name="daterange2" id="daterange2"
                               value="<?php echo $_SESSION['daterange2'] ?>" placeholder="Date Range 2"/>
                    </div>
                </div>
                <div class="row" style="margin-top: 5px;">
                    <div class="col-sm-4" style="padding-right: 0px;">
                        <label for="cmbdayofweek">Figure to Compare</label>
                    </div>
                    <div class="col-sm-8">
                        <select name="totaltype" class="form-control" id="totaltype">
                            <option <?php if ($_SESSION["totaltype"] == "Gross") {
                                echo "selected='selected'";
                            } ?> value="Gross">Gross
                            </option>
                            <option <?php if ($_SESSION["totaltype"] == "Nett") {
                                echo "selected='selected'";
                            } ?> value="Nett">Nett
                            </option>
                            <option <?php if ($_SESSION["totaltype"] == "Banking Sales") {
                                echo "selected='selected'";
                            } ?> value="Banking Sales">Banking Sales
                            </option>
                        </select>
                    </div>
                </div>
                <input name="hiddenyesterday" type="hidden" id="hiddenyesterday" value="<?php echo $yesterdayday; ?>"/>
                <input name="hiddenyestermonth" type="hidden" id="hiddenyestermonth"
                       value="<?php echo $yesterdaymonth; ?>"/>
                <input name="hiddenyesteryear" type="hidden" id="hiddenyesteryear"
                       value="<?php echo $yesterdayyear; ?>"/>
                <input type="hidden" name="submitted" id="submitted" value="<?php if ($a == "s") {
                    echo "1";
                } ?>"/>
                <input name="Submit" type="submit" class="btn btn-default" value="Submit"
                       style="color: white; background-color: #007CC4"/>
            </form>
        </div>
    </div>
</div>

<div class="col-sm-10 col-sm-offset-1">
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
        $dataavailable = true;
        if ($dataavailable == true) {

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
            $worksheet->set_landscape();
            // Set Columns widths
            $worksheet->set_column('A:A', 0.1);
            $worksheet->set_column('B:B', 13); // Store name
            $worksheet->set_column('C:D', 9); // Two Totals
            $worksheet->set_column('E:E', 4); // Total Growth %
            $worksheet->set_column('F:G', 8); // # Trans
            $worksheet->set_column('H:H', 4); // # Trans Growth %
            $worksheet->set_column('I:J', 8); // Avg Trans
            $worksheet->set_column('K:K', 4); // Avg Trans Growth %
            $worksheet->set_column('L:M', 8); // # Checks
            $worksheet->set_column('N:N', 4); // # Checks Growth %
            $worksheet->set_column('O:P', 8); // Avg Checks
            $worksheet->set_column('Q:Q', 5); // Avg Checks Growth %

            $worksheet->insert_bitmap('a1', 'classes/excelexport/report_header_landscape.bmp', 16, 8); // Write Header
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
                size => 8,
                merge => 0,
                font => 'Arial',
                align => "left",
                text_wrap => 1
            )); // Create new font style

            $heading2center =& $workbook->addformat(array(
                bold => 1,
                color => '007CC4',
                size => 8,
                merge => 0,
                font => 'Arial',
                align => "centre",
                text_wrap => 1
            )); // Create new font style
            // *******************************************************************


            if ($radDate == "date") { // Current month selected
                // Is the first day of the month a Monday?
                // Latest range with last Monday is...
                $counter = 1;
                while ($found != true) {
                    $frommonday = mktime(0, 0, 0, $_SESSION["datemonth"], $counter, $_SESSION["dateyear"]);
                    if (date("l", $frommonday) == "Monday") {
                        $found = true;
                        // Set start date for TO range
                        $datefromday = date("d", $frommonday);
                        $datefrommonth = date("m", $frommonday);
                        $datefromyear = date("Y", $frommonday);

                        // Set 28 days TO range
                        $frommonday28days = mktime(0, 0, 0, $_SESSION["datemonth"], $counter + 27, $_SESSION["dateyear"]);
                        $datetoday = date("d", $frommonday28days);
                        $datetomonth = date("m", $frommonday28days);
                        $datetoyear = date("Y", $frommonday28days);
                    }
                    $counter--;
                }

                $datefrom = $datefromyear . "/" . $datefrommonth . "/" . $datefromday;
                $dateto = $datetoyear . "/" . $datetomonth . "/" . $datetoday;

                // Calculate a year back date range
                // Previous range with last Monday is...
                $counter = 1;
                $found = false;
                while ($found != true) {
                    $frommonday = mktime(0, 0, 0, $_SESSION["datemonth"], $counter, $_SESSION["dateyear"] - 1);
                    if (date("l", $frommonday) == "Monday") {
                        $found = true;
                        // Set start date for TO range
                        $datepreviousfromday = date("d", $frommonday);
                        $datepreviousfrommonth = date("m", $frommonday);
                        $datepreviousfromyear = date("Y", $frommonday);

                        // Set 28 days TO range
                        $frommonday28days = mktime(0, 0, 0, $_SESSION["datemonth"], $counter + 27, $_SESSION["dateyear"] - 1);
                        $dateprevioustoday = date("d", $frommonday28days);
                        $dateprevioustomonth = date("m", $frommonday28days);
                        $dateprevioustoyear = date("Y", $frommonday28days);
                    }
                    $counter--;
                }

                $datepreviousfrom = $datepreviousfromyear . "/" . $datepreviousfrommonth . "/" . $datepreviousfromday;
                $datepreviousto = $dateprevioustoyear . "/" . $dateprevioustomonth . "/" . $dateprevioustoday;

            } // End Date check

            ?>
            <div class="col-sm-12">
                <h3 style="text:#007CC4" class="text-center">
                    <?php
                    if ($radStores == "store") {
                        echo GetStoreName($_SESSION["store"]);
                        $excelreport_title = GetStoreName($_SESSION["store"]); // EXCEL
                    }
                    if ($radStores == "storegroup") {
                        echo GetStoreGroupName($grpid) . $_SESSION["storegroupscount"] ;
                        $excelreport_title = GetStoreName($_SESSION["store"]) . $_SESSION["storegroupscount"]; // EXCEL
                    }
                    ?>
                </h3>
                <h5 class="text-center">between dates</h5>
                <span id="storecounter" class="NormalText" hidden></span>
                <?php
                if ($radDate == "date") {
                    echo '<h5 style="color:#757575" class="text-center">' . $datepreviousfrommonth . "/" . $datepreviousfromday . "/" . $datepreviousfromyear . " - " . $dateprevioustomonth . "/" . $dateprevioustoday . "/" . $dateprevioustoyear . "<br>and<br>" . $datefrommonth . "/" . $datefromday . "/" . $datefromyear . " - " . $datetomonth . "/" . $datetoday . "/" . $datetoyear . '</h5>';
                    $excelreport_date = "between dates " . $datepreviousfromday . "/" . $datepreviousfrommonth . "/" . $datepreviousfromyear . " - " . $dateprevioustoday . "/" . $dateprevioustomonth . "/" . $dateprevioustoyear; // EXCEL
                    $excelreport_date2 = "and " . $datefromday . "/" . $datefrommonth . "/" . $datefromyear . " - " . $datetoday . "/" . $datetomonth . "/" . $datetoyear; // EXCEL
                }
                if ($radDate == "daterange") {
                    echo '<h5 style="color:#757575" class="text-center">' . $_SESSION["datefrommonth"] . "/" . $_SESSION["datefromday"] . "/" . $_SESSION["datefromyear"] . " - " . $_SESSION["datetomonth"] . "/" . $_SESSION["datetoday"] . "/" . $_SESSION["datetoyear"] . "<br>and<br>" . $_SESSION["datefrommonth2"] . "/" . $_SESSION["datefromday2"] . "/" . $_SESSION["datefromyear2"] . " - " . $_SESSION["datetomonth2"] . "/" . $_SESSION["datetoday2"] . "/" . $_SESSION["datetoyear2"];
                    $excelreport_date = "between dates " . $_SESSION["datefromday"] . "/" . $_SESSION["datefrommonth"] . "/" . $_SESSION["datefromyear"] . " - " . $_SESSION["datetoday"] . "/" . $_SESSION["datetomonth"] . "/" . $_SESSION["datetoyear"]; // EXCEL
                    $excelreport_date2 = "and " . $_SESSION["datefromday2"] . "/" . $_SESSION["datefrommonth2"] . "/" . $_SESSION["datefromyear2"] . " - " . $_SESSION["datetoday2"] . "/" . $_SESSION["datetomonth2"] . "/" . $_SESSION["datetoyear2"]; // EXCEL
                }
                // ************************************
                // EXCEL - Set up report title
                // *

                $num1_format =& $workbook->addformat(array(
                    num_format => '0.00',
                    bold => 0,
                    color => '007CC4',
                    size => 8,
                    merge => 0,
                    font => 'Arial',
                    align => "right",
                    text_wrap => 0
                ));  //Basic number format
                $num1_formatnodecimals =& $workbook->addformat(array(
                    num_format => '0',
                    bold => 0,
                    color => '007CC4',
                    size => 8,
                    merge => 0,
                    font => 'Arial',
                    align => "right",
                    text_wrap => 0
                ));  //Basic number format
                $num1growthpositive_format =& $workbook->addformat(array(
                    num_format => '0.0',
                    bold => 0,
                    color => 'white',
                    size => 8,
                    merge => 0,
                    font => 'Arial',
                    align => 'right',
                    bg_color => 'green',
                    underline => 0,
                    text_wrap => 0
                ));  //Basic number format

                $num1growthnegative_format =& $workbook->addformat(array(
                    num_format => '0.0',
                    bold => 0,
                    color => 'white',
                    size => 8,
                    merge => 0,
                    font => 'Arial',
                    align => 'right',
                    bg_color => 'red',
                    underline => 1,
                    text_wrap => 0
                ));  //Basic number format
                $num1growthnone_format =& $workbook->addformat(array(
                    num_format => '0.0',
                    bold => 0,
                    color => 'white',
                    size => 8,
                    merge => 0,
                    font => 'Arial',
                    align => 'right',
                    bg_color => 'blue',
                    underline => 0,
                    text_wrap => 0
                ));  //Basic number format

                // Report Title
                $title = "Growth Comparison Report for ";
                $headings = array($title, '');
                $worksheet->write_row('H6', $headings, $heading);
                // Report Specs
                $excelreport_title = $excelreport_title;
                $headings = array($excelreport_title, '');
                $worksheet->write_row('H7', $headings, $heading);
                // Report Date
                //	$rownumber++;
                $excelreport_title = $excelreport_date;
                $headings = array($excelreport_title, '');
                $worksheet->write_row('H8', $headings, $heading);

                $excelreport_title = $excelreport_date2;
                $headings = array($excelreport_title, '');
                $worksheet->write_row('H9', $headings, $heading);


                $rownumber = 20;

                $text = array("Summary", '');
                $worksheet->write_row('H11', $text, $heading);

                // Summary header

                $text = array("Total", '');
                $worksheet->write_row('B14', $text, $heading2);


                $text = array($totaltype . " 1", '');
                $worksheet->write_row('C13', $text, $heading2center);

                $text = array($totaltype . " 2", '');
                $worksheet->write_row('D13', $text, $heading2center);

                $text = array("%", '');
                $worksheet->write_row('E13', $text, $heading2center);

                $text = array("#Trans 1", '');
                $worksheet->write_row('F13', $text, $heading2center);

                $text = array("#Trans 2", '');
                $worksheet->write_row('G13', $text, $heading2center);

                $text = array("%", '');
                $worksheet->write_row('H13', $text, $heading2center);

                $text = array("Avg Trans 1", '');
                $worksheet->write_row('I13', $text, $heading2center);

                $text = array("Avg Trans 2", '');
                $worksheet->write_row('J13', $text, $heading2center);

                $text = array("%", '');
                $worksheet->write_row('K13', $text, $heading2center);

                $text = array("#Checks 1", '');
                $worksheet->write_row('L13', $text, $heading2center);

                $text = array("#Checks 2", '');
                $worksheet->write_row('M13', $text, $heading2center);

                $text = array("%", '');
                $worksheet->write_row('N13', $text, $heading2center);

                $text = array("Avg Check 1", '');
                $worksheet->write_row('O13', $text, $heading2center);

                $text = array("Avg Check 2", '');
                $worksheet->write_row('P13', $text, $heading2center);

                $text = array("%", '');
                $worksheet->write_row('Q13', $text, $heading2center);

                // Details header

                $text = array("Details", '');
                $worksheet->write_row('H16', $text, $heading);

                $text = array("Stores", '');
                $worksheet->write_row('B18', $text, $heading2);

                $text = array($totaltype . " 1", '');
                $worksheet->write_row('C18', $text, $heading2center);

                $text = array($totaltype . " 2", '');
                $worksheet->write_row('D18', $text, $heading2center);

                $text = array("%", '');
                $worksheet->write_row('E18', $text, $heading2center);

                $text = array("#Trans 1", '');
                $worksheet->write_row('F18', $text, $heading2center);

                $text = array("#Trans 2", '');
                $worksheet->write_row('G18', $text, $heading2center);

                $text = array("%", '');
                $worksheet->write_row('H18', $text, $heading2center);

                $text = array("Avg Trans 1", '');
                $worksheet->write_row('I18', $text, $heading2center);

                $text = array("Avg Trans 2", '');
                $worksheet->write_row('J18', $text, $heading2center);

                $text = array("%", '');
                $worksheet->write_row('K18', $text, $heading2center);

                $text = array("#Checks 1", '');
                $worksheet->write_row('L18', $text, $heading2center);

                $text = array("#Checks 2", '');
                $worksheet->write_row('M18', $text, $heading2center);

                $text = array("%", '');
                $worksheet->write_row('N18', $text, $heading2center);

                $text = array("Avg Check 1", '');
                $worksheet->write_row('O18', $text, $heading2center);

                $text = array("Avg Check 2", '');
                $worksheet->write_row('P18', $text, $heading2center);

                $text = array("%", '');
                $worksheet->write_row('Q18', $text, $heading2center);

                $rownumber = 19;
                // ************************************
                ?>
            </div>
            <div class="col-sm-12">
                <hr>
                <h4 style="color:#757575" class="text-center"> Summary</h4>
                <?php

                // Select which total to get
                if ($totaltype == "Gross") {
                    $columnname = "sumgrosssales";
                }
                if ($totaltype == "Nett") {
                    $columnname = "sumnettsales";
                }
                if ($totaltype == "Banking Sales") {
                    $columnname = "sumbankingsales";
                }
                ?>
                <table class="table table-striped table-condensed table-bordered">
                    <tr>
                        <td class="SmallText text-center">
                            <strong>Store</strong>
                        </td>
                        <td class="SmallText text-center">
                            <strong><?php echo $totaltype; ?> 1</strong>
                        </td>
                        <td class="SmallText text-center">
                            <strong><?php echo $totaltype; ?> 2</strong>
                        </td>
                        <td class="SmallText text-center">
                            <strong># Trans 1</strong>
                        </td>
                        <td class="SmallText text-center">
                            <strong># Trans 2</strong>
                        </td>
                        <td class="SmallText text-center">
                            <strong>Avg Trans 1</strong>
                        </td>
                        <td class="SmallText text-center">
                            <strong>Avg Trans 2</strong>
                        </td>
                        <td class="SmallText text-center">
                            <strong># Checks 1</strong>
                        </td>
                        <td class="SmallText text-center">
                            <strong># Checks 2</strong>
                        </td>
                        <td class="SmallText text-center">
                            <strong>Avg Checks 1</strong>
                        </td>
                        <td class="SmallText text-center">
                            <strong>Avg Checks 2 </strong>
                        </td>
                    </tr>
                    <tr class="percentageCols">
                        <td class="SmallText text-center">%<i class="fas fa-long-arrow-alt-up"></i><i class="fas fa-long-arrow-alt-down"></i></td>
                        <td colspan="2" id="totalgrowthcell" class="SmallText text-center white">
                            <div id="totalgrowth"></div>
                        </td>
                        <td colspan="2" id="totaltransgrowthcell" class="SmallText text-center">
                            <div id="totaltransgrowth"></div>
                        </td>
                        <td colspan="2" id="totalavggrowthcell" class="SmallText text-center">
                            <div id="totalavggrowth"></div>
                        </td>
                        <td colspan="2" id="totalchecksgrowthcell" class="SmallText text-center">
                            <div id="totalchecksgrowth" ></div>
                        </td>
                        <td colspan="2" id="totalchecksavggrowthcell" class="SmallText text-center">
                            <div id="totalchecksavggrowth"></div>
                        </td>
                    </tr>
                    <tr>
                        <td class="SmallText text-center bold"><strong>Total</strong></td>
                        <td class="SmallText text-center bold">
                            <div id="totalfigureprevious">
                                <?php // $figureprevious = GetFigureTotal($columnname,$sumid); echo $figureprevious; $previousmainfigure = $figureprevious;
                                ?>
                            </div>
                        </td>
                        <td class="SmallText text-center bold">
                            <div id="totalfigure">
                                <?php // $figure = GetFigureTotal($columnname,$sumid2); echo $figure; $growth = number_format((($figure/$figureprevious) * 100) - 100, 1, '.', ''); if($growth == "-100") {$growth = "0";}  $mainfigure = $figure;
                                ?>
                            </div>
                        </td>
                        <td class="SmallText text-center bold">
                            <div id="totaltransprevious">
                                <?php //$figureprevious = GetItemsBreakdownTotal($sumid); echo $figureprevious;
                                ?>
                            </div>
                        </td>
                        <td class="SmallText text-center bold">
                            <div id="totaltrans">
                                <?php //$figure = GetItemsBreakdownTotal($sumid2); echo $figure; $growth = number_format((($figure/$figureprevious) * 100) - 100, 1, '.', ''); if($growth == "-100") {$growth = "0";}
                                ?>
                            </div>
                        </td>
                        <td class="SmallText text-center bold">
                            <div id="totalavgtransprevious">
                                <?php // $figureprevious = number_format($previousmainfigure / $figureprevious, 2, '.', ''); echo $figureprevious;
                                ?>
                            </div>
                        </td>
                        <td class="SmallText text-center bold">
                            <div id="totalavgtrans">
                                <?php //$figure = number_format($mainfigure / $figure, 2, '.', ''); echo $figure; $growth = number_format((($figure/$figureprevious) * 100) - 100, 1, '.', ''); if($growth == "-100") {$growth = "0";}
                                ?>
                            </div>
                        </td>
                        <td class="SmallText text-center bold">
                            <div id="totalchecksprevious">
                                <?php // $figureprevious = GetChecksTotal($sumid); echo $figureprevious;
                                ?>
                            </div>
                        </td>
                        <td class="SmallText text-center bold">
                            <div id="totalchecks">
                                <?php //$figure = GetChecksTotal($sumid2); echo $figure; $growth = number_format((($figure/$figureprevious) * 100) - 100, 1, '.', ''); if($growth == "-100") {$growth = "0";}
                                ?>
                            </div>
                        </td>
                        <td class="SmallText text-center bold">
                            <div id="totalavgchecksprevious">
                                <?php // $figureprevious = number_format($previousmainfigure / $figureprevious, 2, '.', ''); echo $figureprevious;
                                ?>
                            </div>
                        </td>
                        <td class="SmallText text-center bold">
                            <div id="totalavgchecks">
                                <?php //$figure = number_format($mainfigure / $figure, 2, '.', ''); echo $figure; $growth = number_format((($figure/$figureprevious) * 100) - 100, 1, '.', ''); if($growth == "-100") {$growth = "0";}
                                ?>
                            </div>
                        </td>
                    </tr>
                    <?php
                    // Go through each store in list
                    if ($radStores == "store") {
                        $result = GetStoreDetails($_SESSION["store"]); // Get all the Store IDs inside that group
                    }
                    if ($radStores == "storegroup") {
                        $result = GetStoreIDsForGroup($grpid); // Get all the Store IDs inside that group
                    }
                    $storecounter = 0;
                    $totalfigureprevious = 0;
                    while ($row = mysql_fetch_array($result)) { // Go through each store in Group
                        $sumidprevious = null;
                        $sumidresult = null;
                        // Get SUMID list for a specific store for FROM DATE
                        if ($radDate == "date") {
                            $sumidresult = GetSumIDbetweenDates($datepreviousfrom, $datepreviousto, $row["strid"]);
                        }
                        if ($radDate == "daterange") {
                            $sumidresult = GetSumIDbetweenDates($_SESSION["datefromyear"] . "/" . $_SESSION["datefrommonth"] . "/" . $_SESSION["datefromday"], $_SESSION["datetoyear"] . "/" . $_SESSION["datetomonth"] . "/" . $_SESSION["datetoday"], $row["strid"]);
                        }

                        if (mysql_num_rows($sumidresult) > 0) {
                            $sumidrow = mysql_fetch_array($sumidresult);
                            $sumidprevious = "'" . $sumidrow["sumid"] . "'";
                        }
                        while ($sumidrow = mysql_fetch_array($sumidresult)) {
                            $sumidprevious = $sumidprevious . ",'" . $sumidrow["sumid"] . "'";
                        }
                        $sumidresult = null;
                        $sumid = null;
                        // Get SUMID list for a specific store for TO DATE
                        if ($radDate == "date") {
                            $sumidresult = GetSumIDbetweenDates($datefrom, $dateto, $row["strid"]);
                        }
                        if ($radDate == "daterange") {
                            $sumidresult = GetSumIDbetweenDates($_SESSION["datefromyear2"] . "/" . $_SESSION["datefrommonth2"] . "/" . $_SESSION["datefromday2"], $_SESSION["datetoyear2"] . "/" . $_SESSION["datetomonth2"] . "/" . $_SESSION["datetoday2"], $row["strid"]);
                        }

                        if (mysql_num_rows($sumidresult) > 0) {
                            $sumidrow = mysql_fetch_array($sumidresult);
                            $sumid = "'" . $sumidrow["sumid"] . "'";
                        }
                        while ($sumidrow = mysql_fetch_array($sumidresult)) {
                            $sumid = $sumid . ",'" . $sumidrow["sumid"] . "'";
                        }

                        $figureprevious = GetFigureTotal($columnname, $sumidprevious);
                        $previousmainfigure = $figureprevious;
                        $figuretotal1 = $figuretotal1 + $figureprevious;

                        $figure = GetFigureTotal($columnname, $sumid);
                        $growth = number_format((($figure / $figureprevious) * 100) - 100, 1, '.', '');
                        if ($growth == "-100") {
                            $growth = "0";
                        }
                        $mainfigure = $figure;
                        if ($figureprevious != "0.00" && $figure != "0.00") {
                            $storecounter++;
                            ?>
                            <?php
                        }
                    }
                    ?>
                </table>
            </div>
            <div class="col-sm-12">
                <hr>
                <h4 style="color:#757575" class="text-center"> Detail</h4>
                <table class="table table-striped table-condensed table-bordered">
                    <tr>
                        <td class="SmallText text-center">
                            <strong>Store</strong>
                        </td>
                        <td class="SmallText text-center">
                            <strong><?php echo $totaltype; ?> 1</strong>
                        </td>
                        <td class="SmallText text-center">
                            <strong><?php echo $totaltype; ?> 2</strong>
                        </td>
                        <td class="SmallText text-center">
                            <strong>%</strong>
                        </td>
                        <td class="SmallText text-center">
                            <strong># Trans 1</strong>
                        </td>
                        <td class="SmallText text-center">
                            <strong># Trans 2</strong>
                        </td>
                        <td class="SmallText text-center">
                            <strong>%</strong>
                        </td>
                        <td class="SmallText text-center">
                            <strong>Avg Trans 1</strong>
                        </td>
                        <td class="SmallText text-center">
                            <strong>Avg Trans 2</strong>
                        </td>
                        <td class="SmallText text-center">
                            <strong>%</strong>
                        </td>
                        <td class="SmallText text-center">
                            <strong># Checks 1</strong>
                        </td>
                        <td class="SmallText text-center">
                            <strong># Checks 2</strong>
                        </td>
                        <td class="SmallText text-center">
                           <strong>%</strong>
                        </td>
                        <td class="SmallText text-center">
                            <strong>Avg Checks 1</strong>
                        </td>
                        <td class="SmallText text-center">
                            <strong>Avg Checks 2 </strong>
                        </td>
                        <td class="SmallText text-center">
                            <strong>%</strong>
                        </td>
                    </tr>
                    <?php
                    // Go through each store in list


                    if ($radStores == "store") {
                        $result = GetStoreDetails($_SESSION["store"]); // Get all the Store IDs inside that group
                    }
                    if ($radStores == "storegroup") {
                        $result = GetStoreIDsForGroup($grpid); // Get all the Store IDs inside that group
                    }
                    $storecounter = 0;
                    $totalfigureprevious = 0;
                    while ($row = mysql_fetch_array($result)) { // Go through each store in Group

                        $sumidprevious = null;
                        $sumidresult = null;
                        // Get SUMID list for a specific store for FROM DATE
                        if ($radDate == "date") {
                            $sumidresult = GetSumIDbetweenDates($datepreviousfrom, $datepreviousto, $row["strid"]);
                        }
                        if ($radDate == "daterange") {
                            $sumidresult = GetSumIDbetweenDates($_SESSION["datefromyear"] . "/" . $_SESSION["datefrommonth"] . "/" . $_SESSION["datefromday"], $_SESSION["datetoyear"] . "/" . $_SESSION["datetomonth"] . "/" . $_SESSION["datetoday"], $row["strid"]);
                        }

                        if (mysql_num_rows($sumidresult) > 0) {
                            $sumidrow = mysql_fetch_array($sumidresult);
                            $sumidprevious = "'" . $sumidrow["sumid"] . "'";
                        }
                        while ($sumidrow = mysql_fetch_array($sumidresult)) {
                            $sumidprevious = $sumidprevious . ",'" . $sumidrow["sumid"] . "'";
                        }
                        $sumidresult = null;
                        $sumid = null;
                        // Get SUMID list for a specific store for TO DATE
                        if ($radDate == "date") {
                            $sumidresult = GetSumIDbetweenDates($datefrom, $dateto, $row["strid"]);
                        }
                        if ($radDate == "daterange") {
                            $sumidresult = GetSumIDbetweenDates($_SESSION["datefromyear2"] . "/" . $_SESSION["datefrommonth2"] . "/" . $_SESSION["datefromday2"], $_SESSION["datetoyear2"] . "/" . $_SESSION["datetomonth2"] . "/" . $_SESSION["datetoday2"], $row["strid"]);
                        }

                        if (mysql_num_rows($sumidresult) > 0) {
                            $sumidrow = mysql_fetch_array($sumidresult);
                            $sumid = "'" . $sumidrow["sumid"] . "'";
                        }
                        while ($sumidrow = mysql_fetch_array($sumidresult)) {
                            $sumid = $sumid . ",'" . $sumidrow["sumid"] . "'";
                        }


                        $figureprevious = GetFigureTotal($columnname, $sumidprevious);
                        $previousmainfigure = $figureprevious;
                        $figuretotal1 = $figuretotal1 + $figureprevious;

                        $figure = GetFigureTotal($columnname, $sumid);
                        $growth = number_format((($figure / $figureprevious) * 100) - 100, 1, '.', '');
                        if ($growth == "-100") {
                            $growth = "0";
                        }
                        $mainfigure = $figure;
                        if ($figureprevious != "0.00" && $figure != "0.00") {
                            $storecounter++;
                            ?>
                            <tr>
                                <td class="SmallText text-center">
                                    <?php
                                    $storename = GetStoreName($row["strid"]);
                                    echo $storename;
                                    $text = array(substr($storename, 0, 12) . ".", '');
                                    $worksheet->write_row('B' . $rownumber, $text, $heading2);
                                    ?>
                                </td>
                                <td class="SmallText text-center">
                                    <?php
                                    echo $figureprevious;
                                    $totalfigureprevious = $totalfigureprevious + $figureprevious;
                                    ?>
                                </td>
                                <td class="SmallText text-center">
                                    <?php
                                    echo $figure;
                                    $totalfigure = $totalfigure + $figure;

                                    $text = array($figureprevious, ''); //excel
                                    $worksheet->write_row('C' . $rownumber, $text, $num1_format);    //excel

                                    $text = array($figure, '');//excel
                                    $worksheet->write_row('D' . $rownumber, $text, $num1_format);//excel

                                    ?>
                                </td>
                                <td bgcolor="<?php $text = array($growth, '');
                                if ($growth > 0) {
                                    echo "#8bc34a";
                                    $worksheet->write_row('E' . $rownumber, $text, $num1growthpositive_format);
                                }
                                if ($growth < 0) {
                                    echo "#f44336";
                                    $worksheet->write_row('E' . $rownumber, $text, $num1growthnegative_format);
                                }
                                if ($growth == 0) {
                                    echo "#0066CC";
                                    $worksheet->write_row('E' . $rownumber, $text, $num1growthnone_format);
                                } ?>" class="SmallText text-center">
                                      <?php echo str_replace("-", "", $growth) . '%'; ?>
                                </td>
                                <td class="SmallText text-center">
                                    <?php
                                    $figureprevious = 0;
                                    $figureprevious = GetItemsBreakdownTotal($sumidprevious);
                                    echo $figureprevious;
                                    $totaltransprevious = $totaltransprevious + $figureprevious;
                                    ?>
                                </td>
                                <td class="SmallText text-center">
                                    <?php
                                    $figure = GetItemsBreakdownTotal($sumid);
                                    echo $figure;
                                    $growth = number_format((($figure / $figureprevious) * 100) - 100, 1, '.', '');
                                    if ($growth == "-100") {
                                        $growth = "0";
                                    }
                                    $totaltrans = $totaltrans + $figure;
                                    ?>
                                </td>
                                <td bgcolor="<?php
                                $text = array($figureprevious, '');// excel
                                $worksheet->write_row('F' . $rownumber, $text, $num1_formatnodecimals);    // excel

                                $text = array($figure, '');// excel
                                $worksheet->write_row('G' . $rownumber, $text, $num1_formatnodecimals);    // excel
                                $text = array($growth, '');
                                if ($growth > 0) {
                                    echo "#8bc34a";
                                    $worksheet->write_row('H' . $rownumber, $text, $num1growthpositive_format);
                                }
                                if ($growth < 0) {
                                    echo "#f44336";
                                    $worksheet->write_row('H' . $rownumber, $text, $num1growthnegative_format);
                                }
                                if ($growth == 0) {
                                    echo "#0066CC";
                                    $worksheet->write_row('H' . $rownumber, $text, $num1growthnone_format);
                                } ?>" class="SmallText text-center">
                                    <?php echo str_replace("-", "", $growth) . '%'; ?>
                                </td>
                                <td class="SmallText text-center">
                                    <?php
                                    $figureprevious = number_format($previousmainfigure / $figureprevious, 2, '.', '');
                                    echo $figureprevious;
                                    ?>
                                </td>
                                <td class="SmallText text-center">
                                    <?php
                                    $figure = number_format($mainfigure / $figure, 2, '.', '');
                                    echo $figure;
                                    $growth = number_format((($figure / $figureprevious) * 100) - 100, 1, '.', '');
                                    if ($growth == "-100") {
                                        $growth = "0";
                                    }
                                    ?>
                                </td>
                                <td bgcolor="<?php
                                $text = array($figureprevious, ''); //excel
                                $worksheet->write_row('I' . $rownumber, $text, $num1_format);    //excel

                                $text = array($figure, '');//excel
                                $worksheet->write_row('J' . $rownumber, $text, $num1_format);//excel
                                $text = array($growth, '');
                                if ($growth > 0) {
                                    echo "#8bc34a";
                                    $worksheet->write_row('K' . $rownumber, $text, $num1growthpositive_format);
                                }
                                if ($growth < 0) {
                                    echo "#f44336";
                                    $worksheet->write_row('K' . $rownumber, $text, $num1growthnegative_format);
                                }
                                if ($growth == 0) {
                                    echo "#0066CC";
                                    $worksheet->write_row('K' . $rownumber, $text, $num1growthnone_format);
                                } ?>" class="SmallText text-center">
                                    <?php echo str_replace("-", "", $growth) . '%';?>
                                </td>
                                <td class="SmallText text-center">
                                    <?php
                                    $figureprevious = GetChecksTotal($sumidprevious);
                                    echo $figureprevious;
                                    $totalchecksprevious = $totalchecksprevious + $figureprevious;
                                    ?>
                                </td>
                                <td class="SmallText text-center">
                                    <?php
                                    $figure = GetChecksTotal($sumid);
                                    echo $figure;
                                    $growth = number_format((($figure / $figureprevious) * 100) - 100, 1, '.', '');
                                    if ($growth == "-100") {
                                        $growth = "0";
                                    }
                                    $totalchecks = $totalchecks + $figure;
                                    ?>
                                </td>
                                <td bgcolor="<?php
                                $text = array($figureprevious, ''); //excel
                                $worksheet->write_row('L' . $rownumber, $text, $num1_formatnodecimals);//excel

                                $text = array($figure, '');//excel
                                $worksheet->write_row('M' . $rownumber, $text, $num1_formatnodecimals);//excel
                                $text = array($growth, '');
                                if ($growth > 0) {
                                    echo "#8bc34a";
                                    $worksheet->write_row('N' . $rownumber, $text, $num1growthpositive_format);
                                }
                                if ($growth < 0) {
                                    echo "#f44336";
                                    $worksheet->write_row('N' . $rownumber, $text, $num1growthnegative_format);
                                }
                                if ($growth == 0) {
                                    echo "#0066CC";
                                    $worksheet->write_row('N' . $rownumber, $text, $num1growthnone_format);
                                } ?>" class="SmallText text-center">
                                    <?php echo str_replace("-", "", $growth) . '%';?>
                                </td>
                                <td class="SmallText text-center">
                                    <?php
                                    $figureprevious = number_format($previousmainfigure / $figureprevious, 2, '.', '');
                                    echo $figureprevious;
                                    ?>
                                </td>
                                <td class="SmallText text-center">
                                    <?php $figure = number_format($mainfigure / $figure, 2, '.', '');
                                    echo $figure;
                                    $growth = number_format((($figure / $figureprevious) * 100) - 100, 1, '.', '');
                                    if ($growth == "-100") {
                                        $growth = "0";
                                    }
                                    ?>
                                </td>
                                <td bgcolor="<?php
                                $text = array($figureprevious, ''); //excel
                                $worksheet->write_row('O' . $rownumber, $text, $num1_format); //excel

                                $text = array($figure, ''); //excel
                                $worksheet->write_row('P' . $rownumber, $text, $num1_format); //excel
                                $text = array($growth, '');
                                if ($growth > 0) {
                                    echo "#8bc34a";
                                    $worksheet->write_row('Q' . $rownumber, $text, $num1growthpositive_format);
                                }
                                if ($growth < 0) {
                                    echo "#f44336";
                                    $worksheet->write_row('Q' . $rownumber, $text, $num1growthnegative_format);
                                }
                                if ($growth == 0) {
                                    echo "#0066CC";
                                    $worksheet->write_row('Q' . $rownumber, $text, $num1growthnone_format);
                                } ?>" class="SmallText text-center">
                                    <?php
                                    echo str_replace("-", "", $growth) . '%';
                                    $text = array(" ", '');;
                                    $worksheet->write_row('R' . $rownumber, $text, $heading2);
                                    ?>
                                </td>
                            </tr>
                            <?php
                            //
                            $rownumber++;
                        }
                    }
                    ?>
                </table>
                <div class="row">
                    <div class="col-sm-1 col-sm-offset-10">
                        <a href="classes/excelexport/excelexport.php?fname=<?php echo $fname; ?>" target="_blank">
                            <i class="fas fa-file-excel fa-3x"></i>
                        </a>
                    </div>
                    <div class="col-sm-1">
                            <span class="glyphicon glyphicon-print"
                                  onMouseUp="NewWindow('pages/report_growthcomparison_print.php?<?php echo "radDate=" . $radDate . "&dateday=" . $_SESSION["dateday"] . "&datemonth=" . $_SESSION["datemonth"] . "&dateyear=" . $_SESSION["dateyear"] . "&store=" . str_replace("'", "^", $_SESSION["store"]) . "&a=" . $a . "&datefromday=" . $_SESSION["datefromday"] . "&datefrommonth=" . $_SESSION["datefrommonth"] . "&datefromyear=" . $_SESSION["datefromyear"] . "&datetoday=" . $_SESSION["datetoday"] . "&datetomonth=" . $_SESSION["datetomonth"] . "&grpid=" . $grpid . "&radStores=" . $radStores . "&datetoyear=" . $_SESSION["datetoyear"]; ?>','report_growthcomparison','650','500','yes');return false;"></span>
                    </div>
                </div>
            </div>
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
        $("#datemonth").prop('disabled', false);
        $("#dateyear").prop('disabled', false);
        $("#daterange").prop('disabled', true);
        $("#daterange2").prop('disabled', true);
    }

    function SetSpecificDateRangeFocus() {
        $("#datemonth").prop('disabled', true);
        $("#dateyear").prop('disabled', true);
        $("#daterange").prop('disabled', false);
        $("#daterange2").prop('disabled', false);
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
    if ($radStores == "storegroup") {
        echo "SetStoreGroupFocus();";
    }
    if ($radStores == Null) {
        echo "SetStoreFocus(); document.frmparameters.radStores[0].checked = true;";
    }
    ?>

    if (document.frmparameters.submitted.value == 1) {
        document.getElementById("storecounter").innerHTML = "<?php echo $storecounter; ?>";

// Totals
        document.getElementById("totalfigureprevious").innerHTML = "<?php echo $totalfigureprevious; ?>";
        document.getElementById("totalfigure").innerHTML = "<?php echo $totalfigure; ?>";
        <?php
        $growth = number_format((($totalfigure / $totalfigureprevious) * 100) - 100, 1, '.', '');
        $growthtotal = $growth;
        if ($growth == "-100") {
            $growth = "0";
        }
        ?>
        document.getElementById("totalgrowth").innerHTML = "<?php echo str_replace("-", "", $growth) . '% '; ?>";
        <?php if($growth > 0) { ?>
        $displaystring =
        document.getElementById("totalgrowthcell").style.backgroundColor = "#8bc34a";
        <?php } if($growth < 0) {  ?>
        document.getElementById("totalgrowthcell").style.backgroundColor = "#f44336";
        <?php } if($growth == 0) {  ?>
        document.getElementById("totalgrowthcell").style.backgroundColor = "#0066CC";
        <?php } ?>

        // Trans
        document.getElementById("totaltransprevious").innerHTML = "<?php echo $totaltransprevious; ?>";
        document.getElementById("totaltrans").innerHTML = "<?php echo $totaltrans; ?>";
        <?php
        $growth = 0;
        $growth = number_format((($totaltrans / $totaltransprevious) * 100) - 100, 1, '.', '');
        $growthtrans = $growth;
        if ($growth == "-100") {
            $growth = "0";
        }
        ?>
        document.getElementById("totaltransgrowth").innerHTML = "<?php echo str_replace("-", "", $growth) . '% '; ?>";
        <?php if($growth > 0) { ?>
        document.getElementById("totaltransgrowthcell").style.backgroundColor = "#8bc34a";
        <?php } if($growth < 0) {  ?>
        document.getElementById("totaltransgrowthcell").style.backgroundColor = "#f44336";
        <?php } if($growth == 0) {  ?>
        document.getElementById("totaltransgrowthcell").style.backgroundColor = "#0066CC";
        <?php } ?>


// Avg Trans
        document.getElementById("totalavgtransprevious").innerHTML = "<?php echo number_format($totalfigureprevious / $totaltransprevious, 2, '.', '') ?>";
        document.getElementById("totalavgtrans").innerHTML = "<?php echo number_format($totalfigure / $totaltrans, 2, '.', '') ?>";
        <?php
        $growth = 0;
        $growth = number_format(((number_format($totalfigure / $totaltrans, 2, '.', '') / number_format($totalfigureprevious /
                        $totaltransprevious, 2, '.', '')) * 100) - 100, 1, '.', '');
        $growthavgtrans = $growth;
        if ($growth == "-100") {
            $growth = "0";
        }
        ?>
        document.getElementById("totalavggrowth").innerHTML = "<?php echo str_replace("-", "", $growth) . '% ';?>";
        <?php if($growth > 0) { ?>
        document.getElementById("totalavggrowthcell").style.backgroundColor = "#8bc34a";
        <?php } if($growth < 0) {  ?>
        document.getElementById("totalavggrowthcell").style.backgroundColor = "#f44336";
        <?php } if($growth == 0) {  ?>
        document.getElementById("totalavggrowthcell").style.backgroundColor = "#0066CC";
        <?php } ?>


// Checks
        document.getElementById("totalchecksprevious").innerHTML = "<?php echo $totalchecksprevious; ?>";
        document.getElementById("totalchecks").innerHTML = "<?php echo $totalchecks; ?>";
        <?php
        $growth = 0;
        $growth = number_format((($totalchecks / $totalchecksprevious) * 100) - 100, 1, '.', '');
        $growthchecks = $growth;
        if ($growth == "-100") {
            $growth = "0";
        }
        ?>
        document.getElementById("totalchecksgrowth").innerHTML = "<?php echo str_replace("-", "", $growth) . '% ';?>";
        <?php if($growth > 0) { ?>
        document.getElementById("totalchecksgrowthcell").style.backgroundColor = "#8bc34a";
        <?php } if($growth < 0) {  ?>
        document.getElementById("totalchecksgrowthcell").style.backgroundColor = "#f44336";
        <?php } if($growth == 0) {  ?>
        document.getElementById("totalchecksgrowthcell").style.backgroundColor = "#0066CC";
        <?php } ?>


// Avg Checks
        document.getElementById("totalavgchecksprevious").innerHTML = "<?php echo number_format($totalfigureprevious / $totalchecksprevious, 2, '.', '') ?>";
        document.getElementById("totalavgchecks").innerHTML = "<?php echo number_format($totalfigure / $totalchecks, 2, '.', '') ?>";
        <?php
        $growth = 0;
        $growth = number_format(((number_format($totalfigure / $totalchecks, 2, '.', '') / number_format($totalfigureprevious /
                        $totalchecksprevious, 2, '.', '')) * 100) - 100, 1, '.', '');
        $growthavgchecks = $growth;
        if ($growth == "-100") {
            $growth = "0";
        }
        ?>
        document.getElementById("totalchecksavggrowth").innerHTML = "<?php echo str_replace("-", "", $growth) . '% ';?>";
        <?php if($growth > 0) { ?>
        document.getElementById("totalchecksavggrowthcell").style.backgroundColor = "#8bc34a";
        <?php } if($growth < 0) {  ?>
        document.getElementById("totalchecksavggrowthcell").style.backgroundColor = "#f44336";
        <?php } if($growth == 0) {  ?>
        document.getElementById("totalchecksavggrowthcell").style.backgroundColor = "#0066CC";
        <?php } ?>
//totalgrowth
//echo str_replace("-","",$growth);


    }

</script>
<?php
// ************************************************************
// Excel Export - Close up document
// Write Footer
if ($a == "s") {

    // Write Totals

    /*	$text = array("Total", '');
        $worksheet->write_row('B14', $text, heading2);		*/

    $text = array($totalfigureprevious, '');
    $worksheet->write_row('C14', $text, $num1_format);

    $text = array($totalfigure, '');
    $worksheet->write_row('D14', $text, $num1_format);


    $text = array($growthtotal, '');
    if ($growthtotal > 0) {
        $worksheet->write_row('E14', $text, $num1growthpositive_format);
    }
    if ($growthtotal < 0) {
        $worksheet->write_row('E14', $text, $num1growthnegative_format);
    }
    if ($growthtotal == 0) {
        $worksheet->write_row('E14', $text, $num1growthnone_format);
    }

    $text = array($totaltransprevious, '');
    $worksheet->write_row('F14', $text, $num1_formatnodecimals);

    $text = array($totaltrans, '');
    $worksheet->write_row('G14', $text, $num1_formatnodecimals);


    $text = array($growthtrans, '');
    if ($growthtrans > 0) {
        $worksheet->write_row('H14', $text, $num1growthpositive_format);
    }
    if ($growthtrans < 0) {
        $worksheet->write_row('H14', $text, $num1growthnegative_format);
    }
    if ($growthtrans == 0) {
        $worksheet->write_row('H14', $text, $num1growthnone_format);
    }

    $text = array(number_format($totalfigureprevious / $totaltransprevious, 2, '.', ''), '');
    $worksheet->write_row('I14', $text, $num1_format);

    $text = array(number_format($totalfigure / $totaltrans, 2, '.', ''), '');
    $worksheet->write_row('J14', $text, $num1_format);

    $text = array($growthavgtrans, '');
    if ($growthavgtrans > 0) {
        $worksheet->write_row('K14', $text, $num1growthpositive_format);
    }
    if ($growthavgtrans < 0) {
        $worksheet->write_row('K14', $text, $num1growthnegative_format);
    }
    if ($growthavgtrans == 0) {
        $worksheet->write_row('K14', $text, $num1growthnone_format);
    }

    $text = array($totalchecksprevious, '');
    $worksheet->write_row('L14', $text, $num1_formatnodecimals);

    $text = array($totalchecks, '');
    $worksheet->write_row('M14', $text, $num1_formatnodecimals);

    $text = array($growthchecks, '');
    if ($growthchecks > 0) {
        $worksheet->write_row('N14', $text, $num1growthpositive_format);
    }
    if ($growthchecks < 0) {
        $worksheet->write_row('N14', $text, $num1growthnegative_format);
    }
    if ($growthchecks == 0) {
        $worksheet->write_row('N14', $text, $num1growthnone_format);
    }

    $text = array(number_format($totalfigureprevious / $totalchecksprevious, 2, '.', ''), '');
    $worksheet->write_row('O14', $text, $num1_format);

    $text = array(number_format($totalfigure / $totalchecks, 2, '.', ''), '');
    $worksheet->write_row('P14', $text, $num1_format);

    $text = array($growthavgchecks, '');
    if ($growthavgchecks > 0) {
        $worksheet->write_row('Q14', $text, $num1growthpositive_format);
    }
    if ($growthavgchecks < 0) {
        $worksheet->write_row('Q14', $text, $num1growthnegative_format);
    }
    if ($growthavgchecks == 0) {
        $worksheet->write_row('Q14', $text, $num1growthnone_format);
    }
    $text = array(" ", '');;
    $worksheet->write_row('R14', $text, $heading2);

    $rownumber++; // remove this
    $worksheet->insert_bitmap('a' . $rownumber, 'classes/excelexport/report_footer_landscape.bmp', 16, 8); // Write Footer
    $workbook->close(); // Close the workbook
}
// ************************************************************
?>
