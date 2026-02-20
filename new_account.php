<?php
// DB MODEL SYSTEM
require 'model/condb.php';
$conn = conDB();

// FORM DATA
$username = $_POST["name"];
$password = password_hash($_POST["password"], PASSWORD_DEFAULT);
$email = $_POST["email"];

// # VALIDATION
$valid = 0;

// CHECK IF PASSWORD IS LONG ENOUGH
if (strlen($_POST["password"]) > 8)
{
	echo("<br>Password is long enough!<br>");
	// CHECK IF BOTFIELD IS FILLED OUT
	if (!$_POST["botfield"])
	{
		echo("<br>Botfield isnt filled!<br>");
		// CHECK IF USER WITH NAME EXISTS
		$query = "SELECT username FROM users WHERE username = (?)";
		$stmt = $conn->prepare($query);
		$stmt->bind_param("s", $username);
		echo("<br>Paramaters bound!<br>");
		$stmt->execute();
		$stmt->bind_result($unames);
		$stmt->fetch();
		echo("<br>Result fetched!<br>");
		$stmt->close();
		if (!$unames)
		{
			// REGISTER ATTEMPT VALIDATED!
			$valid = 1;
		}
		else
		{
			echo("<br>User with the name exists!<br><script>var message = 'User with the name already exists!';</script>");
			$errormsg = "unam";
		}
	}
	else
	{
		echo("<br>Botfield is filled!<br><script>var message = 'You are a bot!';</script>");
		$errormsg = "bots";
	}
}
else
{
	echo("<br>Length of password is too short!<br><script>var message = 'Password length is too short!';</script>");
	$errormsg = "plen";
}

if ($valid != 1)
{
	$conn->close();

	// DEBUG
	echo("<script>alert(message);</script>");
	header("Location: sign_up.php?msg=".$errormsg);
}


$stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $username, $email, $password);


$stmt->execute();
$stmt->close();

$conn->close();
header("Location: index.html");
