<?php

set_time_limit(0); // Remove timeout
// Database functions
function db_connect() {// Connects to Database
$HOSTNAME = 'dedi44.flk1.host-h.net';
$USERNAME = 'intouchroot';
$PASSWORD = 'aH73nMjk9';
$DATABASE = 'intouchlink';

/*$HOSTNAME = '188.40.0.194';
$USERNAME = 'wwwtaj_1';
$PASSWORD = 'iP4z5eJ8';
$DATABASE = 'wwwtaj_db1';*/

$dblink = mysql_connect($HOSTNAME, $USERNAME, $PASSWORD) or header( "Location: databasenotification.php?e=connectingtodbserver&m=".mysql_error()."&u=".selfURL());
mysql_select_db($DATABASE) or header( "Location: databasenotification.php?e=connectingtodb&m=".mysql_error()."&u=".selfURL());
}

function db_execsql($SQL) {// Executes Query and returns recordset.
$result = mysql_query($SQL) or header( "Location: databasenotification.php?e=executingsql&m=".$SQL."&u=".selfURL());
return $result;
}

function db_close() {// Closes Database Connection.
mysql_close() or
die("Unable to close connection\n");
}


function db_anyresults($rs) {// Returns if Results returned or not.
if(mysql_num_rows($rs) < 1) {
return false;
} else {
return true;
}
}

// THE USER ////////////////////////////////////////////////////////
function GenerateReferenceNumber() {
list($usec, $sec) = explode(' ', microtime());
return (float) $sec + ((float) $usec * 100000);
}

function IsLoginValid($UID, $PWD) { // Return the whole user records if exists
$SQL = "select * from users where usruserid = '".$UID."' and usrpassword = '".$PWD."' and usrstatus = 'active' ";
//echo $SQL;
$result = db_execsql($SQL);
return $result;
}


function GetUserPrefs($usrid) { // Get the user's prefs
$SQL = "select * from users where usrid = ".$usrid;
$result = db_execsql($SQL);
return $result;
}

function SetUserPref($fieldname, $value, $usrid) { // Set the user prefs
$SQL = "update users set ".$fieldname." = '".$value."' where usrid = ".$usrid;
$result = db_execsql($SQL);
return $result;
}

function GetStoresThatUserCanAccess($usrid) { // Get the stores that have been allocated to the user
$SQL = "select s.strid,s.strname,s.strcode from stores s, storesgroups sg, users u, useraccess ua, groups g where u.usrid = ua.usrid and ua.grpid = sg.grpid and g.grpid = sg.grpid and sg.strid = s.strid and u.usrid = ".$usrid." and s.strstatus = 'active' and g.grpstatus = 'active' group by s.strid order by s.strname";
$result = db_execsql($SQL);
return $result;
}

function GetStoreGroupsThatUserCanAccess($usrid) { // Get store groups user can access
$SQL = "SELECT g.grpid as 'grpid', g.grpname as 'grpname', g.grpstatus as 'grpstatus' from groups g, users u, useraccess ua where u.usrid = ua.usrid and ua.grpid = g.grpid and u.usrid = $usrid and g.grpstatus = 'active'  group by g.grpid order by g.grpname";
$result = db_execsql($SQL);
return $result;
}



function GetSummaryTotals($sumid) {
$SQL = "select SUM(sumgrosssales) as 'sumgrosssales', SUM(sumnettsales) as 'sumnettsales', SUM(sumbankingsales) as 'sumbankingsales', sum(sumcomps) as 'sumcomps', sum(sumrefunds) as 'sumrefunds', SUM(sumvat) as 'sumvat', sum(sumvoids) as 'sumvoids', SUM(sumheadcount) as 'sumheadcount', AVG(sumaveperheadexvat) as 'sumaveperheadexvat', AVG(sumaveperheadincvat) as 'sumaveperheadincvat', COALESCE(SUM(sumcheckcount),0) as 'sumcheckcount' from summary where sumid in (".$sumid.") ";
//echo $SQL;
$result = db_execsql($SQL);
return $result;
}

function GetSummaryTotalsOp2($sumid) {
$SQL = "select SUM(sumgrosssales) as 'sumgrosssales', SUM(sumnettsales) + sum(sumcomps) + SUM(addcharges) + sum(ordercharges) + sum(promos) as 'sumnettsales', SUM(sumbankingsales) as 'sumbankingsales', sum(sumcomps) as 'sumcomps', sum(sumrefunds) as 'sumrefunds', SUM(sumvat) as 'sumvat', sum(sumvoids) as 'sumvoids', SUM(sumheadcount) as 'sumheadcount', AVG(sumaveperheadexvat) as 'sumaveperheadexvat', AVG(sumaveperheadincvat) as 'sumaveperheadincvat', COALESCE(SUM(sumcheckcount),0) as 'sumcheckcount' From summary Left join summaryextras on summary.sumid = summaryextras.sumid where summary.sumid in (".$sumid.") ";
//echo $SQL;
$result = db_execsql($SQL);
return $result;
}

function GetSummaryTotalAdditions($sumids){
$SQL = "select SUM(se.addcharges) AS 'adcharges', sum(se.ordercharges) as 'ordercharges', sum(se.promos) as 'promos', SUM(se.refunds) as 'refunds' from summaryextras se where se.sumid in (".$sumids.")";
$result = db_execsql($SQL);
return $result;
}

function GetSummaryTotalsWithDateRange($sumid) {
$SQL = "select sum(sumgrosssales) as 'sumgrosssales', sum(sumnettsales) as 'sumnettsales',sum(sumbankingsales) as 'sumbankingsales',
	sum(sumcomps) as 'sumcomps', sum(sumrefunds) as 'sumrefunds',sum(sumvat) as 'sumvat', sum(sumvoids) as 'sumvoids', sum(sumheadcount) as 'sumheadcount', avg(sumaveperheadexvat) as 'sumaveperheadexvat', avg(sumaveperheadincvat) as 'sumaveperheadincvat' from summary where sumid in (".$sumid.")";
$result = db_execsql($SQL);
return $result;
}

function GetSummaryTotalsWithDateRangeOp2($sumid) {
$SQL = "select sum(sumgrosssales) as 'sumgrosssales', sum(sumnettsales) + sum(sumcomps) as 'sumnettsales',sum(sumbankingsales) as 'sumbankingsales',
	sum(sumcomps) as 'sumcomps', sum(sumrefunds) as 'sumrefunds',sum(sumvat) as 'sumvat', sum(sumvoids) as 'sumvoids', sum(sumheadcount) as 'sumheadcount', avg(sumaveperheadexvat) as 'sumaveperheadexvat', avg(sumaveperheadincvat) as 'sumaveperheadincvat' from summary where sumid in (".$sumid.")";
$result = db_execsql($SQL);
return $result;
}

function GetSumIDforWithDateRange($datestring, $strid) {
$SQL = "select sumid from summary where sumdate in (".$datestring.") and strid in (".$strid.")";
$result = db_execsql($SQL);
return $result;
}
function GetSumIDbetweenDates($datefrom, $dateto, $strid) {
$SQL = "select sumid from summary where sumdate >= '$datefrom' and sumdate <= '$dateto' and strid in ($strid)";
//echo $SQL;
$result = db_execsql($SQL);
return $result;
}
function GetSumIDForDataBrowser($datestring, $strid) { // For DataBrowser Only
$SQL = "select sumid from summary where sumdate in (".$datestring.") and strid in (".$strid.")";
$result = db_execsql($SQL);
return $result;
}
function GetSummaryData($sumdate, $strid) {
$SQL = "select * from summary where sumdate = '".$sumdate."' and strid in (".$strid.")";
$result = db_execsql($SQL);
return $result;
}
function GetSummaryGrossSales($sumdate, $strid) {
$SQL = "select sumgrosssales from summary where sumdate = '".$sumdate."' and strid in (".$strid.")";
$result = db_execsql($SQL);
return $result;
}
function GetSalesByCategory($sumid) {
$SQL = "select sbccategoryname, sum(sbcamount) as 'sbcamount' from salesbycategoryexvat where sumid in (".$sumid.")  group by sbccategoryname order by sbccategoryname";
$result = db_execsql($SQL);
return $result;
}

function GetPaymentBreakdown($sumid) {
$SQL = "select pbrpaymenttype, sum(pbramount) as 'pbramount', sum(pbrchargetips) as 'pbrchargetips', sum(pbrautogratuity) as 'pbrautogratuity', sum(pbrsales) as 'pbrsales', sum(pbrcash) as 'pbrcash' from paymentsbreakdown where sumid in (".$sumid.") group by pbrpaymenttype order by pbrpaymenttype";
$result = db_execsql($SQL);
return $result;
}

function IsReportAvailableForStore($sumdate, $strid) {
$SQL = "select * from summary where sumdate = '".$sumdate."' and strid in (".$strid.")";
$result = db_execsql($SQL);
if(mysql_num_rows($result) > 0) {
return 'true';
}
}

function IsReportAvailableForStoreInDateRange($sumdate, $strid) {
$SQL = "select * from summary where sumdate in (".$sumdate.") and strid in (".$strid.")";
$result = db_execsql($SQL);
if(mysql_num_rows($result) > 0) {
return 'true';
}
}

//STORES ////////////////////////////////////////////////////
function GetStoreName($strid) {
$SQL = "select strname from stores where strid in (".$strid.")";
$result = db_execsql($SQL);
$row = mysql_fetch_array($result);
return $row["strname"];
}

function GetStoreGroupName($grpid) {
$SQL = "select grpname from groups where grpid = ".$grpid;
$result = db_execsql($SQL);
$row = mysql_fetch_array($result);
return $row["grpname"];
}

function GetStoreIDsForGroup($grpid) {
$SQL = "select s.strid FROM stores s, groups g, storesgroups sg where g.grpid = ".$grpid." and sg.grpid = g.grpid and s.strid = sg.strid and strstatus = 'active' and grpstatus = 'active' order by s.strname";
$result = db_execsql($SQL);
return $result;
}

function GetStoreRoyalty($strid) {
$SQL = "select strroyaltytype, strroyaltypercent from stores where strid = $strid";
$result = db_execsql($SQL);
//echo $SQL;
return $result;
}

function GetStoresInGroupCount($grpid) {
$SQL = "select count(*) as 'counter' from storesgroups where grpid = $grpid";
$result = db_execsql($SQL);
$row = mysql_fetch_array($result);
return $row["counter"];
}

// COMPS REPORT ////////////////////////////////////////////////////
function GetCompsSummary($sumid) {
$SQL = "SELECT compname, sum(cbrcompamount) as 'cbrcompamount',sum(cbrcompcount) as 'cbrcompcount', sum(cbrcomppercentage) as 'cbrcomppercentage' from compsbreakdown where sumid in (".$sumid.") group by compname order by compname";
$result = db_execsql($SQL);
return $result;
}

// VOIDS REPORT ////////////////////////////////////////////////////

function GetVoidsReport($sumid) {
$SQL = "select * from voidsbreakdown where sumid in (".$sumid.") order by vbrmanager,vbrserver, vbrtime, vbrmenuitem";
$result = db_execsql($SQL);
return $result;
}

// TOTAL SUMMARY RERPORT //////////////////////////////////////////
function GetTotalSummary($datestring, $strid) {
$SQL = "select sumdate,sum(sumgrosssales) as 'sumgrosssales', sum(sumnettsales) as 'sumnettsales' ,sum(sumbankingsales) as 'sumbankingsales' from summary where sumdate in (".$datestring.") and strid in (".$strid.")  group by sumdate";
$result = db_execsql($SQL);
return $result;
}

function GetTotalSummaryOp2($datestring, $strid) {
$SQL = "select sumdate,sum(sumgrosssales) as 'sumgrosssales', SUM(sumnettsales) + sum(sumcomps) + SUM(addcharges) + sum(ordercharges) + sum(promos) as 'sumnettsales', sum(sumbankingsales) as 'sumbankingsales' from summary where sumdate in (".$datestring.") and strid in (".$strid.")  group by sumdate";
$result = db_execsql($SQL);
return $result;
}

// TOTAL COMPARISON REPORT ////////////////////////////////////////

function GetTotalComparison($datestring, $strid) {
$SQL = "select st.strid, st.strname,   sm.sumdate, sum(sm.sumgrosssales) as 'sumgrosssales', sum(sm.sumnettsales) as 'sumnettsales' , sum(sm.sumbankingsales) as 'sumbankingsales' from summary sm, stores st  where  sm.strid = st.strid and sm.sumdate in (".$datestring.") and sm.strid in (".$strid.")  and sm.sumgrosssales != '0.00' group by sm.sumdate, st.strname";
$result = db_execsql($SQL);
return $result;
}

function GetTotalComparisonOp2($datestring, $strid) {
$SQL = "select st.strid, st.strname,   sm.sumdate, sum(sm.sumgrosssales) as 'sumgrosssales', SUM(sumnettsales) + sum(sumcomps) + SUM(addcharges) + sum(ordercharges) + sum(promos) as 'sumnettsales', sum(sm.sumbankingsales) as 'sumbankingsales' from summary sm, stores st  where  sm.strid = st.strid and sm.sumdate in (".$datestring.") and sm.strid in (".$strid.")  and sm.sumgrosssales != '0.00' group by sm.sumdate, st.strname";
$result = db_execsql($SQL);
return $result;
}

function GetTotalComparisonSummary($datestring, $strid) {
$SQL = "select st.strid, st.strname, sum(sm.sumgrosssales) as 'sumgrosssales', sum(sm.sumnettsales) as 'sumnettsales' , sum(sm.sumbankingsales) as 'sumbankingsales' from summary sm, stores st  where  sm.strid = st.strid and sm.sumdate in (".$datestring.") and sm.strid in (".$strid.")  and sm.sumgrosssales != '0.00' group by st.strname";
$result = db_execsql($SQL);
return $result;
}

function GetTotalComparisonSummaryOp2($datestring, $strid) {
$SQL = "select st.strid, st.strname, sum(sm.sumgrosssales) as 'sumgrosssales', SUM(sumnettsales) + sum(sumcomps) + SUM(addcharges) + sum(ordercharges) + sum(promos) as 'sumnettsales' , sum(sm.sumbankingsales) as 'sumbankingsales' from summary sm, stores st  where  sm.strid = st.strid and sm.sumdate in (".$datestring.") and sm.strid in (".$strid.")  and sm.sumgrosssales != '0.00' group by st.strname";
$result = db_execsql($SQL);
return $result;
}

// PRODUCT MIX REPORT ////////////////////////////////////////////

function GetProductMix($sumid, $orderby) {
$SQL = "select lcase(ibrcategoryname) as 'ibrcategoryname', ibritenplu ,ibritemname, sum(ibrnumsold) as 'ibrnumsold',ibrpricesold as 'ibrpricesold',sum(ibramount) as 'ibramount',sum(ibrsalespercent) as 'ibrsalespercent' from itemsbreakdown where sumid in (".$sumid.") and ibrtimesold >= '".$_SESSION["timefrom"]."' and ibrtimesold < '".$_SESSION["timeto"]."' group by lcase(ibrcategoryname),ibritemname order by ibrcategoryname,".$orderby;
//echo $SQL;
$result = db_execsql($SQL);
return $result;
}

function GetProductMixSummary($sumid) {
$SQL = "select lcase(ibrcategoryname) as 'ibrcategoryname', sum(ibrnumsold) as 'ibrnumsold',ibrpricesold as 'ibrpricesold',sum(ibramount) as 'ibramount',sum(ibrsalespercent) as 'ibrsalespercent' from itemsbreakdown where sumid in (".$sumid.") and ibrtimesold >= '".$_SESSION["timefrom"]."' and ibrtimesold < '".$_SESSION["timeto"]."' group by lcase(ibrcategoryname)";
//echo "   ".$SQL;
$result = db_execsql($SQL);
return $result;
}

function GetProductMixSummaryTotal($sumid) {
$SQL = "select sum(ibramount) as 'ibramount' from itemsbreakdown where sumid in (".$sumid.") and ibrtimesold >= '".$_SESSION["timefrom"]."' and ibrtimesold < '".$_SESSION["timeto"]."'";
//echo $SQL;
$result = db_execsql($SQL);
return $result;
}

function GetNonSalesCategoriesGrandTotal($sumid) {
$SQL = "select  SUM(nscamount) as 'nscamount' from nonsalescategories where sumid in (".$sumid.")";
$result = db_execsql($SQL);
return $result;
}

function GetNonSalesCategories($sumid) {
$SQL = "select nscname, SUM(nscnumsold) as 'nscnumsold', SUM(nscamount) as 'nscamount' from nonsalescategories where sumid in (".$sumid.") group by nscname order by nscname";
//echo $SQL;
$result = db_execsql($SQL);
return $result;
}

// Payments Report ///////////////////////////////////////////////

function GetPayments($sumid) {
$SQL = "SELECT * from paymentslineitems where sumid in (".$sumid.") order by pmttypeid,checknum";
$result = db_execsql($SQL);
return $result;
}

function GetPaymentsSummary($sumid) {
$SQL = "SELECT pmttype, sum(qty) as 'qty', sum(amount) as 'amount', sum(grat) as 'grat', SUM(tip) as 'tip', sum(total) as 'total', avg(percentagetotal) as 'percentagetotal' from paymentssummary where sumid in (".$sumid.") group by pmttypeid order by pmttypeid";
$result = db_execsql($SQL);
return $result;
}

function GetPaymentSummaryTotals($sumid) {
$SQL = "SELECT sum(qty) as 'qty', sum(amount) as 'amount', sum(grat) as 'grat', SUM(tip) as 'tip', sum(total) as 'total'  from paymentssummary where sumid in (".$sumid.")";
$result = db_execsql($SQL);
return $result;
}

function GetCompsBreakdown($sumid) {
$SQL = "SELECT * from compsheadings ch, compsitems ci where ch.sumid in (".$sumid.") and ci.sumid in (".$sumid.") and ch.chchecknum = ci.cichecknum  and ch.chcompname = ci.cicompname order by ch.chrcompid, ch.chchecknum,ci.ciitemname";
$result = db_execsql($SQL);
return $result;
}

function GetCompsSummaryTotals($sumid) {
$SQL = "SELECT ch.chcompname, sum(ch.chquantity) as 'qty', sum(ch.chamount) as 'amount', SUM(chpercent) as 'percent' from compsheadings ch where ch.sumid in (".$sumid.") group by ch.chcompname order by ch.chrcompid";
$result = db_execsql($SQL);
return $result;
}

function GetCompsSummaryGrandTotal($sumid) {
$SQL = "SELECT sum(ch.chamount) as 'amount' from compsheadings ch where ch.sumid in (".$sumid.")";
$result = db_execsql($SQL);
return $result;
}

// Hourly Sales Reports ///////////////////////////////////////////////////

function GetHourlySales($sumid) {
$SQL = "SELECT hsstarttime, sum(hsgrosssales) as 'hsgrosssales', sum(hsguests) as 'hsguests', sum(hschecks) as 'hschecks',sum(hslaborhours) as 'hslaborhours',avg(hsgrossperhour) as 'hsgrossperhour'
from hourlysales where sumid in (".$sumid.") group by hsstarttime order by hsid";
//echo $SQL;
$result = db_execsql($SQL);
return $result;
}

function GetHourlySalesNett($sumid) {
$SQL = "SELECT hsstarttime, sum(hsnetsales) as 'hsgrosssales', sum(hsguests) as 'hsguests', sum(hschecks) as 'hschecks',sum(hslaborhours) as 'hslaborhours',avg(hsgrossperhour) as 'hsgrossperhour'
from hourlysales where sumid in (".$sumid.") group by hsstarttime order by hsid";
//echo $SQL;
$result = db_execsql($SQL);
return $result;
}

// UTILITY FUNCTIONS //////////////////////////////////////////////////////
//Date Calculations
function DaysBetween($fyear, $fmonth, $fday, $tyear, $tmonth, $tday) {
return abs((mktime ( 0, 0, 0, $fmonth, $fday, $fyear) - mktime ( 0, 0, 0, $tmonth, $tday, $tyear))/(60*60*24));
}

function array_to_string($array) {
foreach ($array as $index => $val) {
$val2 .=$val;
}
return $val2;
}

function ChangePassword($usrid, $password) {
$SQL = "update users set usrpassword = '".$password."' where usrid = ".$usrid;
//echo $SQL;
$result = db_execsql($SQL);
return $result;
}

function selfURL() {
$s = empty($_SERVER["HTTPS"]) ? '' : ($_SERVER["HTTPS"] == "on") ? "s" : "";
$protocol = strleft(strtolower($_SERVER["SERVER_PROTOCOL"]), "/").$s;
$port = ($_SERVER["SERVER_PORT"] == "80") ? "" : (":".$_SERVER["SERVER_PORT"]);
return $protocol."://".$_SERVER['SERVER_NAME'].$port.$_SERVER['REQUEST_URI'];
} function strleft($s1, $s2) { return substr($s1, 0, strpos($s1, $s2));
}

function ReturnDayOfWeekSUMIDs($sumID) {
$SQL = "select sumid,sumdate from summary where sumid in (".$sumID.")";
// echo $SQL;
$result = db_execsql($SQL);

while($row = mysql_fetch_array($result)) {
//	echo substr($row["sumdate"],8,2);
//	echo substr($row["sumdate"],5,2);
//	echo substr($row["sumdate"],0,4);
$day = date("l", mktime(0, 0, 0, substr($row["sumdate"], 5, 2), substr($row["sumdate"], 8, 2), substr($row["sumdate"], 0, 4)));
// 	echo $day;
if($_SESSION["dayofweek"] != $day) { // Delete day
$sumID = str_replace($row["sumid"], "", $sumID);
}
}
$sumID = str_replace(",''", "", $sumID);
$sumID = str_replace("'',", "", $sumID);
$sumID = str_replace(",", "", $sumID);
$sumID = str_replace("''", "','", $sumID);
return $sumID;
}

function authSendEmail($from, $namefrom, $to, $nameto, $subject, $message) {
//SMTP + SERVER DETAILS
/* * * * CONFIGURATION START * * * */
$smtpServer = "mail.remtech.com.au";
$port = "25";
$timeout = "30";
$username = "iansaid@remtech.com.au";
$password = "4iansaid";
$localhost = "localhost";
$newLine = "\r\n";
/* * * * CONFIGURATION END * * * * */

//Connect to the host on the specified port
$smtpConnect = fsockopen($smtpServer, $port, $errno, $errstr, $timeout);
$smtpResponse = fgets($smtpConnect, 515);
if(empty($smtpConnect))
{
$output = "Failed to connect: $smtpResponse";
return $output;
}
else
{
$logArray['connection'] = "Connected: $smtpResponse";
}

//Request Auth Login
fputs($smtpConnect, "AUTH LOGIN" . $newLine);
$smtpResponse = fgets($smtpConnect, 515);
$logArray['authrequest'] = "$smtpResponse";

//Send username
fputs($smtpConnect, base64_encode($username) . $newLine);
$smtpResponse = fgets($smtpConnect, 515);
$logArray['authusername'] = "$smtpResponse";

//Send password
fputs($smtpConnect, base64_encode($password) . $newLine);
$smtpResponse = fgets($smtpConnect, 515);
$logArray['authpassword'] = "$smtpResponse";

//Say Hello to SMTP
fputs($smtpConnect, "HELO $localhost" . $newLine);
$smtpResponse = fgets($smtpConnect, 515);
$logArray['heloresponse'] = "$smtpResponse";

//Email From
fputs($smtpConnect, "MAIL FROM: $from" . $newLine);
$smtpResponse = fgets($smtpConnect, 515);
$logArray['mailfromresponse'] = "$smtpResponse";

//Email To
fputs($smtpConnect, "RCPT TO: $to" . $newLine);
$smtpResponse = fgets($smtpConnect, 515);
$logArray['mailtoresponse'] = "$smtpResponse";

//The Email
fputs($smtpConnect, "DATA" . $newLine);
$smtpResponse = fgets($smtpConnect, 515);
$logArray['data1response'] = "$smtpResponse";

//Construct Headers
$headers = "MIME-Version: 1.0" . $newLine;
$headers .= "Content-type: text/html; charset=iso-8859-1" . $newLine;
$headers .= "To: $nameto <$to>" . $newLine;
$headers .= "From: $namefrom <$from>" . $newLine;

fputs($smtpConnect, "To: $to\nFrom: $from\nSubject: $subject\n$headers\n\n$message\n.\n");
$smtpResponse = fgets($smtpConnect, 515);
$logArray['data2response'] = "$smtpResponse";

// Say Bye to SMTP
fputs($smtpConnect, "QUIT" . $newLine);
$smtpResponse = fgets($smtpConnect, 515);
$logArray['quitresponse'] = "$smtpResponse";
}
/////////////////////////////////////////////////////////////////////////
// ADMIN FUNCTIONS //////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////
// STORES ///////////////////////////////////////////////////////////////
function GetAllStores() {
$SQL = "select * from stores where strstatus in ('active','inactive')order by strname";
$result = db_execsql($SQL);
return $result;
}

function GetStoresAndInfoThatUserCanAccess($usrid) { // Get the stores that have been allocated to the user
$SQL = "select s.* from stores s, storesgroups sg, users u, useraccess ua, groups g where u.usrid = ua.usrid and ua.grpid = sg.grpid and g.grpid = sg.grpid and sg.strid = s.strid and u.usrid = ".$usrid." and s.strstatus = 'active' and g.grpstatus = 'active' group by s.strid order by s.strname";
$result = db_execsql($SQL);
return $result;
}
function InsertStore($storename, $storecode, $storecontactname, $storecontactnumber, $storecontactemail, $storetelnumber, $royaltytype, $roytaltypercentage) {
$SQL = "insert into stores (strname,strcode,strcontactname,strcontactnumber,strcontactemail,strstorenumber,strroyaltytype,strroyaltypercent,strstatus) values ('$storename','$storecode','$storecontactname','$storecontactnumber','$storecontactemail','$storetelnumber','$royaltytype','$roytaltypercentage','active')";
//echo $SQL;
$result = db_execsql($SQL);
return $result;
}

function SetStoreStatus($strid, $storestatus) {
if($storestatus == "1") {
$status = "active";
}
if($storestatus == "0") {
$status = "inactive";
}
$SQL = "update stores set strstatus = '$status' where strid = $strid";
$result = db_execsql($SQL);
return $result;
}

function DeleteStore($strid) {
$SQL = "update stores set strstatus='deleted' where strid = $strid";
$result = db_execsql($SQL);
return $result;
}

function GetStoreDetails($strid) {
$SQL = "select * from stores where strid = $strid";
$result = db_execsql($SQL);
return $result;
}

function UpdateStore($strid, $strname, $strcode, $storecontactname, $storecontactnumber, $storecontactemail, $storetelnumber, $royaltytype, $roytaltypercentage) {
$SQL = "update stores set strname = '$strname', strcode = '$strcode', strcontactname = '$storecontactname', strcontactnumber = '$storecontactnumber', strcontactemail = '$storecontactemail', strstorenumber = '$storetelnumber', strroyaltytype = '$royaltytype',strroyaltypercent = '$roytaltypercentage' where strid = $strid";
//echo $SQL;
$result = db_execsql($SQL);
return $result;
}

function GetStoreWithRoyaltyCount($sumid) {
$SQL = "select count(st.strid) as 'counter' from summary s, stores st where s.sumid in ('".$sumid."') and s.strid = st.strid and st.strroyaltytype in ('Nett','Banking Sales','Gross')";
//echo $SQL;
$result = db_execsql($SQL);
$row = mysql_fetch_array($result);
return $row["counter"];
}

// GROUPS /////////////////////////////////////////////////////

function AddGroup($grpname) {
$SQL = "insert into groups (grpname, grpstatus) values ('$grpname','active')";
$result = db_execsql($SQL);
return mysql_insert_id();
}

function AddStoresGroups($grpid, $strid) {
$SQL = "insert into storesgroups (grpid, strid,stgstatus) values ($grpid,$strid,'active')";
$result = db_execsql($SQL);
return $result;
}

function SetGroupStatus($grpid, $groupstatus) {
if($groupstatus == "1") {
$status = "active";
}
if($groupstatus == "0") {
$status = "inactive";
}
$SQL = "update groups set grpstatus = '$status' where grpid = $grpid";
$result = db_execsql($SQL);
return $result;
}

function DeleteGroup($grpid) {
$SQL = "update groups set grpstatus = 'deleted' where grpid = $grpid";
$result = db_execsql($SQL);
return $result;
}

function IsGroupStore($grpid, $strid) {
$SQL = "select * from storesgroups where grpid = $grpid and strid = $strid and stgstatus not in ('inactive','deleted')";
$result = db_execsql($SQL);
if(mysql_num_rows($result) > 0) {
return "true";
} else {
return "false";
}
}

function GetStoresGroupStatus($grpid, $strid) {
$SQL = "select stgstatus from storesgroups where grpid = $grpid and strid = $strid";
$result = db_execsql($SQL);
if(mysql_num_rows($result) > 0) {
$row = mysql_fetch_array($result);
return $row["stgstatus"];
} else {
return "false";
}
}

function SetAllStoresGroupsStatus($grpid, $status) {
$SQL = "update storesgroups set stgstatus = '$status' where grpid = $grpid";
$result = db_execsql($SQL);
return $result;
}

function SetStoresGroupsStatus($grpid, $strid, $status) {
$SQL = "update storesgroups set stgstatus = '$status' where grpid = $grpid and strid = $strid";
$result = db_execsql($SQL);
return $result;
}

// USERS /////////////////////////////////////////////////////////

function AddUserAccess($usrid, $grpid, $strid) {
$SQL = "insert into useraccess (grpid, strid, usrid,usastatus) values ($grpid,$strid,$usrid,'active')";
$result = db_execsql($SQL);
return mysql_insert_id();
}

function CanUserAccessGroup($usrid, $grpid) {
$SQL = "select * from useraccess where usrid = $usrid and grpid = $grpid";
$result = db_execsql($SQL);
if(mysql_num_rows($result) > 0) {
return "true";
} else {
return "false";
}
}

function GetAllGroups() {
$SQL = "SELECT g.grpid as 'grpid', g.grpname as 'grpname', g.grpstatus as 'grpstatus' from groups g, users u, useraccess ua where   ua.grpid = g.grpid and g.grpstatus not in ('deleted')  group by g.grpid order by g.grpname";
$result = db_execsql($SQL);
return $result;
}

function AddUser($txtname, $chkpoweruser, $txtuserid, $txtpassword, $txttaxtype, $txttimezone, $chkdst, $usrcreatorusrid) {
$SQL = "insert into users (usrfullname, usruserid, usrpassword, usrpref_taxtype, usrpref_gmt, usrpref_daylightsavingstime, usradmin, usrpoweruser,usrstatus,usrcreatorusrid) values ('$txtname', '$txtuserid', '$txtpassword', '$txttaxtype', '$txttimezone', '$chkdst', 'no', '$chkpoweruser','active','$usrcreatorusrid')";
$result = db_execsql($SQL);
return mysql_insert_id();
}

function AddUser2($txtname, $chkpoweruser,$txtuserid,$txtpassword,$txttaxtype,$txttimezone,$chkdst,$usrcreatorusrid,$usraccessitl) {
    $SQL  = "insert into users (usrfullname, usruserid, usrpassword, usrpref_taxtype, usrpref_gmt, usrpref_daylightsavingstime, usradmin, usrpoweruser,usrstatus,usrcreatorusrid,usraccessITL) values ('$txtname', '$txtuserid', '$txtpassword', '$txttaxtype', '$txttimezone', '$chkdst', 'no', '$chkpoweruser','active','$usrcreatorusrid','$usraccessitl')";
	$result = db_execsql($SQL);
    return mysql_insert_id();
}

function InsertUserGroupAccess($usrid, $grpid) {
$SQL = "insert into useraccess (usrid,grpid,strid,usastatus) values ($usrid,$grpid,0,'active')";
$result = db_execsql($SQL);
return $result;
}

function GetCreatedUsers($usrcreatorusrid) {
$SQL = "select * from users where usrcreatorusrid = $usrcreatorusrid and usrstatus in ('active','inactive') order by usrfullname";
$result = db_execsql($SQL);
return $result;
}

function GetAllUsers() {
$SQL = "select * from users where usrstatus in ('active','inactive') order by usrfullname";
$result = db_execsql($SQL);
return $result;
}

function DeleteUser($usrid) {
$SQL = "update users set usrstatus = 'deleted' where usrid = $usrid";
$result = db_execsql($SQL);
return $result;
}

function SetUserStatus($usrid, $usrstatus) {
if($usrstatus == "1") {
$status = "active";
}
if($usrstatus == "0") {
$status = "inactive";
}
$SQL = "update users set usrstatus = '$status' where usrid = $usrid";
$result = db_execsql($SQL);
return $result;
}


function GetUserFullName($usrid) {
$SQL = "select usrfullname from users where usrid = ".$usrid;
$result = db_execsql($SQL);
$row = mysql_fetch_array($result);
return $row["usrfullname"];
}

function DeleteUsersAccess($usrid) {
$SQL = "delete from useraccess where usrid = $usrid";
$result = db_execsql($SQL);
return $result;
}

function UpdateUser ($usrid, $usrfullname, $usruserid, $usrpassword, $usrpref_taxtype, $usrpref_gmt, $usrpref_daylightsavingstime, $usrpoweruser) {
$SQL = "update users set usrfullname = '$usrfullname', usruserid = '$usruserid', usrpassword = '$usrpassword', usrpref_taxtype = '$usrpref_taxtype', usrpref_gmt = '$usrpref_gmt', usrpref_daylightsavingstime = '$usrpref_daylightsavingstime', usrpoweruser = '$usrpoweruser' where usrid = $usrid";
$result = db_execsql($SQL);
return $result;
}
function UpdateUser2 ($usrid,$usrfullname,$usruserid,$usrpassword,$usrpref_taxtype,$usrpref_gmt,$usrpref_daylightsavingstime,$usrpoweruser,$usraccessitl) {
	$SQL = "update users set usrfullname = '$usrfullname', usruserid = '$usruserid', usrpassword = '$usrpassword', usrpref_taxtype = '$usrpref_taxtype', usrpref_gmt = '$usrpref_gmt', usrpref_daylightsavingstime = '$usrpref_daylightsavingstime', usrpoweruser = '$usrpoweruser', usraccessITL='$usraccessitl' where usrid = $usrid";
    $result = db_execsql($SQL);
    return $result;

}
function GetStoreGroupsThatUserCanMaintain($usrid) {
$SQL = "SELECT g.grpid as 'grpid', g.grpname as 'grpname', g.grpstatus as 'grpstatus' from groups g, users u, useraccess ua where u.usrid = ua.usrid and ua.grpid = g.grpid and u.usrid = $usrid and g.grpstatus in ('active','inactive')  group by g.grpid order by g.grpname";
$result = db_execsql($SQL);
return $result;
}
function GetAllStoreGroups() {
$SQL = "SELECT g.grpid as 'grpid', g.grpname as 'grpname', g.grpstatus as 'grpstatus' from groups g, users u, useraccess ua where u.usrid = ua.usrid and ua.grpid = g.grpid and g.grpstatus in ('active','inactive')  group by g.grpid order by g.grpname";
$result = db_execsql($SQL);
return $result;
}

// EMPLOYEE FUNCTIONS -------------------------------------

function GetAllEmployees($sumid) {
$SQL = "select * from employees where sumid in (".$sumid.") group by empno";
$result = db_execsql($SQL);
return $result;
}

function GetEmployee($sumid, $empno) {
$SQL = "select * from employees where empno = '$empno' and sumid = $sumid";
$result = db_execsql($SQL);
return $result;
}

function GetEmployeeSales($sumid, $employeeid) {
$SQL = "select * from employeesalesbycategory WHERE esbcempid = $employeeid and sumid in ($sumid) order by esbccatname";
$result = db_execsql($SQL);
return $result;
}

function GetEmployeePaymentBreakdown($sumid, $employeeid) {
$SQL = "select * from emppaymentsbreakdown WHERE empno = '$employeeid' and sumid = $sumid order BY pbrpaymenttype";
$result = db_execsql($SQL);
return $result;
}

function IsBreakdownValid($sumid, $employeeid) {
$SQL = "select sum(pbramount) as 'total' from emppaymentsbreakdown WHERE empno = '$employeeid' and sumid = $sumid";
$result = db_execsql($SQL);
$row = mysql_fetch_array($result);
if($row["total"] > 0) {
return "true";
}
}

function GetEmployeeTotals($sumid, $employeeid) {
$SQL = "select * from employees where empno = $employeeid and sumid = $sumid";
$result = db_execsql($SQL);
return $result;
}

// REVENUE SALES REPORT ////////////////////////////////////////////////

function GetRevenueCenterNames($sumid) {
$SQL = "select distinct(rc.revenuecentername) from summary s, revenuecenters rc, salescategories sc, revenuesalesbycategory rsc where s.sumid in ($sumid)
	and s.sumid = rc.sumid and s.sumid=sc.sumid and s.sumid = rsc.sumid and rsc.salescatid = sc.salescatid and rc.revenuecenterid = rsc.revenuecenterid
	order by sc.salescatname, rc.revenuecentername
	";
//echo $SQL;
$result = db_execsql($SQL);
return $result;
}

function GetNetSalesByCategory($sumid) {
$SQL = "select sc.salescatname, rc.revenuecentername, sum(rsc.netsales) as 'netsales' from summary s, revenuecenters rc, salescategories sc, revenuesalesbycategory rsc where s.sumid in ($sumid)
	and s.sumid = rc.sumid and s.sumid=sc.sumid and s.sumid = rsc.sumid and rsc.salescatid = sc.salescatid and rc.revenuecenterid = rsc.revenuecenterid  group by salescatname, revenuecentername order by sc.salescatname, rc.revenuecentername";
//echo $SQL;
$result = db_execsql($SQL);
return $result;
}

function GetNetSalesByCategoryNonSalesCat($sumid) {
$SQL = "select sc.nscname, rc.revenuecentername, sum(rsc.netsales) as 'netsales'
	from summary s, revenuecenters rc, nonsalescategories sc, revenuesalesbycategorynonsales rsc
	where s.sumid in ($sumid) and s.sumid = rc.sumid and s.sumid=sc.sumid and s.sumid = rsc.sumid
	and rsc.salescatid = sc.nsccatid and rc.revenuecenterid = rsc.revenuecenterid group by sc.nscname, rc.revenuecentername order by sc.nscname, rc.revenuecentername
	";
//echo $SQL;
$result = db_execsql($SQL);
return $result;
}

function GetNetSalesByDayPart($sumid) {
$SQL = "select dp.dpname, rc.revenuecentername, sum(rs.netsales) as 'netsales' from summary s, revenuecenters rc, dayparts dp, revenuesalesbydaypart rs where
	s.sumid in ($sumid) and rc.sumid = s.sumid and dp.sumid = s.sumid and dp.dpcode = rs.dpcode
	and rc.revenuecenterid = rs.revenuecenterid and rs.sumid = s.sumid group by dp.dpcode,rc.revenuecentername order by dp.dpcode,rc.revenuecentername";
$result = db_execsql($SQL);
return $result;
}

function GetBankingSalesByDayPart($sumid) {
$SQL = "select dp.dpname, rc.revenuecentername, sum(rs.bankingsales) as 'bankingsales' from summary s, revenuecenters rc, dayparts dp,
	revenuesalesbydaypartbanking rs where
	s.sumid in ($sumid) and rc.sumid = s.sumid and dp.sumid = s.sumid and dp.dpcode = rs.dpcode
	and rc.revenuecenterid = rs.revenuecenterid and rs.sumid = s.sumid group by dp.dpcode,rc.revenuecentername order by dp.dpcode,rc.revenuecentername";
$result = db_execsql($SQL);
return $result;
}

function GetGuestsByDayPart($sumid) {
$SQL = "select dp.dpname, rc.revenuecentername, sum(rs.headcount) as 'headcount' from summary s, revenuecenters rc, dayparts dp, revenuesalesbydaypart rs where
	s.sumid in ($sumid) and rc.sumid = s.sumid and dp.sumid = s.sumid and dp.dpcode = rs.dpcode
	and rc.revenuecenterid = rs.revenuecenterid and rs.sumid = s.sumid group by dp.dpcode,rc.revenuecentername order by dp.dpcode,rc.revenuecentername";
$result = db_execsql($SQL);
return $result;
}

function GetCheckCountByDayPart($sumid) {
$SQL = "select dp.dpname, rc.revenuecentername, sum(rs.checkcount) as 'checkcount' from summary s, revenuecenters rc, dayparts dp, revenuesalesbydaypart rs where
	s.sumid in ($sumid) and rc.sumid = s.sumid and dp.sumid = s.sumid and dp.dpcode = rs.dpcode
	and rc.revenuecenterid = rs.revenuecenterid and rs.sumid = s.sumid group by dp.dpcode,rc.revenuecentername order by dp.dpcode,rc.revenuecentername";
$result = db_execsql($SQL);
return $result;
}

function GetRevenueCenterTax($sumid) {
$SQL = "select tx.taxname, rc.revenuecentername, sum(rctx.taxamount) as 'taxamount' from summary s, revenuecenters rc, taxes tx, revenuecentertaxes rctx where s.sumid in ($sumid)
	and s.sumid = rc.sumid and s.sumid = tx.sumid and s.sumid = rctx.sumid and rc.revenuecenterid = rctx.revenuecenterid and
	tx.taxid = rctx.taxid group by tx.taxname, rc.revenuecentername order by tx.taxname, rc.revenuecentername";
$result = db_execsql($SQL);
return $result;
}

function GetRevenueCenterComps($sumid) {
$SQL = "select c.compname, rc.revenuecentername, sum(rcc.compamount) as 'compamount' from summary s, revenuecenters rc, comps c, revenuecentercomps rcc where
	s.sumid in ($sumid) and rc.sumid = s.sumid and c.sumid = s.sumid and rcc.sumid = s.sumid
	and rcc.revenuecenterid = rc.revenuecenterid and rcc.compid = c.compid group by c.compname,rc.revenuecentername order by c.compname,rc.revenuecentername";
$result = db_execsql($SQL);
return $result;
}

function GetRevenueCenterPayments($sumid){
$SQL = "select s.pmttype, sum(rcp.paymentamount) as 'paymentamount', rc.revenuecentername  from paymentssummary s, revenuecenters rc, revenuecenterpayments rcp where
	s.sumid in ($sumid) and s.sumid = rc.sumid and s.sumid = rcp.sumid and s.pmttypeid = rcp.paymentid
	and rcp.revenuecenterid = rc.revenuecenterid
	group by s.pmttype, rc.revenuecentername order by s.pmttype, rc.revenuecentername";
$result = db_execsql($SQL);
return $result;
}

function GetTips($sumid){
$SQL = "select sum(rt.tips) as 'tips', ps.pmttype, rc.revenuecentername  from paymentssummary ps, revenuecenters rc, revenuetipsbydaypart rt where
	ps.sumid in ($sumid) and ps.sumid = rt.sumid and ps.sumid = rc.sumid and
	rt.revenuecenterid = rc.revenuecenterid and ps.pmttypeid = rt.paymentid
	group by  ps.pmttype,rc.revenuecentername order by ps.pmttype, rc.revenuecentername";
$result = db_execsql($SQL);
return $result;
}
// DATA BROWSER EXCEPTION REPORT ////////////////////////////////////////////////

function GetLatestStoreDataDate ($stores) {
if($_SESSION["usrpref_exceptionreportorder"] == "Last available date") {
$SQL = "select st.strid, st.strname, st.strcontactname,st.strcontactnumber, st.strcontactemail,st.strstorenumber,max(s.sumdate) as 'latestdate' from summary s right join stores st on (s.strid = st.strid) where st.strid in ($stores) group by st.strid order by max(s.sumdate)";

}
if($_SESSION["usrpref_exceptionreportorder"] == "Store Name") {
$SQL = "select st.strid, st.strname, st.strcontactname,st.strcontactnumber, st.strcontactemail,st.strstorenumber,max(s.sumdate) as 'latestdate' from summary s right join stores st on (s.strid = st.strid) where st.strid in ($stores) group by st.strid order by st.strname";
}
//	echo $SQL;
$result = db_execsql($SQL);
return $result;
}

// GROWTH COMPARISON REPORT ////////////////////////////////////////////////

function GetFigureTotal($columnname, $sumid) {
$SQL = "select sum($columnname) as 'total' from summary where sumid in ($sumid)";
//	echo $SQL;
$result = db_execsql($SQL);
if(mysql_num_rows($result) > 0) {
$row = mysql_fetch_array($result);
return $row["total"];
} else {
return "0.00";
}
}

function GetItemsBreakdownTotal($sumid) {
$SQL = "select count(ibrid) as 'total' from itemsbreakdown where sumid in ($sumid) and ibrpricesold != '0.00'";
//echo $SQL;
$result = db_execsql($SQL);
if(mysql_num_rows($result) > 0) {
$row = mysql_fetch_array($result);
return $row["total"];
} else {
return "0.00";
}
}
function GetChecksTotal($sumid) {
$SQL = "select sum(sumheadcount) as 'total' from summary where sumid in ($sumid)";
//echo $SQL;
$result = db_execsql($SQL);
if(mysql_num_rows($result) > 0) {
$row = mysql_fetch_array($result);
return $row["total"];
} else {
return "0";
}
}
// Purchases REPORT ////////////////////////////////////////////////////
function GetPurchaseSummary($sumid) {
$SQL = "Select Vendor,ref,InvAmount,Freight,Tax,OtherAmount from Purchases Where sumid in (".$sumid.") group by Vendor order by Vendor";
$result = db_execsql($SQL);
return $result;
}

function GetStorePerfomance($sumid){
$SQL = "select * from storeperformancenm where SummaryID in (".$sumid.")";
$result = db_execsql($SQL);
return $result;
}

function GetInstockInvoices($sumid)
{
$SQL = "Select * from instockinvoices where SummaryID in (".$sumid.")";
$result = db_execsql($SQL);
return $result;
}

function GetSpeedOfService($sumid)
{
$SQL = "select * from speedofservicenm where SummaryID in (".$sumid.")";
$result = db_execsql($SQL);
return $result;
}

function YesterdayExceptions($yesterdaydate,$strid)
{
    //$SQL = "select * from summary where sumdate = '".$sumdate."' and strid in (".$strid.")";
    //get yesterday's store performance data
    //$SQL = "select * from storeperformancenm where SummaryID in (select sumid from summary where sumdate = '".$yesterdaydate."' and strid in (".$strid."))";
    $SQL = "select a.*,b.strid from storeperformancenm a,summary b where a.SummaryID=b.sumid and b.sumdate = '".$yesterdaydate."'";
    $result = db_execsql($SQL);
    //for each store performance data,find if it exceeded set exceptions
    $retresult="";
    $exception=0;
    $exceptionsummaryid=0;
    if(mysql_num_rows($result) > 0 )
    {
        while($row = mysql_fetch_array($result))
	 {
            $SQL="select * from exceptions where storeid =".$row["strid"];
            $result2="";
            $result2 = db_execsql($SQL);
            if(mysql_num_rows($result2) > 0)
            {
                while($row2=mysql_fetch_array($result))
		{
                    if ($row["TotalVoidAmount"]>$row2["voids"] ||$row["RefundsValue"]>$row2["refunds"]||$row["SplitValue"]>$row2["splits"]||$row["ClearsValue"]>$row2["clears"]||$row["TransfersValue"]>$row2["transfers"]||$row["ReopenedChecksAmt"]>$row2["reopenedchecks"])
                    {
                         $exception=1;
                         $exceptionsummaryid=$row["summaryID"];
                         break;
                    }
                }
            }
            if ($exception==1) break;
        }
    }
    if ($exception==1)
    {
        $SQL= "select * from storeperformancenm where SummaryID=".$exceptionsummaryid;
        $retresult= db_execsql($SQL);
    }
    return $retresult;
 }


function SaveExceptions($storeid, $voids, $refunds, $splits, $clears, $transfers, $reopenedchecks)
{
    $SQL ="select * from exceptions where storeid=".$storeid;
    $result = db_execsql($SQL);
    if(mysql_num_rows($result) > 0)
    {
        $SQL ="Update exceptions set voids=".$voids.",refunds=".$refunds.",splits=".$splits.",clears=".$clears.",transfers=".$transfers.",reopenedchecks=".$reopenedchecks." where storeid=".$storeid ;
    }
    else
    {
        $SQL = "Insert into exceptions(storeid,voids,refunds,splits,clears,transfers,reopenedchecks) values (".$storeid.",".$voids.",".$refunds.",".$splits.",".$clears.",".$transfers.",".$reopenedchecks.")";
    }
    $result = db_execsql($SQL);
    return $result;
}



function GetExceptionsReport($dates,$strid)
{
    //$SQL = "select * from summary where sumdate = '".$sumdate."' and strid in (".$strid.")";
    //get yesterday's store performance data
    //$SQL = "select * from storeperformancenm where SummaryID in (select sumid from summary where sumdate = '".$yesterdaydate."' and strid in (".$strid."))";
    $SQL = "select a.*,b.strid from storeperformancenm a,summary b where a.SummaryID=b.sumid and b.sumdate in (".$dates.") and b.strid in (".$strid. ")";
    $result = db_execsql($SQL);
    //for each store performance data,find if it exceeded set exceptions
    $retresult="";
    $exception="0";
    $exceptionsummaryid="0";
    if(mysql_num_rows($result) > 0 )
    {
        while($row = mysql_fetch_array($result))
	 {
            $SQL="select * from exceptions where storeid =".$row["strid"];
            $result2="";
            $result2 = db_execsql($SQL);
            if(mysql_num_rows($result2) > 0)
            {
                while($row2=mysql_fetch_array($result2))
		{
                    if ($row["TotalVoidAmount"]>$row2["voids"] || $row["RefundsValue"]>$row2["refunds"] || $row["NoOfSplits"]>$row2["splits"] || $row["ClearsValue"]>$row2["clears"] || $row["NoOfTransfers"]>$row2["transfers"] || $row["NoOfReopenedChecks"]>$row2["reopenedchecks"])
                    {
                         $exception="1";
                         $exceptionsummaryid = $exceptionsummaryid .",". $row["SummaryID"];
                         //break;
                    }
                }
            }
           // if ($exception==1) break;
        }
    }
    if ($exception=="1")
    {
        $SQL= "select * from storeperformancenm where SummaryID in (".$exceptionsummaryid .")";
        $retresult= db_execsql($SQL);
    }
    return $retresult;
 }

 function GetExceptionsForStore($strid)
 {
      $SQL="select * from exceptions where storeid =".$strid;
      $result = db_execsql($SQL);
      return $result;
 }
 function GetStoreHistory($strid)
 {
     $SQL="select strname, stores.strVersion2, (Select max(sumdate) from summary  where summary.strid = stores.strid) as mSumDate from stores Where strstatus = 'active' ORDER BY mSumDate";
     $result = db_execsql($SQL);
     return $result;
 }
?>
