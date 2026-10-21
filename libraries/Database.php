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
            $this->ensureSchema();
        } catch (PDOException $e) {
            error_log('TalkingSpace database connection failed: ' . $e->getMessage());
            throw new RuntimeException('Database connection failed. Check server configuration.', 0, $e);
        }
    }


    /**
     * Create the application's tables on first connection when they are missing.
     * The database account must have CREATE and INSERT privileges.
     */
    private function ensureSchema()
    {
        static $schemaReady = false;
        if ($schemaReady) {
            return;
        }

        $tables = [
            "CREATE TABLE IF NOT EXISTS users (
                id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
                name VARCHAR(100) NOT NULL,
                email VARCHAR(254) NOT NULL,
                avatar VARCHAR(255) NOT NULL DEFAULT 'default-avatar.svg',
                username VARCHAR(50) NOT NULL,
                password VARCHAR(255) NOT NULL,
                about TEXT NULL,
                PRIMARY KEY (id),
                UNIQUE KEY users_email_unique (email),
                UNIQUE KEY users_username_unique (username)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",
            "CREATE TABLE IF NOT EXISTS categories (
                id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
                name VARCHAR(100) NOT NULL,
                PRIMARY KEY (id),
                UNIQUE KEY categories_name_unique (name)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",
            "CREATE TABLE IF NOT EXISTS topics (
                id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
                category_id BIGINT UNSIGNED NOT NULL,
                user_id BIGINT UNSIGNED NOT NULL,
                title VARCHAR(200) NOT NULL,
                body TEXT NOT NULL,
                create_date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (id),
                KEY topics_category_date_idx (category_id, create_date),
                KEY topics_user_idx (user_id)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",
            "CREATE TABLE IF NOT EXISTS replies (
                id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
                topic_id BIGINT UNSIGNED NOT NULL,
                user_id BIGINT UNSIGNED NOT NULL,
                body TEXT NOT NULL,
                create_date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (id),
                KEY replies_topic_date_idx (topic_id, create_date),
                KEY replies_user_idx (user_id)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci"
        ];

        try {
            foreach ($tables as $sql) {
                $this->dbh->exec($sql);
            }

            $categoryCount = (int) $this->dbh->query("SELECT COUNT(*) FROM categories")->fetchColumn();
            if ($categoryCount === 0) {
                $insert = $this->dbh->prepare("INSERT INTO categories (name) VALUES (?), (?), (?)");
                $insert->execute([
                    'General Discussion',
                    'Questions & Help',
                    'Ideas & Feedback'
                ]);
            }

            $schemaReady = true;
        } catch (PDOException $e) {
            error_log('TalkingSpace schema initialization failed: ' . $e->getMessage());
            throw new RuntimeException(
                'Database schema initialization failed. Ensure the database user can create tables and insert categories.',
                0,
                $e
            );
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
