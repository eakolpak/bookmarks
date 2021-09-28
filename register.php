<?php session_start(); $title = "Вход в систему"; require_once "header.php";  ?>
<div class="container">
<div class="row">
		<div class="col-12  mt-5">
	<dialog open class="text-center">	
		<?php
		// Если была нажата кнопка регистрации
		if(isset($_POST['register'])) 
		{
			// Проверяем совпадение паролей
			if ($_POST['user_password'] === $_POST['password_again']) 
			{
				// Регистрация пользователя
				StartDB();
				$res = RegUser();
				EndDB();
				
				if($res)
				{
					print "<br>Вы успешно зарегистрировались в системе."; 
					print "<br>Сейчас вы будете переадресованы к странице авторизации."; 
					print "<br>Если это не произошло, перейдите на неё по <a href='index.php'>прямой ссылке</a>.</p>";
					header('Refresh: 3; URL = index.php');
				}
				else
				{
					print "<br>Во время регистрации произошли ошибки."; 
				}		
			}
			else
			{
				print "<br>Введенные пароли не совпадают.";
			}	
		}
		?>
			<br><br>
			<a href="index.php">Вернуться на главную</a>	
	</dialog>
</div>
</div>
</div>
 <?php require_once "footer.php";  ?>
