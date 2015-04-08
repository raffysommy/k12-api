<?php
	include_once 'db.php';
	$db = new mysqli($DBhost, $DBuser, $DBpass, $DBname);
	if (!$db) die("Errore connessione server.");

    $myQuery = "SELECT * FROM domande ORDER BY RAND() LIMIT 1";
    $res = $db->query($myQuery) or die($db->err);
    $json = array();

    while($row=mysqli_fetch_assoc($res)){
    	$json[]=$row;
    }
    $res->close();
    $db->close();
    echo json_encode($json);
?>