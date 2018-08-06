<html>
<head>
<title>inTouchLink - Aloha Point Of Sale (POS) online reporting system, Exceptions Listing</title>
</head>
<body>
<div>
<?php
    $yesterday1 = $_REQUEST["yesterday"];
    $localstore="-1";
    $exceptionreportshown=0;
    $result = GetStoresThatUserCanAccess($_SESSION["usrid"]); // Get user's stores
    if (mysql_num_rows($result)>0)
    {
    	while($row = mysql_fetch_array($result)) 
	{
        	$localstore = $localstore .",". $row["strid"];	
    	}
    }
    else //exception report processed
    {
	$exceptionreportshown=1;  //exception report processed
    }

    $localresult=  YesterdayExceptions($yesterday1,$localstore);
    if (mysql_num_rows($localresult)>0)
    {
        echo "There are stores that exceeded exception values limits and details:\n";
	echo "<br/>"; 
        echo "===================================================================\n";
	echo "<br/>"; 
        while ($locrow=mysql_fetch_row($localresult))
        {
            echo "Summary ID=".$locrow["summaryid"]."\n";
	    echo "<br/>"; 
            echo "TotalVoidAmount=".$locrow["TotalVoidAmount"]."\n";
	    echo "<br/>"; 
            echo "ChecksVoided=".$locrow["ChecksVoided"]."\n";
	    echo "<br/>"; 
            echo "ReopenedChecksAmt=".$locrow["ReopenedChecksAmt"]."\n";
	    echo "<br/>"; 
            echo "NoOfReopenedChecks=".$locrow["NoOfReopenedChecks"]."\n";
	    echo "<br/>"; 
            echo "SplitValue=".$locrow["SplitValue"]."\n";
	    echo "<br/>"; 
            echo "NoOfSplits=".$locrow["NoOfSplits"]."\n";
	    echo "<br/>"; 
            echo "TransfersValue=".$locrow["TransfersValue"]."\n";
	    echo "<br/>"; 
            echo "NoOfTransfers=".$locrow["NoOfTransfers"]."\n";
	    echo "<br/>"; 
            echo "ClearsValue=".$locrow["ClearsValue"]."\n";
            echo "<br/>"; 
            echo "NoOfClears=".$locrow["NoOfClears"]."\n";
	    echo "<br/>"; 
            echo "RefundsValue=".$locrow["RefundsValue"]."\n";
	    echo "<br/>"; 
            echo "NoOfRefunds=".$locrow["NoOfRefunds"];
        }
	$exceptionreportshown=1; //exception report processed
    }
    else
    {
    	$exceptionreportshown=1;  //exception report processed
    }
?>
</div>
</body>
</html>