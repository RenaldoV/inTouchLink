<link href="../style.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<style type="text/css">
<!--
.style1 {color: #FFFFFF}
.style3 {color: #FFFFFF; font-weight: bold; }
-->
    .printIcon {
        margin-top: 20px;
        float: right;
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
            drops: 'up',
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
            drops: 'down',
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

<div class='col-sm-12'>
    <div class="row form-row">
        <div class="col-sm-8 col-sm-offset-2 formdiv">
            <div class="row">
                <div class="col-sm-12">
                    <h3 style="text-transform: uppercase;" class="text-left">
                        Data Exception Report
                    </h3>
                    <p class="text-left">
                        This data exception report shows the list of stores that do not have the latest data. <br />
                        It also shows the last date that data was imported.<br />
                    </p>
                </div>
            </div>
            <form id="frmparameters" name="frmparameters" method="post" action="index.php?p=report_dataexception&amp;a=s">
                <div class="radio">
                    <label>
                        <input name="radStores" type="radio" value="store" <?php if($radStores == "store") {echo "checked='checked'";} ?>  onclick="Javascript: SetAllGroupsFocus();"/>
                        <b>All Groups</b>
                    </label>
                </div>
                <div class="row">
                    <div class="col-sm-4">
                        <div class="radio">
                            <label>
                                <input name="radStores" type="radio" value="storegroup" <?php if($radStores == "storegroup") {echo "checked='checked'";}?>  onclick="Javascript: SetSpecificGroupFocus();"/>
                                <b>Specific Group</b>
                            </label>
                        </div>
                    </div>
                    <div class="col-sm-8">
                        <select name="cmbstoregroup" class="form-control" id="cmbstoregroup">
                            <?php
                            $result = GetStoreGroupsThatUserCanAccess($_SESSION["usrid"]);
                            while($row = mysql_fetch_array($result)) {
                                $output = "<option value='".$row["grpid"]."'";
                                if($_SESSION["cmbstoregroup"] == $row["grpid"]) {
                                    $output =  $output." selected ";
                                }
                                $output = $output.">".$row["grpname"]."</option>";
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
                <div class="row" style="margin-top: 5px;">
                    <div class="col-sm-4">
                        <label for="cmbdataexpetionorder">Order By</label>
                    </div>
                    <div class="col-sm-8">
                        <select name="cmbdataexpetionorder" id="cmbdataexpetionorder" class="form-control">
                            <option <?php if($_SESSION["usrpref_exceptionreportorder"] == "Store Name") {echo "selected='selected'";}?>>Store Name</option>
                            <option <?php if($_SESSION["usrpref_exceptionreportorder"] == "Last available date") {echo "selected='selected'";}?>>Last available date</option>
                        </select>
                    </div>
                </div>
                <input name="Submit" type="submit" class="btn btn-default" value="Submit" style="color: white; background-color: #007CC4"/>
            </form>
        </div>
    </div>
</div>

<?php if ($a == "s") {

    // If All groups selected then get all stores user can access
    if ($radStores == "store") {
        $result = GetStoresThatUserCanAccess($_SESSION["usrid"]);
        $row = mysql_fetch_array($result);
        $storelist = "'" . $row["strid"] . "'"; // Set the first ID.
        while ($row = mysql_fetch_array($result)) { // Add all IDs into the store session as a string
            $storelist = $storelist . ",'" . $row["strid"] . "'";
        }
        $result = GetLatestStoreDataDate($storelist);
    }
    // Get the stores from the specific group chosen
    if ($radStores == "storegroup") {
        $result = GetLatestStoreDataDate($_SESSION["store"]);
    }
    if (mysql_num_rows($result) > 0) { ?>
        <div class="col-sm-12">
            <h3 style="text:#007CC4" class="text-center">
                <span style="margin-right: -35px">
                <?php
                if($radStores == "store") {
                    echo "All Groups";
                }
                if($radStores == "storegroup") {
                    echo GetStoreGroupName($grpid);
                }
                if($radStores == "storegroup") {
                    echo $_SESSION["storegroupscount"];
                }
                ?>
                </span>
                </h3>
            <h5 class="text-center">for date</h5>
            <h5 style="color:#757575" class="text-center">
                <?php
                if ($radDate == "date") {
                    echo $_SESSION["date"];
                }
                if ($radDate == "daterange") {
                    echo $_SESSION["daterange"];
                }
                ?>
            </h5>
        </div>
        <div class="col-sm-8 col-sm-offset-2">
            <table class="table table-striped .table-condensed .table-bordered" style="margin-bottom: 0px;">
                <tr>
                    <td width="14%" class="text-left"><strong>Store</strong></td>
                    <td width="9%" class="text-center"><strong>Last Available Date</strong></td>
                    <td width="9%" class="text-center"><strong>Contact Name</strong></td>
                    <td width="9%" class="text-center"><strong>Contact Tel No</strong></td>
                    <td width="9%" class="text-center"><strong>Store Tel No</strong></td>
                </tr>
                <?php
                while ($row = mysql_fetch_array($result)) {
                    if ($row["latestdate"] < $yesterdayyear . "/" . $yesterdaymonth . "/" . $yesterdayday || $row["latestdate"] == null) {
                        ?>
                        <tr>
                            <td class="text-left">
                                <strong><?php echo $row["strname"]; ?></strong>
                            </td>
                            <td class="text-center">
                                <?php
                                if ($row["latestdate"] != null) {
                                    echo $row["latestdate"];
                                } else {
                                    echo "No Data";
                                }
                                ?>
                            </td>
                            <td class="text-center">
                                <?php if ($row["strcontactemail"] != null) { ?>
                                    <a href="mailto:<?php echo $row["strcontactemail"]; ?>"><?php echo $row["strcontactname"]; ?></a>
                                <?php } else {
                                    echo $row["strcontactname"];
                                } ?>
                            </td>
                            <td class="text-center">
                                <?php echo $row["strcontactnumber"]; ?>
                            </td>
                            <td class="text-center">
                                <div align="left"><?php echo $row["strstorenumber"]; ?>&nbsp;</div>
                            </td>
                        </tr>
                    <?php }
                } ?>
            </table>
            <div class="col-sm-1 col-sm-offset-11 text-center">
                <a href="Javascript:;" class="printIcon"><span class="glyphicon glyphicon-print" onmouseup="NewWindow('pages/report_dataexception_print.php?<?php echo "radDate=".$radDate."&dateday=".$_SESSION["dateday"]."&datemonth=".$_SESSION["datemonth"]."&dateyear=".$_SESSION["dateyear"]."&store=".str_replace("'", "^", $_SESSION["store"])."&a=".$a."&datefromday=".$_SESSION["datefromday"]."&datefrommonth=".$_SESSION["datefrommonth"]."&datefromyear=".$_SESSION["datefromyear"]."&datetoday=".$_SESSION["datetoday"]."&datetomonth=".$_SESSION["datetomonth"]."&grpid=".$grpid."&radStores=".$radStores."&datetoyear=".$_SESSION["datetoyear"]; ?>','report_salessummary','650','500','yes');return false;"></span></a>
            </div>
        </div>
    <?php } else { ?>
        <br/>
        <span class="NormaBoldGreen"><strong>No results were returned for that query.<br/>
    Please try different parameters. </strong></span><br/><?php }
} ?>

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
