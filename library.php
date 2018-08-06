<?php
set_time_limit(0);
// Database functions
function db_connect() {// Connects to Database

$connection = 'live'; //Set this var to connect to different database


	// LIVE 
		$HOSTNAME = 'dedi121.cpt2.host-h.net';
		 $USERNAME = 'intouchroot';
		 $PASSWORD = 'aH73nMjk9';
		 $DATABASE = 'intouchlink';

	/*$HOSTNAME = '188.40.0.194';
	$USERNAME = 'wwwtaj_1';
	$PASSWORD = 'iP4z5eJ8';
	$DATABASE = 'wwwtaj_db1';*/

   // $dblink = mysql_connect($HOSTNAME, $USERNAME, $PASSWORD) or
    $dblink = mysql_connect($HOSTNAME, $USERNAME, $PASSWORD) or
	die("Unable to connect to mysqld!\n");
    mysql_select_db($DATABASE) or die("Unable to connect to database!\n");

}

function db_execsql($SQL) // Executes Query and returns recordset.
{
    $result = mysql_query($SQL) or
    die("Unable to execute SQL statement\n");
    return $result;
}

function db_close() // Closes Database Connection.
{  
    mysql_close() or
    die("Unable to close connection\n");
}

function db_anyresults($rs) // Returns if Results returned or not.
{ 
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

function IsLoginValid($UID,$PWD) {
  $SQL = "select * from users where usruserid = '".$UID."' and usrpassword = '".$PWD."' and usrstatus = 'active' ";
  $result = db_execsql($SQL);
  return $result;
}


function GetUserPrefs($usrid) {
$SQL = "select * from users where usrid = ".$usrid;
  //echo $SQL;
  $result = db_execsql($SQL);
  return $result;

}

function SetUserPref($fieldname,$value,$usrid) {
  $SQL = "update users set ".$fieldname." = '".$value."' where usrid = ".$usrid;
//echo $SQL;
  $result = db_execsql($SQL);
  return $result;

}

function GetStoresThatUserCanAccess($usrid) {
$SQL = "select s.strid,s.strname,s.strcode from stores s, storesgroups sg, users u, useraccess ua, groups g where u.usrid = ua.usrid and ua.grpid = sg.grpid and g.grpid = sg.grpid and sg.strid = s.strid and u.usrid = ".$usrid." and s.strstatus = 'active' and g.grpstatus = 'active' group by s.strid order by s.strname";
 // echo $SQL;
  $result = db_execsql($SQL);
  return $result;
}

function GetStoreGroupsThatUserCanAccess($usrid) {
$SQL = "SELECT g.grpid as 'grpid', g.grpname as 'grpname', g.grpstatus as 'grpstatus' from groups g, users u, useraccess ua where u.usrid = ua.usrid and ua.grpid = g.grpid and u.usrid = $usrid and g.grpstatus = 'active'  group by g.grpid order by g.grpname";
// echo $SQL;
  $result = db_execsql($SQL);
  return $result;
}

function GetSummaryTotals($sumdate, $strid) {
	$SQL = "select SUM(sumgrosssales) as 'sumgrosssales', SUM(sumnettsales) as 'sumnettsales', SUM(sumbankingsales) as 'sumbankingsales', sum(sumcomps) as 'sumcomps', sum(sumrefunds) as 'sumrefunds', SUM(sumvat) as 'sumvat', sum(sumvoids) as 'sumvoids', SUM(sumheadcount) as 'sumheadcount', AVG(sumaveperheadexvat) as 'sumaveperheadexvat', AVG(sumaveperheadincvat) as 'sumaveperheadincvat' from summary where sumdate = '".$sumdate."' and strid in (".$strid.") ";


	//echo $SQL;
	$result = db_execsql($SQL);
    return $result;
}

function GetSummaryTotalAdditions($sumids){
$SQL = "select SUM(se.addcharges) AS 'adcharges', sum(se.ordercharges) as 'ordercharges', sum(se.promos) as 'promos', SUM(se.refunds) as 'refunds' from summaryextras se where se.sumid in (".$sumids.")";	//todo : select from summary table
//	echo $SQL;
	$result = db_execsql($SQL);
    return $result;
}

function GetSummaryTotalsWithDateRange($datestring, $strid) {
	
	$SQL = "select sum(sumgrosssales) as 'sumgrosssales', sum(sumnettsales) as 'sumnettsales',sum(sumbankingsales) as 'sumbankingsales',
sum(sumcomps) as 'sumcomps', sum(sumrefunds) as 'sumrefunds',sum(sumvat) as 'sumvat', sum(sumvoids) as 'sumvoids', sum(sumheadcount) as 'sumheadcount', avg(sumaveperheadexvat) as 'sumaveperheadexvat', avg(sumaveperheadincvat) as 'sumaveperheadincvat' from summary where sumdate in(".$datestring.") and strid in (".$strid.") ";
//	echo $SQL;
	$result = db_execsql($SQL);
    return $result;
}

function GetSumIDforWithDateRange($datestring, $strid) {
	
	$SQL = "select sumid from summary where sumdate in (".$datestring.") and strid in (".$strid.") ";
	//echo $SQL;
	$result = db_execsql($SQL);
    return $result;
}

function GetSumIDForDataBrowser($datestring, $strid) { // For DataBrowser Only
	
	$SQL = "select sumid from summary where sumdate in (".$datestring.") and strid in (".$strid.")";
	//echo $SQL;
	$result = db_execsql($SQL);
    return $result;
}
function GetSummaryData($sumdate, $strid) {
//echo $strid;
$SQL = "select * from summary where sumdate = '".$sumdate."' and strid in (".$strid.")";
	$result = db_execsql($SQL);
    return $result;
}

function GetSalesByCategory($sumid) {
    $SQL = "select sbccategoryname, sum(sbcamount) as 'sbcamount' from salesbycategoryexvat where sumid in (".$sumid.")  group by sbccategoryname order by sbccategoryname";
//	echo $SQL;
	$result = db_execsql($SQL);
    return $result;
}

function GetPaymentBreakdown($sumid) {
    $SQL = "select pbrpaymenttype, sum(pbramount) as 'pbramount', sum(pbrchargetips) as 'pbrchargetips', sum(pbrautogratuity) as 'pbrautogratuity', sum(pbrsales) as 'pbrsales', sum(pbrcash) as 'pbrcash' from paymentsbreakdown where sumid in (".$sumid.") group by pbrpaymenttype order by pbrpaymenttype";
//	echo $SQL;
	$result = db_execsql($SQL);
    return $result;
}

function IsReportAvailableForStore($sumdate, $strid) {
//echo $strid;
$SQL = "select * from summary where sumdate = '".$sumdate."' and strid in (".$strid.")";
 //echo $SQL;
 	$result = db_execsql($SQL);
  if(mysql_num_rows($result) > 0) {
   return 'true';
  }
}

function IsReportAvailableForStoreInDateRange($sumdate, $strid) {
//echo $strid;
$SQL = "select * from summary where sumdate in (".$sumdate.") and strid in (".$strid.")";
//echo $SQL;
 	$result = db_execsql($SQL);
  if(mysql_num_rows($result) > 0) {
   return 'true';
  }
}

//Stores
function GetStoreName($strid) {
    $SQL = "select strname from stores where strid in (".$strid.")";
	//echo $SQL;
	$result = db_execsql($SQL);
    $row = mysql_fetch_array($result);
    return $row["strname"];
}

function GetStoreGroupName($grpid) {
    $SQL = "select grpname from groups where grpid = ".$grpid;
	//echo $SQL;
	$result = db_execsql($SQL);
    $row = mysql_fetch_array($result);
    return $row["grpname"];
}

function GetStoreIDsForGroup($grpid) {
    $SQL = "select s.strid FROM stores s, groups g, storesgroups sg where g.grpid = ".$grpid." and sg.grpid = g.grpid and s.strid = sg.strid and strstatus = 'active' and grpstatus = 'active'";
//	echo $SQL;
	$result = db_execsql($SQL);
    return $result;
}


// COMPS REPORT -----------------------------------------------------------------------

// Check Available Data

function GetCompsSummary($sumid) {
$SQL = "SELECT compname, sum(cbrcompamount) as 'cbrcompamount',sum(cbrcompcount) as 'cbrcompcount', sum(cbrcomppercentage) as 'cbrcomppercentage' from compsbreakdown where sumid in (".$sumid.") group by compname order by compname";
//echo $SQL;
	$result = db_execsql($SQL);
    return $result;
}

// VOIDS REPORT

function GetVoidsReport($sumid) {
  $SQL = "select * from voidsbreakdown where sumid in (".$sumid.") order by vbrmanager,vbrserver, vbrtime, vbrmenuitem";
  $result = db_execsql($SQL);
  return $result;
 
}

// TOTAL SUMMARY RERPORT -------------------------------------------------------------

function GetTotalSummary($datestring, $strid) {
	$SQL = "select sumdate,sum(sumgrosssales) as 'sumgrosssales', sum(sumnettsales) as 'sumnettsales' ,sum(sumbankingsales) as 'sumbankingsales' from summary where sumdate in (".$datestring.") and strid in (".$strid.")  group by sumdate";
//echo $SQL;
	$result = db_execsql($SQL);
    return $result;
}

// TOTAL COMPARISON REPORT ---------------------------------------------------

function GetTotalComparison($datestring, $strid) {
	$SQL =  "select st.strname,   sm.sumdate, sum(sm.sumgrosssales) as 'sumgrosssales', sum(sm.sumnettsales) as 'sumnettsales' , sum(sm.sumbankingsales) as 'sumbankingsales' from summary sm, stores st  where  sm.strid = st.strid and sm.sumdate in (".$datestring.") and sm.strid in (".$strid.")  and sm.sumgrosssales != '0.00' group by sm.sumdate, st.strname";
//echo $SQL;
	$result = db_execsql($SQL);
    return $result;
}

// PRODUCT MIX REPORT ----------------------------------------------------------------

function GetProductMix($sumid,$orderby) {

$SQL = "select ibrcategoryname, ibritenplu ,ibritemname, sum(ibrnumsold) as 'ibrnumsold',ibrpricesold as 'ibrpricesold',sum(ibramount) as 'ibramount',sum(ibrsalespercent) as 'ibrsalespercent' from itemsbreakdown where sumid in (".$sumid.") group by ibritemname order by ibrcategoryname,".$orderby;
	
//	echo $SQL;
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
//	echo $SQL;
	$result = db_execsql($SQL);
    return $result;
}

// Payments Report

function GetPayments($sumid) {
	$SQL =  "SELECT * from paymentslineitems where sumid in (".$sumid.") order by pmttypeid,checknum";
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
//echo $SQL;
	$result = db_execsql($SQL);
    return $result;
}

function GetCompsSummaryTotals($sumid) {
 $SQL = "SELECT ch.chcompname, sum(ch.chquantity) as 'qty', sum(ch.chamount) as 'amount', SUM(chpercent) as 'percent' from compsheadings ch where ch.sumid in (".$sumid.") group by ch.chcompname order by ch.chrcompid";
//echo $SQL;
	$result = db_execsql($SQL);
    return $result;
}

function GetCompsSummaryGrandTotal($sumid) {

$SQL = "SELECT sum(ch.chamount) as 'amount' from compsheadings ch where ch.sumid in (".$sumid.")";
	$result = db_execsql($SQL);
    return $result;
}

// Hourly Sales Reports

function GetHourlySales($sumid) {
	$SQL =  "SELECT * from hourlysales where sumid in (".$sumid.")";
	$result = db_execsql($SQL);
    return $result;
}

// UTILITY FUNCTIONS ------------------------------------------------------------------
//Date Calculations
function DaysBetween($fyear, $fmonth, $fday, $tyear, $tmonth, $tday)
{
  return abs((mktime ( 0, 0, 0, $fmonth, $fday, $fyear) - mktime ( 0, 0, 0, $tmonth, $tday, $tyear))/(60*60*24));
}


function array_to_string($array)
 {
  foreach ($array as $index => $val)
   {
   $val2 .=$val;
   }
  return $val2;
}

function ChangePassword($usrid,$password) {

$SQL = "update users set usrpassword = '".$password."' where usrid = ".$usrid;
	//echo $SQL;
	$result = db_execsql($SQL);
    return $result;
}
// --------------------------------------------------------------------------------------
// ADMIN FUNCTIONS ----------------------------------------------------------------------
// --------------------------------------------------------------------------------------

// STORES ---------------------------------
function GetAllStores() {
	$SQL = "select * from stores where strstatus in ('active','inactive')order by strname";
	$result = db_execsql($SQL);
    return $result;
}

function InsertStore($storename,$storecode) {
	$SQL = "insert into stores (strname,strcode,strstatus) values ('$storename','$storecode','active')";
	$result = db_execsql($SQL);
    return $result;
}

function SetStoreStatus($strid,$storestatus) {
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

function UpdateStore($strid,$strname,$strcode) {
	$SQL = "update stores set strname = '$strname', strcode = '$strcode' where strid = $strid";
	//echo $SQL;
	$result = db_execsql($SQL);
    return $result;

}

// GROUPS ---------------------------------

function AddGroup($grpname) {
	$SQL = "insert into groups (grpname, grpstatus) values ('$grpname','active')";
	//echo $SQL;
	$result = db_execsql($SQL);
    return mysql_insert_id();
}

function AddStoresGroups($grpid,$strid) {
	$SQL = "insert into storesgroups (grpid, strid,stgstatus) values ($grpid,$strid,'active')";
	//echo $SQL;
	$result = db_execsql($SQL);
    return $result;


}

function SetGroupStatus($grpid,$groupstatus) {
	if($groupstatus == "1") {
	  $status = "active";
	}
	if($groupstatus == "0") {
	  $status = "inactive";
	}	
	
	$SQL = "update groups set grpstatus = '$status' where grpid = $grpid";
	//echo $SQL;
	$result = db_execsql($SQL);
    return $result;
}

function DeleteGroup($grpid) {
	$SQL = "update groups set grpstatus = 'deleted' where grpid = $grpid";
	//echo $SQL;
	$result = db_execsql($SQL);
    return $result;
}

function IsGroupStore($grpid,$strid) {
   $SQL = "select * from storesgroups where grpid = $grpid and strid = $strid and stgstatus not in ('inactive','deleted')";
	//echo $SQL;
	$result = db_execsql($SQL);
    if(mysql_num_rows($result) > 0) {
    	return "true";
	} else {
		return "false";
	}
}

function GetStoresGroupStatus($grpid,$strid) {
   $SQL = "select stgstatus from storesgroups where grpid = $grpid and strid = $strid";
	//echo $SQL;
	$result = db_execsql($SQL);
    if(mysql_num_rows($result) > 0) {
      $row = mysql_fetch_array($result);
		return $row["stgstatus"];
	} else {
		return "false";
	}
}

function SetAllStoresGroupsStatus($grpid,$status) {
   $SQL = "update storesgroups set stgstatus = '$status' where grpid = $grpid";
	//echo $SQL;
	$result = db_execsql($SQL);
    return $result;
}

function SetStoresGroupsStatus($grpid,$strid,$status) {
   $SQL = "update storesgroups set stgstatus = '$status' where grpid = $grpid and strid = $strid";
	$result = db_execsql($SQL);
    return $result;
}

// USERS ----------------------------------

function AddUserAccess($usrid,$grpid,$strid) {
	$SQL = "insert into useraccess (grpid, strid, usrid,usastatus) values ($grpid,$strid,$usrid,'active')";
	//echo $SQL;
	$result = db_execsql($SQL);
    return mysql_insert_id();
}

function CanUserAccessGroup($usrid,$grpid) {
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
// echo $SQL;
  $result = db_execsql($SQL);
  return $result;
}

function AddUser($txtname, $chkpoweruser,$txtuserid,$txtpassword,$txttaxtype,$txttimezone,$chkdst,$usrcreatorusrid) {
$SQL  = "insert into users (usrfullname, usruserid, usrpassword, usrpref_taxtype, usrpref_gmt, usrpref_daylightsavingstime, usradmin, usrpoweruser,usrstatus,usrcreatorusrid) values ('$txtname', '$txtuserid', '$txtpassword', '$txttaxtype', '$txttimezone', '$chkdst', 'no', '$chkpoweruser','active','$usrcreatorusrid')";
	$result = db_execsql($SQL);
    return mysql_insert_id();
}

function InsertUserGroupAccess($usrid,$grpid) {
	$SQL = "insert into useraccess (usrid,grpid,strid,usastatus) values ($usrid,$grpid,0,'active')";
  	$result = db_execsql($SQL);
	return $result;
}

function GetCreatedUsers($usrcreatorusrid) {
  $SQL = "select * from users where usrcreatorusrid = $usrcreatorusrid and usrstatus in ('active','inactive') order by usrfullname";
 // echo $SQL;
  $result = db_execsql($SQL);
  return $result;
}

function GetAllUsers() { // For Admin
  $SQL = "select * from users where usrstatus in ('active','inactive') order by usrfullname";
  $result = db_execsql($SQL);
  return $result;
}

function DeleteUser($usrid) {
   $SQL = "update users set usrstatus = 'deleted' where usrid = $usrid";
  $result = db_execsql($SQL);
  return $result;
}

function SetUserStatus($usrid,$usrstatus) {
	if($usrstatus == "1") {
	  $status = "active";
	}
	if($usrstatus == "0") {
	  $status = "inactive";
	}	
	
	$SQL = "update users set usrstatus = '$status' where usrid = $usrid";
	//echo $SQL;
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
  //echo $SQL;
  $result = db_execsql($SQL);
  return $result;
}

function UpdateUser ($usrid,$usrfullname,$usruserid,$usrpassword,$usrpref_taxtype,$usrpref_gmt,$usrpref_daylightsavingstime,$usrpoweruser) {
	$SQL = "update users set usrfullname = '$usrfullname', usruserid = '$usruserid', usrpassword = '$usrpassword', usrpref_taxtype = '$usrpref_taxtype', usrpref_gmt = '$usrpref_gmt', usrpref_daylightsavingstime = '$usrpref_daylightsavingstime', usrpoweruser = '$usrpoweruser' where usrid = $usrid";
    $result = db_execsql($SQL);
    return $result;	
	
}
function GetStoreGroupsThatUserCanMaintain($usrid) {
$SQL = "SELECT g.grpid as 'grpid', g.grpname as 'grpname', g.grpstatus as 'grpstatus' from groups g, users u, useraccess ua where u.usrid = ua.usrid and ua.grpid = g.grpid and u.usrid = $usrid and g.grpstatus in ('active','inactive')  group by g.grpid order by g.grpname";
// echo $SQL;
  $result = db_execsql($SQL);
  return $result;
}
function GetAllStoreGroups() {
$SQL = "SELECT g.grpid as 'grpid', g.grpname as 'grpname', g.grpstatus as 'grpstatus' from groups g, users u, useraccess ua where u.usrid = ua.usrid and ua.grpid = g.grpid and g.grpstatus in ('active','inactive')  group by g.grpid order by g.grpname";
// echo $SQL;
  $result = db_execsql($SQL);
  return $result;
}

// EMPLOYEE FUNCTIONS -------------------------------------

function GetAllEmployees($sumid) {
  $SQL = "select * from employees where sumid in (".$sumid.") group by empno";
 // echo $SQL;
  $result = db_execsql($SQL);
  return $result;
}

function GetEmployee($sumid,$empno) {
  $SQL = "select * from employees where empno = '$empno' and sumid = $sumid";
// echo $SQL;
  $result = db_execsql($SQL);
  return $result;
}

function GetEmployeeSales($sumid,$employeeid) {
  $SQL = "select * from employeesalesbycategory WHERE esbcempid = $employeeid and sumid in ($sumid) order by esbccatname";
 // echo $SQL;
  $result = db_execsql($SQL);
  return $result;
}

function GetEmployeePaymentBreakdown($sumid,$employeeid) {
$SQL = "select * from emppaymentsbreakdown WHERE empno = '$employeeid' and sumid = $sumid order BY pbrpaymenttype";
 //echo $SQL;
  $result = db_execsql($SQL);
  return $result;
}

function IsBreakdownValid($sumid,$employeeid) {
$SQL = "select sum(pbramount) as 'total' from emppaymentsbreakdown WHERE empno = '$employeeid' and sumid = $sumid";
 //echo $SQL;
  $result = db_execsql($SQL);
  $row = mysql_fetch_array($result);
  if($row["total"] > 0) {
     return "true";
  }
}

function GetEmployeeTotals($sumid,$employeeid) {
  $SQL = "select * from employees where empno = $employeeid and sumid = $sumid";
  $result = db_execsql($SQL);
  return $result;
}

?>
