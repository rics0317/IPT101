<?php

$hostName = "localhost:3307";
$dbUser = "root";
$dbPassword = "";
$dbName = "ipt101";
$conn = mysqli_connect($hostName, $dbUser, $dbPassword, $dbName);
if (!$conn) {
    die("Something went wrong;");
}

?>