<?php
// models/Auth.php
// Lógica de autenticación y sesiones
// @phpstan-ignore-file

require_once __DIR__ . '/Model.php';
require_once __DIR__ . '/User.php';

class Auth extends Model
{
    protected $table = 'sesiones';  // ← SIN tipo "string"

    /**
     * Get session lifetime with safe fallback
     */
    private function getSessionLifetime(): int
    {
        return (int) Config::env('SESSION_LIFETIME', 120);
    }

    // Registrar nuevo usuario
    public function register(array $data): array
    {
        if (empty($data['nombre']) || empty($data['email']) || empty($data['password'])) {
            return ['success' => false, 'message' => 'Todos los campos son obligatorios'];
        }

        $userModel = new User();

        if ($userModel->emailExists($data['email'])) {
            return ['success' => false, 'message' => 'El correo ya está registrado'];
        }

        $passwordHash = password_hash($data['password'], PASSWORD_BCRYPT, ['cost' => 12]);
        $roleId = $this->getRoleIdByName($data['rol'] ?? 'estudiante');

        $userId = $userModel->create([
            'nombre_completo' => htmlspecialchars($data['nombre']),
            'email' => filter_var($data['email'], FILTER_VALIDATE_EMAIL),
            'password_hash' => $passwordHash,
            'rol_id' => $roleId,
            'telefono' => $data['telefono'] ?? null,
            'fecha_nacimiento' => $data['fecha_nacimiento'] ?? null
        ]);

        if ($userId) {
            return ['success' => true, 'user_id' => $userId, 'message' => 'Registro exitoso'];
        }

        return ['success' => false, 'message' => 'Error al registrar usuario'];
    }

    // Autenticar usuario (login)
    public function login(string $email, string $password): array
    {
        $userModel = new User();
        $user = $userModel->findByEmail($email);

        if (!$user) {
            return ['success' => false, 'message' => 'Credenciales inválidas'];
        }

        if (!password_verify($password, $user['password_hash'])) {
            error_log("Intento fallido de login para: {$email}");
            return ['success' => false, 'message' => 'Credenciales inválidas'];
        }

        if (!isset($user['activo']) || !$user['activo']) {
            return ['success' => false, 'message' => 'Cuenta desactivada. Contacte al administrador'];
        }

        $this->createSession($user);
        $userModel->updateLastLogin($user['id']);

        // @phpstan-ignore-next-line
        $roleName = $user['rol_nombre'] ?? 'estudiante';
        return [
            'success' => true,
            'user' => $user,
            'redirect' => $this->getRedirectByRole($roleName)
        ];
    }

    // Cerrar sesión
    public function logout(): bool
    {
        if (isset($_SESSION['user_id'])) {
            $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE usuario_id = ? AND token = ?");
            $stmt->execute([$_SESSION['user_id'], session_id()]);
        }

        session_destroy();

        if (isset($_COOKIE[session_name()])) {
            setcookie(session_name(), '', time() - 3600, '/');
            unset($_COOKIE[session_name()]);
        }

        return true;
    }

    public function check(): bool
    {
        return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
    }

    public function user(): ?array
    {
        if (!$this->check()) return null;
        $userModel = new User();
        return $userModel->findByIdWithRole((int)$_SESSION['user_id']);
    }


    public function hasRole(string $role): bool
    {
        return $this->check() && ($_SESSION['rol'] ?? '') === $role;
    }

    // ========== MÉTODOS PRIVADOS ==========

    private function getRoleIdByName(string $roleName): int
    {
        $stmt = $this->db->prepare("SELECT id FROM roles WHERE nombre = ?");
        $stmt->execute([$roleName]);
        $result = $stmt->fetch();
        return $result['id'] ?? 3;
    }

    private function createSession(array $user): void
    {
        // Iniciar sesión si no está activa
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        session_regenerate_id(true);

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['nombre'] = $user['nombre_completo'] ?? 'Usuario';
        $_SESSION['rol'] = $user['rol_nombre'] ?? 'estudiante';
        $_SESSION['ip'] = $_SERVER['REMOTE_ADDR'];
        $_SESSION['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
        $_SESSION['last_activity'] = time();

        // ✅ AGREGAR ESTO: Generar token CSRF
        $_SESSION['_token'] = bin2hex(random_bytes(32));

        $token = bin2hex(random_bytes(32));
        $lifetime = $this->getSessionLifetime();
        $expires = date('Y-m-d H:i:s', time() + ($lifetime * 60));

        $stmt = $this->db->prepare("
        INSERT INTO sesiones (usuario_id, token, ip_address, user_agent, fecha_expiracion) 
        VALUES (?, ?, ?, ?, ?)
    ");
        $stmt->execute([
            $user['id'],
            $token,
            $_SERVER['REMOTE_ADDR'],
            $_SERVER['HTTP_USER_AGENT'],
            $expires
        ]);
    }

    private function getRedirectByRole(string $role): string
    {
        $redirects = [
            'admin' => '?page=admin/dashboard',
            'docente' => '?page=docente/dashboard',
            'estudiante' => '?page=estudiante/dashboard',
            'padre' => '?page=padre/dashboard'
        ];
        return $redirects[$role] ?? '?page=home';
    }
}
