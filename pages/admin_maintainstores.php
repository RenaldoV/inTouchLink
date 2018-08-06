<link href="../style.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css"/>
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.10/css/all.css"
      integrity="sha384-+d0P83n9kaQMCwj8F4RJB66tzIwOKmrdb46+porD/OvrJ+37WqIM7UoBtwHO6Nlg" crossorigin="anonymous">
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
        <h4 class="text-center" style="color:#757575">Maintain Stores</h4>
    </div>
    <div class="col-sm-8 col-sm-offset-2">
        <?php if($_SESSION["usr_adminacess"] == "yes" || ($_SESSION["usr_poweruseracess"] == "yes" && $a == "u")) { ?>
        <div class="well well-md">
            <div class="row">
                <div class="col-sm-12">
                    <h3 style="text-transform: uppercase;" class="text-center">
                        <?php
                        if($a != "u") { // Show current action
                            echo "Add new store";
                        } else {
                            echo "Update store";
                        }
                        ?>
                    </h3>
                    <form id="frmadd" name="frmadd" method="post"
                          action="index.php?p=<?php echo $p; ?>&a=<?php if ($a != "u") {
                              echo "a";
                          } else {
                              echo "us";
                          } ?>" onSubmit="return validateform(this);">

                        <div class="row">
                            <div class="col-sm-4">
                                <label for="txtname">Store Name</label>
                            </div>
                            <div class="col-sm-8">
                                <input name="txtname" type="text" class="form-control"
                                       id="txtname" <?php if ($a == "u") {
                                    echo "readonly";
                                } ?> value="<?php if ($a == "u") {
                                    $result = GetStoreDetails($i); // Get store details
                                    $row = mysql_fetch_array($result);
                                    echo $row["strname"];
                                }
                                ?>" size="30" maxlength="40"/>
                            </div>
                        </div>
                        <div class="row" style="margin-top: 5px;">
                            <div class="col-sm-4"><label for="date">Store Code</label></div>
                            <div class="col-sm-8">
                                <input name="txtcode" type="text" class="form-control" id="txtcode"
                                       value="<?php if ($a == "u") {
                                           echo $row["strcode"];
                                       } ?>" size="10"
                                       maxlength="8" <?php if ($a == "u") {
                                    echo "readonly";
                                } ?> />
                                <input name="hi" type="hidden" id="hiddenField" value="<?php if ($a == "u") {
                                    echo $row["strid"];
                                } ?>"/>
                            </div>
                        </div>
                        <div class="row" style="margin-top: 5px;">
                            <div class="col-sm-4"><label for="date">Contact Person</label></div>
                            <div class="col-sm-8">
                                <input name="txtcontactname" type="text" class="form-control" id="txtcontactname"
                                       value="<?php if ($a == "u") {
                                           echo $row["strcontactname"];

                                       }
                                       ?>" size="30" maxlength="40"/>
                            </div>
                        </div>
                        <div class="row" style="margin-top: 5px;">
                            <div class="col-sm-4"><label for="date">Contact Tel Number</label></div>
                            <div class="col-sm-8">
                                <input name="txtcontactnumber" type="text" class="form-control" id="txtcontactnumber"
                                       value="<?php if ($a == "u") {

                                           echo $row["strcontactnumber"];

                                       }
                                       ?>" size="30" maxlength="40"/>
                            </div>
                        </div>
                        <div class="row" style="margin-top: 5px;">
                            <div class="col-sm-4"><label for="date">Contact Email Address</label></div>
                            <div class="col-sm-8">
                                <input name="txtcontactemail" type="text" class="form-control" id="txtcontactemail"
                                       value="<?php if ($a == "u") {

                                           echo $row["strcontactemail"];

                                       }
                                       ?>" size="30" maxlength="40"/>
                            </div>
                        </div>
                        <div class="row" style="margin-top: 5px;">
                            <div class="col-sm-4"><label for="date">Store Tel Number</label></div>
                            <div class="col-sm-8">
                                <input name="txtstorenumber" type="text" class="form-control" id="txtstorenumber"
                                       value="<?php if ($a == "u") {

                                           echo $row["strstorenumber"];

                                       }
                                       ?>" size="30" maxlength="40"/>
                            </div>
                        </div>
                        <div class="row" style="margin-top: 5px;">
                            <div class="col-sm-4"><label for="date">Royalty Type</label></div>
                            <div class="col-sm-8">
                                <select name="cmbroyaltytype" class="form-control" id="cmbroyaltytype">
                                    <option <?php if ($a == "u" && $row["strroyaltytype"] == "Not applicable") {
                                        echo "selected='selected'";
                                    } ?> value="Not applicable">Not applicable
                                    </option>
                                    <option <?php if ($a == "u" && $row["strroyaltytype"] == "Nett") {
                                        echo "selected='selected'";
                                    } ?> value="Nett">Nett
                                    </option>
                                    <option <?php if ($a == "u" && $row["strroyaltytype"] == "Gross") {
                                        echo "selected='selected'";
                                    } ?> value="Gross">Gross
                                    </option>
                                    <option <?php if ($a == "u" && $row["strroyaltytype"] == "Banking Sales") {
                                        echo "selected='selected'";
                                    } ?> value="Banking Sales">Banking Sales
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="row" style="margin-top: 5px;">
                            <div class="col-sm-4"><label for="date">Royalty Percentage</label></div>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <input name="txtroyaltypercent" type="text" class="form-control"
                                           id="txtroyaltypercent" value="<?php if ($a == "u") {
                                        echo $row["strroyaltypercent"];
                                    } else {
                                        echo "0";
                                    }
                                    ?>" size="8" maxlength="3" aria-describedby="basic-addon2">
                                    <span class="input-group-addon" id="basic-addon2">%</span>
                                </div>
                            </div>
                        </div>
                        <input name="Submit" type="submit" class="btn btn-default" value="Submit"
                               style="color: white; background-color: #007CC4"/>
                    </form>
                </div>
            </div>
        </div>
        <?php
        }

        if($a == 'a') { // Add New Item
            InsertStore($_REQUEST["txtname"],$_REQUEST["txtcode"],$_REQUEST["txtcontactname"],$_REQUEST["txtcontactnumber"],$_REQUEST["txtcontactemail"],$_REQUEST["txtstorenumber"],$_REQUEST["cmbroyaltytype"],$_REQUEST["txtroyaltypercent"]);
            echo "<div class='alert alert-success alert-dismissible' role='alert'>
                    <button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
                    <span class='NormaBoldGreen'>Store has been added</span>
                  </div>";
        }

        if($a == "c") { // Change record status
            SetStoreStatus($i,$t);
            echo "<div class='alert alert-success alert-dismissible' role='alert'>
                    <button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
                    <span class='NormaBoldGreen'>Store Status has been changed</span>
                  </div>";
        }
        if($a == "d") { // Set record as deleted
            DeleteStore($i);
            echo "<div class='alert alert-success alert-dismissible' role='alert'>
                    <button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
                    <span class='NormaBoldGreen'>Store has been deleted</span>
                  </div>";
        }

        if($a == "us") { // Update the record
            UpdateStore($_REQUEST["hi"],$_REQUEST["txtname"],$_REQUEST["txtcode"],$_REQUEST["txtcontactname"],$_REQUEST["txtcontactnumber"],$_REQUEST["txtcontactemail"],$_REQUEST["txtstorenumber"],$_REQUEST["cmbroyaltytype"],$_REQUEST["txtroyaltypercent"]);
            echo "<div class='alert alert-success alert-dismissible' role='alert'>
                    <button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
                    <span class='NormaBoldGreen'>Store has been updated</span>
                  </div>";
        }
        ?>
    </div>
    <div class="col-sm-8 col-sm-offset-2">
        <div class="well well-md">
            <div class="row">
                <div class="col-sm-12">
                    <h3 style="text-transform: uppercase;" class="text-center">
                        Current Stores
                        <span class="text-right float-right">
                            <a href="index.php?p=<?php echo $p; ?>" onclick="refresh();">
                                <i class="fas fa-sync-alt"></i>
                            </a>
                        </span>
                    </h3>
                    <form id="frmmaintain" name="frmmaintain" method="post" action="">
                        <?php
                        // Get all stores if Admin is logged in
                        if($_SESSION["usr_adminacess"] == "yes") {
                            $result = GetAllStores();
                        }
                        // Get stores that power user can access when logged in
                        if($_SESSION["usr_poweruseracess"] == "yes") {
                            $result = GetStoresAndInfoThatUserCanAccess($_SESSION["usrid"]);
                        }
                        if(mysql_num_rows($result) > 0) {
                            ?>
                            <table width="100%" class="table table-condensed table-hover">
                                <tr>
                                    <?php if($_SESSION["usr_adminacess"] == "yes") { ?>
                                        <td width="3%" bgcolor="#F2F2F2" class="NormalText"><div align="center"></div></td>
                                    <?php } ?>
                                    <td width="3%" bgcolor="#F2F2F2" class="NormalText"><div align="center"></div></td>
                                    <?php
                                    // Hide column for power users
                                    if($_SESSION["usr_adminacess"] == "yes") {
                                        ?>
                                        <td width="3%" bgcolor="#F2F2F2" class="NormalText"><div align="center"></div></td>
                                    <?php } ?>
                                    <td width="88%" bgcolor="#F2F2F2" class="NormalText"><strong>Store Name</strong></td>
                                </tr>
                                <?php while($row = mysql_fetch_array($result)) {
                                    // Hide inactive stores from Power Users
                                    if($_SESSION["usr_adminacess"] == "yes" || ($_SESSION["usr_poweruseracess"] == "yes" && $row["strstatus"] == "active")) {
                                        ?>
                                        <tr>
                                            <?php
                                            // Hide column for power users
                                            if($_SESSION["usr_adminacess"] == "yes") {
                                                ?>
                                                <td height="24" class="NormalText">
                                                    <div align="center">
                                                        <?php
                                                        if ($row["strstatus"] == "active") {
                                                            echo "<a href='index.php?p=".$p."&a=c&t=0&i=".$row["strid"]."'>
                                                                    <i class='fas fa-check' style='color: limegreen; font-size: 18px;'></i>
                                                                  </a>";

                                                        } else {
                                                            echo "<a href='index.php?p=".$p."&a=c&t=1&i=".$row["strid"]."'>
                                                                    <i class='fas fa-times' style='color: red; font-size: 18px;'></i>
                                                                  </a>";
                                                        }
                                                        ?>
                                                    </div>
                                                </td>
                                            <?php } ?>
                                            <td class="NormalText">
                                                <div align="center">
                                                    <a href="index.php?p=<?php echo $p; ?>&a=u&i=<?php echo $row["strid"];?>">
                                                        <i class="fas fa-wrench" style="color: dimgrey; font-size: 18px;"></i>
                                                    </a>
                                                </div>
                                            </td>
                                            <?php
                                            // Hide column for power users
                                            if($_SESSION["usr_adminacess"] == "yes") {
                                                ?>
                                                <td class="NormalText">
                                                    <div align="center">
                                                        <a href="Javascript: if(confirm('Are you sure you want to delete?'))  window.location='index.php?p=<?php echo $p; ?>&a=d&i=<?php echo $row["strid"];?>';">
                                                            <i class="fas fa-trash-alt" style='color: red; font-size: 18px;'></i>
                                                        </a>
                                                    </div>
                                                </td>
                                            <?php } ?>
                                            <td <?php
                                            if($row["strstatus"] == 'active') {
                                                echo "class='NormalText'";
                                            } else {
                                                echo "class='NormalRed'";
                                            }
                                            ?>> <?php echo $row["strname"]." (".$row["strcode"].")"; ?></td>
                                        </tr>
                                    <?php }} ?>
                            </table>
                        <?php } else {
                            echo "No groups exist yet. Please add one";
                        }?>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php
    // Hide from power users
    if($_SESSION["usr_adminacess"] == "yes") {
    ?>
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
    <?php } ?>


<script language="JavaScript1.2">
    
    function refresh() {
        $(".fa-sync-alt").addClass( "fa-spin" );
    }
 function validateform(theform) {
	 var result = true;
	 if(theform.txtname.value == "") {
		 alert('Please supply a valid Store Name.');
		 result = false;
	 }
	 if(theform.txtcode.value == "") {
		 alert('Please supply a valid Store Code.');
		 result = false;
	 }
	 if(theform.txtroyaltypercent.value < 0 || theform.txtroyaltypercent.value > 100 || trim(theform.txtroyaltypercent.value) == "") {
		 alert('Please supply a royalty percentage between 0 and 100 %.');
		 result = false;
	 }

	 return result;
 }
 function trim(str, chars) {
    return ltrim(rtrim(str, chars), chars);
}

function ltrim(str, chars) {
    chars = chars || "\\s";
    return str.replace(new RegExp("^[" + chars + "]+", "g"), "");
}

function rtrim(str, chars) {
    chars = chars || "\\s";
    return str.replace(new RegExp("[" + chars + "]+$", "g"), "");
}
 </script>
