<?php
session_cache_limiter('public_no_cache');
session_start();

//include charts.php to access the SendChartData function
include "charts.php";

$chart [ 'chart_data' ] =  $_SESSION["totalcomparison_chartdata"];

                               
$chart [ 'chart_type' ] = "line";

$chart [ 'chart_value' ] = array (  'prefix'         =>  "", 
                                    'suffix'         =>  "", 
                                    'decimals'       =>  2,
                                    'decimal_char'   =>  ".",  
                                    'separator'      =>  "",
                                    'position'       =>  "above",
                                    'hide_zero'      =>  false, 
                                    'as_percentage'  =>  false, 
                                    'font'           =>  "Arial", 
                                    'bold'           =>  true, 
                                    'size'           =>  9, 
                                    'color'          =>  "000000", 
                                    'alpha'          =>  50
                                  ); 

$chart [ 'legend_label' ] = array (   'layout'  =>  string, 
                                      'bullet'  =>  string,
                                      'font'    =>  "Arial", 
                                      'bold'    =>  "false", 
                                      'size'    =>  12, 
                                      'color'   =>  string, 
                                      'alpha'   =>  int 
                                  ); 

$chart [ 'axis_category' ] = array (   'skip'          =>  0,
                                       'font'          =>  "Arial", 
                                       'bold'          =>  true, 
                                       'size'          =>  12, 
                                       'color'         =>  "000000", 
                                       'alpha'         =>  100,
                                       'orientation'   =>  "diagonal_up",
                                       
                                       //area, stacked area, line charts
                                       'margin'        =>  0,
                                       
                                       //scatter charts
                                       'min'           =>  float,  
                                       'max'           =>  float, 
                                       'steps'         =>  int,
                                       'prefix'        =>  string, 
                                       'suffix'        =>  string, 
                                       'decimals'      =>  int,
                                       'decimal_char'  =>  string,  
                                       'separator'     =>  string, 
                                   ); 

$chart [ 'axis_value' ] = array (   'min'              =>  0,  
                                    'max'              =>  0, 
                                    'steps'            =>  8,  
                                    'prefix'           =>  "", 
                                    'suffix'           =>  "", 
                                    'decimals'         =>  0,
                                    'decimal_char'     =>  ".",  
                                    'separator'        =>  "", 
                                    'show_min'         =>  true, 
                                    'font'             =>  "Arial", 
                                    'bold'             =>  true, 
                                    'size'             =>  12, 
                                    'color'            =>  "000000", 
                                    'background_color' =>  "FFFFFF", 
                                    'alpha'            =>  90,
                                    'orientation'      =>  "horinzontal" 
                                );

SendChartData ( $chart );

?>