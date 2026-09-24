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
$mensaje = "Ocurrió un problema al guardar el libro.";
$isbn = trim($_POST["isbn"] ?? "");
$nombrel = trim($_POST["nombrel"] ?? "");
$autor = trim($_POST["autor"] ?? "");
$editorial = trim($_POST["editorial"] ?? "");
$tema = trim($_POST["tema"] ?? "");
$stock = trim($_POST["stock"] ?? "");

try {
    include __DIR__ . '/conexion.php';

    if ($isbn === "" || $nombrel === "" || $autor === "" || $editorial === "" || $tema === "" || $stock === "") {
        $mensaje = "Completá todos los campos antes de guardar el libro.";
    } elseif (!ctype_digit($isbn) || !ctype_digit($tema) || !ctype_digit($stock)) {
        $mensaje = "Los datos ingresados no tienen un formato válido.";
    } elseif ((int)$tema < 1 || (int)$tema > 10) {
        $mensaje = "El género seleccionado no es válido.";
    } elseif ((int)$stock < 0 || (int)$stock > 99) {
        $mensaje = "La cantidad disponible debe estar entre 0 y 99.";
    } elseif (mb_strlen($nombrel) > 30) {
        $mensaje = "El título del libro no puede superar los 30 caracteres.";
    } elseif (mb_strlen($autor) > 20) {
        $mensaje = "El nombre del autor no puede superar los 20 caracteres.";
    } elseif (mb_strlen($editorial) > 30) {
        $mensaje = "La editorial no puede superar los 30 caracteres.";
    } else {
        $isbn = (int)$isbn;
        $tema = (int)$tema;
        $stock = (int)$stock;
        $sql = "INSERT INTO librost (isbn, tema, autor, editorial, stock, nombrel) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conexion, $sql);
        if (!$stmt) {
            $mensaje = "No se pudo preparar el guardado del libro.";
        } else {
            mysqli_stmt_bind_param($stmt, "iissis", $isbn, $tema, $autor, $editorial, $stock, $nombrel);
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
    $mensaje = "No se pudo conectar con la base de datos. Verificá que el servidor esté encendido e intentá nuevamente.";
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
            <h1>Libro agregado correctamente</h1>
            <p><strong><?= h($nombrel) ?></strong> fue agregado al catálogo de la biblioteca.</p>
            <div class="acciones">
                <a href="<?= h($urlBiblioteca) ?>" class="boton-volver">Ir a la biblioteca</a>
                <a href="Agregar_libro.php" class="boton-guardar">Agregar otro libro</a>
            </div>
            <?php else: ?>
            <h1>No se pudo agregar el libro</h1>
            <p><?= h($mensaje) ?></p>
            <div class="acciones">
                <a href="Agregar_libro.php" class="boton-guardar">Volver a intentar</a>
                <a href="<?= h($urlBiblioteca) ?>" class="boton-volver">Ir a la biblioteca</a>
            </div>
            <?php endif; ?>
        </section>
    </main>
</body>
</html>
