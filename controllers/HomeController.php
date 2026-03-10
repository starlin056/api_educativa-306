<?php
// controllers/HomeController.php
// Controlador para página principal y dashboards por rol
// @phpstan-ignore-file

require_once __DIR__ . '/Controller.php';
require_once BASE_PATH . '/models/Service.php';
require_once BASE_PATH . '/models/Auth.php';
require_once BASE_PATH . '/models/User.php';

class HomeController extends Controller {

    private Auth $auth;
    private Service $service;
    private User $user;

    public function __construct() {
        $this->auth = new Auth();
        $this->service = new Service();
        $this->user = new User();
    }

    // Página principal pública
    public function index(): void {

        $services = $this->service->getAvailable(null,6);

        $this->with([
            'title' => 'Inicio - Centro Educativo',
            'services' => $services,
            'user' => $this->auth->user()
        ]);

        $this->view('home/home');
    }

    // Dashboard general
    public function dashboard(?string $role = null): void {

        if(!$this->auth->check()){

            $_SESSION['error'] = "Debes iniciar sesión";

            $this->redirect('?page=login');
            return;
        }

        $user = $this->auth->user();

        $userRole = $user['rol_nombre'] ?? 'estudiante';

        if($role && $role != $userRole && $userRole != 'admin'){

            $_SESSION['error'] = "No tienes permiso";

            $this->redirect('?page=home');
            return;
        }

        switch($userRole){

            case 'admin':
                $this->adminDashboard();
            break;

            case 'docente':
                $this->teacherDashboard();
            break;

            case 'padre':
                $this->parentDashboard();
            break;

            default:
                $this->studentDashboard();
            break;
        }
    }

    // ================= DASHBOARDS =================

   private function adminDashboard(): void{

    $stats = $this->getAdminStats();

    $services = $this->service->all(); // ← cargar servicios

    $this->with([
        'title'=>'Panel de Administración',
        'user'=>$this->auth->user(),
        'stats'=>$stats,
        'services'=>$services
    ]);

    $this->view('admin/dashboard');
}

    private function teacherDashboard(): void{

        $user = $this->auth->user();

        $courses = $this->getTeacherCourses($user['id']);

        $this->with([
            'title'=>'Panel Docente',
            'user'=>$user,
            'courses'=>$courses
        ]);

        $this->view('docente/dashboard');
    }

    private function studentDashboard(): void{

        $user = $this->auth->user();

        $enrollments = $this->getStudentEnrollments($user['id']);

        $this->with([
            'title'=>'Panel Estudiantil',
            'user'=>$user,
            'enrollments'=>$enrollments
        ]);

        $this->view('estudiante/dashboard');
    }

    private function parentDashboard(): void{

        $user = $this->auth->user();

        $children = $this->getParentChildren($user['id']);

        $this->with([
            'title'=>'Portal de Padres',
            'user'=>$user,
            'children'=>$children
        ]);

        $this->view('padre/dashboard');
    }

    // Página nosotros
    public function about(): void{

        $this->with([
            'title'=>'Nosotros - Centro Educativo'
        ]);

        $this->view('nosotros/index');
    }

    // ================== MÉTODOS PRIVADOS ==================

    private function getAdminStats(): array{

        $db = $this->user->getConnection();

        $stats = [];

        $roles = ['admin','docente','estudiante','padre'];

        foreach($roles as $role){

            $stmt = $db->prepare("
            SELECT COUNT(*) 
            FROM usuarios u
            INNER JOIN roles r ON r.id = u.rol_id
            WHERE r.nombre = ?
            AND u.activo = 1
            ");

            $stmt->execute([$role]);

            $stats[$role] = $stmt->fetchColumn() ?? 0;
        }

        $stmt = $db->prepare("SELECT COUNT(*) FROM servicios WHERE disponible = 1");
        $stmt->execute();

        $stats['servicios'] = $stmt->fetchColumn() ?? 0;

        return $stats;
    }

    private function getTeacherCourses(int $teacherId): array{

        return [

            [
                'id'=>1,
                'nombre'=>'Matemáticas Avanzadas',
                'estudiantes'=>25
            ],

            [
                'id'=>2,
                'nombre'=>'Programación Web',
                'estudiantes'=>18
            ]

        ];
    }

    private function getStudentEnrollments(int $studentId): array{

        return [

            [
                'id'=>1,
                'servicio'=>'Educación Primaria',
                'estado'=>'aprobada'
            ],

            [
                'id'=>4,
                'servicio'=>'Programa Deportivo',
                'estado'=>'pendiente'
            ]

        ];
    }

    private function getParentChildren(int $parentId): array{

        return [

            [
                'id'=>101,
                'nombre'=>'María Ureña',
                'grado'=>'5to Primaria'
            ],

            [
                'id'=>102,
                'nombre'=>'Carlos Ureña',
                'grado'=>'2do Secundaria'
            ]

        ];
    }

    
}