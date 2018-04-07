<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
 require_once('wp-load.php');

$headers = 'From:' . "nandni.cloud@gmail.com";

if(wp_mail('nandni.cloud@gmail.com', 'test', 'muy test', $headers))
{
echo "sending mail test";
}
else
{
     echo "not";
}



?>