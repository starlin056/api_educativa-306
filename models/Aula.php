<?php

class Aula extends Model
{
    protected $table = 'aulas';

    /**
     * Obtiene todas las aulas de un docente
     */
    public function getByDocente(int $docente_id): array
    {
        try {
            $stmt = $this->db->prepare("
                SELECT 
                    a.id,
                    a.nombre,
                    a.docente_id,
                    a.created_at,
                    COUNT(ae.estudiante_id) as total_estudiantes
                FROM {$this->table} a
                LEFT JOIN aula_estudiantes ae ON a.id = ae.aula_id
                WHERE a.docente_id = ?
                GROUP BY a.id, a.nombre, a.docente_id, a.created_at
                ORDER BY a.created_at DESC
            ");
            $stmt->execute([$docente_id]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error en getByDocente: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Obtiene un aula específica verificando que pertenezca al docente
     */
    public function getByIdAndDocente(int $aula_id, int $docente_id): ?array
    {
        try {
            $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE id = ? AND docente_id = ?");
            $stmt->execute([$aula_id, $docente_id]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result ?: null;
        } catch (Exception $e) {
            error_log("Error en getByIdAndDocente: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Crea una nueva aula
     */
    public function createAula(string $nombre, int $docente_id): int
    {
        try {
            $stmt = $this->db->prepare("INSERT INTO {$this->table} (nombre, docente_id) VALUES (?, ?)");
            $stmt->execute([$nombre, $docente_id]);
            return (int)$this->db->lastInsertId();
        } catch (Exception $e) {
            error_log("Error en createAula: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Obtiene los estudiantes ya inscritos en un aula
     */
    public function getEstudiantesInscritos(int $aula_id): array
    {
        try {
            $stmt = $this->db->prepare("
                SELECT u.id, u.nombre_completo, u.email, u.telefono
                FROM aula_estudiantes ae
                INNER JOIN usuarios u ON u.id = ae.estudiante_id
                WHERE ae.aula_id = ? AND u.activo = 1
                ORDER BY u.nombre_completo ASC
            ");
            $stmt->execute([$aula_id]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error en getEstudiantesInscritos: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Obtiene todos los estudiantes disponibles para inscribir
     */
    public function getEstudiantesDisponibles(int $aula_id): array
    {
        try {
            $stmt = $this->db->prepare("
                SELECT u.id, u.nombre_completo, u.email
                FROM usuarios u
                INNER JOIN roles r ON u.rol_id = r.id
                WHERE r.nombre = 'estudiante' 
                  AND u.activo = 1
                  AND u.id NOT IN (
                      SELECT estudiante_id FROM aula_estudiantes WHERE aula_id = ?
                  )
                ORDER BY u.nombre_completo ASC
            ");
            $stmt->execute([$aula_id]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error en getEstudiantesDisponibles: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Agrega un estudiante al aula
     */
    public function addEstudiante(int $aula_id, int $estudiante_id): bool
    {
        try {
            // Verificar duplicado
            $check = $this->db->prepare("SELECT id FROM aula_estudiantes WHERE aula_id = ? AND estudiante_id = ?");
            $check->execute([$aula_id, $estudiante_id]);
            if ($check->fetch()) {
                return false;
            }

            $stmt = $this->db->prepare("INSERT INTO aula_estudiantes (aula_id, estudiante_id) VALUES (?, ?)");
            return $stmt->execute([$aula_id, $estudiante_id]);
        } catch (Exception $e) {
            error_log("Error en addEstudiante: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Elimina un estudiante del aula
     */
    public function removeEstudiante(int $aula_id, int $estudiante_id): bool
    {
        try {
            $stmt = $this->db->prepare("DELETE FROM aula_estudiantes WHERE aula_id = ? AND estudiante_id = ?");
            return $stmt->execute([$aula_id, $estudiante_id]);
        } catch (Exception $e) {
            error_log("Error en removeEstudiante: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Obtiene servicios disponibles
     */
    public function getServiciosDisponibles(): array
    {
        try {
            $stmt = $this->db->query("
                SELECT id, titulo, descripcion, icono, categoria, disponible, orden_mostrar,
                       (SELECT COUNT(*) FROM inscripciones i WHERE i.servicio_id = servicios.id AND i.estado = 'aprobada') as total_inscripciones
                FROM servicios
                WHERE disponible = 1
                ORDER BY orden_mostrar ASC, titulo ASC
            ");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error en getServiciosDisponibles: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Obtiene servicios inscritos por el aula
     */
    public function getServiciosInscritos(int $aula_id): array
    {
        try {
            $stmt = $this->db->prepare("
                SELECT DISTINCT s.id, s.titulo, s.icono, i.estado
                FROM servicios s
                INNER JOIN inscripciones i ON s.id = i.servicio_id
                INNER JOIN aula_estudiantes ae ON i.usuario_id = ae.estudiante_id
                WHERE ae.aula_id = ?
                ORDER BY s.titulo ASC
            ");
            $stmt->execute([$aula_id]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error en getServiciosInscritos: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Inscribe todos los estudiantes del aula a un servicio
     */
    public function inscribirAulaAServicio(int $aula_id, int $servicio_id): int
    {
        try {
            $estudiantes = $this->getEstudiantesInscritos($aula_id);
            if (empty($estudiantes)) {
                return 0;
            }

            $count = 0;
            foreach ($estudiantes as $estudiante) {
                $stmt = $this->db->prepare("
                    INSERT IGNORE INTO inscripciones (usuario_id, servicio_id, estado, notas) 
                    VALUES (?, ?, 'aprobada', 'Inscripción desde aula')
                ");
                if ($stmt->execute([$estudiante['id'], $servicio_id])) {
                    $count++;
                }
            }
            return $count;
        } catch (Exception $e) {
            error_log("Error en inscribirAulaAServicio: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Obtiene estadísticas del aula
     */
    public function getEstadisticas(int $aula_id): array
    {
        try {
            $stmt = $this->db->prepare("
                SELECT 
                    COUNT(DISTINCT ae.estudiante_id) as total_estudiantes,
                    COUNT(DISTINCT i.servicio_id) as total_servicios
                FROM {$this->table} a
                LEFT JOIN aula_estudiantes ae ON a.id = ae.aula_id
                LEFT JOIN inscripciones i ON ae.estudiante_id = i.usuario_id AND i.estado = 'aprobada'
                WHERE a.id = ?
            ");
            $stmt->execute([$aula_id]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            return [
                'total_estudiantes' => $result['total_estudiantes'] ?? 0,
                'total_servicios' => $result['total_servicios'] ?? 0
            ];
        } catch (Exception $e) {
            error_log("Error en getEstadisticas: " . $e->getMessage());
            return ['total_estudiantes' => 0, 'total_servicios' => 0];
        }
    }
}
