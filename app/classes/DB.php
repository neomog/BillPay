<?php declare(strict_types=1);

namespace App\classes;

use PDO;
use PDOException;

class DB
{
    protected string $hostName;
    protected string $userName;
    protected string $password;
    protected string $dbName;
    protected ?PDO $conn = null;

    public function __construct(string $dbHost, string $userName, string $dbName, string $password)
    {
        if (!empty($dbHost) && !empty($userName) && !empty($dbName) && !empty($password))
        {
            $this->hostName = $dbHost;
            $this->userName = $userName;
            $this->password = $password;
            $this->dbName = $dbName;
            $this->connect();
        } else {
            echo 'Empty fields';
        }
    }

    protected function connect(): void
    {
        try {
            $this->conn = new PDO(
                "mysql:host={$this->hostName};dbname={$this->dbName}",
                $this->userName,
                $this->password,
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
            );
        } catch (PDOException $e) {
//            echo $e->getMessage();
        }
    }

    public function executeQuery(string $query, array $params = []): bool
    {
        if ($this->conn === null) {
            return false;
        }

        try {
            $stmt = $this->conn->prepare($query);
            $stmt->execute($params);
            return true;
        } catch (PDOException $e) {
//            echo $e->getMessage();
//            return false;
        }
    }

    public function fetchAll(string $query, array $params = []): ?array
    {
        if ($this->conn === null) {
            return null;
        }

        try {
            $stmt = $this->conn->prepare($query);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
//            echo $e->getMessage();
//            return null;
        }
    }

    public function fetchRow(string $query, array $params = []): array|bool|null
    {
        if ($this->conn === null) {
            return null;
        }

        try {
            $stmt = $this->conn->prepare($query);
            $stmt->execute($params);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
//            echo $e->getMessage();
//            return null;
        }
    }

    public function getLastInsertedId(): ?int
    {
        if ($this->conn === null) {
            return null;
        }

        try {
            return (int) $this->conn->lastInsertId();
        } catch (PDOException $e) {
//            echo $e->getMessage();
//            return null;
        }
    }

    public function getConnection(): ?PDO
    {
        return $this->conn;
    }

    public function __destruct()
    {
        $this->conn = null;
    }
}
