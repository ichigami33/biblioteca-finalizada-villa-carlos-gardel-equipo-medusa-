<?php
require_once __DIR__ . '/../Inicio_Sesion/sesion.php';
$raiz = '../';
exigir_admin($raiz);
mysqli_report(MYSQLI_REPORT_OFF);
$urlBiblioteca = '../Biblioteca_Comun/Archivos_Biblioteca_Comun/Pagina_Biblioteca_Comun.php';

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: Agregar_libro.php");
    exit;
}

$exito = false;
$mensaje = "Ocurrió un problema al crear la sancion.";
$id = trim($_POST["id"] ?? "");
$sans1 = trim($_POST["sans1"] ?? "");

try {
    include __DIR__ . '/conexion.php';

    if ($id === "" || $sans1 === "" ) {
        $mensaje = "Completá todos los campos antes de guardar el libro.";
    } elseif (!ctype_digit($id)) {
        $mensaje = "Los datos ingresados no tienen un formato válido.";
    } elseif (mb_strlen($sans1) > 999) {
        $mensaje = "El texto de la sancion debe ser menor a 999 caracteres.";
    } else {
        $id = (int)$id;
        $sql = "INSERT INTO sansl (id, sans1) VALUES (?, ?)";
        $stmt = mysqli_prepare($conexion, $sql);
        if (!$stmt) {
            $mensaje = "No se pudo preparar el guardado de la sancion.";
        } else {
            mysqli_stmt_bind_param($stmt, "is", $id, $sans1);
            if (mysqli_stmt_execute($stmt)) {
                $exito = true;
            } else {
                $codigo_error = mysqli_stmt_errno($stmt);
                if ($codigo_error === 1062) {
                    $mensaje = "Ya existe un libro registrado con ese ISBN.";
                } elseif ($codigo_error === 1264) {
                    $mensaje = "El ISBN ingresado supera el tamaño máximo que admite la base de datos (2147483647).";
                } elseif ($codigo_error === 1406) {
                    $mensaje = "Alguno de los textos ingresados es demasiado largo para guardarse.";
                } else {
                    $mensaje = "No se pudo guardar el libro. Revisá los datos e intentá nuevamente.";
                }
            }
            mysqli_stmt_close($stmt);
        }
    }
    mysqli_close($conexion);
} catch (Throwable $e) {
    $mensaje = "Error: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $exito ? 'Libro agregado' : 'Error' ?> | Biblioteca Común</title>
    <link rel="stylesheet" href="styles.css">
    <?php estilo_sesion($raiz); ?>
</head>
<body>
    <header>
        <a href="<?= h($urlBiblioteca) ?>" class="marca">Biblioteca Común</a>
        <?php mostrar_sesion($raiz); ?>
    </header>
    <main class="contenido">
        <section class="exito<?= $exito ? '' : ' error' ?>">
            <div class="exito-icono"><?= $exito ? '✓' : '✕' ?></div>
            <p class="formulario-etiqueta">BIBLIOTECA COMÚN</p>
            <?php if ($exito): ?>
            <h1>Sancion creada correctamente</h1>
            <p><strong><?= h($id) ?></strong> a recibido la sancion especificada.</p>
            <div class="acciones">
                <a href="Agregar_libro.php" class="boton-guardar">Agregar otra sancion</a>
            </div>
            <?php else: ?>
            <h1>No se pudo agregar la sancion</h1>
            <p><?= h($mensaje) ?></p>
            <div class="acciones">
                <a href="Agregar_libro.php" class="boton-guardar">Volver a intentar</a>
            </div>
            <?php endif; ?>
        </section>
    </main>
</body>
</html>
