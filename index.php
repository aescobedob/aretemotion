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

<section class="header-11-sub bg-midnight-blue">
	<div class="background">
	                    &nbsp;
	</div>
	<div class="container">

		<h3>Bienvenido, <?php echo htmlspecialchars($userGoogle->getNickname()); ?></h3>

		<p>Categorías:</p>

		<ul class="lista-categorias">
			<?php 
				$cat->getCategorias($idOrganizacion);
			?>
		</ul>

		<a href="#">Agregar categor&iacute;a</a>

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