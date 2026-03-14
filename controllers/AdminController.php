<?php
// controllers/AdminController.php

require_once __DIR__.'/Controller.php';
require_once BASE_PATH.'/models/Auth.php';
require_once BASE_PATH.'/models/User.php';
require_once BASE_PATH.'/models/Service.php';

class AdminController extends Controller{

    private Auth $auth;
    private User $user;
    private Service $service;

    public function __construct(){

        $this->auth = new Auth();
        $this->user = new User();
        $this->service = new Service();

        $this->checkAdmin();
    }

    // Verificar admin
    private function checkAdmin(){

        if(!$this->auth->check()){

            $_SESSION['error']="Debes iniciar sesión";

            $this->redirect('?page=login');
        }

        $user = $this->auth->user();

        if(($user['rol_nombre'] ?? '') != 'admin'){

            $_SESSION['error']="Acceso restringido";

            $this->redirect('?page=home');
        }
    }

    // ================= DASHBOARD =================

    public function dashboard(){

        $db = $this->user->getConnection();

        $stats = [];

        $stats['usuarios'] = $db->query("SELECT COUNT(*) FROM usuarios")->fetchColumn();
        $stats['servicios'] = $db->query("SELECT COUNT(*) FROM servicios")->fetchColumn();
        $stats['activos'] = $db->query("SELECT COUNT(*) FROM usuarios WHERE activo = 1")->fetchColumn();

        $this->with([
            'title'=>'Administrador',
            'stats'=>$stats,
            'user'=>$this->auth->user()
        ]);

        $this->view('admin/dashboard');
    }

    // ================= USUARIOS =================

    public function users(){

        $db = $this->user->getConnection();

        $stmt = $db->query("
        SELECT u.*, r.nombre as rol
        FROM usuarios u
        INNER JOIN roles r ON r.id = u.rol_id
        ORDER BY u.id DESC
        ");

        $users = $stmt->fetchAll();

        $this->with([
            'title'=>'Usuarios',
            'users'=>$users
        ]);

        $this->view('admin/users');
    }

    // Crear usuario
    public function createUser(){

        if($_SERVER['REQUEST_METHOD']!='POST'){

            $this->redirect('?page=admin/users');
        }

        $data = [

            'nombre'=>$_POST['nombre'] ?? '',
            'email'=>$_POST['email'] ?? '',
            'password'=>password_hash($_POST['password'],PASSWORD_DEFAULT),
            'rol_id'=>$_POST['rol_id'] ?? 3
        ];

        $db = $this->user->getConnection();

        $stmt = $db->prepare("
        INSERT INTO usuarios
        (nombre,email,password,rol_id,activo)
        VALUES(?,?,?,?,1)
        ");

        $stmt->execute(array_values($data));

        $_SESSION['success']="Usuario creado";

        $this->redirect('?page=admin/users');
    }

    // ================= SERVICIOS =================

    public function services(){

        $services = $this->service->all();

        $this->with([
            'title'=>'Servicios',
            'services'=>$services
        ]);

        $this->view('admin/services');
    }

    public function createService(){

        if($_SERVER['REQUEST_METHOD']!='POST'){

            $this->redirect('?page=admin/services');
        }

        $data = [

            'nombre'=>$_POST['nombre'],
            'descripcion'=>$_POST['descripcion'],
            'precio'=>$_POST['precio'],
            'disponible'=>1
        ];

        $db = $this->service->getConnection();

        $stmt = $db->prepare("
        INSERT INTO servicios
        (nombre,descripcion,precio,disponible)
        VALUES(?,?,?,?)
        ");

        $stmt->execute(array_values($data));

        $_SESSION['success']="Servicio creado";

        $this->redirect('?page=admin/services');
    }
}