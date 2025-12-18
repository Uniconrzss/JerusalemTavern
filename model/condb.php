<?php
function getDBSettings() {
	$dbsettingsFile = fopen("dbsettings.json") or return "Failed to open file!";
	$fdata = fread($dbsettingsFile, filesize("dbsettings.json"));
	return $fdata;
}

function conDB() {
	$dbData = json_decode(getDBSettings());
	
	$conn = new mysqli($dbData["host"], $dbData["user"], $dbData["pass"], $dbData["db"]);

	if ($conn->connect_error)
	{
		$conn->close();
		die("Issue connecting to the database! Check config!");		
	}
	return $conn;
}
?>
