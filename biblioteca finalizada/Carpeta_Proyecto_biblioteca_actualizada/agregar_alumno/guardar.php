<?php
require_once __DIR__ . '/../Inicio_Sesion/sesion.php';
$raiz = '../';
exigir_admin($raiz);
mysqli_report(MYSQLI_REPORT_OFF);

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: Agregar_alumno.php");
    exit;
}

$exito = false;
$mensaje = "Ocurrió un problema al registrar el alumno.";
$alumno = trim($_POST["usuario"] ?? "");
$contraseña = $_POST["contraseña"] ?? "";
$tel = trim($_POST["tel"] ?? "");
$mail = trim($_POST["mail"] ?? "");
$curso_actual = trim($_POST["curso_actual"] ?? "");
$rango = trim($_POST["rango"] ?? "");

try {
    include __DIR__ . '/conexion.php';

    if ($alumno === "" || $contraseña === "" || $tel === "" || $mail === "" || $curso_actual === "" || $rango === "") {
        $mensaje = "Completá todos los campos antes de guardar el alumno.";
    } elseif (mb_strlen($alumno) > 30) {
        $mensaje = "El nombre de usuario no puede superar los 30 caracteres.";
    } elseif (mb_strlen($contraseña) > 500) {
        $mensaje = "La contraseña ingresada es demasiado larga.";
    } elseif (!ctype_digit($tel)) {
        $mensaje = "El teléfono debe contener solamente números.";
    } elseif (mb_strlen($tel) > 10) {
        $mensaje = "El teléfono no puede superar los 10 números.";
    } elseif (!filter_var($mail, FILTER_VALIDATE_EMAIL)) {
        $mensaje = "Ingresá un correo electrónico válido.";
    } elseif (mb_strlen($mail) > 60) {
        $mensaje = "El correo electrónico no puede superar los 60 caracteres.";
    } elseif (mb_strlen($curso_actual) > 30) {
        $mensaje = "El curso seleccionado no es válido.";
    } elseif (!in_array($rango, ["Alumno", "Bibliotecario", "Directivo"], true)) {
        $mensaje = "El rango seleccionado no es válido.";
    } else {
        $hash = password_hash($contraseña, PASSWORD_DEFAULT);
        $sql = "INSERT INTO usuarios (usuario, contraseña, tel, mail, sans, libros, curso_actual, rango) VALUES (?, ?, ?, ?, 0, 0, ?, ?)";
        $stmt = mysqli_prepare($conexion, $sql);
        if (!$stmt) {
            $mensaje = "No se pudo preparar el registro del alumno.";
        } else {
            mysqli_stmt_bind_param($stmt, "ssssss", $alumno, $hash, $tel, $mail, $curso_actual, $rango);
            if (mysqli_stmt_execute($stmt)) {
                $exito = true;
            } else {
                $codigo_error = mysqli_stmt_errno($stmt);
                $detalle = mysqli_stmt_error($stmt);
                preg_match("/for key '(?:\w+\.)?(\w+)'/", $detalle, $clave);
                $clave = $clave[1] ?? "";
                if ($codigo_error === 1062 && $clave === "mail") {
                    $mensaje = "Ese correo electrónico ya está registrado en otro usuario.";
                } elseif ($codigo_error === 1062 && $clave === "tel") {
                    $mensaje = "Ese teléfono ya está registrado en otro usuario.";
                } elseif ($codigo_error === 1062) {
                    $mensaje = "Ese nombre de usuario ya está registrado.";
                } elseif ($codigo_error === 1264) {
                    $mensaje = "Alguno de los números ingresados supera el tamaño que admite la base de datos (por ejemplo, el teléfono).";
                } elseif ($codigo_error === 1406) {
                    $mensaje = "Alguno de los datos ingresados es demasiado largo para guardarse.";
                } else {
                    $mensaje = "No se pudo registrar el alumno. Revisá los datos e intentá nuevamente.";
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
    <title><?= $exito ? 'Alumno agregado' : 'Error' ?> | Tabla de Usuarios</title>
    <link rel="stylesheet" href="styles.css">
    <?php estilo_sesion($raiz); ?>
</head>
<body>
    <header>
        <a href="../tabla_usuarios/pagina_tabla_usuarios.php" class="marca">Tabla de Usuarios</a>
        <?php mostrar_sesion($raiz); ?>
    </header>
    <main class="contenido">
        <section class="exito<?= $exito ? '' : ' error' ?>">
            <div class="exito-icono"><?= $exito ? '✓' : '✕' ?></div>
            <p class="formulario-etiqueta">TABLA DE USUARIOS</p>
            <?php if ($exito): ?>
            <h1>Alumno agregado correctamente</h1>
            <p>El usuario <strong><?= h($alumno) ?></strong> fue registrado correctamente en el sistema.</p>
            <div class="acciones">
                <a href="../tabla_usuarios/pagina_tabla_usuarios.php" class="boton-volver">Ir a la Tabla de Usuarios</a>
                <a href="Agregar_alumno.php" class="boton-guardar">Agregar otro alumno</a>
            </div>
            <?php else: ?>
            <h1>No se pudo agregar el alumno</h1>
            <p><?= h($mensaje) ?></p>
            <div class="acciones">
                <a href="Agregar_alumno.php" class="boton-guardar">Volver a intentar</a>
                <a href="../tabla_usuarios/pagina_tabla_usuarios.php" class="boton-volver">Ir a la Tabla de Usuarios</a>
            </div>
            <?php endif; ?>
        </section>
    </main>
</body>
</html>
