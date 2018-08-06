<?php

// Set comparison report type
$_SESSION["comparisonreporttype"] = $_REQUEST["radreporttype"];
if ($_REQUEST["radreporttype"] == null) {
    $_SESSION["comparisonreporttype"] = "summary";
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


<?php if ($_SESSION["comparisonreporttype"] == "detail") { ?>
    <div class='col-sm-12'>
        <div class="row form-row">
            <div class="col-sm-8 col-sm-offset-2 formdiv">
                <div class="row">
                    <div class="col-sm-12">
                        <h3 style="text-transform: uppercase;" class="text-left">
                            Total Comparison Detail Report
                        </h3>
                    </div>
                </div>
                <form id="frmparameters" name="frmparameters" method="post"
                      action="index.php?p=report_totalcomparison&a=s"
                      onSubmit="return CheckDateRange(this);">
                    <div class="row">
                        <div class="col-sm-4">
                            <label for="cmbstoregroup">Store Group</label>
                        </div>
                        <input name="radStores" type="radio" value="storegroup" checked='checked' hidden/>
                        <div class="col-sm-8">
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
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="radio">
                                <label>
                                    <input name="radreporttype" type="radio" id="radio" value="summary"/>
                                    <b>Total Comparison Sumary</b>
                                </label>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="radio">
                                <label>
                                    <input type="radio" name="radreporttype" id="radio2" value="detail"
                                           checked='checked'/>
                                    <b>Total Comparison Details</b>
                                </label>
                            </div>
                        </div>
                    </div>
                    <input name="Submit" type="submit" class="btn btn-default" value="Submit"
                           style="color: white; background-color: #007CC4"/>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1">
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
                // REPORT OUTPUT STARTS HERE
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
                $worksheet->set_column('C:C', 14);
                $worksheet->set_column('D:D', 20);
                $worksheet->set_column('E:J', 14.75);
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


                if ($_POST['cmbReportOption'] == "Option 1") {

                    if ($radDate == "date") {
                        $result = GetTotalComparison("'" . $_SESSION["dateyear"] . "/" . $_SESSION["datemonth"] . "/" . $_SESSION["dateday"] . "'", $_SESSION["store"]);
                        $sumIDResult = GetSumIDforWithDateRange("'" . $_SESSION["dateyear"] . "/" . $_SESSION["datemonth"] . "/" . $_SESSION["dateday"] . "'", $_SESSION["store"]);
                    }
                    if ($radDate == "daterange") {
                        $result = GetTotalComparison($_SESSION["daterangestring"], $_SESSION["store"]);
                        $sumIDResult = GetSumIDforWithDateRange($_SESSION["daterangestring"], $_SESSION["store"]);
                    }
                    $_SESSION['ReportOption'] = 1;
                }

                //Report Option 2 (Net Sales + Comps)
                if ($_POST['cmbReportOption'] == "Option 2") {

                    if ($radDate == "date") {
                        $result = GetTotalComparisonOp2("'" . $_SESSION["dateyear"] . "/" . $_SESSION["datemonth"] . "/" . $_SESSION["dateday"] . "'", $_SESSION["store"]);
                        $sumIDResult = GetSumIDforWithDateRange("'" . $_SESSION["dateyear"] . "/" . $_SESSION["datemonth"] . "/" . $_SESSION["dateday"] . "'", $_SESSION["store"]);
                    }
                    if ($radDate == "daterange") {
                        $result = GetTotalComparisonOp2($_SESSION["daterangestring"], $_SESSION["store"]);
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
                // ************************************
                // EXCEL - Set up report title
                // *
                // Report Title
                $title = "Total Comparison Detail Report for ";
                $headings = array($title, '');
                $worksheet->write_row('D6', $headings, $heading);
                // Report Specs
                $$excelreport_title = $excelreport_title;
                $headings = array($excelreport_title, '');
                $worksheet->write_row('D7', $headings, $heading);
                $num1_format =& $workbook->addformat(array(num_format => '0.00'));  //Basic number format
                // ************************************
                ?>
                <table class="table table-striped .table-condensed .table-bordered" style="margin-bottom: 0px;">

                    <tr>
                        <td class="NormalText">Date</td>
                        <td class="NormalText">Store</td>
                        <td class="NormalText">GROSS</td>
                        <td class="NormalText">NETT</td>
                        <td class="NormalText">Banking Sales</td>
                        <td class="NormalText">Royalties</td>
                    </tr>
                    <?php

                    // ************************************************
                    // Excel
                    // Write Table Headers
                    $title = "Date";
                    $headings = array($title, '');
                    $worksheet->write_row('C10', $headings, $heading2);

                    $title = "Store";
                    $headings = array($title, '');
                    $worksheet->write_row('D10', $headings, $heading2);

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
                    $store_array = array();
                    $date_array = array();
                    while ($row = mysql_fetch_array($result)) {
                        array_push($store_array, $row["strname"]);
                        array_push($date_array, $row["sumdate"]);
                    }
                    // Delete duplicates and sort.
                    $store_array = array_keys(array_flip($store_array));
                    $date_array = array_keys(array_flip($date_array));

                    $chartdata = array(array());
                    // Fill with Zeros
                    for ($y = 0; $y <= count($date_array); $y++) {
                        for ($x = 0; $x <= count($store_array); $x++) {
                            $chartdata[$x][$y] = "0.00";
                        }
                    }

                    $chartdata[0][0] = "";

                    // SET DATE COLUMN HEADER
                    for ($i = 0; $i < count($date_array); $i++) {
                        $chartdata[0][$i + 1] = $date_array[$i];
                    }
                    // SET DATE ROW HEADER
                    for ($i = 0; $i < count($store_array); $i++) {
                        $chartdata[$i + 1][0] = $store_array[$i];
                    }

                    // Set counters
                    $chart_col = 1;
                    $chart_row = 1;

                    mysql_data_seek($result, 0); //  Move pointer to first record.
                    while ($row = mysql_fetch_array($result)) {
                        ?>
                        <tr>
                            <td class="NormalText">
                                <div align="left"><strong>
                                        <?php
                                        // Only show first date
                                        if ($olddate == null) { // First date
                                            $olddate = $row["sumdate"];
                                            $chart_col = 1;
                                            echo explode('/', $row["sumdate"])[1] . '/' . explode('/', $row["sumdate"])[2] . '/' . explode('/', $row["sumdate"])[0];
                                            // Excel
                                            $title = $row["sumdate"];;
                                            $headings = array($title, '');
                                            $worksheet->write_row('C' . $rownumber, $headings, $NormalLeftAlign);
                                        }
                                        if ($olddate != $row["sumdate"]) {
                                            echo explode('/', $row["sumdate"])[1] . '/' . explode('/', $row["sumdate"])[2] . '/' . explode('/', $row["sumdate"])[0];
                                            // Excel
                                            $title = $row["sumdate"];;
                                            $headings = array($title, '');
                                            $worksheet->write_row('C' . $rownumber, $headings, $NormalLeftAlign);
                                            $chart_col++;
                                        }

                                        $olddate = $row["sumdate"];

                                        ?>
                                    </strong></div>
                            </td>
                            <td class="NormalText"><?php echo $row["strname"]; ?></td>
                            <td class="NormalText">
                                <?php $grosstotal = $grosstotal + $row["sumgrosssales"];
                                echo $row["sumgrosssales"]; ?>
                            </td>
                            <td class="NormalText">
                                <?php
                                $netttotal = $netttotal + $row["sumnettsales"];
                                echo $row["sumnettsales"];
                                // Find store row number
                                for ($a = 0; $a <= count($store_array); $a++) {
                                    if ($store_array[$a] == $row["strname"]) { // found store
                                        $chartdata[$a + 1][$chart_col] = $row["sumnettsales"];
                                    }
                                }
                                ?>
                            </td>
                            <td class="NormalText">
                                <?php $bankingtotal = $bankingtotal + $row["sumbankingsales"];
                                echo $row["sumbankingsales"];
                                ?>
                            </td>
                            <td class="NormalText">
                                <?php
                                $royaltyresult = GetStoreRoyalty($row["strid"]);
                                $royaltyrow = mysql_fetch_array($royaltyresult);

                                if ($royaltyrow["strroyaltytype"] == "Gross") { // GROSS ROYALTY
                                    $royalty = number_format(($row["sumgrosssales"] / 100) * $royaltyrow["strroyaltypercent"], 2, '.', '');
                                }

                                if ($royaltyrow["strroyaltytype"] == "Nett") { // NETT ROYALTY
                                    $royalty = number_format(($row["sumnettsales"] / 100) * $royaltyrow["strroyaltypercent"], 2, '.', '');
                                }

                                if ($royaltyrow["strroyaltytype"] == "Banking Sales") { // BANKING SALES ROYALTY
                                    $royalty = number_format(($row["sumbankingsales"] / 100) * $royaltyrow["strroyaltypercent"], 2, '.', '');
                                }
                                if ($royaltyrow["strroyaltytype"] == "Not applicable") { // BANKING SALES ROYALTY
                                    $royalty = "N/A";
                                }
                                echo $royalty;
                                $royaltytotal = $royaltytotal + $royalty;
                                ?>
                            </td>
                        </tr>
                        <?php

// ************************************************
// Excel

                        $title = $row["strname"];
                        $headings = array($title, '');
                        $worksheet->write_row('D' . $rownumber, $headings, $num1_format);

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
                    // Excel
                    // *
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

                    ?>
                    <tr>
                        <td height="23" bgcolor="#F2F2F2" class="NormalText bold">Totals</td>
                        <td bgcolor="#F2F2F2" class="NormalText bold">&nbsp;</td>
                        <td bgcolor="#F2F2F2" class="NormalText bold">
                            <?php echo number_format($grosstotal, 2, '.', ''); ?>
                        </td>
                        <td bgcolor="#F2F2F2" class="NormalText bold">
                            <?php echo number_format($netttotal, 2, '.', ''); ?>
                        </td>
                        <td bgcolor="#F2F2F2" class="NormalText bold">
                            <?php echo number_format($bankingtotal, 2, '.', ''); ?>
                        </td>
                        <td bgcolor="#F2F2F2" class="NormalText bold">
                            <?php echo number_format($royaltytotal, 2, '.', ''); ?>
                        </td>
                    </tr>
                </table>
                <hr style="margin-top: 0px;">
                <div class="row">
                    <div class="col-sm-1 col-sm-offset-9">
                        <a href="Javascript:;"><i class="fas fa-chart-bar fa-3x"
                                                  onMouseUp="NewWindow('https://www.intouchpos.co.za/graphs/chartdisplayer.php?chart=totalcomparison','graph','700','550','yes');return false;"></i></a>
                    </div>
                    <div class="col-sm-1">
                        <a href="classes/excelexport/excelexport.php?fname=<?php echo $fname; ?>" target="_blank">
                            <i class="fas fa-file-excel fa-3x"></i>
                        </a>
                    </div>
                    <div class="col-sm-1">
                        <span class="glyphicon glyphicon-print"
                              onMouseUp="NewWindow('pages/report_totalcomparison_print.php?<?php echo "radDate=" . $radDate . "&dateday=" . $_SESSION["dateday"] . "&datemonth=" . $_SESSION["datemonth"] . "&dateyear=" . $_SESSION["dateyear"] . "&store=" . str_replace("'", "^", $_SESSION["store"]) . "&a=" . $a . "&datefromday=" . $_SESSION["datefromday"] . "&datefrommonth=" . $_SESSION["datefrommonth"] . "&datefromyear=" . $_SESSION["datefromyear"] . "&datetoday=" . $_SESSION["datetoday"] . "&datetomonth=" . $_SESSION["datetomonth"] . "&grpid=" . $grpid . "&radStores=" . $radStores . "&datetoyear=" . $_SESSION["datetoyear"]; ?>','report_totalcomparison','650','500','yes');return false;"></span></a>

                    </div>
                </div>
                <?php
                // ************************************************************
                // Excel Export - Close up document
                // *
                // Write Footer
                $rownumber = $rownumber + 3; // Add some whitespace
                $worksheet->insert_bitmap('a' . $rownumber, 'classes/excelexport/report_footer.bmp', 16, 8); // Write Footer
                $workbook->close(); // Close the workbook
                // ************************************************************
                ?>
                <p align="left" class="NormalText"><span class="NormalHeading">Calcuation Methods:</span><br/>
                    <strong>Gross Sales</strong> - Less Voids Surch. Order Charges Add Chgs<br/>
                    <strong>Nett Sales</strong> - Less Voids, Comps, Promos, Taxes, Surch. Order charges Add Chgs<br/>
                    <strong>Banking Sales</strong> - Less Voids, Comps, Promos, Surch. Order Charges Add Chgs<br/>
                </p>
                <?php
                // Add to array
                $_SESSION["totalcomparison_chartdata"] = $chartdata;
            } else { ?>
                <p align="center" class="NormalText"><br/>
                    <span class="style2">No results were returned for that query.<br/>Please try different parameters. </span>
                </p>
                </div>
                <?php
            }
        } ?>
    </div>
<?php } ?>
<?php if ($_SESSION["comparisonreporttype"] == "summary") { ?>
    <div class='col-sm-12'>
        <div class="row form-row">
            <div class='col-sm-8 col-sm-offset-2 formdiv'>
                <div class="row">
                    <div class="col-sm-12">
                        <h3 style="text-transform: uppercase;" class="text-left">
                            Total Comparison Summary Report
                        </h3>
                    </div>
                </div>
                <form id="frmparameters" name="frmparameters" method="post"
                      action="index.php?p=report_totalcomparison&a=s"
                      onSubmit="return CheckDateRange(this);">
                    <div class="row">
                        <div class="col-sm-4">
                            <label for="cmbstoregroup">Store Group</label>
                        </div>
                        <input name="radStores" type="radio" value="storegroup" checked='checked' hidden/>
                        <div class="col-sm-8">
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
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="radio">
                                <label>
                                    <input name="radreporttype" type="radio" id="radio" value="summary"
                                           checked="checked"/>
                                    <b>Total Comparison Sumary</b>
                                </label>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="radio">
                                <label>
                                    <input type="radio" name="radreporttype" id="radio2" value="detail"/>
                                    <b>Total Comparison Details</b>
                                </label>
                            </div>
                        </div>
                    </div>
                    <input name="Submit" type="submit" class="btn btn-default" value="Submit"
                           style="color: white; background-color: #007CC4"/>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1">
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
    $worksheet->set_column('C:C', 14);
    $worksheet->set_column('D:D', 20);
    $worksheet->set_column('E:J', 14.75);
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
            $result = GetTotalComparison("'" . $_SESSION["dateyear"] . "/" . $_SESSION["datemonth"] . "/" . $_SESSION["dateday"] . "'", $_SESSION["store"]);
            $sumIDResult = GetSumIDforWithDateRange("'" . $_SESSION["dateyear"] . "/" . $_SESSION["datemonth"] . "/" . $_SESSION["dateday"] . "'", $_SESSION["store"]);
        }
        if ($radDate == "daterange") {
            $result = GetTotalComparisonSummary($_SESSION["daterangestring"], $_SESSION["store"]);
            $sumIDResult = GetSumIDforWithDateRange($_SESSION["daterangestring"], $_SESSION["store"]);
        }

        $_SESSION['ReportOption'] = 1;
    }

    //Report Option 2 (Net Sales + Comps)
    if ($_POST['cmbReportOption'] == "Option 2") {

        if ($radDate == "date") {
            $result = GetTotalComparisonOp2("'" . $_SESSION["dateyear"] . "/" . $_SESSION["datemonth"] . "/" . $_SESSION["dateday"] . "'", $_SESSION["store"]);
            $sumIDResult = GetSumIDforWithDateRange("'" . $_SESSION["dateyear"] . "/" . $_SESSION["datemonth"] . "/" . $_SESSION["dateday"] . "'", $_SESSION["store"]);
        }
        if ($radDate == "daterange") {
            $result = GetTotalComparisonSummaryOp2($_SESSION["daterangestring"], $_SESSION["store"]);
            $sumIDResult = GetSumIDforWithDateRange($_SESSION["daterangestring"], $_SESSION["store"]);
        }

        $_SESSION['ReportOption'] = 2;
    }


    // TODO : GET SUMID TO PROPERLY LIST ALL SUMIDs
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
            // ************************************
            // EXCEL - Set up report title
            // *
            // Report Title
            $title = "Total Comparison Summary Report for ";
            $headings = array($title, '');
            $worksheet->write_row('D6', $headings, $heading);
            // Report Specs
            $$excelreport_title = $excelreport_title;
            $headings = array($excelreport_title, '');
            $worksheet->write_row('D7', $headings, $heading);
            $num1_format =& $workbook->addformat(array(num_format => '0.00'));  //Basic number format
            // ************************************
            ?>
            <table class="table table-striped .table-condensed .table-bordered" style="margin-bottom: 0px;">
                <tr>
                    <td class="NormalText">Store</td>
                    <td class="NormalText">GROSS</td>
                    <td class="NormalText">NETT</td>
                    <td class="NormalText">Banking Sales</td>
                    <td class="NormalText">Royalties</td>
                </tr>
                <?php

                // ************************************************
                // Excel
                // Write Table Headers
                $title = "Date";
                $headings = array($title, '');
                $worksheet->write_row('C10', $headings, $heading2);

                $title = "Store";
                $headings = array($title, '');
                $worksheet->write_row('D10', $headings, $heading2);

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
                $store_array = array();
                $date_array = array();
                while ($row = mysql_fetch_array($result)) {
                    array_push($store_array, $row["strname"]);
                    array_push($date_array, $row["sumdate"]);
                }
                // Delete duplicates and sort.
                $store_array = array_keys(array_flip($store_array));
                $date_array = array_keys(array_flip($date_array));

                $chartdata = array(array());
                // Fill with Zeros
                for ($y = 0; $y <= count($date_array); $y++) {
                    for ($x = 0; $x <= count($store_array); $x++) {
                        $chartdata[$x][$y] = "0.00";
                    }
                }

                $chartdata[0][0] = "";

                // SET DATE COLUMN HEADER
                for ($i = 0; $i < count($date_array); $i++) {
                    $chartdata[0][$i + 1] = $date_array[$i];
                }
                // SET DATE ROW HEADER
                for ($i = 0; $i < count($store_array); $i++) {
                    $chartdata[$i + 1][0] = $store_array[$i];
                }

                // Set counters
                $chart_col = 1;
                $chart_row = 1;

                mysql_data_seek($result, 0); //  Move pointer to first record.
                while ($row = mysql_fetch_array($result)) {
                    ?>
                    <tr>
                        <td class="NormalText"><?php echo $row["strname"]; ?></td>
                        <td class="NormalText">
                            <?php $grosstotal = $grosstotal + $row["sumgrosssales"];
                            echo $row["sumgrosssales"]; ?>
                        </td>
                        <td class="NormalText">
                            <?php
                            $netttotal = $netttotal + $row["sumnettsales"];
                            echo $row["sumnettsales"];
                            // Find store row number
                            for ($a = 0; $a <= count($store_array); $a++) {
                                if ($store_array[$a] == $row["strname"]) { // found store
                                    $chartdata[$a + 1][$chart_col] = $row["sumnettsales"];
                                }
                            }
                            ?>
                        </td>
                        <td class="NormalText">
                            <?php $bankingtotal = $bankingtotal + $row["sumbankingsales"];
                            echo $row["sumbankingsales"]; ?>
                        </td>
                        <td class="NormalText">
                            <?php
                            $royaltyresult = GetStoreRoyalty($row["strid"]);
                            $royaltyrow = mysql_fetch_array($royaltyresult);

                            if ($royaltyrow["strroyaltytype"] == "Gross") { // GROSS ROYALTY
                                $royalty = number_format(($row["sumgrosssales"] / 100) * $royaltyrow["strroyaltypercent"], 2, '.', '');
                            }

                            if ($royaltyrow["strroyaltytype"] == "Nett") { // NETT ROYALTY
                                $royalty = number_format(($row["sumnettsales"] / 100) * $royaltyrow["strroyaltypercent"], 2, '.', '');
                            }

                            if ($royaltyrow["strroyaltytype"] == "Banking Sales") { // BANKING SALES ROYALTY
                                $royalty = number_format(($row["sumbankingsales"] / 100) * $royaltyrow["strroyaltypercent"], 2, '.', '');
                            }
                            if ($royaltyrow["strroyaltytype"] == "Not applicable") { // BANKING SALES ROYALTY
                                $royalty = "N/A";
                            }
                            echo $royalty;
                            $royaltytotal = $royaltytotal + $royalty;
                            ?>
                        </td>
                    </tr>
                    <?php

                    // ************************************************
                    // Excel


                    $title = $row["strname"];
                    $headings = array($title, '');
                    $worksheet->write_row('D' . $rownumber, $headings, $num1_format);

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
                // *
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

                ?>
                <tr>
                    <td bgcolor="#F2F2F2" class="NormalText bold"><strong>Totals</strong></td>
                    <td bgcolor="#F2F2F2" class="NormalText bold">
                        <?php echo number_format($grosstotal, 2, '.', ''); ?>
                    </td>
                    <td bgcolor="#F2F2F2" class="NormalText bold">
                        <?php echo number_format($netttotal, 2, '.', ''); ?>
                    </td>
                    <td bgcolor="#F2F2F2" class="NormalText bold"><?php echo number_format($bankingtotal, 2, '.', ''); ?>
                    </td>
                    <td bgcolor="#F2F2F2" class="NormalText bold">
                        <?php echo number_format($royaltytotal, 2, '.', ''); ?>
                    </td>
                </tr>
            </table>
            <hr style="margin-top: 0px;">
            <div class="row">
                <div class="col-sm-1 col-sm-offset-10">
                    <a href="classes/excelexport/excelexport.php?fname=<?php echo $fname; ?>" target="_blank">
                        <i class="fas fa-file-excel fa-3x"></i>
                    </a>
                </div>
                <div class="col-sm-1">
                        <span class="glyphicon glyphicon-print"
                              onMouseUp="NewWindow('pages/report_totalcomparisonsummary_print.php?<?php echo "radDate=" . $radDate . "&dateday=" . $_SESSION["dateday"] . "&datemonth=" . $_SESSION["datemonth"] . "&dateyear=" . $_SESSION["dateyear"] . "&store=" . str_replace("'", "^", $_SESSION["store"]) . "&a=" . $a . "&datefromday=" . $_SESSION["datefromday"] . "&datefrommonth=" . $_SESSION["datefrommonth"] . "&datefromyear=" . $_SESSION["datefromyear"] . "&datetoday=" . $_SESSION["datetoday"] . "&datetomonth=" . $_SESSION["datetomonth"] . "&grpid=" . $grpid . "&radStores=" . $radStores . "&datetoyear=" . $_SESSION["datetoyear"]; ?>','report_totalcomparison','650','500','yes');return false;"></span></a>
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
            <div align="center"><br/>
                <table width="100%" border="0" cellspacing="0" cellpadding="3">
                    <tr>
                        <td align="right" valign="top">
                            <div align="center"></div>
                        </td>
                    </tr>
                </table>
            </div>
            <p align="left" class="NormalText"><span class="NormalHeading">Calcuation Methods:</span><br/>
                <strong>Gross Sales</strong> - Less Voids Surch. Order Charges Add Chgs<br/>
                <strong>Nett Sales</strong> - Less Voids, Comps, Promos, Taxes, Surch. Order charges Add Chgs<br/>
                <strong>Banking Sales</strong> - Less Voids, Comps, Promos, Surch. Order Charges Add Chgs</p>
            <p align="left" class="NormalText"><br/>
            </p>
            <div align="center">
                <?php
                // Add to array
                $_SESSION["totalcomparison_chartdata"] = $chartdata;
                } else { ?>
            </div>
            <p align="center" class="NormalText"><br/>
                <span class="style2">No results were returned for that query.<br/>
        Please try different parameters. </span></p>
        <?php }
        } ?>
        </div>
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
                $worksheet->set_column('C:C', 14);
                $worksheet->set_column('D:D', 20);
                $worksheet->set_column('E:J', 14.75);
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
                        $result = GetTotalComparison("'" . $_SESSION["dateyear"] . "/" . $_SESSION["datemonth"] . "/" . $_SESSION["dateday"] . "'", $_SESSION["store"]);
                        $sumIDResult = GetSumIDforWithDateRange("'" . $_SESSION["dateyear"] . "/" . $_SESSION["datemonth"] . "/" . $_SESSION["dateday"] . "'", $_SESSION["store"]);
                    }
                    if ($radDate == "daterange") {
                        $result = GetTotalComparisonSummary($_SESSION["daterangestring"], $_SESSION["store"]);
                        $sumIDResult = GetSumIDforWithDateRange($_SESSION["daterangestring"], $_SESSION["store"]);
                    }

                    $_SESSION['ReportOption'] = 1;
                }

                //Report Option 2 (Net Sales + Comps)
                if ($_POST['cmbReportOption'] == "Option 2") {

                    if ($radDate == "date") {
                        $result = GetTotalComparisonOp2("'" . $_SESSION["dateyear"] . "/" . $_SESSION["datemonth"] . "/" . $_SESSION["dateday"] . "'", $_SESSION["store"]);
                        $sumIDResult = GetSumIDforWithDateRange("'" . $_SESSION["dateyear"] . "/" . $_SESSION["datemonth"] . "/" . $_SESSION["dateday"] . "'", $_SESSION["store"]);
                    }
                    if ($radDate == "daterange") {
                        $result = GetTotalComparisonSummaryOp2($_SESSION["daterangestring"], $_SESSION["store"]);
                        $sumIDResult = GetSumIDforWithDateRange($_SESSION["daterangestring"], $_SESSION["store"]);
                    }

                    $_SESSION['ReportOption'] = 2;
                }


                // TODO : GET SUMID TO PROPERLY LIST ALL SUMIDs
                if (mysql_num_rows($sumIDResult) > 0) {
                    $sumidrow = mysql_fetch_array($sumIDResult);
                    $sumid = "'" . $sumidrow["sumid"] . "'";
                }
                while ($sumidrow = mysql_fetch_array($sumIDResult)) {
                    $sumid = $sumid . ",'" . $sumidrow["sumid"] . "'";
                }
                ?>

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
                // Add to array
                $_SESSION["totalcomparison_chartdata"] = $chartdata;
            } else { ?>
                <p align="center" class="NormalText"><br/>
                    <span class="style2">No results were returned for that query.<br/>
Please try different parameters. </span></p>
            <?php }
        } ?>
    </div>
<?php } ?>
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


    if ($radStores == "storegroup") {
        echo "SetStoreGroupFocus();";
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
