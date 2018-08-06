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
<title>Comps Report</title>
<link href="../style.css" rel="stylesheet" type="text/css" />
<style type="text/css">
<!--
.style2 {	color: #006600;
	font-weight: bold;
}
.style3 {font-size: 12px; line-height: normal; color: #333333; text-decoration: none; font-family: Verdana, Arial, Helvetica, sans-serif;}
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
	   $result = GetSummaryTotals($dateyear."/".$datemonth."/".$dateday, $store);
	   $sumIDResult = GetSumIDforWithDateRange("'".$dateyear."/".$datemonth."/".$dateday."'", $store);
	}
	if($radDate == "daterange") {
	   $result = GetSummaryTotalsWithDateRange($daterangestring, $store);
	   $sumIDResult = GetSumIDforWithDateRange($daterangestring, $store);
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
	  ?></p>
      <div align="center">
        <hr size="1" />
	    <?php 
	  // REPORT OUTPUT STARTS HERE
	  
	if($radDate == "date") {
	   $result = GetSummaryTotals($_SESSION["dateyear"]."/".$_SESSION["datemonth"]."/".$_SESSION["dateday"], $_SESSION["store"]);
	   $sumIDResult = GetSumIDforWithDateRange("'".$_SESSION["dateyear"]."/".$_SESSION["datemonth"]."/".$_SESSION["dateday"]."'", $_SESSION["store"]);
	}
	if($radDate == "daterange") {
	   $result = GetSummaryTotalsWithDateRange($_SESSION["daterangestring"], $_SESSION["store"]);
	   $sumIDResult = GetSumIDforWithDateRange($_SESSION["daterangestring"], $_SESSION["store"]);
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
        <div align="center">
          <table width="428" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td><div align="center" class="NormalText"><span class="NormalHeading"><strong>Comps Report</strong></span><strong><br />
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
              </div></td>
            </tr>
          </table>
          <hr size="1" />
          <span class="NormalHeading"> Summary</span><br />
          <br />
        </div>
        <?php 
   if($radStores == 'store' && ($radDate == 'date' || $radDate == 'daterange')) {?>
        <table width="71%" border="0" align="center" cellpadding="0" cellspacing="0">
          <tr>
            <td bgcolor="#F2F2F2" class="NormalText"><strong>Comp Type </strong></td>
            <td bgcolor="#F2F2F2" class="NormalText"><div align="right"><strong>Qty</strong></div></td>
            <td bgcolor="#F2F2F2" class="NormalText"><div align="right"><strong>Amount</strong></div></td>
            <td bgcolor="#F2F2F2" class="NormalText"><div align="right"><strong>% Total </strong></div></td>
          </tr>
          <?php 
	 	$qty = 0;
		$amount = 0;
		
	   $result = GetCompsSummary($sumid); 
	   while($row = mysql_fetch_array($result)) {
	   $qty = $qty + intval($row["cbrcompcount"]); 
	   $amount = $amount + floatval($row["cbrcompamount"]); 
	  ?>
          <tr>
            <td width="41%" class="style3"><?php echo $row["compname"]; ?></td>
            <td width="16%" class="NormalText"><div align="right"><?php echo $row["cbrcompcount"]; ?></div></td>
            <td width="20%" class="NormalText"><div align="right"><?php echo $row["cbrcompamount"]; ?></div></td>
            <td width="23%" class="NormalText"><div align="right"><?php echo $row["cbrcomppercentage"]; ?></div></td>
          </tr>
          <?php } ?>
          <tr>
            <td colspan="4" class="style3"><hr size="1" /></td>
          </tr>
          <tr>
            <td class="style3"><strong>Totals</strong></td>
            <td class="NormalText"><div align="right"><strong><?php echo $qty; ?></strong></div></td>
            <td class="NormalText"><div align="right"><strong><?php printf("%.2f",$amount); ?></strong></div></td>
            <td class="NormalText"><div align="right"><strong>100%</strong></div></td>
          </tr>
        </table>
        <?php } ?>
        <?php if($radStores == 'storegroup' || $radDate == 'daterange') {?>
        <table width="71%" border="0" align="center" cellpadding="0" cellspacing="0">
          <tr>
            <td bgcolor="#F2F2F2" class="NormalText"><strong>Comp Type </strong></td>
            <td bgcolor="#F2F2F2" class="NormalText"><div align="right"><strong>Qty</strong></div></td>
            <td bgcolor="#F2F2F2" class="NormalText"><div align="right"><strong>Amount</strong></div></td>
          </tr>
          <?php 
	 	$qty = 0;
		$amount = 0;
		
	   $result = GetCompsSummary($sumid); 
	   while($row = mysql_fetch_array($result)) {
	   $qty = $qty + intval($row["cbrcompcount"]); 
	   $amount = $amount + floatval($row["cbrcompamount"]); 
	  ?>
          <tr>
            <td width="49%" class="style3"><?php echo $row["compname"]; ?></td>
            <td width="26%" class="NormalText"><div align="right"><?php echo $row["cbrcompcount"]; ?></div></td>
            <td width="25%" class="NormalText"><div align="right"><?php echo $row["cbrcompamount"]; ?></div></td>
          </tr>
          <?php } ?>
          <tr>
            <td colspan="3" class="style3"><hr size="1" /></td>
          </tr>
          <tr>
            <td class="style3"><strong>Totals</strong></td>
            <td class="NormalText"><div align="right"><strong><?php echo $qty; ?></strong></div></td>
            <td class="NormalText"><div align="right"><strong><?php printf("%.2f",$amount); ?></strong></div></td>
          </tr>
        </table>
        <?php } ?>
        <br />
        <br />
        <div align="center">
          <?php 
		 
		  ?>
          <?php
		  } else {?>
        </div>
        <p align="center" class="NormalText"><br />
            <span class="style2">No results were returned for that query.<br />
              Please try different parameters. </span></p>
      </div>
    </td>
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