<?php session_start(); require_once "main.php";  require_once "start_mysql.php";
$url = htmlspecialchars($_POST['siteurl']);
// Удаляем протокол из adressа
$url = str_replace (['http://', 'https://'], '', $url); 
// Удаляем пробелы и слеш
$url = trim($url, ' /'); 
StartDB();
// Получение заголовка сайта
$tab = SiteTitle($url);
// Получение скриншота сайта
//$shot = SiteScreenshot($url);
// Код группы 

// Получение кода клиента
$client = $_SESSION['iduser'];

$SQL = "INSERT INTO bookmarks
					(`bookmark`, `adress`, `group code`, `visitor code`) 
			VALUES 	('$tab', '$url',  '0', '$client')";		
	
mysqli_query($db, $SQL);
EndDB();
header("Location: index.php");
