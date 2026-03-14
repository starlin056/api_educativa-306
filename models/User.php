<?php
// models/User.php
// Gestión de usuarios del sistema

require_once __DIR__ . '/Model.php';

class User extends Model
{
    /**
     * Nombre de la tabla en la base de datos
     * @var string
     */
    protected $table = 'usuarios';

    /**
     * Buscar usuario por email con información del rol
     * 
     * @param string $email Email del usuario
     * @return array|null Usuario encontrado o null
     */
    public function findByEmail(string $email): ?array
    {
        $stmt = $this->db->prepare("
            SELECT 
                u.id,
                u.nombre_completo,
                u.email,
                u.password_hash,
                u.rol_id,
                u.activo,
                u.telefono,
                u.fecha_nacimiento,
                u.last_login,
                u.created_at,
                u.updated_at,
                r.nombre as rol_nombre,
                r.descripcion as rol_descripcion
            FROM {$this->table} u 
            INNER JOIN roles r ON u.rol_id = r.id 
            WHERE u.email = ? AND u.activo = 1
            LIMIT 1
        ");
        $stmt->execute([$email]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    /**
     * Verificar si un email ya está registrado
     * 
     * @param string $email Email a verificar
     * @param int|null $excludeId ID a excluir (para edición)
     * @return bool True si existe, false si no
     */
    public function emailExists(string $email, ?int $excludeId = null): bool
    {
        $sql = "SELECT COUNT(*) FROM {$this->table} WHERE email = ?";
        $params = [$email];

        if ($excludeId !== null) {
            $sql .= " AND id != ?";
            $params[] = $excludeId;
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return (int) $stmt->fetchColumn() > 0;
    }

    /**
     * Actualizar fecha de último login
     * 
     * @param int $userId ID del usuario
     * @return bool True si se actualizó correctamente
     */
    public function updateLastLogin(int $userId): bool
    {
        $stmt = $this->db->prepare("
            UPDATE {$this->table} 
            SET last_login = NOW(), updated_at = NOW() 
            WHERE id = ?
        ");
        return $stmt->execute([$userId]);
    }

    /**
     * Obtener usuarios filtrados por rol
     * 
     * @param string $roleName Nombre del rol (admin, docente, estudiante, padre)
     * @param bool $onlyActive Solo usuarios activos
     * @return array Lista de usuarios
     */
    public function getByRole(string $roleName, bool $onlyActive = true): array
    {
        $sql = "
            SELECT 
                u.id,
                u.nombre_completo,
                u.email,
                u.activo,
                u.created_at,
                r.nombre as rol_nombre
            FROM {$this->table} u 
            INNER JOIN roles r ON u.rol_id = r.id 
            WHERE r.nombre = ?
        ";

        if ($onlyActive) {
            $sql .= " AND u.activo = 1";
        }

        $sql .= " ORDER BY u.nombre_completo ASC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$roleName]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Buscar usuarios por término de búsqueda (nombre o email)
     * 
     * @param string $term Término a buscar
     * @param string|null $roleFilter Filtrar por rol específico
     * @return array Resultados de la búsqueda
     */
    public function search(string $term, ?string $roleFilter = null): array
    {
        $sql = "
            SELECT 
                u.id,
                u.nombre_completo,
                u.email,
                u.activo,
                u.created_at,
                r.nombre as rol_nombre
            FROM {$this->table} u 
            INNER JOIN roles r ON u.rol_id = r.id 
            WHERE (u.nombre_completo LIKE ? OR u.email LIKE ?)
        ";

        $params = ["%{$term}%", "%{$term}%"];

        if ($roleFilter && $roleFilter !== 'all') {
            $sql .= " AND r.nombre = ?";
            $params[] = $roleFilter;
        }

        $sql .= " ORDER BY u.nombre_completo ASC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Obtener usuario por ID con información completa del rol
     * 
     * @param int $id ID del usuario
     * @return array|null Usuario encontrado o null
     */
    public function findByIdWithRole(int $id): ?array
    {
        $stmt = $this->db->prepare("
            SELECT 
                u.*,
                r.nombre as rol_nombre,
                r.descripcion as rol_descripcion,
                r.permisos as rol_permisos
            FROM {$this->table} u 
            INNER JOIN roles r ON u.rol_id = r.id 
            WHERE u.id = ?
            LIMIT 1
        ");
        $stmt->execute([$id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    /**
     * Contar usuarios por rol
     * 
     * @param string $roleName Nombre del rol
     * @param bool $onlyActive Solo contar activos
     * @return int Cantidad de usuarios
     */
    public function countByRole(string $roleName, bool $onlyActive = true): int
    {
        $sql = "
            SELECT COUNT(*) 
            FROM {$this->table} u 
            INNER JOIN roles r ON u.rol_id = r.id 
            WHERE r.nombre = ?
        ";

        if ($onlyActive) {
            $sql .= " AND u.activo = 1";
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$roleName]);
        return (int) $stmt->fetchColumn();
    }

    /**
     * Desactivar usuario (soft delete)
     * 
     * @param int $id ID del usuario a desactivar
     * @return int Número de filas afectadas
     */
    public function deactivate(int $id): int
    {
        return $this->update($id, ['activo' => 0]);
    }

    /**
     * Activar usuario
     * 
     * @param int $id ID del usuario a activar
     * @return int Número de filas afectadas
     */
    public function activate(int $id): int
    {
        return $this->update($id, ['activo' => 1]);
    }

    /**
     * Alternar estado de usuario (activar/desactivar)
     * 
     * @param int $id ID del usuario
     * @return array Resultado con nuevo estado
     */
    public function toggleStatus(int $id): array
    {
        $user = $this->findById($id);

        if (!$user) {
            return ['success' => false, 'message' => 'Usuario no encontrado'];
        }

        $newStatus = !$user['activo'];
        $updated = $this->update($id, ['activo' => $newStatus ? 1 : 0]);

        return [
            'success' => (bool) $updated,
            'message' => $newStatus ? 'Usuario activado' : 'Usuario desactivado',
            'new_status' => $newStatus
        ];
    }

    /**
     * Obtener todos los usuarios con paginación
     * 
     * @param int $page Número de página
     * @param int $perPage Registros por página
     * @param string $orderBy Campo para ordenar
     * @param string $direction Dirección del orden (ASC/DESC)
     * @return array Datos paginados
     */
    public function getAllPaginated(int $page = 1, int $perPage = 10, string $orderBy = 'nombre_completo', string $direction = 'ASC'): array
    {
        $offset = ($page - 1) * $perPage;

        // Validar campos de ordenamiento para prevenir SQL injection
        $allowedOrder = ['id', 'nombre_completo', 'email', 'created_at'];
        $orderBy = in_array($orderBy, $allowedOrder) ? $orderBy : 'nombre_completo';
        $direction = strtoupper($direction) === 'DESC' ? 'DESC' : 'ASC';

        // Obtener total de registros
        $totalStmt = $this->db->prepare("SELECT COUNT(*) FROM {$this->table}");
        $totalStmt->execute();
        $total = (int) $totalStmt->fetchColumn();

        // Obtener registros paginados
        $sql = "
            SELECT 
                u.id,
                u.nombre_completo,
                u.email,
                u.activo,
                u.created_at,
                r.nombre as rol_nombre
            FROM {$this->table} u 
            INNER JOIN roles r ON u.rol_id = r.id 
            ORDER BY {$orderBy} {$direction}
            LIMIT :limit OFFSET :offset
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return [
            'data' => $users,
            'pagination' => [
                'current_page' => $page,
                'per_page' => $perPage,
                'total' => $total,
                'total_pages' => ceil($total / $perPage),
                'has_next' => $page < ceil($total / $perPage),
                'has_prev' => $page > 1
            ]
        ];
    }

    /**
     * Actualizar rol de usuario
     * 
     * @param int $userId ID del usuario
     * @param int $newRoleId Nuevo ID de rol
     * @return bool True si se actualizó correctamente
     */
    public function updateRole(int $userId, int $newRoleId): bool
    {
        // Verificar que el rol exista
        $stmt = $this->db->prepare("SELECT id FROM roles WHERE id = ?");
        $stmt->execute([$newRoleId]);

        if (!$stmt->fetch()) {
            return false;
        }

        $stmt = $this->db->prepare("
            UPDATE {$this->table} 
            SET rol_id = ?, updated_at = NOW() 
            WHERE id = ?
        ");
        return $stmt->execute([$newRoleId, $userId]);
    }

    /**
     * Obtener estadísticas generales de usuarios
     * 
     * @return array Estadísticas por rol y estado
     */
    public function getStats(): array
    {
        $stats = [];

        // Total general
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM {$this->table}");
        $stmt->execute();
        $stats['total'] = (int) $stmt->fetchColumn();

        // Activos vs Inactivos
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM {$this->table} WHERE activo = 1");
        $stmt->execute();
        $stats['activos'] = (int) $stmt->fetchColumn();
        $stats['inactivos'] = $stats['total'] - $stats['activos'];

        // Por rol
        $stmt = $this->db->query("
            SELECT r.nombre, COUNT(u.id) as total
            FROM roles r
            LEFT JOIN {$this->table} u ON r.id = u.rol_id AND u.activo = 1
            GROUP BY r.id, r.nombre
            ORDER BY r.nombre
        ");
        $stats['por_rol'] = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);

        // Registrados últimos 30 días
        $stmt = $this->db->prepare("
            SELECT COUNT(*) FROM {$this->table} 
            WHERE created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)
        ");
        $stmt->execute();
        $stats['nuevos_30dias'] = (int) $stmt->fetchColumn();

        return $stats;
    }

    /**
     * Exportar usuarios a formato para CSV/Excel
     * 
     * @param string|null $roleFilter Filtrar por rol
     * @return array Datos listos para exportar
     */
    public function exportData(?string $roleFilter = null): array
    {
        $sql = "
            SELECT 
                u.id as 'ID',
                u.nombre_completo as 'Nombre Completo',
                u.email as 'Email',
                u.telefono as 'Teléfono',
                r.nombre as 'Rol',
                CASE WHEN u.activo = 1 THEN 'Activo' ELSE 'Inactivo' END as 'Estado',
                DATE_FORMAT(u.created_at, '%d/%m/%Y %H:%i') as 'Fecha Registro',
                DATE_FORMAT(u.last_login, '%d/%m/%Y %H:%i') as 'Último Acceso'
            FROM {$this->table} u 
            INNER JOIN roles r ON u.rol_id = r.id 
        ";

        $params = [];

        if ($roleFilter && $roleFilter !== 'all') {
            $sql .= " WHERE r.nombre = ?";
            $params[] = $roleFilter;
        }

        $sql .= " ORDER BY u.nombre_completo ASC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
