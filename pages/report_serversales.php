<link href="../style.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css"/>
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.10/css/all.css"
      integrity="sha384-+d0P83n9kaQMCwj8F4RJB66tzIwOKmrdb46+porD/OvrJ+37WqIM7UoBtwHO6Nlg" crossorigin="anonymous">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/css/bootstrap-select.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/js/bootstrap-select.min.js"></script>
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

        $('.selectpicker').selectpicker({
            dropupAuto: false
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
                        Employee Sales Report
                    </h3>
                </div>
            </div>
            <form id="frmparameters" name="frmparameters" method="post" action="index.php?p=report_serversales&a=s&a2=listservers">
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
                <input name="hiddenyestermonth" type="hidden" id="hiddenyestermonth" value="<?php echo $yesterdaymonth;?>" />
                <input name="hiddenyesteryear" type="hidden" id="hiddenyesteryear" value="<?php echo $yesterdayyear;?>" />
                <input name="Submit" type="submit" class="btn btn-default" value="Show Employees"
                       style="color: white; background-color: #007CC4"/>
            </form>
            <?php
            if ($a == "s" || $a2 == "listservers") {
            $result = GetSummaryTotals($_SESSION["dateyear"] . "/" . $_SESSION["datemonth"] . "/" . $_SESSION["dateday"], $_SESSION["store"]);
            $sumIDResult = GetSumIDforWithDateRange("'" . $_SESSION["dateyear"] . "/" . $_SESSION["datemonth"] . "/" . $_SESSION["dateday"] . "'", $_SESSION["store"]);
            $row = mysql_fetch_array($result);
            if (mysql_num_rows($sumIDResult) > 0) {
            $sumidrow = mysql_fetch_array($sumIDResult);
            $sumid = "'" . $sumidrow["sumid"] . "'";
            while ($sumidrow = mysql_fetch_array($sumIDResult)) {
                $sumid = $sumid . ",'" . $sumidrow["sumid"] . "'";
            }
            $result = GetAllEmployees($sumid);
            // Only show this form if there are any employees
            if (mysql_num_rows($result) > 0) {
            ?>
            <div class="row">
                <div class="col-sm-12">
                    <hr>
                    <h4 style="color:#757575" class="text-center">Select the servers to be included in the report</h4>
                    <hr>
                    <form id="frmemployees" name="frmemployees" method="post" action="index.php?p=report_serversales&a=s&a2=s">
                        <div class="row">
                            <div class="col-sm-4"><label for="selectpicker">Select Server</label></div>
                            <div class="col-sm-8">
                                <div class="form-group">
                                    <select name="chkemployees[]" id="chkemployees" class="selectpicker form-group dropdown" data-width="100%" multiple data-actions-box="true">
                                        <?php
                                        while ($row = mysql_fetch_array($result)) {
                                        ?>
                                        <option value="<?php echo $row["empno"]; ?>"
                                                <?php
                                                $chkemployees = $_REQUEST["chkemployees"];
                                                while (list ($key, $val) = @each($chkemployees)) {
                                                    if ($val == $row["empno"]) {
                                                        echo "selected='true'";
                                                    }
                                                }
                                                ?>>
                                            <?php echo $row["empname"] . " " . $row["empsurname"]; ?>
                                        </option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <input name="cmbstore" type="hidden" id="cmbstore"
                                   value="<?php echo $_REQUEST["cmbstore"]; ?>"/>
                            <input name="dateday" type="hidden" id="dateday"
                                   value="<?php echo $_REQUEST["dateday"]; ?>"/>
                            <input name="datemonth" type="hidden" id="datemonth"
                                   value="<?php echo $_REQUEST["datemonth"]; ?>"/>
                            <input name="dateyear" type="hidden" id="dateyear"
                                   value="<?php echo $_REQUEST["dateyear"]; ?>"/>
                            <input name="radstores" type="hidden" id="radstores" value="store"/>
                            <input name="radDate" type="hidden" id="radDate" value="date"/>
                            <div class="col-sm-4 col-sm-offset-8">
                                <input name="Submit" type="submit" class="btn btn-default" value="Submit" style="color: white; background-color: #007CC4"/>
                            </div>
                        </div>
                        <!--<table width="494" height="120" border="0" cellpadding="3" cellspacing="0">

                            <tr>
                                <td width="152" height="20" class="NormalText">
                                    <div align="right">
                                        <input name="chkall" type="checkbox" id="chkall" value="true"
                                               onmousedown="Javascript : if(document.frmemployees.chkall.checked == false) { checkAll(chkemployees); } else { uncheckAll(chkemployees);}"/>
                                    </div>
                                </td>
                                <td width="330"><strong class="NormaBoldGreen">Select or unselect all</strong></td>
                            </tr>

                            <tr>
                                <td height="7" colspan="2" class="NormalText">
                                    <hr size="1"/>
                                </td>
                            </tr>
                            <?php
/*                            while ($row = mysql_fetch_array($result)) {
                                */?>
                                <tr>
                                    <td height="20" class="NormalText">
                                        <div align="right">
                                            <input name="chkemployees[]" type="checkbox" id="chkemployees"
                                                   value="<?php /*echo $row["empno"]; */?>"
                                                   onmousedown="Javascript: document.frmemployees.chkall.checked = false;"
                                                <?php
/*
                                                $chkemployees = $_REQUEST["chkemployees"];
                                                while (list ($key, $val) = @each($chkemployees)) {
                                                    if ($val == $row["empno"]) {
                                                        echo "checked='checked'";
                                                    }
                                                }
                                                */?> />
                                        </div>
                                    </td>
                                    <td class="NormalText"><?php /*echo $row["empname"] . " " . $row["empsurname"]; */?></td>
                                </tr>
                            <?php /*} */?>
                            <tr>
                                <td height="28" class="NormalText">
                                    <input name="cmbstore" type="hidden" id="cmbstore"
                                           value="<?php /*echo $_REQUEST["cmbstore"]; */?>"/>
                                    <input name="dateday" type="hidden" id="dateday"
                                           value="<?php /*echo $_REQUEST["dateday"]; */?>"/>
                                    <input name="datemonth" type="hidden" id="datemonth"
                                           value="<?php /*echo $_REQUEST["datemonth"]; */?>"/>
                                    <input name="dateyear" type="hidden" id="dateyear"
                                           value="<?php /*echo $_REQUEST["dateyear"]; */?>"/>
                                    <input name="radstores" type="hidden" id="radstores" value="store"/>
                                    <input name="radDate" type="hidden" id="radDate" value="date"/></td>
                                <td><label>
                                        <input name="Submit" type="submit" class="NormalText" value="Submit"/>
                                    </label></td>
                            </tr>
                        </table>-->
                    </form>
                    <?php } else {

                        echo "There are no employee records available.";
                    }
                    } else {
                        echo "<span class='NormalRed'>There are no employee records available.</span>";
                    }
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="col-sm-8 col-sm-offset-2 col-md-10 col-md-offset-1 col-lg-8 col-lg-offset-2">
    <?php
    // -------------- See if any data is in Summary Table -----------------------
    if ($a2 == 's') {
    $dataavailable = true;
    if ($radDate == 'date' && IsReportAvailableForStore($_SESSION["dateyear"] . "/" . $_SESSION["datemonth"] . "/" . $_SESSION["dateday"], $_SESSION["store"]) != 'true') {
        $dataavailable = false;
    }
    if ($radDate == 'daterange' && IsReportAvailableForStoreInDateRange($_SESSION["daterangestring"], $_SESSION["store"]) != 'true') {
        $dataavailable = false;
    }
    // ----------- END OF DATA AVAILABILITY CHECK -------------------------------

    if ($dataavailable == true){

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
    $worksheet->set_column('C:C', 9);
    $worksheet->set_column('D:D', 10);
    $worksheet->set_column('E:E', 7);
    $worksheet->set_column('F:F', 10);
    $worksheet->set_column('G:G', 7);
    $worksheet->set_column('H:H', 11);
    $worksheet->set_column('I:I', 9);
    $worksheet->set_column('J:J', 14);
    $worksheet->insert_bitmap('a1', 'classes/excelexport/report_header.bmp', 16, 8); // Write Header
    // Set styles
    $heading =& $workbook->addformat(array(
        bold => 1,
        color => '007CC4',
        size => 9,
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

    // TODO : GET SUMID TO PROPERLY LIST ALL SUMIDs
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
    $title = "Hourly Sales and Labour Report for ";
    $headings = array($title, '');
    $worksheet->write_row('F6', $headings, $heading);
    // Report Specs
    $$excelreport_title = $excelreport_title;
    $headings = array($excelreport_title, '');
    $worksheet->write_row('F7', $headings, $heading);
    $num1_format =& $workbook->addformat(array(num_format => '0.00'));  //Basic number format
    // ************************************
    ?>
    <div class="col-sm-12">
        <hr>
        <?php
        $chkemployees = $_REQUEST["chkemployees"];
        $_SESSION["chkemployees"] = $_REQUEST["chkemployees"];
        while (list ($key, $val) = @each($chkemployees)) {
            $result = GetEmployee($sumid, $val);
            $row = mysql_fetch_array($result);
            ?>
            <h4 style="color:#757575" class="text-left"><b>Employee
                    : <?php echo trim($row["empname"] . " " . $row["empsurname"]); ?></b></h4>
            <table class="table table-condensed">
                <tr>
                    <td bgcolor="#007BC4" style="color:white"><strong>Category </strong></td>
                    <td bgcolor="#007BC4" style="color:white">
                        <div align="right">Net Sales</div>
                    </td>
                    <td bgcolor="#007BC4" style="color:white">
                        <div align="right">Comps</div>
                    </td>
                    <td bgcolor="#007BC4" style="color:white">
                        <div align="right">Promos</div>
                    </td>
                    <td bgcolor="#007BC4" style="color:white">
                        <div align="right">Taxes</div>
                    </td>
                    <td bgcolor="#007BC4" style="color:white">
                        <div align="right">Gross Sales</div>
                    </td>
                </tr>
                <?php
                $result = GetEmployeeSales($sumid, $val);
                $totalnetsales = 0;
                $totalcomps = 0;
                $totalpromos = 0;
                $totaltaxs = 0;
                $totalgross = 0;
                while ($row = mysql_fetch_array($result)) {
                    ?>
                    <tr>
                        <td width="31%" class="style3"><?php echo $row["esbccatname"]; ?></td>
                        <td width="14%" class="NormalText">
                            <div align="right" class="style3"><?php echo $row["esbcnetsales"]; ?></div>
                        </td>
                        <td width="13%" class="NormalText">
                            <div align="right" class="style3"><?php echo $row["esbccomps"]; ?></div>
                        </td>
                        <td width="13%" class="NormalText">
                            <div align="right" class="style3"><?php echo $row["esbcpromos"]; ?></div>
                        </td>
                        <td width="14%" class="NormalText">
                            <div align="right" class="style3"><?php echo $row["esbctaxes"]; ?></div>
                        </td>
                        <td width="15%" class="NormalText">
                            <div align="right" class="style3"><?php echo $row["esbcgrosssales"]; ?></div>
                        </td>
                    </tr>
                    <?php
                    $totalnetsales = $totalnetsales + $row["esbcnetsales"];
                    $totalcomps = $totalcomps + $row["esbccomps"];
                    $totalpromos = $totalpromos + $row["esbcpromos"];
                    $totaltaxs = $totaltaxs + $row["esbctaxes"];
                    $totalgross = $totalgross + $row["esbcgrosssales"];
                } ?>
                <tr>
                    <td bgcolor="#F5F5F5" class="NormalText bold"><strong>Totals</strong></td>
                    <td bgcolor="#F5F5F5" class="NormalText bold">
                        <div align="right"><strong><?php echo number_format($totalnetsales, "2", ".", ""); ?></strong>
                        </div>
                    </td>
                    <td bgcolor="#F5F5F5" class="NormalText bold">
                        <div align="right"><strong><?php echo number_format($totalcomps, "2", ".", ""); ?></strong>
                        </div>
                    </td>
                    <td bgcolor="#F5F5F5" class="NormalText bold">
                        <div align="right"><strong><?php echo number_format($totalpromos, "2", ".", ""); ?></strong>
                        </div>
                    </td>
                    <td bgcolor="#F5F5F5" class="NormalText bold">
                        <div align="right"><strong><?php echo number_format($totaltaxs, "2", ".", ""); ?></strong></div>
                    </td>
                    <td bgcolor="#F5F5F5" class="NormalText bold">
                        <div align="right"><strong><?php echo number_format($totalgross, "2", ".", ""); ?></strong>
                        </div>
                    </td>
                </tr>
            </table>
            <h4 style="color:#757575" class="text-center">Summary</h4>
            <table class="table table-condensed">
                <tr>
                    <td bgcolor="#007BC4" style="color:white">
                        <div align="center"><strong>Total Guests</strong></div>
                    </td>
                    <td bgcolor="#007BC4" style="color:white">
                        <div align="center">Gross Sales</div>
                    </td>
                    <td bgcolor="#007BC4" style="color:white">
                        <div align="center">Total Checks</div>
                    </td>
                    <td bgcolor="#007BC4" style="color:white">
                        <div align="center">Check Avg</div>
                    </td>
                    <td bgcolor="#007BC4" style="color:white">
                        <div align="center"> Guest Avg</div>
                    </td>
                </tr>
                <?php
                $employeetotalresult = GetEmployeeTotals($sumid, $val);
                $employeetotalrow = mysql_fetch_array($employeetotalresult);
                ?>
                <tr>
                    <td width="16%" class="style3">
                        <div align="center"><?php echo $employeetotalrow["empguests"]; ?></div>
                    </td>
                    <td width="16%" class="NormalText">
                        <div align="right" class="style3">
                            <div align="center"><?php echo $employeetotalrow["empgrosssales"]; ?></div>
                        </div>
                    </td>
                    <td width="16%" class="NormalText">
                        <div align="right" class="style3">
                            <div align="center"><?php echo $employeetotalrow["empchecks"]; ?></div>
                        </div>
                    </td>
                    <td width="18%" class="NormalText">
                        <div align="right" class="style3">
                            <div align="center"><?php echo $employeetotalrow["empcheckave"]; ?></div>
                        </div>
                    </td>
                    <td width="17%" class="NormalText">
                        <div align="right" class="style3">
                            <div align="center"><?php echo $employeetotalrow["empguestave"]; ?></div>
                        </div>
                    </td>
                </tr>
            </table>
            <div class="row">
                <?php if(IsBreakdownValid($sumid,$val) == 'true') { ?>
                    <div class="col-sm-8 col-sm-offset-2">
                        <h4 style="color:#757575" class="text-center">Payments Breakdown</h4>
                        <table class="table table-condensed">
                            <tr>
                                <td bgcolor="#007BC4" style="color:white">
                                    <div align="left"><strong>Payment Type</strong></div>
                                </td>
                                <td bgcolor="#007BC4" style="color:white">
                                    <div align="right">Amount</div>
                                </td>
                            </tr>
                            <?php
                            // ************************************************
                            // Excel
                            // Get list of employees
                            $resultpaymentbreakdown = GetEmployeePaymentBreakdown($sumid, $val);
                            $totalbreakdown = 0;
                            while ($rowpaymentbreakdown = mysql_fetch_array($resultpaymentbreakdown)) {
                                ?>
                                <tr>
                                    <td width="62%" class="style3">
                                        <div align="left"><?php echo $rowpaymentbreakdown["pbrpaymenttype"]; ?></div>
                                    </td>
                                    <td width="38%" class="NormalText">
                                        <div align="right"
                                             class="style3"><?php echo $rowpaymentbreakdown["pbramount"]; ?></div>
                                    </td>
                                </tr>
                                <?php
                                $totalbreakdown = $totalbreakdown + $rowpaymentbreakdown["pbramount"];
                            } ?>
                            <tr>
                                <td bgcolor="#F5F5F5" class="NormalText bold"><strong>Total</strong></td>
                                <td bgcolor="#F5F5F5" class="NormalText bold">
                                    <div align="right"><strong><?php
                                            echo number_format($totalbreakdown, "2", ".", "");
                                            ?></strong></div>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <?php
                }
                ?>
            </div>
            <hr>
            <?php
        }
        ?>
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

    </div>
    <div class="col-sm-1 col-sm-offset-11">
                    <span class="glyphicon glyphicon-print"
                          onMouseUp="NewWindow('pages/report_serversales_print.php?<?php echo "radDate=".$radDate."&dateday=".$_SESSION["dateday"]."&datemonth=".$_SESSION["datemonth"]."&dateyear=".$_SESSION["dateyear"]."&store=".str_replace("'", "^", $_SESSION["store"])."&a=".$a."&datefromday=".$_SESSION["datefromday"]."&datefrommonth=".$_SESSION["datefrommonth"]."&datefromyear=".$_SESSION["datefromyear"]."&datetoday=".$_SESSION["datetoday"]."&datetomonth=".$_SESSION["datetomonth"]."&grpid=".$grpid."&radStores=".$radStores."&datetoyear=".$_SESSION["datetoyear"]."&chkemployees=".$chkemployees; ?>','report_employeesales','650','500','yes');return false;"></span></a>
    </div>
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

    if($radDate == "date") {
        echo "SetSpecificDateFocus();";
    }
    if($radDate == "daterange") {
        echo "SetSpecificDateRangeFocus();";
    }
?>

function checkAll(field)
{
for (i = 0; i < field.length; i++)
	field[i].checked = true ;
}

function uncheckAll(field)
{
for (i = 0; i < field.length; i++)
	field[i].checked = false ;
}

 
</script>
