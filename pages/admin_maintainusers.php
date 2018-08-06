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
    .style1 {	color: #FFFFFF;
        font-weight: bold;
    }
    .style4 {font-weight: bold}
    .style5 {font-size: 12px; line-height: normal; color: #333333; text-decoration: none; font-family: Verdana, Arial, Helvetica, sans-serif;}
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


<div class="col-sm-12">
    <h1 class="text-center">Admin</h1>
    <h4 class="text-center" style="color:#757575">Maintain Users</h4>
</div>
<div class="col-sm-8 col-sm-offset-2">
    <div class="well well-md">
        <div class="row">
            <div class="col-sm-12">
                <h3 style="text-transform: uppercase;" class="text-center">
                    <?php
                    if($a != "u") { // Show current action
                        echo "Add new user";
                    } else {
                        echo "Update user";
                    }
                    ?>
                </h3>
                <form
                        id="frmadd"
                        name="frmadd"
                        method="post"
                        action="index.php?p=<?php echo $p;?>&a=<?php if($a != "u") {echo "a";} else {echo "us";}?>"
                        onSubmit="return validateform(this);">
                    <div class="row">
                        <div class="col-sm-4">
                            <label for="txtname">User Name</label>
                        </div>
                        <div class="col-sm-8">
                            <input name="txtname" type="text" class="form-control" id="txtname" value="<?php if($a == "u") {
                                $result =  GetUserPrefs($i); // Get User Preferences
                                $row = mysql_fetch_array($result);
                                echo $row["usrfullname"];
                            }
                            ?>" size="30" maxlength="40" />
                            <input name="hi" type="hidden" id="hiddenField" value="<?php if($a == "u") { echo $i;} ?>" />
                        </div>
                    </div>
                    <?php if($_SESSION["usr_adminacess"] == "yes") { ?>
                        <div class="row" style="margin-top: 5px;">
                            <div class="col-sm-12">
                                <div class="checkbox">
                                    <label>
                                        <input name="chkpoweruser" type="checkbox" id="chkpoweruser"  <?php
                                        if($row["usrpoweruser"] == "yes") {
                                            echo "checked='checked'";
                                        }
                                        ?>value="yes" />
                                        Power User
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="row" style="margin-top: 5px;">
                            <div class="col-sm-12">
                                <div class="checkbox">
                                    <label>
                                        <input name="chkitluser" type="checkbox" id="chkitluser"  <?php
                                        if($row["usraccessITL"] == "yes") {
                                            echo "checked='checked'";
                                        }
                                        ?>value="yes" />
                                        ITL Access
                                    </label>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                    ?>
                    <div class="row" style="margin-top: 5px;">
                        <div class="col-sm-4">
                            <label for="txtname">UserID</label>
                        </div>
                        <div class="col-sm-8">
                            <input name="txtuserid" type="text" class="form-control" id="txtuserid" size="30" maxlength="40" <?php
                            echo "value='".$row["usruserid"]."'";
                            ?> />
                        </div>
                    </div>
                    <div class="row" style="margin-top: 5px;">
                        <div class="col-sm-4">
                            <label for="txtname">Password</label>
                        </div>
                        <div class="col-sm-8">
                            <input name="txtpassword" type="password" class="form-control" id="txtpassword" size="30" maxlength="40"  <?php
                            echo "value='".$row["usrpassword"]."'";
                            ?>/>
                        </div>
                    </div>
                    <div class="row" style="margin-top: 5px;">
                        <div class="col-sm-4">
                            <label for="txtname">Tax Type</label>
                        </div>
                        <div class="col-sm-8">
                            <select name="txttaxtype" class="form-control" id="txttaxtype">
                                <option <?php if($row["usrpref_taxtype"] == "Vat") {echo "selected='selected'"; } ?>>Vat</option>
                                <option <?php if($row["usrpref_taxtype"] == "Gst") {echo "selected='selected'"; } ?>>Gst</option>
                            </select>
                        </div>
                    </div>
                    <div class="row" style="margin-top: 5px;">
                        <div class="col-sm-4">
                            <label for="txtname">Time Zone</label>
                        </div>
                        <div class="col-sm-8">
                            <select name="txttimezone" class="form-control" id="txttimezone">
                                <option value="+2" <?php if($row["usrpref_gmt"] == "+2") {echo "selected='selected'"; } ?>>South Africa (+2)</option>
                                <option value="+8"<?php if($row["usrpref_gmt"] == "+8") {echo "selected='selected'"; } ?>>Australia (+8)</option>
                            </select>
                        </div>
                    </div>
                    <div class="row" style="margin-top: 5px;">
                        <div class="col-sm-12">
                            <div class="checkbox">
                                <label>
                                    <input
                                            name="chkdst"
                                            type="checkbox"
                                            <?php if($row["usrpref_daylightsavingstime"] == "1") {echo "checked='checked'"; } ?>
                                            id="chkdst"
                                            value="1" />
                                    Daylight Savings Time
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="row" style="margin-top: 5px;">
                        <div class="col-sm-4">
                            <label for="txtname">Select groups user may access</label>
                        </div>
                        <div class="col-sm-8">
                            <div class="form-group">
                                <select name="chkgroups[]" id="chkstores" class="selectpicker form-group" data-width="100%" multiple data-actions-box="true">
                                    <?php
                                    if($_SESSION["usr_adminacess"] == 'yes') {
                                        // Get all groups since Admin is logged in
                                        $result = GetAllGroups();
                                    } else {
                                        // Get all groups that admin gave to poweruser
                                        $result = GetStoreGroupsThatUserCanAccess($_SESSION["usrid"]);
                                    }

                                    while($row = mysql_fetch_array($result)) { // Go through all stores
                                        ?>
                                        <option value="<?php echo $row["grpid"];?>"
                                            <?php
                                            if($a == "u") {
                                                if(CanUserAccessGroup($i,$row["grpid"]) == "true") {
                                                    echo "selected='true'";
                                                }
                                            }
                                            ?>>
                                            <?php echo $row["grpname"];?></td>
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <input name="Submit" type="submit" class="btn btn-default" value="Submit" style="color: white; background-color: #007CC4"/>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="col-sm-8 col-sm-offset-2">
    <?php
    if($a == 'a') { // Add New Item
        // Add User
        if($_REQUEST["chkdst"] == '1') {
            $dst = '1'; // Use daylights savings time
        } else {
            $dst = '0'; // Don't use daylights savings time
        }

        $tempusrid = AddUser2($_REQUEST["txtname"], $_REQUEST["chkpoweruser"], $_REQUEST["txtuserid"], $_REQUEST["txtpassword"], $_REQUEST["txttaxtype"],$_REQUEST["txttimezone"],$dst,$_SESSION["usrid"],$_SESSION["chkitluser"]);

        // Go through all check boxes and give user access to groups
        $chkgroups = $_REQUEST["chkgroups"];
        while (list ($key,$val) = @each ($chkgroups)) {
            InsertUserGroupAccess($tempusrid,$val);
        }
        echo "<div class='alert alert-success alert-dismissible' role='alert'>
                <button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
                <span class='NormaBoldGreen'>User has been added</span>
              </div>";
    }

    if($a == "c") { // Change record status
        SetUserStatus($i,$t);
        echo "<div class='alert alert-success alert-dismissible' role='alert'>
                <button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
                <span class='NormaBoldGreen'>User Status has been changed</span>
              </div>";
    }
    if($a == "d") { // Set record as deleted
        DeleteUser($i);
        echo "<div class='alert alert-success alert-dismissible' role='alert'>
                <button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
                <span class='NormaBoldGreen'>User has been deleted</span>
              </div>";
    }

    if($a == "us") { // Update the record

        if($_REQUEST["chkdst"] == '1') {
            $dst = '1';
        } else {
            $dst = '0';
        }

        // Update User Record
        UpdateUser2($hi,$_REQUEST["txtname"],$_REQUEST["txtuserid"],$_REQUEST["txtpassword"],$_REQUEST["txttaxtype"],$_REQUEST["txttimezone"],$dst,$_REQUEST["chkpoweruser"],$_REQUEST["chkitluser"]);

        // Update User Access
        DeleteUsersAccess($hi);

        $chkgroups = $_REQUEST["chkgroups"];
        while (list ($key,$val) = @each ($chkgroups)) {
            InsertUserGroupAccess($hi,$val);
        }

        echo "<div class='alert alert-success alert-dismissible' role='alert'>
                <button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
                <span class='NormaBoldGreen'>User has been updated</span>
              </div>";
    }
    ?>
</div>
<div class="col-sm-8 col-sm-offset-2">
    <div class="well well-md">
        <div class="row">
            <div class="col-sm-12">
                <h3 style="text-transform: uppercase;" class="text-center">
                    Current Users
                    <span class="text-right float-right">
                        <a href="index.php?p=<?php echo $p; ?>" onclick="refresh();">
                            <i class="fas fa-sync-alt"></i>
                        </a>
                    </span>
                </h3>
                <form id="frmmaintain" name="frmmaintain" method="post" action="">
                    <?php
                    // Get all users if admin, only created users if power user
                    if($_SESSION["usr_adminacess"] == "yes") {
                        $result = GetAllUsers();
                    } else {
                        $result = GetCreatedUsers($_SESSION["usrid"]);
                    }

                    if(mysql_num_rows($result) > 0) {
                        ?>
                        <table class="table table-condensed table-hover">
                            <tr>
                                <td width="3%" bgcolor="#F2F2F2" class="NormalText"><div align="center"></div></td>
                                <td width="3%" bgcolor="#F2F2F2" class="NormalText"><div align="center"></div></td>
                                <td width="3%" bgcolor="#F2F2F2" class="NormalText"><div align="center"></div></td>
                                <td width="88%" bgcolor="#F2F2F2" class="NormalText"><strong>User Name</strong></td>
                            </tr>
                            <?php while($row = mysql_fetch_array($result)) {?>
                                <tr>
                                    <td height="24" class="NormalText"><div align="center">
                                            <?php if ($row["usrstatus"] == "active") {
                                                echo "<a href='index.php?p=".$p."&a=c&t=0&i=".$row["usrid"]."'>
                                                                <i class='fas fa-check' style='color: limegreen; font-size: 18px;'></i>
                                                              </a>";

                                            } else {
                                                echo "<a href='index.php?p=".$p."&a=c&t=1&i=".$row["usrid"]."'>
                                                                <i class='fas fa-times' style='color: red; font-size: 18px;'></i>
                                                              </a>";
                                            }
                                            ?>
                                        </div></td>
                                    <td class="NormalText">
                                        <div align="center">
                                            <a href="index.php?p=<?php echo $p; ?>&a=u&i=<?php echo $row["usrid"];?>">
                                                <i class="fas fa-wrench" style="color: dimgrey; font-size: 18px;"></i>
                                            </a>
                                        </div>
                                    </td>
                                    <td class="NormalText">
                                        <div align="center">
                                            <a href="Javascript: if(confirm('Are you sure you want to delete?'))  window.location='index.php?p=<?php echo $p; ?>&a=d&i=<?php echo $row["usrid"];?>';">
                                                <i class="fas fa-trash-alt" style='color: red; font-size: 18px;'></i>
                                            </a>
                                        </div>
                                    </td>
                                    <td <?php
                                    if($row["usrstatus"] == 'active') {
                                        echo "class='NormalText'";
                                    } else {
                                        echo "class='NormalRed'";
                                    }
                                    ?>> <?php echo $row["usrfullname"];?></td>
                                </tr>
                            <?php } ?>
                        </table>
                    <?php } else {
                        echo "You have not added any users yet. Please add one";
                    }?>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="col-sm-4 col-sm-offset-4">
    <div class="well well-md">
        <div class="row">
            <h4 style="text-transform: uppercase;" class="text-center">
                Button Legend
            </h4>
            <div class="col-sm-2">
                <i class='fas fa-check' style='color: limegreen; font-size: 18px;'></i>
            </div>
            <div class="col-sm-10">Active (Click to de-activate)</div>
            <div class="col-sm-2">
                <i class='fas fa-times' style='color: red; font-size: 18px;'></i>
            </div>
            <div class="col-sm-10">Not Active (Click to de-activate)</div>
            <div class="col-sm-2">
                <i class="fas fa-wrench" style="color: dimgrey; font-size: 18px;"></i>
            </div>
            <div class="col-sm-10">Edit</div>
            <div class="col-sm-2">
                <i class="fas fa-trash-alt" style='color: orangered; font-size: 18px;'></i>
            </div>
            <div class="col-sm-10">Delete</div>
        </div>
    </div>
</div>


<script language="JavaScript1.2">

    function refresh() {
        $(".fa-sync-alt").addClass( "fa-spin" );
    }
 function validateform(theform) {
	 var result = true;
	 if(theform.txtname.value == "") {
		 alert('Please supply a valid User Name.');
		 return false
	 }
	 if(theform.txtuserid.value == "") {
		 alert('Please supply a valid UserID.');
		 return false;
	 }
	 if(theform.txtpassword.value == "") {
		 alert('Please supply a valid User Password.');
		 return false;
	 }
	 return true;
 }
 </script>