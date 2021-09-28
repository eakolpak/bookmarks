<?php $title = "Правка папок"; require_once "header.php"; ?>


<div class="container">
	<a href = 'index.php'><i class='bi bi-box-arrow-left text-info' style="font-size: 30px;"></i></a>
<?php
	StartDB();
	// Получение списка групп
	$SQL = "SELECT * FROM groups";
	
	if (!$result = mysqli_query($db, $SQL)) 
	{
		printf("Ошибка в запросе: %s\n", mysqli_error($db));
	}

	// Получение записи
	$id = $_GET['id'];
	$SQL = "SELECT * FROM groups WHERE `group code`=".$id;

	if ($result_item = mysqli_query($db, $SQL)) 
	{
		$row = mysqli_fetch_assoc($result_item);
		$tab  = $row['group'];
		$group = $row['group code'];
	}
	else
	{
		printf("Ошибка в запросе: %s\n", mysqli_error($db));
	}

?>
<div class="row mt-5">
		<div class="col-12 d-flex justify-content-center  text-center text-warning">
			<form action="update_group.php" method="post">
				<?php			
					print "<label>Название папки</label><div class='form-group'><input name='tab' class='form-control mb-3' value='".$row['group']."'></div>";
					print "<input name='id' type='hidden' value='".$id."'>";
					mysqli_free_result($result);
				?>		
				<div class="btn-group" role="group">
					<button type="submit" class="btn btn-sm btn-warning">Изменить</button>
				</div>	
			</form>
		</div>	
	</div>
</div>

<?php require_once "footer.php"; ?>

