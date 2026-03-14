<?php
// models/Service.php
// Gestión de servicios educativos
// @phpstan-ignore-file

require_once __DIR__ . '/Model.php';

class Service extends Model {
    protected $table = 'servicios';  // ← SIN tipo "string"
    
    /**
     * Obtener servicios disponibles para mostrar en home
     */
    public function getAvailable(?string $category = null, ?int $limit = null): array {
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
        return $stmt->fetchAll();
    }
    
    /**
     * Buscar servicios por término (para admin)
     */
    public function search(string $term): array {
        $stmt = $this->db->prepare("
            SELECT * FROM {$this->table} 
            WHERE titulo LIKE ? OR descripcion LIKE ?
            ORDER BY titulo ASC
        ");
        $search = "%{$term}%";
        $stmt->execute([$search, $search]);
        return $stmt->fetchAll();
    }
    
    /**
     * Cambiar estado de disponibilidad
     */
    public function toggleAvailability(int $id, bool $available): int {
        return $this->update($id, ['disponible' => $available ? 1 : 0]);
    }
    
    /**
     * Obtener servicios por categoría
     */
    public function getByCategory(string $category): array {
        return $this->findBy(['categoria' => $category]);
    }

    public function all(): array
{
    $db = $this->getConnection();

    $stmt = $db->query("
        SELECT *
        FROM servicios
        ORDER BY orden_mostrar ASC
    ");

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
}