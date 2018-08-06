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
    <h4 class="text-center" style="color:#757575">Maintain Store Groups</h4>
</div>
<div class="col-sm-8 col-sm-offset-2">
    <div class="well well-md">
        <div class="row">
            <div class="col-sm-12">
                <h3 style="text-transform: uppercase;" class="text-center">
                    <?php
                    if($a != "u") { // Show current action
                    echo "Add new group";
                    } else {
                    echo "Update group";
                    }
                    ?>
                </h3>
                <form id="frmadd" name="frmadd" method="post" action="index.php?p=<?php echo $p;?>&a=<?php if($a != "u") {echo "a";} else {echo "us";}?>" onSubmit="return validateform(this);">
                    <div class="row">
                        <div class="col-sm-4">
                            <label for="txtname">Group Name</label>
                        </div>
                        <div class="col-sm-8">
                            <input name="txtname" type="text" class="form-control" id="txtname" value="<?php if($a == "u") {
                                $groupname =  GetStoreGroupName($i);
                                echo $groupname;
                            }
                            ?>" size="30" maxlength="40" placeholder="Enter new group name"/>
                            <input name="hi" type="hidden" id="hiddenField" value="<?php if($a == "u") { echo $i;} ?>" />
                        </div>
                    </div>
                    <div class="row" style="margin-top: 5px;">
                        <div class="col-sm-4">
                            <label for="txtname">Select Stores in Group</label>
                        </div>
                        <div class="col-sm-8">
                            <div class="form-group">
                                <select name="chkstores[]" id="chkstores" class="selectpicker form-group" data-width="100%" multiple data-actions-box="true">
                                    <?php
                                    if($_SESSION["usr_adminacess"] == "yes") {
                                        $result = GetAllStores(); // Admin so get all stores
                                    } else {
                                        $result = GetStoresThatUserCanAccess($_SESSION["usrid"]); // Power user so get stores access given to
                                    }

                                    while($row = mysql_fetch_array($result)) { // Show all stores retrieved

                                        ?>
                                        <option value="<?php echo $row["strid"];?>"
                                            <?php
                                            if($a == "u") {
                                                if(IsGroupStore($i,$row["strid"]) == "true") { // Check if part of group already
                                                    echo "selected='true'";
                                                }
                                            }
                                            ?>>
                                            <?php echo $row["strname"];?>
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
        // Add Group
        $grpid = AddGroup($_REQUEST["txtname"]);

        // Give Access to this group to current user
        AddUserAccess($_SESSION["usrid"],$grpid,0);

        // Go through all check boxes and add Group and StoresGroup records
        $chkstores = $_REQUEST["chkstores"];
        while (list ($key,$val) = @each ($chkstores)) {
            AddStoresGroups($grpid,$val);
        }
        echo "<div class='alert alert-success alert-dismissible' role='alert'>
                <button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
                <span class='NormaBoldGreen'>Group has been added</span>
              </div>";
    }

    if($a == "c") { // Change record status
        SetGroupStatus($i,$t);
        echo "<div class='alert alert-success alert-dismissible' role='alert'>
                <button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
                <span class='NormaBoldGreen'>Group Status has been changed</span>
              </div>";
    }
    if($a == "d") { // Set record as deleted
        DeleteGroup($i);
        echo "<div class='alert alert-success alert-dismissible' role='alert'>
                <button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
                <span class='NormaBoldGreen'>Group has been deleted</span>
              </div>";
    }

    if($a == "us") { // Update the record
        // DeActivate all stores for group then set active by list
        SetAllStoresGroupsStatus($hi,"inactive");

        while (list ($key,$val) = @each ($_REQUEST["chkstores"])) {
            $status = GetStoresGroupStatus($hi,$val);

            if($status == "inactive") { //then activate it again.
                SetStoresGroupsStatus($hi,$val,"active");
            }
            // If store already part of group then if it must be deleted then make it inactive
            if($status == "false") {
                AddStoresGroups($hi,$val);
            }
            // If store is not part of group then add record to make it part of group
        }
        echo "<div class='alert alert-success alert-dismissible' role='alert'>
                <button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
                <span class='NormaBoldGreen'>Group has been updated</span>
              </div>";
    }
    ?>
</div>
<div class="col-sm-8 col-sm-offset-2">
    <div class="well well-md">
        <div class="row">
            <div class="col-sm-12">
                <h3 style="text-transform: uppercase;" class="text-center">
                    Current Groups
                    <span class="text-right float-right">
                        <a href="index.php?p=<?php echo $p; ?>" onclick="refresh();">
                            <i class="fas fa-sync-alt"></i>
                        </a>
                    </span>
                </h3>
                <form id="frmmaintain" name="frmmaintain" method="post" action="">
                    <?php
                    if($_SESSION["usr_adminacess"] == "yes") {
                        $result = GetAllStoreGroups();	// User has admin rights. Show all stores.
                    } else {
                        $result = GetStoreGroupsThatUserCanMaintain($_SESSION["usrid"]); // Power user. Show allocated stores
                    }


                    if(mysql_num_rows($result) > 0) {
                        ?>
                        <table class="table table-condensed table-hover">
                            <tr>
                                <td width="3%" bgcolor="#F2F2F2" class="NormalText"><div align="center"></div></td>
                                <td width="3%" bgcolor="#F2F2F2" class="NormalText"><div align="center"></div></td>
                                <td width="3%" bgcolor="#F2F2F2" class="NormalText"><div align="center"></div></td>
                                <td width="88%" bgcolor="#F2F2F2" class="NormalText"><strong>Group Name</strong></td>
                            </tr>
                            <?php while($row = mysql_fetch_array($result)) {?>
                                <tr>
                                    <td height="24" class="NormalText"><div align="center">
                                            <?php if ($row["grpstatus"] == "active") { // Active store
                                                echo "<a href='index.php?p=".$p."&a=c&t=0&i=".$row["grpid"]."'>
                                                                <i class='fas fa-check' style='color: limegreen; font-size: 18px;'></i>
                                                              </a>";

                                            } else { // InActive store
                                                echo "<a href='index.php?p=".$p."&a=c&t=1&i=".$row["grpid"]."'>
                                                                <i class='fas fa-times' style='color: red; font-size: 18px;'></i>
                                                              </a>";
                                            }
                                            ?>
                                        </div></td>
                                    <td class="NormalText">
                                        <div align="center">
                                            <a href="index.php?p=<?php echo $p; ?>&a=u&i=<?php echo $row["grpid"];?>">
                                                <i class="fas fa-wrench" style="color: dimgrey; font-size: 18px;"></i>
                                            </a>
                                        </div>
                                    </td>
                                    <td class="NormalText">
                                        <div align="center">
                                            <a href="Javascript: if(confirm('Are you sure you want to delete?'))  window.location='index.php?p=<?php echo $p; ?>&a=d&i=<?php echo $row["grpid"];?>';">
                                                <i class="fas fa-trash-alt" style='color: red; font-size: 18px;'></i>
                                            </a>
                                        </div>
                                    </td>
                                    <td <?php
                                    if($row["grpstatus"] == 'active') {
                                        echo "class='NormalText'";
                                    } else {
                                        echo "class='NormalRed'";
                                    }
                                    ?>> <?php echo $row["grpname"];?></td>
                                </tr>
                            <?php } ?>
                        </table>
                    <?php } else {
                        echo "No groups exist yet. Please add one";
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
		 alert('Please supply a valid Group name.');
		 result = false;
	 }
	 return result;
 }
 </script>