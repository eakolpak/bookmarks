<?php
//$APIkey = 'bf9adf'; // Введите сюда API key с сайта https://www.screenshotmachine.com/

function InitDB()
{
	global $db;

	if (mysqli_query($db, "DROP TABLE IF EXISTS bookmarks;") === TRUE)
	{
		print "Таблица bookmarks удалена<br>";
	}
	else
	{
		printf("Ошибка: %s\n", mysqli_error($db));
	}
	
	if (mysqli_query($db, "DROP TABLE IF EXISTS groups;")  === TRUE)
	{
		print "Таблица groups удалена<br>";
	}
	else
	{
		printf("Ошибка: %s\n", mysqli_error($db));
	}
	
	
	$SQL = "CREATE TABLE bookmarks ( 
	`code bookmarks` INT NOT NULL  AUTO_INCREMENT PRIMARY KEY, 
	`bookmark` VARCHAR(255) NOT NULL, 
	`adress` VARCHAR(2048) NOT NULL, 
	`screenshot` VARCHAR(255) DEFAULT NULL,
	`visitor code` INT NOT NULL,
	`group code` INT NOT NULL
	);";

	if (mysqli_query($db, $SQL) === TRUE)
	{
		print "Таблица bookmarks создана<br>";
	}
	else
	{
		printf("Ошибка: %s\n", mysqli_error($db));
	}
	
	$SQL = "CREATE TABLE groups ( 
	`group code` INT NOT NULL  AUTO_INCREMENT PRIMARY KEY, 
	`group` VARCHAR(50) NOT NULL,
	`visitor code` INT NOT NULL)
	";
	
	if (mysqli_query($db, $SQL) === TRUE)
	{
		print "Таблица groups создана<br>";
	}
	else
	{
		printf("Ошибка создания таблицы 'groups': %s\n", mysqli_error($db));
	}
	
	// Добавление группы Общая
	 $SQL = "INSERT INTO groups (`group`, `visitor code` ) VALUES ('Общая', '1')";

	 if (mysqli_query($db, $SQL) === TRUE)
	 {
 	print "Запись 'Общая' в таблицу groups добавлена.<br>";
	 }
	 else
	{
	 	printf("Ошибка: %s\n", mysqli_error($db));
	}
	
	
	// Создание таблицы visitors
	if (mysqli_query($db, "DROP TABLE IF EXISTS visitors;") === TRUE)
	{
		print "Таблица visitors удалена<br>";
	}
	else
	{
		printf("Ошибка: %s\n", mysqli_error($db));
	}
	
	$SQL = "CREATE TABLE visitors 
	( 
	`visitor code` INT NOT NULL  AUTO_INCREMENT PRIMARY KEY, 
	`login` VARCHAR(50) NOT NULL, 
	`password` VARCHAR(255) NOT NULL,
	`access` int NOT NULL,
	`registration` TIMESTAMP NOT NULL
	);";

	if (mysqli_query($db, $SQL) === TRUE)
	{
		print "Таблица visitors создана<br>";
	}
	else
	{
		printf("Ошибка: %s\n", mysqli_error($db));
	}

	// Добавляем записи администратора
	$hash_pass1 = password_hash('admin100', PASSWORD_DEFAULT);
	$SQL = "INSERT INTO visitors (`login`, `password`, `access`) 
						VALUES 	('admin', '".$hash_pass1."', '10')				
		";
		
	if (mysqli_query($db, $SQL) === TRUE)
	{
		print "Запись администратора в таблицу visitors добавлена.<br>";
	}
	else
	{
		printf("Ошибка добавления записи администратора: %s\n", mysqli_error($db));
	}

	
}

function GetDB()
{
	global $db;
	$SQL = "
			SELECT bookmarks.`adress`, groups.`group`
			FROM bookmarks JOIN groups 
			ON bookmarks.`group code` = groups.`group code`";
	//print $SQL;
	if ($result = mysqli_query($db, $SQL)) 
	{
		//printf ("Число строк в запросе: %d<br>", mysqli_num_rows($result));
		print "<table border=1 cellpadding=5>"; 
		// Выборка результатов запроса 
		while( $row = mysqli_fetch_assoc($result) )
		{ 
			print "<tr>"; 
			printf("<td>%s</td><td>%s</td>", $row['group'], $row['adress'] ); 
			print "</tr>"; 
		} 
		print "</table>"; 
		mysqli_free_result($result);
	}
	else
	{
		printf("Ошибка в запросе: %s\n", mysqli_error($db));
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
			//print "<br>Нет такого администратора";
			return FALSE;
		}
		$row = mysqli_fetch_assoc($result); 
		// Если логин есть, то проверяем статус
		if($row['access'] < 10)
		{
			//print "Нет прав для доступа<br>";
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
   // print "Нет такого пароля<br>";
    return FALSE;
}

function CheckAdmin()
{
	// Проверка логина
	if(isset($_SESSION['iduser']))
	{
		// Проверка пароля
		if(CheckAdminPassword())
		{?>
			<a class="mr-2" href="./admin/index.php"><i class='bi bi-person text-info mr-1'style="font-size: 30px;"></i></a>
    	<?php
		}
	}
}

function ShowGroups()
{
	if(isset($_SESSION['iduser']))
	{ 

	global $db;
	$SQL = "SELECT * FROM groups WHERE `visitor code`= ".$_SESSION['iduser'];
	//print $SQL;
	if ($result = mysqli_query($db, $SQL)) 
	{	
		print 
		"	
		<div class='col-12 d-flex flex-wrap justify-content-center'>
			<h5 class='text-light'>Мои папки <span class='badge badge-light mr-2' style='font-size: 9px;'>".mysqli_num_rows($result)."</span></h5>
		</div>
		<div class='m-2'> 	
			<a  role='button' data-toggle='modal' data-target='#Modal_Group' 
			style = 'display: flex;  height: 80px; width: 120px; align-items: center; justify-content: center; border: solid 4px; border-color: #FFD700; border-radius: 7px;'> 
			<i class='bi bi-folder-plus mt-2' style = 'font-size: 60px; color: #FFD700'></i></a>			
		</div>
		";
	//Выборка результатов запроса	
		while( $row = mysqli_fetch_assoc($result))
		{ 	
			$title = htmlspecialchars($row['group']);
			if(mb_strlen($title) >= 25 ){
				$title = mb_substr($title,0,25,'UTF-8').'...';
			}
			print 
			"
			<div class='m-2'> 
				<div class='container-fluid text-dark' style = 'height: 80px; width: 120px; border-radius: 7px;  background: #FFD700;'>
					<div class = 'row'>
						<div class = 'col-8'><i class='bi bi-folder' style = 'font-size: 18px;'></i>
						</div>
						<div class= 'col-1'>
							<div class='dropleft'>
								<i class='bi bi-list' style='font-size: 15px;' data-toggle = 'dropdown'></i>
								<div class='dropdown-menu bg-dark'>
									<a class = 'dropdown-item text-warning' href='edit_group.php?id=".$row['group code']."' style = 'font-size: 12px;'><i class='bi bi-pencil text-warning mr-2'></i>Изменить</a>
									<a class = 'dropdown-item text-danger' href='delete_group.php?id=".$row['group code']."' style = 'font-size: 12px;'><i class='bi bi-trash text-danger mr-2'></i>Удалить</a>
								</div>	
							</div>
						</div>					
						<a  class = 'col-12 d-flex flex-wrap justify-content-center text-dark pt-1' href='view_group.php?id=".$row['group code']."' style = 'height: 60px; width: 120px; text-decoration: none;'> 
						<p style = 'font-size: 12px;'>".$title."</p></a>	
					</div>		
				</div>		
			</div>
			";			
		} 
		mysqli_free_result($result);
	}
	else
	{
		printf("Ошибка в запросе: %s\n", mysqli_error($db));
	}
} 
}


function AddDB()
{
	global $db;
	// Получение списка групп
	$SQL = "SELECT * FROM groups";
	
	if (!$result = mysqli_query($db, $SQL)) 
	{
		printf("Ошибка в запросе: %s\n", mysqli_error($db));
	}
	// Получение кода первого клиента
	$SQL = "SELECT * FROM visitors WHERE `login` LIKE '1'";
	
	if (!$result2 = mysqli_query($db, $SQL)) 
	{
		printf("Ошибка в запросе: %s\n", mysqli_error($db));
	}
	$row = mysqli_fetch_assoc($result2);
	$client = $row['visitor code'];
	mysqli_free_result($result2);

?>
<form action="add.php" method="post">
	    <table>
        <tr><td>Закладка</td><td><input name="tab" maxlength=60 size=100></td></tr>
        <tr><td>Адрес</td><td><input name="siteurl" maxlength=2048 size=100></td></tr>
        <tr><td>Группа</td><td>
        <select name="group" size="1">
<?php			
		// Цикл по группам 
		while( $row = mysqli_fetch_assoc($result) )	
		{		
			print "<option selected value='".$row['group code']."'>";
			print $row['group']."</option>";
		}
		mysqli_free_result($result);
		print "<input name='client' type='hidden' value='".$client."'>";
?>		
		</select></td>        
		</tr>
        <tr><td colspan=2><input type="submit" value="Добавить"></td></tr>
    </table>
</form>
	
<?php	
	
}


//Сохраняет скриншот в файл и возвращает его имя
// function SiteScreenshot($url)
// {
// 	global $APIkey;
// 	// Удаляем протокол из адреса
// 	$url = str_replace (['http://', 'https://'], '', $url); 
// 	// Удаляем пробелы и слеш
// 	$url = trim($url, ' /'); 
// 	// К имени файла добавляем visitor code
// 	$file = $url.$_SESSION['iduser'];
// 	// Получаем хэш для имени файла
// 	$filename = md5($file) . ".png";
// 	// Папка, где хранятся скриншоты сайтов
// 	$dir = "pics/";
// 	// Если скриншот существует, то выдаем его на экран
// 	if (is_file($dir.$filename)) 
// 	{
// 		return $dir.$filename;
// 	}
// 	// Иначе создаем скриншот
// 	else 
// 	{
// 		// Запрос для скриншота
// 		$geturl = "https://api.screenshotmachine.com?key=" . $APIkey . "&dimension=60x45&format=png&url=" . $url;
// 		// Получаем скриншот
// 		$screenshot = file_get_contents($geturl);
// 		// Создаем файл
// 		$openfile = fopen($dir.$filename, "w+");
// 		// Сохраняем изображение
// 		$write = fwrite($openfile, $screenshot);
// 		return $dir.$filename;
// 	}
// }


// Возвращает заголовок сайта
function SiteTitle($url)
{  
	
		// Если нет протокола в адресе добавляем его
		if ((strpos($url, 'http://') === false) && (strpos($url, 'https://') === false)) 
		{
			$url = 'http://' . $url;
		}
		$fp = file_get_contents($url);
		if (!$fp) 
		{
			return null;
		}
		$res = preg_match("/<title>(.*)<\/title>/siU", $fp, $title_matches);
		if (!$res) 
		{
			return null;
		}
		// Чистка заголовка
		$title = preg_replace('/\s+/', ' ', $title_matches[1]);
		$title = trim($title);
		return $title;
	
}

// Проверка авторизации
function CheckLogin()
{
	// Если авторизация есть
	if(isset($_SESSION['iduser']))
	{
		
		return;
	}
	// Проверка логина
	if(isset($_POST['userlogin']))
	{
		$_SESSION['login'] = $_POST['userlogin'];
		$_SESSION['password'] = $_POST['userpass'];
		//print "<br>Логин ".$_SESSION['login'];
		//print "<br>Пароль ".$_SESSION['password'];
		// Проверка пароля
		if(CheckPassword())
		{
			
		}
		else
		{
			print "<br>Доступ запрещен";
			print "<a href='index.php'><br>Введите логин и пароль повторно</a>";
		}
    }
	else
	{
		ShowLogin();
	}
}

function CheckPassword() 
{
	global $db;
    // Составляем строку запроса
    $SQL = "SELECT * FROM `visitors` WHERE `login` LIKE '".$_SESSION['login']."'";

	if ($result = mysqli_query($db, $SQL)) 
	{
		// Если нет пользователя с таким логином, то завершаем функцию
		if(mysqli_num_rows($result)== 0) 
		{
			print "<br>Пара логин-пароль не совпадает";
			return FALSE;
		}
		// Если логин есть, то проверяем пароль
		$row = mysqli_fetch_assoc($result); 
		if (password_verify($_SESSION['password'], $row['password']))
		{
			//print "<br>Пароль совпадает<br>";
			$_SESSION['iduser']=$row['visitor code'];
			return TRUE;
		}
	}
	else
	{
		printf("Ошибка: %s\n", mysqli_error($db));
	}
    unset($_SESSION['user']);
    print "Пара логин-пароль не совпадает<br>";
    return FALSE;
}

// Функция регистрации пользователя
function RegUser() 
{
	global $db;
	// Проверка данных
	if(!$_POST['user_login']) 
	{
		print "<br>Не указан логин";
		return FALSE;
	} 
	elseif(!$_POST['user_password']) 
	{
		print "<br>Не указан пароль";
		return FALSE;
	}
	
	// Проверяем не зарегистрирован ли уже пользователь
	$SQL = "SELECT `login` FROM `visitors` WHERE `login` LIKE '".$_POST['user_login']. "'";

	// Делаем запрос к базе
	if ($result = mysqli_query($db, $SQL)) 
	{
		// Если есть пользователь с таким логином, то завершаем функцию
		if(mysqli_num_rows($result) > 0) 
		{
			print "<br>Пользователь с указанным логином уже зарегистрирован.";
			return FALSE;
		}
	}
	else
	{
		printf("Ошибка: %s\n", mysqli_error($db));
	} 
	// Если такого пользователя нет, регистрируем его
	$hash_pass = password_hash($_POST['user_password'], PASSWORD_DEFAULT);
	$SQL = "INSERT INTO `visitors` 
			(`login`,`password`,`access`) VALUES 
			('".$_POST['user_login']. "','".$hash_pass. "', '1')";

	if ($result = mysqli_query($db, $SQL))
	{
		//print "<br>Пользователь зарегистрирован";
	}
	else
	{
		printf("Ошибка: %s\n", mysqli_error($db));
		return FALSE;
	}
	
	return TRUE;
}


function ShowLogin()
{
	?>
	<div class="row">
		<div class="col-12 d-flex justify-content-center mt-5">
			<form action="index.php" class="form" method="POST">			
				<div class = "form-group">
					<input  type="text" name="userlogin" class="form-control" placeholder="Логин">
				</div>
				<div class = "form-group">
					<input  type="password" name="userpass" class="form-control" placeholder="Пароль">
				</div>
				<div class = "form-group">
					<input type="submit" name = "login" class="btn btn-sm btn-outline-danger button2" value="Войти"></input>
				</div>
				<p> Еще не зарегистрированы?</p> 
				<button type="button" class="btn btn-sm btn-outline-warning" data-toggle="modal" data-target="#Modal_Reg">
  					Регистрация
				</button>
				<!-- <a href = "register.php">Регистрация</a> -->
			</form>
		</div>
	</div>
	<!-- Modal -->
	<div class="modal fade" id="Modal_Reg" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">Регистрация</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">				
					<div class="row">
						<div class="col-12 d-flex justify-content-center mt-2">
							<form action="register.php" class="form" method="POST">			
								<div class = "form-group">
									<input  type="text" name="user_login" class="form-control" placeholder="Логин">
								</div>
								<div class = "form-group">
									<input  type="password" name="user_password" class="form-control" placeholder="Пароль">
								</div>
								<div class = "form-group">
									<input  type="password" name="password_again" class="form-control" placeholder="Повторите пароль">
								</div>
								<div class = "form-group">
									<input type="submit" name = "register" class="btn btn-danger button2" value="Зарегистрироваться"></input>
								</div>
							</form>
						</div>
					</div>					
				</div>
			</div>
		</div>
	</div>	
	<?php
}

function ViewBM() 
{
	global $db;
	$groupCode = $_GET['id'];
	$SQL = "SELECT * FROM bookmarks WHERE `visitor code` = " . $_SESSION['iduser'] . " AND `group code` = " . $groupCode;
	if ($result = mysqli_query($db, $SQL)) 
		{  
				while( $row = mysqli_fetch_assoc($result) )
				{ 
					$url = 'http://'.$row['adress'];
					$title = htmlspecialchars($row['bookmark']);
					if(mb_strlen($title) >= 50 ){
						$title = mb_substr($title,0,50,'UTF-8').'...';
					}		
					print 
					"
					<div class='m-2'>
						<div style = 'display: flex;  height: 50px; width: 260px; border-radius: 7px; background: #D3D3D3;'>					
							<a class = 'mr-auto  text-dark' href='".$url."'  
							style = 'display: flex; height: 50px; width: 220px; font-size: 12px; align-items: center; justify-content: start;  text-decoration: none'>
							<i class='bi bi-bookmark text-primary mr-3 ml-2' style = 'font-size: 20px;'></i>
							<div>".$title."</div></a>
							<div class='dropleft' style = 'display: flex; height: 50px; width: 30px;   align-items: center; justify-content: center;'>
								<i class='bi bi-list text-dark' style='font-size: 15px;' data-toggle = 'dropdown'></i>
								<div class='dropdown-menu bg-dark'>
									<a class = 'dropdown-item text-warning' href='edit_bookmark.php?id=".$row['code bookmarks']."' style = 'font-size: 12px;'><i class='bi bi-pencil text-warning mr-2'></i>Изменить</a>
									<a class = 'dropdown-item text-danger' href='delete_bookmark.php?id=".$row['code bookmarks']."' style = 'font-size: 12px;'><i class='bi bi-trash text-danger mr-2'></i>Удалить</a>
								</div>	
							</div>
						</div>		
					</div>	
					";
			} 
			mysqli_free_result($result);
		}
		else
		{
			printf("Ошибка в запросе: %s\n", mysqli_error($db));
		}
}
function CountBM()
{
	if(isset($_SESSION['iduser']))
	{ 
		global $db;
		$groupCode = $_GET['id'];
		$SQL = "SELECT `bookmark` FROM bookmarks WHERE `visitor code` = ".$_SESSION['iduser'] . " AND `group code` = " . $groupCode; 
		//print $SQL;
		if ($result = mysqli_query($db, $SQL)) 
		{			
			print " <span class='badge badge-light mr-2 mt-1' style='font-size: 9px;'>".mysqli_num_rows($result)."</span>";			
		} 
	} 
}
function NameGroup ()
{
	global $db;
	$groupCode = $_GET['id'];					
	// Получение списка групп
	$SQL = "SELECT * FROM groups WHERE `visitor code` = ".$_SESSION['iduser'] . " AND `group code` = " . $groupCode;	
	if ($result = mysqli_query($db, $SQL)) 									
	// Цикл по группам 
	while( $row = mysqli_fetch_assoc($result) )	
	{		
		print $row['group'];
	}

	mysqli_free_result($result);
}
function SelectGroup ()
{
	global $db;
	$groupCode = $_GET['id'];					
	// Получение списка групп
	$SQL = "SELECT * FROM groups WHERE `visitor code` = ".$_SESSION['iduser'] . " AND `group code` = " . $groupCode;	
	if (!$result = mysqli_query($db, $SQL)) 
	{
		printf("Ошибка в запросе: %s\n", mysqli_error($db));
	}		
	print "<select class='form-control mb-4' name='group'>";										
	// Цикл по группам 
	while( $row = mysqli_fetch_assoc($result) )	
	{		
		print "<option value='".$row['group code']."'>".$row['group']."</option>";
	}
	print "</select>";
	mysqli_free_result($result);
}




function ViewBM1() 
{
	if(isset($_SESSION['iduser']))
	{
	global $db;
	$SQL = "SELECT * FROM bookmarks WHERE `visitor code` = " . $_SESSION['iduser']. " AND `group code` = 0";
	if ($result = mysqli_query($db, $SQL)) 
		{  
			print 
			"	
			<div class='col-12 d-flex flex-wrap justify-content-center'>
				<h5 class='text-light'>Мои закладки <span class='badge badge-light mr-2' style='font-size: 9px;'>".mysqli_num_rows($result)."</span></h5>
			</div>
			";
			while( $row = mysqli_fetch_assoc($result) )
			{ 
				$url = 'http://'.$row['adress'];
				$title = htmlspecialchars($row['bookmark']);
				if(mb_strlen($title) >= 50 ){
					$title = mb_substr($title,0,50,'UTF-8').'...';
				}
				print 
				"
				<div class='m-2'>
					<div style = 'display: flex;  height: 50px; width: 257px; border-radius: 7px; background: #D3D3D3;'>					
						<a class = 'mr-auto  text-dark' href='".$url."'  
						style = 'display: flex; height: 50px; width: 220px; font-size: 12px; align-items: center; justify-content: start;  text-decoration: none'>
						<i class='bi bi-bookmark text-primary mr-3 ml-2' style = 'font-size: 20px;'></i>
						<div class 'truncate'>".$title."</div></a>
						<div class='dropleft' style = 'display: flex; height: 50px; width: 30px;   align-items: center; justify-content: center;'>
							<i class='bi bi-list text-dark' style='font-size: 15px;' data-toggle = 'dropdown'></i>
							<div class='dropdown-menu bg-dark'>
								<a class = 'dropdown-item text-warning' href='edit_bookmark.php?id=".$row['code bookmarks']."' style = 'font-size: 12px;'><i class='bi bi-pencil text-warning mr-2'></i>Изменить</a>
								<a class = 'dropdown-item text-danger' href='delete_bookmark.php?id=".$row['code bookmarks']."' style = 'font-size: 12px;'><i class='bi bi-trash text-danger mr-2'></i>Удалить</a>
							</div>	
						</div>
					</div>		
				</div>	
				";	
			} 
			mysqli_free_result($result);
		}
		else
		{
			printf("Ошибка в запросе: %s\n", mysqli_error($db));
		}
	}
}
 
function ViewLogin ()
{
	if(isset($_SESSION['iduser']))
	{ 
	global $db;
	$SQL = "SELECT `login` FROM visitors WHERE `visitor code` = " . $_SESSION['iduser'];
	if ($result = mysqli_query($db, $SQL)) 
		{
			while( $row = mysqli_fetch_assoc($result) )
			{ 
				print $row['login'];
			}
			mysqli_free_result($result);
		}
	}
}
//<img class = 'mr-2' src='".$row['screenshot']."' style = 'height: 40px; width: 60px; border-radius: 10px; margin-left: 2px;'>
