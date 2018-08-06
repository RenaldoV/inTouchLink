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
<title>Total Summary Report</title>
<link href="../style.css" rel="stylesheet" type="text/css" />
<style type="text/css">
<!--
.style4 {color: #FFFFFF; font-weight: bold; }
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
	   $result = GetTotalSummary("'".$_SESSION["dateyear"]."/".$_SESSION["datemonth"]."/".$_SESSION["dateday"]."'", $_SESSION["store"]);
	   $sumIDResult = GetSumIDforWithDateRange("'".$_SESSION["dateyear"]."/".$_SESSION["datemonth"]."/".$_SESSION["dateday"]."'", $_SESSION["store"]);
	}
	if($radDate == "daterange") {
	   $result = GetTotalSummary($_SESSION["daterangestring"], $_SESSION["store"]);
	   $sumIDResult = GetSumIDforWithDateRange($_SESSION["daterangestring"], $_SESSION["store"]);
	}

	
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
        <div align="center">
          <table width="606" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td><div align="center" class="NormalText"><span class="NormalHeading"><strong>Total Summary </strong></span><strong><br />
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
        <br />
        <br />
        <table width="635" height="64" border="0" align="center" cellpadding="2" cellspacing="0">
          <tr>
            <td width="205" height="18" bgcolor="#487CC4" class="NormalText"><span class="style4">Date</span></td>
            <td width="135" bgcolor="#487CC4" class="NormalText"><div align="right" class="style4">GROSS</div></td>
            <td width="136" bgcolor="#487CC4" class="NormalText"><div align="right" class="style4">NETT</div></td>
            <td width="143" bgcolor="#487CC4" class="NormalText"><div align="right" class="style4">Banking Sales </div></td>
          </tr>
          <?php 
	   
	   $grosstotal = "0.00";
	   $netttotal = "0.00";
	   $bankingtotal = "0.00";
	   
	   while($row = mysql_fetch_array($result)) { 
	   
	   
	   ?>
          <tr>
            <td height="23" class="NormalText"><div align="left"><strong><?php echo $row["sumdate"];?></strong></div></td>
            <td class="NormalText"><div align="right">
              <?php $grosstotal = $grosstotal + $row["sumgrosssales"]; echo $row["sumgrosssales"];?>
            </div></td>
            <td class="NormalText"><div align="right">
              <?php $netttotal = $netttotal + $row["sumnettsales"]; echo $row["sumnettsales"];?>
            </div></td>
            <td class="NormalText"><div align="right">
              <?php $bankingtotal = $bankingtotal + $row["sumbankingsales"]; echo $row["sumbankingsales"];?>
            </div></td>
          </tr>
          <?php  } ?>
          <tr>
            <td height="23" bgcolor="#F2F2F2" class="NormalText"><strong>Totals</strong></td>
            <td bgcolor="#F2F2F2" class="NormalText"><div align="right"><?php echo number_format($grosstotal, 2, '.', ''); ?></div></td>
            <td bgcolor="#F2F2F2" class="NormalText"><div align="right"><?php echo number_format($netttotal, 2, '.', ''); ?></div></td>
            <td bgcolor="#F2F2F2" class="NormalText"><div align="right"><?php echo number_format($bankingtotal, 2, '.', '');?></div></td>
          </tr>
        </table>
      </div>
      <hr size="1" />      <p align="left" class="NormalText"><span class="NormalHeading">Calcuation Methods:</span><br />
          <strong>Gross Sales</strong> - Less Voids Surch. Order Charges Add Chgs<br />
          <strong>Nett Sales</strong> - Less Voids, Comps, Promos, Taxes, Surch. Order charges Add Chgs<br />
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
<div align="center"><img src="../images/report_footer.gif" width="600" height="54" />
</div>
</body>
</html>
<?php db_close(); ?>

<script language="javascript1.2">
window.print(); 
setInterval("window.close()", 1000);

</script>