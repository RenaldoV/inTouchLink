<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>inTouchLink Database Notification</title>
<style type="text/css">
<!--
.style2 {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 14px;
}
-->
</style>
<link href="style.css" rel="stylesheet" type="text/css" />
</head>

<body>
<div align="center">
  <p><br />  
      <br />
      <img src="images/errormessage.gif" width="463" height="232" /></p>
  <p class="NormalText">go to <a href="index.php?lgoff=1">home page</a></p>
  <p class="NormalText"><?php 
 

 
  // Send error message
  
    $message = "An inTouchLink error message has been generated\n";
    $message = $message."in page : ".$_REQUEST["u"]."\n";
	$message = $message."\n";
	$message = $message."Date : ".date("Y-m-d H:i:s")."\n";
	$message = $message."User ID : ".$_SESSION["userUserID"]."\n";
	$message = $message."Logged In : ".$_SESSION["loggedin"]."\n";
	$message = $message."Error Type : ".$_REQUEST["e"]."\n";
	$message = $message."Error Description : ".$_REQUEST["m"]."\n\n";


	//mail("ian@costofsale.co.za", "inTouchlink Error Message : ".date("Y-m-d H:i:s"), $message,"From: info@intouchlink.co.za");
	//mail("info@cognite.co.za", "inTouchlink Error Message : ".date("Y-m-d H:i:s"), $message,"From: info@intouchlink.co.za");	
  ?><br />
  </p>
</div>
</body>
</html>
