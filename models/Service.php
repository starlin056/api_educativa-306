<?php

require_once __DIR__ . '/Model.php';

class Service extends Model
{
    protected $table = 'servicios';





    public function countAvailable()
    {
        $stmt = $this->db->query("
        SELECT COUNT(*) FROM servicios WHERE disponible=1
    ");

        return $stmt->fetchColumn();
    }

    public function getAvailable(?string $category = null, ?int $limit = null): array
    {
        $sql = "SELECT * FROM {$this->table} WHERE disponible = 1";
        $params = [];

        if ($category) {
            $sql .= " AND categoria = ?";
            $params[] = $category;
        }

        $sql .= " ORDER BY orden_mostrar ASC, titulo ASC";

        if ($limit) {
            $sql .= " LIMIT ?";
            $params[] = $limit;
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function getByCategory(string $category): array
    {
        $stmt = $this->db->prepare("
            SELECT * FROM {$this->table} 
            WHERE categoria = ? 
            ORDER BY orden_mostrar ASC, titulo ASC
        ");
        $stmt->execute([$category]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function search(string $term): array
    {
        $stmt = $this->db->prepare("
            SELECT * FROM {$this->table} 
            WHERE titulo LIKE ? OR descripcion LIKE ?
            ORDER BY orden_mostrar ASC, titulo ASC
        ");
        $search = "%{$term}%";
        $stmt->execute([$search, $search]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function toggleAvailability(int $id, bool $available): int
    {
        return $this->update($id, ['disponible' => $available ? 1 : 0]);
    }


    public function all(): array
    {

        $sql = "SELECT *
            FROM {$this->table}
            ORDER BY orden_mostrar ASC";

        $stmt = $this->db->query($sql);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function disponibles()
    {

        $sql = "SELECT *
            FROM servicios
            WHERE disponible = 1
            ORDER BY orden_mostrar";

        $stmt = $this->db->query($sql);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function buscar($id)
    {

        $sql = "SELECT * FROM servicios WHERE id = :id";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([':id' => $id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
