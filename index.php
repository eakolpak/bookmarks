<?php session_start(); $title = "coursework3"; require_once "header.php"; require_once "admin/stat.php"; StartDB();?>

<nav class="navbar navbar-expand-lg navbar-light fixed-top" style="background-color: #121212;">
	<button type="button" class="btn text-light  mt-1" data-toggle="dropdown">
	<i class='bi bi-person mr-2' style="font-size: 20px;"></i>
		<?php  
			ViewLogin ();
		 ?>
	</button>
	<div class='dropdown-menu  bg-dark'>
		<a id = "dark_theme" class="dropdown-item text-light" style="display: block; font-size: 15px;"><i class='bi bi-brightness-high-fill mr-2'></i>Тёмная тема</a>
		<a id = "light_theme" class="dropdown-item text-secondary" style = "display: none; font-size: 15px;"><i class='bi bi-brightness-high mr-2'></i>Светлая тема</a> 
		<!-- <a class="dropdown-item text-light" href="./admin/index.php" style="font-size: 15px;"><i class='bi bi-person mr-2'></i>Администратор</a> -->
		<div class="dropdown-divider text-danger"></div>
		<a class="dropdown-item text-danger" href="exit.php" style="font-size: 15px;"><i class='bi bi-box-arrow-right mr-2'></i>Выйти</a>
	</div>		
	<form class="d-flex ml-auto" action = "#" method="post" >
		<input class="form-control  mr-2 mt-1" type="text" name ="search" id="search" placeholder="Искать закладки" aria-label="Поиск" style = 'display: none;'>	
	</form>
	<a id = "close_search" class="mr-2" style = 'display: none;'><i class='bi bi-x-circle text-light' style="font-size: 20px;"></i></a>
	<a id = "open_search" class="mr-2"><i class='bi bi-search text-light'style="font-size: 20px;"></i></a>
</nav>
<div class="container main">
	<div class="row showsites">
		<div class="col-12 d-flex flex-wrap justify-content-center text-center">
			<?php	
				//InitDB(); // Первоначальное создание таблиц		
				CheckLogin();			 
			?>
		</div>
	</div>	
	<div class="row showsites mb-3">
		<div class="col-12 d-flex flex-wrap justify-content-center">
			<?php	
				ShowGroups();					 
			?>
		</div>
	</div>
	<div class="row showsites">
		<div class="col-12 d-flex flex-wrap justify-content-center">
			<?php	
				ViewBM1();						 
			?>
		</div>
	</div>
	<div class="row">
		<div class="col-12 d-flex flex-wrap justify-content-center">
			<div class="output"></div>
		</div>
	</div>
</div>
<?php	 
if(isset($_SESSION['iduser']))
{?>
<!-- Модальное окно добавления новой папки-->
	<div class="modal fade" id="Modal_Group" role="dialog" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content bg-dark">
				<div class="modal-body">				
					<div class="row text-center">
						<div class="col-12 d-flex justify-content-center mt-2">
							<div class="accordion form3">
								<div class="card-body">
									<form action="addgroup.php" class="" method="POST">			
										<div class = "form-group">
											<input  type="text" name="group"  class="form-control" placeholder="Введите название папки" required>
										</div>
										<button class="btn btn-sm btn-outline-warning" type="submit">Добавить</button>
									</form>
								</div>
							</div>	
						</div>	
					</div>
				</div>
			</div>
		</div>	
	</div>
<?php	
}
if(isset($_SESSION['iduser']))
{?>
	<!-- Модальное окно добавления новой закладки-->
	<div class="modal fade" id="Modal_BM_Main"  role="dialog" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content bg-dark">
				<div class="modal-body">				
					<div class="row text-center">
						<div class="col-12 d-flex justify-content-center mt-2">
							<div class="accordion form3">
								<div class="card-body bg-dark">
									<form action="addsite1.php" class="" method="POST">			
										<input  type="text" name="siteurl"  class="form-control mb-4" placeholder="Введите адрес сайта" required>
								 		<button class="btn btn-sm btn-outline-primary mb-4" type="submit">Добавить</button>
									</form>
								</div>
							</div>
						</div>
					</div>						
				</div>
			</div>
		</div>
	</div>
	<?php
}?>
	<div class="container">
		<div class="row showsites">
			<div class="col-12 d-flex flex-wrap justify-content-end ">
			<a  role='button' data-toggle='modal' data-target='#Modal_BM_Main'> 
			<i class='bi bi-plus-circle-fill plus text-light' style = 'font-size: 40px;'></i></a>	
			</div>
		</div>
	</div>

<script type="text/javascript">
//Живой поиск закладок
$(document).ready(function()
{
	$("#search").keyup(function ()
	{
		let txt = $(this).val();
		if(txt != '')
		{
			$(".showsites").hide();	
			$.ajax(
			{
				type:'POST',
				url:'search.php',
				data:
				{
				name:$("#search").val(),	
				},			
				success:function(data)
				{
				$(".output").html(data);
				}
				});	
		}
		else
		{
			$(".output").html('');
			$(".showsites").show();
		}
	});
});

//Gоявление и скрытие формы поиска
$("#open_search").click(function () 
{
	$("#search, #close_search").css("display", "block");
	$("#open_search").css("display", "none");
});
$("#close_search").click(function () 
{
	$("#search, #close_search").css("display", "none");
	$("#open_search").css("display", "block");
});
$("#dark_theme").click(function () 
{
	$("body").css("background", "#121212");
	$("#light_theme").css("display", "block");
	$("#dark_theme").css("display", "none");
});
//Светлая и темная тема
// $("#light_theme").click(function () 
// {
// 	$("body").css("background", "#FFFFFF");
// 	$("#dark_theme").css("display", "block");
// 	$("#light_theme").css("display", "none");
// });
$(document).ready(function()
 {	

});
function line1() {
	 	
		 draw = SVG('drawing1').size(150, 20);	
		 line = draw.line(10, 0, 10, 0);
		 line.stroke({ color: 'red', width: 3, linecap: 'round' });
	 }
	 
</script>


<?php require_once "footer.php"; EndDB();?>
