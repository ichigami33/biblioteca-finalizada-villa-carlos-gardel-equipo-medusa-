<?php
require_once __DIR__ . '/../Inicio_Sesion/sesion.php';
$raiz = '../';
exigir_admin($raiz);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar libro | Biblioteca Común</title>
    <link rel="stylesheet" href="styles.css">
    <?php estilo_sesion($raiz); ?>
</head>
<body>
    <header>
        <a href="../Biblioteca_Comun/Archivos_Biblioteca_Comun/Pagina_Biblioteca_Comun.php" class="marca">Biblioteca Común</a>
    <?php mostrar_sesion($raiz); ?>
    </header>
    <main class="contenido">
        <section class="formulario-contenedor">
            <div class="formulario-cabecera">
                <span class="formulario-icono">+</span>
                <div>
                    <p class="formulario-etiqueta">BIBLIOTECA COMÚN</p>
                    <h1>Agregar un libro</h1>
                    <p>Completá los datos del libro para incorporarlo al catálogo.</p>
                </div>
            </div>
            <form action="guardar.php" method="POST" class="formulario-libro">
                <div class="campo">
                    <label for="isbn">ISBN</label>
                    <input type="number" id="isbn" name="isbn" min="1" required placeholder="Ej: 9789504912345">
                </div>
                <div class="campo">
                    <label for="nombrel">Título del libro</label>
                    <input type="text" id="nombrel" name="nombrel" maxlength="30" required placeholder="Ej: El Principito">
                </div>
                <div class="campo">
                    <label for="autor">Autor</label>
                    <input type="text" id="autor" name="autor" maxlength="20" required placeholder="Ej: Antoine de Saint-Exupéry">
                </div>
                <div class="campo">
                    <label for="editorial">Editorial</label>
                    <input type="text" id="editorial" name="editorial" maxlength="30" required placeholder="Ej: Salamandra">
                </div>
                <div class="campo">
                    <label for="tema">Género</label>
                    <select id="tema" name="tema" required>
                        <option value="">Seleccioná un género</option>
                        <option value="1">Novela Juvenil</option>
                        <option value="2">Literatura Clásica</option>
                        <option value="3">Ciencia Ficción</option>
                        <option value="4">Cuentos Cortos</option>
                        <option value="5">Terror Suspenso</option>
                        <option value="6">Divulgación Científica</option>
                        <option value="7">Historia</option>
                        <option value="8">Poesía</option>
                        <option value="9">Comics Novela Gráfica</option>
                        <option value="10">Autoayuda</option>
                    </select>
                </div>
                <div class="campo">
                    <label for="stock">Cantidad disponible</label>
                    <input type="number" id="stock" name="stock" min="0" max="99" required placeholder="Ej: 5">
                </div>
                <div class="acciones">
                    <a class="boton-volver" href="../Biblioteca_Comun/Archivos_Biblioteca_Comun/Pagina_Biblioteca_Comun.php">
                        Volver a la biblioteca
                    </a>
                    <button type="submit" class="boton-guardar">
                        Guardar libro
                    </button>
                </div>
            </form>
        </section>
    </main>
</body>
</html>
