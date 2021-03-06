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

    $idSet = $_GET['id_set'];

    $cat = new Categoria();
    $set = new Set();
    //$pantalla = new Pantalla();



    $gql = new GQL();

    $pantallas = $gql->getPantallas($idSet);

    $response_array['datos'] = $pantallas;
    $response_array['status'] = 'success';
    $response_array['user_status'] = 'success';
    $response_array['num_pantallas_set'] = count($pantallas);

} else {
    $response_array['status'] = 'error';
	$response_array['user_status'] = 'error';
	return false;

}


echo json_encode($response_array);

