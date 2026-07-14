<?php
/**
 * Módulo de Auditoría de Datos (Dashboard)
 * Arquitectura estructurada para extracción eficiente de registros.
 */
require_once 'conexion.php';
session_start();

// Control de acceso heredado del Oficial de Seguridad
if (!isset($_SESSION['usuario_id'])) {
    die(json_encode(['status' => 'error', 'message' => 'Acceso denegado. Se requiere autenticación.']));
}

// Configuración de límites para evitar saturación de memoria (Full Table Scans)
$limite_registros = 10;
$dominio_filtro = '%@gmail.com%'; // Parámetro de ejemplo para la cláusula WHERE

/*
 * Consulta optimizada:
 * Se aplican estrictamente SELECT, WHERE, ORDER BY y LIMIT.
 */
$sql = "SELECT id, nombre_usuario, correo, creado_en 
        FROM usuarios 
        WHERE correo LIKE :dominio 
        ORDER BY creado_en DESC 
        LIMIT :limite";

try {
    $stmt = $pdo->prepare($sql);
    
    // Vinculación estricta de parámetros para mitigar inyecciones
    $stmt->bindValue(':dominio', $dominio_filtro, PDO::PARAM_STR);
    $stmt->bindValue(':limite', $limite_registros, PDO::PARAM_INT);
    
    $stmt->execute();
    $auditoria_usuarios = $stmt->fetchAll();
    
} catch (\PDOException $e) {
    error_log("Error en la auditoría de datos (Dashboard): " . $e->getMessage());
    die("Error crítico al extraer los datos de auditoría.");
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Dashboard de Auditoría</title>
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Segoe UI', Arial, sans-serif; padding: 20px; background-color: #f4f6f9; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; background: #fff; }
        th, td { padding: 12px; border: 1px solid #ddd; text-align: left; }
        th { background-color: #007bff; color: white; }
    </style>
</head>
<body>
    <h2>Panel de Optimización y Auditoría</h2>
    <p>Mostrando los últimos <strong><?php echo $limite_registros; ?></strong> registros filtrados de forma segura.</p>
    
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre de Usuario</th>
                <th>Correo</th>
                <th>Fecha de Creación</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($auditoria_usuarios as $fila): ?>
                <tr>
                    <td><?php echo htmlspecialchars($fila['id']); ?></td>
                    <td><?php echo htmlspecialchars($fila['nombre_usuario']); ?></td>
                    <td><?php echo htmlspecialchars($fila['correo']); ?></td>
                    <td><?php echo htmlspecialchars($fila['creado_en']); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
