<?php
$username = $_POST["name"];
$passwordins = $_POST["password"];
//$email = $_POST["email"];

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
?><br><?php

$sql = "SELECT id, username, password FROM users";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        if ($row["username"] == $username && $row["password"] == $passwordins) {
            //logged in!!!!!!
            echo "logged in!";
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
