<?php
// DB MODEL SYSTEM
require 'model/condb.php';
//echo("Added requirement!");
$conn = conDB();
//echo("Connected!");
// FORM DATA
$username = $_POST["name"];
$password = password_hash($_POST["password"], PASSWORD_DEFAULT);
$email = $_POST["email"];


echo "Connected";
echo("<br>Password: $password <br>");
$stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $username, $email, $password);



echo("<br>Post data: ");

//print_r($_POST);
//echo("<br>");

$stmt->execute();
//echo("post-execute");
$stmt->close();

$conn->close();
header("Location: index.html");
