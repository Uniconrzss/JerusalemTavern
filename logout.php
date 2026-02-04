<?php
if (isset($_COOKIE["u_name"])) {
	unset($_COOKIE["u_name"]);
	setcookie("u_name", "", time() - 3600, "/");
}
setcookie("u_name", "", time() - 3600, "mainpake.php");
echo($_COOKIE["u_name"]);
header("Location: index.html");
?>
