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
<title>Revenue Center Report</title>
<link href="../style.css" rel="stylesheet" type="text/css" />
<style type="text/css">
<!--
.style2 {	color: #006600;
	font-weight: bold;
}
.style4 {font-weight: bold}
.style7 {color: #000000; font-weight: bold; }
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
        <table width="596" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="560"><div align="center" class="NormalText"><span class="NormalHeading"><strong>Revenue Center Sales Report</strong></span><strong><br />
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
            <td width="10">&nbsp;</td>
            <td width="32">&nbsp;</td>
          </tr>
        </table>
        <hr size="1" />
      </div>

      <div align="center"><span class="NormalHeading">Net Sales By Category</span><br />
      </div>
      <table width="414" height="64" border="0" align="center" cellpadding="2" cellspacing="0">
        <tr>
          <td width="240" height="18" bgcolor="#487CC4" class="NormalText"><strong>Category</strong></td>
 <?php 
 
 // CREATE THE COLUMS FOR EACH REVENUE CENTER
 $result = GetRevenueCenterNames($sumid);
 $revenuecentercount = mysql_num_rows($result);
 while($row = mysql_fetch_array($result)) { 
 ?>
          <td width="98" bgcolor="#487CC4" class="NormalText"><div align="right"><strong><?php echo $row["revenuecentername"]; ?></strong></div></td>
  <?php  } // Colums created ?>        
          <td width="68" bgcolor="#487CC4" class="NormalText"><div align="right"><strong> Total</strong></div></td>
        </tr>
        <?php 
   // ROW - WHILE IN SAME SALES CAT
	   $result = GetNetSalesByCategory($sumid); // Get all records

       $totalarray = array();
	   while($row = mysql_fetch_array($result)) { 
	   ?> 
        <tr>
          <td height="23" class="NormalText"><div align="left"><strong><?php echo $row["salescatname"];?></strong></div></td>
   <?php 
   $cattotal = 0;

   
   for($i=0;$i<$revenuecentercount;$i++) { // Go through revenue center count while in same sales category
	   ?>
			  <td class="NormalText"><div align="right">
		<?php echo $row["netsales"];
		$cattotal = $cattotal + $row["netsales"];
		$grandtotal = $grandtotal + $row["netsales"];
		$totalarray[$i] = $totalarray[$i] + $row["netsales"];
		?>
			 </div></td>
		<?php
		 if($i < $revenuecentercount - 1) { 
		   $row = mysql_fetch_array($result);
		  }
	} ?>

	       <td class="NormalText"><div align="right"><?php echo number_format($cattotal, 2, '.', ''); ?>
          </div></td>
        </tr>
        <?php 
	    } 
		?>
        <tr>
          <td height="23" bgcolor="#F2F2F2" class="NormalText"><strong>Totals</strong></td>
      
      <?php   for($i=0;$i<$revenuecentercount;$i++) {
	   ?>
          <td bgcolor="#F2F2F2" class="NormalText"><div align="right"><strong><?php echo number_format($totalarray[$i], 2, '.', ''); ?></strong></div></td>
		<?php  
	} ?>    

          
          <td bgcolor="#F2F2F2" class="NormalText"><div align="right"><strong><?php echo number_format($grandtotal, 2, '.', ''); ?></strong></div></td>
        </tr>
      </table>
      <div align="center"><br />
        <br />
        <?php // START - NET SALES BY CATEGORY (NON SALES CATEGORIES) ///////////////////////////////////////   ?>
        <span class="NormalHeading">Net Sales By Category(Non Sales Categories)</span><br />
      </div>
      <table width="414" height="64" border="0" align="center" cellpadding="2" cellspacing="0">
        <tr>
          <td width="240" height="18" bgcolor="#487CC4" class="NormalText"><span class="style4">Category</span></td>
 <?php 
 
 // CREATE THE COLUMS FOR EACH REVENUE CENTER
 $result = GetRevenueCenterNames($sumid);
 $revenuecentercount = mysql_num_rows($result);
 while($row = mysql_fetch_array($result)) { 
 ?>
          <td width="98" bgcolor="#487CC4" class="NormalText"><div align="right" class="style4"><?php echo $row["revenuecentername"]; ?></div></td>
  <?php  } // Colums created ?>        
          <td width="87" bgcolor="#487CC4" class="NormalText"><div align="right" class="style4"> Total</div></td>
        </tr>
        <?php 
   // ROW - WHILE IN SAME SALES CAT
	// Reset Totals
	$grandtotal =0;
	
	   $result = GetNetSalesByCategoryNonSalesCat($sumid);

       $totalarray = array();
	   while($row = mysql_fetch_array($result)) { 
	   ?> 
        <tr>
          <td height="23" class="NormalText"><div align="left"><strong><?php echo $row["nscname"];?></strong></div></td>
   <?php 
   $cattotal = 0;

   
   for($i=0;$i<$revenuecentercount;$i++) {
	   ?>
			  <td class="NormalText"><div align="right">
		<?php echo $row["netsales"];
		$cattotal = $cattotal + $row["netsales"];
		$grandtotal = $grandtotal + $row["netsales"];
		$totalarray[$i] = $totalarray[$i] + $row["netsales"];
		?>
			 </div></td>
		<?php
		 if($i < $revenuecentercount - 1) { 
		   $row = mysql_fetch_array($result);
		  }
		  } ?>

	       <td class="NormalText"><div align="right"><?php echo number_format($cattotal, 2, '.', ''); ?>
          </div></td>
        </tr>
        <?php 
	    } 
		?>
        <tr>
          <td height="23" bgcolor="#F2F2F2" class="NormalText"><strong>Totals</strong></td>
      
      <?php   for($i=0;$i<$revenuecentercount;$i++) {
	   ?>
          <td bgcolor="#F2F2F2" class="NormalText"><div align="right"><strong><?php echo number_format($totalarray[$i], 2, '.', ''); ?></strong></div></td>
		<?php  
	} ?>    

          
          <td bgcolor="#F2F2F2" class="NormalText"><div align="right"><strong><?php echo number_format($grandtotal, 2, '.', ''); ?></strong></div></td>
        </tr>
      </table>
      <?php // END - NET SALES BY CATEGORY (NON SALES CATEGORIES) ///////////////////////////////////////   ?>

      </div>
      <br />
      <br />
      <div align="center">
        <?php // START - NET SALES BY CATEGORY (NON SALES CATEGORIES) ///////////////////////////////////////   ?>
        <span class="NormalHeading">Net Sales By Day Part</span><br />
      </div>
      <table width="414" height="64" border="0" align="center" cellpadding="2" cellspacing="0">
        <tr>
          <td width="240" height="18" bgcolor="#487CC4" class="NormalText"><span class="style4">Day Part</span></td>
          <?php 
 
 	// Reset Totals
	$grandtotal =0;
	$cattotal = 0;
	unset($totalarray);
 // CREATE THE COLUMS FOR EACH REVENUE CENTER
 $result = GetRevenueCenterNames($sumid);
 $revenuecentercount = mysql_num_rows($result);
 while($row = mysql_fetch_array($result)) { 
 ?>
          <td width="98" bgcolor="#487CC4" class="NormalText"><div align="right" class="style4"><?php echo $row["revenuecentername"]; ?></div></td>
          <?php  } // Colums created ?>
          <td width="87" bgcolor="#487CC4" class="NormalText"><div align="right" class="style4"> Total</div></td>
        </tr>
        <?php 
   // ROW - WHILE IN SAME SALES CAT

	   $result = GetNetSalesByDayPart($sumid);

       $totalarray = array();
	   while($row = mysql_fetch_array($result)) { 
	   ?>
        <tr>
          <td height="23" class="NormalText"><div align="left"><strong><?php echo $row["dpname"];?></strong></div></td>
          <?php 


   	$cattotal = 0;
   for($i=0;$i<$revenuecentercount;$i++) {
	   ?>
          <td class="NormalText"><div align="right"> <?php echo $row["netsales"];
		$cattotal = $cattotal + $row["netsales"];
		$grandtotal = $grandtotal + $row["netsales"];
		$totalarray[$i] = $totalarray[$i] + $row["netsales"];
		?> </div></td>
          <?php
		 if($i < $revenuecentercount - 1) { 
		   $row = mysql_fetch_array($result);
		  }
		  } ?>
          <td class="NormalText"><div align="right"><?php echo number_format($cattotal, 2, '.', ''); ?> </div></td>
        </tr>
        <?php 
	    } 
		?>
        <tr>
          <td height="23" bgcolor="#F2F2F2" class="NormalText"><strong>Totals</strong></td>
          <?php   for($i=0;$i<$revenuecentercount;$i++) {
	   ?>
          <td bgcolor="#F2F2F2" class="NormalText"><div align="right"><strong><?php echo number_format($totalarray[$i], 2, '.', ''); ?></strong></div></td>
          <?php  
	} ?>
          <td bgcolor="#F2F2F2" class="NormalText"><div align="right"><strong><?php echo number_format($grandtotal, 2, '.', ''); ?></strong></div></td>
        </tr>
      </table>
      <p>
        <?php // END - NET SALES BY DAY PART ///////////////////////////////////////   ?>
        </div>      
      </p>
      <div align="center">
        <?php // START - BANKING SALES BY CATEGORY (NON SALES CATEGORIES) ///////////////////////////////////////   ?>
        <span class="NormalHeading">Banking Sales By Day Part</span><br />
      </div>
      <table width="414" height="64" border="0" align="center" cellpadding="2" cellspacing="0">
        <tr>
          <td width="240" height="18" bgcolor="#487CC4" class="NormalText"><span class="style7">Day Part</span></td>
          <?php 
 
 	// Reset Totals
	$grandtotal =0;
	$cattotal = 0;
	unset($totalarray);
 // CREATE THE COLUMS FOR EACH REVENUE CENTER
 $result = GetRevenueCenterNames($sumid);
 $revenuecentercount = mysql_num_rows($result);
 while($row = mysql_fetch_array($result)) { 
 ?>
          <td width="94" bgcolor="#487CC4" class="NormalText"><div align="right" class="style7"><?php echo $row["revenuecentername"]; ?></div></td>
          <?php  } // Colums created ?>
          <td width="87" bgcolor="#487CC4" class="NormalText"><div align="right" class="style7"> Total</div></td>
        </tr>
        <?php 
   // ROW - WHILE IN SAME SALES CAT

	   $result = GetBankingSalesByDayPart($sumid);

       $totalarray = array();
	   while($row = mysql_fetch_array($result)) { 
	   ?>
        <tr>
          <td height="23" class="NormalText"><div align="left"><strong><?php echo $row["dpname"];?></strong></div></td>
          <?php 


   	$cattotal = 0;
   for($i=0;$i<$revenuecentercount;$i++) {
	   ?>
          <td class="NormalText"><div align="right"> <?php echo $row["bankingsales"];
		$cattotal = $cattotal + $row["bankingsales"];
		$grandtotal = $grandtotal + $row["bankingsales"];
		$totalarray[$i] = $totalarray[$i] + $row["bankingsales"];
		?> </div></td>
          <?php
		 if($i < $revenuecentercount - 1) { 
		   $row = mysql_fetch_array($result);
		  }
		  } ?>
          <td class="NormalText"><div align="right"><?php echo number_format($cattotal, 2, '.', ''); ?> </div></td>
        </tr>
        <?php 
	    } 
		?>
        <tr>
          <td height="23" bgcolor="#F2F2F2" class="NormalText"><strong>Totals</strong></td>
          <?php   for($i=0;$i<$revenuecentercount;$i++) {
	   ?>
          <td bgcolor="#F2F2F2" class="NormalText"><div align="right"><strong><?php echo number_format($totalarray[$i], 2, '.', ''); ?></strong></div></td>
          <?php  
	} ?>
          <td bgcolor="#F2F2F2" class="NormalText"><div align="right"><strong><?php echo number_format($grandtotal, 2, '.', ''); ?></strong></div></td>
        </tr>
      </table>
      <?php // END - NET SALES BY DAY PART ///////////////////////////////////////   ?>
      <div align="center"><br />
        <br />
        <?php // START - NET SALES BY CATEGORY (NON SALES CATEGORIES) ///////////////////////////////////////   ?>
        <span class="NormalHeading">Number of Guests</span><br />
      </div>
      <table width="414" height="64" border="0" align="center" cellpadding="2" cellspacing="0">
        <tr>
          <td width="240" height="18" bgcolor="#487CC4" class="NormalText"><span class="style4">Day Part</span></td>
          <?php 
 
 	// Reset Totals
	$grandtotal =0;
	$cattotal = 0;
	unset($totalarray);
 // CREATE THE COLUMS FOR EACH REVENUE CENTER
 $result = GetRevenueCenterNames($sumid);
 $revenuecentercount = mysql_num_rows($result);
 while($row = mysql_fetch_array($result)) { 
 ?>
          <td width="98" bgcolor="#487CC4" class="NormalText"><div align="right" class="style4"><?php echo $row["revenuecentername"]; ?></div></td>
          <?php  } // Colums created ?>
          <td width="87" bgcolor="#487CC4" class="NormalText"><div align="right" class="style4"> Total</div></td>
        </tr>
        <?php 
   // ROW - WHILE IN SAME SALES CAT

	   $result = GetGuestsByDayPart($sumid);

       $totalarray = array();
	   while($row = mysql_fetch_array($result)) { 
	   ?>
        <tr>
          <td height="23" class="NormalText"><div align="left"><strong><?php echo $row["dpname"];?></strong></div></td>
          <?php 


   	$cattotal = 0;
   for($i=0;$i<$revenuecentercount;$i++) {
	   ?>
          <td class="NormalText"><div align="right"> <?php echo $row["headcount"];
		$cattotal = $cattotal + $row["headcount"];
		$grandtotal = $grandtotal + $row["headcount"];
		$totalarray[$i] = $totalarray[$i] + $row["headcount"];
		?> </div></td>
          <?php
		 if($i < $revenuecentercount - 1) { 
		   $row = mysql_fetch_array($result);
		  }
		  } ?>
          <td class="NormalText"><div align="right"><?php echo number_format($cattotal, 0, '.', ''); ?> </div></td>
        </tr>
        <?php 
	    } 
		?>
        <tr>
          <td height="23" bgcolor="#F2F2F2" class="NormalText"><strong>Totals</strong></td>
          <?php   for($i=0;$i<$revenuecentercount;$i++) {
	   ?>
          <td bgcolor="#F2F2F2" class="NormalText"><div align="right"><strong><?php echo number_format($totalarray[$i], 0, '.', ''); ?></strong></div></td>
          <?php  
	} ?>
          <td bgcolor="#F2F2F2" class="NormalText"><div align="right"><strong><?php echo number_format($grandtotal, 0, '.', ''); ?></strong></div></td>
        </tr>
      </table>
      <p>
        <?php // END - NET SALES BY DAY PART ///////////////////////////////////////   ?>
        </div>
      </p>
      <div align="center">
        <?php // START - NET SALES BY CATEGORY (NON SALES CATEGORIES) ///////////////////////////////////////   ?>
        <span class="NormalHeading">Number of Checks</span><br />
      </div>
      <table width="414" height="64" border="0" align="center" cellpadding="2" cellspacing="0">
        <tr>
          <td width="240" height="18" bgcolor="#487CC4" class="NormalText"><span class="style4">Day Part</span></td>
          <?php 
 
 	// Reset Totals
	$grandtotal =0;
	$cattotal = 0;
	unset($totalarray);
 // CREATE THE COLUMS FOR EACH REVENUE CENTER
 $result = GetRevenueCenterNames($sumid);
 $revenuecentercount = mysql_num_rows($result);
 while($row = mysql_fetch_array($result)) { 
 ?>
          <td width="98" bgcolor="#487CC4" class="NormalText"><div align="right" class="style4"><?php echo $row["revenuecentername"]; ?></div></td>
          <?php  } // Colums created ?>
          <td width="87" bgcolor="#487CC4" class="NormalText"><div align="right" class="style4"> Total</div></td>
        </tr>
        <?php 
   // ROW - WHILE IN SAME SALES CAT

	   $result = GetCheckCountByDayPart($sumid);

       $totalarray = array();
	   while($row = mysql_fetch_array($result)) { 
	   ?>
        <tr>
          <td height="23" class="NormalText"><div align="left"><strong><?php echo $row["dpname"];?></strong></div></td>
          <?php 


   	$cattotal = 0;
   for($i=0;$i<$revenuecentercount;$i++) {
	   ?>
          <td class="NormalText"><div align="right"> <?php echo $row["checkcount"];
		$cattotal = $cattotal + $row["checkcount"];
		$grandtotal = $grandtotal + $row["checkcount"];
		$totalarray[$i] = $totalarray[$i] + $row["checkcount"];
		?> </div></td>
          <?php
		 if($i < $revenuecentercount - 1) { 
		   $row = mysql_fetch_array($result);
		  }
		  } ?>
          <td class="NormalText"><div align="right"><?php echo number_format($cattotal, 0, '.', ''); ?> </div></td>
        </tr>
        <?php 
	    } 
		?>
        <tr>
          <td height="23" bgcolor="#F2F2F2" class="NormalText"><strong>Totals</strong></td>
          <?php   for($i=0;$i<$revenuecentercount;$i++) {
	   ?>
          <td bgcolor="#F2F2F2" class="NormalText"><div align="right"><strong><?php echo number_format($totalarray[$i], 0, '.', ''); ?></strong></div></td>
          <?php  
	} ?>
          <td bgcolor="#F2F2F2" class="NormalText"><div align="right"><strong><?php echo number_format($grandtotal, 0, '.', ''); ?></strong></div></td>
        </tr>
      </table>
      <?php // END - NET SALES BY DAY PART ///////////////////////////////////////   ?>
      </div>
      <div align="center"><br />
        
        <?php // START - NET SALES BY CATEGORY (NON SALES CATEGORIES) ///////////////////////////////////////   ?>
        <span class="NormalHeading">Taxes By Tax ID</span></div>
      <table width="414" height="64" border="0" align="center" cellpadding="2" cellspacing="0">
        <tr>
          <td width="240" height="18" bgcolor="#487CC4" class="NormalText"><span class="style4">Tax</span></td>
          <?php 
 
 	// Reset Totals
	$grandtotal =0;
	$cattotal = 0;
	unset($totalarray);
 // CREATE THE COLUMS FOR EACH REVENUE CENTER
 $result = GetRevenueCenterNames($sumid);
 $revenuecentercount = mysql_num_rows($result);
 while($row = mysql_fetch_array($result)) { 
 ?>
          <td width="98" bgcolor="#487CC4" class="NormalText"><div align="right" class="style4"><?php echo $row["revenuecentername"]; ?></div></td>
          <?php  } // Colums created ?>
          <td width="87" bgcolor="#487CC4" class="NormalText"><div align="right" class="style4"> Total</div></td>
        </tr>
        <?php 
   // ROW - WHILE IN SAME SALES CAT

	   $result = GetRevenueCenterTax($sumid);

       $totalarray = array();
	   while($row = mysql_fetch_array($result)) { 
	   ?>
        <tr>
          <td height="23" class="NormalText"><div align="left"><strong><?php echo $row["taxname"];?></strong></div></td>
          <?php 


   	$cattotal = 0;
   for($i=0;$i<$revenuecentercount;$i++) {
	   ?>
          <td class="NormalText"><div align="right"> <?php echo $row["taxamount"];
		$cattotal = $cattotal + $row["taxamount"];
		$grandtotal = $grandtotal + $row["taxamount"];
		$totalarray[$i] = $totalarray[$i] + $row["taxamount"];
		?> </div></td>
          <?php
		 if($i < $revenuecentercount - 1) { 
		   $row = mysql_fetch_array($result);
		  }
		  } ?>
          <td class="NormalText"><div align="right"><?php echo number_format($cattotal, 2, '.', ''); ?> </div></td>
        </tr>
        <?php 
	    } 
		?>
        <tr>
          <td height="23" bgcolor="#F2F2F2" class="NormalText"><strong>Totals</strong></td>
          <?php   for($i=0;$i<$revenuecentercount;$i++) {
	   ?>
          <td bgcolor="#F2F2F2" class="NormalText"><div align="right"><strong><?php echo number_format($totalarray[$i], 2, '.', ''); ?></strong></div></td>
          <?php  
	} ?>
          <td bgcolor="#F2F2F2" class="NormalText"><div align="right"><strong><?php echo number_format($grandtotal, 2, '.', ''); ?></strong></div></td>
        </tr>
      </table>
      <?php // END - NET SALES BY DAY PART ///////////////////////////////////////   ?>
      <div align="center"><br />

        <?php // START - NET SALES BY CATEGORY (NON SALES CATEGORIES) ///////////////////////////////////////   ?>
      <span class="NormalHeading">Comps</span></div>
      <table width="414" height="64" border="0" align="center" cellpadding="2" cellspacing="0">
        <tr>
          <td width="240" height="18" bgcolor="#487CC4" class="NormalText"><span class="style4">Comp</span></td>
          <?php 
 
 	// Reset Totals
	$grandtotal =0;
	$cattotal = 0;
	unset($totalarray);
 // CREATE THE COLUMS FOR EACH REVENUE CENTER
 $result = GetRevenueCenterNames($sumid);
 $revenuecentercount = mysql_num_rows($result);
 while($row = mysql_fetch_array($result)) { 
 ?>
          <td width="98" bgcolor="#487CC4" class="NormalText"><div align="right" class="style4"><?php echo $row["revenuecentername"]; ?></div></td>
          <?php  } // Colums created ?>
          <td width="87" bgcolor="#487CC4" class="NormalText"><div align="right" class="style4"> Total</div></td>
        </tr>
        <?php 
   // ROW - WHILE IN SAME SALES CAT

	   $result = GetRevenueCenterComps ($sumid);

       $totalarray = array();
	   while($row = mysql_fetch_array($result)) { 
	   ?>
        <tr>
          <td height="23" class="NormalText"><div align="left"><strong><?php echo $row["compname"];?></strong></div></td>
          <?php 


   	$cattotal = 0;
   for($i=0;$i<$revenuecentercount;$i++) {
	   ?>
          <td class="NormalText"><div align="right"> <?php echo $row["compamount"];
		$cattotal = $cattotal + $row["compamount"];
		$grandtotal = $grandtotal + $row["compamount"];
		$totalarray[$i] = $totalarray[$i] + $row["compamount"];
		?> </div></td>
          <?php
		 if($i < $revenuecentercount - 1) { 
		   $row = mysql_fetch_array($result);
		  }
		  } ?>
          <td class="NormalText"><div align="right"><?php echo number_format($cattotal, 2, '.', ''); ?> </div></td>
        </tr>
        <?php 
	    } 
		?>
        <tr>
          <td height="23" bgcolor="#F2F2F2" class="NormalText"><strong>Totals</strong></td>
          <?php   for($i=0;$i<$revenuecentercount;$i++) {
	   ?>
          <td bgcolor="#F2F2F2" class="NormalText"><div align="right"><strong><?php echo number_format($totalarray[$i], 2, '.', ''); ?></strong></div></td>
          <?php  
	} ?>
          <td bgcolor="#F2F2F2" class="NormalText"><div align="right"><strong><?php echo number_format($grandtotal, 2, '.', ''); ?></strong></div></td>
        </tr>
      </table>
      <?php // END - NET SALES BY DAY PART ///////////////////////////////////////   ?>
      <div align="center">
        <p>
          <?php // START - NET SALES BY CATEGORY (NON SALES CATEGORIES) ///////////////////////////////////////   ?>
          <span class="NormalHeading">Payments</span><br />
        </p>
      </div>
      <table width="414" height="64" border="0" align="center" cellpadding="2" cellspacing="0">
        <tr>
          <td width="240" height="18" bgcolor="#487CC4" class="NormalText"><span class="style4">Payments</span></td>
          <?php 
 
 	// Reset Totals
	$grandtotal =0;
	$cattotal = 0;
	unset($totalarray);
 // CREATE THE COLUMS FOR EACH REVENUE CENTER
 $result = GetRevenueCenterNames($sumid);
 $revenuecentercount = mysql_num_rows($result);
 while($row = mysql_fetch_array($result)) { 
 ?>
          <td width="98" bgcolor="#487CC4" class="NormalText"><div align="right" class="style4"><?php echo $row["revenuecentername"]; ?></div></td>
          <?php  } // Colums created ?>
          <td width="87" bgcolor="#487CC4" class="NormalText"><div align="right" class="style4"> Total</div></td>
        </tr>
        <?php 
   // ROW - WHILE IN SAME SALES CAT

	   $result = GetRevenueCenterPayments($sumid);

       $totalarray = array();
	   while($row = mysql_fetch_array($result)) { 
	   ?>
        <tr>
          <td height="23" class="NormalText"><div align="left"><strong><?php echo $row["pmttype"];?></strong></div></td>
          <?php 


   	$cattotal = 0;

   for($i=0;$i<$revenuecentercount;$i++) {
	   ?>
        <td class="NormalText"><div align="right"> <?php 

			echo $row["paymentamount"];
			$cattotal = $cattotal + $row["paymentamount"];
			$grandtotal = $grandtotal + $row["paymentamount"];
			$totalarray[$i] = $totalarray[$i] + $row["paymentamount"];

		
		
		?> </div></td>
          <?php
		 if($i < $revenuecentercount - 1) { 
		   $row = mysql_fetch_array($result);
		  }
		  } ?>
          <td class="NormalText"><div align="right"><?php echo number_format($cattotal, 2, '.', ''); ?> </div></td>
        </tr>
        <?php 
	    } 
		?>
        <tr>
          <td height="23" bgcolor="#F2F2F2" class="NormalText"><strong>Totals</strong></td>
          <?php   for($i=0;$i<$revenuecentercount;$i++) {
	   ?>
          <td bgcolor="#F2F2F2" class="NormalText"><div align="right"><strong><?php echo number_format($totalarray[$i], 2, '.', ''); ?></strong></div></td>
          <?php  
	} ?>
          <td bgcolor="#F2F2F2" class="NormalText"><div align="right"><strong><?php echo number_format($grandtotal, 2, '.', ''); ?></strong></div></td>
        </tr>
      </table>
      <?php // END - NET SALES BY DAY PART ///////////////////////////////////////   ?>
      <div align="center">
        <p>
          <?php // START - NET SALES BY CATEGORY (NON SALES CATEGORIES) ///////////////////////////////////////   ?>
          <span class="NormalHeading">Tips</span><br />
        </p>
      </div>
      <table width="414" height="64" border="0" align="center" cellpadding="2" cellspacing="0">
        <tr>
          <td width="240" height="18" bgcolor="#487CC4" class="NormalText"><span class="style4">Tip Type</span></td>
          <?php 
 
 	// Reset Totals
	$grandtotal =0;
	$cattotal = 0;
	unset($totalarray);
 // CREATE THE COLUMS FOR EACH REVENUE CENTER
 $result = GetRevenueCenterNames($sumid);
 $revenuecentercount = mysql_num_rows($result);
 while($row = mysql_fetch_array($result)) { 
 ?>
          <td width="98" bgcolor="#487CC4" class="NormalText"><div align="right" class="style4"><?php echo $row["revenuecentername"]; ?></div></td>
          <?php  } // Colums created ?>
          <td width="87" bgcolor="#487CC4" class="NormalText"><div align="right" class="style4"> Total</div></td>
        </tr>
        <?php 
   // ROW - WHILE IN SAME SALES CAT

	   $result =  GetTips($sumid);

       $totalarray = array();
	   while($row = mysql_fetch_array($result)) { 
	   ?>
        <tr>
          <td height="23" class="NormalText"><div align="left"><strong><?php echo $row["pmttype"];?></strong></div></td>
          <?php 


   	$cattotal = 0;
   for($i=0;$i<$revenuecentercount;$i++) {
	   ?>
          <td class="NormalText"><div align="right"> <?php echo $row["tips"];
		$cattotal = $cattotal + $row["tips"];
		$grandtotal = $grandtotal + $row["tips"];
		$totalarray[$i] = $totalarray[$i] + $row["tips"];
		?> </div></td>
          <?php
		 if($i < $revenuecentercount - 1) { 
		   $row = mysql_fetch_array($result);
		  }
		  } ?>
          <td class="NormalText"><div align="right"><?php echo number_format($cattotal, 2, '.', ''); ?> </div></td>
        </tr>
        <?php 
	    } 
		?>
        <tr>
          <td height="23" bgcolor="#F2F2F2" class="NormalText"><strong>Totals</strong></td>
          <?php   for($i=0;$i<$revenuecentercount;$i++) {
	   ?>
          <td bgcolor="#F2F2F2" class="NormalText"><div align="right"><strong><?php echo number_format($totalarray[$i], 2, '.', ''); ?></strong></div></td>
          <?php  
	} ?>
          <td bgcolor="#F2F2F2" class="NormalText"><div align="right"><strong><?php echo number_format($grandtotal, 2, '.', ''); ?></strong></div></td>
        </tr>
      </table>
      <?php // END - NET SALES BY DAY PART ///////////////////////////////////////   ?>
<p><br />
      </p>
      <div align="center">

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