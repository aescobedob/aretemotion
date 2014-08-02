
<?php
use google\appengine\api\users\User;
use google\appengine\api\users\UserService;
# Looks for current Google account session

$userGoogle = UserService::getCurrentUser();

if($userGoogle) {
// Obtener organizacion a la que pertenece a partir de su usuario
    $userMotion = new Usuario();
    $organizacion = new Organizacion();

    $infoUsuario = $userMotion->getInfoUsuario($userGoogle->getEmail());

    $idUsuario = $infoUsuario['idUser'];

    $infoOrganizacion = $organizacion->getInfoOrganizacion($idUsuario);

    $idOrganizacion = $infoOrganizacion['idOrganizacion'];
}


?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Areté Motion</title>
        <meta name="apple-mobile-web-app-capable" content="yes" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
        <meta name="apple-mobile-web-app-status-bar-style" content="black" />
        <link rel="shortcut icon" href="/css/flat-ui/images/favicon.ico">
        <link rel="stylesheet" href="/css/flat-ui/bootstrap/css/bootstrap.css">
        <link rel="stylesheet" href="/css/flat-ui/css/flat-ui.css">
        <!-- Using only with Flat-UI (free)-->
        <link rel="stylesheet" href="/css/common-files/css/icon-font.css">
        <!-- end -->
        <link rel="stylesheet" href="/css/style.css">
    </head>

    <body>

    

    <div class="page-wrapper">

        <div class="demo-headline">
            <h1 class="demo-logo">
              <div class="logo"></div>
              Motion
              <small>Administrador</small>
            </h1>
          </div> <!-- /demo-headline -->
            <!-- header-11 -->
            <header class="header-11">
                <div class="container">
                    <div class="row">
                        <div class="navbar col-sm-12" role="navigation">
                            <div class="navbar-header">
                                <button type="button" class="navbar-toggle"></button>
                                <a class="brand" href="#"><img src="img/logo@2x.png" width="50" height="50" alt=""> Motion</a>
                            </div>
                            <div class="collapse navbar-collapse pull-right">
                                <ul class="nav pull-left">
                                    <li class="active"><a href="/">INICIO</a></li>
                                </ul>
                                <form class="navbar-form pull-left">
                                    <?php if(!$userGoogle) {
                                        ?>
                                        <a class="btn btn-primary" href="<?php echo UserService::createLoginUrl('/') ?>">Iniciar</a>
                                    <?
                                    } else { ?>
                                        <a class="btn btn-primary" href="<?php echo UserService::createLogoutUrl('/') ?>">Salir</a>
                                    <?php } ?>

                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </header>