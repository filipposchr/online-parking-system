<?php

header("Content-Type: application/json; charset=UTF-8");
ini_set('display_errors', 'Off'); 

function errorHandler($errno, $errstr) {
	$error = true;
	$msg = "Αποτυχία φόρτωσης δεδομένων";
	$error_msg = $errstr;
	$data = null;
	$response = array(
		'error' => $error,
		'msg' => $msg,
		'error_msg' => $error_msg,		
		'data' => $data
	);
	$jdata = json_encode($response);
	echo $jdata;
	die();
}

// Set user-defined error handler function
set_error_handler("errorHandler");

$coords = array();
$mysqli = new mysqli("localhost", "root", "806175", "parking");
$sql = "SELECT `y`,`x` FROM `info`"; //sintetagmenes tis polis
$stmt = $mysqli->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();
$res = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

$city = $res[0];
$sql = "SELECT MAX(`tetragono_id`) FROM `polygon`"; //vriskoume to poligwno me to megalitero id gia na
//kseroume ton arithmo twn poligwnwn
$stmt = $mysqli->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();
$res = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

$num = $res[0]['MAX(`tetragono_id`)'];
$num = $num + 1;

$info[0] = $city; //sintetagmenes polis
$info[1] = $num; //arithmos poligwnwn

$numData = array( 'numOfPol' => $num ); //apothikeusi arithmo poligwnwn sto array numData

for($i=0; $i < $num; $i++) {
	$sql ="SELECT `Y`,`X`  FROM `polygon` WHERE `tetragono_id`=$i ";
	$stmt = $mysqli->prepare($sql);
	$stmt->execute();
	$result = $stmt->get_result();
	$pol = $result->fetch_all(MYSQLI_ASSOC);
	$coords[$i] = $pol;
}
$stmt->close();
$data[0] = $info;
$data[1] = $coords; //sintetagmenes poligwnou

$msg = "Success";
$response = array(
	'error' => false,
	'msg' => $msg,
	'error_msg' => "",		
	'data' => $data
);

mysqli_close($mysqli);

//apothikeusi arithmo poligwnwn sto general_info.json
$fp = fopen('general_info.json', 'w');
fwrite($fp, json_encode($numData));
fclose($fp);

$fp = fopen('polydata.json', 'w');
fwrite($fp, json_encode($data, JSON_PRETTY_PRINT));
fclose($fp);

$jdata = json_encode($response);

echo $jdata;
?>