<?php

session_start(); // Start session
ini_set('error_reporting', E_ALL | E_STRICT);
ini_set('display_errors', 'Off');
//ini_set('log_errors', 'On');
//ini_set('error_log', 'php.log');
require_once('library/library.php'); // Make library functions available
set_time_limit(0); // Remove timeout

// Retrieve POSTED variables
$p = $_REQUEST["p"];
$a = $_REQUEST["a"];
$a2 = $_REQUEST["a2"];
$i = $_REQUEST["i"];
$hi = $_REQUEST["hi"];
$t = $_REQUEST["t"];
$lg = $_REQUEST["lg"];
$lgoff = $_REQUEST["lgoff"];
$parm1 = $_REQUEST["parm1"];
$srt = $_REQUEST["srt"];
$UID = $_REQUEST["UID"];
$PWD = $_REQUEST["PWD"];
$orderby = $_REQUEST["cmborder"];
$loginsource = $_REQUEST["logsrc"];
$quicklaunch = $_REQUEST["quicklaunch"];
$cmbdataexpetionorder = $_REQUEST["cmbdataexpetionorder"];
$timefrom = str_replace(":","",$_REQUEST["timefrom"]);
$timeto = str_replace(":","",$_REQUEST["timeto"]);
$salestype = $_REQUEST["salestype"]; // GROSS or NETT
$totaltype = $_REQUEST["totaltype"]; // FOR GROWTH COMPARISON FIGURE TYPE
$dayofweek = $_REQUEST["cmbdayofweek"];
$strid = $_REQUEST["str"];


//exceptions by nm
$saveexception =$_REQUEST["saveexception"];
if ($saveexception=='1')
{
    $_SESSION['cmbstore'] = $_REQUEST["cmbstore"];
 $exceptionstore = $_REQUEST["cmbstore"];
 $voids =$_REQUEST["txtVoids"];
 $refunds =$_REQUEST["txtRefunds"];
 $splits =$_REQUEST["txtSplits"];
 $clears =$_REQUEST["txtClears"];
 $transfers =$_REQUEST["txtTransfers"];
 $reopenedchecks = $_REQUEST["txtReopenedChecks"];
}



if($_SESSION["timefrom"] == null) {
  $_SESSION["timefrom"] = "0000";
  $_SESSION["timeto"] = "2359";
}
if($timefrom != "") { // New time range submitted
  $_SESSION["timefrom"] = $timefrom;
  $_SESSION["timeto"] = $timeto;
}

if($totaltype != null) {
$_SESSION["totaltype"] = $totaltype;
}

// Default Day of week
if($_SESSION["dayofweek"] == null) {
	$_SESSION["dayofweek"] = "All Days";
}
if($radDate == "") {//Date range submitted. Update day of week as well.
	$_SESSION["dayofweek"] = $dayofweek;
}


if($_SESSION["loggedin"] != 1) { //If not logged in then force to login page
 if($p == "home" || substr($p,0,6) == 'report' || substr($p,0,5) == 'admin'|| $p=="" || $p == null) { // if no page given, send to login page
   $p = "login";
 }
}

if($p != "homeaboutus" && $p != "homefeatures" && $p != "homereportsavailable" ) {
db_connect(); // Connect to database
}

// Server GMT offset (minus 2 hours)
$serverGMToffset = -2;


if($lgoff == 1) { // Log off and clear session
	$_SESSION["loggedin"] = 0; // Set logged in status to false
	$p = "login"; // Send to login page
	//Destroy the session.
	session_destroy();
}

if($lg == "1") { // Login attempt
$err = ""; // Clear login error message
$loginresult = IsLoginValid($UID,$PWD); // See if login details are valid

if(mysql_num_rows($loginresult) < 1) { // If login not valid then give error message
	$p = "login"; // Resend to login page
	$err = "Login is not valid. Please try again.";
} else { // Login is valid and set up session variables
	$loginrow = mysql_fetch_array($loginresult); // Get user session variables
	// Set Up Session
	$_SESSION["loggedin"] = 1; // Set login status to true
	$_SESSION["usrid"] = $loginrow["usrid"];
	$_SESSION["userUserID"] = $loginrow["usruserid"];
	$_SESSION["usr_adminacess"] = $loginrow["usradmin"]; // Set Admin rights
	$_SESSION["usr_poweruseracess"] = $loginrow["usrpoweruser"]; // Set poweruser rights
	$p = "home"; // Send to Home page

	// Get all basic user preferences on login
	$result = GetUserPrefs($_SESSION["usrid"]);
	$row = mysql_fetch_array($result);
	$_SESSION["usrpref_productmixorder"] =  $row["usrpref_productmixorder"];
	$_SESSION["usrpref_taxtype"] =  $row["usrpref_taxtype"];
	$_SESSION["usrpref_gmt"] = $row["usrpref_gmt"];
	$_SESSION["usrpref_daylightsavingstime"] =  $row["usrpref_daylightsavingstime"];
	$_SESSION["usrpref_salestype"] = $row["usrpref_salestype"];
	if($row["usrpref_exceptionreportorder"] == null) {
		$_SESSION["usrpref_exceptionreportorder"] = "Last available date";
	} else {
		$_SESSION["usrpref_exceptionreportorder"] = $row["usrpref_exceptionreportorder"];
	}
	}
}

// PREFERENCE UPDATES
// Set session if new
if($_SESSION["radDate"] == null) {
	$_SESSION["radDate"] = "date";
}
if($_SESSION["radStores"] == null) {
	$_SESSION["radStores"] = "store";
}
if($_REQUEST["radDate"] != "") {
	$_SESSION["radDate"] = $_REQUEST["radDate"];
}
if($_REQUEST["radStores"] != "") {
	$_SESSION["radStores"] = $_REQUEST["radStores"];
}
// Update Data Exception Report Pref
if($cmbdataexpetionorder != null) {
SetUserPref("usrpref_exceptionreportorder","$cmbdataexpetionorder",$_SESSION["usrid"]);
$_SESSION["usrpref_exceptionreportorder"] = $cmbdataexpetionorder;
}
// Sales type submitted
if($salestype != null) {
	SetUserPref("usrpref_salestype",$salestype,$_SESSION["usrid"]);
    $_SESSION["usrpref_salestype"] = $salestype;
}

// ADD PAGE HERE IF ONLY STORE & NOT STORE GROUP OPTION IS AVAILABLE
if($p == "report_voids" || $p == "report_voids_print" || $p == "report_payments_print" || $p == "report_payments" || $p == "report_hourlysalesandlabour" || $p == "report_hourlysalesandlabour_print" || $p == "report_revenuecentersales" || $p == "report_revenuecentersales_print" || $p == "report_serversales"  || $p == "report_serversales_print") {
  $_SESSION["radStores"] = "store";
}

// ADD PAGE HERE IF ONLY GROUP & NOT STORE OPTION IS AVAILABLE
if($p == "report_totalcomparison" || $p == "report_totalcomparison_print" ) {
  $_SESSION["radStores"] = "storegroup";
}

$radDate = $_SESSION["radDate"];
$radStores = $_SESSION["radStores"];


//  Report
if($p == "report_productmix" && $a == "s" && $_SESSION["usrpref_productmixorder"] != $orderby) {
    // Orderby has changed for this page.
	$_SESSION["usrpref_productmixorder"] =  $orderby;
	SetUserPref("usrpref_productmixorder","$orderby",$_SESSION["usrid"]); // Update the user prefs
}

// Parameter Fields for reports
if($_REQUEST["date"] != "") { // If report submitted, keep date in session
    $_SESSION["date"] = $_REQUEST["date"];
    $dateArr = explode("/", $_REQUEST["date"]);
    $_SESSION["datemonth"] = $dateArr[0];
    $_SESSION["dateday"] = $dateArr[1];
    $_SESSION["dateyear"] = $dateArr[2];
    //file_put_contents('php://stderr', print_r($_SESSION["dateday"].' / '.$_SESSION["datemonth"].' / '.$_SESSION["dateyear"], TRUE));
}
if($_REQUEST["daterange"] != "") { // If report submitted, keep date in session
    $_SESSION["daterange"] = $_REQUEST["daterange"];
    $daterangeArr = explode(" - ", $_REQUEST["daterange"]);
    $_SESSION["startdate"] = $daterangeArr[0];
    $_SESSION["enddate"] = $daterangeArr[1];
    $startDateArr = explode("/", $daterangeArr[0]);
    $endDateArr = explode("/", $daterangeArr[1]);
    $_SESSION["datefrommonth"] = $startDateArr[0];
    $_SESSION["datefromday"] = $startDateArr[1];
    $_SESSION["datefromyear"] = $startDateArr[2];

    $_SESSION["datetomonth"] = $endDateArr[0];
    $_SESSION["datetoday"] = $endDateArr[1];
    $_SESSION["datetoyear"] = $endDateArr[2];
    /*file_put_contents('php://stderr', print_r($_SESSION["datefrommonth"].' / '.$_SESSION["datefromday"].' / '.$_SESSION["datefromyear"], TRUE));
    file_put_contents('php://stderr', print_r(' to ', TRUE));
    file_put_contents('php://stderr', print_r($_SESSION["datetomonth"].' / '.$_SESSION["datetoday"].' / '.$_SESSION["datetoyear"], TRUE));*/
}
if($_REQUEST["daterange2"] != "") { // If report submitted, keep date in session
    $_SESSION["daterange2"] = $_REQUEST["daterange2"];
    $daterangeArr = explode(" - ", $_REQUEST["daterange2"]);
    $_SESSION["startdate"] = $daterangeArr[0];
    $_SESSION["enddate"] = $daterangeArr[1];
    $startDateArr = explode("/", $daterangeArr[0]);
    $endDateArr = explode("/", $daterangeArr[1]);
    $_SESSION["datefrommonth2"] = $startDateArr[0];
    $_SESSION["datefromday2"] = $startDateArr[1];
    $_SESSION["datefromyear2"] = $startDateArr[2];

    $_SESSION["datetomonth2"] = $endDateArr[0];
    $_SESSION["datetoday2"] = $endDateArr[1];
    $_SESSION["datetoyear2"] = $endDateArr[2];
    /*file_put_contents('php://stderr', print_r($_SESSION["datefrommonth2"].' / '.$_SESSION["datefromday2"].' / '.$_SESSION["datefromyear2"], TRUE));
    file_put_contents('php://stderr', print_r(' to ', TRUE));
    file_put_contents('php://stderr', print_r($_SESSION["datetomonth2"].' / '.$_SESSION["datetoday2"].' / '.$_SESSION["datetoyear2"], TRUE));*/
}

if($_REQUEST["dateday"] != "") { // If report submitted, keep date in session
	$_SESSION["dateday"] = $_REQUEST["dateday"];
	$_SESSION["datemonth"] = $_REQUEST["datemonth"];
	$_SESSION["dateyear"] = $_REQUEST["dateyear"];
	$_SESSION["date"] = $_SESSION["datemonth"] . '/' . $_SESSION["dateday"] . '/' . $_SESSION["dateyear"];
}
if($_REQUEST["datefromday"] != "") { // If report submitted, keep date in session
	$_SESSION["datefromday"] = $_REQUEST["datefromday"];
	$_SESSION["datefrommonth"] = $_REQUEST["datefrommonth"];
	$_SESSION["datefromyear"] = $_REQUEST["datefromyear"];
}
if($_REQUEST["datetoday"] != "") {	// If report submitted, keep date in session
	$_SESSION["datetoday"] = $_REQUEST["datetoday"];
	$_SESSION["datetomonth"] = $_REQUEST["datetomonth"];
	$_SESSION["datetoyear"] = $_REQUEST["datetoyear"];
}

// Keep selection of combo boxes in parameters for reports
if($radStores == "store" && $a == "s") { // Single store chosen
	$_SESSION["cmbstore"] = $_REQUEST["cmbstore"];
    //file_put_contents('php://stderr', print_r($_SESSION["cmbstore"], TRUE));
}
if($radStores == "storegroup" && $a == "s") { // Store group chosen
	$_SESSION["cmbstoregroup"] = $_REQUEST["cmbstoregroup"];
	$_SESSION["storegroupscount"] = " (".GetStoresInGroupCount($_SESSION["cmbstoregroup"])." stores)";

}
// Set Up Store IDs
if(($radStores == "store" && $a == "s") || ($radStores == "store" && $a == "listservers")) {//Specific Store
	$_SESSION["store"] = "'".$_REQUEST["cmbstore"]."'";
}

if($radStores == "storegroup" && $a == "s" && $ql != '1') { // Store group submitted
	$grpid = $_REQUEST["cmbstoregroup"]; // Store the group id
	$result = GetStoreIDsForGroup($grpid); // Get all the Store IDs inside that group
	$row = mysql_fetch_array($result);
	$_SESSION["store"] = "'".$row["strid"]."'"; // Set the first ID.
	while ($row = mysql_fetch_array($result)) { // Add all IDs into the store session as a string
		$_SESSION["store"] = $_SESSION["store"].",'".$row["strid"]."'";
	}
}

if($radDate != null  && $a == "s") { // Single date is being searched
	$_SESSION["datesearched"] = 1;
}

// Calculate GMT Offset hours
$gmtoffset = floatval($serverGMToffset) + floatval($_SESSION["usrpref_gmt"]) + floatval($_SESSION["usrpref_daylightsavingstime"]);

// Calculate Yesterday's date (Since that is the date of the latest data)
$yesterdaydate  = mktime(date("H") + $gmtoffset, date("i"), date("s"), date("m"),date("d")-1, date("Y"));
$yesterdayday = date( "d",$yesterdaydate);
$yesterdaymonth = date( "m",$yesterdaydate);
$yesterdayyear = date( "Y",$yesterdaydate);

// Calculate the Day before Yesterday (Since that is the first date before the latest date - for date range selection)
$daybeforeyesterdaydate  = mktime(date("H") + $gmtoffset, date("i"), date("s"), date("m"),date("d")-2, date("Y"));
$daybeforeyesterdayday = date( "d",$daybeforeyesterdaydate);
$daybeforeyesterdaymonth = date( "m",$daybeforeyesterdaydate);
$daybeforeyesterdayyear = date( "Y",$daybeforeyesterdaydate);

// Calculate Date Range Dates
if($radDate == "daterange"  && $a == "s") {
	$daysdiff = DaysBetween($_SESSION["datefromyear"], $_SESSION["datefrommonth"], $_SESSION["datefromday"], $_SESSION["datetoyear"], $_SESSION["datetomonth"], $_SESSION["datetoday"]); // Calculate the amount of days between a from and to date
	$_SESSION["daterangestring"] = "'".$_SESSION["datefromyear"]."/".$_SESSION["datefrommonth"]."/".$_SESSION["datefromday"]."'"; // Set up first date in string
	for($i=0;$i<=$daysdiff;$i++) { // Add all other dates for date range into string
	   $_SESSION["daterangestring"] = $_SESSION["daterangestring"].",'".date("Y/m/d",mktime(0, 0, 0, $_SESSION["datefrommonth"],$_SESSION["datefromday"]+$i, $_SESSION["datefromyear"]))."'";
	}
}

// No date searched yet so set default dates in session
if($_SESSION["datesearched"] == 0) {
   $_SESSION["dateday"] = date( "d",$yesterdaydate);
   $_SESSION["datemonth"] = date( "m",$yesterdaydate);
   $_SESSION["dateyear"] = date( "Y",$yesterdaydate);

   $_SESSION["datefromday"] = date( "d",$daybeforeyesterdaydate);
   $_SESSION["datefrommonth"] = date( "m",$daybeforeyesterdaydate);
   $_SESSION["datefromyear"] = date( "Y",$daybeforeyesterdaydate);

   $_SESSION["datetoday"] = date( "d",$yesterdaydate);
   $_SESSION["datetomonth"] = date( "m",$yesterdaydate);
   $_SESSION["datetoyear"] = date( "Y",$yesterdaydate);
}

if($orderby != null) { // Orderby has changed so update the session
     $_SESSION["orderby"] = $orderby;
}

if($ql == '1') { // Databrowser Report Quicklaunch
$radDate="date";
$dateday = $yesterdayday;
$datemonth = $yesterdaymonth;
$dateyear = $yesterdayyear;
$radStores="store";

}

?>

<script language="JavaScript1.2">

	var win= null;
	function NewWindow(mypage,myname,w,h,scroll){
		var winl = (screen.width-w)/2;
		var wint = (screen.height-h)/2;
		var settings ='height='+h+',';
		settings +='width='+w+',';
		settings +='top='+wint+',';
		settings +='left='+winl+',';
		settings +='scrollbars='+scroll+',';
		settings +='resizable=no';
		win=window.open(mypage,myname,settings);
		  if(parseInt(navigator.appVersion) >= 4){
		     win.window.focus();
		  }
		}

	function MM_openBrWindow(theURL,winName,features) {
	window.open(theURL,winName,features);
	}

</script>

<?

if ($p != "login") {

?>

<html>
<head>
<title>inTouchLink - Aloha Point Of Sale (POS) online reporting system</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<META NAME="description" CONTENT="inTouchLink is an accredited premium reporting system for Aloha Point Of Sale. With this online service, you will be able to view, print and download in depth sales reports and have strategic emails and sms's sent to you automatically.">
<META NAME="keywords" CONTENT="Aloha pos reports,pos,point of sale,aloha point of sale,aloha pos,online reports,aloha reports">
<meta name="distribution" content="south africa">
<META name="robots" content="ALL">
<Meta name="rating" content="General">
<META name="Revisit-After" content="15 days">
<meta http-equiv='Expires' content='0'>
<link href="https://fonts.googleapis.com/css?family=Oswald" rel="stylesheet">
<link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="chrometheme/chromestyle.css" />
<script language="JavaScript">
<!-- hide
function openNewWindow(windowaddress,windowname) {
  popupWin = window.open(windowaddress,
  windowname,
  'menubar, toolbar, location, directories, status, scrollbars, resizable, dependent, width=800, height=600, left=0, top=0')
}
// done hiding -->
</script>

<script type="text/javascript" src="chromejs/chrome.js">
</script>
<link href="style.css" rel="stylesheet" type="text/css">
    <link rel="icon" href="img/intl.ico">

</head>
<body bgcolor="#FFFFFF" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
    <?php
        if($_SESSION["loggedin"] == 1) {
            require_once('innermenu.php'); // Logged in so get report menu
        }
        else {
            require_once('menu.php'); // Not logged in so get standard menu
        }
    ?>
    <br/>
    <div class="row" style="margin: 70px 0px 39px 0px;">
        <?php require_once('pages/'.$p.'.php');  // Get the main content page
           //NM Code to show exception report
           /* if ($_SESSION["loggedin"] == 1)
            {
                if ($exceptionreportshown !=1)
                {
            //check if there are any exceptions for yesterday
            $result1 = GetStoresThatUserCanAccess($_SESSION["usrid"]); // Get user's stores
                if (mysql_num_rows($result1)>0)
                {
                $localstore ="-1";
                    while($row = mysql_fetch_array($result1))
                {
                        $localstore = $localstore .",". $row["strid"];
                    }
                }
            $localDate=$yesterdayyear."/".$yesterdaymonth."/".$yesterdayday;
            $localresult=  YesterdayException($localDate,$localstore);
            if (mysql_num_rows($localresult)>0)
            {
                        require_once('pages/ExceptionAlert.php?yesterday='.$localDate);
            }
                }
            }*/
        ?>
    </div>
    <div style="width: 100%; height: 30px; bottom: 0px; background-color: #007BC4; position:fixed; color: white">
        <div class="row" style="padding-top:5px">
            <div class="col-sm-8 col-sm-offset-2 text-center">
                <span> Tel: <b>+27 82 855 8024</b> </span><span> Email: <b>info@intouchlink.co.za</b> </span>
            </div>
        </div>
    </div>

<!-- <td height="60" colspan="3" align="left" valign="top"><img src="images/footer1024.gif" width="950" height="60" border="0" usemap="#Map"></td> -->

<script type="text/javascript" src="fixit.js"></script>

<map name="Map"><area shape="rect" coords="776,13,948,59" href="http://www.intouchpos.co.za" target="_blank">
</map></body>
</html>

<?

} else {

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <!--meta tag, meta name-->
    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="keywords" content="">
    <meta name="description" content="">
    <!--website title-->
    <title>inTouchLink - Aloha Point Of Sale (POS) online reporting system</title>
    <!--favicon links-->
    <link rel="shortcut icon" type="landing/image/ico" href="favicon.ico">
    <link rel="icon" type="landing/image/ico" href="favicon.ico">
    <!--STYLESHEETS-->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <link type="text/css" rel="stylesheet" media="all" href="landing/css/main.css">
    <link type="text/css" rel="stylesheet" media="all" href="landing/css/animate.css">
    <!--Fonts-->
    <link type="text/css" rel="stylesheet" media="all" href="https://fonts.googleapis.com/css?family=Montserrat:200,300,400,500,600,800%7COpen+Sans:300,300i,400,400i,600,700">
    <!--Icon Fonts-->
    <link type="text/css" rel="stylesheet" media="all" href="landing/fonts/linearicons/style.css">
    <link type="text/css" rel="stylesheet" media="all" href="landing/fonts/font-awesome-4.7.0/css/font-awesome.min.css">
    <link type="text/css" rel="stylesheet" media="all" href="landing/css/blue.css">

	<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
      <style>
          div.form-row {
              background-color: #fafafa;
              padding-bottom: 15px
          }
          div.formdiv {
              height: auto
          }
          h3 {
              font-family: 'Oswald', Helvetica, Arial, Lucida, sans-serif;
              font-size: 37px;
              letter-spacing: 2px;
              line-height: 1.5em;
              color: #5b5b5b!important;
              text-transform: uppercase;
          }
          input.form-control, select.form-control, button.btn.dropdown-toggle {
              border-radius: 5px;
              border: none;
              -webkit-box-shadow: none;
              box-shadow: none;
              background-color: rgba(234,234,234,0.61);
              font-family: 'Oswald', Helvetica, Arial, Lucida, sans-serif;
              height: 50px;
              width: 100%
              letter-spacing: 1px;
          }
          select.form-control:focus, input.form-control:focus {
              border: none;
              -webkit-box-shadow: none;
              box-shadow: none;
          }
          input.btn.btn-default {
              background-color: #0c71c3;
              font-family: 'Oswald', Helvetica, Arial, Lucida, sans-serif;
              text-transform: uppercase;
              height: 40px;
              -webkit-transition: padding-right 0.2s; /* Safari */
              transition: padding-right 0.2s;
              float:right;
              margin-top: 5px;
              border-radius: 0px;
              letter-spacing: 0.8px;
          }
          input.btn.btn-default:hover {
              background-color: #0b96c4!important;
          }
      </style>
  </head>
  <body class="wow fadeInDown" id="page-top" data-wow-duration="0.8s" data-wow-delay="0s" data-spy="scroll" data-target=".navbar-fixed-top">
    <div class="orange" id="template"></div>
    <!--START NAVIGATION-->
    <nav class="navbar navbar-default navigation-style navbar-fixed-top" style="background: #fff;">
      <div class="container container-style">
        <!--END MOBILE NAVIGATION-->
        <div class="navbar-header">
            <a href="//www.intouchlink.co.za"><img style="width: 200px;" class="img-responsive header-logo" src="landing/images/logo.jpg" alt="inTouchLink Point of Sale Reporting"></a>
        </div>
      </div>
    </nav>


    <!--START LOG IN-->
    <div class='col-sm-12' style="margin-top: 90px;">
        <div class="row form-row">
            <div class="col-sm-8 col-sm-offset-2 formdiv">
                <h3 class="text-center" style="text-transform: uppercase;">
                    Login to <strong>inTouchLink</strong>
                </h3>
                <form id="frmlogin" name="frmlogin" method="post" action="index.php?p=home">
                    <div class="form-group">
                        <input name="UID" type="text" class="form-control" id="UID" placeholder="Username">
                    </div>
                    <div class="form-group">
                        <input name="PWD" type="password" class="form-control" id="PWD" placeholder="Password"/>
                    </div>
                    <input class="btn btn-fill btn-md" name="Submit" type="submit" value="Login">
                    <input name="lg" type="hidden" id="lg" value="1">
                </form>
            </div>
        </div>
    </div>
    <!--END LOG IN-->


    <!--START FOOTER-->
    <!--<div class="footer">
      <div class="container">
        <div class="row">
          <div class="col-md-12 col-sm-12">
          	<center>
            <ul class="heading text-xs-center">
              <li><h3>InTouch Point of Sale</h3></li>
              <li>
                <p class="about-text">Get inTouch Point-of-Sale for your sit-down or quick-service restaurant. To get the full feature list of this market-leading software, please visit: <a href="http://www.intouchpos.co.za" target="_blank"><strong>www.intouchpos.co.za</strong></a> or call 0861 111 438 for more information.</p>
              </li>
            </ul>
            </center>
          </div>
        </div>
      </div>
    </div>
    <div class="footer-bottom">
      <div class="container">
        <div class="row">
          <div class="col-md-12">
            <p>(C) 2017. InTouchLink. All rights reserved.</p>
          </div>
        </div>
      </div>
    </div>-->
    <!--END FOOTER-->

    <!--SCRIPTS-->
		<script src="landing/js/jquery-2.2.4.min.js"></script>
		<script src="landing/js/wow.min.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
		<script src="https://maps.googleapis.com/maps/api/js?v=3&amp;key=AIzaSyASbZGps9iVQs7H7gK0TRiunz1v7hvlyjU"></script>
		<script src="landing/js/infobox.js"></script>
		<script src="landing/js/jquery.easing.min.js"></script>
		<script src="landing/js/classie.js"></script>
		<script src="landing/js/custom.js"></script>

  </body>
</html>

<?

}

?>

<?php

db_close(); // Close the database connection

?>
<script src="https://ssl.google-analytics.com/urchin.js" type="text/javascript">
</script>
<script type="text/javascript">
_uacct = "UA-1657384-9";
urchinTracker();
</script>
