<?php
// DB MODEL SYSTEM
require "model/condb.php";

$conn = connDB();

// FORM DATA
$username = $_POST["name"];
$password = $_POST["password"];
$email = $_POST["email"];


echo "Connected";
$stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $username, $email, $password);

$stmt->execute();
$stmt->close();
$conn->close();
header("Location: index.html");
