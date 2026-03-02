<?php
// ============================================================
// Base Model — Aurora Restaurant
// ============================================================

abstract class Model
{

    protected PDO $db;
    protected string $table = '';

    public function __construct()
    {
        $this->db = getDB();
    }

    protected function query(string $sql, array $params = []): PDOStatement
    {
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    protected function findAll(string $sql, array $params = []): array
    {
        return $this->query($sql, $params)->fetchAll();
    }

    protected function findOne(string $sql, array $params = []): ?array
    {
        $result = $this->query($sql, $params)->fetch();
        return $result !== false ? $result : null;
    }

    protected function execute(string $sql, array $params = []): int
    {
        $stmt = $this->query($sql, $params);
        return $stmt->rowCount();
    }

    protected function lastInsertId(): string
    {
        return $this->db->lastInsertId();
    }
}
