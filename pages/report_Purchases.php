<link href="../style.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
.style1 {color: #FFFFFF}
.style2 {
	color: #006600;
	font-weight: bold;
}
.style3 {font-size: 12px; line-height: normal; color: #333333; text-decoration: none; font-family: Verdana, Arial, Helvetica, sans-serif;}
-->
</style>
<table width="948" height="410" border="0" cellpadding="5" cellspacing="0">
  <tr>
    <td width="935" height="410" valign="top"><p align="center" class="NormalHeading">Purchases Report</p>
      <table width="495" height="203" border="0" align="center" cellpadding="0" cellspacing="0" id="Table_01">
        <tr>
          <td width="4" rowspan="2" background="images/panel_01.gif"><img src="images/panel_01.gif" width="4" height="28" alt="" /></td>
          <td width="429" height="5" background="images/panel_02.gif"><img src="images/panel_02.gif" width="138" height="5" alt="" /></td>
          <td width="62" rowspan="2" background="images/panel_03.gif"><img src="images/panel_03.gif" width="4" height="28" alt="" /></td>
        </tr>
        <tr>
          <td height="23" bgcolor="#007BC4"><div align="center" class="NormalText style1">Please select the report parameters </div></td>
        </tr>
        <tr>
          <td width="4" height="171" align="left" valign="top" background="images/panel_05.gif"><img src="images/panel_05.gif" width="4" height="171" alt="" /></td>
          <td width="429" height="1" align="center" valign="middle" class="SmallText"><form id="frmparameters" name="frmparameters" method="post" action="index.php?p=report_Purchases&a=s" onSubmit="return CheckDateRange(this);">
              <table width="494" height="171" border="0" cellpadding="3" cellspacing="0">
                <tr>
                  <td height="26" class="NormalText"><strong>
                    <input name="radStores" type="radio" value="store" <?php if($radStores == "store") {echo "checked='checked'";} ?>  onclick="Javascript: SetStoreFocus();"/>
                  </strong></td>
                  <td height="26" class="NormalText"><strong>Store</strong></td>
                  <td width="301"><label>
                    <select name="cmbstore" class="NormalText" id="cmbstore">
<?php 
$result = GetStoresThatUserCanAccess($_SESSION["usrid"]); // Get user's stores
while($row = mysql_fetch_array($result)) {
	$output = "<option value='".$row["strid"]."'";
	if($_SESSION["cmbstore"] == $row["strid"]) {
		$output =  $output." selected ";
	}
		$output = $output.">".$row["strname"]."</option>";
		echo $output;
}
?>
                    </select>
                    </label></td>
                </tr>
                <tr>
                  <td height="26" class="NormalText"><strong>
                    <input name="radStores" type="radio" value="storegroup" <?php if($radStores == "storegroup") {echo "checked='checked'";} ?>  onclick="Javascript: SetStoreGroupFocus();"/>
                  </strong></td>
                  <td class="NormalText"><strong>Store Group </strong></td>
                  <td><select name="cmbstoregroup" class="NormalText" id="cmbstoregroup">
<?php 
$result = GetStoreGroupsThatUserCanAccess($_SESSION["usrid"]);
while($row = mysql_fetch_array($result)) {
	$output = "<option value='".$row["grpid"]."'";
	if($_SESSION["cmbstoregroup"] == $row["grpid"]) {
		$output =  $output." selected ";
	}
	$output = $output.">".$row["grpname"]."</option>";
	echo $output;
}
?>
                                                      </select></td>
                </tr>
                <tr>
                  <td height="7" colspan="3" class="NormalText"><hr size="1" /></td>
                </tr>
                <tr>
                  <td width="20" height="26" class="NormalText"><strong>
                    <label>
                    <input name="radDate" type="radio" value="date" <?php if($radDate == "date") {echo "checked='checked'";} ?>  onclick="Javascript: SetSpecificDateFocus();"/>
                    </label>
                    </strong></td>
                  <td width="155" class="NormalText"><div align="left"><strong>Specific Date</strong></div></td>
                  <td>
                    <select name="dateday">
                      <option value="01" <?php if ($_SESSION["dateday"] == "01") {echo "selected";} ?>>01</option>
                      <option value="02" <?php if ($_SESSION["dateday"] == "02") {echo "selected";} ?>>02</option>
                      <option value="03" <?php if ($_SESSION["dateday"] == "03") {echo "selected";} ?>>03</option>
                      <option value="04" <?php if ($_SESSION["dateday"] == "04") {echo "selected";} ?>>04</option>
                      <option value="05" <?php if ($_SESSION["dateday"] == "05") {echo "selected";} ?>>05</option>
                      <option value="06" <?php if ($_SESSION["dateday"] == "06") {echo "selected";} ?>>06</option>
                      <option value="07" <?php if ($_SESSION["dateday"] == "07") {echo "selected";} ?>>07</option>
                      <option value="08" <?php if ($_SESSION["dateday"] == "08") {echo "selected";} ?>>08</option>
                      <option value="09" <?php if ($_SESSION["dateday"] == "09") {echo "selected";} ?>>09</option>
                      <option value="10" <?php if ($_SESSION["dateday"] == "10") {echo "selected";} ?>>10</option>
                      <option value="11" <?php if ($_SESSION["dateday"] == "11") {echo "selected";} ?>>11</option>
                      <option value="12" <?php if ($_SESSION["dateday"] == "12") {echo "selected";} ?>>12</option>
                      <option value="13" <?php if ($_SESSION["dateday"] == "13") {echo "selected";} ?>>13</option>
                      <option value="14" <?php if ($_SESSION["dateday"] == "14") {echo "selected";} ?>>14</option>
                      <option value="15" <?php if ($_SESSION["dateday"] == "15") {echo "selected";} ?>>15</option>
                      <option value="16" <?php if ($_SESSION["dateday"] == "16") {echo "selected";} ?>>16</option>
                      <option value="17" <?php if ($_SESSION["dateday"] == "17") {echo "selected";} ?>>17</option>
                      <option value="18" <?php if ($_SESSION["dateday"] == "18") {echo "selected";} ?>>18</option>
                      <option value="19" <?php if ($_SESSION["dateday"] == "19") {echo "selected";} ?>>19</option>
                      <option value="20" <?php if ($_SESSION["dateday"] == "20") {echo "selected";} ?>>20</option>
                      <option value="21" <?php if ($_SESSION["dateday"] == "21") {echo "selected";} ?>>21</option>
                      <option value="22" <?php if ($_SESSION["dateday"] == "22") {echo "selected";} ?>>22</option>
                      <option value="23" <?php if ($_SESSION["dateday"] == "23") {echo "selected";} ?>>23</option>
                      <option value="24" <?php if ($_SESSION["dateday"] == "24") {echo "selected";} ?>>24</option>
                      <option value="25" <?php if ($_SESSION["dateday"] == "25") {echo "selected";} ?>>25</option>
                      <option value="26" <?php if ($_SESSION["dateday"] == "26") {echo "selected";} ?>>26</option>
                      <option value="27" <?php if ($_SESSION["dateday"] == "27") {echo "selected";} ?>>27</option>
                      <option value="28" <?php if ($_SESSION["dateday"] == "28") {echo "selected";} ?>>28</option>
                      <option value="29" <?php if ($_SESSION["dateday"] == "29") {echo "selected";} ?>>29</option>
                      <option value="30" <?php if ($_SESSION["dateday"] == "30") {echo "selected";} ?>>30</option>
                      <option value="31" <?php if ($_SESSION["dateday"] == "31") {echo "selected";} ?>>31</option>
                    </select>
                    <select name="datemonth">
                      <option value="01"  <?php if ($_SESSION["datemonth"] == "01") {echo "selected";} ?>>January</option>
                      <option value="02" <?php if ($_SESSION["datemonth"] == "02") {echo "selected";} ?>>February</option>
                      <option value="03" <?php if ($_SESSION["datemonth"] == "03") {echo "selected";} ?>>March</option>
                      <option value="04" <?php if ($_SESSION["datemonth"] == "04") {echo "selected";} ?>>April</option>
                      <option value="05" <?php if ($_SESSION["datemonth"] == "05") {echo "selected";} ?>>May</option>
                      <option value="06" <?php if ($_SESSION["datemonth"] == "06") {echo "selected";} ?>>June</option>
                      <option value="07" <?php if ($_SESSION["datemonth"] == "07") {echo "selected";} ?>>July</option>
                      <option value="08" <?php if ($_SESSION["datemonth"] == "08") {echo "selected";} ?>>August</option>
                      <option value="09" <?php if ($_SESSION["datemonth"] == "09") {echo "selected";} ?>>September</option>
                      <option value="10" <?php if ($_SESSION["datemonth"] == "10") {echo "selected";} ?>>October</option>
                      <option value="11" <?php if ($_SESSION["datemonth"] == "11") {echo "selected";} ?>>November</option>
                      <option value="12" <?php if ($_SESSION["datemonth"] == "12") {echo "selected";} ?>>December</option>
                    </select>
                    <select name="dateyear">
                      <option value="2020"  <?php if ($_SESSION["dateyear"] == "2020") {echo "selected";} ?> >2020</option>
                      <option value="2019"  <?php if ($_SESSION["dateyear"] == "2019") {echo "selected";} ?> >2019</option>
                      <option value="2018"  <?php if ($_SESSION["dateyear"] == "2018") {echo "selected";} ?> >2018</option>
                      <option value="2017"  <?php if ($_SESSION["dateyear"] == "2017") {echo "selected";} ?> >2017</option>
                      <option value="2016"  <?php if ($_SESSION["dateyear"] == "2016") {echo "selected";} ?> >2016</option>
                      <option value="2015"  <?php if ($_SESSION["dateyear"] == "2015") {echo "selected";} ?> >2015</option>                      
                      <option value="2014"  <?php if ($_SESSION["dateyear"] == "2014") {echo "selected";} ?> >2014</option>
                      <option value="2013"  <?php if ($_SESSION["dateyear"] == "2013") {echo "selected";} ?> >2013</option>
                      <option value="2012"  <?php if ($_SESSION["dateyear"] == "2012") {echo "selected";} ?> >2012</option>
                      <option value="2011"  <?php if ($_SESSION["dateyear"] == "2011") {echo "selected";} ?> >2011</option>
					  <option value="2010"  <?php if ($_SESSION["dateyear"] == "2010") {echo "selected";} ?> >2010</option>
                    </select>
                  </td>
                </tr>
                <tr>
                  <td height="28" class="NormalText"><strong>
                    <input name="radDate" type="radio" value="daterange" onclick="Javascript: SetSpecificDateRangeFocus();" <?php if($radDate == "daterange") {echo "checked='checked'";} ?> />
                    </strong></td>
                  <td height="28" class="NormalText"><div align="left"><strong>Date Range</strong> (From) </div></td>
                  <td>
                    <select name="datefromday" id="datefromday">
                      <option value="01" <?php if ($_SESSION["datefromday"] == "01") {echo "selected";} ?>>01</option>
                      <option value="02" <?php if ($_SESSION["datefromday"] == "02") {echo "selected";} ?>>02</option>
                      <option value="03" <?php if ($_SESSION["datefromday"] == "03") {echo "selected";} ?>>03</option>
                      <option value="04" <?php if ($_SESSION["datefromday"] == "04") {echo "selected";} ?>>04</option>
                      <option value="05" <?php if ($_SESSION["datefromday"] == "05") {echo "selected";} ?>>05</option>
                      <option value="06" <?php if ($_SESSION["datefromday"] == "06") {echo "selected";} ?>>06</option>
                      <option value="07" <?php if ($_SESSION["datefromday"] == "07") {echo "selected";} ?>>07</option>
                      <option value="08" <?php if ($_SESSION["datefromday"] == "08") {echo "selected";} ?>>08</option>
                      <option value="09" <?php if ($_SESSION["datefromday"] == "09") {echo "selected";} ?>>09</option>
                      <option value="10" <?php if ($_SESSION["datefromday"] == "10") {echo "selected";} ?>>10</option>
                      <option value="11" <?php if ($_SESSION["datefromday"] == "11") {echo "selected";} ?>>11</option>
                      <option value="12" <?php if ($_SESSION["datefromday"] == "12") {echo "selected";} ?>>12</option>
                      <option value="13" <?php if ($_SESSION["datefromday"] == "13") {echo "selected";} ?>>13</option>
                      <option value="14" <?php if ($_SESSION["datefromday"] == "14") {echo "selected";} ?>>14</option>
                      <option value="15" <?php if ($_SESSION["datefromday"] == "15") {echo "selected";} ?>>15</option>
                      <option value="16" <?php if ($_SESSION["datefromday"] == "16") {echo "selected";} ?>>16</option>
                      <option value="17" <?php if ($_SESSION["datefromday"] == "17") {echo "selected";} ?>>17</option>
                      <option value="18" <?php if ($_SESSION["datefromday"] == "18") {echo "selected";} ?>>18</option>
                      <option value="19" <?php if ($_SESSION["datefromday"] == "19") {echo "selected";} ?>>19</option>
                      <option value="20" <?php if ($_SESSION["datefromday"] == "20") {echo "selected";} ?>>20</option>
                      <option value="21" <?php if ($_SESSION["datefromday"] == "21") {echo "selected";} ?>>21</option>
                      <option value="22" <?php if ($_SESSION["datefromday"] == "22") {echo "selected";} ?>>22</option>
                      <option value="23" <?php if ($_SESSION["datefromday"] == "23") {echo "selected";} ?>>23</option>
                      <option value="24" <?php if ($_SESSION["datefromday"] == "24") {echo "selected";} ?>>24</option>
                      <option value="25" <?php if ($_SESSION["datefromday"] == "25") {echo "selected";} ?>>25</option>
                      <option value="26" <?php if ($_SESSION["datefromday"] == "26") {echo "selected";} ?>>26</option>
                      <option value="27" <?php if ($_SESSION["datefromday"] == "27") {echo "selected";} ?>>27</option>
                      <option value="28" <?php if ($_SESSION["datefromday"] == "28") {echo "selected";} ?>>28</option>
                      <option value="29" <?php if ($_SESSION["datefromday"] == "29") {echo "selected";} ?>>29</option>
                      <option value="30" <?php if ($_SESSION["datefromday"] == "30") {echo "selected";} ?>>30</option>
                      <option value="31" <?php if ($_SESSION["datefromday"] == "31") {echo "selected";} ?>>31</option>
                    </select>
                    <select name="datefrommonth" id="datefrommonth">
                      <option value="01"  <?php if ($_SESSION["datefrommonth"] == "01") {echo "selected";} ?>>January</option>
                      <option value="02" <?php if ($_SESSION["datefrommonth"] == "02") {echo "selected";} ?>>February</option>
                      <option value="03" <?php if ($_SESSION["datefrommonth"] == "03") {echo "selected";} ?>>March</option>
                      <option value="04" <?php if ($_SESSION["datefrommonth"] == "04") {echo "selected";} ?>>April</option>
                      <option value="05" <?php if ($_SESSION["datefrommonth"] == "05") {echo "selected";} ?>>May</option>
                      <option value="06" <?php if ($_SESSION["datefrommonth"] == "06") {echo "selected";} ?>>June</option>
                      <option value="07" <?php if ($_SESSION["datefrommonth"] == "07") {echo "selected";} ?>>July</option>
                      <option value="08" <?php if ($_SESSION["datefrommonth"] == "08") {echo "selected";} ?>>August</option>
                      <option value="09" <?php if ($_SESSION["datefrommonth"] == "09") {echo "selected";} ?>>September</option>
                      <option value="10" <?php if ($_SESSION["datefrommonth"] == "10") {echo "selected";} ?>>October</option>
                      <option value="11" <?php if ($_SESSION["datefrommonth"] == "11") {echo "selected";} ?>>November</option>
                      <option value="12" <?php if ($_SESSION["datefrommonth"] == "12") {echo "selected";} ?>>December</option>
                    </select>
                    <select name="datefromyear" id="datefromyear">
                      <option value="2020"  <?php if ($_SESSION["datefromyear"] == "2020") {echo "selected";} ?> >2020</option>
                      <option value="2019"  <?php if ($_SESSION["datefromyear"] == "2019") {echo "selected";} ?> >2019</option>
                      <option value="2018"  <?php if ($_SESSION["datefromyear"] == "2018") {echo "selected";} ?> >2018</option>
                      <option value="2017"  <?php if ($_SESSION["datefromyear"] == "2017") {echo "selected";} ?> >2017</option>
                      <option value="2016"  <?php if ($_SESSION["datefromyear"] == "2016") {echo "selected";} ?> >2016</option>
                      <option value="2015"  <?php if ($_SESSION["datefromyear"] == "2015") {echo "selected";} ?> >2015</option>  
                      <option value="2014"  <?php if ($_SESSION["datefromyear"] == "2014") {echo "selected";} ?> >2014</option>  
                      <option value="2013"  <?php if ($_SESSION["datefromyear"] == "2013") {echo "selected";} ?> >2013</option>  
                      <option value="2012"  <?php if ($_SESSION["datefromyear"] == "2012") {echo "selected";} ?> >2012</option>  
                      <option value="2011"  <?php if ($_SESSION["datefromyear"] == "2011") {echo "selected";} ?> >2011</option>  
					  <option value="2010"  <?php if ($_SESSION["datefromyear"] == "2010") {echo "selected";} ?> >2010</option>  
                     
                    </select></td>
                </tr>
                <tr>
                  <td height="28" class="NormalText">&nbsp;</td>
                  <td height="28" class="NormalText"><div align="left"><strong>Date Range</strong> (To) </div></td>
                  <td>
                    <select name="datetoday" id="datetoday">
                      <option value="01" <?php if ($_SESSION["datetoday"] == "01") {echo "selected";} ?>>01</option>
                      <option value="02" <?php if ($_SESSION["datetoday"] == "02") {echo "selected";} ?>>02</option>
                      <option value="03" <?php if ($_SESSION["datetoday"] == "03") {echo "selected";} ?>>03</option>
                      <option value="04" <?php if ($_SESSION["datetoday"] == "04") {echo "selected";} ?>>04</option>
                      <option value="05" <?php if ($_SESSION["datetoday"] == "05") {echo "selected";} ?>>05</option>
                      <option value="06" <?php if ($_SESSION["datetoday"] == "06") {echo "selected";} ?>>06</option>
                      <option value="07" <?php if ($_SESSION["datetoday"] == "07") {echo "selected";} ?>>07</option>
                      <option value="08" <?php if ($_SESSION["datetoday"] == "08") {echo "selected";} ?>>08</option>
                      <option value="09" <?php if ($_SESSION["datetoday"] == "09") {echo "selected";} ?>>09</option>
                      <option value="10" <?php if ($_SESSION["datetoday"] == "10") {echo "selected";} ?>>10</option>
                      <option value="11" <?php if ($_SESSION["datetoday"] == "11") {echo "selected";} ?>>11</option>
                      <option value="12" <?php if ($_SESSION["datetoday"] == "12") {echo "selected";} ?>>12</option>
                      <option value="13" <?php if ($_SESSION["datetoday"] == "13") {echo "selected";} ?>>13</option>
                      <option value="14" <?php if ($_SESSION["datetoday"] == "14") {echo "selected";} ?>>14</option>
                      <option value="15" <?php if ($_SESSION["datetoday"] == "15") {echo "selected";} ?>>15</option>
                      <option value="16" <?php if ($_SESSION["datetoday"] == "16") {echo "selected";} ?>>16</option>
                      <option value="17" <?php if ($_SESSION["datetoday"] == "17") {echo "selected";} ?>>17</option>
                      <option value="18" <?php if ($_SESSION["datetoday"] == "18") {echo "selected";} ?>>18</option>
                      <option value="19" <?php if ($_SESSION["datetoday"] == "19") {echo "selected";} ?>>19</option>
                      <option value="20" <?php if ($_SESSION["datetoday"] == "20") {echo "selected";} ?>>20</option>
                      <option value="21" <?php if ($_SESSION["datetoday"] == "21") {echo "selected";} ?>>21</option>
                      <option value="22" <?php if ($_SESSION["datetoday"] == "22") {echo "selected";} ?>>22</option>
                      <option value="23" <?php if ($_SESSION["datetoday"] == "23") {echo "selected";} ?>>23</option>
                      <option value="24" <?php if ($_SESSION["datetoday"] == "24") {echo "selected";} ?>>24</option>
                      <option value="25" <?php if ($_SESSION["datetoday"] == "25") {echo "selected";} ?>>25</option>
                      <option value="26" <?php if ($_SESSION["datetoday"] == "26") {echo "selected";} ?>>26</option>
                      <option value="27" <?php if ($_SESSION["datetoday"] == "27") {echo "selected";} ?>>27</option>
                      <option value="28" <?php if ($_SESSION["datetoday"] == "28") {echo "selected";} ?>>28</option>
                      <option value="29" <?php if ($_SESSION["datetoday"] == "29") {echo "selected";} ?>>29</option>
                      <option value="30" <?php if ($_SESSION["datetoday"] == "30") {echo "selected";} ?>>30</option>
                      <option value="31" <?php if ($_SESSION["datetoday"] == "31") {echo "selected";} ?>>31</option>
                    </select>
                    <select name="datetomonth" id="datetomonth">
                      <option value="01"  <?php if ($_SESSION["datetomonth"] == "01") {echo "selected";} ?>>January</option>
                      <option value="02" <?php if ($_SESSION["datetomonth"] == "02") {echo "selected";} ?>>February</option>
                      <option value="03" <?php if ($_SESSION["datetomonth"] == "03") {echo "selected";} ?>>March</option>
                      <option value="04" <?php if ($_SESSION["datetomonth"] == "04") {echo "selected";} ?>>April</option>
                      <option value="05" <?php if ($_SESSION["datetomonth"] == "05") {echo "selected";} ?>>May</option>
                      <option value="06" <?php if ($_SESSION["datetomonth"] == "06") {echo "selected";} ?>>June</option>
                      <option value="07" <?php if ($_SESSION["datetomonth"] == "07") {echo "selected";} ?>>July</option>
                      <option value="08" <?php if ($_SESSION["datetomonth"] == "08") {echo "selected";} ?>>August</option>
                      <option value="09" <?php if ($_SESSION["datetomonth"] == "09") {echo "selected";} ?>>September</option>
                      <option value="10" <?php if ($_SESSION["datetomonth"] == "10") {echo "selected";} ?>>October</option>
                      <option value="11" <?php if ($_SESSION["datetomonth"] == "11") {echo "selected";} ?>>November</option>
                      <option value="12" <?php if ($_SESSION["datetomonth"] == "12") {echo "selected";} ?>>December</option>
                    </select>
                    <select name="datetoyear" id="datetoyear">
                      <option value="2020"  <?php if ($_SESSION["datetoyear"] == "2020") {echo "selected";} ?> >2020</option>
                      <option value="2019"  <?php if ($_SESSION["datetoyear"] == "2019") {echo "selected";} ?> >2019</option>
                      <option value="2018"  <?php if ($_SESSION["datetoyear"] == "2018") {echo "selected";} ?> >2018</option>
                      <option value="2017"  <?php if ($_SESSION["datetoyear"] == "2017") {echo "selected";} ?> >2017</option>
                      <option value="2016"  <?php if ($_SESSION["datetoyear"] == "2016") {echo "selected";} ?> >2016</option>
                      <option value="2015"  <?php if ($_SESSION["datetoyear"] == "2015") {echo "selected";} ?> >2015</option>
                      <option value="2014"  <?php if ($_SESSION["datetoyear"] == "2014") {echo "selected";} ?> >2014</option>
                      <option value="2013"  <?php if ($_SESSION["datetoyear"] == "2013") {echo "selected";} ?> >2013</option>
                      <option value="2012"  <?php if ($_SESSION["datetoyear"] == "2012") {echo "selected";} ?> >2012</option>
                      <option value="2011"  <?php if ($_SESSION["datetoyear"] == "2011") {echo "selected";} ?> >2011</option>
					  <option value="2010"  <?php if ($_SESSION["datetoyear"] == "2010") {echo "selected";} ?> >2010</option>
  
                    </select></td>
                </tr>
                <tr>
                  <td height="28" colspan="2" class="NormalText"><input name="hiddenyesterday" type="hidden" id="hiddenyesterday" value="<?php echo $yesterdayday;?>" />
                    <input name="hiddenyestermonth" type="hidden" id="hiddenyestermonth" value="<?php echo $yesterdaymonth;?>" />
                    <input name="hiddenyesteryear" type="hidden" id="hiddenyesteryear" value="<?php echo $yesterdayyear;?>" /></td>
                  <td><label>
                    <input name="Submit" type="submit" class="NormalText" value="Submit" />
                    </label></td>
                </tr>
              </table>
            </form></td>
          <td width="62" align="left" valign="top" background="images/panel_07.gif"><img src="images/panel_07.gif" width="4" height="171" alt="" /></td>
        </tr>
        <tr>
          <td height="4"><img src="images/panel_08.gif" width="4" height="4" alt="" /></td>
          <td background="images/panel_09.gif"><img src="images/panel_09.gif" width="138" height="4" alt="" /></td>
          <td><img src="images/panel_10.gif" width="4" height="4" alt="" /></td>
        </tr>
      </table>
      <br />
      <hr width="935" size="1" />
      <br />
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
// ******************************************************************
// Excel Export. Initialize
// *
	set_time_limit(30000);
	require_once "classes/excelexport/class.writeexcel_workbook.inc.php";
	require_once "classes/excelexport/class.writeexcel_worksheet.inc.php";
	$fname = tempnam("/tmp", GenerateReferenceNumber().".xls");
	$workbook =& new writeexcel_workbook($fname);
	$worksheet =& $workbook->addworksheet('Report');
	// Set Columns widths
	$worksheet->set_column('A:B', 1);
	$worksheet->set_column('C:C', 20);
	$worksheet->set_column('D:J', 14.75);
	$worksheet->insert_bitmap('a1', 'classes/excelexport/report_header.bmp', 16, 8); // Write Header
	// Set styles
	$heading  =& $workbook->addformat(array(
											bold    => 1,
											color   => '007CC4',
											size    => 12,
											merge   => 1,
											font    => 'Arial'
											)); // Create new font style
	$heading2  =& $workbook->addformat(array(
											bold    => 1,
											color   => '007CC4',
											size    => 11,
											merge   => 1,
											font    => 'Arial',
											align   => "left"
											)); // Create new font style
	$RightNumberTotalBold  =& $workbook->addformat(array(
											bold    => 1,
											color   => '007CC4',
											size    => 11,
											merge   => 1,
											font    => 'Arial',
											align   => "right"
											)); // Create new font style
	$NormalLeftAlign  =& $workbook->addformat(array(
											bold    => 0,
											color   => '007CC4',
											size    => 10,
											merge   => 1,
											font    => 'Arial',
											align   => "left"
											)); // Create new font style
	$NormalRightAlign  =& $workbook->addformat(array(
											bold    => 0,
											color   => '007CC4',
											size    => 10,
											merge   => 1,
											font    => 'Arial',
											align   => "right"
											)); // Create new font style
	
// *******************************************************************	 	  
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
        <table width="935" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="111">&nbsp;</td>
            <td width="701"><div align="center" class="NormalText"><span class="NormalHeading"><strong>Purchases Report</strong></span><strong><br />
                for<br />
                </strong>
				<?php 
				if($radStores == "store") {
				  echo GetStoreName($_SESSION["store"]); 
				  $excelreport_title = GetStoreName($_SESSION["store"]); // EXCEL
				}
				if($radStores == "storegroup") {
				  echo GetStoreGroupName($grpid); 
				  $excelreport_title = GetStoreName($_SESSION["store"]).$_SESSION["storegroupscount"]; // EXCEL
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
			 $excelreport_title = $excelreport_title." for date ".$_SESSION["dateday"]."/".$_SESSION["datemonth"]."/".$_SESSION["dateyear"]; // EXCEL
			}
			if($radDate == "daterange") {
			  echo $_SESSION["datefromday"]."/".$_SESSION["datefrommonth"]."/".$_SESSION["datefromyear"]." to ".$_SESSION["datetoday"]."/".$_SESSION["datetomonth"]."/".$_SESSION["datetoyear"];
			  $excelreport_title = $excelreport_title." for date ".$_SESSION["datefromday"]."/".$_SESSION["datefrommonth"]."/".$_SESSION["datefromyear"]." to ".$_SESSION["datetoday"]."/".$_SESSION["datetomonth"]."/".$_SESSION["datetoyear"]; // EXCEL
			}		
			?>       <?php 
	   // ************************************
			// EXCEL - Set up report title
			// *
				// Report Title
				$title = "Comps Report for ";				 
				$headings = array($title, '');
				$worksheet->write_row('D6', $headings, $heading);
				// Report Specs
				$$excelreport_title = $excelreport_title;				 
				$headings = array($excelreport_title, '');
				$worksheet->write_row('D7', $headings, $heading);
        		$num1_format =& $workbook->addformat(array(num_format => '0.00'));  //Basic number format
			// ************************************
	   ?>
            </div></td>
            <td width="60"><a href="classes/excelexport/excelexport.php?fname=<?php echo $fname; ?>" target="_blank"><img src="images/btnexport.gif" width="48" height="48" border="0" /></a></td>
            <td width="63"><div align="center"> <a href="Javascript:;"><img src="images/btnprint.gif" width="48" height="48" border="0" onMouseUp="NewWindow('pages/report_Purchases_print.php?<?php echo "radDate=".$radDate."&dateday=".$_SESSION["dateday"]."&datemonth=".$_SESSION["datemonth"]."&dateyear=".$_SESSION["dateyear"]."&store=".str_replace("'", "^", $_SESSION["store"])."&a=".$a."&datefromday=".$_SESSION["datefromday"]."&datefrommonth=".$_SESSION["datefrommonth"]."&datefromyear=".$_SESSION["datefromyear"]."&datetoday=".$_SESSION["datetoday"]."&datetomonth=".$_SESSION["datetomonth"]."&grpid=".$grpid."&radStores=".$radStores."&datetoyear=".$_SESSION["datetoyear"]; ?>','report_Purchases','650','500','yes');return false;"></a> </div></td>
          </tr>
        </table>
        <hr size="1" />
        <span class="NormalHeading"> Summary</span><br />
        <br />
      </div>
   
   <?php 
   if($radStores == 'store' && $radDate == 'date') {?>
      <table width="90%" border="0" align="center" cellpadding="0" cellspacing="0">
        <tr>
          <td bgcolor="#F2F2F2" class="NormalText"><strong>Vendor</strong></td>
          <td bgcolor="#F2F2F2" class="NormalText"><strong>Ref</strong></td>
          <td bgcolor="#F2F2F2" class="NormalText"><div align="right"><strong>Invoice Amount </strong></div></td>
          <td bgcolor="#F2F2F2" class="NormalText"><div align="right"><strong>Freight</strong></div></td>
          <td bgcolor="#F2F2F2" class="NormalText"><div align="right"><strong>Tax</strong></div></td>
          <td bgcolor="#F2F2F2" class="NormalText"><div align="right"><strong>OtherAmount</strong></div></td>
        </tr>
	  <?php 
	   // ************************************************
		   // Excel
		   // Write Table Headers for GROUP
		  	  $title = "Vendor";				 
			  $headings = array($title, '');
			  $worksheet->write_row('C10', $headings, $heading2);

		  	  $title = "Ref";				 
			  $headings = array($title, '');
			  $worksheet->write_row('E10', $headings, $RightNumberTotalBold);
			  			  
		  	  $title = "Invoice Amount";				 
			  $headings = array($title, '');
			  $worksheet->write_row('F10', $headings, $RightNumberTotalBold);

		  	  $title = "%Freight";				 
			  $headings = array($title, '');
			  $worksheet->write_row('G10', $headings, $RightNumberTotalBold);
			  
			  $title = "%Tax";				 
			  $headings = array($title, '');
			  $worksheet->write_row('H10', $headings, $RightNumberTotalBold);
			  
			  $title = "%Other Amount";				 
			  $headings = array($title, '');
			  $worksheet->write_row('I10', $headings, $RightNumberTotalBold);
			
			  $rownumber = 11; 
	  
	 	$qty = 0;
		$amount = 0;
		
	   $result = GetPurchaseSummary($sumid); 
	   while($row = mysql_fetch_array($result)) {
		   $InvAmount = $InvAmount + intval($row["InvAmount"]); 
		   $Freight = $Freight + floatval($row["Freight"]); 
		   $Tax = $Tax + floatval($row["Tax"]); 
		   $OtherAmount = $OtherAmount + floatval($row["OtherAmount"]); 
	  ?> 
	    <tr>
          <td width="25%" class="style3"><?php echo $row["Vendor"]; ?></td>
          <td width="12%" class="NormalText"><?php echo $row["ref"]; ?></td>
          <td width="15%" class="NormalText"><div align="right"><?php echo $row["InvAmount"]; ?></div></td>
          <td width="15%" class="NormalText"><div align="right"><?php echo $row["Freight"]; ?></div></td>
          <td width="15%" class="NormalText"><div align="right"><?php echo $row["Tax"]; ?></div></td>
          <td width="18%" class="NormalText"><div align="right"><?php echo $row["OtherAmount"]; ?></div></td>
        </tr>

      <?php
	             // ************************************************
		   // Excel
		  	  $title = $row["compname"];			 
			  $headings = array($title, '');
			  $worksheet->write_row('C'.$rownumber, $headings, $NormalLeftAlign);	

		  	  $title = $row["cbrcompcount"];			 
			  $headings = array($title, '');
			  $worksheet->write_row('E'.$rownumber,$headings,$NormalRightAlign);			
	
		  	  $title = $row["cbrcompamount"];			 
			  $headings = array($title, '');
			  $worksheet->write_row('F'.$rownumber,$headings,$num1_format);		
	
		  	  $title = $row["cbrcomppercentage"];			 
			  $headings = array($title, '');
			  $worksheet->write_row('G'.$rownumber,$headings,$num1_format);		
	
		$rownumber++;
	   } ?>
		    <tr>
		      <td colspan="6" class="style3"><hr size="1" /></td>
        </tr>
		    <tr>
	      <td class="style3"><strong>Totals</strong></td>
	      <td class="NormalText">&nbsp;</td>
	      <td class="NormalText"><div align="right"><strong><?php echo $InvAmount; ?></strong></div></td>
	      <td class="NormalText"><div align="right"><strong><?php echo $Freight; ?></strong></div></td>
	      <td class="NormalText"><div align="right"><strong><?php echo $Tax; ?></strong></div></td>
	      <td class="NormalText"><div align="right"><strong><?php echo $OtherAmount; ?></strong></div></td>
        </tr>
	  </table>
	  <?php         // ********************************
		// Excel
		// *
		// Write the totals
		  	  $title = "Totals";			 
			  $headings = array($title, '');
			  $worksheet->write_row('D'.$rownumber, $headings, $RightNumberTotalBold );	
			  		
		  	  $title = $qty;			 
			  $headings = array($title, '');
			  $worksheet->write_row('E'.$rownumber, $headings, $RightNumberTotalBold );	

		  	  $title = number_format($amount, 2, '.', '');			 
			  $headings = array($title, '');
			  $worksheet->write_row('F'.$rownumber,$headings,$RightNumberTotalBold );			
	
		  	  $title = 100;			 
			  $headings = array($title, '');
			  $worksheet->write_row('G'.$rownumber,$headings,$RightNumberTotalBold );		
			   } ?><br />
      <br />
      <div align="center">
        <table width="100%" border="0" cellspacing="0" cellpadding="5">
          <tr>
            <td><div align="center"></div></td>
          </tr>
        </table>
	  
	  <?php
		  } else {?>
      </div>
      <p align="center" class="NormalText"><br />
        <span class="style2">No results were returned for that query.<br />
        Please try different parameters. </span></p></td>
    <?php } }?>
  </tr>
</table>


<script language="JavaScript1.2">

function SetSpecificDateFocus() {

     document.frmparameters.dateday.disabled = false;
     document.frmparameters.datemonth.disabled = false;	 
     document.frmparameters.dateyear.disabled = false;
     
	 document.frmparameters.datefromday.disabled = true;	 
     document.frmparameters.datefrommonth.disabled = true;	 
     document.frmparameters.datefromyear.disabled = true;	 
     
	 document.frmparameters.datetoday.disabled = true;
	 document.frmparameters.datetomonth.disabled = true;
	 document.frmparameters.datetoyear.disabled = true;
}

function SetSpecificDateRangeFocus() {
     document.frmparameters.dateday.disabled = true;
     document.frmparameters.datemonth.disabled = true;	 
     document.frmparameters.dateyear.disabled = true;
     
	 document.frmparameters.datefromday.disabled = false;	 
     document.frmparameters.datefrommonth.disabled = false;	 
     document.frmparameters.datefromyear.disabled = false;	 
     
	 document.frmparameters.datetoday.disabled = false;
	 document.frmparameters.datetomonth.disabled = false;
	 document.frmparameters.datetoyear.disabled = false;
}

function SetStoreFocus() {
     document.frmparameters.cmbstore.disabled = false;
     document.frmparameters.cmbstoregroup.disabled = true;
}

function SetStoreGroupFocus() {
     document.frmparameters.cmbstore.disabled = true;
     document.frmparameters.cmbstoregroup.disabled = false;
}

<?php 
if($radDate == "date") {
    echo "SetSpecificDateFocus();";
}
if($radDate == "daterange") {
    echo "SetSpecificDateRangeFocus();";
}
if($radDate == Null) {
       echo "SetSpecificDateFocus(); document.frmparameters.radDate[0].checked = true;";
}

if($radStores == "store") {
    echo "SetStoreFocus();";
}
if($radStores == "storegroup") {
    echo "SetStoreGroupFocus();";
}
if($radStores == Null) {
       echo "SetStoreFocus(); document.frmparameters.radStores[0].checked = true;";
}
?>

function CheckDateRange(somevar) {

	 var result = true;


// CHECK DATE RANGE
if(document.frmparameters.radDate[1].checked == true) {
	if (document.frmparameters.datefromyear.value > document.frmparameters.datetoyear.value) { //1. FromYear cannot be bigger than ToYear
	  result = false;
	}
	
    if (document.frmparameters.datefromyear.value == document.frmparameters.datetoyear.value && document.frmparameters.datefrommonth.value > document.frmparameters.datetomonth.value) { //2. FromMonth cannot be bigger than ToMonth if FromYear = ToYear
		  result = false;
	}
	
    if (document.frmparameters.datefromyear.value == document.frmparameters.datetoyear.value && document.frmparameters.datefrommonth.value == document.frmparameters.datetomonth.value && document.frmparameters.datefromday.value >= document.frmparameters.datetoday.value) {//3. FromDay cannot be bigger than ToDay when FromMonth = ToMonth and FromYear = ToYear.
         result = false;
	}		   

 // CHECK FROM DATE COMPARED TO LAST DATA DATE
 
 	if (document.frmparameters.datefromyear.value > document.frmparameters.hiddenyesteryear.value) { 
	  result = false;
	}
	
    if (document.frmparameters.datefromyear.value == document.frmparameters.hiddenyesteryear.value && document.frmparameters.datefrommonth.value > document.frmparameters.hiddenyestermonth.value) {
		  result = false;
	}
	
    if (document.frmparameters.datefromyear.value == document.frmparameters.hiddenyesteryear.value && document.frmparameters.datefrommonth.value == document.frmparameters.hiddenyestermonth.value && document.frmparameters.datefromday.value > document.frmparameters.hiddenyesterday.value) {
         result = false;
	}
 
 
     
	 if(result == false) {
	    alert('Please ensure that your From Date is before your To Date and that your From date is not later than yesterday');
	 } 

}
	 return result;
 
 }
 
</script>
