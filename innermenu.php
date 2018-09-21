<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
<link rel="stylesheet" type="text/css" href="chrometheme/chromestyle.css" />
<script src="landing/js/jquery-2.2.4.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
<script type="text/javascript" src="chromejs/chrome.js">
/***********************************************
* Chrome CSS Drop Down Menu- � Dynamic Drive DHTML code library (www.dynamicdrive.com)
* This notice MUST stay intact for legal use
* Visit Dynamic Drive at http://www.dynamicdrive.com/ for full source code
***********************************************/
</script>
<style>
    button.btn {
        border-radius: 0px;
    }
    button.btn.btn-default:active:focus, button.btn.btn-default:active, button.btn.btn-default:focus {
        outline: none;
    }
    ul.dropdown-menu {
        overflow: auto;
        max-height: 75vh;
    }
    li.dropdown-header {
        text-transform: uppercase;
        font-size: 12px;
    }
    .navbar-nav>li>ul.dropdown-menu>li.divider {
        padding: 0px;
    }
    .style3 {
        font-family: 'Nunito', sans-serif;!important;
        font-size: 13px;
    }
   td.NormalText {
       font-family: 'Nunito', sans-serif;!important;
   }
    td.bold {
        font-size: 15px;!important;
        font-weight: bold;!important;
    }
    @media only screen and (max-width: 768px) {
        div.navbar-collapse.collapse.in {
            background-color: white;
            border-top: solid 2px #007BC4;
            border-bottom: solid 2px #007BC4;
        }
        ul.nav.navbar-nav {
            float: left;
        }
    }
    @-webkit-keyframes swing
    {
        15%
        {
            -webkit-transform: translateX(5px);
            transform: translateX(5px);
        }
        30%
        {
            -webkit-transform: translateX(-5px);
            transform: translateX(-5px);
        }
        50%
        {
            -webkit-transform: translateX(3px);
            transform: translateX(3px);
        }
        65%
        {
            -webkit-transform: translateX(-3px);
            transform: translateX(-3px);
        }
        80%
        {
            -webkit-transform: translateX(2px);
            transform: translateX(2px);
        }
        100%
        {
            -webkit-transform: translateX(0);
            transform: translateX(0);
        }
    }
    @keyframes swing
    {
        15%
        {
            -webkit-transform: translateX(5px);
            transform: translateX(5px);
        }
        30%
        {
            -webkit-transform: translateX(-5px);
            transform: translateX(-5px);
        }
        50%
        {
            -webkit-transform: translateX(3px);
            transform: translateX(3px);
        }
        65%
        {
            -webkit-transform: translateX(-3px);
            transform: translateX(-3px);
        }
        80%
        {
            -webkit-transform: translateX(2px);
            transform: translateX(2px);
        }
        100%
        {
            -webkit-transform: translateX(0);
            transform: translateX(0);
        }
    }

    h3 {
        font-family: 'Oswald', Helvetica, Arial, Lucida, sans-serif;
        font-size: 37px;
        letter-spacing: 2px;
        line-height: 1.5em;
        color: #5b5b5b!important;
        text-transform: uppercase;
    }
    button.btn.btn-lg.dropdown-toggle {
        border-radius: 0px;
        border: solid 1px black;
        text-transform: uppercase;
        font-family: 'Oswald', Helvetica, Arial, Lucida, sans-serif;
    }
    div.form-row {
        background-color: #fafafa;
        padding-bottom: 15px
    }
    div.formdiv {
        height: auto
    }
    ul.dropdown-menu {
        font-family: 'Oswald', Helvetica, Arial, Lucida, sans-serif;
        border-top: solid 3px #2ea3f2;
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
    span.filter-option{
        font-family: 'Oswald', Helvetica, Arial, Lucida, sans-serif;
        letter-spacing: 1px;
        text-transform: uppercase;
        font-size: 13px;
        font-weight: 500;
    }
    div.input-group {
        background-color: rgba(234,234,234,0.61);
        border-radius: 5px;
    }
    div.input-group.disabled {
        background-color: #d6d6d6;!important;
        border-radius: 5px;
    }
    .bootstrap-select.btn-group.show-tick .dropdown-menu li a span.text {
        font-family: 'Oswald', Helvetica, Arial, Lucida, sans-serif;
        letter-spacing: 1px;
    }
    div.dropdown-menu.open {
        border-top: solid 3px #2ea3f2;
    }
    .input-group.disabled div.bootstrap-select button.dropdown-toggle {
        background-color: #d6d6d6;!important;
        cursor: no-drop;
    }
    input.form-control[disabled], select.form-control[disabled] {
        background-color: #d6d6d6;!important;
    }
    .form-control[disabled], .form-control[readonly], fieldset[disabled] .form-control{
        background-color: #d6d6d6;!important;
        cursor: no-drop;
    }
    .input-group-addon {
        z-index: 20;
        border: none;
        -webkit-box-shadow: none;
        box-shadow: none;
        background-color: rgba(234,234,234,0.61);
        border-bottom-left-radius: 5px;
        border-top-left-radius: 5px;
    }
    .radio-addon {
        z-index: 40;
        border-top-left-radius: 5px;
        border-bottom-left-radius: 5px;
        width: 37px;
        float: left;
        height: 100%;
        background-color: inherit;
        position: absolute;
    }
    .radio-addon.disabled {
        background-color: #d6d6d6;!important;
    }
    .radio-addon input.empRadBtns {
        margin-top: 17px;
        margin-left: 12px;
    }
    .employeesMulti {
        border-radius: 5px;
        background-color: rgba(234,234,234,0.61);
        height: 50px;
        width: 100%;
        position: relative;
    }
    button
    .employeesMulti.disabled {
        background-color: #d6d6d6;!important;
    }
    .employeesMulti > div.btn-group.bootstrap-select {
        padding-left: 37px;
        border-radius: 5px;
    }
    .employeesMulti > div.btn-group.bootstrap-select.disabled {
        background-color: #d6d6d6;!important;
    }
    div.btn-group.bootstrap-select.disabled > button {
        background-color: #d6d6d6;!important;
        cursor: no-drop;
    }
    .btn.dropdown-toggle.disabled {
        background-color: #d6d6d6;!important;
    }
    .input-group-addon {
        border: none;
        -webkit-box-shadow: none;
        box-shadow: none;
        background-color: rgba(234,234,234,0.61);
        border-bottom-left-radius: 5px;
        border-top-left-radius: 5px;
    }
    div.input-group.disabled .input-group-addon {
        background-color: #d6d6d6;
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
    label {
        font-family: 'Oswald', Helvetica, Arial, Lucida, sans-serif;
        text-transform: uppercase;
        letter-spacing: 1px;
    }
    .navbar-default .navbar-nav>.open>a, .navbar-default .navbar-nav>.open>a:focus, .navbar-default .navbar-nav>.open>a:hover {
        color: lightgray;
        background-color: white;
    }
    .navbar-nav>li>ul.dropdown-menu>li>a {
         font-family: 'Oswald', Helvetica, Arial, Lucida, sans-serif;
         text-transform: uppercase;
         font-weight: normal;
         font-size: 15px;
    }
    .navbar-nav>li>ul.dropdown-menu {
        margin-top: 24px;
        border-top: solid 3px #2ea3f2;
    }
    .navbar-nav>li>ul.dropdown-menu>li {
        padding: 3px 10px 3px 10px;
    }
    a.navbar-brand.noleftpad {
        padding-left: 15px;
        margin-left: 47px;
        margin-bottom: 40px;
    }
    nav.navbar.navbar-default.navbar-fixed-top {
        background-color: white;
        height: 90px;
    }
    ul.navbar-nav {
        margin-right: 30px;
        float: right;
    }
    li.logof > a {
        border: solid 1px;
    }
    ul.nav.navbar-nav > li > a {
        font-family: 'Oswald', Helvetica, Arial, Lucida, sans-serif;
        text-transform: uppercase;
        font-weight: normal;
        font-size: 18px;
        margin-top:15px;
        color: #4f4f4f;
        transition: all .4s ease-in-out;
    }
    ul.nav.navbar-nav > li > a:hover {
        color: lightgray;
    }
    nav.navbar-default .navbar-nav>.active>a, .navbar-default .navbar-nav>.active>a:focus, .navbar-default .navbar-nav>.active>a:hover {
        background-color: white;
        color: #2ea3f2;
        text-decoration: none;
    }
    .navbar.navbar-nav {
        background-color: white;
        border-color: white;
    }
    h4 {
        font-family: 'Oswald', Helvetica, Arial, Lucida, sans-serif;
        text-transform: uppercase;
        letter-spacing: 1px;
    }
    p, strong, span, h5 {
        font-family: 'Nunito', sans-serif;
    }
    .SmallText {
        font-size: 12px;
        font-family: 'Nunito', sans-serif;
    }
</style>
<!-- <div id="chromemenu">
  <ul>
    <li><a href="index.php?p=home">Home</a></li>
    <li><a href="#" onMouseover="cssdropdown.dropit(this,event,'dropmenu1')">Reports</a></li>
    <?php //if($_SESSION["userUserID"] != "Demo") {?>
      <li><a href="index.php?p=changepassword">Change Password</a></li>
    <?php //} ?>
    <?php //if($_SESSION["usr_adminacess"] == "yes" || $_SESSION["usr_poweruseracess"] == "yes") {?>
      <li><a href="#" onMouseover="cssdropdown.dropit(this,event,'dropmenu2')">Admin</a></li>
    <?php //} ?>
    <li><a href="index.php?lgoff=1">Log Off</a></li>
  </ul>
</div> -->


<!--1 drop down menu -->
<!-- <div id="dropmenu1" class="dropmenudiv" style="width: 170px;">
  <a href="index.php?p=report_databrowser">Data Browser</a>
  <a href="index.php?p=report_dataexception">Data Exception</a>
  <a href="index.php?p=report_salessummary">Sales Summary</a>
  <a href="index.php?p=report_totalsummary">Total Summary</a>
  <a href="index.php?p=report_totalcomparison">Total Comparison</a>
  <a href="index.php?p=report_growthcomparison">Growth Comparison</a>
  <a href="index.php?p=report_comps">Comps</a>
  <a href="index.php?p=report_voids">Voids</a>
  <a href="index.php?p=report_productmix">Product Mix</a>
  <a href="index.php?p=report_payments">Payments</a>
  <a href="index.php?p=report_hourlysalesandlabour">Hourly Sales and Labour</a>
  <a href="index.php?p=report_revenuecentersales">Revenue Center Sales</a>
  <a href="index.php?p=report_serversales">Employee Sales</a>
  <a href="index.php?p=report_speedofservice">Speed of Service</a>
  <a href="index.php?p=report_storeperformance">Store Performance</a>
  <a href="index.php?p=report_instockpurchases">Purchases</a>
  <a href="index.php?p=report_exceptions">Exceptions Report</a> -->
  <!--a href="index.php?p=report_Purchases">Supplier Purchases</a-->
  <!--a href="index.php?p=form_exceptionbs">Capture Exceptions</a-->
<!-- </div> -->

<!--1 drop down menu -->
<!-- <div id="dropmenu2" class="dropmenudiv" style="width: 170px;">
  <?php //if($_SESSION["usr_adminacess"] == "yes" || $_SESSION["usr_poweruseracess"] == "yes") {?>
    <a href="index.php?p=admin_maintainstores">Maintain Stores</a>
  <?php //} ?>
  <a href="index.php?p=admin_maintainstoregroups">Maintain Store Groups</a>
  <a href="index.php?p=admin_maintainusers">Maintain Users</a>
  <a href="index.php?p=form_exceptions">Capture Exceptions</a>
  <a href="index.php?p=admin_storehistory">Store History</a>
</div> -->


<nav class="navbar navbar-default navbar-fixed-top" >
  <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand noleftpad" href="index.php?p=home"><img src="img/new-logo.png" style="height:50px"></a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">
        <li class="<?php if($_REQUEST["p"] == 'home') { echo 'active'; } ?>"><a href="index.php?p=home">Home</a></li>
        <li class="dropdown <?php if(strpos($_REQUEST["p"], 'report') !== false) { echo 'active'; } ?>">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Reports <span class="caret"></span></a>
          <ul class="dropdown-menu">
              <li class="dropdown-header">Sales Reports</li>
              <li role="separator" class="divider"></li>
                <li><a href="index.php?p=report_salessummary">Sales Summary</a></li>
                <li><a href="index.php?p=report_totalsummary">Total Summary</a></li>
                <li><a href="index.php?p=report_revenuecentersales">Revenue Center Sales</a></li>
                <li><a href="#">Promos (Coming Soon)</a></li>
                <li><a href="index.php?p=report_comps">Comps</a></li>
                <li><a href="index.php?p=report_voids">Voids</a></li>
                <li><a href="index.php?p=report_payments">Payments</a></li>
                <li><a href="index.php?p=report_speedofservice">Speed of Service</a></li>
              <li class="dropdown-header">Stock Reports</li>
              <li role="separator" class="divider"></li>
                <li><a href="index.php?p=report_instockpurchases">Purchases Detail Report</a></li>
                <li><a href="#">Purchases Summary Report (Coming Soon)</a></li>
                <li><a href="index.php?p=report_productmix">Product Mix</a></li>
                <li><a href="#">Group Costing Report (Coming Soon)</a></li>
                <li><a href="#">Stock Unit (Coming Soon)</a></li>
              <li class="dropdown-header">Comparison Reports</li>
              <li role="separator" class="divider"></li>
                <li><a href="index.php?p=report_totalcomparison">Total Comparison</a></li>
                <li><a href="index.php?p=report_growthcomparison">Growth Comparison</a></li>
              <li class="dropdown-header">Labour Reports</li>
              <li role="separator" class="divider"></li>
                <li><a href="index.php?p=report_hourlysalesandlabour">Hourly Sales and Labour</a></li>
                <li><a href="index.php?p=report_serversales">Employee Sales</a></li>
                <li><a href="#">Labour (Coming Soon)</a></li>
              <li class="dropdown-header">Audit Reports</li>
              <li role="separator" class="divider"></li>
                <li><a href="index.php?p=report_exceptions">Audit Report</a></li>
                <li><a href="index.php?p=report_storeperformance">Store Performance</a></li>
                <li><a href="index.php?p=report_databrowser">Data Browser</a></li>
                <li><a href="index.php?p=report_dataexception">Data Exception</a></li>
            <!--a href="index.php?p=report_Purchases">Supplier Purchases</a-->
            <!--a href="index.php?p=form_exceptionbs">Capture Exceptions</a-->
          </ul>
        </li>
        <?php if($_SESSION["userUserID"] != "Demo") {?>
          <li class="<?php if($_REQUEST["p"] == 'changepassword') { echo 'active'; } ?>">
            <a href="index.php?p=changepassword">Change Password</a>
          </li>
        <?php } ?>
        <?php if($_SESSION["usr_adminacess"] == "yes" || $_SESSION["usr_poweruseracess"] == "yes") {?>
          <li class="dropdown <?php if(strpos($_REQUEST["p"], 'admin') !== false || strpos($_REQUEST["p"], 'form') !== false) { echo 'active'; } ?>">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Admin <span class="caret"></span></a>
            <ul class="dropdown-menu">
              <?php if($_SESSION["usr_adminacess"] == "yes" || $_SESSION["usr_poweruseracess"] == "yes") {?>
                <li><a href="index.php?p=admin_maintainstores">Maintain Stores</a></li>
              <?php } ?>
              <li><a href="index.php?p=admin_maintainstoregroups">Maintain Store Groups</a></li>
              <li><a href="index.php?p=admin_maintainusers">Maintain Users</a></li>
              <li><a href="index.php?p=form_exceptions">Capture Exceptions</a></li>
              <li><a href="index.php?p=admin_storehistory">Store History</a></li>
            </ul>
          </li>
        <?php } ?>
        <li class="logof"><a href="index.php?lgoff=1">Log Off</a></li>
      </ul>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>
