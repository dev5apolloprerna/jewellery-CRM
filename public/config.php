<?php

ob_start();
session_start();
date_default_timezone_set("Asia/Calcutta");
$website_name = "MB Herbals";
$ProjectName = "MB Herbals";


$dbhost = "localhost";
$dbuser = "mbherbal_herbaluser";
$dbpass = "rO,V0Z3o5dI1";
$dbname = "mbherbal_storemb";
$web_url = 'https://www.mbherbals.com/';
$dbconn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname) or die('Could not connect: ' . mysqli_connect_error());

$cateperpaging = 50;

$mailHost = "mail.getdemo.in";
$mailUsername = "info@getdemo.in";
$mailPassword = "Info@123@";
$mailSMTPSecure = 'tls';
$mailFrom = "no-replay@getdemo.in";
$mailFromName = "MB Herbals";
$mailAddReplyTo = "no-replay@getdemo.in";
    
?>
 