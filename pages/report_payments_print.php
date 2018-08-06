<?php 
session_start();
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
<title>Payments Report</title>
<link href="../style.css" rel="stylesheet" type="text/css" />
<style type="text/css">
<!--
.style1 {font-weight: bold}
.style2 {font-size: 12px; line-height: normal; color: #333333; text-decoration: none; font-family: Verdana, Arial, Helvetica, sans-serif;}
.style3 {font-weight: bold}
-->
</style>
</head>

<body>
<link href="../style.css" rel="stylesheet" type="text/css">
<div align="center"><br />
  
</div>
<div align="center"><img src="../images/report_header.gif" width="600" height="73" border="0" /></div>
<table width="600" height="462" border="0" align="center" cellpadding="5" cellspacing="0">
  
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
	 // ******************************************************************
  
	if($radDate == "date") {
	   $result = GetSummaryTotals($_SESSION["dateyear"]."/".$_SESSION["datemonth"]."/".$_SESSION["dateday"], $_SESSION["store"]);
	   $sumIDResult = GetSumIDforWithDateRange("'".$_SESSION["dateyear"]."/".$_SESSION["datemonth"]."/".$_SESSION["dateday"]."'", $_SESSION["store"]);
	}
	if($radDate == "daterange") {
	   $result = GetSummaryTotalsWithDateRange($_SESSION["daterangestring"], $_SESSION["store"]);
	   $sumIDResult = GetSumIDforWithDateRange($_SESSION["daterangestring"], $_SESSION["store"]);
	}
	  $row = mysql_fetch_array($result);
	

     if(mysql_num_rows($sumIDResult) > 0) {
	    $sumidrow = mysql_fetch_array($sumIDResult);
	    $sumid = "'".$sumidrow["sumid"]."'";
	 }
	 while($sumidrow = mysql_fetch_array($sumIDResult)) {  
	  $sumid = $sumid.",'".$sumidrow["sumid"]."'";
     }
	  

	  ?>
      <div align="center">
        <table width="428" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="396"><div align="center" class="NormalText"><span class="NormalHeading"><strong>Payments Report</strong></span><strong><br />
                for<br />
                </strong>
				<?php 
				if($radStores == "store") {
				  echo GetStoreName($_SESSION["store"]); 
				 
				}
				if($radStores == "storegroup") {
				  echo GetStoreGroupName($grpid); 
				 
				}				
				?><br />
                <strong>for date </strong> <br />
               <?php 
			if($radDate == "date") {
			  echo $_SESSION["dateday"]."/".$_SESSION["datemonth"]."/".$_SESSION["dateyear"];
			 $excelreport_title = $excelreport_title." for date ".$_SESSION["dateday"]."/".$_SESSION["datemonth"]."/".$_SESSION["dateyear"]; // EXCEL
			}
			if($radDate == "daterange") {
			  echo $_SESSION["datefromday"]."/".$_SESSION["datefrommonth"]."/".$_SESSION["datefromyear"]." to ".$_SESSION["datetoday"]."/".$_SESSION["datetomonth"]."/".$_SESSION["datetoyear"];
			  $excelreport_title = $excelreport_title." for date ".$_SESSION["datefromday"]."/".$_SESSION["datefrommonth"]."/".$_SESSION["datefromyear"]." to ".$_SESSION["datetoday"]."/".$_SESSION["datetomonth"]."/".$_SESSION["datetoyear"]; // EXCEL
			}		
			?>       <?php 
	  
	   
	   
	   ?>
            </div></td>
            <td width="32">&nbsp;</td>
          </tr>
        </table>
        <hr size="1" />
        <span class="NormalHeading"> Payments</span><span class="NormalHeading"><br />
      </span></div>
   
      <div align="center"></div>
      <table width="100%" border="0" align="center" cellpadding="5" cellspacing="0">
        <tr>
          <td colspan="7" class="NormalText"><span class="NormalHeading">
<?php 
	      /// -------------------- PAYMENTS  --------------------------
		 $result = GetPayments($sumid); 

         

	  
	 	
	?>	

          <br />
          </span></td>
        </tr>
       
<?php 

// Initialize vars
 $oldcategoryname = "";
 $categoryname = "";
 $totalqty = 0;
 $totalamount = 0;
 $totalgrat = 0;
 $totaltip = 0;
 $totaltotal = 0;

 while($row = mysql_fetch_array($result)) {

//Set category name if none set yet

if($oldcategoryname == "") {
   $oldcategoryname = $row["pmttype"];
   $categoryname = $row["pmttype"];  
   $firstcat = true;
}
$categoryname = $row["pmttype"];
if($categoryname <> $oldcategoryname || $firstcat == true) { // Write Header if new
  
 
    if($firstcat != true) {
  ?>
        	<tr>
		      <td height="31" class="NormalText"><strong>Total</strong></td>
		      <td height="31" bgcolor="#F2F2F2" class="NormalText"><div align="right"><strong><?php echo $totalqty; $totalqty = 0;?></strong></div></td>
		      <td height="31" bgcolor="#F2F2F2" class="NormalText"><div align="right"><strong><?php echo number_format($totalamount, 2, '.', ''); $totalamount = 0;?></strong></div></td>
		      <td height="31" bgcolor="#F2F2F2" class="NormalText"><div align="right"><strong><?php echo number_format($totalgrat, 2, '.', '');  $totalgrat = 0;?></strong></div></td>
		      <td height="31" bgcolor="#F2F2F2" class="NormalText"><div align="right"><strong><?php echo number_format($totaltip, 2, '.', ''); $totaltip = 0;?></strong></div></td>
		      <td height="31" bgcolor="#F2F2F2" class="NormalText"><div align="right"><strong><?php echo number_format($totaltotal, 2, '.', ''); $totaltotal = 0;?></strong></div></td>
		      <td height="31" class="NormalText">&nbsp;</td>
        </tr>
  <tr>
	            	  <td height="31" class="style3">&nbsp;</td>
	            	  <td height="31" class="style3">&nbsp;</td>
	            	  <td height="31" class="style3">&nbsp;</td>
	            	  <td height="31" class="style3">&nbsp;</td>
	            	  <td height="31" class="style3">&nbsp;</td>
	            	  <td height="31" class="style3">&nbsp;</td>
	            	  <td height="31" class="style3">&nbsp;</td>
   	    </tr>
  <?php
  
  }
  
    $oldcategoryname = $categoryname;
	echo "<tr><td colspan='7' bgcolor='#007CC4' class='BreadCrumb'>".$row["pmttype"]."</td></tr>";
   

?>
        <tr>
          <td bgcolor="#F2F2F2" class="NormalText"><strong>Check #</strong></td>
          <td bgcolor="#F2F2F2" class="NormalText"><div align="right"><strong>Qty</strong></div></td>
          <td bgcolor="#F2F2F2" class="NormalText"><div align="right"><strong>Amount</strong></div></td>
          <td bgcolor="#F2F2F2" class="NormalText"><div align="right"><strong>Grat</strong></div></td>
          <td bgcolor="#F2F2F2" class="NormalText"><div align="right"><strong>Tip</strong></div></td>
          <td bgcolor="#F2F2F2" class="NormalText"><div align="right"><strong>Total</strong></div></td>
          <td bgcolor="#F2F2F2" class="NormalText"><div align="left"><strong> Employee </strong></div></td>
        </tr>

<?php 
} // end writing header
	  ?> 
	    <tr>
          <td width="11%" class="NormalText"><?php echo $row["checknum"]; ?></td>
          <td width="6%" class="NormalText"><div align="right"><?php echo $row["qty"]; $totalqty = $totalqty + $row["qty"];?></div></td>
          <td width="13%" class="NormalText"><div align="right"><?php echo $row["amount"]; $totalamount = $totalamount + $row["amount"];?></div></td>
          <td width="13%" class="NormalText"><div align="right"><?php echo $row["grat"]; $totalgrat = $totalgrat + $row["grat"];?></div></td>
          <td width="13%" class="NormalText"><div align="right"><?php echo $row["tip"]; $totaltip = $totaltip + $row["tip"];?></div></td>
          <td width="12%" class="NormalText"><div align="right"><?php echo $row["total"]; $totaltotal = $totaltotal + $row["total"];?></div></td>
          <td width="32%" class="NormalText"><div align="left"><?php  
		  if(strlen($row["emp"]) > 35) {
		     echo substr($row["emp"], 0, 35)."..."; // shorten
		  } else {
		    echo $row["emp"];
		  }
		 $firstcat = false;
		  ?></div></td>
        </tr>
      

          
          
      <?php
	         
	   
	   
	   } 
	 
	   ?>
	            	<tr>
		      <td height="31" class="NormalText"><strong>Total</strong></td>
		      <td height="31" bgcolor="#F2F2F2" class="NormalText"><div align="right"><strong><?php echo $totalqty; $totalqty = 0;?></strong></div></td>
		      <td height="31" bgcolor="#F2F2F2" class="NormalText"><div align="right"><strong><?php echo number_format($totalamount, 2, '.', ''); $totalamount = 0;?></strong></div></td>
		      <td height="31" bgcolor="#F2F2F2" class="NormalText"><div align="right"><strong><?php echo number_format($totalgrat, 2, '.', '');  $totalgrat = 0;?></strong></div></td>
		      <td height="31" bgcolor="#F2F2F2" class="NormalText"><div align="right"><strong><?php echo number_format($totaltip, 2, '.', ''); $totaltip = 0;?></strong></div></td>
		      <td height="31" bgcolor="#F2F2F2" class="NormalText"><div align="right"><strong><?php echo number_format($totaltotal, 2, '.', ''); $totaltotal = 0;?></strong></div></td>
		      <td height="31" class="NormalText">&nbsp;</td>
        </tr>
	            	
      </table>
	  <p align="center"><span class="NormalHeading">Payment Summary</span><br />
        <?php 
$resulttotals = GetPaymentSummaryTotals($sumid);
$rowtotals = mysql_fetch_array($resulttotals);

$summarytotal = $rowtotals["total"];


$result = GetPaymentsSummary($sumid);


?>
      </p>
	  <table width="100%" border="0" cellpadding="5" cellspacing="0">
        <tr>
          <td bgcolor="#F2F2F2" class="NormalText"><strong>Payment Type</strong></td>
          <td bgcolor="#F2F2F2" class="NormalText"><div align="right"><strong>Qty</strong></div></td>
          <td bgcolor="#F2F2F2" class="NormalText"><div align="right"><strong>Amount</strong></div></td>
          <td bgcolor="#F2F2F2" class="NormalText"><div align="right"><strong>Grat</strong></div></td>
          <td bgcolor="#F2F2F2" class="NormalText"><div align="right"><strong>Tip</strong></div></td>
          <td bgcolor="#F2F2F2" class="NormalText"><div align="right"><strong>Total</strong></div></td>
          <td bgcolor="#F2F2F2" class="NormalText"><div align="right"><strong>% Total</strong></div></td>
        </tr>
  <?php 
  
  $qty = 0;
  $amount = 0;
  $grat = 0;
  $tip = 0;
  $total = 0;
    
  while($row = mysql_fetch_array($result)) { ?>
        <tr>
          <td class="NormalText"><?php echo $row["pmttype"]; ?></td>
          <td class="NormalText"><div align="right"><?php echo $row["qty"]; $qty = $qty + $row["qty"]; ?></div></td>
          <td class="NormalText"><div align="right"><?php echo $row["amount"];  $amount= $amount + $row["amount"];?></div></td>
          <td class="NormalText"><div align="right"><?php echo $row["grat"]; $grat = $grat + $row["grat"]; ?></div></td>
          <td class="NormalText"><div align="right"><?php echo $row["tip"];  $tip = $tip + $row["tip"];?></div></td>
          <td class="NormalText"><div align="right"><?php echo $row["total"];  $total = $total + $row["total"];?></div></td>
          <td class="NormalText"><div align="right"><?php echo number_format(($row["total"]/$summarytotal) * 100, 2, '.', ''); ?></div></td>
        </tr>

   <?php }    ?>
        <tr>
          <td class="NormalText"><strong>Total Summary</strong></td>
          <td bgcolor="#F2F2F2" class="NormalText"><div align="right"><strong><?php echo $qty; ?></strong></div></td>
          <td bgcolor="#F2F2F2" class="NormalText"><div align="right"><strong><?php echo $amount; ?></strong></div></td>
          <td bgcolor="#F2F2F2" class="NormalText"><div align="right"><strong><?php echo $grat; ?></strong></div></td>
          <td bgcolor="#F2F2F2" class="NormalText"><div align="right"><strong><?php echo $tip; ?></strong></div></td>
          <td bgcolor="#F2F2F2" class="NormalText"><div align="right"><strong><?php echo $total; ?></strong></div></td>
          <td bgcolor="#F2F2F2" class="NormalText"><div align="right"><strong><?php echo "100.00"; ?></strong></div></td>
        </tr>
      </table>
	  <p align="center"><span class="NormalHeading">Comps Breakdown</span><br />
	    
        <?php 
		// -------------------------- COMPS BREAKDOWN --------------------------------
		

		
		// LOGIC
		// 1. Split by chcompname
		// 2. Split by chchecknum
		// 3. List ciitemname
		 $resulttotal =  GetCompsSummaryGrandTotal($sumid);
 $rowtotal = mysql_fetch_array($resulttotal);
		
		$result = GetCompsBreakdown($sumid);
		
				
				
		
		?>
        <br />
	  </p>
	  <table width="100%" border="0" cellpadding="2">
<?php 
$catname = "";
$oldcatname = "";
$firstcat = "";

$checknumber = "";
$oldchecknumber = "";
$firstcheck = "";

$checkqty = 0;
$checkamount = 0;
$checkpercent = 0;

while($row = mysql_fetch_array($result)) { // MAIN LOOP

if($oldcatname == "") { // Set first cat
   $oldcatname = $row["chcompname"];
   $catname = $row["chcompname"];  
   $firstcat = true;
}
$catname = $row["chcompname"]; // Set current cat.
// Write Cat header if required ------------------
	if($catname <> $oldcatname && $firstcat == false) { 
		?>
        <tr>
          <td height="16" colspan="9" class="NormalText"><hr size="1" /></td>
        </tr>
        <tr>
		  <td colspan="3" class="NormalText"><div align="left"><strong><?php echo $oldcatname." "; ?>Total</strong></div></td>
		  <td class="NormalText">&nbsp;</td>
		  <td bgcolor="#F2F2F2" class="NormalText"><div align="right"><strong><?php echo $checkqty; $checkqty = 0;?></strong></div></td>
		  <td bgcolor="#F2F2F2" class="NormalText"><div align="right"><strong><?php echo number_format($checkamount, 2, '.', ''); $checkamount = 0;?></strong></div></td>
		  <td bgcolor="#F2F2F2" class="NormalText"><div align="right"><strong><?php echo number_format($checkpercent, 2, '.', '') ; $checkpercent = 0;?></strong></div></td>
	      <td class="NormalText">&nbsp;</td>
	      <td class="NormalText">&nbsp;</td>
		</tr>
            <tr>
          <td height="20" colspan="9" class="NormalText"><hr size="1" /></td>
        </tr>
           <?php 
	 }
	if($catname <> $oldcatname || $firstcat == true) { 
	?>
			<tr>
			  <td height="31" colspan="9" bgcolor="#007CCF" class="BreadCrumb"><?php echo $row["chcompname"]; ?></td>
			</tr>

	<?php 
	  $firstcat = false;
	  $oldcatname = $catname;
	}
// Check Number line ------------------------------------------------
	if($oldchecknumber == "") { // Set first check
	   $oldchecknumber = $row["chchecknum"];
	   $checknumber = $row["chchecknum"];  
	   $firstcheck = true;
	}
	$checknumber = $row["chchecknum"]; // Set current cat.
	

		if($checknumber <> $oldchecknumber || $firstcheck == true) { 
		?>
	        <tr>
	          <td height="21" colspan="9" class="NormalText">&nbsp;</td>
        </tr>
	        <tr>
          <td width="9%" height="21" bgcolor="#F2F2F2" class="NormalText"><strong>Chk#</strong></td>
          <td width="7%" bgcolor="#F2F2F2" class="NormalText"><div align="center"><strong>Time</strong></div></td>
          <td width="22%" bgcolor="#F2F2F2" class="NormalText"><strong>Name</strong></td>
          <td width="7%" bgcolor="#F2F2F2" class="NormalText"><div align="center"><strong>Unit</strong></div></td>
          <td width="6%" bgcolor="#F2F2F2" class="NormalText"><div align="right"><strong>Qty</strong></div></td>
          <td width="8%" bgcolor="#F2F2F2" class="NormalText"><div align="right"><strong>Amount</strong></div></td>
          <td width="8%" bgcolor="#F2F2F2" class="NormalText"><div align="right"><strong>% Tot</strong></div></td>
          <td width="15%" bgcolor="#F2F2F2" class="NormalText"><strong>Employee</strong></td>
          <td width="18%" bgcolor="#F2F2F2" class="NormalText"><strong>Manager</strong></td>
        </tr>
		<tr>
			  <td bgcolor="#F2F2F2" class="NormalText"><?php echo $row["chchecknum"]; ?></td>
			  <td bgcolor="#F2F2F2" class="NormalText"><div align="center"><?php echo $row["chtime"]; ?></div>		      </td>
			  <td bgcolor="#F2F2F2" class="NormalText"><?php echo $row["chcustname"]; ?></td>
			  <td bgcolor="#F2F2F2" class="NormalText"><div align="right"><?php echo $row["chunit"]; ?></div></td>
			  <td bgcolor="#F2F2F2" class="NormalText"><div align="right"><?php echo $row["chquantity"]; $checkqty = $checkqty + $row["chquantity"] ; ?></div></td>
			  <td bgcolor="#F2F2F2" class="NormalText"><div align="right"><?php echo $row["chamount"]; $checkamount = $checkamount + $row["chamount"] ;?></div></td>
			  <td bgcolor="#F2F2F2" class="NormalText"><div align="right">
			    <?php
			    if($rowtotal["amount"] > 0) {
				$pertemp = ($row["chamount"]/$rowtotal["amount"])*100; 
				echo number_format(($row["chamount"]/$rowtotal["amount"])*100, 2, '.', '');; 
				$checkpercent = $checkpercent + $pertemp ;
				} else {
				echo '0.00';
				}
				?>
		  </div></td>
			  <td bgcolor="#F2F2F2" class="NormalText"><?php echo $row["chemployee"]; ?></td>
			  <td bgcolor="#F2F2F2" class="NormalText"><?php echo $row["chmanager"]; ?></td>
		</tr>
		
        <?php 
				$oldchecknumber = $checknumber;
		$firstcheck = false;
		} // Check number line -----------------------
		?>
        

		<tr>
		  <td class="NormalText">&nbsp;</td>
		  <td colspan="2" class="NormalText"><?php echo $row["ciitemname"]; ?></td>
		  <td colspan="2" class="NormalText"><div align="right"><?php echo $row["ciitemamount"]; ?></div></td>
		  <td class="NormalText">&nbsp;</td>
		  <td colspan="3" class="NormalText">&nbsp;</td>
	    </tr>

		
		<?php


} // MAIN LOOP
?>
            <tr>
          <td height="16" colspan="9" class="NormalText"><hr size="1" /></td>
        </tr>
        <tr>
		  <td colspan="3" class="NormalText"><div align="left"><strong><?php echo $oldcatname." "; ?>Total</strong></div></td>
		  <td class="NormalText">&nbsp;</td>
		  <td bgcolor="#F2F2F2" class="NormalText"><div align="right"><strong><?php echo $checkqty; $checkqty = 0;?></strong></div></td>
		  <td bgcolor="#F2F2F2" class="NormalText"><div align="right"><strong><?php echo number_format($checkamount, 2, '.', ''); $checkamount = 0;?></strong></div></td>
		  <td bgcolor="#F2F2F2" class="NormalText"><div align="right"><strong><?php echo number_format($checkpercent, 2, '.', '') ; $checkpercent = 0;?></strong></div></td>
	      <td class="NormalText">&nbsp;</td>
	      <td class="NormalText">&nbsp;</td>
		</tr>
            <tr>
          <td height="20" colspan="9" class="NormalText"><hr size="1" /></td>
        </tr>
      </table>
	
	  <div align="center">
        <?php 
  
  // COMPS BREAKDOWN DONE
 $resulttotal =  GetCompsSummaryGrandTotal($sumid);
 $rowtotal = mysql_fetch_array($resulttotal);
 
 
  // COMP SUMMARY ----------------------------------------------------
  $result = GetCompsSummaryTotals($sumid);
 
  ?>
	    <br />
        <span class="NormalHeading">Comp Summary</span><br />
        <br />
        <table width="66%" border="0" cellpadding="5" cellspacing="0">
          <tr>
            <td width="53%" bgcolor="#007CCF" class="NormalText"><span class="style1"><strong>Comp Type</strong></span></td>
            <td width="12%" bgcolor="#007CCF" class="NormalText"><div align="right" class="style1"><strong>Qty</strong></div></td>
            <td width="16%" bgcolor="#007CCF" class="NormalText"><div align="right" class="style1"><strong>Amount</strong></div></td>
            <td width="19%" bgcolor="#007CCF" class="NormalText"><div align="right" class="style1"><strong>% Total</strong></div></td>
          </tr>
  <?php 
  
  $qty = 0;
  $amount = 0;
  $totalpercent = 0;
  while($row = mysql_fetch_array($result)) { ?>
          <tr>
            <td class="NormalText"><?php echo $row["chcompname"];?></td>
            <td class="NormalText"><div align="right"><?php echo $row["qty"]; $qty = $qty + $row["qty"];?></div></td>
            <td class="NormalText"><div align="right"><?php echo number_format($row["amount"], 2, '.', ''); $amount = $amount + $row["amount"];?></div></td>
            <td class="NormalText"><div align="right">
              <?php 
			 if($rowtotal["amount"] > 0) {
			echo number_format(($row["amount"]/$rowtotal["amount"])*100, 2, '.', ''); 
			} else {
			echo '0.00';
			}
			?>
            </div></td>
          </tr>

   <?php } ?>  
                <tr>
            <td bgcolor="#F2F2F2" class="NormalText"><strong>Total</strong></td>
            <td bgcolor="#F2F2F2" class="NormalText"><div align="right"><strong><?php echo $qty;?></strong></div></td>
            <td bgcolor="#F2F2F2" class="NormalText"><div align="right"><strong><?php echo number_format($amount, 2, '.', '');?></strong></div></td>
            <td bgcolor="#F2F2F2" class="NormalText"><div align="right"><strong><?php echo "100.00"?></strong></div></td>
          </tr>
        </table>
 <?php 
   // END COMP SUMMARY ----------------------------------------------------
 
 ?>
        <br />
        <table width="100%" border="0" cellspacing="0" cellpadding="5">
          <tr>
            <td><div align="center"></div></td>
          </tr>
        </table>
	  <br />


      
      
	  <?php
	
		  
		  } else {?>
      </div>
      <p align="center" class="NormalText"><br />
        <span class="style2">No results were returned for that query.<br />
        Please try different parameters. </span></p></td>
    <?php } }?>
  </tr>
</table>
<br />
<div align="center"></div>
<div align="center"><img src="../images/report_footer.gif" width="600" height="54" />
</div>
</body>
</html>
<?php db_close(); ?>

<script language="javascript1.2">
window.print(); 
setInterval("window.close()", 1000);

</script>