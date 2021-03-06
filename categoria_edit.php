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

<section class="header-11-sub">
	<!-- <div class="background">
	                    &nbsp;
	</div> -->
	<div class="container">
		
			<div class="col-sm-12">
				<div class="row">
					<h3>
						Editando categoría: <span class="edit-cat-name"><?php echo $cat_info["sNombre"]; ?></span> 
						<a href="#" class="edit-cat-name-link"><span class="fui-new"></span></a>
						<div class="edit-cat-name-form">
							<input type="text" class="form-control edit-cat-name-input" placeholder="Nombre" data-cat-id="<?php echo $_GET['catid']; ?>" value="<?php echo $cat_info["sNombre"]; ?>">
							<a href="#" class="btn-guardar-cat-name btn btn-primary btn-info">Guardar</a>
							<a href="#" class="btn-cancelar-cat-name btn btn-default">Cancelar</a>
						</div>
					</h3>
				</div>

					

				
				<!-- <div class="col-sm-7 col-sm-offset-1"> -->
				<div class="row">
					<h4>Sets</h4>
					<div class="lista-categorias col-sm-12">

						<?php 
						  $sets = $set->getSets($cat_info['ID']);

					      foreach($sets as $setItem) {
					        ?>
					        <div class="set-edit-item row" data-set-collapsed="1" data-orden-set='<?php echo $setItem['iOrden']; ?>' data-set-id='<?php echo $setItem['ID']; ?>'>
					        	
					        	<h5>
					        		<div class="reorder-set-handle"><span class="fui-list"></span></div>
					        		<a href='/editset?setid=<?php echo $setItem["ID"]; ?>' data-set-id='<?php echo $setItem['ID']; ?>' data-orden-set='<?php echo $setItem['iOrden']; ?>' class="edit-set-link" data-set-collapsed="1"> Set <?php echo $setItem["iOrden"] ?></a> 
					        		<a href='#' class='del-set-link' data-set-id='<?php echo $setItem['ID']; ?>'><span class="fui-cross"></span></a>
					        	</h5>
						        <div class="set-edit-item-wrapper">
						        	<div class='edit-set-pantallas-list col-md-12'></div>
						        	<div class="btn-add-pantalla-row">
										<a href="#" class="btn-add-pantalla"><span class="fui-plus"></span></a>
									</div>
					        	</div>
					        	
					        </div>
					        
					        <?php
					      }
						?>
						
						<!-- Clones necesarios en el DOM para construir operaciones via AJAX -->

						<!-- Set completo -->
						<div class="set-edit-item-clone row" data-set-collapsed="1" data-orden-set="" data-set-id="0">
				        	<h5>
				        		<div class="reorder-set-handle"><span class="fui-list"></span></div>
				        		<a href='#' data-set-id='' data-orden-set='' class="edit-set-link"> Set </a> 
				        		<a href='#' class='del-set-link' data-set-id=''><span class="fui-cross"></span></a>
				        	</h5>
					        <div class="set-edit-item-wrapper">
					        	<div class='edit-set-pantallas-list col-md-12'></div>
				        	</div>
					   </div>

					   <!-- Input de pantalla individual -->
						<div class="pantalla-edit-item form-group pantalla-edit-item-clone row" style="" data-set-id="0" data-pantalla-id="0" data-orden-pantalla="1">
							<div class="reorder-pantalla-handle"><span class="fui-list"></span></div>
							<input type="text" class="pantalla-titulo-edit form-control" name="pantalla-titulo" value="" placeholder="T&iacute;tulo">
							<input type="text" class="pantalla-url-edit form-control" name="pantalla-url" value="" placeholder="URL">
							<div class="btn-del-pantalla"><span class="fui-cross"></span></div>
						</div>

					    <!-- Botón de agregar pantalla -->
						<div class="btn-add-pantalla-row btn-add-pantalla-row-clone">
							<a href="#" class="btn-add-pantalla"><span class="fui-plus"></span></a>
						</div>
						
						<!-- END clones -->

					</div>
					
					<div class="btn-add-set tagsinput-add" data-orden-set-next='<?php echo ($setItem['iOrden'] + 1); ?>'></div>
					
				</div>
				


			
				<div class="row demo-row">
						<a class="btn-guardar btn btn-primary btn-info pull-right">Guardar</a>
				</div>
		
			</div> <!-- .col-sm-12 -->
		</div> <!-- .demo-row -->
	</div> <!-- .container -->
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