<?php

// Проверка авторизации
function CheckAdmin()
{
	// Проверка логина
	if(isset($_SESSION['iduser']))
	{
		// Проверка пароля
		if(CheckAdminPassword())
		{?>
		<div class="accordion form3" id="accordionExample">
			<div class="card">
				<div class="card-header" id="headingOne">
					<h2 class="mb-0">
					<button class="btn btn-outline-info" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
					Доступ посетителей
					</button>
					</h2>
				</div>
				<div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionExample">
					<div class="card-body">
					<?php EditDBU(); ?>
					</div>
				</div>
			</div>
			<div class="card">
				<div class="card-header" id="headingTwo">
					<h2 class="mb-0">
					<button class="btn btn-outline-info" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
					Статистика посещений
					</button>
					</h2>
				</div>
				<div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionExample">
					<div class="card-body">
						<?php $file=file("../stat.log"); ?>
						<div class="container"
						<div class="col-12 d-flex justify-content-center">
						<table class='table table-bordered w-100 text-center'>
						<tr>
						<td><b>Время и дата</b></td>
						<td><b>Данные о посетителе</b></td>
						<td><b>Сервер</b></td>
						<td><b>Посещенный URL</b></td>
						<td><b>Порт сервера</b></td>
						</tr>
						</div>
						<?php
							$fsize=sizeof($file);
							// Вывод лога в обратном порядке (последняя запись сверху)
							for($si=sizeof($file)-1; $si+1>sizeof($file)-$fsize; $si--) 
							{
								// Разбиваем текущую строку с помощью разделителя "|"
								$string=explode("|",$file[$si]);
								$q1[$si]=$string[0]; // дата и время
								$q2[$si]=$string[1]; // имя 
								$q3[$si]=$string[2]; // ip 
								$q4[$si]=$string[3]; // адрес посещения
								$q5[$si]=$string[4]; // порт сервера
								print '<tr><td>'.$q1[$si].'</td>';
								print '<td>'.$q2[$si].'</td>';
								print '<td>'.$q3[$si].'</td>';
								print '<td>'.$q4[$si].'</td>';
								print '<td>'.$q5[$si].'</td></tr>';
							}
						?>	
						</table>
					</div>
				</div>
			</div>
		</div>	
		<?php
		}
		else
		{
			print "<br>Доступ запрещен";
			print "<br><a href='../exit.php'><br>Введите логин и пароль повторно</a>";
		}
    }
	else
	{
		print "<a href='../index.php'>Для доступа введите логин и пароль</a>";
	}
}

function CheckAdminPassword() 
{
	global $db;
    // Составляем строку запроса
    $SQL = "SELECT * FROM `visitors` WHERE `login` LIKE '".$_SESSION['login']."'";

	if ($result = mysqli_query($db, $SQL)) 
	{
		// Если нет пользователя с таким логином, то завершаем функцию
		if(mysqli_num_rows($result)== 0) 
		{
			print "<br>Нет такого администратора";
			return FALSE;
		}
		$row = mysqli_fetch_assoc($result); 
		// Если логин есть, то проверяем статус
		if($row['access'] < 10)
		{
			print "Нет прав для доступа<br>";
			return FALSE;
		}
		// Если логин и статус есть, то проверяем password

		if (password_verify($_SESSION['password'], $row['password']))
		{
			//print "Пароль администратора совпадает<br>";
			return TRUE;
		}
	}
	else
	{
		printf("Ошибка: %s\n", mysqli_error($db));
	}
    print "Нет такого пароля<br>";
    return FALSE;
}

// Вывод таблицы с функциями редактирования
function EditDBU()
{
	global $db;
	if ($result = mysqli_query($db, "SELECT * FROM visitors")) 
	{?>
	<div class="d-flex justify-content-center">
	<?php
		print "<table <table class='table table-striped table-bordered w-50'>";
		while ($row = mysqli_fetch_assoc($result)) 
		{
			print "<tr>"; 
			printf("<td>%s</td><td>%d</td><td>%s</td>", $row['login'], $row['access'], $row['registration']); 
			print "<td><a href='edit.php?id=".$row['visitor code']."'><i class='bi bi-pencil-square text-warning' style='font-size: 18px;'></i></a></td>";
			print "<td><a href='delete.php?id=".$row['visitor code']."'><i class='bi bi-trash text-danger' style='font-size: 18px;'></i></a></td>";
			print "</tr>"; 		
		}	 
		print "</table><br>";
	?>
	</div>
		<?php
	}
}

?>




