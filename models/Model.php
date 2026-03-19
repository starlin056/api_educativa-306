<?php


require_once __DIR__ . '/../config/database.php';

abstract class Model
{

    protected $table;


    protected $db;

    public function __construct()
    {
        // Database::getInstance() devuelve directamente PDO
        $this->db = Database::getInstance();

        if (empty($this->table)) {
            throw new Exception("La clase " . static::class . " debe definir la propiedad \$table");
        }
    }



    public function getDB(): PDO
    {
        return $this->db;
    }



    public function findAll(string $orderBy = 'id', string $direction = 'ASC', ?int $limit = null): array
    {
        $sql = "SELECT * FROM {$this->table} ORDER BY {$orderBy} {$direction}";
        if ($limit) $sql .= " LIMIT {$limit}";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function findById(int $id)
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function findBy(array $conditions): array
    {
        $where = [];
        $params = [];

        foreach ($conditions as $column => $value) {
            $where[] = "{$column} = ?";
            $params[] = $value;
        }

        $sql = "SELECT * FROM {$this->table} WHERE " . implode(' AND ', $where);
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function findOne(array $conditions)
    {
        $results = $this->findBy($conditions);
        return $results[0] ?? null;
    }

    public function create(array $data)
    {
        if (empty($data)) {
            throw new Exception("No hay datos para insertar");
        }

        $columns = implode(', ', array_keys($data));
        $placeholders = implode(', ', array_fill(0, count($data), '?'));

        $sql = "INSERT INTO {$this->table} ({$columns}) VALUES ({$placeholders})";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(array_values($data));

        return $this->db->lastInsertId();
    }

    public function update(int $id, array $data): int
    {
        if (empty($data)) {
            return 0;
        }

        $set = implode(', ', array_map(fn($col) => "{$col} = ?", array_keys($data)));
        $sql = "UPDATE {$this->table} SET {$set} WHERE id = ?";

        $params = array_values($data);
        $params[] = $id;

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);

        return $stmt->rowCount();
    }

    public function delete(int $id): int
    {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->rowCount();
    }

    public function count(array $conditions = []): int
    {
        if (empty($conditions)) {
            $sql = "SELECT COUNT(*) FROM {$this->table}";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
        } else {
            $where = [];
            $params = [];
            foreach ($conditions as $column => $value) {
                $where[] = "{$column} = ?";
                $params[] = $value;
            }
            $sql = "SELECT COUNT(*) FROM {$this->table} WHERE " . implode(' AND ', $where);
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
        }
        return (int) $stmt->fetchColumn();
    }


    public function find($idOrConditions)
    {
        if (is_array($idOrConditions)) {
            return $this->findOne($idOrConditions);
        }
        return $this->findById((int)$idOrConditions);
    }
}
