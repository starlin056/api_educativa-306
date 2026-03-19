<?php
// model

require_once 'Model.php';

class Inscripcion extends Model
{

    protected $table = 'inscripciones';

    public function inscribir($usuario_id, $servicio_id)
    {

        $sql = "INSERT INTO inscripciones (usuario_id, servicio_id, estado)
                VALUES (:usuario_id, :servicio_id, 'pendiente')";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':usuario_id' => $usuario_id,
            ':servicio_id' => $servicio_id
        ]);
    }

    public function serviciosDeUsuario($usuario_id)
    {

        $sql = "SELECT s.*, i.estado, i.fecha_inscripcion
                FROM inscripciones i
                INNER JOIN servicios s ON s.id = i.servicio_id
                WHERE i.usuario_id = :usuario_id
                ORDER BY i.fecha_inscripcion DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':usuario_id' => $usuario_id]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function verificarInscripcion($usuario_id, $servicio_id)
    {

        $sql = "SELECT id FROM inscripciones
                WHERE usuario_id = :usuario_id
                AND servicio_id = :servicio_id";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            ':usuario_id' => $usuario_id,
            ':servicio_id' => $servicio_id
        ]);

        return $stmt->fetch();
    }
}
