<?php session_start(); require_once "main.php"; require_once "start_mysql.php";

$group = htmlspecialchars($_POST['group']);

StartDB();
// Получение кода клиента
$client = $_SESSION['iduser'];
$SQL = "INSERT INTO groups
					(`group`, `visitor code`) 
			VALUES 	('$group', '$client')";		
mysqli_query($db, $SQL);
EndDB();
header("Location: index.php");
