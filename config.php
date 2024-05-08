<?php

// Include functions
include('functions.php');

// Add database login credentials below
// This file is added to every page to connect to the database

$mysql_server = ""; 							// Usually db or localhost
$mysql_username = "";    					// Your MySQL username
$mysql_password = "";      			    // Your MySQL Password
$mysql_database = "";     				// The name of your database

$con = new mysqli($mysql_server, $mysql_username, $mysql_password, $mysql_database);

if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

//Set the timezone
date_default_timezone_set('Australia/Melbourne');

//Start a SESSION to allow to store variables temporarily for the user
session_start();

?>

<html>
<head>
<title>Boomyville</title>
</head>

</html>
