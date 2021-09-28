<?php $title = "Добавление bookmarks"; require_once "header.php"; ?>

<div id="wrapper">
<div id="header">
	<h2>Добавление bookmarks</h2>
</div> 

<div id="content">
<?php


	$tab  = htmlspecialchars($_POST['tab']);
	$siteurl = htmlspecialchars($_POST['siteurl']);
	$group = $_POST['group'];
	$client = $_POST['client'];
	
	print "bookmark - $tab<br>";
	print "adress - $siteurl<br>";	
	print "group code - $group<br>";	
	print "visitor code - $client<br>";	
	
	StartDB();

	$SQL = "INSERT INTO bookmarks
					(`bookmark`, `adress`, `group code`, `visitor code`) 
			VALUES 	('$tab', '$siteurl', '$group', '$client')";		
	print $SQL."<br>";
	if (mysqli_query($db, $SQL) === TRUE)
	{
		print "Запись в таблицу 'bookmarks' добавлена.<br>";
	}
	else
	{
		printf("Ошибка добавления записи: %s\n", mysqli_error($db));
	}
	print '<a href="edit_table.php">Вернуться к таблице</a>';
	
	EndDB();
?>
	
</div>
<div id="footer">
</div>

</div>

<?php require_once "footer.php"; ?>
