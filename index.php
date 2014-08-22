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
			<div class="row col-sm-12">
				<h4>Categorías:</h4>

				<div class="lista-categorias row col-sm-12">
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

				<div class="row col-sm-12">
					<h3><a href="#" class="add-cat-link"><span class="fui-plus"></span></a></h3>
				</div>


				<div class="add-cat-form form-group row">
					<div class="form-group form-text-and-button col-sm-4">
						<div class="row">
							<input type="text" class="form-control add-cat-nombre" placeholder="Nombre">
                        	<button type="submit" class="btn btn-block btn-info add-cat-button">+</button>
                        </div>
                    </div>
				</div>

				<div class="row">
					<label for="pantallas-por-set" class="pantallas-por-set-label">Pantallas por set:</label>
					<input type="number" min="1" max="8" maxlength="2" class="pantallas-por-set form-control" id="pantallas-por-set">
				</div>
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