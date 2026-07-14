<?php
/**
 * Módulo de Autenticación y Control de Sesiones
 * Diseñado bajo el principio de "Zero Trust" (Defensa Profunda)[cite: 4, 23].
 */
require_once 'conexion.php';

// Gestionar sesiones seguras con $_SESSION [cite: 24]
session_start(); 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Validación de Capa 2: Re-validar en el Back-end todos los datos recibidos [cite: 26]
    // Se asume que la validación del Front-end puede ser evadida [cite: 26]
    $correo_crudo = $_POST['email'] ?? '';
    $password_plana = $_POST['password'] ?? '';

    // Sanitización y validación estricta de tipos de datos
    $correo_validado = filter_var(trim($correo_crudo), FILTER_VALIDATE_EMAIL);

    if (!$correo_validado || empty($password_plana)) {
        // Respuesta hermética ante datos de entrada anómalos
        die(json_encode([
            'status' => 'error', 
            'message' => 'Validación de servidor fallida. Formato de datos no permitido.'
        ]));
    }

    // Consulta con placeholders para evitar Inyección SQL (SQLi)
    $sql = "SELECT id, nombre_usuario, password_hash FROM usuarios WHERE correo = :email LIMIT 1";

    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':email' => $correo_validado]);
        $usuario = $stmt->fetch();

        // Autenticación: Recuperando el hash de la base de datos y validando con password_verify() [cite: 24]
        if ($usuario && password_verify($password_plana, $usuario['password_hash'])) {
            
            // Mitigación de fijación de sesión regenerando el ID
            session_regenerate_id(true);

            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['usuario_nombre'] = $usuario['nombre_usuario'];

            echo json_encode([
                'status' => 'success', 
                'message' => 'Autenticación exitosa.'
            ]);
        } else {
            // Respuesta genérica para evitar enumeración de usuarios
            echo json_encode([
                'status' => 'error', 
                'message' => 'Credenciales inválidas.'
            ]);
        }
    } catch (\PDOException $e) {
        // Auditoría Técnica: Hermetismo absoluto sin fugas de información en los bloques catch [cite: 25]
        error_log("Fallo crítico en módulo de autenticación: " . $e->getMessage());
        
        // Respuesta segura para el cliente, aislando los detalles internos del motor InnoDB [cite: 3]
        die(json_encode([
            'status' => 'error', 
            'message' => 'Error interno de infraestructura.'
        ]));
    }
}