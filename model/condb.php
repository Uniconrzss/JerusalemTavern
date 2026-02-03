<?php
function getDBSettings() {
	$dbsettingsFile = fopen(__DIR__."/dbsettings.json", "r");
	$fdata = fread($dbsettingsFile, filesize(__DIR__."/dbsettings.json"));
	return $fdata;
}

function conDB() {
	$dbData = json_decode(getDBSettings());
	$conn = new mysqli($dbData->host, $dbData->user, $dbData->pass, $dbData->db);
	if ($conn->connect_error)
	{
		$conn->close();
		die("Issue connecting to the database! Check config!");		
	}
	return $conn;
}
?>
