<?php
if (session_status() === PHP_SESSION_NONE) session_start();

function h($texto) { return htmlspecialchars((string)$texto, ENT_QUOTES, 'UTF-8'); }
function sesion_activa() { return isset($_SESSION['id'], $_SESSION['usuario'], $_SESSION['rango']); }
function es_admin() { return sesion_activa() && in_array($_SESSION['rango'], ['Bibliotecario', 'Directivo'], true); }
function url_principal($raiz) { return $raiz . 'Pagina_Principal/pagina_principal_biblioteca/Pagina_principal.php'; }
function url_login($raiz) { return $raiz . 'Inicio_Sesion/inicio_sesion.php'; }
function url_logout($raiz) { return $raiz . 'Inicio_Sesion/cerrar_sesion.php'; }
function url_carnet($raiz) { return $raiz . 'Carnet_De_Estudiante/Archivos_Ficha_De_Estudiantes/carnet.php?id=' . (int)($_SESSION['id'] ?? 0); }
function estilo_sesion($raiz) { echo '<link rel="stylesheet" href="' . h($raiz) . 'Inicio_Sesion/sesion.css">'; }

function mostrar_sesion($raiz, $clase = '') {
    if (!sesion_activa()) {
        echo '<a href="' . h(url_login($raiz)) . '" class="boton-login sesion-entrar ' . h($clase) . '">Iniciar sesión</a>';
        return;
    }
    echo '<div class="sesion-caja">';
    echo '<a class="sesion-usuario" href="' . h(url_carnet($raiz)) . '" title="Ver mi carnet">';
    echo '<span class="sesion-nombre">' . h($_SESSION['usuario']) . '</span>';
    echo '<span class="sesion-rango">' . h($_SESSION['rango']) . '</span></a>';
    echo '<a class="sesion-salir" href="' . h(url_logout($raiz)) . '">Cerrar sesión</a>';
    echo '</div>';
}

function mostrar_error($raiz, $titulo, $mensaje, $botones, $estado = 400) {
    http_response_code($estado);
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= h($titulo) ?> | Biblioteca Común</title>
<?php estilo_sesion($raiz); ?>
</head>
<body class="aviso-pagina">
<header>
<a class="marca" href="<?= h(url_principal($raiz)) ?>">
<svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 5.5C4 4.7 4.7 4 5.5 4H11v15H5.5C4.7 19 4 18.3 4 17.5v-12z"/><path d="M20 5.5c0-.8-.7-1.5-1.5-1.5H13v15h5.5c.8 0 1.5-.7 1.5-1.5v-12z"/></svg>
<span>Biblioteca Común</span>
</a>
<?php mostrar_sesion($raiz); ?>
</header>
<main class="aviso-contenido">
<section class="aviso">
<div class="aviso-icono">✕</div>
<p class="aviso-etiqueta">BIBLIOTECA COMÚN</p>
<h1><?= h($titulo) ?></h1>
<p><?= h($mensaje) ?></p>
<div class="aviso-acciones">
<?php foreach ($botones as $i => $boton): ?>
<a href="<?= h($boton[1]) ?>" class="aviso-boton<?= $i > 0 ? ' secundario' : '' ?>"><?= h($boton[0]) ?></a>
<?php endforeach; ?>
</div>
</section>
</main>
</body>
</html>
<?php
    exit;
}

function exigir_sesion($raiz) {
    if (sesion_activa()) return;
    mostrar_error($raiz, 'Necesitás iniciar sesión', 'Para ingresar a esta página primero tenés que iniciar sesión con tu usuario y contraseña.', [['Iniciar sesión', url_login($raiz)], ['Volver a la página principal', url_principal($raiz)]], 401);
}

function exigir_admin($raiz) {
    exigir_sesion($raiz);
    if (es_admin()) return;
    mostrar_error($raiz, 'Acceso restringido', 'Tu rango (' . $_SESSION['rango'] . ') no tiene permiso para ingresar a esta página. Solo los bibliotecarios y directivos pueden hacerlo.', [['Ir a mi carnet', url_carnet($raiz)], ['Volver a la página principal', url_principal($raiz)]], 403);
}
