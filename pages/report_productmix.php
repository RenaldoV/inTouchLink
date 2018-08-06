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
                        Product Mix
                    </h3>
                </div>
            </div>
            <form id="frmparameters" name="frmparameters" method="post" action="index.php?p=report_productmix&a=s"
                  onSubmit="if(CheckDateRange(this) && CheckTimeRanges()) {return true;} else {return false;}">
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
                    <div class="col-sm-4"><label for="time">Time Range</label></div>
                    <div class="col-sm-3" style="padding-right: 0px;">
                        <select name="timefrom" class="form-control" id="timefrom">
                            <option value="0000"<?php if ($_SESSION["timefrom"] == '0000') {
                                echo "selected='selected'";
                            } ?>>00:00
                            </option>
                            <option value="0030"<?php if ($_SESSION["timefrom"] == '0030') {
                                echo "selected='selected'";
                            } ?>>00:30
                            </option>
                            <option value="0100"<?php if ($_SESSION["timefrom"] == '0100') {
                                echo "selected='selected'";
                            } ?>>01:00
                            </option>
                            <option value="0130"<?php if ($_SESSION["timefrom"] == '0130') {
                                echo "selected='selected'";
                            } ?>>01:30
                            </option>
                            <option value="0200"<?php if ($_SESSION["timefrom"] == '0200') {
                                echo "selected='selected'";
                            } ?>>02:00
                            </option>
                            <option value="0230"<?php if ($_SESSION["timefrom"] == '0230') {
                                echo "selected='selected'";
                            } ?>>02:30
                            </option>
                            <option value="0300"<?php if ($_SESSION["timefrom"] == '0300') {
                                echo "selected='selected'";
                            } ?>>03:00
                            </option>
                            <option value="0330"<?php if ($_SESSION["timefrom"] == '0330') {
                                echo "selected='selected'";
                            } ?>>03:30
                            </option>
                            <option value="0400"<?php if ($_SESSION["timefrom"] == '0400') {
                                echo "selected='selected'";
                            } ?>>04:00
                            </option>
                            <option value="0430"<?php if ($_SESSION["timefrom"] == '0430') {
                                echo "selected='selected'";
                            } ?>>04:30
                            </option>
                            <option value="0500"<?php if ($_SESSION["timefrom"] == '0500') {
                                echo "selected='selected'";
                            } ?>>05:00
                            </option>
                            <option value="0530"<?php if ($_SESSION["timefrom"] == '0530') {
                                echo "selected='selected'";
                            } ?>>05:30
                            </option>
                            <option value="0600"<?php if ($_SESSION["timefrom"] == '0600') {
                                echo "selected='selected'";
                            } ?>>06:00
                            </option>
                            <option value="0630"<?php if ($_SESSION["timefrom"] == '0630') {
                                echo "selected='selected'";
                            } ?>>06:30
                            </option>
                            <option value="0700"<?php if ($_SESSION["timefrom"] == '0700') {
                                echo "selected='selected'";
                            } ?>>07:00
                            </option>
                            <option value="0730"<?php if ($_SESSION["timefrom"] == '0730') {
                                echo "selected='selected'";
                            } ?>>07:30
                            </option>
                            <option value="0800"<?php if ($_SESSION["timefrom"] == '0800') {
                                echo "selected='selected'";
                            } ?>>08:00
                            </option>
                            <option value="0830"<?php if ($_SESSION["timefrom"] == '0830') {
                                echo "selected='selected'";
                            } ?>>08:30
                            </option>
                            <option value="0900"<?php if ($_SESSION["timefrom"] == '0900') {
                                echo "selected='selected'";
                            } ?>>09:00
                            </option>
                            <option value="0930"<?php if ($_SESSION["timefrom"] == '0930') {
                                echo "selected='selected'";
                            } ?>>09:30
                            </option>
                            <option value="1000"<?php if ($_SESSION["timefrom"] == '1000') {
                                echo "selected='selected'";
                            } ?>>10:00
                            </option>
                            <option value="1030"<?php if ($_SESSION["timefrom"] == '1030') {
                                echo "selected='selected'";
                            } ?>>10:30
                            </option>
                            <option value="1100"<?php if ($_SESSION["timefrom"] == '1100') {
                                echo "selected='selected'";
                            } ?>>11:00
                            </option>
                            <option value="1130"<?php if ($_SESSION["timefrom"] == '1130') {
                                echo "selected='selected'";
                            } ?>>11:30
                            </option>
                            <option value="1200"<?php if ($_SESSION["timefrom"] == '1200') {
                                echo "selected='selected'";
                            } ?>>12:00
                            </option>
                            <option value="1230"<?php if ($_SESSION["timefrom"] == '1230') {
                                echo "selected='selected'";
                            } ?>>12:30
                            </option>
                            <option value="1300"<?php if ($_SESSION["timefrom"] == '1300') {
                                echo "selected='selected'";
                            } ?>>13:00
                            </option>
                            <option value="1330"<?php if ($_SESSION["timefrom"] == '1330') {
                                echo "selected='selected'";
                            } ?>>13:30
                            </option>
                            <option value="1400"<?php if ($_SESSION["timefrom"] == '1400') {
                                echo "selected='selected'";
                            } ?>>14:00
                            </option>
                            <option value="1430"<?php if ($_SESSION["timefrom"] == '1430') {
                                echo "selected='selected'";
                            } ?>>14:30
                            </option>
                            <option value="1500"<?php if ($_SESSION["timefrom"] == '1500') {
                                echo "selected='selected'";
                            } ?>>15:00
                            </option>
                            <option value="1530"<?php if ($_SESSION["timefrom"] == '1530') {
                                echo "selected='selected'";
                            } ?>>15:30
                            </option>
                            <option value="1600"<?php if ($_SESSION["timefrom"] == '1600') {
                                echo "selected='selected'";
                            } ?>>16:00
                            </option>
                            <option value="1630"<?php if ($_SESSION["timefrom"] == '1630') {
                                echo "selected='selected'";
                            } ?>>16:30
                            </option>
                            <option value="1700"<?php if ($_SESSION["timefrom"] == '1700') {
                                echo "selected='selected'";
                            } ?>>17:00
                            </option>
                            <option value="1730"<?php if ($_SESSION["timefrom"] == '1730') {
                                echo "selected='selected'";
                            } ?>>17:30
                            </option>
                            <option value="1800"<?php if ($_SESSION["timefrom"] == '1800') {
                                echo "selected='selected'";
                            } ?>>18:00
                            </option>
                            <option value="1830"<?php if ($_SESSION["timefrom"] == '1830') {
                                echo "selected='selected'";
                            } ?>>18:30
                            </option>
                            <option value="1900"<?php if ($_SESSION["timefrom"] == '1900') {
                                echo "selected='selected'";
                            } ?>>19:00
                            </option>
                            <option value="1930"<?php if ($_SESSION["timefrom"] == '1930') {
                                echo "selected='selected'";
                            } ?>>19:30
                            </option>
                            <option value="2000"<?php if ($_SESSION["timefrom"] == '2000') {
                                echo "selected='selected'";
                            } ?>>20:00
                            </option>
                            <option value="2030"<?php if ($_SESSION["timefrom"] == '2030') {
                                echo "selected='selected'";
                            } ?>>20:30
                            </option>
                            <option value="2100"<?php if ($_SESSION["timefrom"] == '2100') {
                                echo "selected='selected'";
                            } ?>>21:00
                            </option>
                            <option value="2130"<?php if ($_SESSION["timefrom"] == '2130') {
                                echo "selected='selected'";
                            } ?>>21:30
                            </option>
                            <option value="2200"<?php if ($_SESSION["timefrom"] == '2200') {
                                echo "selected='selected'";
                            } ?>>22:00
                            </option>
                            <option value="2230"<?php if ($_SESSION["timefrom"] == '2230') {
                                echo "selected='selected'";
                            } ?>>22:30
                            </option>
                            <option value="2300"<?php if ($_SESSION["timefrom"] == '2300') {
                                echo "selected='selected'";
                            } ?>>23:00
                            </option>
                            <option value="2330"<?php if ($_SESSION["timefrom"] == '2330') {
                                echo "selected='selected'";
                            } ?>>23:30
                            </option>
                            <option value="2359"<?php if ($_SESSION["timefrom"] == '2359') {
                                echo "selected='selected'";
                            } ?>>23:59
                            </option>
                        </select>
                    </div>
                    <div class="col-sm-2 text-center" style="padding-right: 0px;">
                        <span>to</span>
                    </div>
                    <div class="col-sm-3" style="padding-left: 0px;">
                        <select name="timeto" class="form-control" id="timeto">
                            <option value="0000"<?php if ($_SESSION["timeto"] == '0000') {
                                echo "selected='selected'";
                            } ?>>00:00
                            </option>
                            <option value="0030"<?php if ($_SESSION["timeto"] == '0030') {
                                echo "selected='selected'";
                            } ?>>00:30
                            </option>
                            <option value="0100"<?php if ($_SESSION["timeto"] == '0100') {
                                echo "selected='selected'";
                            } ?>>01:00
                            </option>
                            <option value="0130"<?php if ($_SESSION["timeto"] == '0130') {
                                echo "selected='selected'";
                            } ?>>01:30
                            </option>
                            <option value="0200"<?php if ($_SESSION["timeto"] == '0200') {
                                echo "selected='selected'";
                            } ?>>02:00
                            </option>
                            <option value="0230"<?php if ($_SESSION["timeto"] == '0230') {
                                echo "selected='selected'";
                            } ?>>02:30
                            </option>
                            <option value="0300"<?php if ($_SESSION["timeto"] == '0300') {
                                echo "selected='selected'";
                            } ?>>03:00
                            </option>
                            <option value="0330"<?php if ($_SESSION["timeto"] == '0330') {
                                echo "selected='selected'";
                            } ?>>03:30
                            </option>
                            <option value="0400"<?php if ($_SESSION["timeto"] == '0400') {
                                echo "selected='selected'";
                            } ?>>04:00
                            </option>
                            <option value="0430"<?php if ($_SESSION["timeto"] == '0430') {
                                echo "selected='selected'";
                            } ?>>04:30
                            </option>
                            <option value="0500"<?php if ($_SESSION["timeto"] == '0500') {
                                echo "selected='selected'";
                            } ?>>05:00
                            </option>
                            <option value="0530"<?php if ($_SESSION["timeto"] == '0530') {
                                echo "selected='selected'";
                            } ?>>05:30
                            </option>
                            <option value="0600"<?php if ($_SESSION["timeto"] == '0600') {
                                echo "selected='selected'";
                            } ?>>06:00
                            </option>
                            <option value="0630"<?php if ($_SESSION["timeto"] == '0630') {
                                echo "selected='selected'";
                            } ?>>06:30
                            </option>
                            <option value="0700"<?php if ($_SESSION["timeto"] == '0700') {
                                echo "selected='selected'";
                            } ?>>07:00
                            </option>
                            <option value="0730"<?php if ($_SESSION["timeto"] == '0730') {
                                echo "selected='selected'";
                            } ?>>07:30
                            </option>
                            <option value="0800"<?php if ($_SESSION["timeto"] == '0800') {
                                echo "selected='selected'";
                            } ?>>08:00
                            </option>
                            <option value="0830"<?php if ($_SESSION["timeto"] == '0830') {
                                echo "selected='selected'";
                            } ?>>08:30
                            </option>
                            <option value="0900"<?php if ($_SESSION["timeto"] == '0900') {
                                echo "selected='selected'";
                            } ?>>09:00
                            </option>
                            <option value="0930"<?php if ($_SESSION["timeto"] == '0930') {
                                echo "selected='selected'";
                            } ?>>09:30
                            </option>
                            <option value="1000"<?php if ($_SESSION["timeto"] == '1000') {
                                echo "selected='selected'";
                            } ?>>10:00
                            </option>
                            <option value="1030"<?php if ($_SESSION["timeto"] == '1030') {
                                echo "selected='selected'";
                            } ?>>10:30
                            </option>
                            <option value="1100"<?php if ($_SESSION["timeto"] == '1100') {
                                echo "selected='selected'";
                            } ?>>11:00
                            </option>
                            <option value="1130"<?php if ($_SESSION["timeto"] == '1130') {
                                echo "selected='selected'";
                            } ?>>11:30
                            </option>
                            <option value="1200"<?php if ($_SESSION["timeto"] == '1200') {
                                echo "selected='selected'";
                            } ?>>12:00
                            </option>
                            <option value="1230"<?php if ($_SESSION["timeto"] == '1230') {
                                echo "selected='selected'";
                            } ?>>12:30
                            </option>
                            <option value="1300"<?php if ($_SESSION["timeto"] == '1300') {
                                echo "selected='selected'";
                            } ?>>13:00
                            </option>
                            <option value="1330"<?php if ($_SESSION["timeto"] == '1330') {
                                echo "selected='selected'";
                            } ?>>13:30
                            </option>
                            <option value="1400"<?php if ($_SESSION["timeto"] == '1400') {
                                echo "selected='selected'";
                            } ?>>14:00
                            </option>
                            <option value="1430"<?php if ($_SESSION["timeto"] == '1430') {
                                echo "selected='selected'";
                            } ?>>14:30
                            </option>
                            <option value="1500"<?php if ($_SESSION["timeto"] == '1500') {
                                echo "selected='selected'";
                            } ?>>15:00
                            </option>
                            <option value="1530"<?php if ($_SESSION["timeto"] == '1530') {
                                echo "selected='selected'";
                            } ?>>15:30
                            </option>
                            <option value="1600"<?php if ($_SESSION["timeto"] == '1600') {
                                echo "selected='selected'";
                            } ?>>16:00
                            </option>
                            <option value="1630"<?php if ($_SESSION["timeto"] == '1630') {
                                echo "selected='selected'";
                            } ?>>16:30
                            </option>
                            <option value="1700"<?php if ($_SESSION["timeto"] == '1700') {
                                echo "selected='selected'";
                            } ?>>17:00
                            </option>
                            <option value="1730"<?php if ($_SESSION["timeto"] == '1730') {
                                echo "selected='selected'";
                            } ?>>17:30
                            </option>
                            <option value="1800"<?php if ($_SESSION["timeto"] == '1800') {
                                echo "selected='selected'";
                            } ?>>18:00
                            </option>
                            <option value="1830"<?php if ($_SESSION["timeto"] == '1830') {
                                echo "selected='selected'";
                            } ?>>18:30
                            </option>
                            <option value="1900"<?php if ($_SESSION["timeto"] == '1900') {
                                echo "selected='selected'";
                            } ?>>19:00
                            </option>
                            <option value="1930"<?php if ($_SESSION["timeto"] == '1930') {
                                echo "selected='selected'";
                            } ?>>19:30
                            </option>
                            <option value="2000"<?php if ($_SESSION["timeto"] == '2000') {
                                echo "selected='selected'";
                            } ?>>20:00
                            </option>
                            <option value="2030"<?php if ($_SESSION["timeto"] == '2030') {
                                echo "selected='selected'";
                            } ?>>20:30
                            </option>
                            <option value="2100"<?php if ($_SESSION["timeto"] == '2100') {
                                echo "selected='selected'";
                            } ?>>21:00
                            </option>
                            <option value="2130"<?php if ($_SESSION["timeto"] == '2130') {
                                echo "selected='selected'";
                            } ?>>21:30
                            </option>
                            <option value="2200"<?php if ($_SESSION["timeto"] == '2200') {
                                echo "selected='selected'";
                            } ?>>22:00
                            </option>
                            <option value="2230"<?php if ($_SESSION["timeto"] == '2230') {
                                echo "selected='selected'";
                            } ?>>22:30
                            </option>
                            <option value="2300"<?php if ($_SESSION["timeto"] == '2300') {
                                echo "selected='selected'";
                            } ?>>23:00
                            </option>
                            <option value="2330"<?php if ($_SESSION["timeto"] == '2330') {
                                echo "selected='selected'";
                            } ?>>23:30
                            </option>
                            <option value="2359"<?php if ($_SESSION["timeto"] == '2359') {
                                echo "selected='selected'";
                            } ?>>23:59
                            </option>
                        </select>
                    </div>
                </div>
                <div class="row" style="margin-top: 5px;">
                    <div class="col-sm-2" style="padding-right: 0px;">
                        <label for="cmbdayofweek">Day of Week</label>
                    </div>
                    <div class="col-sm-4">
                        <select name="cmbdayofweek" class="form-control" id="cmbdayofweek">
                            <option <?php if ($_SESSION["dayofweek"] == "All Days") {
                                echo "selected='selected'";
                            } ?> value="All Days">All Days
                            </option>
                            <option <?php if ($_SESSION["dayofweek"] == "Monday") {
                                echo "selected='selected'";
                            } ?> value="Monday">Monday
                            </option>
                            <option <?php if ($_SESSION["dayofweek"] == "Tuesday") {
                                echo "selected='selected'";
                            } ?> value="Tuesday">Tuesday
                            </option>
                            <option <?php if ($_SESSION["dayofweek"] == "Wednesday") {
                                echo "selected='selected'";
                            } ?> value="Wednesday">Wednesday
                            </option>
                            <option <?php if ($_SESSION["dayofweek"] == "Thursday") {
                                echo "selected='selected'";
                            } ?> value="Thursday">Thursday
                            </option>
                            <option <?php if ($_SESSION["dayofweek"] == "Friday") {
                                echo "selected='selected'";
                            } ?> value="Friday">Friday
                            </option>
                            <option <?php if ($_SESSION["dayofweek"] == "Saturday") {
                                echo "selected='selected'";
                            } ?> value="Saturday">Saturday
                            </option>
                            <option <?php if ($_SESSION["dayofweek"] == "Sunday") {
                                echo "selected='selected'";
                            } ?> value="Sunday">Sunday
                            </option>
                        </select>
                    </div>
                    <div class="col-sm-2" style="padding: 0px;">
                        <label for="orderby">Order By</label>
                    </div>
                    <div class="col-sm-4">
                        <select name="cmborder" class="form-control" id="cmborder">
                            <option value="ibritenplu" <?php if ($_SESSION["usrpref_productmixorder"] == 'ibritenplu') {
                                echo "selected";
                            } ?>>Item PLU
                            </option>
                            <option value="ibritemname" <?php if ($_SESSION["usrpref_productmixorder"] == 'ibritemname') {
                                echo "selected";
                            } ?>>Item Name
                            </option>
                        </select>
                    </div>
                </div>
                <input name="Submit" type="submit" class="btn btn-default" value="Submit"
                       style="color: white; background-color: #007CC4"/>
            </form>
        </div>
    </div>
</div>


<div class="col-sm-8 col-sm-offset-2 col-lg-6 col-lg-offset-3">
    <?php
    // -------------- See if any data is in Summary Table -----------------------
    if ($a == 's') {
        $dataavailable = true;
        if($radDate == 'date' && IsReportAvailableForStore($_SESSION["dateyear"]."/".$_SESSION["datemonth"]."/".$_SESSION["dateday"], $_SESSION["store"]) != 'true') {
            $dataavailable = false;
        }
        if($radDate == 'daterange' && IsReportAvailableForStoreInDateRange($_SESSION["daterangestring"], $_SESSION["store"]) != 'true') {
            $dataavailable = false;
        }
        // ----------- END OF DATA AVAILABILITY CHECK -------------------------------

        if ($dataavailable == true){

            ?>
            <?php
            // REPORT OUTPUT STARTS HERE
            // Excel Export. Initialize

            require_once "classes/excelexport/class.writeexcel_workbook.inc.php";
            require_once "classes/excelexport/class.writeexcel_worksheet.inc.php";
            $fname = tempnam("/tmp", GenerateReferenceNumber().".xls");
            $fname = str_replace("\\","/",$fname);
            $workbook =& new writeexcel_workbook($fname);
            $worksheet =& $workbook->addworksheet('Report');
            // Set Columns widths
            $worksheet->set_column('A:B', 0.10);
            $worksheet->set_column('C:C', 12); // First usuable column
            $worksheet->set_column('D:D', 36.14);
            $worksheet->set_column('E:E', 8.71);
            $worksheet->set_column('F:F', 11.29);
            $worksheet->set_column('G:G', 13.14);

            $worksheet->insert_bitmap('a1', 'classes/excelexport/report_header.bmp', 16, 8); // Write Header
            // Set styles
            $heading  =& $workbook->addformat(array(
                bold    => 1,
                color   => '007CC4',
                size    => 12,
                merge   => 1,
                font    => 'Arial'
            )); // Create new font style
            $heading2  =& $workbook->addformat(array(
                bold    => 1,
                color   => '007CC4',
                size    =>12,
                merge   => 1,
                font    => 'Arial',
                align   => "left"
            )); // Create new font style
            $LeftNormalTotalBold  =& $workbook->addformat(array(
                bold    => 1,
                color   => '007CC4',
                size    => 11,
                merge   => 1,
                font    => 'Arial',
                align   => "left"
            )); // Create new font style
            $RightNumberTotalBold  =& $workbook->addformat(array(
                bold    => 1,
                color   => '007CC4',
                size    => 11,
                merge   => 1,
                font    => 'Arial',
                align   => "right"
            )); // Create new font style
            $NormalLeftAlign  =& $workbook->addformat(array(
                bold    => 0,
                color   => '007CC4',
                size    => 11,
                merge   => 1,
                font    => 'Arial',
                align   => "left"
            )); // Create new font style
            $NormalRightAlign  =& $workbook->addformat(array(
                bold    => 0,
                color   => '007CC4',
                size    => 11,
                merge   => 1,
                font    => 'Arial',
                align   => "right"
            )); // Create new font style

            $color_background_blue   =& $workbook->addformat(array(
                fg_color => 22,
            )); // Create new font style

            // *******************************************************************
            if($radDate == "date") {
                $sumIDResult = GetSumIDforWithDateRange("'".$_SESSION["dateyear"]."/".$_SESSION["datemonth"]."/".$_SESSION["dateday"]."'", $_SESSION["store"]);
            }
            if($radDate == "daterange") {
                $sumIDResult = GetSumIDforWithDateRange($_SESSION["daterangestring"], $_SESSION["store"]);

            }
            if(mysql_num_rows($sumIDResult) > 0) {
                $sumidrow = mysql_fetch_array($sumIDResult);
                $sumid = "'".$sumidrow["sumid"]."'";
            }
            while($sumidrow = mysql_fetch_array($sumIDResult)) {
                $sumid = $sumid.",'".$sumidrow["sumid"]."'";
            }

            // Trim SUMID's if day of week selected
            //echo $_SESSION["dayofweek"];
            if($radDate == "daterange" && $_SESSION["dayofweek"] != "All Days") {
                //echo "here";
                $sumid = ReturnDayOfWeekSUMIDs($sumid);
            }
            //echo "SUMID : ".$sumid;
            //$row = mysql_fetch_array($result);
            ?>

            <h3 style="text:#007CC4" class="text-center">
                <?php
                if($radStores == "store") {
                    echo GetStoreName($_SESSION["store"]);
                    $excelreport_title = GetStoreName($_SESSION["store"]); // EXCEL
                }
                if($radStores == "storegroup") {
                    echo GetStoreGroupName($grpid) . $_SESSION["storegroupscount"];
                    $excelreport_title = GetStoreName($_SESSION["store"]).$_SESSION["storegroupscount"]; // EXCEL
                }

                ?>
            </h3>
            <h5 class="text-center">for date</h5>
            <h5 style="color:#757575" class="text-center">
                <?php
                if($radDate == "date") {
                    echo $_SESSION["datemonth"]."/".$_SESSION["dateday"]."/".$_SESSION["dateyear"];
                    $excelreport_title = $excelreport_title." for date ".$_SESSION["dateday"]."/".$_SESSION["datemonth"]."/".$_SESSION["dateyear"]; // EXCEL
                }
                if($radDate == "daterange") {
                    echo $_SESSION["datefrommonth"]."/".$_SESSION["datefromday"]."/".$_SESSION["datefromyear"]." to ".$_SESSION["datetomonth"]."/".$_SESSION["datetoday"]."/".$_SESSION["datetoyear"];
                    $excelreport_title = $excelreport_title." for date ".$_SESSION["datefromday"]."/".$_SESSION["datefrommonth"]."/".$_SESSION["datefromyear"]." to ".$_SESSION["datetoday"]."/".$_SESSION["datetomonth"]."/".$_SESSION["datetoyear"]; // EXCEL
                }
                ?>
            <?php
            // ************************************
            // EXCEL - Set up report title
            // *
            // Report Title
            $title = "Product Mix Report for ";
            $headings = array($title, '');
            $worksheet->write_row('D6', $headings, $heading);
            // Report Specs
            $$excelreport_title = $excelreport_title;
            if($_SESSION["dayofweek"] != "All Days") {
                echo " (".$_SESSION["dayofweek"]."s only)";
                $excelreport_title = $excelreport_title." (".$_SESSION["dayofweek"]."s only)";
            }
            $headings = array($excelreport_title, '');
            $worksheet->write_row('D7', $headings, $heading);
            $num1_format =& $workbook->addformat(array(num_format => '0.00',size  => 11));  //Basic number format
            $rownumber = 8;
            // ************************************
            ?>
            </h5>
            <?php
            // Check if orderby is set, if not then set it to the item name

            if($_SESSION["orderby"] == null) {
                $_SESSION["orderby"] = "ibritemname";
            }
            $result = GetProductMix($sumid,$_SESSION["orderby"]) ;
            ?>
            <br />
            <?php

            $catname = "";
            $oldcatname = "";
            $itemsoldtotal = 0;
            $itempricesoldtotal = 0;
            $itemamountotal = 0;

            while($row = mysql_fetch_array($result)) {
                if($catname == "") { // FIRST HEADER
                    $catname = ucfirst($row["ibrcategoryname"]);
                    $oldcatname  =  $catname;
                    // SHOW FIRST HEADER .............
                    ?>
                    <table class="table table-striped table-condensed" style="margin-bottom: 0px;">
                        <tr>
                            <td colspan="5" style="background-color: #487CC4; color: white">
                                <?php
                                echo ucfirst($row["ibrcategoryname"]);

                                // EXCEL
                                // ************************************************
                                // Excel
                                // Write Table Headers for GROUP
                                $rownumber++;
                                $rownumber++;
                                $title = ucfirst($row["ibrcategoryname"]);
                                $headings = array($title, '');
                                $worksheet->write_row('C'.$rownumber, $headings, $heading2);

                                $rownumber++;
                                $title = "Item PLU";
                                $headings = array($title, '');
                                $worksheet->write_row('C'.$rownumber, $headings, $LeftNormalTotalBold);

                                $title = "Item Name";
                                $headings = array($title, '');
                                $worksheet->write_row('D'.$rownumber, $headings, $LeftNormalTotalBold);

                                $title = "# Sold";
                                $headings = array($title, '');
                                $worksheet->write_row('E'.$rownumber, $headings, $RightNumberTotalBold);

                                $title = "Price Sold";
                                $headings = array($title, '');
                                $worksheet->write_row('F'.$rownumber, $headings, $RightNumberTotalBold);

                                $title = "Amount";
                                $headings = array($title, '');
                                $worksheet->write_row('G'.$rownumber, $headings, $RightNumberTotalBold);

                                // end excel
                                ?></td>
                        </tr>
                        <tr>
                            <td width="107" height="25" bgcolor="#F2F2F2"><span class="style5">Item PLU </span></td>
                            <td width="463" bgcolor="#F2F2F2"><span class="style5">Item Name </span></td>
                            <td width="98" bgcolor="#F2F2F2"><div align="right" class="style5"># Sold </div></td>
                            <td width="118" bgcolor="#F2F2F2"><div align="right"><span class="style5">Price Sold </span></div></td>
                            <td width="129" bgcolor="#F2F2F2"><div align="right" class="style5">Amount</div></td>
                        </tr>
                    </table>
                    <?php
                }
                // Logic
                // 1. Get first Category Name and display header
                // 2. For each item
                // 3. If Category has changed, show footer and clear category totals and show new header
                // 4. Show item
                $catname = ucfirst($row["ibrcategoryname"]); // SET NEW CATNAME
                if($catname != $oldcatname) { // New Category has arrived
                    ?>
                    <table class="table table-striped table-condensed">
                        <tr>
                            <td width="108" bgcolor="#F2F2F2" class="style6">&nbsp;</td>
                            <td width="463" bgcolor="#F2F2F2"><div align="right" class="style6 bold">Total</div></td>
                            <td width="99" bgcolor="#F2F2F2"><div align="right" class="style6 bold"><strong><?php echo $itemsoldtotal; $finalsoldtotal = $finalsoldtotal + $itemsoldtotal; ?></strong></div></td>
                            <td width="118" bgcolor="#F2F2F2"><div align="right" class="style6 bold"><strong><?php echo number_format($itempricesoldtotal,"2",".",""); ?></strong></div></td>
                            <td width="127" bgcolor="#F2F2F2"><div align="right" class="style6 bold"><strong><?php echo number_format($itemamountotal,"2",".",""); $finalgrandtotal = $finalgrandtotal + $itemamountotal;?></strong></div></td>
                            <?php
                            $rownumber++;
                            $title = "Total";
                            $headings = array($title, '');
                            $worksheet->write_row('D'.$rownumber, $headings, $heading2);

                            $title = $itemsoldtotal;
                            $headings = array($title, '');
                            $worksheet->write_row('E'.$rownumber, $headings, $RightNumberTotalBold);

                            $title = number_format($itempricesoldtotal,"2",".","");
                            $headings = array($title, '');
                            $worksheet->write_row('F'.$rownumber, $headings, $RightNumberTotalBold);

                            $title = $itemamountotal;
                            $headings = array($title, '');
                            $worksheet->write_row('G'.$rownumber, $headings, $RightNumberTotalBold);

                            ?>
                        </tr>
                    </table>

                    <table class="table table-striped table-condensed" style="margin-bottom: 0px;">
                        <tr>
                            <td colspan="5" style="background-color: #487CC4; color: white">
                                <?php
                                echo ucfirst($row["ibrcategoryname"]);
                                // EXCEL
                                // ************************************************
                                // Write Table Headers for GROUP
                                $rownumber++;
                                $rownumber++;
                                $title = ucfirst($row["ibrcategoryname"]);
                                $headings = array($title, '');
                                $worksheet->write_row('C'.$rownumber, $headings, $heading2);

                                $rownumber++;

                                $title = "Item PLU";
                                $headings = array($title, '');
                                $worksheet->write_row('C'.$rownumber, $headings, $LeftNormalTotalBold);

                                $title = "Item Name";
                                $headings = array($title, '');
                                $worksheet->write_row('D'.$rownumber, $headings, $LeftNormalTotalBold);

                                $title = "# Sold";
                                $headings = array($title, '');
                                $worksheet->write_row('E'.$rownumber, $headings, $RightNumberTotalBold);

                                $title = "Price Sold";
                                $headings = array($title, '');
                                $worksheet->write_row('F'.$rownumber, $headings,$RightNumberTotalBold);

                                $title = "Amount";
                                $headings = array($title, '');
                                $worksheet->write_row('G'.$rownumber, $headings, $RightNumberTotalBold);

                                // end excel

                                ?></td>
                        </tr>
                        <tr>
                            <td width="107" height="25" bgcolor="#F2F2F2"><span class="style5">Item PLU </span></td>
                            <td width="463" bgcolor="#F2F2F2"><span class="style5">Item Name </span></td>
                            <td width="98" bgcolor="#F2F2F2""><div align="right" class="style5"># Sold </div></td>
                            <td width="118" bgcolor="#F2F2F2"><div align="right"><span class="style5">Price Sold </span></div></td>
                            <td width="129" bgcolor="#F2F2F2"><div align="right" class="style5">Amount</div></td>
                        </tr>
                    </table>
                    <?php
                    $oldcatname  =  $catname;
                    // Reset Counters
                    $itemsoldtotal = 0;
                    $itempricesoldtotal = 0;
                    $itemamountotal = 0;
                }
                ?>

                <table height="22" border="0" align="center" cellpadding="2" cellspacing="0">

                    <tr>
                        <td width="102" height="22" class="NormalText"><div align="left"><?php echo $row["ibritenplu"];?></div>
                            <div align="left"></div></td>
                        <td width="467" class="NormalText"><div align="left"><?php echo $row["ibritemname"];?></div></td>
                        <td width="99" class="NormalText"><div align="right"><?php echo $row["ibrnumsold"];?></div></td>
                        <td width="119" class="NormalText"><div align="right"><?php echo number_format($row["ibramount"] / $row["ibrnumsold"],"2",".",""); ?></div></td>
                        <td width="128" class="NormalText"><div align="right"><?php echo number_format($row["ibramount"],"2",".",""); ?></div></td>
                        <?php
                        // ************************************************
                        // Excel

                        $rownumber++;

                        $title = $row["ibritenplu"];
                        $headings = array($title, '');
                        $worksheet->write_row('C'.$rownumber, $headings, $NormalLeftAlign);

                        $title = $row["ibritemname"];
                        $headings = array($title, '');
                        $worksheet->write_row('D'.$rownumber,$headings,$NormalLeftAlign);

                        $title = $row["ibrnumsold"];
                        $headings = array($title, '');
                        $worksheet->write_row('E'.$rownumber,$headings,$NormalRightAlign);

                        $title = number_format($row["ibramount"] / $row["ibrnumsold"],"2",".","");
                        $headings = array($title, '');
                        $worksheet->write_row('F'.$rownumber,$headings,$num1_format);

                        $title = $row["ibramount"];
                        $headings = array($title, '');
                        $worksheet->write_row('G'.$rownumber,$headings,$num1_format);

                        ?>
                    </tr>
                </table>

                <?php
// Count Totals

                $itemsoldtotal =  $itemsoldtotal + $row["ibrnumsold"];
                $itempricesoldtotal = $itempricesoldtotal + ($row["ibramount"] / $row["ibrnumsold"]);
                $itemamountotal = $itemamountotal + $row["ibramount"];

            } ?>

            <table class="table table-striped table-condensed">
                <tr>
                    <td width="108" bgcolor="#F2F2F2" class="style6 bold">&nbsp;</td>
                    <td width="463" bgcolor="#F2F2F2"><div align="right" class="style6 bold">Total</div></td>
                    <td width="99" bgcolor="#F2F2F2"><div align="right" class="style6 bold"><strong><?php echo $itemsoldtotal; $finalsoldtotal = $finalsoldtotal + $itemsoldtotal;?></strong></div></td>
                    <td width="118" bgcolor="#F2F2F2"><div align="right" class="style6 bold"><strong><?php echo number_format($itempricesoldtotal,"2",".",""); ?></strong></div></td>
                    <td width="127" bgcolor="#F2F2F2" ><div align="right" class="style6 bold"><strong><?php echo number_format($itemamountotal,"2",".",""); $finalgrandtotal = $finalgrandtotal + $itemamountotal; ?></strong></div></td>
                </tr>
                <?php
                // *** EXCEL ********************************
                $rownumber++;
                $title = "Total";
                $headings = array($title, '');
                $worksheet->write_row('D'.$rownumber, $headings, $heading2);

                $title = $itemsoldtotal;
                $headings = array($title, '');
                $worksheet->write_row('E'.$rownumber, $headings, $RightNumberTotalBold);

                $title = number_format($itempricesoldtotal,"2",".","");
                $headings = array($title, '');
                $worksheet->write_row('F'.$rownumber, $headings, $RightNumberTotalBold);

                $title = $itemamountotal;
                $headings = array($title, '');
                $worksheet->write_row('G'.$rownumber, $headings, $RightNumberTotalBold);
                // ********************
                ?>
            </table>
            <div align="center">
            <?php
            $resulttemp = GetProductMixSummaryTotal($sumid);
            $row = mysql_fetch_array($resulttemp);
            $grandtotal = $row["ibramount"];

            $result = GetProductMixSummary($sumid);
            if(mysql_num_rows($result) > 0) { // only display if there is data

// ************************************************
// Excel
// Write Table Headers for NON-SALES CATEGORIES
// Report Title
                $rownumber++;
                $rownumber++;
                $title = "Summary";
                $headings = array($title, '');
                $worksheet->write_row('D'.$rownumber, $headings, $heading);
                $rownumber++;

                $title = "Name";
                $headings = array($title, '');
                $worksheet->write_row('C'.$rownumber, $headings, $LeftNormalTotalBold);

                $title = "# Sold";
                $headings = array($title, '');
                $worksheet->write_row('E'.$rownumber, $headings, $RightNumberTotalBold);

                $title = "Amount";
                $headings = array($title, '');
                $worksheet->write_row('F'.$rownumber, $headings,$RightNumberTotalBold);

                $title = "% Sold";
                $headings = array($title, '');
                $worksheet->write_row('G'.$rownumber, $headings, $RightNumberTotalBold);

// end excel
                ?>
                <h4 style="color:#757575" class="text-center"> Summary</h4>
                <table width="74%" border="0" align="center" class="table table-condensed">
                    <tr>
                        <td width="43%" bgcolor="#487CC4"><div align="left" class="style1">Name</div></td>
                        <td width="19%" bgcolor="#487CC4"><div align="right" class="style1"># Sold</div></td>
                        <td width="18%" bgcolor="#487CC4"><div align="right" class="style1">Amount</div></td>
                        <td width="20%" bgcolor="#487CC4"><div align="right" class="style1">% Sales</div></td>
                    </tr>
                    <?php
                    $soldtotal = 0;
                    $amounttotal = 0;
                    $salestotal = 0;
                    while($row = mysql_fetch_array($result)) { ?>
                        <tr>
                            <td class="NormalText"><strong><?php echo ucfirst($row["ibrcategoryname"]); ?></strong></td>
                            <td class="NormalText"><div align="right"><?php echo $row["ibrnumsold"]; ?></div></td>
                            <td class="NormalText"><div align="right"><?php echo $row["ibramount"]; ?></div></td>
                            <td class="NormalText"><div align="right">
                                    <?php
                                    echo number_format(($row["ibramount"] / $grandtotal) * 100, 2, '.', '') ; $salestotal = $salestotal + $perctotal;

                                    ?>
                                </div></td>
                        </tr>

                        <?php
                        $soldtotal = $soldtotal + $row["ibrnumsold"];
                        $amounttotal = $amounttotal + $row["ibramount"];

// EXCEL *************************************
                        $rownumber ++;
                        $title = ucfirst($row["ibrcategoryname"]);
                        $headings = array($title, '');
                        $worksheet->write_row('C'.$rownumber, $headings, $NormalLeftAlign);

                        $title = $row["ibrnumsold"];
                        $headings = array($title, '');
                        $worksheet->write_row('E'.$rownumber,$headings,$NormalRightAlign);

                        $title = $row["ibramount"];
                        $headings = array($title, '');
                        $worksheet->write_row('F'.$rownumber,$headings,$num1_format);

                        $title = number_format(($row["ibramount"] / $grandtotal) * 100, 2, '.', '');
                        $headings = array($title, '');
                        $worksheet->write_row('G'.$rownumber,$headings,$num1_format);
// *********************************************

                    }
                    // EXCEL - WRITE TOTALS
                    $rownumber++;
                    $title = "Total";
                    $headings = array($title, '');
                    $worksheet->write_row('C'.$rownumber, $headings, $heading2);

                    $title = $soldtotal;
                    $headings = array($title, '');
                    $worksheet->write_row('E'.$rownumber, $headings, $RightNumberTotalBold);

                    $title = $amounttotal;
                    $headings = array($title, '');
                    $worksheet->write_row('F'.$rownumber, $headings, $RightNumberTotalBold);

                    $title = "100.00";
                    $headings = array($title, '');
                    $worksheet->write_row('G'.$rownumber, $headings, $RightNumberTotalBold);

                    ?>
                    <tr>
                        <td class="NormalText bold"><div align="right"><strong>Total</strong></div></td>
                        <td bgcolor="#F2F2F2" class="NormalText  bold"><div align="right"><strong><?php echo number_format($soldtotal, 2, '.', '');?></strong></div></td>
                        <td bgcolor="#F2F2F2" class="NormalText  bold"><div align="right"><strong><?php echo number_format($amounttotal, 2, '.', '');?></strong></div></td>
                        <td bgcolor="#F2F2F2" class="NormalText  bold"><div align="right"><strong>100.00</strong></div></td>
                    </tr>
                </table>
                <br />
            <?php }

// ************************************************************
// Excel Export - Close up document
// Write Footer
            $rownumber = $rownumber + 3; // Add some whitespace
            $worksheet->insert_bitmap('a'.$rownumber, 'classes/excelexport/report_footer.bmp', 16, 8); // Write Footer
            $workbook->close(); // Close the workbook

// ************************************************************
            ?>
            <br />
            <br />
            <table width="100%" border="0" cellspacing="0" cellpadding="5">
                <tr>
                    <td><div align="center"></div></td>
                </tr>
            </table>
            <div class="col-sm-1 col-sm-offset-10 text-center">
                <a href='classes/excelexport/excelexport.php?fname=<?php echo $fname; ?>' target='_blank'>
                    <i class="fas fa-file-excel fa-3x"></i>
                </a>
            </div>
            <div class="col-sm-1">
                <a href="Javascript:;"><span class="glyphicon glyphicon-print"
                                             onMouseUp="NewWindow('pages/report_productmix_print.php?<?php echo "radDate=".$radDate."&dateday=".$_SESSION["dateday"]."&datemonth=".$_SESSION["datemonth"]."&dateyear=".$_SESSION["dateyear"]."&store=".str_replace("'", "^", $_SESSION["store"])."&a=".$a."&datefromday=".$_SESSION["datefromday"]."&datefrommonth=".$_SESSION["datefrommonth"]."&datefromyear=".$_SESSION["datefromyear"]."&datetoday=".$_SESSION["datetoday"]."&datetomonth=".$_SESSION["datetomonth"]."&grpid=".$grpid."&radStores=".$radStores."&datetoyear=".$_SESSION["datetoyear"]; ?>','report_productmix','650','500','yes');return false;"></span></a>
            </div>
            <hr size="1" />
            <p align="left" class="NormalText">&nbsp;</p>

            <?php

            ?>
            <?php
        } else {?>
            </div>
            <p align="center" class="NormalText"><br />
                <span class="style2">No results were returned for that query.<br />
    Please try different parameters. </span></p></td>
        <?php } }?>
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
if($radStores == "storegroup") {
    echo "SetStoreGroupFocus();";
}
if($radStores == Null) {
       echo "SetStoreFocus(); document.frmparameters.radStores[0].checked = true;";
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
 
function CheckTimeRanges() {

var result = true;

	if(document.frmparameters.timefrom.value >= document.frmparameters.timeto.value) {
		alert('Please ensure the FROM time is earlier than the TO time');
	    result = false;
	}
return result;
} 

</script>
