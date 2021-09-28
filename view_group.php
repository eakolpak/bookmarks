<?php session_start(); $title = "coursework3"; require_once "header.php";?>

<nav class="navbar navbar-expand-lg navbar-light fixed-top"  style = "background-color: #121212;">
	<a class="mr-auto ml-2 mt-1" href="index.php"><i class='bi bi-arrow-left-square text-light' style="font-size: 30px;"></i></a>		
</nav>

<div class="container main">
	<div class='row'>
		<div class="col-12 d-flex flex-wrap justify-content-center">
			<h4 class="text-light"><?php StartDB(); NameGroup(); CountBM();?></h4>
		</div>
	</div>
	<div class='row'>
		<div class="col-12 d-flex flex-wrap justify-content-center">
			
			<?php
			ViewBM();
			?>
		</div>
	</div>
</div>
<?php
if(isset($_SESSION['iduser']))
{?>
	<!-- Модальное окно добавления новой закладки-->
	<div class="modal fade" id="Modal_BM"  role="dialog" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content bg-dark">
				<div class="modal-body">				
					<div class="row text-center">
						<div class="col-12 d-flex justify-content-center mt-2">
							<div class="accordion form3">
								<div class="card-body bg-dark">
									<form action="addsite.php" class="" method="POST">											
										<div class = "form-group">
											<?php
												SelectGroup();
												EndDB();
											?>				
										</div>
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
	<div class="container">
		<div class="row">
			<div class="col-12 d-flex flex-wrap justify-content-end ">
			<a  role='button' data-toggle='modal' data-target='#Modal_BM'> 
			<i class='bi bi-plus-circle-fill plus text-light' style = 'font-size: 40px;'></i></a>	
			</div>
		</div>
	</div>
	<?php	
}
 require_once "footer.php";
