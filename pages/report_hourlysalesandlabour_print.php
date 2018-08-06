<?php 
session_start();
require_once('../library/library.php');
db_connect();
$salestype = $_SESSION["usrpref_salestype"];
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
.style2 {font-size: 12px; line-height: normal; color: #333333; text-decoration: none; font-family: Verdana, Arial, Helvetica, sans-serif;}
.style3 {
	color: #FFFFFF;
	font-weight: bold;
}
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
        <table width="396" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="396"><div align="center" class="NormalText"><span class="NormalHeading"><strong>Hourly Sales and Labour Report (<?php echo $salestype;?>)</strong></span><strong><br />
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
			?>      
            </div></td>
          </tr>
        </table>
        <hr size="1" />
        <?php 
   if($radStores == 'store' && ($radDate == 'date' || $radDate == 'daterange')) {?>
        <table width="100%" border="0" align="center" cellpadding="2" cellspacing="0">
          <tr>
            <td bgcolor="#007BC4" class="BreadCrumb"><strong>Start Time </strong></td>
            <td bgcolor="#007BC4" class="BreadCrumb"><div align="right"><strong>
              <?php 
		  if($salestype == 'Gross') {
		     echo "Gross";
		  }
		  if($salestype == 'Nett') {
		     echo "Nett";
		  }		  
		  ?>
              Sales</strong></div></td>
            <td bgcolor="#007BC4" class="BreadCrumb"><div align="right"><strong>Guests</strong></div></td>
            <td bgcolor="#007BC4" class="BreadCrumb"><div align="right"><strong>Guests Avg</strong></div></td>
            <td bgcolor="#007BC4" class="BreadCrumb"><div align="right"><strong>Checks</strong></div></td>
            <td bgcolor="#007BC4" class="BreadCrumb"><div align="right"><strong>Check Avg</strong></div></td>
            <td bgcolor="#007BC4" class="BreadCrumb"><div align="right"><strong>Labour Hrs</strong></div></td>
            <td bgcolor="#007BC4" class="BreadCrumb"><div align="right"><strong>Labour
              <?php 
		  if($salestype == 'Gross') {
		     echo "Gross";
		  }
		  if($salestype == 'Nett') {
		     echo "Nett";
		  }		  
		  ?>
              /Hr </strong></div></td>
          </tr>
          <?php 

	  $grosssalestotal = 0;
	  $gueststotal = 0;
	  $checkstotal = 0;
	  $labourhrstotal = 0;
	
	// Use different function for Gross and Nett
	if ($salestype == "Gross") { // Gross
	  $result = GetHourlySales($sumid); 
	} else { // Nett
	  $result = GetHourlySalesNett($sumid); 
	}
	  $counter = mysql_num_rows($result);
   
   while($row = mysql_fetch_array($result)) {
	  $grosssalestotal = $grosssalestotal + floatval($row["hsgrosssales"]); 
	  $gueststotal = $gueststotal + intval($row["hsguests"]); 
	  $checkstotal = $checkstotal + intval($row["hschecks"]); 
	  $labourhrstotal = $labourhrstotal + floatval($row["hslaborhours"]); 

	  ?>
          <tr>
            <td width="11%" class="style2"><?php echo $row["hsstarttime"]; ?></td>
            <td width="14%" class="NormalText"><div align="right"><span class="style2">
              <?php $grosssales = $row["hsgrosssales"]; echo number_format($row["hsgrosssales"], 2, '.', '');  ?>
            </span></div></td>
            <td width="12%" class="NormalText"><div align="right"><span class="style2">
              <?php $guests = $row["hsguests"]; echo $row["hsguests"]; ?>
            </span></div></td>
            <td width="13%" class="NormalText"><div align="right"><span class="style2"><?php echo number_format($grosssales / $guests, 2, '.', '');  ?></span></div></td>
            <td width="10%" class="NormalText"><div align="right"><span class="style2">
              <?php $checks = $row["hschecks"]; echo $row["hschecks"]; ?>
            </span></div></td>
            <td width="12%" class="NormalText"><div align="right"><span class="style2"><?php echo number_format($grosssales / $checks, 2, '.', ''); ?></span></div></td>
            <td width="12%" class="NormalText"><div align="right"><span class="style2">
              <?php $labourhrs = $row["hslaborhours"]; echo number_format($row["hslaborhours"], 2, '.', ''); ?>
            </span></div></td>
            <td width="16%" class="NormalText"><div align="right"><span class="style2"><?php echo number_format($grosssales / $labourhrs, 2, '.', '');  ?></span></div></td>
          </tr>
          <?php
	             
	   } ?>
          <tr>
            <td colspan="8" class="style2"><hr size="1" /></td>
          </tr>
          <tr>
            <td bgcolor="#F5F5F5" class="style2"><strong>Totals</strong></td>
            <td bgcolor="#F5F5F5" class="NormalText"><div align="right"><strong><?php printf("%.2f",$grosssalestotal); ?></strong></div></td>
            <td bgcolor="#F5F5F5" class="NormalText"><div align="right"><strong><?php echo $gueststotal; ?></strong></div></td>
            <td bgcolor="#F5F5F5" class="NormalText"><div align="right"><strong><?php printf("%.2f",$grosssalestotal/$gueststotal);  ?></strong></div></td>
            <td bgcolor="#F5F5F5" class="NormalText"><div align="right"><strong><?php echo $checkstotal; ?></strong></div></td>
            <td bgcolor="#F5F5F5" class="NormalText"><div align="right"><strong><?php printf("%.2f",$grosssalestotal/$checkstotal); ?></strong></div></td>
            <td bgcolor="#F5F5F5" class="NormalText"><div align="right"><strong><?php printf("%.2f",$labourhrstotal); ?></strong></div></td>
            <td bgcolor="#F5F5F5" class="NormalText"><div align="right"><strong><?php printf("%.2f",$grosssalestotal/$labourhrstotal); ?></strong></div></td>
          </tr>
        </table>
        <?php        		    
			  
			   } ?>
        <?php if($radStores == 'storegroup' || $radDate == 'daterange') {?>
        <?php 
	
	  } 
	  
	 

	  
	  ?>
        <br />
        <br />
      </div>
   
      <div align="center">
        <table width="100%" border="0" cellspacing="0" cellpadding="5">
          <tr>
            <td>&nbsp;</td>
          </tr>
        </table>
	  
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