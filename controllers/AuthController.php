<?php
// controllers/AuthController.php
// Controlador para autenticación: login, registro, logout

require_once __DIR__ . '/Controller.php';
require_once BASE_PATH . '/models/Auth.php';

class AuthController extends Controller {
    private $auth;
    
    public function __construct() {
        $this->auth = new Auth();
    }
    
    // Mostrar formulario de login
    public function showLogin() {
        // Si ya está logueado, redirigir según rol
        if ($this->auth->check()) {
            return $this->redirect('?page=home');
        }
        $this->view('login/login', false);
    }
    
    // Procesar login
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->redirect('?page=login');
        }
        
        $email = filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL);
        $password = $_POST['password'] ?? '';
        
        if (!$email || !$password) {
            $_SESSION['error'] = 'Email y contraseña son requeridos';
            return $this->redirect('?page=login');
        }
        
        $result = $this->auth->login($email, $password);
        
        if ($result['success']) {
            $this->success('Bienvenido, ' . htmlspecialchars($result['user']['nombre_completo']));
            return $this->redirect($result['redirect']);
        } else {
            $_SESSION['error'] = $result['message'];
            return $this->redirect('?page=login');
        }
    }
    
    // Mostrar formulario de registro
    public function showRegister() {
        if ($this->auth->check()) {
            return $this->redirect('?page=home');
        }
        $this->view('login/register', false);
    }
    
    // Procesar registro
    public function register() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->redirect('?page=register');
        }
        
        // Sanitizar y validar datos
        $data = [
            'nombre' => trim($_POST['nombre'] ?? ''),
            'email' => filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL),
            'password' => $_POST['password'] ?? '',
            'password_confirm' => $_POST['password_confirm'] ?? '',
            'telefono' => trim($_POST['telefono'] ?? ''),
            'fecha_nacimiento' => $_POST['fecha_nacimiento'] ?? null,
            'rol' => 'estudiante' // Por defecto, solo admin puede asignar otros roles
        ];
        
        // Validaciones
        if (strlen($data['nombre']) < 3) {
            $_SESSION['error'] = 'El nombre debe tener al menos 3 caracteres';
            return $this->redirect('?page=register');
        }
        
        if (!$data['email']) {
            $_SESSION['error'] = 'Email inválido';
            return $this->redirect('?page=register');
        }
        
        if (strlen($data['password']) < 6) {
            $_SESSION['error'] = 'La contraseña debe tener al menos 6 caracteres';
            return $this->redirect('?page=register');
        }
        
        if ($data['password'] !== $data['password_confirm']) {
            $_SESSION['error'] = 'Las contraseñas no coinciden';
            return $this->redirect('?page=register');
        }
        
        // Intentar registrar
        $result = $this->auth->register($data);
        
        if ($result['success']) {
            $this->success('Registro exitoso. Ahora puedes iniciar sesión.');
            return $this->redirect('?page=login');
        } else {
            $_SESSION['error'] = $result['message'];
            return $this->redirect('?page=register');
        }
    }
    
    // Cerrar sesión
    public function logout() {
        $this->auth->logout();
        $this->success('Sesión cerrada correctamente');
        return $this->redirect('?page=home');
    }
}