<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();



require_once "conexion.php";

if (!isset($_GET['isbn'])) {
    die("No se recibió ningún ISBN.");
}

$isbn = $_GET['isbn'];

echo "ISBN recibido: " . htmlspecialchars($isbn) . "<br>";

$stmt = $conexion->prepare("
    SELECT isbn, tema, autor, stock, nombrel, editorial
    FROM librost
    WHERE isbn = ?
");

if ($stmt === false) {
    die("Error en la consulta SQL: " . $conexion->error);
}

$stmt->bind_param("s", $isbn);

$stmt->execute();

$resultado = $stmt->get_result();

if ($resultado->num_rows != 1) {
    die("Libro no encontrado. ISBN buscado: " . htmlspecialchars($isbn));
}

$libro = $resultado->fetch_assoc();

echo "Libro encontrado: " . htmlspecialchars($libro['nombrel']) . "<br>";







if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nuevostock = (int)$_POST['nueva_cantidad'];

    $stmtstock = $conexion->prepare("
        UPDATE librost
        SET stock = ?
        WHERE isbn = ?
    ");

    if ($stmtstock === false) {
        die("Error al modificar el stock: " . $conexion->error);
    }

    $stmtstock->bind_param("is", $nuevostock, $isbn);

    $stmtstock->execute();

    $stmtstock->close();

    header("Location: ../Biblioteca_comun/Archivos_Biblioteca_Comun/Pagina_Biblioteca_Comun.php");
    exit();
}

?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carnet del Estudiante - Biblioteca Virtual</title>
    <link rel="stylesheet" href="css.css">
</head>

<body>

<header id="header">

    <a class="marca" href="../Pagina_Principal/pagina_principal_biblioteca/Pagina_principal.php">
        <svg width="30" height="30" viewBox="0 0 24 24"
            fill="none"
            stroke="currentColor"
            stroke-width="1.8"
            stroke-linecap="round"
            stroke-linejoin="round"
            aria-hidden="true">

            <path d="M4 5.5C4 4.7 4.7 4 5.5 4H11v15H5.5C4.7 19 4 18.3 4 17.5v-12z"/>
            <path d="M20 5.5c0-.8-.7-1.5-1.5-1.5H13v15h5.5c.8 0 1.5-.7 1.5-1.5v-12z"/>

        </svg>

        <span>Biblioteca Común</span>
    </a>


    <nav class="navegacion">

        <a href="#inicio">Inicio</a>
        <a href="#accesos">Accesos</a>
        <a href="#informacion">Información</a>

    </nav>


    <a href="#" class="boton-login">
        Iniciar sesión
    </a>


    <button
        class="menu-movil"
        id="menuMovil"
        aria-label="Abrir menú"
        aria-expanded="false">

        <span></span>
        <span></span>
        <span></span>

    </button>

</header>


<div class="sub-header">

    <span>Biblioteca Virtual</span>

    <span class="separador">•</span>

    <strong>Villa Gardel</strong>

</div>

<main id="inicio">

    <section class="recuadro_al_centro">

        <div class="carnet">

            <div class="titulo_carnet">
                <h1>INFORMACIÓN DEL LIBRO</h1>
                
            </div>

            <div class="contenido_carnet">
                <div class="datos_estudiante">

                    <div class="dato">
                        <span class="etiqueta">NOMBRE</span>
                        <strong><?php echo htmlspecialchars($libro['nombrel']); ?></strong>
                    </div>

                    <div class="dato">
                        <span class="etiqueta">ISBM</span>
                        <strong><?php echo htmlspecialchars($libro['isbn']); ?></strong>
                    </div>

                    <div class="dato">
                        <span class="etiqueta">AUTOR</span>
                        <strong><?php echo htmlspecialchars($libro['autor']); ?></strong>
                    </div>

                    <div class="dato">
                        <span class="etiqueta">EDITORIAL</span>
                        <strong><?php echo htmlspecialchars($libro['editorial']); ?></strong>
                    </div>

                </div>

            </div>

            <div class="titulo_seccion">
                <table class="pepe">
                    <tr>
                        <td class="pepito1">
                            <h2><strong>stock actual:  <?php echo htmlspecialchars($libro['stock']); ?></strong></h2>
                        </td>


                        <?php if (
                            $_SESSION['rango'] == 'Bibliotecario' ||
                            $_SESSION['rango'] == 'Directivo'
                            ): ?>
                            
                            <td class="pepito2">

                                <div class="contenedor_añadir">
                                    <form method="POST" class="numeros">
                                        <input type="number" name="nueva_cantidad" min="0" value="<?php echo htmlspecialchars($libro['stock']);?>" requiered>
                                        
                                        <button type="submit" class="añadir">
                                            modificar stock
                                        </button>
                                    </form>
                            </div>
                        </td>
                        <?php endif; ?>
                    </tr>
                </table>
                
            </div>

            <p></p>
        </div>

    </section>

</main>


<footer>

    <div class="parte_de_abajo">
        <span>
            Todos los derechos reservados - Colegio Villa Carlos Gardel
        </span>
    </div>

</footer>


<script src="script/municipio.js"></script>

</body>
</html>