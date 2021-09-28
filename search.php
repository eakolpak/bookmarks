<?php session_start();

if(isset($_SESSION['iduser']))
{
	//Страница для AJAX поиска
	$db = mysqli_connect("localhost","root","root","test");
	$SQL = "SELECT * FROM bookmarks WHERE adress LIKE '%".$_POST['name']."%' AND `visitor code` = ".$_SESSION['iduser'];
	$result = mysqli_query($db, $SQL);
	
		if(mysqli_num_rows($result) > 0)
		{	
			while( $row = mysqli_fetch_assoc($result) )
			{   
				$url = 'http://'.$row['adress'];
				$title = parse_url($url, PHP_URL_HOST);
				print 
				"
				<div class='m-2'>
				<div style = 'display: flex;  height: 50px; width: 260px; border-radius: 10px; box-shadow: 0 0 5px #7F7F7F; background: #D3D3D3;'>					
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
			print "<div class='text-center text-danger'><tr><td>Совпадений не найдено</td></tr></div>";
		}
}
?>  
	