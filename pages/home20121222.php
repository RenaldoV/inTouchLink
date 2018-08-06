<link href="../style.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
.style1 {color: #FFFFFF}
.style2 {font-size: 12px; line-height: normal; color: #333333; text-decoration: none; font-family: Verdana, Arial, Helvetica, sans-serif;}
.style3 {color: #FFFFFF; font-weight: bold; }
-->
</style>
<table width="940" height="410" border="0" cellpadding="5" cellspacing="0">
  <tr>
    <td width="738" height="410" valign="top"><p class="NormalHeading">Welcome to  inTouchLink. <br />
        <span class="NormalText"> If there are any additional reports that you would like to see, please contact us on 0861 111 438 or <a href="mailto:info@intouchpos.co.za">info@intouchpos.co.za</a>. </span></p>
      <hr width="930" size="1" />
      <?php 
	  if($_SESSION["userUserID"] == "demo") {
	  
	  echo "<p class='NormalHeading'>Welcome to the Demo Account of inTouchLink.<br /><span class='NormaBoldRed'>Please Note : There is only demo data for November 2012. </span></p>";
	  }
	  
	  ?>
      <p class="NormalHeading">        Yesterday's Available Reports <br />
        <span class="style2">Date : <?php echo $yesterdayday."/".$yesterdaymonth."/".$yesterdayyear?></span><br />
        <br />
        <span class="NormalText">Click on the active icons to view the reports. </span></p>
      <table width="920" border="1" align="center"  cellpadding="2" cellspacing="1" bordercolor="#F5F5F5" class="SmallText">
        <tr>
          <td width="14%" bgcolor="#007CC4" class="style1 SmallText"><strong>Store</strong></td>
          <td width="9%" bgcolor="#007CC4" class="SmallText"><div align="center" class="style3">Sales <br />
          Summary </div></td>
          <td width="9%" bgcolor="#007CC4" class="SmallText"><div align="center" class="style1"><strong>Total <br />
          Summary</strong></div></td>
          <td width="9%" bgcolor="#007CC4" class="SmallText"><div align="center" class="style3">Comps</div></td>
          <td width="8%" bgcolor="#007CC4" class="SmallText"><div align="center" class="style3">Product Mix </div></td>
          <td width="8%" bgcolor="#007CC4" class="SmallText"><div align="center" class="style3">Voids</div></td>
          <td width="8%" bgcolor="#007CC4" class="SmallText"><div align="center" class="style3">Rev Center Sales</div></td>
          <td width="8%" bgcolor="#007CC4" class="SmallText"><div align="center" class="style3">Hourly Sales &amp; Labour</div></td>
          <td width="4%" bgcolor="#007CC4" class="SmallText"><div align="center" class="style1"><strong>Payments</strong></div></td>
          <td width="4%" bgcolor="#007CC4" class="SmallText"><div align="center" class="style1"><strong>Purchases</strong></div></td>
          <td width="8%" bgcolor="#007CC4" class="SmallText"><div align="center" class="style1"><strong>Service Speed</strong></div></td>
          <td width="8%" bgcolor="#007CC4" class="SmallText"><div align="center" class="style1"><strong>Performance</strong></div></td>
          <td width="8%" bgcolor="#007CC4" class="SmallText"><div align="center" class="style1"><strong>Exceptions</strong></div></td>
        </tr>
    <?php 
	                    
				$result = GetStoresThatUserCanAccess($_SESSION["usrid"]);
				  while($row = mysql_fetch_array($result)) {

				$reportresult = GetSummaryGrossSales($yesterdayyear."/".$yesterdaymonth."/".$yesterdayday, "'".$row["strid"]."'");   
				$reportrow = mysql_fetch_array($reportresult);
				if(mysql_num_rows($reportresult) > 0) { // there is a record
					  if($reportrow["sumgrosssales"] != '0.00') {   //All Data Available
						 $showreport = 'true';
						 $storeclosed = 'false';
					  } else { // Store Closed
					   $showreport = 'false';
					   $storeclosed = 'true';
					  }
				} else { // No record at all found
				   $showreport = 'false';
				   $storeclosed = 'false';
				}				
				
	?>
	    <tr valign="middle">
          <td align="left" valign="middle" bgcolor="#F5F5F5" class="SmallText"><strong><?php echo $row["strname"]; ?></strong></td>
          <?php 
if($storeclosed == 'false') {
?>
          <td align="center"   ><?php if ($showreport == 'true') {echo "<a href='index.php?p=report_salessummary&ql=1&a=s&cmbstore=".$row["strid"]."'><img  src='images/ico_datayes.jpg' width='32' height='32' border = '0' />";} else {echo "<img src='images/ico_datano.jpg' width='32' height='32' /></a>"; } ?>  </td>
          <td align="center"  ><?php if ($showreport == 'true') {echo "<a href='index.php?p=report_totalsummary&a=s&ql=1&cmbstore=".$row["strid"]."'><img  src='images/ico_datayes.jpg' width='32' height='32' border = '0' />";} else {echo "<img src='images/ico_datano.jpg' width='32' height='32' /></a>"; } ?></td>
          <td align="center"  ><?php if ($showreport == 'true') {echo "<a href='index.php?p=report_comps&a=s&ql=1&cmbstore=".$row["strid"]."'><img  src='images/ico_datayes.jpg' width='32' height='32' border = '0' />";} else {echo "<img src='images/ico_datano.jpg' width='32' height='32' /></a>"; } ?></td>
          <td align="center"  ><?php if ($showreport == 'true') {echo "<a href='index.php?p=report_productmix&a=s&ql=1&cmbstore=".$row["strid"]."'><img  src='images/ico_datayes.jpg' width='32' height='32' border = '0' />";} else {echo "<img src='images/ico_datano.jpg' width='32' height='32' /></a>"; } ?></td>
          <td align="center"  ><?php if ($showreport == 'true') {echo "<a href='index.php?p=report_voids&a=s&ql=1&cmbstore=".$row["strid"]."'><img  src='images/ico_datayes.jpg' width='32' height='32' border = '0' />";} else {echo "<img src='images/ico_datano.jpg' width='32' height='32' /></a>"; } ?></td>
          <td align="center"  ><?php if ($showreport == 'true') {echo "<a href='index.php?p=report_revenuecentersales&a=s&ql=1&cmbstore=".$row["strid"]."'><img  src='images/ico_datayes.jpg' width='32' height='32' border = '0' />";} else {echo "<img src='images/ico_datano.jpg' width='32' height='32' /></a>"; } ?></td>
          <td align="center" ><?php if ($showreport == 'true') {echo "<a href='index.php?p=report_hourlysalesandlabour&a=s&ql=1&cmbstore=".$row["strid"]."'><img  src='images/ico_datayes.jpg' width='32' height='32' border = '0' />";} else {echo "<img src='images/ico_datano.jpg' width='32' height='32' /></a>"; } ?></td>
          <td align="center"  ><?php if ($showreport == 'true') {echo "<a href='index.php?p=report_payments&a=s&ql=1&cmbstore=".$row["strid"]."'><img  src='images/ico_datayes.jpg' width='32' height='32' border = '0' />";} else {echo "<img src='images/ico_datano.jpg' width='32' height='32' /></a>"; } ?></td>
          <td align="center"  ><?php if ($showreport == 'true') {echo "<a href='index.php?p=report_instockPurchases&a=s&ql=1&cmbstore=".$row["strid"]."'><img  src='images/ico_datayes.jpg' width='32' height='32' border = '0' />";} else {echo "<img src='images/ico_datano.jpg' width='32' height='32' /></a>"; } ?></td>          
          <td align="center"  ><?php if ($showreport == 'true') {echo "<a href='index.php?p=report_speedofservice&a=s&ql=1&cmbstore=".$row["strid"]."'><img  src='images/ico_datayes.jpg' width='32' height='32' border = '0' />";} else {echo "<img src='images/ico_datano.jpg' width='32' height='32' /></a>"; } ?></td>
          <td align="center"  ><?php if ($showreport == 'true') {echo "<a href='index.php?p=report_storeperformance&a=s&ql=1&cmbstore=".$row["strid"]."'><img  src='images/ico_datayes.jpg' width='32' height='32' border = '0' />";} else {echo "<img src='images/ico_datano.jpg' width='32' height='32' /></a>"; } ?></td>
        <td align="center"  ><?php if ($showreport == 'true') {echo "<a href='index.php?p=report_exceptions&a=s&ql=1&cmbstore=".$row["strid"]."'><img  src='images/ico_datayes.jpg' width='32' height='32' border = '0' />";} else {echo "<img src='images/ico_datano.jpg' width='32' height='32' /></a>"; } ?></td>
        <?php
}  
if($storeclosed == 'true') {
echo "<td colspan='8' class='NormaBoldRed' height='32' align='center' bgcolor='#EEEEEE' valign='middle' class='SmallText'><div align='left'>Store Closed or Zero Sales</div></td>";
}
?>
        </tr>
     <?php }?>
      </table>
      <div align="center"><span class="SmallText"><strong><br />
        </strong></span><span class="NormalText"><strong>Note :</strong> If a report shows that no results were returned, then the store did not perform a action in that category, 
          for example, no comps given or voids captured. </span><br />
        <br />
      </div>
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="6%" class="SmallText"><img src="images/ico_datayes.jpg" width="32" height="32" /></td>
          <td width="94%" class="SmallText"><strong>Data Available </strong></td>
        </tr>
        <tr>
          <td class="SmallText"><img src="images/ico_datano.jpg" width="32" height="32" /></td>
          <td class="SmallText"><strong>No Data Available </strong></td>
        </tr>
      </table>      <p class="NormalHeading"><br />
        <br />
      </p></td>
  </tr>
</table>
