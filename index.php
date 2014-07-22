<?php

use google\appengine\api\users\User;
use google\appengine\api\users\UserService;
# Looks for current Google account session

$user = UserService::getCurrentUser();

if($user) {

	// Usuario loggeado, mostrar bienvenida
?>

<?php include('header.php'); ?>

<section class="header-11-sub bg-midnight-blue">
	<div class="background">
	                    &nbsp;
	</div>
	<div class="container">

		<h3>Bienvenido, <?php echo htmlspecialchars($user->getNickname()); ?></h3>

		<p>Categorías:</p>

		<?php

		// sacar lista de categorias

		 $db = new pdo('mysql:unix_socket=/cloudsql/aretesoftware:aretemotion;dbname=aretemotion',
		  'root',  // username
		  ''       // password
		  );

		 ?>

		<ul class="lista-categorias">
			<li><a href="#">Finanzas</a></li>
			<li><a href="#">Recursos Humanos</a></li>
			<li><a href="#">Miguel</a></li>
		</ul>

		<a href="#">Agregar categor&iacute;a</a>

	</div>
</section>




<?php

} else {

?>

	<?php include('header.php'); ?>
	
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