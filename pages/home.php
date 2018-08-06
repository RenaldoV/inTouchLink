<link href="../style.css" rel="stylesheet" type="text/css">
<style type="text/css">
    <!--
    .style1 {color: #FFFFFF}
    .style2 {font-size: 12px; line-height: normal; color: #333333; text-decoration: none;}
    .style3 {color: #FFFFFF; font-weight: bold; }
    .style6 {color: #000000; font-weight: bold; }
    -->
    div.alert{
        border-radius: 0px;
    }
</style>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<?php
    // Get Total Summary Data for Google Graph
    $result = GetStoreGroupsThatUserCanAccess($_SESSION["usrid"]);
    $noGrps = 0;
    $theGrp;
    //Get number of groups that a user can access
    while ($row = mysql_fetch_array($result)) {
        if ($row['grpid']) {
            $noGrps++;
            $theGrp = $row['grpid'];
        }
    }
    if (isset($_GET['grp']) || $noGrps == 1) {

        if (isset($_GET['grp']))
        {
            $_SESSION["selectedGroup"] = $_GET['grp'];
            $grpid = $_SESSION["selectedGroup"];
        }
        else {
            $_SESSION["selectedGroup"] = $theGrp;
            $grpid = $_SESSION["selectedGroup"];
        }
        // file_put_contents('php://stderr', print_r('the Grp: ' . $grpid . "\n", TRUE));
        $result1 = GetStoreIDsForGroup($grpid); // Get all the Store IDs inside that group
        $row1 = mysql_fetch_array($result1);
        $_SESSION["store"] = "'" . $row1["strid"] . "'"; // Set the first ID.

        while ($row1 = mysql_fetch_array($result1)) { // Add all IDs into the store session as a string
            $_SESSION["store"] = $_SESSION["store"] . ",'" . $row1["strid"] . "'";
        }

        $daterange = getLastNDays(7);
        $result2 = GetTotalSummary($daterange, $_SESSION["store"]); // Get group summary data
        $sumIDResult = GetSumIDforWithDateRange($daterange, $_SESSION["store"]);

        if (mysql_num_rows($sumIDResult) > 0) {
            $sumidrow = mysql_fetch_array($sumIDResult);
            $sumid = "'" . $sumidrow["sumid"] . "'";
        }
        while ($sumidrow = mysql_fetch_array($sumIDResult)) {
            $sumid = $sumid . ",'" . $sumidrow["sumid"] . "'";
        }
        $date_array = array();
        while ($row = mysql_fetch_array($result2)) {
            array_push($date_array, $row["sumdate"]);
        }
        // Delete duplicates and sort.
        $date_array = array_keys(array_flip($date_array));
        $chartdata = array(array());
        // Fill with Zeros
        for ($y = 0; $y <= count($date_array); $y++) {
            for ($x = 0; $x <= 3; $x++) {
                $chartdata[$x][$y] = "0.00";
            }
        }

        $chartdata[0][0] = ""; // Set first blank cell

        // SET DATE COLUMN HEADER
        for ($i = 0; $i < count($date_array); $i++) {
            $chartdata[0][$i + 1] = $date_array[$i];
        }
        // SET DATE ROW HEADER ON LEFT

        $chartdata[1][0] = "Gross";
        $chartdata[2][0] = "Nett";
        $chartdata[3][0] = "Banking";

        // Set counters
        $chart_col = 1;
        $chart_row = 1;
        $grosstotal = 0.00;
        $netttotal = 0.00;
        $bankingtotal = 0.00;
        mysql_data_seek($result2, 0); //  Move pointer to first record.

        $hasData = false; // Check if chart has data to show

        while ($row = mysql_fetch_array($result2)) {
            $hasData = true;
            /*file_put_contents('php://stderr', print_r('gross: ' . $row["sumgrosssales"] . "\n", TRUE));
            file_put_contents('php://stderr', print_r('Nett: ' . $row["sumnettsales"] . "\n", TRUE));
            file_put_contents('php://stderr', print_r('bank: ' . $row["sumbankingsales"] . "\n", TRUE));*/
            $chartdata[1][$chart_col] = $row["sumgrosssales"];    // SET GROSS
            $chartdata[2][$chart_col] = $row["sumnettsales"];    // SET NETT
            $chartdata[3][$chart_col] = $row["sumbankingsales"];    // SET BANKING
            $chart_col++;
        }
        if (!$hasData) {
            for($i = 0; $i < 7; $i++) {
                $chartdata[1][$i + 1] = 0;    // SET GROSS
                $chartdata[2][$i + 1] = 0;    // SET NETT
                $chartdata[3][$i + 1] = 0;    // SET BANKING
            }
        }
        // file_put_contents('php://stderr', print_r($chartdata, TRUE));
    }


    if (isset($_GET['str'])) {
        $_SESSION["selectedStore"] = $_GET['str'];
    }

    function getLastNDays($days, $format = 'y/m/d'){
        $m = date("m"); $de= date("d"); $y= date("Y");
        $dateArray = "'".date("Y/m/d",mktime(0, 0, 0, $m,$de-1, $y))."'"; // Set up first date in string
    	for($i=2;$i<=$days;$i++) { // Add all other dates for date range into string
    	   $dateArray .= ",'".date("Y/m/d",mktime(0, 0, 0, $m,$de-$i, $y))."'";
    	}
        return $dateArray;
    }

    if (isset($_GET['str'])) {
        $daterange = getLastNDays(7);
        $result3 = GetTotalSummary($daterange, $_SESSION["selectedStore"]); // Get group summary data
        $sumIDResult1 = GetSumIDforWithDateRange($daterange, $_SESSION["selectedStore"]);

        if(mysql_num_rows($sumIDResult1) > 0) {
        	$sumidrow1 = mysql_fetch_array($sumIDResult1);
        	$sumid1 = "'".$sumidrow1["sumid"]."'";
        }
        while($sumidrow1 = mysql_fetch_array($sumIDResult1)) {
        	$sumid1 = $sumid1.",'".$sumidrow1["sumid"]."'";
        }
        $chartdata1 = array(array());
        // Fill with Zeros
        for($y=0;$y <= count($date_array)  ;$y++) {
        	for($x=0;$x <= 3;$x++) {
        		$chartdata1[$x][$y] = "0.00";
        	}
        }

        $chartdata1[0][0] = ""; // Set first blank cell

        // SET DATE COLUMN HEADER
        for($i=0;$i < count($date_array);$i++){
        $chartdata1[0][$i+1] = $date_array[$i];
        }
        // SET DATE ROW HEADER ON LEFT

        $chartdata1[1][0] = "Gross";
        $chartdata1[2][0] = "Nett";
        $chartdata1[3][0] = "Banking";

        // Set counters
        $chart_col1 = 1;
        $chart_row1 = 1;
        $grosstotal1 = 0.00;
        $netttotal1 = 0.00;
        $bankingtotal1 = 0.00;
        mysql_data_seek($result3,0); //  Move pointer to first record.
        while($row = mysql_fetch_array($result3)) {
            $chartdata1[1][$chart_col1] = $row["sumgrosssales"];	// SET GROSS
            $chartdata1[2][$chart_col1] = $row["sumnettsales"];	// SET NETT
            $chartdata1[3][$chart_col1] = $row["sumbankingsales"];	// SET BANKING
            $chart_col1++;
        }
        file_put_contents('php://stderr', print_r($chartdata1, TRUE));
    }

?>
<script type="text/javascript">
    google.charts.load('current', {packages: ['corechart', 'bar']});
    google.charts.setOnLoadCallback(drawTopX);
    google.charts.setOnLoadCallback(drawTopX1);
    function drawTopX() {
        var strDates = [];
        var dates = [];
        var date = new Date();
        var m_names = new Array("Jan", "Feb", "Mar",
            "Apr", "May", "Jun", "Jul", "Aug", "Sep",
            "Oct", "Nov", "Dec");

        for (var i = 1; i < 8; i++){
            var tempDate = new Date();
            tempDate.setDate(date.getDate()-i);
            dates.push(tempDate);
            var str = tempDate.getDate() + "/" + m_names[tempDate.getMonth()];
            strDates.push(str);
        }
        var data = google.visualization.arrayToDataTable([
          ['Date', 'GROSS', 'NETT', 'Banking Sales'],
          [strDates[6], <?php echo $chartdata[1][1].','.$chartdata[2][1].','.$chartdata[3][1]; ?>],
          [strDates[5], <?php echo $chartdata[1][2].','.$chartdata[2][2].','.$chartdata[3][2]; ?>],
          [strDates[4], <?php echo $chartdata[1][3].','.$chartdata[2][3].','.$chartdata[3][3]; ?>],
          [strDates[3], <?php echo $chartdata[1][4].','.$chartdata[2][4].','.$chartdata[3][4]; ?>],
          [strDates[2], <?php echo $chartdata[1][5].','.$chartdata[2][5].','.$chartdata[3][5]; ?>],
          [strDates[1], <?php echo $chartdata[1][6].','.$chartdata[2][6].','.$chartdata[3][6]; ?>],
          [strDates[0], <?php echo $chartdata[1][7].','.$chartdata[2][7].','.$chartdata[3][7]; ?>],
        ]);

      var materialChart = new google.charts.Bar(document.getElementById('chart_div'));
      materialChart.draw(data);
    }
    function drawTopX1() {
        var strDates = [];
        var dates = [];
        var date = new Date();
        var m_names = new Array("Jan", "Feb", "Mar",
            "Apr", "May", "Jun", "Jul", "Aug", "Sep",
            "Oct", "Nov", "Dec");

        for (var i = 1; i < 8; i++){
            var tempDate = new Date();
            tempDate.setDate(date.getDate()-i);
            dates.push(tempDate);
            var str = tempDate.getDate() + "/" + m_names[tempDate.getMonth()];
            strDates.push(str);
        }
        var data = google.visualization.arrayToDataTable([
          ['Date', 'GROSS', 'NETT', 'Banking Sales'],
          [strDates[6], <?php echo $chartdata1[1][1].','.$chartdata1[2][1].','.$chartdata1[3][1]; ?>],
          [strDates[5], <?php echo $chartdata1[1][2].','.$chartdata1[2][2].','.$chartdata1[3][2]; ?>],
          [strDates[4], <?php echo $chartdata1[1][3].','.$chartdata1[2][3].','.$chartdata1[3][3]; ?>],
          [strDates[3], <?php echo $chartdata1[1][4].','.$chartdata1[2][4].','.$chartdata1[3][4]; ?>],
          [strDates[2], <?php echo $chartdata1[1][5].','.$chartdata1[2][5].','.$chartdata1[3][5]; ?>],
          [strDates[1], <?php echo $chartdata1[1][6].','.$chartdata1[2][6].','.$chartdata1[3][6]; ?>],
          [strDates[0], <?php echo $chartdata1[1][7].','.$chartdata1[2][7].','.$chartdata1[3][7]; ?>],
        ]);

      var materialChart = new google.charts.Bar(document.getElementById('chart_div1'));
      materialChart.draw(data);
    }
</script>
<!--News and no data Alerts-->

<div class="col-sm-12" style="background-color: #ededed; margin-bottom: 10px;">
    <h3 class="text-center" style="color: #007BC4!important;">Total Summary Graphs</h3>
    <div class="row">
        <div class="col-sm-6">
            <div clas="row">
                <?php $result = GetStoreGroupsThatUserCanMaintain($_SESSION["usrid"]); ?>
                <div class="col-sm-12" style="padding-left:0px;">
                    <div class="btn-group" style="float: right">
                        <button class="btn btn-default btn-lg dropdown-toggle" type="button" data-toggle="dropdown">
                            Select Group <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu">
                            <?php while ($row = mysql_fetch_array($result)) {
                                if (isset($_GET["str"])) {
                                    $link = 'index.php?p=home&str=' . $_GET["str"] . '&grp=' . $row["grpid"];
                                } else {
                                    $link = 'index.php?p=home&grp=' . $row["grpid"];
                                }
                                if ($row["grpstatus"] == "active") {
                                    echo "<li><a href='" . $link . "'>" . $row["grpname"] . "</a></li>";
                                }
                            } ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <div clas="row">
                <div class="col-sm-12" style="padding-left:0px; margin-bottom: 20px;">
                    <div class="btn-group" style="float: left">
                        <button class="btn btn-default btn-lg dropdown-toggle" type="button" data-toggle="dropdown">
                            Select Store <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu">
                            <?php
                            $result = GetStoresThatUserCanAccess($_SESSION["usrid"]);
                            while($row = mysql_fetch_array($result)) {
                                if (isset($_GET["grp"])) {
                                    $link = 'index.php?p=home&str=' . $row["strid"] . '&grp=' . $_GET["grp"];
                                } else {
                                    $link = 'index.php?p=home&str=' . $row["strid"];
                                }
                                echo "<li><a href='".$link."'>".$row["strname"]."</a></li>";
                            }

                            ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="col-sm-10 col-sm-offset-1 graphs_row">
    <div class="row">
        <div class="<?php if(isset($_GET['str'])) {echo 'col-md-12 col-lg-6';} else {echo 'col-lg-12';} ?>">
            <?php
            if (isset($_GET["grp"])) {
                $result = GetStoreGroupsThatUserCanMaintain($_SESSION["usrid"]);
                while ($row = mysql_fetch_array($result)) {
                    file_put_contents('php://stderr', print_r($row["grpid"], TRUE));
                    if ($row["grpid"] == $_SESSION["selectedGroup"]) { ?>
                        <h3 style="text-transform: uppercase;">
                            <?php echo $row['grpname'] . " "; ?>
                        </h3>
                        <h5 style="color:#757575">Total Sales Summary</h5>
                        <h6 style="color:#BDBDBD">Based on the last week</h6>
                        <div class="row">
                            <div class="col-sm-12">
                                <!--Div that will hold the bar chart-->
                                <?php echo '<div id="chart_div" style="height: 400px;"></div>'; ?>
                            </div>
                        </div>
                    <?php }
                }
            } ?>
        </div>
        <div class="<?php if (isset($_GET["grp"])) {echo 'col-md-12 col-lg-6';} else {echo 'col-lg-12';}?>">
            <?php
            $result = GetStoresThatUserCanAccess($_SESSION["usrid"]); // Get the row that is equal to the chosen store, to access all data
            while($row = mysql_fetch_array($result)) {
                if (isset($_GET['str']) && $row["strid"] == $_GET['str']) { // Store selected, show store overview
                    ?>
                    <h3 style="text-transform: uppercase;">
                        <?php echo $row['strname']." ";?>
                    </h3>
                    <h5 style="color:#757575">Total Sales Summary</h5>
                    <h6 style="color:#BDBDBD">Based on the last week</h6>
                    <div class="row">
                        <div class="col-sm-12">
                            <!--Div that will hold the bar chart-->
                            <div id="chart_div1" style="height: 400px;"></div>
                        </div>
                    </div>
                    <?php
                }
            }
            ?>
        </div>
    </div>
</div>
<div class="col-sm-10 col-sm-offset-1">
    <div class="row">
        <div class="col-sm-6">
            <h4 style="color:#757575">Stores with no data for <?php echo $yesterdaymonth."/".$yesterdayday."/".$yesterdayyear ?>:</h4>
            <div class="row">
                <?php $result = GetStoresThatUserCanAccess($_SESSION["usrid"]);
                $noAlerts = true;
                while($row = mysql_fetch_array($result)) {
                    $reportresult = GetSummaryGrossSales($yesterdayyear."/".$yesterdaymonth."/".$yesterdayday/*"2017/04/26"*/, "'".$row["strid"]."'");
                    $reportrow = mysql_fetch_array($reportresult);
                    if(mysql_num_rows($reportresult) > 0) { // there is a record
                        if($reportrow["sumgrosssales"] != '0.00') {   //All Data Available
                            $showreport = 'true';
                            $storeclosed = 'false';
                        } else { // Store Closed
                            $showreport = 'false';
                            $storeclosed = 'true';
                            $noAlerts = false;
                        }
                    } else { // No record at all found
                        $showreport = 'false';
                        $storeclosed = 'false';
                        $noAlerts = false;
                    }
                    /*if($storeclosed == 'true') {
                        echo '<div class="col-sm-6"><div class="alert alert-danger alert-dismissable">
                                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                  <strong>'.$row["strname"].'</strong> was closed.
                                </div></div>';
                    }*/
                    if($showreport == 'false' && $storeclosed == 'false') {
                        echo '<div class="col-sm-12"><div class="alert alert-danger alert-dismissable">
                                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                  <strong>'.$row["strname"].'</strong> has no reports.
                                </div></div>';
                    }
                }
                if ($noAlerts == true) {
                    echo '<div class="col-sm-12"><div class="alert alert-success alert-dismissable">
                                 <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                 <strong>All stores have data available.</strong>
                               </div></div>';
                }
                ?>
            </div>
        </div>
        <div class="col-sm-6">
            <h4 style="color:#757575">Stores closed on <?php echo $yesterdaymonth."/".$yesterdayday."/".$yesterdayyear ?>:</h4>
            <div class="row">
                <?php $result1 = GetStoresThatUserCanAccess($_SESSION["usrid"]);
                $noAlerts = true;
                while($row = mysql_fetch_array($result1)) {
                    $reportresult = GetSummaryGrossSales($yesterdayyear."/".$yesterdaymonth."/".$yesterdayday/*"2017/04/26"*/, "'".$row["strid"]."'");
                    $reportrow = mysql_fetch_array($reportresult);
                    if(mysql_num_rows($reportresult) > 0) { // there is a record
                        if($reportrow["sumgrosssales"] != '0.00') {   //All Data Available
                            $showreport = 'true';
                            $storeclosed = 'false';
                        } else { // Store Closed
                            $showreport = 'false';
                            $storeclosed = 'true';
                            $noAlerts = false;
                        }
                    } else { // No record at all found
                        $showreport = 'false';
                        $storeclosed = 'false';
                        $noAlerts = false;
                    }
                    if($storeclosed == 'true') {
                        echo '<div class="col-sm-12"><div class="alert alert-warning alert-dismissable">
                                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                  <strong>'.$row["strname"].'</strong> was closed.
                                </div></div>';
                    }
                    /*if($showreport == 'false') {
                        echo '<div class="col-sm-6"><div class="alert alert-danger alert-dismissable">
                                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                  <strong>'.$row["strname"].'</strong> has no reports.
                                </div></div>';
                    }*/
                }
                if ($noAlerts == true) {
                    echo '<div class="col-sm-12"><div class="alert alert-success alert-dismissable">
                                 <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                 <strong>No stores were closed.</strong>
                               </div></div>';
                }
                ?>
            </div>
        </div>
    </div>
</div>

