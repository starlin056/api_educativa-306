<?php
// controllers/ServiceController.php

require_once __DIR__ . '/../models/Service.php';
require_once __DIR__ . '/../models/Auth.php';

class ServiceController
{
    private $serviceModel;
    private $auth;

    public function __construct()
    {
        $this->serviceModel = new Service();
        $this->auth = new Auth();

        // Solo administrador
        if (!$this->auth->check() || !$this->auth->hasRole('admin')) {
            header("Location: ?page=login");
            exit;
        }
    }

    /**
     * Mostrar lista de servicios
     */
    public function index()
    {
        $services = $this->serviceModel->all();

        $title = "Gestión de Servicios";

        require __DIR__ . '/../views/layouts/header.php';
        require __DIR__ . '/../views/admin/dashboard.php';
        require __DIR__ . '/../views/layouts/footer.php';
    }

    /**
     * Mostrar formulario crear servicio
     */
    public function create()
    {
        $title = "Crear Servicio";

        require __DIR__ . '/../views/layouts/header.php';
        require __DIR__ . '/../views/admin/services/create.php';
        require __DIR__ . '/../views/layouts/footer.php';
    }

    /**
     * Guardar nuevo servicio
     */
    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: ?page=admin/services");
            exit;
        }

        $data = [
            'titulo' => trim($_POST['titulo']),
            'descripcion' => trim($_POST['descripcion']),
            'categoria' => $_POST['categoria'],
            'icono' => $_POST['icono'] ?? 'fa-cog',
            'orden_mostrar' => (int)($_POST['orden_mostrar'] ?? 0),
            'disponible' => isset($_POST['disponible']) ? 1 : 0
        ];

        $this->serviceModel->create($data);

        header("Location: ?page=admin/services");
        exit;
    }

    /**
     * Mostrar formulario editar
     */
    public function edit()
    {
        if (!isset($_GET['id'])) {
            header("Location: ?page=admin/services");
            exit;
        }

        $id = (int)$_GET['id'];

        $service = $this->serviceModel->findById($id);

        if (!$service) {
            header("Location: ?page=admin/services");
            exit;
        }

        $title = "Editar Servicio";

        require __DIR__ . '/../views/layouts/header.php';
        require __DIR__ . '/../views/admin/services/edit.php';
        require __DIR__ . '/../views/layouts/footer.php';
    }

    /**
     * Actualizar servicio
     */
    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_GET['id'])) {
            header("Location: ?page=admin/services");
            exit;
        }

        $id = (int)$_GET['id'];

        $data = [
            'titulo' => trim($_POST['titulo']),
            'descripcion' => trim($_POST['descripcion']),
            'categoria' => $_POST['categoria'],
            'icono' => $_POST['icono'] ?? 'fa-cog',
            'orden_mostrar' => (int)($_POST['orden_mostrar'] ?? 0),
            'disponible' => isset($_POST['disponible']) ? 1 : 0
        ];

        $this->serviceModel->update($id, $data);

        header("Location: ?page=admin/services");
        exit;
    }

    /**
     * Activar / Desactivar servicio
     */
    public function toggle()
    {
        if (!isset($_GET['id'])) {
            header("Location: ?page=admin/services");
            exit;
        }

        $id = (int)$_GET['id'];

        $service = $this->serviceModel->findById($id);

        if (!$service) {
            header("Location: ?page=admin/services");
            exit;
        }

        $newState = $service['disponible'] ? 0 : 1;

        $this->serviceModel->toggleAvailability($id, $newState);

        header("Location: ?page=admin/services");
        exit;
    }
}