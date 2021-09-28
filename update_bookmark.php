<?php require_once "start_mysql.php";
StartDB();
	$id = $_POST['id'];
	$tab  = htmlspecialchars($_POST['tab']);
	$siteurl  = htmlspecialchars($_POST['siteurl']);
	$group = htmlspecialchars($_POST['group']);
	$SQL = "UPDATE bookmarks SET `bookmark`='$tab', `adress`='$siteurl' WHERE `code bookmarks`='$id'";

	if (!$result = mysqli_query($db, $SQL)) 
	{
		printf("Ошибка в запросе: %s\n", mysqli_error($db));
	}

	$SQL1 = "UPDATE groups SET `group`='$group' WHERE `group code`='$id'";

	if (!$result = mysqli_query($db, $SQL1)) 
	{
		printf("Ошибка в запросе: %s\n", mysqli_error($db));
	}
EndDB();
header("Location: index.php");	
