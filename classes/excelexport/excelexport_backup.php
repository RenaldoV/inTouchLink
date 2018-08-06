<?php
session_start();
require_once('../../library/library.php'); 
db_connect();
set_time_limit(30000);

require_once "class.writeexcel_workbook.inc.php";
require_once "class.writeexcel_worksheet.inc.php";

$grpid = $_REQUEST["grpid"];
$radStores = $_REQUEST["radStores"];

$fname = tempnam("/tmp", "demo.xls");
$workbook =& new writeexcel_workbook($fname);
$worksheet =& $workbook->addworksheet('Report');
//$worksheet2 =& $workbook->addworksheet('Another sheet');
//$worksheet3 =& $workbook->addworksheet('And another');


// Write header image
$worksheet->set_column('A:B', 1);
$worksheet->set_column('C:C', 20);
$worksheet->set_column('D:J', 14.75);
$worksheet->insert_bitmap('a1', 'report_header.bmp', 16, 8); // Write Header

// Write Report Content

// Set styles
$heading  =& $workbook->addformat(array(
                                        bold    => 1,
                                        color   => '007CC4',
                                        size    => 12,
                                        merge   => 1,
										font    => 'Arial'
                                        )); // Create new font style

// Set up report title
$title = "Sales Summary Report for ";				 
$headings = array($title, '');
$worksheet->write_row('C6', $headings, $heading);


$worksheet->insert_bitmap('a50', 'report_footer.bmp', 16, 8); // Write Footer

/*
#######################################################################
#
# Write a general heading
#
$worksheet->set_column('A:B', 32); // Set column widths
$heading  =& $workbook->addformat(array(
                                        bold    => 1,
                                        color   => '007CC4',
                                        size    => 18,
                                        merge   => 1,
										font    => 'Arial'
                                        )); // Create new font style

$headings = array('Features of php_writeexcel', '');
$worksheet->write_row('A1', $headings, $heading);

#######################################################################
#
# Some text examples
#
$text_format =& $workbook->addformat(array(
                                            bold    => 1,
                                            italic  => 1,
                                            color   => 'red',
                                            size    => 18,
                                            font    => 'Arial'
                                        ));

$worksheet->write('A2', "Text");
$worksheet->write('B2', "Hello Excel");
$worksheet->write('A3', "Formatted text");
$worksheet->write('B3', "Hello Excel", $text_format);

#######################################################################
#
# Some numeric examples
#
$num1_format =& $workbook->addformat(array(num_format => '$#,##0.00'));
$num2_format =& $workbook->addformat(array(num_format => ' d mmmm yyy'));

$worksheet->write('A4', "Numbers");
$worksheet->write('B4', 1234.56);
$worksheet->write('A5', "Formatted numbers");
$worksheet->write('B5', 1234.56, $num1_format);
$worksheet->write('A6', "Formatted numbers");
$worksheet->write('B6', 37257, $num2_format);

#######################################################################
#
# Formulae
#
$worksheet->set_selection('B7');
$worksheet->write('A7', 'Formulas and functions, "=SIN(PI()/4)"');
$worksheet->write('B7', '=SIN(PI()/4)');

#######################################################################
#
# Hyperlinks
#
$worksheet->write('A8', "Hyperlinks");
$worksheet->write('B8',  'http://www.php.net/');

#######################################################################
#
# Images
#
$worksheet->write('A9', "Images");
$worksheet->insert_bitmap('B9', 'php.bmp', 16, 8);

#######################################################################
#
# Misc
#
$worksheet->write('A17', "Page/printer setup");
$worksheet->write('A18', "Multiple worksheets");
*/

$workbook->close(); // Close the workbook

header("Content-Type: application/x-msexcel; name=\"export.xls\"");
header("Content-Disposition: inline; filename=\"export.xls\"");
$fh=fopen($fname, "rb");
fpassthru($fh);
unlink($fname);

 db_close(); // Close DB
?>
