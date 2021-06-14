<?php 
namespace Web\App\Database;
use \PDO;
require('./vendor/autoload.php');

Class Database {

    private $url = NULL;
    private $db = 'contactbook';
    private $user = NULL;
    private $pwd = NULL;
    private $cnx = NULL;
    private static $database_instance = NULL;
    private static $charset = 'utf8';


    private function __construct() {
        $dotenv = \Dotenv\Dotenv::createImmutable('./');
        $dotenv->load();

        $this->url = $_ENV['DB_HOST'].':'.$_ENV['DB_PORT'];
        $this->user = $_ENV['DB_USER'];
        $this->pwd = $_ENV['DB_PWD'];

        $this->create_connection();
    }

    private function create_connection(): void {
        $this -> cnx = new PDO(
            'mysql:host='.$this->url.';dbname='.$this->db.';charset='.self::$charset,
            $this->user,
            $this->pwd
        );

        $this -> cnx -> setAttribute(
            PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION
        );
    }

    static function get_instance():Database {
        if (NULL == self::$database_instance) {
            self::$database_instance = new Database();
        }
        return self::$database_instance;
    }

    public function get_connection(): PDO {
        return $this->cnx;
    }

}
?>  