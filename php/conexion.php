<?php

$host = 'localhost';
$dbname = 'examen';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    
    error_log("Error crítico de conexión: " . $e->getMessage());
    die("Servicio no disponible. Por favor, intente más tarde.");
}
?>