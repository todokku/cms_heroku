<?php
class Database
{
    // Note: specify your own database credentials
    private $host = "us-cdbr-iron-east-04.cleardb.net";

    private $db_name = "heroku_49e2e74c0fef76c";

    private $username = "bdc135ba90e398";

    private $password = "0d9e489b";

    private static $instance = null;
    public $conn;

    private function __construct()
    {
        $db_dsn = array(
            'host'    => $this->host,
            'dbname'  => $this->db_name,
            'charset' => 'utf8',
        );

        if (getenv('IDP_ENVIRONMENT') === 'docker') {
            $db_dsn['host'] = 'mysql';
            $this->username = 'docker_u';
            $this->password = 'docker_p';
        }

        try {
            $dsn        = 'mysql:' . http_build_query($db_dsn, '', ';');
            $this->conn = new PDO($dsn, $this->username, $this->password);
        } catch (PDOException $exception) {
            echo json_encode(
                array(
                    'error'   => 'Database connection failed',
                    'message' => $exception->getMessage(),
                )
            );
            exit;
        }
    }

    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new Database();
        }

        return self::$instance;
    }
    // get the database connection
    public function getConnection()
    {
        return $this->conn;
    }
}
