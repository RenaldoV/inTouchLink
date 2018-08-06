<?php
  
  $fname = $_REQUEST["fname"];
    // Open Excel doc for download
	header("Content-Type: application/x-msexcel; name=\"export.xls\"");
	header("Content-Disposition: inline; filename=\"export.xls\"");
	$fh=fopen($fname, "rb");
	fpassthru($fh);
	//unlink($fname);
?>
