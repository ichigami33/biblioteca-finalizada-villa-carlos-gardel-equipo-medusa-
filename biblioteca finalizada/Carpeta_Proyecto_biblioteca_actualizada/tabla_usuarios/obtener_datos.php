<?php
header("Content-Type: application/json; charset=UTF-8");
require_once __DIR__ . '/../Inicio_Sesion/sesion.php';
if (!es_admin()) {
    http_response_code(sesion_activa() ? 403 : 401);
    echo json_encode(["error" => "No tenés permiso para ver esta información."]);
    exit;
}
$host = "localhost";
$usuario = "root";
$password = "";
try {
    $pdo = new PDO(
        "mysql:host=$host;charset=utf8mb4",
        $usuario,
        $password
    );
    $pdo->setAttribute(
        PDO::ATTR_ERRMODE,
        PDO::ERRMODE_EXCEPTION
    );
    $pdo->exec("USE `bibloteca`");
    $consulta = $pdo->query("
        SELECT
            id,
            usuario,
            tel,
            mail,
            sans,
            curso_actual,
            rango
        FROM usuarios
        ORDER BY id ASC
    ");
    $usuarios = $consulta->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode(
        $usuarios,
        JSON_UNESCAPED_UNICODE
    );
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        "error" => "No se pudieron obtener los alumnos."
    ]);
}
?>
