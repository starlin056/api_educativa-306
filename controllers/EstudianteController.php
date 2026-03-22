<?php

require_once __DIR__ . '/Controller.php';
require_once __DIR__ . '/../models/Estudiante.php';
require_once BASE_PATH . '/middleware/Csrf.php';

class EstudianteController extends Controller
{
    private Estudiante $estudianteModel;

    public function __construct()
    {
        parent::__construct();
        $this->estudianteModel = new Estudiante();

        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        if (empty($_SESSION['_token'])) {
            $_SESSION['_token'] = bin2hex(random_bytes(32));
        }
    }

    public function cursos()
    {
        if (!$this->verificarAuth(['admin', 'estudiante'])) {
            return;
        }

        $estudiante_id = $_SESSION['user_id'];
        
        // Use matching variables for the cursos.php view
        $inscripciones = $this->estudianteModel->getInscripciones($estudiante_id);

        $this->with([
            'enrollments' => $inscripciones,
            'user' => [
                'nombre' => $_SESSION['nombre'] ?? 'Estudiante',
                'nombre_completo' => $_SESSION['nombre'] ?? 'Estudiante'
            ],
            'titulo' => 'Mis Cursos'
        ])->view('estudiante/cursos');
    }

    public function dashboard()
    {
        if (!$this->verificarAuth(['admin', 'estudiante'])) {
            return;
        }

        $estudiante_id = $_SESSION['user_id'];

        $servicios = $this->estudianteModel->getServiciosDisponibles();
        $inscripciones = $this->estudianteModel->getInscripciones($estudiante_id);
        $aulas = $this->estudianteModel->getAulas($estudiante_id);

        // Contar estados de inscripciones
        $pendientes = 0;
        $aprobadas = 0;
        foreach ($inscripciones as $ins) {
            if ($ins['estado'] === 'pendiente') $pendientes++;
            if ($ins['estado'] === 'aprobada') $aprobadas++;
        }

        $this->with([
            'servicios' => $servicios,
            'inscripciones' => $inscripciones,
            'aulas' => $aulas,
            'pendientes' => $pendientes,
            'aprobadas' => $aprobadas,
            'titulo' => 'Panel del Estudiante'
        ])->view('estudiante/dashboard');
    }

    public function inscribir()
    {
        if (!$this->verificarAuth(['admin', 'estudiante'])) {
            return;
        }

        if (!$this->verificarCSRF()) {
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $estudiante_id = $_SESSION['user_id'];
            $servicio_id = $_POST['servicio_id'] ?? null;

            if ($servicio_id) {
                if ($this->estudianteModel->yaInscrito($estudiante_id, $servicio_id)) {
                    $_SESSION['error'] = "Ya estás inscrito a este servicio.";
                } else {
                    if ($this->estudianteModel->inscribirServicio($estudiante_id, $servicio_id)) {
                        $_SESSION['success'] = "Inscripción realizada. Espera aprobación del administrador.";
                    } else {
                        $_SESSION['error'] = "Error al inscribirse.";
                    }
                }
            } else {
                $_SESSION['error'] = "Servicio no válido.";
            }
        }

        header("Location: index.php?page=estudiante/dashboard");
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
            header("Location: index.php?page=estudiante/dashboard");
            exit;
        }

        return true;
    }
}
