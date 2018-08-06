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
<title>Voids Report</title>
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
    <td width="590" height="1" valign="top"><p align="center" class="NormalHeading"><?php 
 
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
	  // ==============================================================================
	  // REPORT OUTPUT STARTS HERE ====================================================
	  // ==============================================================================
	  
	if($radDate == "date") {
	   $sumIDResult = GetSumIDforWithDateRange("'".$_SESSION["dateyear"]."/".$_SESSION["datemonth"]."/".$_SESSION["dateday"]."'", $_SESSION["store"]);
	}
	if($radDate == "daterange") {
	   $sumIDResult = GetSumIDforWithDateRange($_SESSION["daterangestring"], $_SESSION["store"]);
	}
	//  $row = mysql_fetch_array($sumIDResult);
	
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
            <td><div align="center" class="NormalText"><span class="NormalHeading"><strong>Voids Report</strong></span><strong><br />
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
   
      <br />
      <table width="98%" height="253" border="0" align="center" cellpadding="4" cellspacing="3" bordercolor="#F2F2F2">
        <tr>
          <td width="12%" bgcolor="#F2F2F2" class="NormalText"><strong>Check # </strong></td>
          <td width="15%" bgcolor="#F2F2F2" class="NormalText"><strong>Menu Item </strong></td>
          <td width="13%" bgcolor="#F2F2F2" class="NormalText"><strong>Reason</strong></td>
          <td width="20%" bgcolor="#F2F2F2" class="NormalText"><div align="left"><strong>Manager</strong></div></td>
          <td width="9%" bgcolor="#F2F2F2" class="NormalText"><div align="center"><strong>Time</strong></div></td>
          <td width="21%" bgcolor="#F2F2F2" class="NormalText"><strong>Server</strong></td>
          <td bgcolor="#F2F2F2" class="NormalText"><div align="right"><strong>Amt</strong></div></td>
        </tr>
       
	  <?php 
   
   
       $result = GetVoidsReport ($sumid);
       $voidtotal = 0;
	   $voidcounter = 0.00;
	   $showtotal = 0;
	   $showtotalmanager = 0;	   
	   $server = "null"; // Reset server
  	   $manager = "null"; // Reset manager
	   $voidmanagertotal = 0;
	   $voidcountermanager = 0.00;
	   $voidgrandtotal = 0;
	   $voidgrandcounter = 0;
	   
	   while($row = mysql_fetch_array($result)) {
       $showtotal = 0;

	     if($server == "null" && $manager == "null") { // Set first server and manager
	        $server = $row["vbrserver"];   
			$manager = $row["vbrmanager"]; 
		 }
   	     if($server != $row["vbrserver"]) {//Existing server has changed
		   $showtotal = 1;
		   $server = $row["vbrserver"];
		 }
   	     if($manager != $row["vbrmanager"]) {//Existing manager has changed

		   $showtotalmanager = 1;
		   $manager = $row["vbrmanager"];
		 }		 
		 

	  ?> 
<?php // SHOW SERVER TOTAL
  if($showtotal == 1) {
?>
	    <tr>
	      <td height="26" colspan="6" bgcolor="#F2F2F2" class="style3 style4"><div align="right">Total voids for <?php echo $prev_server ; ?> by <?php echo $prev_manager ; ?> (<?php echo $voidcounter; ?>) </div>
          <div align="center"></div></td>
	      <td width="10%" bgcolor="#F2F2F2" class="style5"><div align="right" class="SmallText"><?php echo number_format($voidtotal,2);
		  ?></div>
            <div align="right"></div></td>
        </tr>
<?php 
   $voidcounter = 0.00;
   $voidtotal = 0.00;
   }
?>
<?php // SHOW MANAGER TOTAL
  if($showtotalmanager == 1) {
?>
	    <tr>
	      <td height="9" colspan="7" class="style3 style4 style1"><hr size="1" /></td>
        </tr>
	    <tr>
	      <td height="26" colspan="6" bgcolor="#007CC4" class="style3 style4 style1"><div align="right" class="style1">Total voids for manager <?php echo $prev_manager ; ?> (<?php echo $voidcountermanager; ?>) </div>
            <div align="center"></div></td>
	      <td width="10%" bgcolor="#007CC4" class="BreadCrumb"><div align="right"><?php echo number_format($voidmanagertotal,2);
		  ?></div>
          <div align="right"></div></td>
        </tr>
	    <tr>
	      <td height="9" colspan="7" class="style3"><hr size="1" /></td>
        </tr>
<?php 
    $showtotalmanager = 0;
    $voidcountermanager = 0.00;
 // $voidtotalmanager = 0.00;
 // $voidcounter = 0.00;
    $voidtotal = 0.00;
  $voidmanagertotal = 0;
   }
?>

	    <tr>
	      <td height="26" class="style3"><?php echo $row["vbrcheckno"]; ?></td>
	      <td class="style3"><?php echo $row["vbrmenuitem"]; ?></td>
	      <td class="style3"><?php echo $row["vbrreason"]; ?></td>
	      <td class="style3"><?php echo $row["vbrmanager"]; ?></td>
	      <td class="style3"><div align="center"><?php echo $row["vbrtime"]; ?></div></td>
	      <td class="style3"><?php echo $row["vbrserver"]; ?></td>
	      <td class="style3"><div align="right"><?php echo $row["vbramount"]; ?></div></td>
        </tr>

      <?php 
	  $voidtotal = $voidtotal + $row["vbramount"];
      $voidmanagertotal = $voidmanagertotal + $row["vbramount"];
	  $voidgrandtotal = $voidgrandtotal + $row["vbramount"];
	  
	//  echo $voidtotal;
	  $voidcounter = $voidcounter + 1;
	  $voidcountermanager = $voidcountermanager + 1;
	  $voidgrandcounter = $voidgrandcounter + 1;
	  $prev_server = $row["vbrserver"];
	  $prev_manager = $row["vbrmanager"];
	  
	  } ?>
<?php 
 // SHOWS FINAL SERVER FOR PAGE
?>
	    <tr>
	      <td height="26" colspan="6" bgcolor="#F2F2F2" class="style3 style4"><div align="right">Total voids for <?php echo $prev_server ; ?> by <?php echo $prev_manager ; ?> (<?php echo $voidcounter; ?>) </div>
          <div align="center"></div></td>
	      <td width="10%" bgcolor="#F2F2F2" class="style5"><div align="right"><div align="right"><?php echo number_format($voidtotal,2);
		  ?></div>
          <div align="right"></div></td>
        </tr>
<?php 
 
?>
<?php 
 // SHOWS FINAL MANAGER FOR PAGE
?>
	    <tr>
	      <td height="9" colspan="7" class="style3 style4 style1"><hr size="1" /></td>
        </tr>
	    <tr>
	      <td height="26" colspan="6" bgcolor="#007CC4" class="style3 style4 style1"><div align="right" class="style1">Total voids for manager <?php echo $prev_manager ; ?> (<?php echo $voidcountermanager; ?>) </div>
            <div align="center"></div></td>
	      <td width="10%" bgcolor="#007CC4" class="BreadCrumb style1"><div align="right"><?php echo number_format($voidmanagertotal,2);
		  ?></div>
          <div align="right"></div></td>
        </tr>
<?php 
 
?>
		    <tr>
		      <td colspan="7" class="style3"><hr size="1" /></td>
        </tr>
		    <tr>
		      <td colspan="5" class="style3"><div align="left"><strong>Voids Grand Total </strong></div></td>
              <td class="style3"><div align="right"><strong>(<?php echo $voidgrandcounter; ?>)</strong></div></td>
              <td class="style3"><div align="right"><strong><?php echo number_format($voidgrandtotal,2);
		  ?></strong></div></td>
	    </tr>
	  </table>
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