<?php
	$dbc = mysqli_connect('localhost', 'root', '806175', 'parking') or die('Αποτυχία διαγραφής δεδομένων');

	//Adiasma twn pinakwn apo tin vasi dedomenwn
	mysqli_query($dbc, 'TRUNCATE TABLE `centroids`');
	mysqli_query($dbc, 'TRUNCATE TABLE `kamp`');
	mysqli_query($dbc, 'TRUNCATE TABLE `polygon`');
	mysqli_query($dbc, 'TRUNCATE TABLE `pol_info`');
	mysqli_query($dbc, 'TRUNCATE TABLE `info`');
	mysqli_close($dbc);

	echo 'Επιτυχής διαγραφή βάσης δεδομένων';
	exit();
?>