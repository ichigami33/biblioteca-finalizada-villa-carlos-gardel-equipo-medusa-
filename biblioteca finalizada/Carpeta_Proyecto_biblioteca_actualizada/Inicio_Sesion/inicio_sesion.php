<?php
require_once __DIR__ . "/sesion.php";
$raiz = "../";
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >
    <title>Iniciar sesión | Biblioteca Virtual</title>
    <link
        rel="stylesheet"
        href="login.css"
    >
<?php estilo_sesion($raiz); ?>
</head>
<body>
<header id="header">
    <a
        class="marca"
        href="../Pagina_Principal/pagina_principal_biblioteca/Pagina_principal.php"
    >
        <svg
            width="30"
            height="30"
            viewBox="0 0 24 24"
            fill="none"
            stroke="currentColor"
            stroke-width="1.8"
            stroke-linecap="round"
            stroke-linejoin="round"
            aria-hidden="true"
        >
            <path
                d="M4 5.5C4 4.7 4.7 4 5.5 4H11v15H5.5C4.7 19 4 18.3 4 17.5v-12z"
            />
            <path
                d="M20 5.5c0-.8-.7-1.5-1.5-1.5H13v15h5.5c.8 0 1.5-.7 1.5-1.5v-12z"
            />
        </svg>
        <span>Biblioteca Común</span>
    </a>
    <nav class="navegacion">
        <a
            href="../Pagina_Principal/pagina_principal_biblioteca/Pagina_principal.php#inicio"
        >
            Inicio
        </a>
        <a
            href="../Pagina_Principal/pagina_principal_biblioteca/Pagina_principal.php#accesos"
        >
            Accesos
        </a>
        <a
            href="../Pagina_Principal/pagina_principal_biblioteca/Pagina_principal.php#informacion"
        >
            Información
        </a>
    </nav>
    <?php mostrar_sesion($raiz, "activo"); ?>
</header>
<div class="sub-header">
    <span>Biblioteca Virtual</span>
    <span class="separador">•</span>
    <strong>Villa Gardel</strong>
</div>
<main>
    <section class="login-contenedor">
        <div class="login-tarjeta">
            <div class="login-encabezado">
                <div class="icono-libro">
                    <svg
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.7"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                    >
                        <path
                            d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"
                        />
                        <path
                            d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"
                        />
                    </svg>
                </div>
                <span class="etiqueta">
                    Biblioteca comunitaria
                </span>
                <h1>
                    Iniciar sesión
                </h1>
                <p>
                    Ingresá a tu cuenta para acceder
                    a los recursos de la biblioteca.
                </p>
            </div>
            <?php if (isset($_GET['error'])): ?>
                <div class="mensaje-error">
                    <?php
                    if ($_GET['error'] === 'usuario') {
                        echo "El usuario no existe.";
                    } elseif ($_GET['error'] === 'contraseña') {
                        echo "La contraseña es incorrecta.";
                    } elseif ($_GET['error'] === 'campos') {
                        echo "Completá todos los campos.";
                    } else {
                        echo "Ocurrió un error al iniciar sesión.";
                    }
                    ?>
                </div>
            <?php endif; ?>
            <form
                action="procesar_login.php"
                method="POST"
                class="formulario-login"
            >
                <div class="campo">
                    <label for="usuario">
                        Usuario
                    </label>
                    <div class="input-con-icono">
                        <svg
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.7"
                        >
                            <circle
                                cx="12"
                                cy="8"
                                r="4"
                            />
                            <path
                                d="M4 21a8 8 0 0 1 16 0"
                            />
                        </svg>
                        <input
                            type="text"
                            id="usuario"
                            name="usuario"
                            placeholder="Ingresá tu usuario"
                            autocomplete="username"
                            required
                        >
                    </div>
                </div>
                <div class="campo">
                    <label for="contraseña">
                        Contraseña
                    </label>
                    <div class="input-con-icono">
                        <svg
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.7"
                        >
                            <rect
                                x="4"
                                y="10"
                                width="16"
                                height="11"
                                rx="2"
                            />
                            <path
                                d="M8 10V7a4 4 0 0 1 8 0v3"
                            />
                        </svg>
                        <input
                            type="password"
                            id="contraseña"
                            name="contraseña"
                            placeholder="Ingresá tu contraseña"
                            autocomplete="current-password"
                            required
                        >
                    </div>
                </div>
                <button
                    type="submit"
                    class="boton-ingresar"
                >
                    <span>
                        Ingresar
                    </span>
                    <span class="flecha">
                        →
                    </span>
                </button>
            </form>
            <div class="volver">
                <a
                    href="../Pagina_Principal/pagina_principal_biblioteca/Pagina_principal.php"
                >
                    ← Volver a la pagina principal
                </a>
            </div>
        </div>
    </section>
</main>
<footer>
    <div class="footer-contenido">
        <div class="footer-marca">
            Biblioteca Común
        </div>
        <p>
            Todos los derechos reservados — Grupo Medusa
        </p>
    </div>
</footer>
</body>
</html>
