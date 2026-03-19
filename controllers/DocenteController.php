<?php

require_once __DIR__ . '/Controller.php';
require_once __DIR__ . '/../models/Aula.php';
require_once BASE_PATH . '/middleware/Csrf.php';

class DocenteController extends Controller
{
    private Aula $aulaModel;

    public function __construct()
    {
        parent::__construct();
        $this->aulaModel = new Aula();

        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        if (empty($_SESSION['_token'])) {
            $_SESSION['_token'] = bin2hex(random_bytes(32));
        }
    }

    public function dashboard()
    {
        if (!$this->verificarAuth(['admin', 'docente'])) {
            return;
        }

        $docente_id = $_SESSION['user_id'];

        // DEBUG TEMPORAL
        error_log("DOCENTE ID EN SESSION: " . $docente_id);
        $aulas = $this->aulaModel->getByDocente($docente_id);
        error_log("AULAS ENCONTRADAS: " . count($aulas));
        error_log(print_r($aulas, true));
        // FIN DEBUG

        $this->with([
            'aulas' => $aulas,
            'titulo' => 'Panel del Docente'
        ])->view('docente/dashboard');
    }

    public function aula()
    {
        if (!$this->verificarAuth(['admin', 'docente'])) {
            return;
        }

        $aula_id = $_GET['id'] ?? null;
        $docente_id = $_SESSION['user_id'];

        if (!$aula_id) {
            $_SESSION['error'] = "ID de aula no especificado.";
            header("Location: index.php?page=docente/dashboard");
            exit;
        }

        $aula = $this->aulaModel->getByIdAndDocente($aula_id, $docente_id);

        if (!$aula) {
            $_SESSION['error'] = "No tienes permiso para ver esta aula o no existe.";
            header("Location: index.php?page=docente/dashboard");
            exit;
        }

        $estudiantes = $this->aulaModel->getEstudiantesInscritos($aula_id);
        $estudiantes_disponibles = $this->aulaModel->getEstudiantesDisponibles($aula_id);
        $servicios = $this->aulaModel->getServiciosDisponibles();
        $servicios_inscritos = $this->aulaModel->getServiciosInscritos($aula_id);
        $estadisticas = $this->aulaModel->getEstadisticas($aula_id);

        $this->with([
            'aula' => $aula,
            'estudiantes' => $estudiantes,
            'estudiantes_disponibles' => $estudiantes_disponibles,
            'servicios' => $servicios,
            'servicios_inscritos' => $servicios_inscritos,
            'estadisticas' => $estadisticas,
            'titulo' => 'Gestionar Aula: ' . $aula['nombre']
        ])->view('docente/aula');
    }

    public function store()
    {
        if (!$this->verificarAuth(['admin', 'docente'])) {
            return;
        }

        if (!$this->verificarCSRF()) {
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = trim($_POST['nombre'] ?? '');
            $docente_id = $_SESSION['user_id'];

            if (empty($nombre)) {
                $_SESSION['error'] = "El nombre del aula es obligatorio.";
                header("Location: index.php?page=docente/dashboard");
                exit;
            }

            if (empty($docente_id)) {
                $_SESSION['error'] = "Error de sesión. Inicia sesión nuevamente.";
                header("Location: index.php?page=docente/dashboard");
                exit;
            }

            $aulaId = $this->aulaModel->createAula($nombre, $docente_id);

            if ($aulaId > 0) {
                $_SESSION['success'] = "Aula creada correctamente.";
            } else {
                $_SESSION['error'] = "Error al crear el aula.";
            }
        }

        header("Location: index.php?page=docente/dashboard");
        exit;
    }

    public function addStudent()
    {
        if (!$this->verificarAuth(['admin', 'docente'])) {
            return;
        }

        if (!$this->verificarCSRF()) {
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $aula_id = $_POST['aula_id'] ?? null;
            $estudiante_id = $_POST['estudiante_id'] ?? null;
            $docente_id = $_SESSION['user_id'];

            if ($aula_id && $estudiante_id && $this->aulaModel->getByIdAndDocente($aula_id, $docente_id)) {
                if ($this->aulaModel->addEstudiante($aula_id, $estudiante_id)) {
                    $_SESSION['success'] = "Estudiante agregado al aula.";
                } else {
                    $_SESSION['error'] = "El estudiante ya está en el aula.";
                }
            } else {
                $_SESSION['error'] = "Datos inválidos o no tienes permiso.";
            }
        }

        $redirect = $aula_id ? "index.php?page=docente/aulas&action=view&id=$aula_id" : "index.php?page=docente/dashboard";
        header("Location: $redirect");
        exit;
    }

    public function removeStudent()
    {
        if (!$this->verificarAuth(['admin', 'docente'])) {
            return;
        }

        if (!$this->verificarCSRF()) {
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $aula_id = $_POST['aula_id'] ?? null;
            $estudiante_id = $_POST['estudiante_id'] ?? null;
            $docente_id = $_SESSION['user_id'];

            if ($aula_id && $estudiante_id && $this->aulaModel->getByIdAndDocente($aula_id, $docente_id)) {
                if ($this->aulaModel->removeEstudiante($aula_id, $estudiante_id)) {
                    $_SESSION['success'] = "Estudiante eliminado del aula.";
                } else {
                    $_SESSION['error'] = "Error al eliminar el estudiante.";
                }
            } else {
                $_SESSION['error'] = "Datos inválidos.";
            }
        }

        $redirect = $aula_id ? "index.php?page=docente/aulas&action=view&id=$aula_id" : "index.php?page=docente/dashboard";
        header("Location: $redirect");
        exit;
    }

    public function inscribirServicio()
    {
        if (!$this->verificarAuth(['admin', 'docente'])) {
            return;
        }

        if (!$this->verificarCSRF()) {
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $aula_id = $_POST['aula_id'] ?? null;
            $servicio_id = $_POST['servicio_id'] ?? null;
            $docente_id = $_SESSION['user_id'];

            if ($aula_id && $servicio_id && $this->aulaModel->getByIdAndDocente($aula_id, $docente_id)) {
                $cantidad = $this->aulaModel->inscribirAulaAServicio($aula_id, $servicio_id);
                if ($cantidad > 0) {
                    $_SESSION['success'] = "$cantidad estudiantes inscritos al servicio.";
                } else {
                    $_SESSION['error'] = "No hay estudiantes en el aula para inscribir.";
                }
            } else {
                $_SESSION['error'] = "Datos inválidos.";
            }
        }

        $redirect = $aula_id ? "index.php?page=docente/aulas&action=view&id=$aula_id" : "index.php?page=docente/dashboard";
        header("Location: $redirect");
        exit;
    }

    private function verificarAuth(array $allowedRoles): bool
    {
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?page=login");
            exit;
        }

        $rol = $_SESSION['rol'] ?? null;

        if (!$rol || !in_array($rol, $allowedRoles)) {
            header("Location: index.php?page=home");
            exit;
        }

        return true;
    }

    private function verificarCSRF(): bool
    {
        if (!isset($_POST['_token']) || !Csrf::validate($_POST['_token'])) {
            $_SESSION['error'] = "Token de seguridad inválido.";
            header("Location: index.php?page=docente/dashboard");
            exit;
        }

        return true;
    }
}
