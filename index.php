<?php

use google\appengine\api\users\User;
use google\appengine\api\users\UserService;
# Looks for current Google account session

$userGoogle = UserService::getCurrentUser();

include('db.php');
include('header.php');

if($userGoogle) {

	// Usuario loggeado, mostrar bienvenida
?>



<?php

$cat = new Categoria();

?>

<section class="header-11-sub">
	<!-- <div class="background">
	                    &nbsp;
	</div> -->
	<div class="container">
		<div class="row">
			<div class="col-sm-4">
				<h3>Bienvenido, <?php echo htmlspecialchars($userGoogle->getNickname()); ?></h3>

				<p>Categorías:</p>

				<ul class="lista-categorias">
					<?php 
						$cat->getCategorias($idOrganizacion);
					?>
				</ul>

				<p><a href="#" class="add-cat-link">Agregar categor&iacute;a</a></p>


				<div class="add-cat-form signup-form">
					<form>
						<div class="form-group form-text-and-button">
							<div class=""><input type="text" class="form-control add-cat-nombre" placeholder="Nombre"></div>
                            <div><button type="submit" class="btn btn-block btn-info add-cat-button">+</button></div>
                        </div>
					</form>
				</div>

				<p>Pantallas por set:</p> 	<div><input type="text" class="pantallas-por-set form-control"></div>
			</div>
			<div class="col-sm-7 col-sm-offset-1">
				<h4></h4>
			</div>
		</div>

	</div>
</section>


<?php

} else {

?>

	<?php //include('header.php'); ?>
	
	<section class="header-11-sub bg-midnight-blue">
	<div class="background">
	                    &nbsp;
	</div>
	<div class="container">

		<h3>Login</h3>
	</div>
</section>

<?php

	// Enviar a Login
	//header('Location: ' . UserService::createLoginURL($_SERVER['REQUEST_URI']));
}

?>

<?php include('footer.php'); ?>