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
					        <div class="set-edit-item row">
					        	<h5>
					        		<a href='/editset?setid=<?php echo $setItem["ID"]; ?>' data-id-set='<?php echo $setItem['ID']; ?>' data-orden-set='<?php echo $setItem['iOrden']; ?>' class="edit-set-link"> Set <?php echo $setItem["iOrden"] ?></a> 
					        		<a href='#' class='del-set-link' data-id-set='<?php echo $setItem['ID']; ?>'><span class="fui-cross"></span></a>
					        	</h5>
					        	<div class='edit-set-pantallas-list col-md-12'>
					        		
					        	</div>
					        	<div class="btn-add-pantalla-row btn-add-pantalla-row-clone row">
							        	<a href="#" class="btn-add-pantalla"><span class="fui-plus"></span></a>
							    </div>
					        	
					        </div>
					        
					        <?php
					      }
						?>

						<div class="set-edit-item-clone row">
				        	<h5>
				        		<a href='#' data-id-set='' data-orden-set='' class="edit-set-link"> Set </a> 
				        		<a href='#' class='del-set-link' data-id-set=''><span class="fui-cross"></span></a>
				        	</h5>
				        	<div class='edit-set-pantallas-list col-md-12'></div>
				        	<div class="btn-add-pantalla-row btn-add-pantalla-row-clone row">
						        <a href="#" class="btn-add-pantalla"><span class="fui-plus"></span></a>
						    </div>
					   </div>

						<div class="pantalla-edit-item form-group pantalla-edit-item-clone row" style="">
							<input type="text" class="pantalla-titulo-edit form-control" name="pantalla-titulo" value="" placeholder="T&iacute;tulo">
							<input type="text" class="pantalla-url-edit form-control" name="pantalla-url" value="" placeholder="URL">
						</div>
						<div class="row">
						
							<div class="btn-add-set tagsinput-add" data-orden-set='<?php echo ($setItem['iOrden'] + 1); ?>'></div>
							
						
						</div>
					</div>
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