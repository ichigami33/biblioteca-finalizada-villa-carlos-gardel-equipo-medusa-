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
    <title>Agregar alumno | Tabla de Usuarios</title>
    <link rel="stylesheet" href="styles.css">
    <?php estilo_sesion($raiz); ?>
</head>
<body>
    <header>
        <a href="../tabla_usuarios/pagina_tabla_usuarios.php" class="marca">
            Tabla de Usuarios
        </a>
    <?php mostrar_sesion($raiz); ?>
    </header>
    <main class="contenido">
        <section class="formulario-contenedor">
            <div class="formulario-cabecera">
                <span class="formulario-icono">+</span>
                <div>
                    <p class="formulario-etiqueta">Tabla de Usuarios</p>
                    <h1>Agregar un alumno</h1>
                    <p>Completá los datos del alumno para registrarlo en el sistema.</p>
                </div>
            </div>

            
            <form action="guardar.php" method="POST" class="formulario-libro">
                <div class="campo">
                    <label for="usuario">Nombre de Usuario</label>
                    <input
                        type="text"
                        id="usuario"
                        name="usuario"
                        maxlength="30"
                        required
                        placeholder="Ej: juanperez"
                    >
                </div>
                <div class="campo">
                    <label for="contraseña">Contraseña</label>
                    <input
                        type="password"
                        id="contraseña"
                        name="contraseña"
                        maxlength="500"
                        required
                        placeholder="Ingresá una contraseña"
                    >
                </div>
                <div class="campo">
                    <label for="tel">Teléfono</label>
                    <input
                        type="number"
                        id="tel"
                        name="tel"
                        required
                        placeholder="Ej: 1123456789"
                    >
                </div>
                <div class="campo">
                    <label for="mail">Email</label>
                    <input
                        type="email"
                        id="mail"
                        name="mail"
                        maxlength="60"
                        required
                        placeholder="Ej: alumno@gmail.com"
                    >
                </div>
                <div class="campo">
                    <label for="curso_actual">Curso</label>
                    <select id="curso_actual" name="curso_actual" required>
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
                </div>
                <div class="campo">
                    <label for="rango">Rango en el sistema</label>
                    <select id="rango" name="rango" required>
                        <option value="">-- seleccione el rango en el sistema --</option>
                        <option value="Alumno">Alumno</option>
                        <option value="Bibliotecario">Bibliotecario</option>
                        <option value="Directivo">Directivo</option>
                    </select>
                </div>
                <div class="acciones">
                    <a
                        class="boton-volver"
                        href="../tabla_usuarios/pagina_tabla_usuarios.php"
                    >
                        Volver a la Tabla de Usuarios
                    </a>
                    <button type="submit" class="boton-guardar">
                        Guardar alumno
                    </button>
                </div>
            </form>
        </section>
    </main>
</body>
</html>
