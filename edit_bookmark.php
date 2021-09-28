<?php $title = "Правка закладок"; require_once "header.php"; ?>

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
		$SQL = "SELECT * FROM bookmarks WHERE `code bookmarks`=".$id;

		if ($result_item = mysqli_query($db, $SQL)) 
		{
			$row = mysqli_fetch_assoc($result_item);
			$tab  = $row['bookmark'];
			$siteurl = $row['adress'];
			$shot = $row['screenshot'];
			$group = $row['group code'];
		}
		else
		{
			printf("Ошибка в запросе: %s\n", mysqli_error($db));
		}
		EndDB();
	?>	
	<div class="row">
		<div class="col-12 d-flex justify-content-center text-center text-warning">
			<form class="form3" action="update_bookmark.php" method="post">
				<?php			
					print "<label>Название закладки</label><div class = 'form-group'><input name='tab' class='form-control mb-3' value='".$row['bookmark']."'</div>";
					print "<label>Адрес закладки</label><div class = 'form-group'><input name='siteurl' class='form-control' value='".$row['adress']."'></div>";
					print "<input name='id' type='hidden' value='".$id."'>";
				?>
				<div class="btn-group mt-3" role="group">
					<button type="submit" class="btn btn-sm btn-warning">Изменить</button>
				</div>
			</form>
		</div>	
	</div>
</div>

<?php require_once "footer.php"; ?>

