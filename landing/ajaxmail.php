<?php
// Change Here for the emails
$fromEmail = 'do-not-reply@intouchlink.co.za'; // Replace with your email address
$toEmail = 'sales@intouchpos.co.za'; // Replace with your email address
$subject = 'Enquiry from InTouchLink.co.za';
// Changes end here

$output = array();
$username = sanitize('username');
$email = sanitize('email');
$message = sanitize('msg');
$business = $_POST['business'];
$existing = $_POST['existing'];
$support = $_POST['support'];

if ($existing == "on") {

	$existing = "They are an existing customer. ";

} else {

	$existing = "They are not an existing customer. ";

}

if ($support == "on") {

	$subject = "Support Request from InTouchLink.co.za";
	$toEmail = 'service@intouchpos.co.za'; 

}

// If user has submitted the form blank
if ($email === '' || $username === '' || $message === '') {
    $output['status'] = 'fail';
    $output['message'] = '<div class="alert alert-danger">Please, fill in all your details.</div>';
    echo json_encode($output);
    exit();
}

// Validate the email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $output['status'] = 'fail';
    $output['message'] = '<div class="alert alert-warning">Please, provide valid Email.</div>';

} else {
    $message = 'Hi Admin,<p>' . $username . '(' . $business . ') has sent a query. ' . $existing . 'Email: ' . $email . '</p><p>Message: ' . $message . '</p>';
    $headers = 'MIME-Version: 1.0' . '\r\n';
    $headers .= 'Content-type:text/html;charset=UTF-8' . '\r\n';
    $headers .= 'From: <' . $fromEmail . '>' . '\r\n';

    if (mail($toEmail, $subject, $message, $headers)) {
        $output['status'] = 'success';
        $output['message'] = '<div class="alert alert-success">Mail Sent successfully.</div>';

    } else {
        $output['status'] = 'fail';
        $output['message'] = '<div class="alert alert-danger">Please, Try Again.</div>';
    }
}

// Print the response in json format
echo json_encode($output);


// Function to sanitize the post data

function sanitize($data)
{
    return filter_var(trim($_POST[$data]), FILTER_SANITIZE_STRING);
}

?>