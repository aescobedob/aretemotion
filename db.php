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

class GQL
{
  function getPantallas($idSet) {
    try {
      $core = Core::getInstance();
      $q = "SELECT 
              pantallas.ID,
              pantallas.idSet,
              pantallas.sTitulo,
              pantallas.sURL,
              pantallas.iOrdenPantalla
            FROM 
              tblPantallas as pantallas
            WHERE 
              idSet = :idSet 
            ORDER BY 
              iOrdenPantalla ASC";
      $statement = $core->dbh->prepare($q);
      $statement->execute(array(":idSet" => $idSet));

      $i = 0;

      // Mostrar categorias
      foreach($statement->fetchAll() as $row) {

        $pantallas[$i]["ID"] = $row["ID"];
        $pantallas[$i]["idSet"] = $row["idSet"];
        $pantallas[$i]["sTitulo"] = $row["sTitulo"];
        $pantallas[$i]["sURL"] = $row["sURL"];
        $pantallas[$i]["iOrdenPantalla"] = $row["iOrdenPantalla"];


        $i++;

      }

      return $pantallas;

    } catch (PDOException $ex) {
       echo "Error: " . $ex;
    }
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

  function saveCategoria($idCategoria, $sNombreCategoria) {
    try {
      $core = Core::getInstance();
      $q = "UPDATE tblCategorias SET sNombre = :sNombreCategoria WHERE ID = :idCategoria";
      $statement = $core->dbh->prepare($q);
      $statement->execute(array(":sNombreCategoria" => $sNombreCategoria, ":idCategoria" => $idCategoria));
      $affected_rows = $statement->rowCount();
      $last_inserted_id = $core->dbh->lastInsertId();
      return $affected_rows;
    } catch (PDOException $ex) {
      echo "Error: " . $ex;
    }
  }
}



/* Sets */

class Set {

  public $ID;
  public $nombre;
  public $idOrganizacion;
  public $setInicio;



  function getSets($idCategoria) {
    try {
      $core = Core::getInstance();
      $q = "SELECT ID, idCategoria, iOrden, bSetInicio FROM tblSets WHERE idCategoria = :idCategoria ORDER BY iOrden ASC";
      $statement = $core->dbh->prepare($q);
      $statement->execute(array(":idCategoria" => $idCategoria));

      $i=0;

      // Mostrar categorias
      foreach($statement->fetchAll() as $row) {
        //echo "<li><a href='/editset?setid=".$row['ID']."' data-id-set='".$row['ID']."'> Set ". $row['iOrden'] ."</a> (<a href='#' class='del-cat-link' data-id-set='".$row['ID']."'>Borrar</a>)</li>";

        $sets[$i]["ID"] = $row['ID'];
        $sets[$i]["iOrden"] = $row['iOrden'];
        $sets[$i]["idCategoria"] = $row['idCategoria'];
        $sets[$i]["bSetInicio"] = $row['bSetInicio'];

        $i++;

      }

      return $sets;

    } catch (PDOException $ex) {
       echo "Error: " . $ex;
    }
  }

  function getSetsConPantallas($idCategoria) {
    try {
      $core = Core::getInstance();
      $q = "SELECT 
              sets.ID as setID, 
              idCategoria, 
              sets.iOrden, 
              bSetInicio,
              pantallas.ID pantallaID,
              pantallas.idSet,
              pantallas.sTitulo as tituloPantalla,
              pantallas.sURL as URL,
              pantallas.iOrdenPantalla as ordenPantalla
            FROM 
              tblSets as sets,
              tblPantallas as pantallas
            WHERE 
              idCategoria = :idCategoria 
            AND
              pantallas.idSet = sets.ID
            ORDER BY 
              iOrden ASC";
      $statement = $core->dbh->prepare($q);
      $statement->execute(array(":idCategoria" => $idCategoria));

      $iSet=0;
      $iPantalla=0;

      // Mostrar categorias
      foreach($statement->fetchAll() as $row) {
        //echo "<li><a href='/editset?setid=".$row['ID']."' data-id-set='".$row['ID']."'> Set ". $row['iOrden'] ."</a> (<a href='#' class='del-cat-link' data-id-set='".$row['ID']."'>Borrar</a>)</li>";



        $setsConPantallas[$row['ID']]["setID"] = $row['ID'];
        $setsConPantallas[$row['ID']]["iOrden"] = $row['iOrden'];
        $setsConPantallas[$row['ID']]["idCategoria"] = $row['idCategoria'];
        $setsConPantallas[$row['ID']]["bSetInicio"] = $row['bSetInicio'];

        $setsConPantallas[$row['ID']]["pantallas"][$row['pantallaID']]["pantallaID"] = $row['pantallaID'];
        $setsConPantallas[$row['ID']]["pantallas"][$row['pantallaID']]["sTitulo"] = $row['sTitulo'];
        $setsConPantallas[$row['ID']]["pantallas"][$row['pantallaID']]["sURL"] = $row['sURL'];
        $setsConPantallas[$row['ID']]["pantallas"][$row['pantallaID']]["iOrdenPantalla"] = $row['iOrdenPantalla'];

        $i++;

      }

      //print_r($setsConPantallas);

      return $setsConPantallas;

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

  function addSet($idCategoria, $ordenSet, $setInicio) {
    try {
      $core = Core::getInstance();
      $q = "INSERT INTO tblSets (idCategoria, iOrden, bSetInicio) VALUES (:idCategoria, :iOrden, :bSetInicio);";
      $statement = $core->dbh->prepare($q);
      $statement->execute(array(":idCategoria" => $idCategoria, ":iOrden" => $ordenSet, ":bSetInicio" => $setInicio));
      $affected_rows = $statement->rowCount();
      $last_inserted_id = $core->dbh->lastInsertId();
      return $last_inserted_id;
    } catch (PDOException $ex) {
      echo "Error: " . $ex;
    }
  }

  function delSet($idSet) {
    try {
      $core = Core::getInstance();
      $q = "DELETE FROM tblSets WHERE ID = :idSet;";
      $statement = $core->dbh->prepare($q);
      $statement->execute(array(":idSet" => $idSet));
      $affected_rows = $statement->rowCount();
      return $affected_rows;
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
        $info_org = array(      "idOrganizacion" => $row['idOrg'],
                                "nombre" => $row['sNombre'],
                                "sConfiguracion" => $row['sConfiguracion']
                              );
      }
      return $info_org;
    } catch (PDOException $ex) {
       echo "Error: " . $ex;
    }
  }
}



?>