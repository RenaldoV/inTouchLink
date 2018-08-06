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

    .style4 {
        color: #FFFFFF;
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
            <div class='col-sm-8 col-sm-offset-2 formdiv'>
                <div class="row">
                    <div class="col-sm-12">
                        <h3 style="text-transform: uppercase;" class="text-left">
                            Total Summary Report
                        </h3>
                    </div>
                </div>
                <form id="frmparameters" name="frmparameters" method="post" action="index.php?p=report_totalsummary&a=s" onSubmit="return CheckDateRange(this);">
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
                        <div class="col-sm-4">
                            <label for="cmbdataexpetionorder">Report Option</label>
                        </div>
                        <div class="col-sm-8">
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
            // *******************************************************************

            //Report Option 1 (Standard)
            if ($_POST['cmbReportOption'] == "Option 1") {

                if ($radDate == "date") {
                    $result = GetTotalSummary("'" . $_SESSION["dateyear"] . "/" . $_SESSION["datemonth"] . "/" . $_SESSION["dateday"] . "'", $_SESSION["store"]);
                    $sumIDResult = GetSumIDforWithDateRange("'" . $_SESSION["dateyear"] . "/" . $_SESSION["datemonth"] . "/" . $_SESSION["dateday"] . "'", $_SESSION["store"]);
                }

                if ($radDate == "daterange") {
                    $result = GetTotalSummary($_SESSION["daterangestring"], $_SESSION["store"]);
                    $sumIDResult = GetSumIDforWithDateRange($_SESSION["daterangestring"], $_SESSION["store"]);
                }
                $_SESSION['ReportOption'] = 1;
            }

            //Report Option 2 (Net Sales + Comps)
            if ($_POST['cmbReportOption'] == "Option 2") {

                if ($radDate == "date") {
                    $result = GetTotalSummaryOp2("'" . $_SESSION["dateyear"] . "/" . $_SESSION["datemonth"] . "/" . $_SESSION["dateday"] . "'", $_SESSION["store"]);
                    $sumIDResult = GetSumIDforWithDateRange("'" . $_SESSION["dateyear"] . "/" . $_SESSION["datemonth"] . "/" . $_SESSION["dateday"] . "'", $_SESSION["store"]);
                }

                if ($radDate == "daterange") {
                    $result = GetTotalSummaryOp2($_SESSION["daterangestring"], $_SESSION["store"]);
                    $sumIDResult = GetSumIDforWithDateRange($_SESSION["daterangestring"], $_SESSION["store"]);
                }

                $_SESSION['ReportOption'] = 2;
            }


            if (mysql_num_rows($sumIDResult) > 0) {
                $sumidrow = mysql_fetch_array($sumIDResult);
                $sumid = "'" . $sumidrow["sumid"] . "'";
            }
            while ($sumidrow = mysql_fetch_array($sumIDResult)) {
                $sumid = $sumid . ",'" . $sumidrow["sumid"] . "'";
            }
            ?>
            <div class="col-sm-12">
                <h3 style="text:#007CC4;" class="text-center">
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
                ?>
            </div>
            <?php
            // ************************************
            // EXCEL - Set up report title
            // *
            // Report Title
            $title = "Total Summary Report for ";
            $headings = array($title, '');
            $worksheet->write_row('D6', $headings, $heading);

            // Report Specs
            $$excelreport_title = $excelreport_title;
            $headings = array($excelreport_title, '');
            $worksheet->write_row('D7', $headings, $heading);
            $num1_format =& $workbook->addformat(array(num_format => '0.00'));  //Basic number format
            // ************************************
            ?>
            <div class="col-sm-12">
                <table class="table table-striped .table-condensed .table-bordered" style="margin-bottom: 0px;">
                    <tr>
                        <td class="NormalText"><span class="">Date</span>
                        </td>
                        <td class="NormalText">
                            <div align="right" class="">GROSS</div>
                        </td>
                        <td class="NormalText">
                            <div align="right" class="">NETT</div>
                        </td>
                        <td class="NormalText">
                            <div align="right" class="">Banking Sales</div>
                        </td>
                    </tr>
                    <?php
                    // ************************************************
                    // Excel
                    // Write Table Headers
                    $title = "Date";
                    $headings = array($title, '');
                    $worksheet->write_row('C10', $headings, $heading2);

                    $title = "GROSS";
                    $headings = array($title, '');
                    $worksheet->write_row('E10', $headings, $RightNumberTotalBold);

                    $title = "NETT";
                    $headings = array($title, '');
                    $worksheet->write_row('F10', $headings, $RightNumberTotalBold);

                    $title = "Banking Sales";
                    $headings = array($title, '');
                    $worksheet->write_row('G10', $headings, $RightNumberTotalBold);

                    $rownumber = 11;

                    $grosstotal = 0.00;
                    $netttotal = 0.00;
                    $bankingtotal = 0.00;

                    // Make the list of stores involved - even if no data for a specific day

                    $date_array = array();
                    while ($row = mysql_fetch_array($result)) {
                        array_push($date_array, $row["sumdate"]);
                    }
                    // Delete duplicates and sort.
                    $date_array = array_keys(array_flip($date_array));

                    $chartdata = array(array());
                    // Fill with Zeros
                    for ($y = 0; $y <= count($date_array); $y++) {
                        for ($x = 0; $x <= 3; $x++) {
                            $chartdata[$x][$y] = "0.00";
                        }
                    }

                    $chartdata[0][0] = ""; // Set first blank cell

                    // SET DATE COLUMN HEADER
                    for ($i = 0; $i < count($date_array); $i++) {
                        $chartdata[0][$i + 1] = $date_array[$i];
                    }
                    // SET DATE ROW HEADER ON LEFT

                    $chartdata[1][0] = "Gross";
                    $chartdata[2][0] = "Nett";
                    $chartdata[3][0] = "Banking";

                    // Set counters
                    $chart_col = 1;
                    $chart_row = 1;

                    mysql_data_seek($result, 0); //  Move pointer to first record.

                    while ($row = mysql_fetch_array($result)) {
                        $dateArr = explode('/', $row["sumdate"]);
                        $displaydate = $dateArr[1] . '/' . $dateArr[2] . '/' . $dateArr[0];
                        ?>
                        <tr>
                            <td class="NormalText">
                                <div align="left"><strong><?php echo $displaydate; ?></strong></div>
                            </td>
                            <td class="NormalText">
                                <div align="right"><?php $grosstotal = $grosstotal + $row["sumgrosssales"];
                                    echo $row["sumgrosssales"]; ?></div>
                            </td>
                            <td class="NormalText">
                                <div align="right"><?php $netttotal = $netttotal + $row["sumnettsales"];
                                    echo $row["sumnettsales"]; ?></div>
                            </td>
                            <td class="NormalText">
                                <div align="right"><?php $bankingtotal = $bankingtotal + $row["sumbankingsales"];
                                    echo $row["sumbankingsales"]; ?></div>
                            </td>
                        </tr>
                        <?php
// Find store row number
                        $chartdata[1][$chart_col] = $row["sumgrosssales"];    // SET GROSS
                        $chartdata[2][$chart_col] = $row["sumnettsales"];    // SET NETT
                        $chartdata[3][$chart_col] = $row["sumbankingsales"];    // SET BANKING

                        $chart_col++;

// ************************************************
// Excel
                        $title = $row["sumdate"];;
                        $headings = array($title, '');
                        $worksheet->write_row('C' . $rownumber, $headings, $NormalLeftAlign);

                        $title = $row["sumgrosssales"];
                        $headings = array($title, '');
                        $worksheet->write_row('E' . $rownumber, $headings, $num1_format);

                        $title = $row["sumnettsales"];
                        $headings = array($title, '');
                        $worksheet->write_row('F' . $rownumber, $headings, $num1_format);

                        $title = $row["sumbankingsales"];
                        $headings = array($title, '');
                        $worksheet->write_row('G' . $rownumber, $headings, $num1_format);

                        $rownumber++;

                    }
                    // ********************************
                    // Excel
                    // Write the totals

                    $title = "Totals";
                    $headings = array($title, '');
                    $worksheet->write_row('D' . $rownumber, $headings, $RightNumberTotalBold);

                    $title = number_format($grosstotal, 2, '.', '');
                    $headings = array($title, '');
                    $worksheet->write_row('E' . $rownumber, $headings, $RightNumberTotalBold);

                    $title = number_format($netttotal, 2, '.', '');
                    $headings = array($title, '');
                    $worksheet->write_row('F' . $rownumber, $headings, $RightNumberTotalBold);

                    $title = number_format($bankingtotal, 2, '.', '');
                    $headings = array($title, '');
                    $worksheet->write_row('G' . $rownumber, $headings, $RightNumberTotalBold);
                    //file_put_contents('php://stderr', print_r($worksheet . "\n", TRUE));
                    ?>
                    <tr>
                        <td class="NormalText bold"><strong>Totals</strong></td>
                        <td class="NormalText bold">
                            <div align="right"><?php echo number_format($grosstotal, 2, '.', ''); ?></div>
                        </td>
                        <td class="NormalText bold">
                            <div align="right"><?php echo number_format($netttotal, 2, '.', ''); ?></div>
                        </td>
                        <td class="NormalText bold">
                            <div align="right"><?php echo number_format($bankingtotal, 2, '.', ''); ?></div>
                        </td>
                    </tr>
                </table>
                <div class="col-sm-12">
                    <hr style="margin-top: 0px;">
                </div>
                <div align="center">
                    <table width="738" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                            <td width="738">
                                <?php
                                // ************************************************************
                                // Excel Export - Close up document
                                // *
                                // Write Footer
                                $rownumber = $rownumber + 3; // Add some whitespace
                                $worksheet->insert_bitmap('a'.$rownumber, 'classes/excelexport/report_footer.bmp', 16, 8); // Write Footer
                                $workbook->close(); // Close the workbook
                                // ************************************************************
                                ?>
                                <div align="center"></div></td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="col-sm-1 col-sm-offset-10 text-center">
                <a href="classes/excelexport/excelexport.php?fname=<?php echo $fname; ?>" target="_blank" class="icon">
                    <i class="fas fa-file-excel fa-3x"></i>
                </a>
            </div>
            <div class="col-sm-1">
                <a href="Javascript:;" class="icon"><span class="glyphicon glyphicon-print"
                                                          onMouseUp="NewWindow('pages/report_totalsummary_print.php?<?php echo "radDate=" . $radDate . "&dateday=" . $_SESSION["dateday"] . "&datemonth=" . $_SESSION["datemonth"] . "&dateyear=" . $_SESSION["dateyear"] . "&store=" . str_replace("'", "^", $_SESSION["store"]) . "&a=" . $a . "&datefromday=" . $_SESSION["datefromday"] . "&datefrommonth=" . $_SESSION["datefrommonth"] . "&datefromyear=" . $_SESSION["datefromyear"] . "&datetoday=" . $_SESSION["datetoday"] . "&datetomonth=" . $_SESSION["datetomonth"] . "&grpid=" . $grpid . "&radStores=" . $radStores . "&datetoyear=" . $_SESSION["datetoyear"]; ?>','report_totalsummary','650','500','yes');return false;"></span></a>
            </div>
            <div class="col-sm-12">
                <p align="left" class="NormalText"><span class="NormalHeading">Calcuation Methods:</span><br/>
                    <strong>Gross Sales</strong> - Less Voids Surch. Order Charges Add Chgs<br/>
                    <strong>Nett Sales</strong> - Less Voids, Comps, Promos, Taxes, Surch. Order charges Add Chgs<br/>
                    <strong>Banking Sales</strong> - Less Voids, Comps, Promos, Surch. Order Charges Add Chgs<br/>
                </p>
            </div>
            <?php
            $_SESSION["totalsummary_chartdata"] = $chartdata;
        } else { ?>
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
