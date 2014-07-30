<?php

Class Core
{
    public $dbh; // handle of the db connexion
    private static $instance;

    private function __construct()
    {
        //$this->dbh = new pdo('mysql:unix_socket=/cloudsql/aretesoftware:aretemotion;dbname=aretemotion', 'root', '');

        $this->dbh = null;
        if (isset($_SERVER['SERVER_SOFTWARE']) &&
        strpos($_SERVER['SERVER_SOFTWARE'],'Google App Engine') !== false) {
          // Connect from App Engine.
          try{
             $this->dbh = new pdo('mysql:unix_socket=/cloudsql/aretesoftware:aretemotion;dbname=aretemotion', 'root', '');
          }catch(PDOException $ex){
              die(json_encode(
                  array('outcome' => false, 'message' => 'Unable to connect. ' . $ex)
                  )
              );
          }
        } else {
          // Connect from a development environment.
          try{
            //$this->dbh = new pdo('mysql:host=173.194.243.107:3306;dbname=aretemotion', 'areteMotion', 'aretE');
             // building data source name from config
            $dsn = 'mysql:host=' . Config::read('db.host') .
                   ';dbname='    . Config::read('db.basename') .
                   ';port='      . Config::read('db.port') .
                   ';connect_timeout=15';
                   // getting DB user from config                
            $user = Config::read('db.user');
            // getting DB password from config                
            $password = Config::read('db.password');
            $this->dbh = new PDO($dsn, $user, $password);

          }catch(PDOException $ex){
              die(json_encode(
                  array('outcome' => false, 'message' => 'Unable to connect (local). ' . $ex)
                  )
              );
          }
        }
    }

    public static function getInstance()
    {
        if (!isset(self::$instance))
        {
            $object = __CLASS__;
            self::$instance = new $object;
        }
        return self::$instance;
    }

    // others global functions
}

class Config
{
    static $confArray;

    public static function read($name)
    {
        return self::$confArray[$name];
    }

    public static function write($name, $value)
    {
        self::$confArray[$name] = $value;
    }

}

// db
Config::write('db.host', '173.194.243.107');
Config::write('db.port', '3306');
Config::write('db.basename', 'aretemotion');
Config::write('db.user', 'areteMotion');
Config::write('db.password', 'aretE');




class Categoria {

  /*public static $ID;
  public static $nombre;
  public static $idOrganizacion;*/

  function getCategorias($idOrganizacion) {
    try {
      $core = Core::getInstance();
      $q = "SELECT ID, sNombre, idOrganizacion FROM tblCategorias WHERE idOrganizacion = :idOrganizacion";
      $statement = $core->dbh->prepare($q);
      $statement->execute(array(":idOrganizacion" => $idOrganizacion));
      // Mostrar categorias
      foreach($statement->fetchAll() as $row) {
        echo "<li><a href='/editcat?catid=".$row['ID']."' data-id-categoria='".$row['ID']."'>". $row['sNombre'] ."</a> (<a href='#' class='del-cat-link' data-id-categoria='".$row['ID']."'>Borrar</a>)</li>";
      }
    } catch (PDOException $ex) {
       echo "Error: " . $ex;
    }
  }

  function getCategoria($idCategoria) {
    try {
      $core = Core::getInstance();
      $q = "SELECT ID, sNombre, idOrganizacion FROM tblCategorias WHERE ID = :idCategoria";
      $statement = $core->dbh->prepare($q);
      $statement->execute(array(":idCategoria" => $idCategoria));
      $cat_info["cat_ok"] = false;
      // Obtener categoria
      foreach($statement->fetchAll() as $row) {
        $cat_info["ID"] = $row['ID'];
        $cat_info["sNombre"] = $row['sNombre'];
        $cat_info["idOrganizacion"] = $row['idOrganizacion'];
        $cat_info["cat_ok"] = true;
      }
      return $cat_info;
    } catch (PDOException $ex) {
       echo "Error: " . $ex;
    }
  }

  function addCategoria($idOrganizacion, $nombreCategoria) {
    try {
      $core = Core::getInstance();
      $q = "INSERT INTO tblCategorias (idOrganizacion, sNombre) VALUES (:idOrganizacion, :sNombre);";
      $statement = $core->dbh->prepare($q);
      $statement->execute(array(":idOrganizacion" => $idOrganizacion, ":sNombre" => $nombreCategoria));
      $affected_rows = $statement->rowCount();
      $last_inserted_id = $core->dbh->lastInsertId();
      return $last_inserted_id;
    } catch (PDOException $ex) {
      echo "Error: " . $ex;
    }
  }

  function delCategoria($idCategoria) {
    try {
      $core = Core::getInstance();
      $q = "DELETE FROM tblCategorias WHERE ID = :idCategoria;";
      $statement = $core->dbh->prepare($q);
      $statement->execute(array(":idCategoria" => $idCategoria));
      $affected_rows = $statement->rowCount();
    } catch (PDOException $ex) {
       echo "Error: " . $ex;
    }
  }
}



/* Sets */

class Set {

  /*public static $ID;
  public static $nombre;
  public static $idOrganizacion;*/

  function getSets($idCategoria) {
    try {
      $core = Core::getInstance();
      $q = "SELECT ID, idCategoria, iOrden, bSetInicio FROM tblSets WHERE idCategoria = :idCategoria ORDER BY iOrden ASC";
      $statement = $core->dbh->prepare($q);
      $statement->execute(array(":idCategoria" => $idCategoria));
      // Mostrar categorias
      foreach($statement->fetchAll() as $row) {
        echo "<li><a href='/editset?setid=".$row['ID']."' data-id-set='".$row['ID']."'> Set ". $row['iOrden'] ."</a> (<a href='#' class='del-cat-link' data-id-set='".$row['ID']."'>Borrar</a>)</li>";
      }
    } catch (PDOException $ex) {
       echo "Error: " . $ex;
    }
  }

  function getSet($idSet) {
    try {
      $core = Core::getInstance();
      $q = "SELECT ID, idCategoria, iOrden, bSetInicio FROM tblSets WHERE ID = :idSet";
      $statement = $core->dbh->prepare($q);
      $statement->execute(array(":idCategoria" => $idCategoria));
      $set_info["cat_ok"] = false;
      // Obtener categoria
      foreach($statement->fetchAll() as $row) {
        $set_info["ID"] = $row['ID'];
        $set_info["idCategoria"] = $row['idCategoria'];
        $set_info["iOrden"] = $row['iOrden'];
        $set_info["set_ok"] = true;
      }
      return $set_info;
    } catch (PDOException $ex) {
       echo "Error: " . $ex;
    }
  }

  function addCategoria($idOrganizacion, $nombreCategoria) {
    try {
      $core = Core::getInstance();
      $q = "INSERT INTO tblCategorias (idOrganizacion, sNombre) VALUES (:idOrganizacion, :sNombre);";
      $statement = $core->dbh->prepare($q);
      $statement->execute(array(":idOrganizacion" => $idOrganizacion, ":sNombre" => $nombreCategoria));
      $affected_rows = $statement->rowCount();
      $last_inserted_id = $core->dbh->lastInsertId();
      return $last_inserted_id;
    } catch (PDOException $ex) {
      echo "Error: " . $ex;
    }
  }

  function delCategoria($idCategoria) {
    try {
      $core = Core::getInstance();
      $q = "DELETE FROM tblCategorias WHERE ID = :idCategoria;";
      $statement = $core->dbh->prepare($q);
      $statement->execute(array(":idCategoria" => $idCategoria));
      $affected_rows = $statement->rowCount();
    } catch (PDOException $ex) {
       echo "Error: " . $ex;
    }
  }
}

class Usuario {

  function getInfoUsuario($user) {
    try {
      $core = Core::getInstance();
      $q = "SELECT ID, sUser, idOrganizacion, sPermiso FROM tblUsuarios WHERE sUser = :usuario";
      $statement = $core->dbh->prepare($q);
      $statement->execute(array(":usuario" => $user));
      // Mostrar categorias
      foreach($statement->fetchAll() as $row) {
        $infoUsuario = array( "idUser" => $row["ID"],
                              "userName" => $row["sUser"],
                              "idOrganizacion" => $row["idOrganizacion"],
                              "sPermiso" => $row["sPermiso"]
                            );
      }
      return $infoUsuario;
    } catch (PDOException $ex) {
       echo "Error: " . $ex;
    }
  }
}

class Organizacion {

  function getInfoOrganizacion($idUser) {
    try {
      $core = Core::getInstance();
      $q = "SELECT 
            orgs.ID as idOrg, 
            sNombre, 
            sConfiguracion 
            FROM 
              tblOrganizaciones as orgs,
              tblUsuarios as usuarios
            WHERE usuarios.ID = :idUser
            AND 
                orgs.ID = usuarios.idOrganizacion";

      $statement = $core->dbh->prepare($q);
      $statement->execute(array(":idUser" => $idUser));

      // Regresar id de organizacion
      foreach($statement->fetchAll() as $row) {
        $idOrganizacion = array("idOrganizacion" => $row['idOrg'],
                                "nombre" => $row['sNombre']
                              );
      }
      return $idOrganizacion;
    } catch (PDOException $ex) {
       echo "Error: " . $ex;
    }
  }
}

?>