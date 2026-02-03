<?php
require 'model/condb.php';

$username = $_POST["name"];
$passwordins = $_POST["password"];
//$email = $_POST["email"];

$connection = conDB();
echo "Connected";

?><br><?php

$sql = "SELECT id, username, password FROM users";
$result = $connection->query($sql);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
	// echo("<br> GIVEN HASH:".password_hash($passwordins, PASSWORD_DEFAULT)."<br>ROW HASH:".$row['password']."<br>");    
	if ($row["username"] == $username && password_verify($passwordins, $row["password"])) {
            //logged in!!!!!!
            // echo "logged in!";
	    
	    // OLD COOKIE SYSTEM
	    // RENEW WITH SESSIONS TABLE!!
	    setcookie("u_name", $username, time() + (86400 + 30), "mainpage.php");
	    
	    
	    header("Location: mainpage.php"); //Send to webpage
            die();
        }
    }
} else {
    //couldnt log in
    echo "0 Accounts? Bug";
    die();

}
header("Location: login.html");
die();
