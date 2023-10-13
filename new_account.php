<?php
//data
$username = $_POST["name"];
$password = $_POST["password"];
$email = $_POST["email"];

//database_info
$login_host = "127.0.0.1";
$login_name = "root";
$login_password = "";
$login_dbname = "emila_baze";

//Connect
$conn = mysqli_connect($login_host, $login_name, $login_password, $login_dbname);

//Check Connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
echo "Connected";
$stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $username, $email, $password);

$stmt->execute();
$stmt->close();
$conn->close();
header("Location: index.html");
