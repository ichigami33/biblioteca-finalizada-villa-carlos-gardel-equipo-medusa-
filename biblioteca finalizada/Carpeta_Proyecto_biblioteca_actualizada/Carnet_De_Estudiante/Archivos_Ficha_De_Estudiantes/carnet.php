<?php
require_once __DIR__ . '/../../Inicio_Sesion/sesion.php';
$raiz = '../../';
exigir_sesion($raiz);

$id = isset($_GET['id']) ? (int)$_GET['id'] : (int)$_SESSION['id'];
$botones = es_admin()
    ? [['Volver a la tabla de usuarios', $raiz . 'tabla_usuarios/pagina_tabla_usuarios.php'], ['Volver a la página principal', url_principal($raiz)]]
    : [['Ir a mi carnet', url_carnet($raiz)], ['Volver a la página principal', url_principal($raiz)]];

if ($id <= 0) {
    mostrar_error($raiz, 'Carnet no válido', 'El carnet que intentás abrir no es válido. Volvé e intentá de nuevo.', $botones);
}

if (!es_admin() && $id !== (int)$_SESSION['id']) {
    mostrar_error($raiz, 'Acceso restringido', 'Solo podés consultar tu propio carnet de estudiante.', $botones, 403);
}

try {
    require_once __DIR__ . '/conexion.php';

    // Obtener los datos del alumno
    $stmt = $conexion->prepare(
        'SELECT id, usuario, tel, mail, sans, curso_actual, rango
         FROM usuarios
         WHERE id = ?'
    );

    $stmt->bind_param('i', $id);
    $stmt->execute();

    $alumno = $stmt->get_result()->fetch_assoc();

    $stmt->close();

    // Obtener todas las sanciones de este alumno
    $stmt_sanciones = $conexion->prepare(
        'SELECT sans1
         FROM sansl
         WHERE id = ?
         ORDER BY id'
    );

    $stmt_sanciones->bind_param('i', $id);
    $stmt_sanciones->execute();

    $resultado_sanciones = $stmt_sanciones->get_result();

    $sanciones = [];

    while ($sancion = $resultado_sanciones->fetch_assoc()) {
        $sanciones[] = $sancion['sans1'];
    }

    $stmt_sanciones->close();

    $conexion->close();

} 

catch (Throwable $e) {
    $mensaje = "Error: " . $e->getMessage();
}

if (!$alumno) {
    mostrar_error($raiz, 'Usuario no encontrado', 'No existe ningún usuario registrado con ese número de identificación.', $botones, 404);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nuevo_curso = $_POST['cam_curso'];

    $stmtcurso = $conexion->prepare("
        UPDATE usuarios
        SET curso_actual = ?
        WHERE id = ?
    ");

    if ($stmtcurso === false) {
        die("Error al modificar el curso_actual: " . $conexion->error);
    }

    $stmtcurso->bind_param("si", $nuevo_curso, $id);

    $stmtcurso->execute();

    $stmtcurso->close();

    header("Location: ../../tabla_usuarios/pagina_tabla_usuarios.php");
    exit();
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carnet del Estudiante - Biblioteca Virtual</title>
    <link rel="stylesheet" href="t.css">
    <?php estilo_sesion($raiz); ?>
</head>
<body>
<header>
    <a class="marca" href="<?= h(url_principal($raiz)) ?>">
        <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <path d="M4 5.5C4 4.7 4.7 4 5.5 4H11v15H5.5C4.7 19 4 18.3 4 17.5v-12z"/>
            <path d="M20 5.5c0-.8-.7-1.5-1.5-1.5H13v15h5.5c.8 0 1.5-.7 1.5-1.5v-12z"/>
        </svg>
        <span>Biblioteca Común</span>
    </a>
    <?php mostrar_sesion($raiz); ?>
</header>
<div class="sub-header">
    <span>Biblioteca Virtual</span>
    <span class="separador">•</span>
    <strong>Villa Gardel</strong>
</div>
<main>
    <section class="recuadro_al_centro">
        <div class="carnet">
            <div class="titulo_carnet">
                <h1>CARNET DEL ESTUDIANTE</h1>
            </div>
            <div class="contenido_carnet">
                <div class="datos_estudiante">
                    <div class="dato">
                        <span class="etiqueta">NOMBRE</span>
                        <strong><?= h($alumno['usuario']) ?></strong>
                    </div>
                    <div class="dato">
                        <span class="etiqueta">ID</span>
                        <strong><?= h($alumno['id']) ?></strong>
                    </div>

                    <div class="dato">
                        <span class="etiqueta">MAIL</span>
                        <strong><?= h($alumno['mail']) ?></strong>
                    </div>
                    <div class="dato">
                        <span class="etiqueta">Telefono</span>
                        <strong><?= h($alumno['tel']) ?></strong>
                    </div>
                    <div class="dato">
                        <span class="etiqueta">Rango en el sistema</span>
                        <strong><?= h($alumno['rango']) ?></strong>
                    </div>
                    <div class="dato">
                        <span class="etiqueta">Curso</span>
                        <strong><?= h($alumno['curso_actual']) ?></strong>

                        <?php if (
                            $_SESSION['rango'] == 'Bibliotecario' ||
                            $_SESSION['rango'] == 'Directivo'
                            ): ?>
                            <form method="POST">
                                <select id="cam_curso" name="cam_curso">
                                    <option value="">-- Seleccione --</option>
                                    <option value="trabajador"> trabajador de la institucion</option>
                                    <option value="1°A">1°A</option>
                                    <option value="1°B">1°B</option>
                                    <option value="1°C">1°C</option>
                                    <option value="1°D">1°D</option>
                                    <option value="2°A">2°A</option>
                                    <option value="2°B">2°B</option>
                                    <option value="2°C">2°C</option>
                                    <option value="2°D">2°D</option>
                                    <option value="3°A">3°A</option>
                                    <option value="3°B">3°B</option>
                                    <option value="3°C">3°C</option>
                                    <option value="3°D">3°D</option>
                                    <option value="4°A">4°A</option>
                                    <option value="4°B">4°B</option>
                                    <option value="4°C">4°C</option>
                                    <option value="4°D">4°D</option>
                                    <option value="5°A">5°A</option>
                                    <option value="5°B">5°B</option>
                                    <option value="5°C">5°C</option>
                                    <option value="5°D">5°D</option>
                                    <option value="6°A">6°A</option>
                                    <option value="6°B">6°B</option>
                                    <option value="6°C">6°C</option>
                                    <option value="6°D">6°D</option>
                                </select>
                                <button type="submit" class="añadir">
                                    modificar ano
                                </button>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
            </div>




            <div class="titulo_seccion">
                <table class="pepe">
                    <tr>
                        <td class="pepito1">
                            <h2>SANCIONES</h2>
                        </td>
                        <td class="pepito2"></td>
                        <td class="pepito1">
                            <div class="contenedor_añadir">
                                <a
                                    href="../../formulario_añadir/Agregar_libro.php"
                                    class="hero-boton"
                                >
                                    Añadir
                                </a>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>

            <div class="lista_sanciones">

                <?php if (empty($sanciones)): ?>

                <div class="sancion sin_sanciones">
                    <div class="icono_sancion">✓</div>
                    <div>
                        <h3>Sin sanciones activas</h3>
                        <p>El estudiante no posee sanciones actualmente.</p>
                    </div>
                </div>

            <?php else: ?>

               <?php foreach ($sanciones as $sancion): ?>

                    <div class="sancion">
                        <div class="icono_sancion">!</div>
                        <div>
                           <h3>Sanción</h3>
                            <p><?= h($sancion) ?></p>
                        </div>
                    </div>

                <?php endforeach; ?>

            <?php endif; ?>
        </div>
    </section>
</main>
<footer>
    <div class="parte_de_abajo">
        <span>Todos los derechos reservados - Colegio Villa Carlos Gardel</span>
    </div>
</footer>
<script src="script/municipio.js"></script>
</body>
</html>
