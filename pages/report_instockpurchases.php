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
                        Purchases Report
                    </h3>
                </div>
            </div>
            <form id="frmparameters" name="frmparameters" method="post" action="index.php?p=report_instockpurchases&a=s"
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
                <input name="Submit" type="submit" class="btn btn-default" value="Submit"
                       style="color: white; background-color: #007CC4"/>
            </form>
        </div>
    </div>
</div>


<div class="col-sm-12 col-lg-10 col-lg-offset-1">
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
                    echo GetStoreGroupName($grpid) . $_SESSION["storegroupscount"];
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
            $title = "Instock Purchases Report for ";
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
            <?php
                if ($radStores == 'store' && $radDate == 'date') { ?>
                    <hr>
                    <h4 style="color:#757575" class="text-center">Summary</h4>
                    <table class="table table-condensed table-striped">
                        <tr>
                            <td height="23" bgcolor="#007BC4" class="NormalText style1" width="18%">
                                <div align="left"><strong>Supplier Name</strong></div>
                            </td>
                            <td height="23" bgcolor="#007BC4" class="NormalText style1" align="center"><strong>Invoice
                                    Id </strong></td>
                            <td height="23" bgcolor="#007BC4" class="NormalText style1">
                                <div align="center"><strong>Item Id</strong></div>
                            </td>
                            <td height="23" bgcolor="#007BC4" class="NormalText style1">
                                <div align="right"><strong>Item Name</strong></div>
                            </td>
                            <td height="23" bgcolor="#007BC4" class="NormalText style1">
                                <div align="right"><strong>Quantity</strong></div>
                            </td>
                            <td height="23" bgcolor="#007BC4" class="NormalText style1">
                                <div align="right"><strong>Unit Price</strong></div>
                            </td>
                            <td height="23" bgcolor="#007BC4" class="NormalText style1">
                                <div align="right"><strong>Price</strong></div>
                            </td>
                        </tr>
                        <?php
                        // ************************************************
                        // Excel
                        // Write Table Headers for GROUP
                        $title = "Item ID";
                        $headings = array($title, '');
                        $worksheet->write_row('C10', $headings, $heading2);

                        $title = "Item Name";
                        $headings = array($title, '');
                        $worksheet->write_row('E10', $headings, $RightNumberTotalBold);

                        $title = "Quantity";
                        $headings = array($title, '');
                        $worksheet->write_row('F10', $headings, $RightNumberTotalBold);

                        $title = "Unit Price";
                        $headings = array($title, '');
                        $worksheet->write_row('G10', $headings, $RightNumberTotalBold);

                        $title = "Price";
                        $headings = array($title, '');
                        $worksheet->write_row('H10', $headings, $RightNumberTotalBold);

                        $title = "Supplier";
                        $headings = array($title, '');
                        $worksheet->write_row('I10', $headings, $RightNumberTotalBold);

                        $rownumber = 11;

                        $qty = 0;
                        $amount = 0;

                        $result = GetInstockInvoices($sumid);//GetCompsSummary($sumid);
                        while ($row = mysql_fetch_array($result)) {
                            $qty = $qty + intval($row["Quantity"]);
                            $amount = $amount + floatval($row["Price"]);
                            ?>
                            <tr>
                                <td width="15%" class="NormalText">
                                    <div align="left"><?php echo $row["SupplierName"]; ?></div>
                                </td>
                                <td width="15%" class="style3" align="center"><?php echo $row["InvoiceId"]; ?></td>
                                <td width="15%" class="style3" align="center"><?php echo $row["ItemId"]; ?></td>
                                <td width="15%" class="NormalText">
                                    <div align="right"><?php echo $row["Itemname"]; ?></div>
                                </td>
                                <td width="15%" class="NormalText">
                                    <div align="right"><?php echo $row["Quantity"]; ?></div>
                                </td>
                                <td width="15%" class="NormalText">
                                    <div align="right"><?php echo $row["Unitprice"]; ?></div>
                                </td>
                                <td width="15%" class="NormalText">
                                    <div align="right"><?php echo $row["Price"]; ?></div>
                                </td>
                            </tr>

                            <?php
                            // ************************************************
                            // Excel
                            $title = $row["ItemID"];
                            $headings = array($title, '');
                            $worksheet->write_row('C' . $rownumber, $headings, $NormalLeftAlign);

                            $title = $row["ItemName"];
                            $headings = array($title, '');
                            $worksheet->write_row('E' . $rownumber, $headings, $NormalRightAlign);

                            $title = $row["Quantity"];
                            $headings = array($title, '');
                            $worksheet->write_row('F' . $rownumber, $headings, $num1_format);

                            $title = $row["Unitprice"];
                            $headings = array($title, '');
                            $worksheet->write_row('G' . $rownumber, $headings, $num1_format);

                            $title = $row["Price"];
                            $headings = array($title, '');
                            $worksheet->write_row('H' . $rownumber, $headings, $num1_format);

                            $title = $row["SupplierName"];
                            $headings = array($title, '');
                            $worksheet->write_row('I' . $rownumber, $headings, $num1_format);


                            $rownumber++;
                        } ?>

                        <tr>
                            <td bgcolor="#F2F2F2" class="NormalText">
                                <div align="right"><strong></strong></div>
                            </td>
                            <td bgcolor="#F2F2F2" colspan="3" class="text-right"><strong>Totals</strong></td>
                            <td bgcolor="#F2F2F2" class="NormalText">
                                <div align="right"><strong><?php echo $qty; ?></strong></div>
                            </td>
                            <td bgcolor="#F2F2F2" class="NormalText">
                                <div align="right"><strong></strong></div>
                            </td>
                            <td bgcolor="#F2F2F2" class="NormalText">
                                <div align="right"><strong><?php printf("%.2f", $amount); ?></strong></div>
                            </td>
                        </tr>
                    </table>
                    <?php // ********************************
                    // Excel
                    // *
                    // Write the totals
                    $title = "Totals";
                    $headings = array($title, '');
                    $worksheet->write_row('D' . $rownumber, $headings, $RightNumberTotalBold);

                    $title = $qty;
                    $headings = array($title, '');
                    $worksheet->write_row('E' . $rownumber, $headings, $RightNumberTotalBold);

                    $title = number_format($amount, 2, '.', '');
                    $headings = array($title, '');
                    $worksheet->write_row('F' . $rownumber, $headings, $RightNumberTotalBold);

                    $title = 100;
                    $headings = array($title, '');
                    $worksheet->write_row('G' . $rownumber, $headings, $RightNumberTotalBold);
                } ?>
                <?php if ($radStores == 'storegroup' || $radDate == 'daterange') { ?>
                    <hr>
                    <h4 style="color:#757575" class="text-center">Summary</h4>
                    <table class="table table-condensed table-striped">
                        <tr>
                            <td height="23" bgcolor="#007BC4" class="NormalText style1">
                                <div align="left"><strong>Supplier Name</strong></div>
                            </td>
                            <td height="23" bgcolor="#007BC4" class="NormalText style1" align="center"><strong>Invoice
                                    Id </strong></td>
                            <td height="23" bgcolor="#007BC4" class="NormalText style1">
                                <div align="center"><strong>Item Id</strong></div>
                            </td>
                            <td height="23" bgcolor="#007BC4" class="NormalText style1">
                                <div align="right"><strong>Item Name</strong></div>
                            </td>
                            <td height="23" bgcolor="#007BC4" class="NormalText style1">
                                <div align="right"><strong>Quantity</strong></div>
                            </td>
                            <td height="23" bgcolor="#007BC4" class="NormalText style1">
                                <div align="right"><strong>Unit Price</strong></div>
                            </td>
                            <td height="23" bgcolor="#007BC4" class="NormalText style1">
                                <div align="right"><strong>Price</strong></div>
                            </td>
                        </tr>

                        <?php

                        // ************************************************
                        // Excel
                        // Write Table Headers for GROUP
                        $title = "Item ID";
                        $headings = array($title, '');
                        $worksheet->write_row('C10', $headings, $heading2);

                        $title = "Item Name";
                        $headings = array($title, '');
                        $worksheet->write_row('E10', $headings, $RightNumberTotalBold);

                        $title = "Quantity";
                        $headings = array($title, '');
                        $worksheet->write_row('F10', $headings, $RightNumberTotalBold);

                        $title = "Unit Price";
                        $headings = array($title, '');
                        $worksheet->write_row('G10', $headings, $RightNumberTotalBold);

                        $title = "Price";
                        $headings = array($title, '');
                        $worksheet->write_row('H10', $headings, $RightNumberTotalBold);

                        $title = "Supplier";
                        $headings = array($title, '');
                        $worksheet->write_row('I10', $headings, $RightNumberTotalBold);


                        $rownumber = 11;

                        $qty = 0;
                        $amount = 0;

                        $result = GetInstockInvoices($sumid); //GetCompsSummary($sumid);
                        while ($row = mysql_fetch_array($result)) {
                            $qty = $qty + intval($row["Quantity"]);
                            $amount = $amount + floatval($row["Price"]);
                            ?>
                            <tr>
                                <td width="15%" class="NormalText">
                                    <div align="left"><?php echo $row["SupplierName"]; ?></div>
                                </td>
                                <td width="15%" class="NormalText" align="center"><?php echo $row["InvoiceId"]; ?></td>
                                <td width="15%" class="NormalText" align="center"><?php echo $row["ItemId"]; ?></td>
                                <td width="15%" class="NormalText">
                                    <div align="right"><?php echo $row["Itemname"]; ?></div>
                                </td>
                                <td width="15%" class="NormalText">
                                    <div align="right"><?php echo $row["Quantity"]; ?></div>
                                </td>
                                <td width="15%" class="NormalText">
                                    <div align="right"><?php echo $row["Unitprice"]; ?></div>
                                </td>
                                <td width="15%" class="NormalText">
                                    <div align="right"><?php echo $row["Price"]; ?></div>
                                </td>
                            </tr>

                            <?php
                            // ************************************************
                            // Excel
                            $title = $row["ItemID"];
                            $headings = array($title, '');
                            $worksheet->write_row('C' . $rownumber, $headings, $NormalLeftAlign);

                            $title = $row["ItemName"];
                            $headings = array($title, '');
                            $worksheet->write_row('E' . $rownumber, $headings, $NormalRightAlign);

                            $title = $row["Quantity"];
                            $headings = array($title, '');
                            $worksheet->write_row('F' . $rownumber, $headings, $num1_format);

                            $title = $row["Unitprice"];
                            $headings = array($title, '');
                            $worksheet->write_row('G' . $rownumber, $headings, $num1_format);

                            $title = $row["Price"];
                            $headings = array($title, '');
                            $worksheet->write_row('H' . $rownumber, $headings, $num1_format);

                            $title = $row["Supplier"];
                            $headings = array($title, '');
                            $worksheet->write_row('I' . $rownumber, $headings, $num1_format);

                            $rownumber++;
                        } ?>
                        <tr>
                            <td bgcolor="#F2F2F2" class="NormalText bold">
                                <div align="right"><strong></strong></div>
                            </td>
                            <td bgcolor="#F2F2F2" colspan="3" class="NormalText text-right bold"><strong>Totals</strong></td>
                            <td bgcolor="#F2F2F2" class="NormalText bold">
                                <div align="right"><strong><?php echo $qty; ?></strong></div>
                            </td>
                            <td bgcolor="#F2F2F2" class="NormalText">
                                <div align="right"><strong></strong></div>
                            </td>
                            <td bgcolor="#F2F2F2" class="NormalText bold">
                                <div align="right"><strong><?php printf("%.2f", $amount); ?></strong></div>
                            </td>
                        </tr>
                    </table>
                    <?php
                    // ********************************
                    // Excel
                    // *
                    // Write the totals
                    $title = "Totals";
                    $headings = array($title, '');
                    $worksheet->write_row('E' . $rownumber, $headings, $RightNumberTotalBold);

                    $title = $qty;
                    $headings = array($title, '');
                    $worksheet->write_row('F' . $rownumber, $headings, $RightNumberTotalBold);

                    $title = number_format($amount, 2, '.', '');
                    $headings = array($title, '');
                    $worksheet->write_row('G' . $rownumber, $headings, $RightNumberTotalBold);
                }

                // ************************************************************
                // Excel Export - Close up document
                // *
                // Write Footer
                $rownumber = $rownumber + 3; // Add some whitespace
                $worksheet->insert_bitmap('a' . $rownumber, 'classes/excelexport/report_footer.bmp', 16, 8); // Write Footer
                $workbook->close(); // Close the workbook
                // ************************************************************
                ?>
            </div>
            <div class="col-sm-1 col-sm-offset-10 text-center">
                <a href="classes/excelexport/excelexport.php?fname=<?php echo $fname; ?>" target="_blank">
                    <i class="fas fa-file-excel fa-3x"></i>
                </a>
            </div>
            <div class="col-sm-1">
                <span class="glyphicon glyphicon-print"
                      onMouseUp="NewWindow('pages/report_comps_print.php?<?php echo "radDate=".$radDate."&dateday=".$_SESSION["dateday"]."&datemonth=".$_SESSION["datemonth"]."&dateyear=".$_SESSION["dateyear"]."&store=".str_replace("'", "^", $_SESSION["store"])."&a=".$a."&datefromday=".$_SESSION["datefromday"]."&datefrommonth=".$_SESSION["datefrommonth"]."&datefromyear=".$_SESSION["datefromyear"]."&datetoday=".$_SESSION["datetoday"]."&datetomonth=".$_SESSION["datetomonth"]."&grpid=".$grpid."&radStores=".$radStores."&datetoyear=".$_SESSION["datetoyear"]; ?>','report_comps','650','500','yes');return false;"></span></a>
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
