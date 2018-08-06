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
<title>Product Mix Report</title>
<link href="../style.css" rel="stylesheet" type="text/css" />
<style type="text/css">
<!--
.style5 {	color: #000000;
	font-weight: bold;
}
.style6 {color: #000000}
.style1 {color: #FFFFFF}
-->
</style>
</head>

<body>
<link href="../style.css" rel="stylesheet" type="text/css">
<div align="center"><br />
  
</div>
<div align="center"><img src="../images/report_header.gif" width="600" height="73" border="0" /></div>
<table width="600" height="753" border="0" align="center" cellpadding="5" cellspacing="0">
  
  <tr>
    <td width="720" height="1" valign="top"><p align="center" class="NormalHeading">
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

if($radDate == "date") {
	$result = GetSummaryTotals($sumid);
}
if($radDate == "daterange") {
	$result = GetSummaryTotalsWithDateRange($sumid);

}

$row = mysql_fetch_array($result);

	  ?>
      <div align="center">
        <table width="606" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="44">&nbsp;</td>
            <td width="530"><div align="center" class="NormalText"><span class="NormalHeading"><strong>Product Mix Report </strong></span><strong><br />
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
			?>
                <?php 
	
	   	if($_SESSION["dayofweek"] != "All Days") {
	echo " (".$_SESSION["dayofweek"]."s only)";
}
	   
	   ?>
</div></td>
            <td width="32">&nbsp;</td>
          </tr>
        </table>
        <hr size="1" />
        <span class="NormalHeading"> Summary</span><br />
        <br />
      </div>
      <br />
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
			        <table width="600" height="55" border="0" cellpadding="2" cellspacing="0" bgcolor="#487CC4">
        <tr>
          <td height="25" colspan="5" class="BreadCrumb"><?php echo ucfirst($row["ibrcategoryname"]);
		  
		  ?></td>
        </tr>
        <tr>
          <td height="5" colspan="5" bgcolor="#FFFFFF" class="BreadCrumb"><hr size="1" /></td>
          </tr>
        <tr>
          <td width="67" height="25" bgcolor="#F2F2F2" class="BreadCrumb"><span class="style5">Item PLU </span></td>
          <td width="228" bgcolor="#F2F2F2" class="BreadCrumb"><span class="style5">Item Name </span></td>
          <td width="96" bgcolor="#F2F2F2" class="BreadCrumb"><div align="right" class="style5"># Sold </div></td>
          <td width="94" bgcolor="#F2F2F2" class="BreadCrumb"><div align="right"><span class="style5">Price Sold </span></div></td>
          <td width="95" bgcolor="#F2F2F2" class="BreadCrumb"><div align="right" class="style5">Amount</div></td>
        </tr>
      </table>
			  
			  
<?php // ....................
		 
		 	 }
		 
		 /*
		 1. Get first Category Name and display header
		 2. For each item 
		    3. If Category has changed, show footer and clear category totals and show new header
			4. Show item

		 */
	$catname = ucfirst($row["ibrcategoryname"]); // SET NEW CATNAME
		 
	if($catname != $oldcatname) { // New Category has arrived
 	 ?>
	       <table width="600" border="0" cellspacing="0" cellpadding="2">
        <tr>
          <td width="12%" class="BreadCrumb style6">&nbsp;</td>
          <td width="38%" class="BreadCrumb"><div align="right" class="style6">Total</div></td>
          <td width="17%" bgcolor="#F2F2F2" class="BreadCrumb"><div align="right" class="style6"><strong><?php echo $itemsoldtotal; $finalsoldtotal = $finalsoldtotal + $itemsoldtotal; ?></strong></div></td>
          <td width="17%" bgcolor="#F2F2F2" class="BreadCrumb"><div align="right" class="style6"><strong><?php echo number_format($itempricesoldtotal,"2",".",""); ?></strong></div></td>
          <td width="16%" bgcolor="#F2F2F2" class="BreadCrumb"><div align="right" class="style6"><strong><?php echo number_format($itemamountotal,"2",".",""); $finalgrandtotal = $finalgrandtotal + $itemamountotal;?></strong></div></td>

	    </tr>
      </table>
	 
	<br />
	
	<table width="600" height="55" border="0" cellpadding="2" cellspacing="0" bgcolor="#487CC4">
        <tr>
          <td height="25" colspan="5" class="BreadCrumb"><?php echo ucfirst($row["ibrcategoryname"]);
 
		  ?></td>
        </tr>
        <tr>
          <td height="5" colspan="5" bgcolor="#FFFFFF" class="BreadCrumb"><hr size="1" /></td>
        </tr>
        <tr>
          <td width="69" height="25" bgcolor="#F2F2F2" class="BreadCrumb"><span class="style5">Item PLU </span></td>
          <td width="225" bgcolor="#F2F2F2" class="BreadCrumb"><span class="style5">Item Name </span></td>
          <td width="96" bgcolor="#F2F2F2" class="BreadCrumb"><div align="right" class="style5"># Sold </div></td>
          <td width="98" bgcolor="#F2F2F2" class="BreadCrumb"><div align="right"><span class="style5">Price Sold </span></div></td>
          <td width="92" bgcolor="#F2F2F2" class="BreadCrumb"><div align="right" class="style5">Amount</div></td>
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
      
      <table width="600" height="22" border="0" align="center" cellpadding="2" cellspacing="0">

	    <tr>
          <td width="63" height="22" class="NormalText"><div align="left"><?php echo $row["ibritenplu"];?></div>
            <div align="left"></div></td>
          <td width="230" class="NormalText"><div align="left"><?php echo $row["ibritemname"];?></div></td>
          <td width="96" class="NormalText"><div align="right"><?php echo $row["ibrnumsold"];?></div></td>
          <td width="97" class="NormalText"><div align="right"><?php echo number_format($row["ibramount"] / $row["ibrnumsold"],"2",".",""); ?></div></td>
          <td width="94" class="NormalText"><div align="right"><?php echo $row["ibramount"];?></div></td>

	    </tr>
      </table>

<?php 
	   // Count Totals
	   
	    $itemsoldtotal =  $itemsoldtotal + $row["ibrnumsold"];
		$itempricesoldtotal = $itempricesoldtotal + ($row["ibramount"] / $row["ibrnumsold"]);
	    $itemamountotal = $itemamountotal + $row["ibramount"];
		
	   } ?>
	   
	   	       <table width="600" border="0" cellspacing="0" cellpadding="2">
        <tr>
          <td width="12%" class="BreadCrumb style6">&nbsp;</td>
          <td width="39%" class="BreadCrumb"><div align="right" class="style6">Total</div></td>
          <td width="16%" bgcolor="#F2F2F2" class="BreadCrumb"><div align="right" class="style6"><strong><?php echo $itemsoldtotal; $finalsoldtotal = $finalsoldtotal + $itemsoldtotal;?></strong></div></td>
          <td width="17%" bgcolor="#F2F2F2" class="BreadCrumb"><div align="right" class="style6"><strong><?php echo number_format($itempricesoldtotal,"2",".",""); ?></strong></div></td>
          <td width="16%" bgcolor="#F2F2F2" class="BreadCrumb"><div align="right" class="style6"><strong><?php echo number_format($itemamountotal,"2",".",""); $finalgrandtotal = $finalgrandtotal + $itemamountotal;?></strong></div></td>
        </tr>

	  </table>
	   
	   
      <div align="center">

	<?php	  
$result = GetProductMixSummaryTotal($sumid);
$row = mysql_fetch_array($result);
$grandtotal = $row["ibramount"];
	
		$result = GetProductMixSummary($sumid);
		 if(mysql_num_rows($result) > 0) { // only display if there is data 
		
		?>  
	 
	             <br />
         <br />
	               <span class="NormalHeading">Summary <br />
	               <br />
      </span></div>
      <table width="600" border="0" align="center" cellpadding="3" cellspacing="0">
  <tr>
    <td width="226" bgcolor="#487CC4" class="BreadCrumb">Name</td>
    <td width="96" bgcolor="#487CC4" class="BreadCrumb"><div align="right" class="style1"># Sold</div></td>
    <td width="91" bgcolor="#487CC4" class="BreadCrumb"><div align="right" class="style1">Amount</div></td>
    <td width="103" bgcolor="#487CC4" class="BreadCrumb"><div align="right" class="style1">% Sales</div></td>
  </tr>
  <tr>
    <td colspan="4" class="NormalText"><hr size="1" /></td>
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




} 






?>
  <tr>
    <td class="NormalText"><div align="right"><strong>Total</strong></div></td>
    <td bgcolor="#F2F2F2" class="NormalText"><div align="right"><strong><?php echo number_format($soldtotal, 2, '.', '');?></strong></div></td>
    <td bgcolor="#F2F2F2" class="NormalText"><div align="right"><strong><?php echo number_format($amounttotal, 2, '.', '');?></strong></div></td>
    <td bgcolor="#F2F2F2" class="NormalText"><div align="right"><strong>100.00</strong></div></td>
  </tr>
</table>
      <br />
      <?php } 
	  
	  ?>
      <br />
               <br />
      <br />
      <hr size="1" />
      <p align="left" class="NormalText">&nbsp;</p>
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
<div align="center"><img src="../images/report_footer.gif" width="600" height="54" />
</div>
</body>
</html>
<?php db_close(); ?>

<script language="javascript1.2">
window.print(); 
setInterval("window.close()", 1000);

</script>