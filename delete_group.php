<?php  require_once "start_mysql.php";
	
	StartDB();
	$id = $_GET['id'];
	$SQL = "DELETE FROM groups WHERE `group code`='$id'";
	print $SQL;
	if (!$result = mysqli_query($db, $SQL)) 
	{
		printf("Ошибка в запросе: %s\n", mysqli_error($db));
	}
	EndDB();
	header("Location: ".$_SERVER['HTTP_REFERER']);
