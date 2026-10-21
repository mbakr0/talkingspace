<?php
class Database
{
    private $host = DB_HOST;
    private $user = DB_USER;
    private $pass = DB_PASS;
    private $dbname = DB_NAME;
    private $dbh;
    private $error;
    private $stmt;

    public function __construct()
    {
        $dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->dbname . ';charset=utf8mb4';

        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_PERSISTENT => false,
        ];

        // PHP 8.5 moved MySQL-specific PDO constants to Pdo\\Mysql.
        // Keep the legacy constants only as a compatibility fallback for older PHP.
        $mysqlPdoClass = 'Pdo\\Mysql';
        $sslCaOption = class_exists($mysqlPdoClass) && defined($mysqlPdoClass . '::ATTR_SSL_CA')
            ? constant($mysqlPdoClass . '::ATTR_SSL_CA')
            : constant('PDO::MYSQL_ATTR_SSL_CA');
        $sslCaPathOption = class_exists($mysqlPdoClass) && defined($mysqlPdoClass . '::ATTR_SSL_CAPATH')
            ? constant($mysqlPdoClass . '::ATTR_SSL_CAPATH')
            : (defined('PDO::MYSQL_ATTR_SSL_CAPATH') ? constant('PDO::MYSQL_ATTR_SSL_CAPATH') : null);
        $sslVerifyOption = class_exists($mysqlPdoClass) && defined($mysqlPdoClass . '::ATTR_SSL_VERIFY_SERVER_CERT')
            ? constant($mysqlPdoClass . '::ATTR_SSL_VERIFY_SERVER_CERT')
            : constant('PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT');

        $caPath = getenv('DB_SSL_CA') ?: null;
        if (!$caPath) {
            foreach ([
                '/etc/ssl/certs/ca-certificates.crt',
                '/etc/ssl/cert.pem',
                '/etc/pki/tls/certs/ca-bundle.crt',
            ] as $candidate) {
                if (is_file($candidate) && is_readable($candidate)) {
                    $caPath = $candidate;
                    break;
                }
            }
        }

        if ($caPath && is_file($caPath) && is_readable($caPath)) {
            $options[$sslCaOption] = $caPath;
        } elseif ($sslCaPathOption !== null) {
            foreach (['/etc/ssl/certs', '/etc/pki/tls/certs'] as $candidate) {
                if (is_dir($candidate) && is_readable($candidate)) {
                    $options[$sslCaPathOption] = $candidate;
                    break;
                }
            }
        }

        // Keep server certificate verification enabled.
        $options[$sslVerifyOption] = true;

        try {
            $this->dbh = new PDO($dsn, $this->user, $this->pass, $options);
        } catch (PDOException $e) {
            error_log('TalkingSpace database connection failed: ' . $e->getMessage());
            throw new RuntimeException('Database connection failed. Check server configuration.', 0, $e);
        }
    }

    public function query($query)
    {
        $this->stmt = $this->dbh->prepare($query);
    }

    public function bind($param, $value, $type = null)
    {
        if (is_null($type)) {
            switch (true) {
                case is_int($value):
                    $type = PDO::PARAM_INT;
                    break;
                case is_bool($value):
                    $type = PDO::PARAM_BOOL;
                    break;
                case is_null($value):
                    $type = PDO::PARAM_NULL;
                    break;
                default:
                    $type = PDO::PARAM_STR;
            }
        }

        $this->stmt->bindValue($param, $value, $type);
    }

    public function execute()
    {
        return $this->stmt->execute();
    }

    public function resultset()
    {
        $this->execute();
        return $this->stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function single()
    {
        $this->execute();
        return $this->stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function rowCount()
    {
        return $this->stmt->rowCount();
    }

    public function lastInsertId()
    {
        return $this->dbh->lastInsertId();
    }

    public function beginTransaction()
    {
        return $this->dbh->beginTransaction();
    }

    public function endTransaction()
    {
        return $this->dbh->commit();
    }

    public function cancelTransaction()
    {
        return $this->dbh->rollBack();
    }

    public function debugDumpParams()
    {
        return $this->stmt->debugDumpParams();
    }
}
