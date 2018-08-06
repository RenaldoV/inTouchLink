<?php
// Get variables
$txtoldpassword = $_REQUEST["txtoldpassword"];
$txtnewpassword = $_REQUEST["txtnewpassword"];
$txtnewpasswordconfirm = $_REQUEST["txtnewpasswordconfirm"];

?>

<link href="../style.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
.style1 {color: #FFFFFF}
.style3 {
	font-size: 10px;
	color: #FF0000;
}
.style12 {
	color: #009933;
	font-weight: bold;
}
-->
</style>


<div class='col-sm-12'>
    <div class="row form-row">
        <div class="col-sm-8 col-sm-offset-2 formdiv">
            <h3 class="text-center" style="text-transform: uppercase;">
                Change Your Password
            </h3>
			<form id="form1" name="form1" method="post" action="index.php?p=changepassword&a=s" onSubmit="return validateform(this);">
				<div class="form-group">
					<input name="txtoldpassword" type="password" class="form-control" id="txtoldpassword" placeholder="Old Password*">
				</div>
				<div class="form-group">
					<input name="txtnewpassword" type="password" class="form-control" id="txtnewpassword" placeholder="New Password*"/>
				</div>
				<div class="form-group">
					<input name="txtnewpasswordconfirm" type="password" class="form-control" id="txtnewpasswordconfirm" placeholder="New Password*"/>
				</div>
				<input name="Submit" type="submit" class="btn btn-default" value="Submit" style="color: white; background-color: #007CC4"/>
			</form>
            <br>
            <br>
            <br>
			<?php
			if($a == "s") { // If page submitted...
				file_put_contents('php://stderr', print_r(IsLoginValid($_SESSION["userUserID"],$txtoldpassword), TRUE));
				if(IsLoginValid($_SESSION["userUserID"],$txtoldpassword) != "false") { // Login details valid
					echo '<div class="alert alert-success alert-dismissable">
							  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
							  <strong>Success!</strong> Your password has now been changed.
						  </div>';
					ChangePassword($_SESSION["usrid"],$txtnewpassword); // Change password
				} else {
					echo '<div class="alert alert-danger alert-dismissable">
						  	<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
						  	<strong>Error!</strong> Your Old Password was typed in incorrectly.<br />Please try again.
						  </div>'; // Login not valid. Try again
					echo "<script language='javascript1.2'>document.form1.txtoldpassword.focus();</script>";
				}
			}
			?>
		</div>
	</div>
</div>

<script language="JavaScript1.2">
 function validateform(theform) {

	 var result = true;

	 if(theform.txtoldpassword.value == "") {
		 alert('Please supply your old password.');
		document.form1.txtoldpassword.focus();
		 result = false;
	 } else {

	  	 if(theform.txtnewpassword.value == "") {
		 alert('Please supply your new password.');
		document.form1.txtnewpassword.focus();
		 result = false;
	 	} else {

	  	 if(theform.txtnewpassword.value != theform.txtnewpasswordconfirm.value) {

		 alert('New password does not match confirm password.');
		 document.form1.txtnewpassword.value = "";
		 document.form1.txtnewpasswordconfirm.value = "";
		 document.form1.txtnewpassword.focus();
		 result = false;
	 }


	 }


	 }



	 return result;
 }


 </script>
