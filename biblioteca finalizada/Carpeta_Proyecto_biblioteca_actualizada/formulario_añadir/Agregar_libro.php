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
                <span class="formulario-icono"><b>✕</b></span>
                <div>
                    <p class="formulario-etiqueta">Sanciones</p>
                    <h1>Dar Sanción</h1>
                    <p>Completá la informacion.</p>
                </div>
            </div>
            <form action="guardar.php" method="POST" class="formulario-libro">
            
                <div class="campo2">
                    <label for="nombrel">Conducta a Sancionar</label>
                    <textarea type="text" id="sans1" name="sans1" maxlength="999" required placeholder="Motivo por el cual recibe la sanción."></textarea>
                </div>
                <div class="campo">
                    <label for="isbn">ID</label>
                    <input type="number" id="id" name="id" min="1" required placeholder="ID segun se muestra en la tabla">
                </div>
            
                <div class="acciones">
                    <a class="boton-volver" href="../Biblioteca_Comun/Archivos_Biblioteca_Comun/Pagina_Biblioteca_Comun.php">
                        Volver a la biblioteca
                    </a>
                    <button type="submit" class="boton-guardar">
                        Crear sancion
                    </button>
                </div>
            </form>
        </section>
    </main>
</body>
</html>
