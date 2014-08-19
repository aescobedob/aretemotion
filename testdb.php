<?php

Class Core
{
    public $dbh; // handle of the db connexion
    private static $instance;

    private function __construct()
    {
        //$this->dbh = new pdo('mysql:unix_socket=/cloudsql/aretesoftware:aretemotion;dbname=aretemotion', 'root', '');

        $this->dbh = null;
        // Descomentar para instanciar 
        
        if (isset($_SERVER['SERVER_SOFTWARE']) &&
        strpos($_SERVER['SERVER_SOFTWARE'],'Google App Engine') !== false) {
          // Connect from App Engine.
          try{
             $this->dbh = new pdo('mysql:unix_socket=/cloudsql/aretesoftware:aretemotion;dbname=aretemotion', 'areteMotion', 'aretE');
          }catch(PDOException $ex){
              die(json_encode(
                  array('outcome' => false, 'message' => 'Unable to connect. ' . $ex)
                  )
              );
          }
          // Connect from IP if in App Engine
          /*try{
            //$this->dbh = new pdo('mysql:host=173.194.243.107:3306;dbname=aretemotion', 'areteMotion', 'aretE');
             // building data source name from config
            $dsn = 'mysql:host=' . '127.0.0.1' .
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
                  array('outcome' => false, 'message' => 'Unable to connect. ' . $ex)
                  )
              );
          }*/
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

?>