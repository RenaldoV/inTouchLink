<HTML>
<BODY bgcolor="#FFFFFF">

<?php

//include charts.php to access the InsertChart function
include "charts.php";
$chart = $_REQUEST["chart"];
set_time_limit(0);

?>

<table width="100%" height="100%" border="0" cellpadding="5" cellspacing="0">
  <tr>
    <td><div align="center"><img src="../images/report_header.gif" width="600" height="73">
      <?php 
	
	if($chart == "totalsummary") {
	    echo InsertChart ( "charts.swf", "charts_library", "totalsummary.php", 600, 400,"FFFFFF" ); 
	}
	if($chart == "totalcomparison") {
	    echo InsertChart ( "charts.swf", "charts_library", "totalcomparison.php", 600, 400,"FFFFFF" ); 
	}	
	
	
	
	?>
      <img src="../images/report_footer.gif" width="600" height="54"></div></td>
  </tr>
</table>
</BODY>
</HTML>