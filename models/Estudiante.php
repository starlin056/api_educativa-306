<?php

require_once __DIR__ . '/Model.php';

class Estudiante extends Model
{
    protected $table = 'usuarios';

    public function getServiciosDisponibles(): array
    {
        try {
            $stmt = $this->db->query("
                SELECT id, titulo, descripcion, icono, categoria, disponible,
                       (SELECT COUNT(*) FROM inscripciones i WHERE i.servicio_id = servicios.id AND i.estado = 'aprobada') as total_inscripciones
                FROM servicios
                WHERE disponible = 1
                ORDER BY orden_mostrar ASC, titulo ASC
            ");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error getServiciosDisponibles: " . $e->getMessage());
            return [];
        }
    }

    public function getInscripciones(int $estudiante_id): array
    {
        try {
            $stmt = $this->db->prepare("
                SELECT s.id, s.titulo, s.descripcion, s.icono, s.categoria,
                       i.estado, i.fecha_inscripcion, i.notas
                FROM inscripciones i
                INNER JOIN servicios s ON i.servicio_id = s.id
                WHERE i.usuario_id = ?
                ORDER BY i.fecha_inscripcion DESC
            ");
            $stmt->execute([$estudiante_id]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error getInscripciones: " . $e->getMessage());
            return [];
        }
    }

    public function yaInscrito(int $estudiante_id, int $servicio_id): bool
    {
        try {
            $stmt = $this->db->prepare("
                SELECT id FROM inscripciones 
                WHERE usuario_id = ? AND servicio_id = ?
            ");
            $stmt->execute([$estudiante_id, $servicio_id]);
            return $stmt->fetch() !== false;
        } catch (Exception $e) {
            error_log("Error yaInscrito: " . $e->getMessage());
            return false;
        }
    }

    public function inscribirServicio(int $estudiante_id, int $servicio_id): bool
    {
        try {
            if ($this->yaInscrito($estudiante_id, $servicio_id)) {
                return false;
            }

            $stmt = $this->db->prepare("
                INSERT INTO inscripciones (usuario_id, servicio_id, estado) 
                VALUES (?, ?, 'pendiente')
            ");
            return $stmt->execute([$estudiante_id, $servicio_id]);
        } catch (Exception $e) {
            error_log("Error inscribirServicio: " . $e->getMessage());
            return false;
        }
    }

    public function getAulas(int $estudiante_id): array
    {
        try {
            $stmt = $this->db->prepare("
                SELECT a.id, a.nombre, a.created_at,
                       u.nombre_completo as docente_nombre
                FROM aulas a
                INNER JOIN aula_estudiantes ae ON a.id = ae.aula_id
                INNER JOIN usuarios u ON a.docente_id = u.id
                WHERE ae.estudiante_id = ?
                ORDER BY a.created_at DESC
            ");
            $stmt->execute([$estudiante_id]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error getAulas: " . $e->getMessage());
            return [];
        }
    }
}
