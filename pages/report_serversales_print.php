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
<title>Employee Sales Report</title>
<link href="../style.css" rel="stylesheet" type="text/css" />
<style type="text/css">
<!--
.style3 {font-size: 12px; line-height: normal; color: #333333; text-decoration: none; font-family: Verdana, Arial, Helvetica, sans-serif;}
-->
</style>
</head>

<body>
<link href="../style.css" rel="stylesheet" type="text/css">
<div align="center"><br />
  
</div>
<div align="center"><img src="../images/report_header.gif" width="600" height="73" border="0" /></div>
<table width="600" height="736" border="0" align="center" cellpadding="5" cellspacing="0">
  
  <tr>
    <td width="590" height="736" valign="top"><p align="center" class="NormalHeading"><br />
        <?php 
 
 // -------------- See if any data is in Summary Table ----------------------- 

$a2 = 's';
 if ($a2 == 's') {
  $dataavailable = true;
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
	
 // TODO : GET SUMID TO PROPERLY LIST ALL SUMIDs
     if(mysql_num_rows($sumIDResult) > 0) {
	    $sumidrow = mysql_fetch_array($sumIDResult);
	    $sumid = "'".$sumidrow["sumid"]."'";
	 }
	 while($sumidrow = mysql_fetch_array($sumIDResult)) {  
	  $sumid = $sumid.",'".$sumidrow["sumid"]."'";
     }
	  

	  ?>
</p>
      <div align="center">
        <table width="590" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="590"><div align="center" class="NormalText"><span class="NormalHeading"><strong>Employees Sales Report</strong></span><strong><br />
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
        <br />
      </div>
      <?php 
			
		   

		   while (list ($key,$val) = @each ($_SESSION["chkemployees"])) {
			 $result = GetEmployee($sumid,$val);
		     $row = mysql_fetch_array($result);
		   
	?>
<br />
      <br />
      <span class="NormalHeading">Employee</span><span class="NormalText"> : <strong class="NormalHeading">
      <?php 
	  echo trim($row["empname"]." ".$row["empsurname"]);
	  ?>
      </strong></span><br />
      <br />
      <table width="100%" border="0" align="center" cellpadding="2" cellspacing="0">
        <tr>
          <td bgcolor="#007BC4" class="BreadCrumb"><strong>Category </strong></td>
          <td bgcolor="#007BC4" class="BreadCrumb"><div align="right">Net Sales</div></td>
          <td bgcolor="#007BC4" class="BreadCrumb"><div align="right">Comps</div></td>
          <td bgcolor="#007BC4" class="BreadCrumb"><div align="right">Promos</div></td>
          <td bgcolor="#007BC4" class="BreadCrumb"><div align="right">Taxes</div></td>
          <td bgcolor="#007BC4" class="BreadCrumb"><div align="right">Gross Sales</div></td>
        </tr>
        <?php 
	   $result = GetEmployeeSales($sumid,$val);
	  $totalnetsales = 0;
	  $totalcomps = 0;
	  $totalpromos = 0;
	  $totaltaxs = 0;
	  $totalgross = 0;
	   while($row = mysql_fetch_array($result)) {
	   
	   ?>
        <tr>
          <td width="31%" class="style3"><?php echo $row["esbccatname"]; ?></td>
          <td width="14%" class="NormalText"><div align="right" class="style3"><?php echo $row["esbcnetsales"]; ?></div></td>
          <td width="13%" class="NormalText"><div align="right" class="style3"><?php echo $row["esbccomps"]; ?></div></td>
          <td width="13%" class="NormalText"><div align="right" class="style3"><?php echo $row["esbcpromos"]; ?></div></td>
          <td width="14%" class="NormalText"><div align="right" class="style3"><?php echo $row["esbctaxes"]; ?></div></td>
          <td width="15%" class="NormalText"><div align="right" class="style3"><?php echo $row["esbcgrosssales"]; ?></div></td>
        </tr>
        <?php 
   
   	  $totalnetsales = $totalnetsales + $row["esbcnetsales"];
	  $totalcomps = $totalcomps + $row["esbccomps"];
	  $totalpromos = $totalpromos + $row["esbcpromos"];
	  $totaltaxs = $totaltaxs + $row["esbctaxes"];
	  $totalgross = $totalgross + $row["esbcgrosssales"];
   } ?>
        <tr>
          <td height="5" colspan="6" class="style3"><hr size="1" /></td>
        </tr>
        <tr>
          <td bgcolor="#F5F5F5" class="style3"><strong>Totals</strong></td>
          <td bgcolor="#F5F5F5" class="NormalText"><div align="right"><strong><?php echo number_format($totalnetsales,"2",".",""); ?></strong></div></td>
          <td bgcolor="#F5F5F5" class="NormalText"><div align="right"><strong><?php echo number_format($totalcomps,"2",".",""); ?></strong></div></td>
          <td bgcolor="#F5F5F5" class="NormalText"><div align="right"><strong><?php echo number_format($totalpromos,"2",".",""); ?></strong></div></td>
          <td bgcolor="#F5F5F5" class="NormalText"><div align="right"><strong><?php echo number_format($totaltaxs,"2",".",""); ?></strong></div></td>
          <td bgcolor="#F5F5F5" class="NormalText"><div align="right"><strong><?php echo number_format($totalgross,"2",".",""); ?></strong></div></td>
        </tr>
      </table>
      <br />
      <div align="center"><br />
          <span class="NormalHeading"><strong>Summary</strong></span><br />
          <br />
      </div>
      <table width="100%" border="0" align="center" cellpadding="2" cellspacing="0">
        <tr>
          <td bgcolor="#007BC4" class="BreadCrumb"><div align="center"><strong>Total Guests</strong></div></td>
          <td bgcolor="#007BC4" class="BreadCrumb"><div align="center">Gross Sales</div></td>
          <td bgcolor="#007BC4" class="BreadCrumb"><div align="center">Total Checks</div></td>
          <td bgcolor="#007BC4" class="BreadCrumb"><div align="center">Check Avg</div></td>
          <td bgcolor="#007BC4" class="BreadCrumb"><div align="center"> Guest Avg</div></td>
        </tr>
        <?php 
	 $employeetotalresult = GetEmployeeTotals($sumid,$val);
	 $employeetotalrow = mysql_fetch_array($employeetotalresult);
	 ?>
        <tr>
          <td width="16%" class="style3"><div align="center"><?php echo $employeetotalrow["empguests"]; ?></div></td>
          <td width="16%" class="NormalText"><div align="right" class="style3">
              <div align="center"><?php echo $employeetotalrow["empgrosssales"]; ?></div>
          </div></td>
          <td width="16%" class="NormalText"><div align="right" class="style3">
              <div align="center"><?php echo $employeetotalrow["empchecks"]; ?></div>
          </div></td>
          <td width="18%" class="NormalText"><div align="right" class="style3">
              <div align="center"><?php echo $employeetotalrow["empcheckave"]; ?></div>
          </div></td>
          <td width="17%" class="NormalText"><div align="right" class="style3">
              <div align="center"><?php echo $employeetotalrow["empguestave"]; ?></div>
          </div></td>
        </tr>
      </table>
      <?php if(IsBreakdownValid($sumid,$val) == 'true') {?>
      <div align="center"><strong><br />
            <span class="NormalHeading">Payments Breakdown</span></strong><br />
            <br />
      </div>
      <table width="54%" border="0" align="center" cellpadding="2" cellspacing="0">
        <tr>
          <td bgcolor="#007BC4" class="BreadCrumb"><div align="left"><strong>Payment Type</strong></div></td>
          <td bgcolor="#007BC4" class="BreadCrumb"><div align="right">Amount</div></td>
        </tr>
        <?php 
	    
       // Get list of employees 
	   
	   
 
	   $resultpaymentbreakdown = GetEmployeePaymentBreakdown($sumid,$val); 
     $totalbreakdown = 0;
	  while($rowpaymentbreakdown = mysql_fetch_array($resultpaymentbreakdown)) {
	  ?>
        <tr>
          <td width="62%" class="style3"><div align="left"><?php echo $rowpaymentbreakdown["pbrpaymenttype"]; ?></div></td>
          <td width="38%" class="NormalText"><div align="right" class="style3"><?php echo $rowpaymentbreakdown["pbramount"]; ?></div></td>
        </tr>
        <?php 
		$totalbreakdown = $totalbreakdown + $rowpaymentbreakdown["pbramount"];
		} ?>
        <tr>
          <td colspan="2" class="style3"><hr size="1" /></td>
        </tr>
        <tr>
          <td bgcolor="#F5F5F5" class="style3"><strong>Total</strong></td>
          <td bgcolor="#F5F5F5" class="NormalText"><div align="right"><strong>
            <?php 
		   echo number_format($totalbreakdown,"2",".",""); 
		  ?>
          </strong></div></td>
        </tr>
      </table>
      <?php } ?>
      <p>
        <?php
	 
	 }
	  ?>
      </p>
      <p>
        <?php         // ********************************
		  
		  
		  
	  ?>
        <br />
      </p>
      <div align="center">
        <?php
		  }} else {?>
</div></td>
    <?php } ?>
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