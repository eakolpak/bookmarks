<?php require_once "start_mysql.php";
StartDB();
	$id = $_POST['id'];
	$tab  = htmlspecialchars($_POST['tab']);
	$SQL = "UPDATE groups SET `group`='$tab' WHERE `group code`='$id'";

	if (!$result = mysqli_query($db, $SQL)) 
	{
		printf("Ошибка в запросе: %s\n", mysqli_error($db));
	}
EndDB();
header("Location: index.php");	
