<?php
$file = 'stat.log';  // файл для записи истории посещения сайта
$size_max=100;    	// ограничение размера log-файла 

date_default_timezone_set("Europe/Moscow");
$date = date("H:i:s d.m.Y");
$agent = $_SERVER['HTTP_USER_AGENT'];
$server = $_SERVER['SERVER_NAME'];
$home = $_SERVER['REQUEST_URI'];
$port = $_SERVER['SERVER_PORT'];
 
$lines="";
if(file_exists ($file))
{
	$lines = file($file);	
}	
	
// Обрезаем записи, если больше максимума
while(count($lines) > $size_max) 
{
	array_shift($lines);
}
$lines[] = $date."|".$agent."|".$server."|".$home."|".$port."|\r\n";
file_put_contents($file, $lines);
?>
