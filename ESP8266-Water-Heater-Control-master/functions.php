<?php
include_once "init.php";
date_default_timezone_set('Asia/Jerusalem');

function startConnection() {
	$servername = "localhost";
	$username = USERNAME;
	$password = PWD_DB;
	$dbname = NAME_DB;

	// Create connection
	$connTMP = new mysqli($servername, $username, $password, $dbname);
	// Check connection
	if ($connTMP->connect_error) {
		die("Connection failed: " . $connTMP->connect_error);
	}

	return $connTMP;
}



function deleteTable($conn) {
	$sql = "DROP TABLE ".NAME_DB_TOUSE."";
	return $conn->query($sql);
}

function createTable($conn) {
	$sql = "CREATE TABLE IF NOT EXISTS ".NAME_DB_TOUSE." (
	id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
	name VARCHAR(30) NOT NULL,
	toRepeat VARCHAR(30) NOT NULL,
	startTime TIMESTAMP,
	endTime TIMESTAMP
	)";
	return $conn->query($sql);
}

function getTable($conn) {
	$sql = "SELECT id, name, toRepeat, startTime,endTime FROM ".NAME_DB_TOUSE." ORDER BY startTime";
	return $conn->query($sql);

}

function EraseQuery($conn,$id){
	$sql = "DELETE FROM ".NAME_DB_TOUSE." WHERE id=". $id;
	return ($conn->query($sql));
}



function handleDailyQuery($conn,$row) {

	$timeGap = (strtotime($row["endTime"]) - strtotime(date("Y-m-d H:i:s")) < 0);
	if ($timeGap) {
		echo $row["toRepeat"]." <br>";
		if ($row["toRepeat"] == "Daily") {
			$newTimeStart = date('Y-m-d H:i', strtotime('+'. 1 .' days',strtotime($row["startTime"])));
			$newTimeEnd = date('Y-m-d H:i', strtotime('+'. 1 .' days',strtotime($row["endTime"])));
			$sql = "UPDATE ".NAME_DB_TOUSE." SET startTime='".$newTimeStart."',endTime='".$newTimeEnd."' WHERE id=".$row["id"];
			if ($conn->query($sql) === TRUE) {
				return TRUE;
			} else {
				EraseQuery($conn,$row["id"]);
				return FALSE;
			}
		}
		else {
			EraseQuery($conn,$row["id"]);
			return FALSE;
		}
		
	}
	return TRUE; 	
}

?>