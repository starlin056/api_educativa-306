<?php
// controllers/UserController.php
// Gestión de usuarios para panel administrativo
// @phpstan-ignore-file

require_once __DIR__ . '/Controller.php';
require_once BASE_PATH . '/models/User.php';
require_once BASE_PATH . '/middleware/AuthMiddleware.php';


class UserController extends Controller
{
    private User $user;

    public function __construct()
    {
        $this->user = new User();
    }

    /**
     * Listar usuarios por rol
     * URL: /?page=admin/users&role=estudiante
     */
    public function index(): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) session_start();
        if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
            $_SESSION['error'] = 'Acceso denegado';
            $this->redirect('?page=home');
            return;
        }

        // Obtén la conexión PDO de forma segura
        $pdo = $this->user->getDB();

        // 1. Obtener todos los roles para el filtro
        $roles = $this->getRolesList();

        // 2. Filtrado y Búsqueda
        $roleFilter = $_GET['role'] ?? '';
        $searchFilter = $_GET['search'] ?? '';

        $sql = "SELECT u.*, r.nombre as rol_nombre 
            FROM usuarios u 
            JOIN roles r ON u.rol_id = r.id";

        $where = [];
        $params = [];

        if ($roleFilter && $roleFilter !== 'all') {
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
        $perPage = 10;

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

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $this->with([
            'title'       => 'Gestión de Usuarios',
            'users'       => $users,
            'roles'       => $roles,
            'role'        => $roleFilter,
            'search'      => $searchFilter,
            'pagination'  => [
                'current'     => $page,
                'total'       => $totalPages,
                'total_items' => $totalUsers,
                'per_page'    => $perPage
            ]
        ]);

        $this->view('admin/users/index');
    }

    /**
     * Mostrar formulario de creación
     */
    public function create(): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) session_start();
        if ($_SESSION['rol'] !== 'admin') {
            $this->redirect('?page=home');
            return;
        }

        $roles = $this->getRolesList();

        $this->with([
            'title' => 'Crear Nuevo Usuario',
            'roles' => $roles,
            'user' => null // Para reutilizar el formulario
        ]);

        $this->view('admin/users/form');
    }

    /**
     * Procesar creación de usuario
     */
    public function store(): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) session_start();
        if ($_SESSION['rol'] !== 'admin' || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('?page=home');
            return;
        }

        // Validar datos
        $data = [
            'nombre_completo' => trim($_POST['nombre_completo'] ?? ''),
            'email' => filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL),
            'password' => $_POST['password'] ?? '',
            'rol_id' => (int)($_POST['rol_id'] ?? 3), // Default: estudiante
            'telefono' => trim($_POST['telefono'] ?? ''),
            'activo' => isset($_POST['activo']) ? 1 : 0
        ];

        // Validaciones básicas
        if (strlen($data['nombre_completo']) < 3) {
            $_SESSION['error'] = 'El nombre debe tener al menos 3 caracteres';
            $this->redirect('?page=admin/users&action=create');
            return;
        }

        if (!$data['email']) {
            $_SESSION['error'] = 'Email inválido';
            $this->redirect('?page=admin/users&action=create');
            return;
        }

        if ($this->user->emailExists($data['email'])) {
            $_SESSION['error'] = 'El email ya está registrado';
            $this->redirect('?page=admin/users&action=create');
            return;
        }

        if (strlen($data['password']) < 6) {
            $_SESSION['error'] = 'La contraseña debe tener al menos 6 caracteres';
            $this->redirect('?page=admin/users&action=create');
            return;
        }

        // Hash de contraseña y crear usuario
        $data['password_hash'] = password_hash($data['password'], PASSWORD_BCRYPT, ['cost' => 12]);
        unset($data['password']); // Remover contraseña plana

        try {
            $userId = $this->user->create($data);
            $_SESSION['success'] = 'Usuario creado exitosamente';
            $this->redirect('?page=admin/users');
        } catch (Exception $e) {
            error_log("Error creando usuario: " . $e->getMessage());
            $_SESSION['error'] = 'Error al crear usuario';
            $this->redirect('?page=admin/users&action=create');
        }
    }

    /**
     * Mostrar formulario de edición
     */
    public function edit(int $id): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) session_start();
        if ($_SESSION['rol'] !== 'admin') {
            $this->redirect('?page=home');
            return;
        }

        $user = $this->user->findByIdWithRole($id);

        if (!$user) {
            $_SESSION['error'] = 'Usuario no encontrado';
            $this->redirect('?page=admin/users');
            return;
        }

        $roles = $this->getRolesList();

        $this->with([
            'title' => 'Editar Usuario',
            'user' => $user,
            'roles' => $roles
        ]);

        $this->view('admin/users/form');
    }

    /**
     * Procesar actualización de usuario
     */
    public function update(int $id): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) session_start();
        if ($_SESSION['rol'] !== 'admin' || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('?page=home');
            return;
        }

        $data = [
            'nombre_completo' => trim($_POST['nombre_completo'] ?? ''),
            'email' => filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL),
            'rol_id' => (int)($_POST['rol_id'] ?? 3),
            'telefono' => trim($_POST['telefono'] ?? ''),
            'activo' => isset($_POST['activo']) ? 1 : 0
        ];

        // Si se envió nueva contraseña, actualizarla
        if (!empty($_POST['password'])) {
            if (strlen($_POST['password']) < 6) {
                $_SESSION['error'] = 'La contraseña debe tener al menos 6 caracteres';
                $this->redirect("?page=admin/users&action=edit&id={$id}");
                return;
            }
            $data['password_hash'] = password_hash($_POST['password'], PASSWORD_BCRYPT, ['cost' => 12]);
        }

        try {
            $updated = $this->user->update($id, $data);
            $_SESSION['success'] = $updated ? 'Usuario actualizado exitosamente' : 'No se realizaron cambios';
            $this->redirect('?page=admin/users');
        } catch (Exception $e) {
            error_log("Error actualizando usuario {$id}: " . $e->getMessage());
            $_SESSION['error'] = 'Error al actualizar usuario';
            $this->redirect("?page=admin/users&action=edit&id={$id}");
        }
    }

    /**
     * Eliminar usuario (soft delete)
     */
    public function delete(int $id): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) session_start();
        if ($_SESSION['rol'] !== 'admin' || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('?page=home');
            return;
        }

        // No permitir eliminarse a sí mismo
        if ($id === ($_SESSION['user_id'] ?? 0)) {
            $_SESSION['error'] = 'No puedes eliminar tu propia cuenta';
            $this->redirect('?page=admin/users');
            return;
        }

        try {
            $deleted = $this->user->deactivate($id);
            $_SESSION['success'] = $deleted ? 'Usuario desactivado exitosamente' : 'Usuario no encontrado';
            $this->redirect('?page=admin/users');
        } catch (Exception $e) {
            error_log("Error desactivando usuario {$id}: " . $e->getMessage());
            $_SESSION['error'] = 'Error al procesar la solicitud';
            $this->redirect('?page=admin/users');
        }
    }

    /**
     * Cambiar estado de usuario (activar/desactivar)
     */
    public function toggleStatus(int $id): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) session_start();
        if ($_SESSION['rol'] !== 'admin') {
            $this->json(['success' => false, 'message' => 'Acceso denegado'], 403);
            return;
        }

        $user = $this->user->findById($id);
        if (!$user) {
            $this->json(['success' => false, 'message' => 'Usuario no encontrado'], 404);
            return;
        }

        $newStatus = !$user['activo'];
        $updated = $this->user->update($id, ['activo' => $newStatus ? 1 : 0]);

        $this->json([
            'success' => (bool)$updated,
            'message' => $newStatus ? 'Usuario activado' : 'Usuario desactivado',
            'new_status' => $newStatus
        ]);
    }

    // ========== MÉTODOS AUXILIARES ==========

    private function getRolesList(): array
    {
        // Usa el helper getDB() que devuelve PDO
        $pdo = $this->user->getDB();

        $stmt = $pdo->query("SELECT id, nombre FROM roles ORDER BY nombre ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
