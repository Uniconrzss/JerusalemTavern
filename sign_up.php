<html>
<head>
	<link rel="stylesheet" href="entrance.css">
	<title>The Jerusalem Tavern > Register</title>
	<link rel="icon" type="image/x-icon" href="/images/favicon.ico">
	<?php
		// Check if comes from register attempt with error.
		$errormsg = $_GET["msg"];
		if ($errormsg)
		{
			switch ($errormsg)
			{
				case "plen":
					echo ("<script>alert('Password length is under 9 charaters!');</script>");
					break;
				case "unam":
					echo ("<script>alert('Username is already taken!');</script>");
					break;
				case "bots":
					echo ("<script>alert('Bot detected!');</script>");
					break;
				default:
					echo ("<script>alert('Unknown error!');</script>");
					break;
			}
		}
	?>
</head>
<body>
<h1>Jerusalem Tavern</h1>
<p>Enter your details to register to the website.</p>
<form autocomplete="off" action="new_account.php" method="post">
	<label class="inputlabel" for="name">Username:</label>
	<input class="textinput" type="text" name="name" required><br>
	<label class="inputlabel" for="email">E-Mail:   </label>
	<input class="textinput" type="text" name="email"><br>
	<label class="inputlabel" for="password">Password:</label>
	<input class="textinput" type="password" minlength="9" name="password" required><br>
<input class="button" type="submit" value="Register">
	<input type="text" style="display: none;" name="botfield">
</form>

</body>
</html>
