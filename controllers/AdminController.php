<?php
// controllers/AdminController.php

require_once __DIR__ . '/Controller.php';
require_once BASE_PATH . '/models/Auth.php';
require_once BASE_PATH . '/models/User.php';
require_once BASE_PATH . '/models/Service.php';

class AdminController extends Controller
{

    private Auth $auth;
    private User $user;
    private Service $service;

    public function __construct()
    {

        $this->auth = new Auth();
        $this->user = new User();
        $this->service = new Service();

        $this->checkAdmin();
    }

    // Verificar admin
    private function checkAdmin()
    {

        if (!$this->auth->check()) {

            $_SESSION['error'] = "Debes iniciar sesión";

            $this->redirect('?page=login');
        }

        $user = $this->auth->user();

        if (($user['rol_nombre'] ?? '') != 'admin') {

            $_SESSION['error'] = "Acceso restringido";

            $this->redirect('?page=home');
        }
    }

    // ================= DASHBOARD =================

    // El dashboard ahora es manejado por HomeController

    // ================= USUARIOS =================

    public function users()
    {
        $pdo = $this->service->getDB();


        // 1. Obtener todos los roles para el filtro
        $roles = $pdo->query("SELECT id, nombre FROM roles ORDER BY nombre ASC")->fetchAll(PDO::FETCH_ASSOC);

        // 2. Filtrado y Búsqueda
        $roleFilter = $_GET['role'] ?? '';
        $searchFilter = $_GET['search'] ?? '';

        // 3. Construcción de la consulta dinámica
        $sql = "SELECT u.*, r.nombre as rol_nombre 
                FROM usuarios u 
                JOIN roles r ON u.rol_id = r.id";

        $where = [];
        $params = [];

        if ($roleFilter) {
            $where[] = "r.nombre = ?";
            $params[] = $roleFilter;
        }

        if ($searchFilter) {
            $where[] = "(u.nombre_completo LIKE ? OR u.email LIKE ?)";
            $params[] = "%{$searchFilter}%";
            $params[] = "%{$searchFilter}%";
        }

        if (!empty($where)) {
            $sql .= " WHERE " . implode(' AND ', $where);
        }

        // 4. Paginación
        $page = isset($_GET['p']) ? (int)$_GET['p'] : 1;
        $perPage = 10; // 10 usuarios por página

        // Contar total de resultados para la paginación
        $countSql = "SELECT COUNT(*) FROM usuarios u JOIN roles r ON u.rol_id = r.id";
        if (!empty($where)) {
            $countSql .= " WHERE " . implode(' AND ', $where);
        }

        $totalStmt = $pdo->prepare($countSql);
        $totalStmt->execute($params);
        $totalUsers = $totalStmt->fetchColumn();

        $totalPages = ceil($totalUsers / $perPage);
        $offset = ($page - 1) * $perPage;

        $sql .= " ORDER BY u.created_at DESC LIMIT {$perPage} OFFSET {$offset}";

        // 5. Ejecutar la consulta final
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // 6. Pasar todos los datos a la vista
        $this->with([
            'title' => 'Gestión de Usuarios',
            'users' => $users,
            'roles' => $roles, // para el dropdown
            'role' => $roleFilter, // valor seleccionado
            'search' => $searchFilter, // valor del buscador
            'pagination' => [
                'current' => $page,
                'total' => $totalPages,
                'total_items' => $totalUsers,
                'per_page' => $perPage
            ]
        ]);

        $this->view('admin/users/index');
    }

    // Crear usuario
    public function createUser()
    {

        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            $this->redirect('?page=admin/users');
        }

        // Validación básica
        if (empty($_POST['nombre_completo']) || empty($_POST['email']) || empty($_POST['password'])) {
            $_SESSION['error'] = "Todos los campos son obligatorios.";
            // Idealmente, aquí se redirigiría a un formulario de creación,
            // pero la ruta actual es manejada por UserController.
            // Esto es una solución temporal.
            $this->redirect('?page=admin/users');
            return;
        }

        $data = [
            'nombre_completo' => $_POST['nombre_completo'],
            'email'           => $_POST['email'],
            'password'        => password_hash($_POST['password'], PASSWORD_DEFAULT),
            'rol_id'          => $_POST['rol_id'] ?? 3
        ];

        $pdo = $this->service->getDB();


        // Corregir el nombre de la columna
        $stmt = $pdo->prepare("
        INSERT INTO usuarios
        (nombre_completo, email, password_hash, rol_id, activo)
        VALUES(?, ?, ?, ?, 1)
        ");

        $stmt->execute(array_values($data));

        $_SESSION['success'] = "Usuario creado exitosamente.";
        $this->redirect('?page=admin/users');
    }

    // ================= SERVICIOS =================

    public function services()
    {

        $services = $this->service->all();

        $this->with([
            'title' => 'Servicios',
            'services' => $services
        ]);

        $this->view('admin/services/index');
    }

    public function createService()
    {

        if ($_SERVER['REQUEST_METHOD'] != 'POST') {

            $this->redirect('?page=admin/services');
        }

        $data = [

            'nombre' => $_POST['nombre'],
            'descripcion' => $_POST['descripcion'],
            'precio' => $_POST['precio'],
            'disponible' => 1
        ];

        $pdo = $this->service->getDB();


        $stmt = $pdo->prepare("
        INSERT INTO servicios
        (nombre,descripcion,precio,disponible)
        VALUES(?,?,?,?)
        ");

        $stmt->execute(array_values($data));

        $_SESSION['success'] = "Servicio creado";

        $this->redirect('?page=admin/services');
    }
}
