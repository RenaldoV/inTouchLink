<?php 
session_start();
ini_set('display_errors', 'Off');
require_once('../library/library.php');
db_connect();

	 // Paramter Fields
                     // Dates
	
					 $a = "s";
				 
					 $radDate = $_SESSION["radDate"];					
					 $store = str_replace("^","'",$_REQUEST["store"]);	
                     $daterangestring = $_SESSION["daterangestring"];
                     $grpid = $_REQUEST["grpid"];
                     $radStores = $_REQUEST["radStores"];


?>
<?php 
// Save date range variables unique to Growth Comparison Report

// Save date range variables unique to Growth Comparison Report





?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Growth Comparison Report</title>
<link href="../style.css" rel="stylesheet" type="text/css" />
<style type="text/css">
<!--
.style1 {font-weight: bold}
.style2 {font-size: 12px; line-height: normal; color: #333333; text-decoration: none; font-family: Verdana, Arial, Helvetica, sans-serif;}
.style11 {font-weight: bold; font-size: 10px; }
.style12 {font-size: 10px; }
-->
</style>
</head>

<body>
<link href="../style.css" rel="stylesheet" type="text/css">
<div align="center"><br />
  
</div>
<div align="center"><img src="../images/report_header.gif" width="600" height="73" border="0" /></div>
<table width="640" height="462" border="0" align="center" cellpadding="5" cellspacing="0">
  
  <tr>
    <td width="650" height="1" valign="top"><p align="center" class="NormalHeading">
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
 $dataavailable = true;
 if ($dataavailable == true){
 
 ?>
      <?php 
	  // REPORT OUTPUT STARTS HERE
	
	  

		 if($radDate == "date") { // Current month selected
		   // Is the first day of the month a Monday?
		   // Latest range with last Monday is...
			  $counter = 1;
			  while($found != true) {
		        $frommonday = mktime(0, 0, 0, $_SESSION["datemonth"], $counter, $_SESSION["dateyear"]);				
  			    if(date("l", $frommonday) == "Monday") {
				    $found = true;
					// Set start date for TO range
					$datefromday = date("d", $frommonday);
					$datefrommonth = date("m", $frommonday);
					$datefromyear = date("Y", $frommonday);

					// Set 28 days TO range
		            $frommonday28days = mktime(0, 0, 0, $_SESSION["datemonth"], $counter + 27, $_SESSION["dateyear"]);						
					$datetoday = date("d", $frommonday28days);
					$datetomonth = date("m", $frommonday28days);
					$datetoyear = date("Y", $frommonday28days);					
				} 
				$counter--;
  			  }

			  $datefrom = $datefromyear."/".$datefrommonth."/".$datefromday;
			  $dateto = $datetoyear."/".$datetomonth."/".$datetoday;
		   
              // Calculate a year back date range
		   // Previous range with last Monday is...
			  $counter = 1;
			  $found = false;
			  while($found != true) {
		        $frommonday = mktime(0, 0, 0, $_SESSION["datemonth"], $counter, $_SESSION["dateyear"]-1);				
  			    if(date("l", $frommonday) == "Monday") {
				    $found = true;
					// Set start date for TO range
					$datepreviousfromday = date("d", $frommonday);
					$datepreviousfrommonth = date("m", $frommonday);
					$datepreviousfromyear = date("Y", $frommonday);

					// Set 28 days TO range
		            $frommonday28days = mktime(0, 0, 0, $_SESSION["datemonth"], $counter + 27, $_SESSION["dateyear"]-1);						
					$dateprevioustoday = date("d", $frommonday28days);
					$dateprevioustomonth = date("m", $frommonday28days);
					$dateprevioustoyear = date("Y", $frommonday28days);					
				} 
				$counter--;
  			  }

			  $datepreviousfrom = $datepreviousfromyear."/".$datepreviousfrommonth."/".$datepreviousfromday;
			  $datepreviousto = $dateprevioustoyear."/".$dateprevioustomonth."/".$dateprevioustoday;		   

		 } // End Date check

?>
      <div align="center">
        <table width="626" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="104">&nbsp;</td>
            <td width="722"><div align="center" class="NormalText">
              <p><span class="NormalHeading"><strong>Growth Comparison Report </strong></span><strong><br />
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
                (<span id="storecounter" class="NormalText" align="center"></span> stores)<br />
                <strong>between dates </strong> <br />
                <?php 
			if($radDate == "date") {
			  echo $datepreviousfromday."/".$datepreviousfrommonth."/".$datepreviousfromyear." - ".$dateprevioustoday."/".$dateprevioustomonth."/".$dateprevioustoyear."<br>and<br>".$datefromday."/".$datefrommonth."/".$datefromyear." - ".$datetoday."/".$datetomonth."/".$datetoyear;

}
			if($radDate == "daterange") {
			  echo $_SESSION["datefromday"]."/".$_SESSION["datefrommonth"]."/".$_SESSION["datefromyear"]." - ".$_SESSION["datetoday"]."/".$_SESSION["datetomonth"]."/".$_SESSION["datetoyear"]."<br>and<br>".$_SESSION["datefromday2"]."/".$_SESSION["datefrommonth2"]."/".$_SESSION["datefromyear2"]." - ".$_SESSION["datetoday2"]."/".$_SESSION["datetomonth2"]."/".$_SESSION["datetoyear2"];

}
?>
                </p>
              </div></td>
            <td width="61">&nbsp;</td>
            <td width="63"><div align="center"></div></td>
          </tr>
        </table>
        <hr size="1" />
        <form id="frmparameters" name="frmparameters" method="post" action="">
          <span class="NormalText">
          <input type="hidden" name="submitted" id="submitted" value="<?php if($a == "s") {echo "1";} ?>" />
          </span>
                </form>
        <span class="SmallText"><strong>Note : </strong>For the benefit of black &amp; white printers, <br />
        an underlined growth % means a negative growth. </span><br />
        <br />
      </div>
      <div align="center"><span class="NormalText"><strong>Summary</strong></span><br />
        <br />
      </div>
      <?php 
	  // Logic
	  
	  // Need
      // 1. Get list of StoreIDs
	  // If for DATE then work out correct date range dates using Monday formula
	  // a) Get select month and a month a year back.
  
	  // For each store ...
	  		// 1. Get two sets of SUMIDs for store using date ranges
	  		// 2. Select the data using two selections
	  		//    a) Gross (from summary table)
			//    b) # Trans (from items breakdown table)
			//    c) Avg Trans (Gross / # Trans)
			//    d) # Checks (headcount from summary table)
			//    e) Avg Checks (Gross / # Checks)
	
// Select which total to get
if($totaltype == "Gross") {
   $columnname = "sumgrosssales";
}
if($totaltype == "Nett") {
   $columnname = "sumnettsales";
}
if($totaltype == "Banking Sales") {
   $columnname = "sumbankingsales";
}

	/*// Get SUMIDs for Previous
		if($radDate == "date") {
			$sumIDResult = GetSumIDbetweenDates($datepreviousfrom,$datepreviousto, $_SESSION["store"]);
		}
		if($radDate == "daterange") {
			$sumIDResult = GetSumIDbetweenDates($_SESSION["datefromyear"]."/".$_SESSION["datefrommonth"]."/".$_SESSION["datefromday"],$_SESSION["datetoyear"]."/".$_SESSION["datetomonth"]."/".$_SESSION["datetoday"], $_SESSION["store"]);
		}

     if(mysql_num_rows($sumIDResult) > 0) {
	    $sumidrow = mysql_fetch_array($sumIDResult);
	    $sumid = "'".$sumidrow["sumid"]."'";
	 }
	 while($sumidrow = mysql_fetch_array($sumIDResult)) {  
	  $sumid = $sumid.",'".$sumidrow["sumid"]."'";
     }
$sumIDResult = null;
	// Get SUMIDs for Current
		if($radDate == "date") {
			$sumIDResultnow = GetSumIDbetweenDates($datefrom,$dateto, $_SESSION["store"]);
		}
		if($radDate == "daterange") {
			$sumIDResultnow = GetSumIDbetweenDates($_SESSION["datefromyear2"]."/".$_SESSION["datefrommonth2"]."/".$_SESSION["datefromday2"],$_SESSION["datetoyear2"]."/".$_SESSION["datetomonth2"]."/".$_SESSION["datetoday2"], $_SESSION["store"]);
		}

     if(mysql_num_rows($sumIDResultnow) > 0) {
	    $sumidrownow = mysql_fetch_array($sumIDResultnow);
	    $sumid2 = "'".$sumidrownow["sumid"]."'";
	 }
	 while($sumidrownow = mysql_fetch_array($sumIDResultnow)) {  
	  $sumid2 = $sumid2.",'".$sumidrownow["sumid"]."'";
     }	  
	 */
	  
	  ?>
      
      
      <table width="640" height="159" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#F2F2F2">
        
        <tr>
          <td height="18" bgcolor="#487CC4" class="SmallText"><span class="style11">Store</span></td>
          <td bgcolor="#487CC4" class="SmallText"><div align="center" class="style11"><strong><?php echo $totaltype; ?><br />
          1</strong></div></td>
          <td bgcolor="#487CC4" class="SmallText style1"><div align="center" class="style11"><strong><?php echo $totaltype; ?></strong><br />
          2</div></td>
          <td bgcolor="#487CC4" class="SmallText style1"><div align="center" class="style11">%</div></td>
          <td bgcolor="#487CC4" class="SmallText style1"><div align="center" class="style11">#<br />
            Trans <br />
          1</div></td>
          <td bgcolor="#487CC4" class="SmallText style1"><div align="center" class="style11">#<br />
            Trans <br />
          2</div></td>
          <td bgcolor="#487CC4" class="SmallText style1"><div align="center" class="style11">%</div></td>
          <td bgcolor="#487CC4" class="SmallText style1"><div align="center" class="style11">Avg Trans<br />
          1</div></td>
          <td bgcolor="#487CC4" class="SmallText style1"><div align="center" class="style11">Avg Trans <br />
          2</div></td>
          <td bgcolor="#487CC4" class="SmallText style1"><div align="center" class="style11">%</div></td>
          <td bgcolor="#487CC4" class="SmallText style1"><div align="center" class="style11">#<br />
            Checks<br />
          1</div></td>
          <td bgcolor="#487CC4" class="SmallText style1"><div align="center" class="style11">#<br />
            Checks<br />
          2</div></td>
          <td bgcolor="#487CC4" class="SmallText style1"><div align="center" class="style11">%</div></td>
          <td bgcolor="#487CC4" class="SmallText style1"><div align="center" class="style11">Avg Checks<br />
          1</div></td>
          <td bgcolor="#487CC4" class="SmallText style1"><div align="center" class="style11">Avg Checks<br />
          2 </div></td>
          <td bgcolor="#487CC4" class="SmallText style1"><div align="center" class="style11">%</div></td>
        </tr>
        <tr>
          <td height="24" bgcolor="#F2F2F2" class="SmallText"><span class="style11">Total</span></td>
          <td class="SmallText">
            
            <div align="right" class="style11" id="totalfigureprevious">
              <?php // $figureprevious = GetFigureTotal($columnname,$sumid); echo $figureprevious; $previousmainfigure = $figureprevious; ?>
          </div></td>
          <td class="SmallText">
            
            <div align="right" class="style11" id="totalfigure">
              <?php // $figure = GetFigureTotal($columnname,$sumid2); echo $figure; $growth = number_format((($figure/$figureprevious) * 100) - 100, 1, '.', ''); if($growth == "-100") {$growth = "0";}  $mainfigure = $figure;?>
          </div></td>
          <td  id="totalgrowthcell" class="SmallText" ><div align="right" class="style11" id="totalgrowth">
            <?php // echo str_replace("-","",$growth);   ?>
          </div></td>
          <td class="SmallText">
            
            <div align="right" class="style11" id="totaltransprevious">
              <?php  //$figureprevious = GetItemsBreakdownTotal($sumid); echo $figureprevious;?>
          </div></td>
          <td class="SmallText">
            
            <div align="right" class="style11" id="totaltrans">
              <?php //$figure = GetItemsBreakdownTotal($sumid2); echo $figure; $growth = number_format((($figure/$figureprevious) * 100) - 100, 1, '.', ''); if($growth == "-100") {$growth = "0";}?>
          </div></td>
          <td id="totaltransgrowthcell"    class="SmallText"><div align="right" class="style11" id="totaltransgrowth"><?php ?></div></td>
          <td class="SmallText">
            
            <div align="right" class="style11" id="totalavgtransprevious">
              <?php // $figureprevious = number_format($previousmainfigure / $figureprevious, 2, '.', ''); echo $figureprevious;?>
          </div></td>
          <td class="SmallText">
            
            <div align="right" class="style11" id="totalavgtrans">
              <?php //$figure = number_format($mainfigure / $figure, 2, '.', ''); echo $figure; $growth = number_format((($figure/$figureprevious) * 100) - 100, 1, '.', ''); if($growth == "-100") {$growth = "0";}?>
          </div></td>
          <td id="totalavggrowthcell"   class="SmallText"><div align="right" class="style11" id="totalavggrowth"><?php ?></div></td>
          <td class="SmallText">
            
            <div align="right" class="style11" id="totalchecksprevious">
              <?php // $figureprevious = GetChecksTotal($sumid); echo $figureprevious;?>
          </div></td>
          <td class="SmallText">
            
            <div align="right" class="style11" id="totalchecks">
              <?php //$figure = GetChecksTotal($sumid2); echo $figure; $growth = number_format((($figure/$figureprevious) * 100) - 100, 1, '.', ''); if($growth == "-100") {$growth = "0";}?>
          </div></td>
          <td id="totalchecksgrowthcell"    class="SmallText"><div align="right" class="style11" id="totalchecksgrowth"><?php ?></div></td>
          <td class="SmallText">
            
            <div align="right" class="style11" id="totalavgchecksprevious">
              <?php // $figureprevious = number_format($previousmainfigure / $figureprevious, 2, '.', ''); echo $figureprevious;?>
          </div></td>
          <td class="SmallText">
            
            <div align="right" class="style11"  id="totalavgchecks">
              <?php //$figure = number_format($mainfigure / $figure, 2, '.', ''); echo $figure; $growth = number_format((($figure/$figureprevious) * 100) - 100, 1, '.', ''); if($growth == "-100") {$growth = "0";}?>
          </div></td>
          <td id="totalchecksavggrowthcell"    class="SmallText"><div align="right" class="style11" id="totalchecksavggrowth"><?php ?></div></td>
        </tr>
        <tr>
          <td height="37" colspan="16" class="SmallText"><div align="center" class="NormalText style12"><strong>Detail</strong></div></td>
        </tr>

        <tr>
          <td width="121" height="18" bgcolor="#487CC4" class="SmallText"><span class="style11"><strong>Store</strong></span></td>
          <td width="62" bgcolor="#487CC4" class="SmallText"><div align="center" class="style12"><strong><strong><?php echo $totaltype; ?><br /> 
          1</strong></strong></div></td>
          <td width="56" bgcolor="#487CC4" class="SmallText style1"><div align="center" class="style12"><strong><strong><?php echo $totaltype; ?></strong><br />
          2</strong></div></td>
          <td width="32" bgcolor="#487CC4" class="SmallText style1"><div align="center" class="style12"><strong>%</strong></div></td>
          <td width="44" bgcolor="#487CC4" class="SmallText style1"><div align="center" class="style12"><strong>#<br />
            Trans <br />
          1</strong></div></td>
          <td width="45" bgcolor="#487CC4" class="SmallText style1"><div align="center" class="style12"><strong>#<br />
            Trans <br />
          2</strong></div></td>
          <td width="33" bgcolor="#487CC4" class="SmallText style1"><div align="center" class="style12"><strong>%</strong></div></td>
          <td width="59" bgcolor="#487CC4" class="SmallText style1"><div align="center" class="style12"><strong>Avg Trans<br /> 
          1</strong></div></td>
          <td width="60" bgcolor="#487CC4" class="SmallText style1"><div align="center" class="style12"><strong>Avg Trans <br />
          2</strong></div></td>
          <td width="33" bgcolor="#487CC4" class="SmallText style1"><div align="center" class="style12"><strong>%</strong></div></td>
          <td width="68" bgcolor="#487CC4" class="SmallText style1"><div align="center" class="style12"><strong>#<br />
            Checks<br /> 
          1</strong></div></td>
          <td width="67" bgcolor="#487CC4" class="SmallText style1"><div align="center" class="style12"><strong>#<br />
            Checks<br /> 
          2</strong></div></td>
          <td width="35" bgcolor="#487CC4" class="SmallText style1"><div align="center" class="style12"><strong>%</strong></div></td>
          <td width="65" bgcolor="#487CC4" class="SmallText style1"><div align="center" class="style12"><strong>Avg Checks<br />
          1</strong></div></td>
          <td width="65" bgcolor="#487CC4" class="SmallText style1"><div align="center" class="style12"><strong>Avg Checks<br /> 
            2
          </strong></div></td>
          <td width="39" bgcolor="#487CC4" class="SmallText style1"><div align="center" class="style12"><strong>%</strong></div></td>
        </tr>
      <?php 
	  // Go through each store in list


if($radStores == "store") {
	$result = GetStoreDetails($_SESSION["store"]); // Get all the Store IDs inside that group   
}
if($radStores == "storegroup") {
	$result = GetStoreIDsForGroup($grpid); // Get all the Store IDs inside that group   				 
}
	$storecounter = 0;	
    $totalfigureprevious = 0;
	while ($row = mysql_fetch_array($result)) { // Go through each store in Group

$sumidprevious = null;
$sumidresult = null;
	  // Get SUMID list for a specific store for FROM DATE
		if($radDate == "date") {
			$sumidresult = GetSumIDbetweenDates($datepreviousfrom,$datepreviousto, $row["strid"]);
		}
		if($radDate == "daterange") {
			$sumidresult = GetSumIDbetweenDates($_SESSION["datefromyear"]."/".$_SESSION["datefrommonth"]."/".$_SESSION["datefromday"],$_SESSION["datetoyear"]."/".$_SESSION["datetomonth"]."/".$_SESSION["datetoday"], $row["strid"]);
		}
		
		if(mysql_num_rows($sumidresult) > 0) {
			$sumidrow = mysql_fetch_array($sumidresult);
			$sumidprevious = "'".$sumidrow["sumid"]."'";
		}
		while($sumidrow = mysql_fetch_array($sumidresult)) {  
			$sumidprevious = $sumidprevious.",'".$sumidrow["sumid"]."'";
		}
$sumidresult = null;
$sumid = null;
	  // Get SUMID list for a specific store for TO DATE
		if($radDate == "date") {
			$sumidresult = GetSumIDbetweenDates($datefrom,$dateto, $row["strid"]);
		}
		if($radDate == "daterange") {
			$sumidresult = GetSumIDbetweenDates($_SESSION["datefromyear2"]."/".$_SESSION["datefrommonth2"]."/".$_SESSION["datefromday2"],$_SESSION["datetoyear2"]."/".$_SESSION["datetomonth2"]."/".$_SESSION["datetoday2"], $row["strid"]);
		}
		
		if(mysql_num_rows($sumidresult) > 0) {
			$sumidrow = mysql_fetch_array($sumidresult);
			$sumid = "'".$sumidrow["sumid"]."'";
		}
		while($sumidrow = mysql_fetch_array($sumidresult)) {  
			$sumid = $sumid.",'".$sumidrow["sumid"]."'";
		}




//echo "Previous ".$sumidprevious."<br>";
//echo "Current ".$sumid."<br>"; 

	$figureprevious = GetFigureTotal($columnname,$sumidprevious); $previousmainfigure = $figureprevious; $figuretotal1 = $figuretotal1 + $figureprevious;
	
	$figure = GetFigureTotal($columnname,$sumid); $growth = number_format((($figure/$figureprevious) * 100) - 100, 1, '.', ''); if($growth == "-100") {$growth = "0";}  $mainfigure = $figure;
	if($figureprevious != "0.00" && $figure != "0.00") {
	  $storecounter++;
	  ?>
        <tr>
          <td height="29" class="SmallText"><span class="style11"><?php echo GetStoreName($row["strid"]) ;?></span></td>
          <td class="SmallText">
            <div align="right" class="style11">
              <?php  echo $figureprevious; $totalfigureprevious = $totalfigureprevious + $figureprevious;   ?>
          </div></td>
          <td class="SmallText">
            <div align="right" class="style11">
              <?php  echo $figure; $totalfigure = $totalfigure + $figure; ?>
          </div></td>
          <td  class="SmallText"><div align="right" class="style11">
            <div align="center">
              <div align="right" style="color:<?php if($growth > 0) {echo "#2f9740";} if($growth < 0) {echo "#FF0000; text-decoration:underline";} if($growth == 0) {echo "#0066CC";} ?>"><?php echo str_replace("-","",$growth);   ?></div>
            </div>
          </div></td>
          <td class="SmallText">
            
            <div align="right"><strong>
              <?php $figureprevious = 0; $figureprevious = GetItemsBreakdownTotal($sumidprevious); echo $figureprevious; $totaltransprevious = $totaltransprevious + $figureprevious;?>
            </strong></div></td>
          <td class="SmallText">
            
            <div align="right"><strong>
              <?php $figure = GetItemsBreakdownTotal($sumid); echo $figure; $growth = number_format((($figure/$figureprevious) * 100) - 100, 1, '.', ''); if($growth == "-100") {$growth = "0";} $totaltrans = $totaltrans + $figure; ?>
            </strong></div></td>
          <td  class="SmallText"><div align="right" style="color:<?php if($growth > 0) {echo "#2f9740";} if($growth < 0) {echo "#FF0000; text-decoration:underline";} if($growth == 0) {echo "#0066CC";} ?>"><strong><?php echo str_replace("-","",$growth);   ?></strong></strong></div></td>
          <td class="SmallText">
            
            <div align="right"><strong>
              <?php $figureprevious = number_format($previousmainfigure / $figureprevious, 2, '.', ''); echo $figureprevious;?>
            </strong></div></td>
          <td class="SmallText">
            
            <div align="right"><strong>
              <?php $figure = number_format($mainfigure / $figure, 2, '.', ''); echo $figure; $growth = number_format((($figure/$figureprevious) * 100) - 100, 1, '.', ''); if($growth == "-100") {$growth = "0";}?>
            </strong></div></td>
          <td  class="SmallText"><div align="right" style="color:<?php if($growth > 0) {echo "#2f9740";} if($growth < 0) {echo "#FF0000; text-decoration:underline";} if($growth == 0) {echo "#0066CC";} ?>"><strong><?php echo str_replace("-","",$growth);   ?></strong></strong></div></td>
          <td class="SmallText">
            
            <div align="right"><strong>
              <?php $figureprevious = GetChecksTotal($sumidprevious); echo $figureprevious; $totalchecksprevious = $totalchecksprevious + $figureprevious;?>
            </strong></div></td>
          <td class="SmallText">
            
            <div align="right"><strong>
              <?php $figure = GetChecksTotal($sumid); echo $figure; $growth = number_format((($figure/$figureprevious) * 100) - 100, 1, '.', ''); if($growth == "-100") {$growth = "0";} $totalchecks = $totalchecks + $figure;?>
            </strong></div></td>
          <td  class="SmallText"><div align="right" style="color:<?php if($growth > 0) {echo "#2f9740";} if($growth < 0) {echo "#FF0000; text-decoration:underline";} if($growth == 0) {echo "#0066CC";} ?>"><strong><?php echo str_replace("-","",$growth);   ?></strong></strong></div></td>
          <td class="SmallText">
            
            <div align="right"><strong>
              <?php $figureprevious = number_format($previousmainfigure / $figureprevious, 2, '.', ''); echo $figureprevious;?>
            </strong></div></td>
          <td class="SmallText">
            
            <div align="right"><strong>
              <?php $figure = number_format($mainfigure / $figure, 2, '.', ''); echo $figure; $growth = number_format((($figure/$figureprevious) * 100) - 100, 1, '.', ''); if($growth == "-100") {$growth = "0";}?>
            </strong></div></td>
          <td  class="SmallText"><div align="right" style="color:<?php if($growth > 0) {echo "#2f9740";} if($growth < 0) {echo "#FF0000; text-decoration:underline";} if($growth == 0) {echo "#0066CC";} ?>"><strong><?php echo str_replace("-","",$growth);   ?></strong></strong></div></td>
        </tr>
        <?php 
		}
		}	
		?>
      </table>
 
      <br />
      <p align="left" class="NormalText">&nbsp;</p>
      <div align="center">

	<?php
      } else {
	  ?>
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
 if(document.frmparameters.submitted.value == 1) {
 document.getElementById("storecounter").innerHTML = "<?php echo $storecounter; ?>";

// Totals
 document.getElementById("totalfigureprevious").innerHTML = "<?php echo $totalfigureprevious; ?>";
 document.getElementById("totalfigure").innerHTML = "<?php echo $totalfigure; ?>";
<?php 
 $growth = number_format((($totalfigure/$totalfigureprevious) * 100) - 100, 1, '.', ''); 
 if($growth == "-100") {$growth = "0";}
 ?>
  document.getElementById("totalgrowth").innerHTML = "<?php echo str_replace("-","",$growth) ?>";
<?php if($growth > 0) { ?>
document.getElementById("totalgrowthcell").style.color = "#2f9740";
 <?php } if($growth < 0) {  ?>
document.getElementById("totalgrowthcell").style.color = "#FF0000";
document.getElementById("totalgrowthcell").style.textDecoration = "underline";
 <?php } if($growth == 0) {  ?>
 document.getElementById("totalgrowthcell").style.color = "#0066CC";
 <?php } ?>
 // Trans 
  document.getElementById("totaltransprevious").innerHTML = "<?php echo $totaltransprevious; ?>";
  document.getElementById("totaltrans").innerHTML = "<?php echo $totaltrans; ?>";
<?php 
$growth = 0;
 $growth = number_format((($totaltrans/$totaltransprevious) * 100) - 100, 1, '.', ''); 
 if($growth == "-100") {$growth = "0";}
 ?>
  document.getElementById("totaltransgrowth").innerHTML = "<?php echo str_replace("-","",$growth) ?>";
<?php if($growth > 0) { ?>
document.getElementById("totaltransgrowthcell").style.color  = "#006600";
 <?php } if($growth < 0) {  ?>
document.getElementById("totaltransgrowthcell").style.color  = "#FF0000";
document.getElementById("totaltransgrowthcell").style.textDecoration = "underline";
 <?php } if($growth == 0) {  ?>
 document.getElementById("totaltransgrowthcell").style.color = "#0066CC";
 <?php } ?>


// Avg Trans
  document.getElementById("totalavgtransprevious").innerHTML = "<?php echo number_format($totalfigureprevious / $totaltransprevious, 2, '.', '') ?>";
  document.getElementById("totalavgtrans").innerHTML = "<?php echo number_format($totalfigure / $totaltrans, 2, '.', '') ?>";
<?php 
$growth = 0;
 $growth = number_format(((number_format($totalfigure / $totaltrans, 2, '.', '')/number_format($totalfigureprevious / $totaltransprevious, 2, '.', '')) * 100) - 100, 1, '.', ''); 
 if($growth == "-100") {$growth = "0";}
 ?>
  document.getElementById("totalavggrowth").innerHTML = "<?php echo str_replace("-","",$growth) ?>";
<?php if($growth > 0) { ?>
document.getElementById("totalavggrowthcell").style.color  = "#006600";
 <?php } if($growth < 0) {  ?>
document.getElementById("totalavggrowthcell").style.color  = "#FF0000";
document.getElementById("totalavggrowthcell").style.textDecoration = "underline";
 <?php } if($growth == 0) {  ?>
 document.getElementById("totalavggrowthcell").style.color = "#0066CC";
 <?php } ?>


// Checks
  document.getElementById("totalchecksprevious").innerHTML = "<?php echo $totalchecksprevious; ?>";
  document.getElementById("totalchecks").innerHTML = "<?php echo $totalchecks; ?>";
<?php 
$growth = 0;
 $growth = number_format((($totalchecks/$totalchecksprevious) * 100) - 100, 1, '.', ''); 
 if($growth == "-100") {$growth = "0";}
 ?>
  document.getElementById("totalchecksgrowth").innerHTML = "<?php echo str_replace("-","",$growth) ?>";
<?php if($growth > 0) { ?>
document.getElementById("totalchecksgrowthcell").style.color  = "#006600";
 <?php } if($growth < 0) {  ?>
document.getElementById("totalchecksgrowthcell").style.color  = "#FF0000";
document.getElementById("totalchecksgrowthcell").style.textDecoration = "underline";
 <?php } if($growth == 0) {  ?>
 document.getElementById("totalchecksgrowthcell").style.color = "#0066CC";
 <?php } ?>


// Avg Checks
  document.getElementById("totalavgchecksprevious").innerHTML = "<?php echo number_format($totalfigureprevious / $totalchecksprevious, 2, '.', '') ?>";
  document.getElementById("totalavgchecks").innerHTML = "<?php echo number_format($totalfigure / $totalchecks, 2, '.', '') ?>";
<?php 
$growth = 0;
 $growth = number_format(((number_format($totalfigure / $totalchecks, 2, '.', '')/number_format($totalfigureprevious / $totalchecksprevious, 2, '.', '')) * 100) - 100, 1, '.', ''); 
 if($growth == "-100") {$growth = "0";}
 ?>
  document.getElementById("totalchecksavggrowth").innerHTML = "<?php echo str_replace("-","",$growth) ?>";
<?php if($growth > 0) { ?>
document.getElementById("totalchecksavggrowthcell").style.color = "#006600";
 <?php } if($growth < 0) {  ?>
document.getElementById("totalchecksavggrowthcell").style.color  = "#FF0000";
document.getElementById("totalchecksavggrowthcell").style.textDecoration = "underline";
 <?php } if($growth == 0) {  ?>
 document.getElementById("totalchecksavggrowthcell").style.color = "#0066CC";
 <?php } ?>
//totalgrowth
//echo str_replace("-","",$growth);
 }

window.print(); 
setInterval("window.close()", 1000);

</script>