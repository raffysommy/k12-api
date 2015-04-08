<?php
	include_once "db.php";
	$user = $_POST['username'];
	$pass = $_POST['password'];
	$db = new mysqli($DBhost, $DBuser, $DBpass, $DBname);
	if (!$db) die("Errore connessione server.");
    $myQuery = "SELECT nome, cognome, scuola, permissions FROM utenti where Username='$user' and password='$pass';";
    $res = $db->query($myQuery) or die($db->err);
    $json = array();

    while($row=mysqli_fetch_assoc($res)){
    	$json[]=$row;
    }
    $res->close();
    $db->close();
    echo json_encode($json);

?>
