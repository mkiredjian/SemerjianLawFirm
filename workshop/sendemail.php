<?php

// Define some constants
define( "RECIPIENT_NAME", "Semerjian Law Firm" );
define( "RECIPIENT_EMAIL", "mosigk@gmail.com" );


// Read the form values
$success = false;
$topic = $_POST['topic'];
$userName = isset( $_POST['firstname'] ) ? preg_replace( "/[^\.\-\' a-zA-Z0-9]/", "", $_POST['firstname'] ) : "";
$senderEmail = isset( $_POST['email'] ) ? preg_replace( "/[^\.\-\' a-zA-Z0-9]/", "", $_POST['email'] ) : "";
$message = isset( $_POST['message'] ) ? preg_replace( "/(From:|To:|BCC:|CC:|Subject:|Content-Type:)/", "", $_POST['message'] ) : "";

// If all values exist, send the email
if ( $userName && $senderEmail && $message) {
  $recipient = RECIPIENT_NAME . " <" . RECIPIENT_EMAIL . ">";
  $subject = "Topic is ".$topic."";
  $headers = "From: " . $username . " <" . $senderEmail . ">";
  $msgBody = " Message: " . $message . "";
  $success = mail( $recipient, $subject,$msgBody,$headers);

  //Set Location After Successsfull Submission
  header('Location: contact.html?message='.$success);
}

else{
	//Set Location After Unsuccesssfull Submission
  	header('Location: index.html?message=Failed');	
}

?>