<?php 
session_start();
ini_set('error_reporting', E_ALL | E_STRICT);
ini_set('display_errors', 'Off');
ini_set('log_errors', 'On');
ini_set('error_log', 'php.log');
set_time_limit(0); // Remove timeout
require_once('../library/library.php');
db_connect();

			 // Paramter Fields
                     // Dates
					 $dateday = $_REQUEST["dateday"];
                     $datemonth = $_REQUEST["datemonth"];				 
                     $dateyear = $_REQUEST["dateyear"];		
					 $a = "s";
					 $datefromday = $_REQUEST["datefromday"];
					 $datefrommonth = $_REQUEST["datefrommonth"];
					 $datefromyear = $_REQUEST["datefromyear"];					 
					
					 $datetoday = $_REQUEST["datetoday"];
					 $datetomonth = $_REQUEST["datetomonth"];
					 $datetoyear = $_REQUEST["datetoyear"];						 
					 $radDate = $_REQUEST["radDate"];					
					 $store = str_replace("^","'",$_REQUEST["store"]);	
                     $daterangestring = $_SESSION["daterangestring"];
                     $grpid = $_REQUEST["grpid"];
                     $radStores = $_REQUEST["radStores"];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Sales Summary Report</title>
<link href="../style.css" rel="stylesheet" type="text/css" />
</head>

<body>
<link href="../style.css" rel="stylesheet" type="text/css">
<div align="center"><br />
  
</div>
<div align="center"><img src="../images/report_header.gif" width="600" height="73" border="0" /></div>
<table width="600" height="753" border="0" align="center" cellpadding="5" cellspacing="0">
  
  <tr>
    <td width="590" height="1" valign="top"><p align="center" class="NormalHeading">
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
if($_SESSION["ReportOption"] == 1){
if($radDate == "date") {
	$result = GetSummaryTotals($sumid);
}
if($radDate == "daterange") {
	$result = GetSummaryTotalsWithDateRange($sumid);
}
}

if($_SESSION["ReportOption"] == 2){
if($radDate == "date") {
	$result = GetSummaryTotalsOp2($sumid);
}
if($radDate == "daterange") {
	$result = GetSummaryTotalsWithDateRangeOp2($sumid);
}
}


$row = mysql_fetch_array($result);
	$resultadditional = GetSummaryTotalAdditions($sumid);
	//  echo "*".mysql_num_rows($resultadditional)."*";
    $rowadditional = mysql_fetch_array($resultadditional);
	
if($rowadditional["adcharges"] == null) {
  $additionalchargers = "0.00" ;
} else {

	$additionalchargers = $rowadditional["adcharges"] ;
}

if( $rowadditional["ordercharges"] == null) {
		$ordercharges = "0.00";
} else {
		$ordercharges = $rowadditional["ordercharges"] ;
}
	  
if( $rowadditional["promos"] == null) {
		$promos = "0.00";
} else {
		$promos = $rowadditional["promos"] ;
}

if($rowadditional["refunds"] == null) {
   $refunds = "0.00";
} else {
	$refunds = $rowadditional["refunds"] ;
}
	
	  ?></p>
      <div align="center">
	    <table width="524" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td><div align="center" class="NormalText">
              <p><span class="NormalHeading"><strong>Sales Summary Report</strong></span><strong><br />
                for<br />
                  </strong>
                  <?php 
				if($radStores == "store") {
				  echo GetStoreName($_SESSION["store"]); 
				}
				if($radStores == "storegroup") {
				  echo GetStoreGroupName($grpid); 
				}				
				?>
                  <?php 
if($radStores == "storegroup") {
	echo $_SESSION["storegroupscount"];
}

?>
                  <br />
                <strong>for date </strong> <br />
                <?php 
			if($radDate == "date") {
			  echo $_SESSION["dateday"]."/".$_SESSION["datemonth"]."/".$_SESSION["dateyear"];
			}
			if($radDate == "daterange") {
			  echo $_SESSION["datefromday"]."/".$_SESSION["datefrommonth"]."/".$_SESSION["datefromyear"]." to ".$_SESSION["datetoday"]."/".$_SESSION["datetomonth"]."/".$_SESSION["datetoyear"];
			}			
			if($_SESSION["dayofweek"] != "All Days") {
	echo " (".$_SESSION["dayofweek"]."s only)";
}
			?>
              <br />
              <strong>Report Option </strong> <br />
                <?php 
			  echo $_SESSION["ReportOption"];?>
</p></div></td>
          </tr>
        </table>
	    <hr size="1" />
	    <span class="NormalHeading">	    Summary</span><br />
        <br />
      </div>
      <table width="53%" border="0" align="center" cellpadding="0" cellspacing="0">
        <tr>
          <td width="60%" bgcolor="#F2F2F2" class="NormalText"><strong>Gross Sales  </strong></td>
          <td width="40%" class="NormalText"><div align="right"><?php echo $row["sumgrosssales"]; ?></div></td>
        </tr>
        <tr>
          <td bgcolor="#F2F2F2" class="NormalText"><strong>Nett Sales </strong></td>
          <td class="NormalText"><div align="right"><?php echo $row["sumnettsales"]; ?></div></td>
        </tr>
        <tr>
          <td bgcolor="#F2F2F2" class="NormalText"><strong>Banking Sales </strong></td>
          <td class="NormalText"><div align="right"><?php echo $row["sumbankingsales"]; ?></div></td>
        </tr>
      </table>      
      <br />
      <table width="56%" border="0" align="center" cellpadding="2" cellspacing="0">
      <tr>
          <td bgcolor="#F2F2F2" class="NormalText"><strong>Additional Charges</strong></td>
          <td class="NormalText"><div align="right"><?php echo $additionalchargers;; 
		  		  // ************************************************

		  ?></div></td>
        </tr>
        <tr>
          <td bgcolor="#F2F2F2" class="NormalText"><strong>Order Charges</strong></td>
          <td class="NormalText"><div align="right"><?php echo $ordercharges; 
		  		  // ************************************************

		  ?></div></td>
        </tr>
        <tr>
          <td bgcolor="#F2F2F2" class="NormalText"><strong>Promos</strong></td>
          <td class="NormalText"><div align="right"><?php echo $promos; 
		  		  // ************************************************

		  ?></div></td>
        </tr>
        <tr>
          <td bgcolor="#F2F2F2" class="NormalText"><strong>Refunds</strong></td>
          <td class="NormalText"><div align="right"><?php echo $refunds; 
		  		  // ************************************************

		  ?></div></td>
        </tr>  <tr>
          <td width="60%" bgcolor="#F2F2F2" class="NormalText"><strong>Comps</strong></td>
          <td width="40%" class="NormalText"><div align="right"><?php echo $row["sumcomps"]; ?></div></td>
        </tr>
        <tr>
          <td bgcolor="#F2F2F2" class="NormalText"><strong><?php echo $_SESSION["usrpref_taxtype"]; ?></strong></td>
          <td class="NormalText"><div align="right"><?php echo $row["sumvat"]; ?></div></td>
        </tr>
        <tr>
          <td bgcolor="#F2F2F2" class="NormalText"><strong>Voids</strong></td>
          <td class="NormalText"><div align="right"><?php echo $row["sumvoids"]; ?></div></td>
        </tr>
        <tr>
          <td bgcolor="#F2F2F2" class="NormalText"><strong>Head Count </strong></td>
          <td class="NormalText"><div align="right"><?php echo $row["sumheadcount"]; ?></div></td>
        </tr>
        <tr>
          <td bgcolor="#F2F2F2" class="NormalText"><strong>Ave Per Head (excl <?php echo $_SESSION["usrpref_taxtype"]; ?>) </strong></td>
          <td class="NormalText"><div align="right"><?php 
		  // Calculate the Avg per Head ex Vat
             if($row["sumheadcount"] == '0') {
			   echo "0";
			 } else {
			   echo round($row["sumnettsales"] / $row["sumheadcount"],2);
             }
		  ?></div></td>
        </tr>
        <tr>
          <td bgcolor="#F2F2F2" class="NormalText"><strong>Ave Per Head (incl <?php echo $_SESSION["usrpref_taxtype"]; ?>) </strong></td>
          <td class="NormalText"><div align="right">
            <?php 
// Calculate the Avg per Head incl Vat
if($row["sumheadcount"] < 1) {
	$amount = "0";
	echo $amount;
} else {
	// South Africa is 14% vat, Australia is 10% GST
	if($_SESSION["usrpref_taxtype"] == "Vat") {	
		 
		$amount = number_format(($row["sumnettsales"] / $row["sumheadcount"])*1.14, 2, '.', '');
	} 
	if($_SESSION["usrpref_taxtype"] == "Gst") {		
		$amount = number_format(($row["sumnettsales"] / $row["sumheadcount"])*1.10, 2, '.', '');
	}
	echo $amount;
}

?>
          </div></td>
        </tr>
        <tr>
          <td bgcolor="#F2F2F2" class="NormalText"><strong>Check Count </strong></td>
          <td class="NormalText"><div align="right"><?php echo $row["sumcheckcount"]; 
	  
		  ?></div></td>
        </tr>
        <tr>
          <td bgcolor="#F2F2F2" class="NormalText"><strong>Ave Per Check (excl <?php echo $_SESSION["usrpref_taxtype"]; ?>) </strong></td>
          <td class="NormalText"><div align="right">
              <?php 
// Calculate the Avg per Head ex Vat
if($row["sumcheckcount"] < 1) {
	$amount = "0.00";
	echo $amount;
} else {
	$amount = round($row["sumnettsales"] / $row["sumcheckcount"],2);
	echo number_format($amount,"2",".","");
}


?>
          </div></td>
        </tr>
        <tr>
          <td bgcolor="#F2F2F2" class="NormalText"><strong>Ave Per Check (incl <?php echo $_SESSION["usrpref_taxtype"]; ?>) </strong></td>
          <td class="NormalText"><div align="right">
              <?php 
// Calculate the Avg per Head incl Vat
if($row["sumheadcount"] < 1) {
	$amount = "0";
	echo $amount;
} else {
	// South Africa is 14% vat, Australia is 10% GST
	if($_SESSION["usrpref_taxtype"] == "Vat") {	
		 
		$amount = number_format(($row["sumnettsales"] / $row["sumcheckcount"])*1.14, 2, '.', '');
	} 
	if($_SESSION["usrpref_taxtype"] == "Gst") {		
		$amount = number_format(($row["sumnettsales"] / $row["sumcheckcount"])*1.10, 2, '.', '');
	}
	echo $amount;
}

?>
          </div></td>
        </tr>
      </table>      
      
	  <?php 
	  
	  $result = GetSalesByCategory($sumid);
	  
	  ?>
	  <div align="center"><br />
        <span class="NormalHeading">Sales by Category (Excl. <?php echo $_SESSION["usrpref_taxtype"]; ?>)<br />
        <br />
</span></div>
      <table width="53%" border="0" align="center" cellpadding="2" cellspacing="0">
       
	   <?php while($row = mysql_fetch_array($result)) { ?>
	    <tr>
          <td width="60%" bgcolor="#F2F2F2" class="NormalText"><strong><?php echo $row["sbccategoryname"];?></strong></td>
          <td width="40%" class="NormalText"><div align="right"><?php echo $row["sbcamount"];?></div></td>
        </tr>
		<?php } ?>
      </table>
      <?php 
	  
	  $result = GetPaymentBreakdown($sumid);
	  
	  ?>
	  <div align="center"><br />
          <span class="NormalHeading">Payments Breakdown</span>      </div>
      <table width="100%" border="0" cellspacing="0" cellpadding="2">
        <tr>
          <td width="26%" bgcolor="#F2F2F2" class="NormalText"><div align="left"><strong>Payment Method </strong></div></td>
          <td width="19%" bgcolor="#F2F2F2" class="NormalText"><div align="right"><strong>Amount</strong></div></td>
          <td width="18%" bgcolor="#F2F2F2" class="NormalText"><div align="right"><strong>Charge Tips </strong></div></td>
          <td width="19%" bgcolor="#F2F2F2" class="NormalText"><div align="right"><strong>AutoGratuity</strong></div></td>
          <td width="18%" bgcolor="#F2F2F2" class="NormalText"><div align="right"><strong>Sales</strong></div></td>
        </tr>
       <?php while($row = mysql_fetch_array($result)) { ?>
	    <tr>
          <td class="NormalText"><?php echo $row["pbrpaymenttype"];?></td>
          <td><div align="right" class="NormalText"><?php echo $row["pbramount"];?></div></td>
          <td><div align="right" class="NormalText"><?php echo $row["pbrchargetips"];?></div></td>
          <td><div align="right" class="NormalText"><?php echo $row["pbrautogratuity"];?></div></td>
          <td><div align="right" class="NormalText"><?php echo $row["pbrsales"];?></div></td>
        </tr>
     <?php } ?>
	  </table>      
      <hr size="1" />      <p align="left" class="NormalText"><span class="NormalHeading">Calcuation Methods:</span><br />
        <strong>Gross Sales</strong> - Less Voids Surch. Order Charges Add Chgs<br />
        <strong>Nett Sales</strong> - Less Voids, Comps, Promos, Taxes, Surch. Order charges Add Chgs<br />
        <strong>Banking Sales</strong> -  Less Voids, Comps, Promos, Surch. Order Charges Add Chgs</p>
      <p align="left" class="NormalText"><span class="NormalHeading">Report Options 1 :</span><br />
          <strong>Gross Sales</strong> - Less Voids Surch. Order Charges Add Chgs<br />
          <strong>Nett Sales</strong> - Less Voids, Comps, Promos, Taxes, Surch. Order charges Add Chgs<br />
          <strong>Banking Sales</strong> -  Less Voids, Comps, Promos, Surch. Order Charges Add Chgs</p>
      <p align="left" class="NormalText"><span class="NormalHeading">Report Options 2 :</span><br />
          <strong>Gross Sales</strong> - Less Voids Surch. Order Charges Add Chgs<br />
          <strong>Nett Sales</strong> - Less Voids, Taxes<br />
          <strong>Banking Sales</strong> -  Less Voids, Comps, Promos, Surch. Order Charges Add Chgs</p>
      <div align="center">
		    <?php 
		 
		  ?>
		    
	        <?php
		  } else {?>
      </div>
	  <p align="center" class="NormalText"><span class="style2">Please select from the parameters above to view a report. </span></p></td>
        <?php } }?>
  </tr>
</table>
<br />
<div align="center"></div>
<div align="center"><img src="../images/report_footer.gif" width="950" height="60" /></div>
</body>
</html>
<?php db_close(); ?>

<script language="javascript1.2">
window.print(); 
setInterval("window.close()", 1000);

</script>