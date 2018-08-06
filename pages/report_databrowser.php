<link href="../style.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.10/css/all.css"
      integrity="sha384-+d0P83n9kaQMCwj8F4RJB66tzIwOKmrdb46+porD/OvrJ+37WqIM7UoBtwHO6Nlg" crossorigin="anonymous">
<script type="text/javascript" src="heading-styles.css"></script>
<style type="text/css">
<!--
.style1 {color: #FFFFFF}
.style2 {color: #FFFFFF; font-weight: bold; }
-->

.glyphicon {
    text-decoration: none;
    font-size: 25px;
    color: #007CC4;
}
.glyphicon:not(.nodata):hover {
    color: #00a1ff;
    -webkit-animation: swing 1s ease;
    animation: swing 0.7s ease;
    -webkit-animation-iteration-count: 1;
    animation-iteration-count: 1;
}
.glyphicon.nodata {
    color: lightgray;
}

</style>
<script>
    $(function() {
        var yesterday = new Date();
        yesterday.setDate(yesterday.getDate() - 1);
        var dd = yesterday.getDate();
        var mm = yesterday.getMonth()+1; //January is 0!

        var yyyy = yesterday.getFullYear();
        if(dd<10){
            dd='0'+dd;
        }
        if(mm<10){
            mm='0'+mm;
        }
        yesterday = mm+'/'+dd+'/'+yyyy;

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
        }, function(start, end, label) {
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

<div class="col-sm-12">
    <div class='row form-row'>
        <div class='col-sm-8 col-sm-offset-2 formdiv'>
            <div class="row">
                <div class="col-sm-12">
                    <h3 style="text-transform: uppercase;" class="text-left">
                        Data Browser Report
                    </h3>
                </div>
            </div>
            <form id="frmparameters" name="frmparameters" method="post" action="index.php?p=report_databrowser&a=s" onSubmit="return CheckDateRange(this);">
                <input name="radStores" type="radio"  onclick="Javascript: SetStoreFocus();" value="store" checked="checked" <?php if($radStores == "store") {echo "checked='checked'";} ?> hidden/>
                <div class="row">
                    <div class="col-sm-4"><label for="cmbstore">Select a Store</label></div>
                    <div class="col-sm-8"><select class="form-control col-sm-8" name="cmbstore" id="cmbstore">
                            <?php
                            $result = GetStoresThatUserCanAccess($_SESSION["usrid"]);
                            while($row = mysql_fetch_array($result)) {
                                $output = "<option value='".$row["strid"]."'";
                                if($_SESSION["cmbstore"] == $row["strid"]) {
                                    $output =  $output." selected ";
                                }
                                $output = $output.">".$row["strname"]."</option>";
                                echo $output;
                            }
                            ?>
                        </select></div>
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
                <input name="Submit" type="submit" class="btn btn-default" value="Submit" style="color: white; background-color: #007CC4"/>
            </form>
        </div>
    </div>
</div>


<div class="col-sm-8 col-sm-offset-2">
    <div class="row">
        <?php
        if ($a == 's') {
            $dataavailable = true; // THIS IS TO ALWAYS MAKE IT SHOW!
            if ($dataavailable == true){
                ?>
                <div class="col-sm-12">
                    <h3 style="text:#007CC4" class="text-center">
                        <?php echo GetStoreName($_SESSION["store"])?>
                    </h3>
                    <h5 class="text-center">for date</h5>
                    <h5 style="color:#757575" class="text-center">
                        <?php
                        if($radDate == "date") {
                            echo $_SESSION["date"];
                        }
                        if($radDate == "daterange") {
                            echo $_SESSION["daterange"];
                        }
                        ?>
                    </h5>
                </div>

                <?php
                // ==============================================================================
                // REPORT OUTPUT STARTS HERE ====================================================
                // ==============================================================================

                if($radDate == "date") {
                    $sumIDResult = GetSumIDForDataBrowser("'".$_SESSION["dateyear"]."/".$_SESSION["datemonth"]."/".$_SESSION["dateday"]."'", $_SESSION["store"]);
                }
                if($radDate == "daterange") {
                    $sumIDResult = GetSumIDForDataBrowser($_SESSION["daterangestring"], $_SESSION["store"]);
                }
                $row = mysql_fetch_array($result);

                // TODO : GET SUMID TO PROPERLY LIST ALL SUMIDs
                if(mysql_num_rows($sumIDResult) > 0) {
                    $sumidrow = mysql_fetch_array($sumIDResult);
                    $sumid = "'".$sumidrow["sumid"]."'";
                }
                while($sumidrow = mysql_fetch_array($sumIDResult)) {
                    $sumid = $sumid.",'".$sumidrow["sumid"]."'";
                }


                ?>

                <div class="col-sm-12">
                    <table class="table table-striped .table-condensed .table-bordered" style="margin-bottom: 0px;">
                        <tr>
                            <td width="11.1%" class="SmallText text-center"><strong>Date</strong></td>
                            <td width="11.1%" class="SmallText text-center"><strong>Sales<br/>Summary</strong></td>
                            <td width="11.1%" class="SmallText text-center"><strong>Total<br />Summary</strong></td>
                            <td width="11.1%" class="SmallText text-center"><strong>Comps</strong></td>
                            <td width="11.1%" class="SmallText text-center"><strong>Product Mix </strong></td>
                            <td width="11.1%" class="SmallText text-center"><strong>Rev Center Sales</strong></td>
                            <td width="11.1%" class="SmallText text-center"><strong>Hourly Sales &amp; Labour</strong></td>
                            <td width="11.1%" class="SmallText text-center"><strong>Voids</strong></td>
                            <td width="11.1%" class="SmallText text-center"><strong>Payments</strong></td>
                        </tr>

                        <?php
                        if ($radDate == "date") {
                            $daysdiff = 0;
                            $displaydate = $_SESSION["dateyear"] . "/" . $_SESSION["datemonth"] . "/" . $_SESSION["dateday"];
                        }

                        for ($i = 0; $i <= $daysdiff; $i++) {
                            if ($radDate != "date") {
                                $displaydate = date("Y/m/d", mktime(0, 0, 0, $_SESSION["datefrommonth"], $_SESSION["datefromday"] + $i, $_SESSION["datefromyear"]));
                            }

                            $reportdate = explode("/", $displaydate);
                            $reportyear = $reportdate[0];
                            $reportmonth = $reportdate[1];
                            $reportday = $reportdate[2];
                            $formatDisplayDate = $reportmonth . '/' . $reportday . '/' . $reportyear;
                            //See if data available
                            $storeclosed = '';
                            $reportresult = GetSummaryData($displaydate, "'" . $_REQUEST["cmbstore"] . "'");
                            $reportrow = mysql_fetch_array($reportresult);
                            if (mysql_num_rows($reportresult) > 0) { // there is a record
                                if ($reportrow["sumgrosssales"] != '0.00') {   //All Data Available
                                    $showreport = 'true';
                                    $storeclosed = 'false';
                                } else { // Store Closed
                                    $showreport = 'false';
                                    $storeclosed = 'true';
                                }
                            } else { // No record at all found
                                $showreport = 'false';
                                $storeclosed = 'false';
                            }

                            ?>
                            <tr>
                                <td class="SmallText">
                                    <strong><?php echo $formatDisplayDate; ?></strong></td>
                                <?php
                                if ($storeclosed == 'false') {
                                    ?>
                                    <td align="center" valign="middle" class="SmallText">
                                        <?php if ($showreport == 'true') {
                                            echo "<a href='index.php?p=report_salessummary&radDate=date&cmbstore=".$_REQUEST["cmbstore"]."&dateday=" . $reportday . "&datemonth=" . $reportmonth . "&dateyear=" . $reportyear . "&a=s&radStores=store'><span class='glyphicon glyphicon-search' aria-hidden='true'></span></a>";
                                        } else {
                                            echo "<span class='glyphicon glyphicon-search nodata' aria-hidden='true'></span>";
                                        } ?>
                                    </td>
                                    <td align="center" valign="middle" class="SmallText">
                                        <?php if ($showreport == 'true') {
                                            echo "<a href='index.php?p=report_totalsummary&radDate=date&cmbstore=" . $_REQUEST["cmbstore"] . "&dateday=" . $reportday . "&datemonth=" . $reportmonth . "&dateyear=" . $reportyear . "&a=s&radStores=store'><span class='glyphicon glyphicon-search' aria-hidden='true'></span></a>";
                                        } else {
                                            echo "<span class='glyphicon glyphicon-search nodata' aria-hidden='true'></span>";
                                        } ?>
                                    </td>
                                    <td align="center" valign="middle" class="SmallText">
                                        <?php if ($showreport == 'true') {
                                            echo "<a href='index.php?p=report_comps&radDate=date&cmbstore=" . $_REQUEST["cmbstore"] . "&dateday=" . $reportday . "&datemonth=" . $reportmonth . "&dateyear=" . $reportyear . "&a=s&radStores=store'><span class='glyphicon glyphicon-search' aria-hidden='true'></span></a>";
                                        } else {
                                            echo "<span class='glyphicon glyphicon-search nodata' aria-hidden='true'></span>";
                                        } ?></td>
                                    <td align="center" valign="middle" class="SmallText">
                                        <?php if ($showreport == 'true') {
                                            echo "<a href='index.php?p=report_productmix&radDate=date&cmbstore=" . $_REQUEST["cmbstore"] . "&dateday=" . $reportday . "&datemonth=" . $reportmonth . "&dateyear=" . $reportyear . "&a=s&radStores=store'><span class='glyphicon glyphicon-search' aria-hidden='true'></span></a>";
                                        } else {
                                            echo "<span class='glyphicon glyphicon-search nodata' aria-hidden='true'></span>";
                                        } ?></td>
                                    <td align="center" valign="middle" class="SmallText">
                                        <?php if ($showreport == 'true') {
                                            echo "<a href='index.php?p=report_revenuecentersales&radDate=date&cmbstore=" . $_REQUEST["cmbstore"] . "&dateday=" . $reportday . "&datemonth=" . $reportmonth . "&dateyear=" . $reportyear . "&a=s&radStores=store'><span class='glyphicon glyphicon-search' aria-hidden='true'></span></a>";
                                        } else {
                                            echo "<span class='glyphicon glyphicon-search nodata' aria-hidden='true'></span>";
                                        } ?></td>
                                    <td align="center" valign="middle" class="SmallText">
                                        <?php if ($showreport == 'true') {
                                            echo "<a href='index.php?p=report_hourlysalesandlabour&radDate=date&cmbstore=" . $_REQUEST["cmbstore"] . "&dateday=" . $reportday . "&datemonth=" . $reportmonth . "&dateyear=" . $reportyear . "&a=s&radStores=store'><span class='glyphicon glyphicon-search' aria-hidden='true'></span></a>";
                                        } else {
                                            echo "<span class='glyphicon glyphicon-search nodata' aria-hidden='true'></span>";
                                        } ?></td>
                                    <td align="center" valign="middle" class="SmallText">
                                        <?php if ($showreport == 'true') {
                                            echo "<a href='index.php?p=report_voids&radDate=date&cmbstore=" . $_REQUEST["cmbstore"] . "&dateday=" . $reportday . "&datemonth=" . $reportmonth . "&dateyear=" . $reportyear . "&a=s&radStores=store'><span class='glyphicon glyphicon-search' aria-hidden='true'></span></a>";
                                        } else {
                                            echo "<span class='glyphicon glyphicon-search nodata' aria-hidden='true'></span>";
                                        } ?></td>
                                    <td align="center" valign="middle" class="SmallText">
                                        <?php if ($showreport == 'true') {
                                            echo "<a href='index.php?p=report_payments&radDate=date&cmbstore=" . $_REQUEST["cmbstore"] . "&dateday=" . $reportday . "&datemonth=" . $reportmonth . "&dateyear=" . $reportyear . "&a=s&radStores=store'><span class='glyphicon glyphicon-search' aria-hidden='true'></span></a>";
                                        } else {
                                            echo "<span class='glyphicon glyphicon-search nodata' aria-hidden='true'></span>";
                                        } ?></td>
                                    <?php
                                }
                                if ($storeclosed == 'true') {
                                    echo "<td colspan='8' class='NormaBoldRed' align='center' valign='middle'><div align='center'><strong>Store Closed or Zero Sales</strong></div></td>";
                                }
                                ?>
                            </tr>
                        <?php } ?>
                    </table>
                    <hr style="margin-top: 0px; margin-bottom: 0px;">
                    <br/>
                    <span class="NormalText"><strong>Note :</strong> If a report shows that no results were returned, then the restuarant did not perform a action in that category,
                for example, no comps given or voids captured. </span><br/>
                    <br/>
                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                            <td width="6%"><span class='glyphicon glyphicon-search' aria-hidden='true'></span></td>
                            <td width="94%" class="SmallText"><strong>Data Available </strong></td>
                        </tr>
                        <tr>
                            <td><span class='glyphicon glyphicon-search nodata' aria-hidden='true'></span></td>
                            <td class="SmallText"><strong>No Data Available </strong></td>
                        </tr>
                    </table>
                </div>
            <?php }
        } ?>
    </div>
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
if($radDate == Null) {
       echo "SetSpecificDateFocus(); document.frmparameters.radDate[0].checked = true;";
}

if($radStores == "store") {
    echo "SetStoreFocus();";
}

if($radStores == Null) {
       echo "SetStoreFocus(); document.frmparameters.radStores.checked = true;";
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
