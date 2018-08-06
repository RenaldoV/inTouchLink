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
	   $result = GetTotalComparison("'".$_SESSION["dateyear"]."/".$_SESSION["datemonth"]."/".$_SESSION["dateday"]."'", $_SESSION["store"]);
	   $sumIDResult = GetSumIDforWithDateRange("'".$_SESSION["dateyear"]."/".$_SESSION["datemonth"]."/".$_SESSION["dateday"]."'", $_SESSION["store"]);
	}
	if($radDate == "daterange") {
	   $result = GetTotalComparison($_SESSION["daterangestring"], $_SESSION["store"]);
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
      <div align="center">
        <table width="606" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="57">&nbsp;</td>
            <td width="642"><div align="center" class="NormalText"><span class="NormalHeading"><strong>Total Comparison Detail </strong></span><strong><br />
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
            <td width="39">&nbsp;</td>
          </tr>
        </table>
        <hr size="1" />
        <span class="NormalHeading"> Summary</span><br />
        <br />
      </div>
      <br />
      <br />
      <table width="100%" height="64" border="0" align="center" cellpadding="2" cellspacing="0">
        <tr>
          <td width="106" height="18" bgcolor="#487CC4" class="NormalText"><span class="style4">Date</span></td>
          <td width="220" bgcolor="#487CC4" class="BreadCrumb">Store</td>
          <td width="100" bgcolor="#487CC4" class="NormalText"><div align="right" class="style4">GROSS</div></td>
          <td width="92" bgcolor="#487CC4" class="NormalText"><div align="right" class="style4">NETT</div></td>
          <td width="105" bgcolor="#487CC4" class="NormalText"><div align="right" class="style4">Banking Sales </div></td>
          <td width="91" bgcolor="#487CC4" class="NormalText"><div align="right" class="style4">Royalties</div></td>
        </tr>
        <?php 

// ************************************************
// Excel
// Write Table Headers
$title = "Date";				 
$headings = array($title, '');
$worksheet->write_row('C10', $headings, $heading2);

$title = "Store";				 
$headings = array($title, '');
$worksheet->write_row('D10', $headings, $heading2);

$title = "GROSS";				 
$headings = array($title, '');
$worksheet->write_row('E10', $headings, $RightNumberTotalBold);

$title = "NETT";				 
$headings = array($title, '');
$worksheet->write_row('F10', $headings, $RightNumberTotalBold);

$title = "Banking Sales";				 
$headings = array($title, '');
$worksheet->write_row('G10', $headings, $RightNumberTotalBold);

$rownumber = 11;  			  			   

$grosstotal = 0.00;
$netttotal = 0.00;
$bankingtotal = 0.00;

// Make the list of stores involved - even if no data for a specific day
$store_array = array();
$date_array = array();
while($row = mysql_fetch_array($result)) { 
	array_push($store_array,$row["strname"]);
	array_push($date_array,$row["sumdate"]);  
}
// Delete duplicates and sort.
$store_array  = array_keys(array_flip($store_array));
$date_array  = array_keys(array_flip($date_array));

$chartdata = array(array());
// Fill with Zeros
for($y=0;$y <= count($date_array)  ;$y++) {
	for($x=0;$x <= count($store_array);$x++) {
		$chartdata[$x][$y] = "0.00";
	} 
}

$chartdata[0][0] = "";

// SET DATE COLUMN HEADER
for($i=0;$i < count($date_array);$i++){ 
	$chartdata[0][$i+1] = $date_array[$i];	   
}	   
// SET DATE ROW HEADER
for($i=0;$i < count($store_array);$i++){ 
	$chartdata[$i+1][0] = $store_array[$i];	   
}    

// Set counters 
$chart_col = 1;
$chart_row = 1;

mysql_data_seek($result,0); //  Move pointer to first record.
while($row = mysql_fetch_array($result)) { 
?>
        <tr>
          <td height="23" class="NormalText"><div align="left"><strong>
              <?php 
// Only show first date
if( $olddate == null) { // First date
	$olddate = $row["sumdate"];
	$chart_col = 1; 
	echo $row["sumdate"];
	// Excel
	$title = $row["sumdate"];;			 
	$headings = array($title, '');
	$worksheet->write_row('C'.$rownumber, $headings, $NormalLeftAlign);	
}
if($olddate !=  $row["sumdate"]) {
	echo $row["sumdate"];
	// Excel
	$title = $row["sumdate"];;			 
	$headings = array($title, '');
	$worksheet->write_row('C'.$rownumber, $headings, $NormalLeftAlign);	
	$chart_col++;
}

$olddate = $row["sumdate"];

?>
          </strong></div></td>
          <td class="NormalText"><?php echo $row["strname"]; ?></td>
          <td class="NormalText"><div align="right">
              <?php $grosstotal = $grosstotal + $row["sumgrosssales"]; echo $row["sumgrosssales"];?>
          </div></td>
          <td class="NormalText"><div align="right">
              <?php 
$netttotal = $netttotal + $row["sumnettsales"]; echo $row["sumnettsales"];
// Find store row number
for($a=0; $a <= count($store_array); $a++){
	if($store_array[$a] == $row["strname"]) { // found store
		$chartdata[$a+1][$chart_col] = $row["sumnettsales"];	
	}
}
?>
          </div></td>
          <td class="NormalText"><div align="right">
              <?php $bankingtotal = $bankingtotal + $row["sumbankingsales"]; echo $row["sumbankingsales"];?>
          </div></td>
          <td class="NormalText"><div align="right">
              <?php 
	$royaltyresult = GetStoreRoyalty($row["strid"]);
	$royaltyrow = mysql_fetch_array($royaltyresult);
    
	if($royaltyrow["strroyaltytype"] == "Gross") { // GROSS ROYALTY
		$royalty = number_format(($row["sumgrosssales"] / 100) * $royaltyrow["strroyaltypercent"], 2, '.', '');
    }

	if($royaltyrow["strroyaltytype"] == "Nett") { // NETT ROYALTY
		$royalty =  number_format(($row["sumnettsales"] / 100) * $royaltyrow["strroyaltypercent"], 2, '.', '');
    }

	if($royaltyrow["strroyaltytype"] == "Banking Sales") { // BANKING SALES ROYALTY
		$royalty =  number_format(($row["sumbankingsales"] / 100) * $royaltyrow["strroyaltypercent"], 2, '.', '');
    }
	if($royaltyrow["strroyaltytype"] == "Not applicable") { // BANKING SALES ROYALTY
		$royalty =  "N/A";
    }
echo $royalty;
$royaltytotal = $royaltytotal + $royalty;
?>
          </div></td>
        </tr>
        <?php 

// ************************************************
// Excel

$title = $row["strname"];			 
$headings = array($title, '');
$worksheet->write_row('D'.$rownumber,$headings,$num1_format);	

$title = $row["sumgrosssales"];			 
$headings = array($title, '');
$worksheet->write_row('E'.$rownumber,$headings,$num1_format);			

$title = $row["sumnettsales"];			 
$headings = array($title, '');
$worksheet->write_row('F'.$rownumber,$headings,$num1_format);		

$title = $row["sumbankingsales"];			 
$headings = array($title, '');
$worksheet->write_row('G'.$rownumber,$headings,$num1_format);		

$rownumber++;       

} 
// Excel
// *
// Write the totals

$title = "Totals";			 
$headings = array($title, '');
$worksheet->write_row('D'.$rownumber, $headings, $RightNumberTotalBold );	

$title = number_format($grosstotal, 2, '.', '');			 
$headings = array($title, '');
$worksheet->write_row('E'.$rownumber,$headings,$RightNumberTotalBold );			

$title = number_format($netttotal, 2, '.', '');			 
$headings = array($title, '');
$worksheet->write_row('F'.$rownumber,$headings,$RightNumberTotalBold );		

$title = number_format($bankingtotal, 2, '.', '');			 
$headings = array($title, '');
$worksheet->write_row('G'.$rownumber,$headings,$RightNumberTotalBold );	

?>
        <tr>
          <td height="23" bgcolor="#F2F2F2" class="NormalText"><strong>Totals</strong></td>
          <td bgcolor="#F2F2F2" class="NormalText">&nbsp;</td>
          <td bgcolor="#F2F2F2" class="NormalText"><div align="right"><?php echo number_format($grosstotal, 2, '.', ''); ?></div></td>
          <td bgcolor="#F2F2F2" class="NormalText"><div align="right"><?php echo number_format($netttotal, 2, '.', ''); ?></div></td>
          <td bgcolor="#F2F2F2" class="NormalText"><div align="right"><?php echo number_format($bankingtotal, 2, '.', ''); ?></div></td>
          <td bgcolor="#F2F2F2" class="NormalText"><div align="right"><?php echo number_format($royaltytotal, 2, '.', ''); ?></div></td>
        </tr>
      </table>
      <div align="center"><br />
      </div>
      <hr size="1" />
      <p align="left" class="NormalText"><span class="NormalHeading">Calcuation Methods:</span><br />
        <strong>Gross Sales</strong> - Less Voids Surch. Order Charges Add Chgs<br />
        <strong>Nett Sales</strong> - Less Voids, Comps, Promos, Taxes, Surch. Order charges Add Chgs<br />
        <strong>Banking Sales</strong> -  Less Voids, Comps, Promos, Surch. Order Charges Add Chgs</p>
      <p align="left" class="NormalText"><br />
      </p>
      <div align="center">

        <?php
		 // Add to array
		 
			   $_SESSION["totalcomparison_chartdata"] = $chartdata; 
		 	
			
	

		 
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