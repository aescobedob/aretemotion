<?php
header('Content-type: application/json');
//echo "Something!";
use google\appengine\api\users\User;
use google\appengine\api\users\UserService;
# Looks for current Google account session

$userGoogle = UserService::getCurrentUser();


include('db.php');




if($userGoogle) {
// Obtener organizacion a la que pertenece a partir de su usuario
    $userMotion = new Usuario();
    $organizacion = new Organizacion();

    $infoUsuario = $userMotion->getInfoUsuario($userGoogle->getEmail());
    $idUsuario = $infoUsuario['idUser'];
    $infoOrganizacion = $organizacion->getInfoOrganizacion($idUsuario);
    $idOrganizacion = $infoOrganizacion['idOrganizacion'];

    $cat = new Categoria();

    $last_inserted_id = $cat->addCategoria($idOrganizacion, $_POST['nombre_categoria'] );

    $response_array['last_inserted_id'] = $last_inserted_id;
    $response_array['status'] = 'success';

} else {
	$response_array['status'] = 'error';
	return false;

}


echo json_encode($response_array);

