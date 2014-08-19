<?php
//header('Content-type: application/json');
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
    $pantalla = new Pantalla();


    //$last_inserted_id = $pantalla->addPantallaInsert($_POST['id_categoria'], $_POST["orden_set"], $_POST["set_inicio"]);

    $response_array['p_insert'] = $_POST['p_insert'];
    $response_array['p_update'] = $_POST['p_update'];

    //$post = '[{"id":"9","titulo":"Reporte 5","url":"http://google.com","set_id":"2","orden":"1"},{"id":"10","titulo":"Reporte 7.6","url":"http://google.com","set_id":"2","orden":"2"},{"id":"11","titulo":"Reporte 7.2","url":"http://google.com","set_id":"2","orden":"3"},{"id":"12","titulo":"Reporte 7.3","url":"http://google.com","set_id":"2","orden":"4"}]';

    $p_update = json_decode($_POST['p_update'], true);
    //$p_update = json_decode($post, true);

    // Actualizando las pantallas ya existentes
    foreach($p_update as $p) {
        $query_status = $pantalla->editPantalla($p['id'], $p['titulo'], $p['url'], $p['orden']);
    }

    /*foreach($p_insert as $p) {
        $query_status = $pantalla->addPantallaUpdate($p['id'], $p['titulo'], $p['url'], $p['orden']);
    }*/


    //$query_status = $pantalla->addPantallaUpdate()

    $response_array['query_status'] = $query_status;
    $response_array['last_inserted_id'] = $last_inserted_id;
    $response_array['status'] = 'success';

} else {
	$response_array['status'] = 'error';
	return false;

}


echo json_encode($response_array);