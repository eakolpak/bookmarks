<?php session_start(); $title = "Администрирование"; require_once "header.php"; ?>

<div class="container">
<a href = '../index.php'><i class='bi bi-box-arrow-left text-info' style="font-size: 30px;"></i></a>
        <div class='row'>
    	        <div class="col-12 text-center">
                        <h3 class="mt-3 mb-3">Администратор</h3>
                        <?php 
                        StartDB(); 
                        CheckAdmin();
                        EndDB(); ?>	
                </div>
	</div>
</div>
<?php require_once "footer.php";?>
