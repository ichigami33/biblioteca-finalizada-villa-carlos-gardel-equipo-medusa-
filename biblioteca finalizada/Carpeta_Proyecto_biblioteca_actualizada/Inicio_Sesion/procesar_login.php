<?php
require_once __DIR__ . '/sesion.php';
$raiz = '../';
$botones = [['Volver a iniciar sesión', 'inicio_sesion.php'], ['Volver a la página principal', url_principal($raiz)]];

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: inicio_sesion.php');
    exit;
}

$nombre = trim($_POST['usuario'] ?? '');
$clave = $_POST['contraseña'] ?? '';

if ($nombre === '' || $clave === '') {
    mostrar_error($raiz, 'Faltan datos', 'Completá el usuario y la contraseña para poder iniciar sesión.', $botones);
}

try {
    require_once __DIR__ . '/conexion.php';
    $stmt = $conexion->prepare('SELECT id, usuario, contraseña, rango FROM usuarios WHERE usuario = ?');
    $stmt->bind_param('s', $nombre);
    $stmt->execute();
    $datos = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    $conexion->close();
} catch (Throwable $e) {
    mostrar_error($raiz, 'Error del sistema', 'No se pudo consultar la base de datos. Verificá que el servidor esté encendido e intentá nuevamente.', $botones, 500);
}

if (!$datos) {
    mostrar_error($raiz, 'El usuario no existe', 'No encontramos ningún usuario con el nombre "' . $nombre . '". Revisá cómo lo escribiste e intentá de nuevo.', $botones, 401);
}

if (!password_verify($clave, $datos['contraseña'])) {
    mostrar_error($raiz, 'Contraseña incorrecta', 'La contraseña ingresada no es correcta para el usuario "' . $datos['usuario'] . '". Intentá nuevamente.', $botones, 401);
}

session_regenerate_id(true);
$_SESSION['id'] = $datos['id'];
$_SESSION['usuario'] = $datos['usuario'];
$_SESSION['rango'] = $datos['rango'];
header('Location: ' . url_principal($raiz));
exit;
