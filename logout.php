<?php
require 'model/condb.php';

if (isset($_COOKIE["u_cookie"])) {
	// Remove session from DB
	$conn = conDB();

	$stmt = $conn->prepare("DELETE FROM sessions WHERE cookie = ?");
	$stmt->bind_param("s", $_COOKIE["u_cookie"]);
	$stmt->execute();
	$stmt->close();

	unset($_COOKIE["u_cookie"]);
	setcookie("u_cookie", "", time() - 3600, "/");
}
setcookie("u_cookie", "", time() - 3600, "mainpake.php");
echo($_COOKIE["u_cookie"]);
header("Location: index.html");
?>
