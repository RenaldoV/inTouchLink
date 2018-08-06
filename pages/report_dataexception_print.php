<?php 
session_start();
require_once('../library/library.php');
db_connect();

// Parameter Fields
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

$yesterdaydate  = mktime(date("H") + $gmtoffset, date("i"), date("s"), date("m"),date("d")-1, date("Y"));	 
$yesterdayday = date( "d",$yesterdaydate);
$yesterdaymonth = date( "m",$yesterdaydate);
$yesterdayyear = date( "Y",$yesterdaydate);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Data Exception Report</title>
<link href="../style.css" rel="stylesheet" type="text/css" />
<style type="text/css">
<!--
.style1 {color: #FFFFFF}
.style3 {color: #FFFFFF; font-weight: bold; }
-->
</style>
</head>

<body>
<link href="../style.css" rel="stylesheet" type="text/css">
<div align="center"><br />
  
</div>
<div align="center"><img src="../images/report_header.gif" width="600" height="73" border="0" /></div>
<table width="600" height="286" border="0" align="center" cellpadding="5" cellspacing="0">
  
  <tr>
    <td width="590" height="286" valign="top"><p align="center" class="NormalHeading">
      <?php if($a == "s") { 
     
// If All groups selected then get all stores user can access
if($radStores == "store") {
$result = GetStoresThatUserCanAccess($_SESSION["usrid"]);
$row = mysql_fetch_array($result);
	$storelist = "'".$row["strid"]."'"; // Set the first ID.
	while ($row = mysql_fetch_array($result)) { // Add all IDs into the store session as a string
		$storelist = $storelist.",'".$row["strid"]."'";
	}	
	$result = GetLatestStoreDataDate($storelist);
}
// Get the stores from the specific group chosen
if($radStores == "storegroup") { 
	$result = GetLatestStoreDataDate($_SESSION["store"]);	
}	
if(mysql_num_rows($result) > 0 ) {
	?>
      <br />
</p>
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="48">&nbsp;</td>
          <td width="690"><div align="center" class="NormalText"><span class="NormalHeading"><strong>Data Exception Report</strong></span><strong><br />
            for<br />
              </strong>
                  <?php 
if($radStores == "store") {
	echo "All Groups"; 

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
      <p class="NormalText"> </p>
      <table width="100%" border="1" align="center" cellpadding="2" cellspacing="0" bordercolor="#F5F5F5">
        <tr>
          <td width="14%" bgcolor="#007CC4" class="NormalText"><span class="style1"><strong>Store </strong></span></td>
          <td width="9%" bgcolor="#007CC4" class="NormalText"><div align="center" class="style3">Last Available Date</div></td>
          <td width="9%" bgcolor="#007CC4" class="NormalText"><div align="center" class="style1"><strong>Contact Name</strong></div></td>
          <td width="9%" bgcolor="#007CC4" class="NormalText"><div align="center" class="style1"><strong>Contact Tel No</strong></div></td>
          <td width="9%" bgcolor="#007CC4" class="NormalText"><div align="center" class="style1"><strong>Store Tel No</strong></div></td>
        </tr>
        <?php 
while($row = mysql_fetch_array($result)) {
if($row["latestdate"] != $yesterdayyear."/".$yesterdaymonth."/".$yesterdayday || $row["latestdate"] == null ) {
?>
        <tr>
          <td align="left" valign="middle" bgcolor="#F5F5F5" class="NormalText"><strong><?php echo $row["strname"]; ?></strong></td>
          <td align="center" valign="middle" class="NormalText"><div align="center">
            <?php 
		  if($row["latestdate"] != null) {
		  echo $row["latestdate"]; 
		  } else {
		  echo "No Data";
		  }
		  
		  ?>
          </div>
              <div align="center"></div></td>
          <td align="center" valign="middle" class="NormalText"><div align="left">
              <?php if($row["strcontactemail"] != null) { ?>
              <a href="mailto:<?php echo $row["strcontactemail"]; ?>"><?php echo $row["strcontactname"]; ?></a>
              <?php } else { echo $row["strcontactname"];} ?>
            &nbsp;</div></td>
          <td align="center" valign="middle" class="NormalText"><div align="left"><?php echo $row["strcontactnumber"]; ?>&nbsp;</div></td>
          <td align="center" valign="middle" class="NormalText"><div align="left"><?php echo $row["strstorenumber"]; ?>&nbsp;</div></td>
        </tr>
        <?php }
	 } ?>
      </table>
      <div align="center"><span class="SmallText"><strong>
        <?php } else { ?>
        <br />
        </strong></span><span class="NormaBoldGreen"><strong>No results were returned for that query.<br />
          Please try different parameters. </strong></span><br />
  <?php }} ?>
      </div></td>

  </tr>
</table>
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