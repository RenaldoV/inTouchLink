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
    <h4 class="text-center" style="color:#757575">Exceptions Capturing/Definition</h4>
</div>
<div class="col-sm-8 col-sm-offset-2">
    <div class="well well-md">
        <div class="row">
            <div class="col-sm-12">
                <h3 style="text-transform: uppercase;" class="text-center">
                    Please enter store exceptions values
                </h3>
                <form id="frmparameters" name="frmparameters" method="post" action="index.php?p=form_exceptions&saveexception=1">
                    <div class="row">
                        <div class="col-sm-4">
                            <label for="cmbstore">Store</label>
                        </div>
                        <div class="col-sm-8">
                            <input hidden name="radStores" type="radio" value="store" checked='checked'/>
                            <select name="cmbstore" class="form-control" id="cmbstore" >
                                <?php
                                $voidsval = 0;
                                $refundsval = 0;
                                $splitsval = 0;
                                $clearsval = 0;
                                $transfersval = 0;
                                $reopenedchecksval = 0;
                                $save = $_REQUEST["save"];
                                $result = GetStoresThatUserCanAccess($_SESSION["usrid"]); // Get user's stores
                                while($row = mysql_fetch_array($result)) {
                                    $output = "<option value='".$row["strid"]."'";
                                    if(isset($_REQUEST["store"])) {
                                        if($_REQUEST["store"] == $row["strid"]) {
                                            $output =  $output." selected ";
                                        }
                                    }else {
                                        if($_SESSION["cmbstore"] == $row["strid"]) {
                                            $output =  $output." selected ";
                                        }
                                    }
                                    $output = $output.">".$row["strname"]."</option>";
                                    echo $output;
                                }
                                if(isset($_REQUEST['store']) && $saveexception < '1') {
                                    $storeexceptions = GetExceptionsForStore($_REQUEST['store']);
                                    while ($row2 = mysql_fetch_array($storeexceptions))
                                    {
                                        $voidsval = $row2["voids"];
                                        $refundsval = $row2["refunds"];
                                        $splitsval = $row2["splits"];
                                        $clearsval = $row2["clears"];
                                        $transfersval = $row2["transfers"];
                                        $reopenedchecksval = $row2["reopenedchecks"];
                                    }
                                }else if($_SESSION['cmbstore'] && $saveexception < '1') {
                                    $storeexceptions = GetExceptionsForStore($_SESSION['cmbstore']);
                                    while ($row2 = mysql_fetch_array($storeexceptions))
                                    {
                                        $voidsval = $row2["voids"];
                                        $refundsval = $row2["refunds"];
                                        $splitsval = $row2["splits"];
                                        $clearsval = $row2["clears"];
                                        $transfersval = $row2["transfers"];
                                        $reopenedchecksval = $row2["reopenedchecks"];
                                    }
                                }else {
                                    $voidsval = $voids;
                                    $refundsval = $refunds;
                                    $splitsval = $splits;
                                    $clearsval = $clears;
                                    $transfersval = $transfers;
                                    $reopenedchecksval = $reopenedchecks;
                                }

                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="row" style="margin-top: 5px">
                        <div class="col-sm-4">
                            <label for="cmbstore">Voids Amount</label>
                        </div>

                        <div class="col-sm-8">
                            <input value="<?php echo $voidsval ?>" type="text" name="txtVoids" id="txtVoids" class="form-control" />
                        </div>
                    </div>
                    <div class="row" style="margin-top: 5px">
                        <div class="col-sm-4">
                            <label for="cmbstore">Refunds Amount</label>
                        </div>

                        <div class="col-sm-8">
                            <input value="<?php echo $refundsval ?>" type="text" name="txtRefunds" id="txtRefunds" class="form-control"/>
                        </div>
                    </div>
                    <div class="row" style="margin-top: 5px">
                        <div class="col-sm-4">
                            <label for="cmbstore">Splits Count</label>
                        </div>

                        <div class="col-sm-8">
                            <input value="<?php echo $splitsval ?>" type="text" name="txtSplits" id="txtSplits" class="form-control"/>
                        </div>
                    </div>
                    <div class="row" style="margin-top: 5px">
                        <div class="col-sm-4">
                            <label for="cmbstore">Clears Amount</label>
                        </div>

                        <div class="col-sm-8">
                            <input value="<?php echo $clearsval ?>" type="text" name="txtClears" id="txtClears" class="form-control"/>
                        </div>
                    </div>
                    <div class="row" style="margin-top: 5px">
                        <div class="col-sm-4">
                            <label for="cmbstore">Transfers Count</label>
                        </div>

                        <div class="col-sm-8">
                            <input value="<?php echo $transfersval ?>" type="text" name="txtTransfers" id="txtTransfers" class="form-control"/>
                        </div>
                    </div>
                    <div class="row" style="margin-top: 5px">
                        <div class="col-sm-4">
                            <label for="cmbstore">Reopened Checks Count</label>
                        </div>

                        <div class="col-sm-8">
                            <input value="<?php echo $reopenedchecksval ?>" type="text" name="txtReopenedChecks" id="txtReopenedChecks" class="form-control"/>
                        </div>
                    </div>
                    <input name="Submit" type="submit" class="btn btn-default" value="Submit"
                           style="color: white; background-color: #007CC4"/>
                </form>
            </div>
        </div>
    </div>
</div>

<hr>
<div class="col-sm-8 col-sm-offset-2">
    <?php
    // -------------- save exceptions data -----------------------
    if ($saveexception == '1') { ?>
    <div class="alert alert-success alert-dismissible" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                    aria-hidden="true">&times;</span></button>
        <div class="media">
            <div class="media-left">
                <a href="#">
                    <span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span>
                </a>
            </div>
            <div class="media-body">
                <?php

                /*$exceptionstore = $_REQUEST["cmbstore"];
                $voids =$_REQUEST["txtVoids"];
                $refunds =$_REQUEST["txtRefunds"];
                $splits =$_REQUEST["txtSplits"];
                $clears =$_REQUEST["txtClears"];
                $transfers =$_REQUEST["txtTransfers"];
                $reopenedchecks = $_REQUEST["txtReopenedChecks"];*/
                $results = SaveExceptions($exceptionstore, $voids, $refunds, $splits, $clears, $transfers, $reopenedchecks);
                echo "<h4 class='media-heading'>Exceptions data saved successfully</h4>";
                echo "<br/>";
                echo "<strong>Store ID</strong> = " . $exceptionstore;
                echo "<br/>";
                echo "<strong>Voids</strong> = " . $voids;
                echo "<br/>";
                echo "<strong>Refunds</strong> = " . $refunds;
                echo "<br/>";
                echo "<strong>Splits</strong> = " . $splits;
                echo "<br/>";
                echo "<strong>Clears</strong> = " . $clears;
                echo "<br/>";
                echo "<strong>Transfers</strong> = " . $transfers;
                echo "<br/>";
                echo "<strong>Reopenedchecks</strong> = " . $reopenedchecks;
                $saveexception = '0';
                }
                ?>
            </div>
        </div>
    </div>
</div>


<script language="JavaScript1.2">

    $(document).ready(function(){
        $('#cmbstore').change(function(){
            //Selected value
            var inputValue = $(this).val();
            //Ajax for calling php function
            $.post('pages/setStore.php', { dropdownValue: inputValue }, function(data){
                window.location.href = '/inTouchLink/index.php?p=form_exceptions&store=' + data;
                //do after submission operation in DOM
            });
        });
    });

function SetSpecificDateFocus() {

     document.frmparameters.dateday.disabled = false;
     document.frmparameters.datemonth.disabled = false;	 
     document.frmparameters.dateyear.disabled = false;
     
	 document.frmparameters.datefromday.disabled = true;	 
     document.frmparameters.datefrommonth.disabled = true;	 
     document.frmparameters.datefromyear.disabled = true;	 
     
	 document.frmparameters.datetoday.disabled = true;
	 document.frmparameters.datetomonth.disabled = true;
	 document.frmparameters.datetoyear.disabled = true;
}

function SetSpecificDateRangeFocus() {
     document.frmparameters.dateday.disabled = true;
     document.frmparameters.datemonth.disabled = true;	 
     document.frmparameters.dateyear.disabled = true;
     
	 document.frmparameters.datefromday.disabled = false;	 
     document.frmparameters.datefrommonth.disabled = false;	 
     document.frmparameters.datefromyear.disabled = false;	 
     
	 document.frmparameters.datetoday.disabled = false;
	 document.frmparameters.datetomonth.disabled = false;
	 document.frmparameters.datetoyear.disabled = false;
}

function SetStoreFocus() {
     document.frmparameters.cmbstore.disabled = false;
     document.frmparameters.cmbstoregroup.disabled = true;
}

function SetStoreGroupFocus() {
     document.frmparameters.cmbstore.disabled = true;
     document.frmparameters.cmbstoregroup.disabled = false;
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
 
</script>
