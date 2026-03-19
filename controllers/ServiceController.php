<?php
// controllers/ServiceController.php
// Gestión de servicios educativos para panel administrativo
// @phpstan-ignore-file

require_once __DIR__ . '/Controller.php';
require_once BASE_PATH . '/models/Service.php';
require_once BASE_PATH . '/middleware/AuthMiddleware.php';

class ServiceController extends Controller
{
    private Service $service;

    public function __construct()
    {
        $this->service = new Service();
    }

    /**
     * Listar servicios con filtros
     */
    public function index(): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) session_start();
        if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
            $_SESSION['error'] = 'Acceso denegado';
            $this->redirect('?page=home');
            return;
        }

        $category = $_GET['category'] ?? null;
        $search = $_GET['search'] ?? null;
        $page = max(1, (int)($_GET['page'] ?? 1));
        $perPage = 10;

        // Obtener servicios filtrados
        if ($search) {
            $services = $this->service->search($search);
        } elseif ($category) {
            $services = $this->service->getByCategory($category);
        } else {
            $services = $this->service->findAll('orden_mostrar', 'ASC');
        }

        // Paginación simple
        $total = count($services);
        $services = array_slice($services, ($page - 1) * $perPage, $perPage);

        $this->with([
            'title' => 'Gestión de Servicios',
            'services' => $services,
            'category' => $category,
            'search' => $search,
            'pagination' => [
                'current' => $page,
                'total' => ceil($total / $perPage),
                'total_items' => $total
            ]
        ]);

        $this->view('admin/services/index');
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

        $this->with(['title' => 'Crear Servicio']);
        $this->view('admin/services/create');
    }

    /**
     * Procesar creación de servicio
     */
    public function store(): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) session_start();
        if ($_SESSION['rol'] !== 'admin' || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('?page=home');
            return;
        }

        $data = [
            'titulo' => trim($_POST['titulo'] ?? ''),
            'descripcion' => trim($_POST['descripcion'] ?? ''),
            'categoria' => $_POST['categoria'] ?? 'academico',
            'icono' => trim($_POST['icono'] ?? 'fa-graduation-cap'),
            'orden_mostrar' => (int)($_POST['orden_mostrar'] ?? 0),
            'disponible' => isset($_POST['disponible']) ? 1 : 0
        ];

        // Validaciones
        if (strlen($data['titulo']) < 3) {
            $_SESSION['error'] = 'El título debe tener al menos 3 caracteres';
            $this->redirect('?page=admin/services&action=create');
            return;
        }

        if (strlen($data['descripcion']) < 10) {
            $_SESSION['error'] = 'La descripción debe tener al menos 10 caracteres';
            $this->redirect('?page=admin/services&action=create');
            return;
        }

        try {
            $this->service->create($data);
            $_SESSION['success'] = 'Servicio creado exitosamente';
            $this->redirect('?page=admin/services');
        } catch (Exception $e) {
            error_log("Error creando servicio: " . $e->getMessage());
            $_SESSION['error'] = 'Error al crear servicio';
            $this->redirect('?page=admin/services&action=create');
        }
    }

    /**
     * Mostrar formulario de edición ← CORREGIDO: leer id de $_GET
     */
    public function edit(): void
    {  // ← Sin parámetro tipado
        if (session_status() !== PHP_SESSION_ACTIVE) session_start();
        if ($_SESSION['rol'] !== 'admin') {
            $this->redirect('?page=home');
            return;
        }

        // ← Leer id desde la URL
        $id = $_GET['id'] ?? null;
        if (!$id) {
            $_SESSION['error'] = 'ID de servicio no especificado';
            $this->redirect('?page=admin/services');
            return;
        }

        $service = $this->service->findById($id);

        if (!$service) {
            $_SESSION['error'] = 'Servicio no encontrado';
            $this->redirect('?page=admin/services');
            return;
        }

        $this->with([
            'title' => 'Editar Servicio',
            'service' => $service
        ]);

        $this->view('admin/services/edit');
    }

    /**
     * Procesar actualización de servicio ← CORREGIDO: leer id de $_GET/POST
     */
    public function update(): void
    {  // ← Sin parámetro tipado
        if (session_status() !== PHP_SESSION_ACTIVE) session_start();
        if ($_SESSION['rol'] !== 'admin' || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('?page=home');
            return;
        }

        // ← Leer id desde la URL o POST
        $id = $_GET['id'] ?? $_POST['id'] ?? null;
        if (!$id) {
            $_SESSION['error'] = 'ID de servicio no especificado';
            $this->redirect('?page=admin/services');
            return;
        }

        $data = [
            'titulo' => trim($_POST['titulo'] ?? ''),
            'descripcion' => trim($_POST['descripcion'] ?? ''),
            'categoria' => $_POST['categoria'] ?? 'academico',
            'icono' => trim($_POST['icono'] ?? 'fa-graduation-cap'),
            'orden_mostrar' => (int)($_POST['orden_mostrar'] ?? 0),
            'disponible' => isset($_POST['disponible']) ? 1 : 0
        ];

        // Validaciones
        if (strlen($data['titulo']) < 3) {
            $_SESSION['error'] = 'El título debe tener al menos 3 caracteres';
            $this->redirect("?page=admin/services&action=edit&id={$id}");
            return;
        }

        try {
            $updated = $this->service->update($id, $data);
            $_SESSION['success'] = $updated ? 'Servicio actualizado exitosamente' : 'No se realizaron cambios';
            $this->redirect('?page=admin/services');
        } catch (Exception $e) {
            error_log("Error actualizando servicio {$id}: " . $e->getMessage());
            $_SESSION['error'] = 'Error al actualizar servicio';
            $this->redirect("?page=admin/services&action=edit&id={$id}");
        }
    }

    /**
     * Eliminar servicio ← CORREGIDO: leer id de $_GET
     */
    public function delete(): void
    {  // ← Sin parámetro tipado
        if (session_status() !== PHP_SESSION_ACTIVE) session_start();
        if ($_SESSION['rol'] !== 'admin' || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('?page=home');
            return;
        }

        // ← Leer id desde la URL
        $id = $_GET['id'] ?? null;
        if (!$id) {
            $_SESSION['error'] = 'ID de servicio no especificado';
            $this->redirect('?page=admin/services');
            return;
        }

        try {
            $deleted = $this->service->delete($id);
            $_SESSION['success'] = $deleted ? 'Servicio eliminado exitosamente' : 'Servicio no encontrado';
            $this->redirect('?page=admin/services');
        } catch (Exception $e) {
            error_log("Error eliminando servicio {$id}: " . $e->getMessage());
            $_SESSION['error'] = 'Error al eliminar servicio';
            $this->redirect('?page=admin/services');
        }
    }

    /**
     * Toggle disponibilidad (AJAX) ← CORREGIDO: leer id de $_GET
     */
    public function toggleDisponible(): void
    {  // ← Sin parámetro tipado
        if (session_status() !== PHP_SESSION_ACTIVE) session_start();
        if ($_SESSION['rol'] !== 'admin') {
            $this->json(['success' => false, 'message' => 'Acceso denegado'], 403);
            return;
        }

        // ← Leer id desde la URL
        $id = $_GET['id'] ?? null;
        if (!$id) {
            $this->json(['success' => false, 'message' => 'ID no especificado'], 400);
            return;
        }

        $service = $this->service->findById($id);
        if (!$service) {
            $this->json(['success' => false, 'message' => 'Servicio no encontrado'], 404);
            return;
        }

        $newStatus = !$service['disponible'];
        $updated = $this->service->update($id, ['disponible' => $newStatus ? 1 : 0]);

        $this->json([
            'success' => (bool)$updated,
            'message' => $newStatus ? 'Servicio activado' : 'Servicio desactivado',
            'new_status' => $newStatus
        ]);
    }
}
