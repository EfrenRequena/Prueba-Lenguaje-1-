<?php

require_once 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
   
    $usuario = trim($_POST['usuario'] ?? '');
    $correo = trim($_POST['correo'] ?? '');
    $clave = $_POST['clave'] ?? '';
    $confirmar = $_POST['confirmar'] ?? ''; // Recibimos el campo de confirmación

    // back-end (Capa 2, apoyo al Estudiante 3)
    if (empty($usuario) || empty($correo) || empty($clave)) {
        die("Error: Todos los campos son obligatorios.");
    }

    // Validación extra: verificar que ambas contraseñas coincidan
    if ($clave !== $confirmar) {
        die("Error: Las contraseñas no coinciden.");
    }

    // (BCRYPT) - Esto debe coordinarse con el Estudiante 3
    $hash = password_hash($clave, PASSWORD_BCRYPT, ['cost' => 12]);

    try {
        $sql = "INSERT INTO usuarios (usuario, correo, clave) VALUES (:usuario, :correo, :clave)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':usuario' => $usuario,
            ':correo' => $correo,
            ':clave' => $hash
        ]);
        echo "Usuario registrado exitosamente.";
    } catch (PDOException $e) {
        error_log("Error en registro: " . $e->getMessage());
        die("No se pudo completar el registro. Verifique que el usuario o correo no estén duplicados.");
    }
} else {
    die("Acceso no permitido.");
}
?>