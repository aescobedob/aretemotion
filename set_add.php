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
    $configuracion = $infoOrganizacion['sConfiguracion'];

    $cat = new Categoria();
    $set = new Set();

    $last_inserted_id = $set->addSet($_POST['id_categoria'], $_POST["orden_set"], $_POST["set_inicio"]);

    $response_array['last_inserted_id'] = $last_inserted_id;
    $response_array['status'] = 'success';

} else {
	$response_array['status'] = 'error';
	return false;

}


echo json_encode($response_array);

