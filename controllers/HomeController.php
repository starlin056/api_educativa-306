<?php
// controllers/HomeController.php

require_once __DIR__ . '/Controller.php';
require_once BASE_PATH . '/models/Service.php';
require_once BASE_PATH . '/models/Auth.php';
require_once BASE_PATH . '/models/User.php';

class HomeController extends Controller
{
    private Auth $auth;
    private Service $service;
    private User $user;

    public function __construct()
    {
        $this->auth = new Auth();
        $this->service = new Service();
        $this->user = new User();
    }

    /*
    |--------------------------------------------------------------------------
    | Página principal
    |--------------------------------------------------------------------------
    */

    public function index(): void
    {
        $services = $this->service->getAvailable(null, 6);

        $this->with([
            'title' => 'Inicio - Centro Educativo',
            'services' => $services,
            'user' => $this->auth->user()
        ]);

        $this->view('home/home');
    }

    /*
    |--------------------------------------------------------------------------
    | Página nosotros
    |--------------------------------------------------------------------------
    */

    public function about(): void
    {
        $this->with([
            'title' => 'Sobre Nosotros'
        ]);

        $this->view('nosotros/index'); // Cambiado a


    }

    /*
    |--------------------------------------------------------------------------
    | Página admisiones
    |--------------------------------------------------------------------------
    */

    public function admisiones(): void
    {
        $this->with([
            'title' => 'Admisiones'
        ]);

        $this->view('admisiones/index');
    }

    /*
    |--------------------------------------------------------------------------
    | Dashboard principal
    |--------------------------------------------------------------------------
    */

    public function dashboard(): void
    {
        if (!$this->auth->check()) {

            $_SESSION['error'] = "Debes iniciar sesión";
            $this->redirect('?page=login');
            return;
        }

        $user = $this->auth->user();
        $role = $user['rol_nombre'] ?? 'estudiante';

        switch ($role) {

            case 'admin':
                $this->adminDashboard($user);
                break;

            case 'docente':
                $this->teacherDashboard($user);
                break;

            case 'padre':
                $this->parentDashboard($user);
                break;

            default:
                $this->studentDashboard($user);
                break;
        }
    }

    /*
    |--------------------------------------------------------------------------
    | ADMIN DASHBOARD
    |--------------------------------------------------------------------------
    */

    private function adminDashboard(array $user): void
    {
        $db = $this->user->getDB();

        $stats = $this->getAdminStats($db);

        $pendingInscriptions = $db
            ->query("SELECT COUNT(*) FROM inscripciones WHERE estado='pendiente'")
            ->fetchColumn();

        $stmt = $db->query("
            SELECT u.nombre_completo, u.email, r.nombre AS rol, u.created_at
            FROM usuarios u
            JOIN roles r ON r.id = u.rol_id
            ORDER BY u.created_at DESC
            LIMIT 5
        ");

        $recentUsers = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $this->with([
            'title' => 'Panel de Administración',
            'user' => $user,
            'stats' => $stats,
            'pendingInscriptions' => $pendingInscriptions,
            'recentUsers' => $recentUsers
        ]);

        $this->view('admin/dashboard');
    }

    /*
    |--------------------------------------------------------------------------
    | DOCENTE DASHBOARD
    |--------------------------------------------------------------------------
    */

    private function teacherDashboard(array $user): void
    {
        $db = $this->user->getDB();

        $stmt = $db->prepare("
            SELECT id, nombre
            FROM aulas
            WHERE docente_id = ?
        ");

        $stmt->execute([$user['id']]);

        $courses = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $this->with([
            'title' => 'Panel Docente',
            'user' => $user,
            'courses' => $courses
        ]);

        $this->view('docente/dashboard');
    }

    /*
    |--------------------------------------------------------------------------
    | ESTUDIANTE DASHBOARD
    |--------------------------------------------------------------------------
    */

    private function studentDashboard(array $user): void
    {
        $db = $this->user->getDB();

        $stmt = $db->prepare("
            SELECT s.titulo, i.estado
            FROM inscripciones i
            JOIN servicios s ON s.id = i.servicio_id
            WHERE i.usuario_id = ?
        ");

        $stmt->execute([$user['id']]);

        $enrollments = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $this->with([
            'title' => 'Panel Estudiantil',
            'user' => $user,
            'enrollments' => $enrollments
        ]);

        $this->view('estudiante/dashboard');
    }

    /*
    |--------------------------------------------------------------------------
    | PADRE DASHBOARD
    |--------------------------------------------------------------------------
    */

    private function parentDashboard(array $user): void
    {
        $db = $this->user->getDB();

        $stmt = $db->prepare("
            SELECT u.nombre_completo, a.nombre AS aula
            FROM usuarios u
            JOIN aulas_estudiantes ae ON ae.estudiante_id = u.id
            JOIN aulas a ON a.id = ae.aula_id
            WHERE ae.padre_id = ?
        ");

        $stmt->execute([$user['id']]);

        $children = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $this->with([
            'title' => 'Portal de Padres',
            'user' => $user,
            'children' => $children
        ]);

        $this->view('padre/dashboard');
    }

    /*
    |--------------------------------------------------------------------------
    | Estadísticas admin
    |--------------------------------------------------------------------------
    */

    private function getAdminStats(PDO $db): array
    {
        $stats = [];

        $roles = ['admin', 'docente', 'estudiante', 'padre'];

        foreach ($roles as $role) {

            $stmt = $db->prepare("
                SELECT COUNT(*)
                FROM usuarios u
                JOIN roles r ON r.id = u.rol_id
                WHERE r.nombre = ? AND u.activo = 1
            ");

            $stmt->execute([$role]);

            $stats[$role] = $stmt->fetchColumn() ?: 0;
        }

        $stats['servicios'] = $db
            ->query("SELECT COUNT(*) FROM servicios WHERE disponible = 1")
            ->fetchColumn();

        return $stats;
    }
}
