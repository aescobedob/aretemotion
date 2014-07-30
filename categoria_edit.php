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

$cat_info = $cat->getCategoria($_GET['catid']);

$set = new Set();

?>

<section class="header-11-sub bg-midnight-blue">
	<div class="background">
	                    &nbsp;
	</div>
	<div class="container">
		<div class="row">
			<div class="col-sm-4">
				<h3>Editando categoría: <?php echo $cat_info["sNombre"]; ?></h3>

				<div><input type="text" class="form-control edit-cat-nombre" placeholder="Nombre" value="<?php echo $cat_info["sNombre"]; ?>"></div>

			</div>
			<div class="col-sm-7 col-sm-offset-1">
				<h4>Sets</h4>
				<ul class="lista-categorias">
					<?php 
						$set->getSets($cat_info['ID']);
					?>
					<li><a href="#" class="add-set-link">+</a></li>
				</ul>
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