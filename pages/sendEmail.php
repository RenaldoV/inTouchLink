<?php
//if "email" variable is filled out, send email
  if (isset($_REQUEST['email']))  {
  
  //Email information
  $admin_email = "info@mangofusion.co.za";  //<==  Client need to put his email in here and have it send to him
  $email = $_REQUEST['email'];
  $subject = $_REQUEST['subject'];
  $comment = $_REQUEST['comment'];
  
  //send email
  mail($admin_email, "$subject", $comment, "From:" . $email);
  
  //Email response
  echo "Thank you for contacting us!";
  }
  
  //if "email" variable is not filled out, display the form
  else  {
?>

 <form method="post">

  Email: <input name="email" type="text" />

  Subject: <input name="subject" type="text" />

  Message:

  <?php echo $_POST['callReport']; ?>

  <input type="submit" value="Submit" />
  </form>
  
<?php
  }
?>