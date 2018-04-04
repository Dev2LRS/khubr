<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
$to      = 'nandni.cloud@gmail.com';
$subject = 'the subject';
$message = 'hello';
$headers = 'From:nandni.cloud@gmail.com' . "\r\n" .
    'Reply-To: webmaster@example.com' . "\r\n" .
    'X-Mailer: PHP/' . phpversion();

$success =mail($to, $subject, $message, $headers);

if(!$success) {
   print_r(error_get_last());
}else{
	echo "sent";
}
?> 