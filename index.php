<?php

use google\appengine\api\users\User;
use google\appengine\api\users\UserService;
# Looks for current Google account session

$userGoogle = UserService::getCurrentUser();

include('db.php');
include('header.php');

if($userGoogle && $isAuthorized) {

	// Usuario loggeado, mostrar bienvenida


	// Checar que el usuario esté en la base de usuarios
?>



<?php

$cat = new Categoria();

?>

<section class="header-11-sub">
	<!-- <div class="background">
	                    &nbsp;
	</div> -->
	<div class="container">
		<div class="col-sm-12">
			<div class="row">
				<h3>Bienvenido, <?php echo htmlspecialchars($userGoogle->getNickname()); ?></h3>
			</div>
			<div class="row">
				<h4>Categorías:</h4>

				<div class="lista-categorias col-sm-12">
					<?php 
						$cats = $cat->getCategorias($idOrganizacion);

						foreach ($cats as $catItem) {
							?>
							<div class="cat-edit-item row" data-id-categoria='<?php echo $catItem['ID']; ?>'>
								<h5>
									<div class="cat-bullet"><span class="fui-arrow-right"></span></div>
									<a href='/editcat?catid=<?php echo $catItem['ID'] ?>' class='cat-link' data-id-categoria='<?php echo $catItem['ID'] ?>'><?php echo $catItem['sNombre'] ?></a>
									<a href='#' class='del-cat-link' data-id-categoria='<?php echo $catItem['ID']; ?>'><span class="fui-cross"></span></a>
								</h5>
							</div>
							
							<?php
						}
					?>

					<!-- Clones necesarios en el DOM para construir operaciones via AJAX -->

					<!-- Categoría -->
					<div class="cat-edit-item-clone cat-edit-item row" data-id-categoria=''>
						<h5>
							<div class="cat-bullet"><span class="fui-arrow-right"></span></div>
							<a href='/editcat?catid=' class='cat-link' data-id-categoria=''></a>
							<a href='#' class='del-cat-link' data-id-categoria=''><span class="fui-cross"></span></a>
						</h5>
					</div>


				</div>

				<p><a href="#" class="add-cat-link">Agregar categor&iacute;a</a></p>


				<div class="add-cat-form form-group row">
					<form>
						<div class="form-group form-text-and-button">
							<div class=""><input type="text" class="form-control add-cat-nombre" placeholder="Nombre"></div>
                            <div><button type="submit" class="btn btn-block btn-info add-cat-button">+</button></div>
                        </div>
					</form>
				</div>


				<p>Pantallas por set:</p> 	<div><input type="text" class="pantallas-por-set form-control"></div>
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