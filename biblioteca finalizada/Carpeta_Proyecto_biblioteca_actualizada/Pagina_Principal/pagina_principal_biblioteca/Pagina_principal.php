<?php
require_once __DIR__ . '/../../Inicio_Sesion/sesion.php';
$raiz = '../../';
$urlBiblioteca = $raiz . 'Biblioteca_Comun/Archivos_Biblioteca_Comun/Pagina_Biblioteca_Comun.php';
$urlTabla = $raiz . 'tabla_usuarios/pagina_tabla_usuarios.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Biblioteca Virtual | Villa Gardel</title>
    <link rel="stylesheet" href="styles.css">
    <?php estilo_sesion($raiz); ?>
</head>
<body>
<header id="header">
    <a class="marca" href="#">
        <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
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
    <?php mostrar_sesion($raiz); ?>
    <button class="menu-movil" id="menuMovil" aria-label="Abrir menú" aria-expanded="false">
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
    <section class="presentacion">
        <div class="presentacion-texto">
            <span class="etiqueta">Biblioteca comunitaria</span>
            <h1>
                Un espacio para
                <span>leer, aprender y compartir.</span>
            </h1>
            <p>
                Accedé a los recursos de la Biblioteca Común de Villa Gardel.
                Encontrá material bibliográfico, información y herramientas
                disponibles para nuestra comunidad.
            </p>
            <a href="#accesos" class="boton-principal">
                Explorar biblioteca
                <span aria-hidden="true">→</span>
            </a>
        </div>
        <div class="presentacion-imagen">
            <img src="./Images_pagina_principal/images (4).jpg" alt="Biblioteca comunitaria">
            <div class="detalle-imagen">
                <strong>Villa Gardel</strong>
                <span>Biblioteca Virtual</span>
            </div>
        </div>
    </section>
    <section class="seccion-accesos" id="accesos">
        <div class="titulo-seccion">
            <span>Accesos</span>
            <h2>¿Qué estás buscando?</h2>
            <p>Ingresá rápidamente a las principales herramientas disponibles.</p>
        </div>
        <div class="contenedores<?php echo es_admin() ? '' : ' uno'; ?>">
            <a href="<?php echo h($urlBiblioteca); ?>" class="tarjeta-acceso"<?php echo sesion_activa() ? '' : ' data-requiere-sesion'; ?>>
                <div class="icono-acceso">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/>
                        <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/>
                    </svg>
                </div>
                <div class="contenido-tarjeta">
                    <h3>Biblioteca Común</h3>
                    <p>Consultá libros, recursos y material disponible en nuestra biblioteca.</p>
                </div>
                <span class="flecha">→</span>
            </a>
            <?php if (es_admin()): ?>
            <a href="<?php echo h($urlTabla); ?>" class="tarjeta-acceso">
                <div class="icono-acceso">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/>
                        <circle cx="9" cy="7" r="4"/>
                        <path d="M22 21v-2a4 4 0 0 0-3-3.87"/>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                    </svg>
                </div>
                <div class="contenido-tarjeta">
                    <h3>Tabla de Alumnos</h3>
                    <p>Administración y consulta de usuarios registrados en el sistema.</p>
                </div>
                <span class="flecha">→</span>
            </a>
            <?php endif; ?>
        </div>
    </section>
    <section class="informacion" id="informacion">
        <div>
            <span class="etiqueta">Nuestra biblioteca</span>
            <h2>El conocimiento al alcance de la comunidad.</h2>
        </div>
        <p>
            Este espacio fue pensado para facilitar el acceso a información,
            libros y diferentes recursos de la Biblioteca Común de Villa Gardel
            desde cualquier dispositivo.
        </p>
    </section>
</main>
<footer>
    <div class="footer-contenido">
        <div class="footer-marca">Biblioteca Común</div>
        <p>Todos los derechos reservados — Grupo Medusa</p>
    </div>
</footer>
<?php if (!sesion_activa()): ?>
<div class="modal" id="modalLogin" role="dialog" aria-modal="true" aria-labelledby="modalTitulo" hidden>
    <div class="modal-caja">
        <div class="modal-icono">!</div>
        <h2 id="modalTitulo">Iniciá sesión para continuar</h2>
        <p>Para ingresar a la Biblioteca Común necesitás iniciar sesión con tu usuario y contraseña.</p>
        <div class="modal-acciones">
            <a href="<?php echo h(url_login($raiz)); ?>" class="modal-boton">Iniciar sesión</a>
            <button type="button" class="modal-boton secundario" data-cerrar>Cancelar</button>
        </div>
    </div>
</div>
<?php endif; ?>
<script>
const header = document.getElementById("header");
let ultimoScroll = 0;
window.addEventListener("scroll", () => {
    const scrollActual = window.pageYOffset;
    if (scrollActual <= 0) {
        header.classList.remove("scroll-down");
        return;
    }
    if (scrollActual > ultimoScroll && scrollActual > 100) {
        header.classList.remove("scroll-up");
        header.classList.add("scroll-down");
    } else {
        header.classList.remove("scroll-down");
        header.classList.add("scroll-up");
    }
    ultimoScroll = scrollActual;
});
const menuMovil = document.getElementById("menuMovil");
const navegacion = document.querySelector(".navegacion");
menuMovil.addEventListener("click", () => {
    navegacion.classList.toggle("activo");
    menuMovil.classList.toggle("activo");
    const abierto = menuMovil.getAttribute("aria-expanded") === "true";
    menuMovil.setAttribute("aria-expanded", !abierto);
});
const modal = document.getElementById("modalLogin");
if (modal) {
    const cerrarModal = () => { modal.hidden = true; };
    document.querySelectorAll("[data-requiere-sesion]").forEach(tarjeta => {
        tarjeta.addEventListener("click", evento => {
            evento.preventDefault();
            modal.hidden = false;
            modal.querySelector(".modal-boton").focus();
        });
    });
    modal.addEventListener("click", evento => {
        if (evento.target === modal || evento.target.hasAttribute("data-cerrar")) cerrarModal();
    });
    document.addEventListener("keydown", evento => {
        if (evento.key === "Escape") cerrarModal();
    });
}
</script>
</body>
</html>
