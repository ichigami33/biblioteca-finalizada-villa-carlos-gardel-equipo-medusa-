<?php
require_once __DIR__ . '/../Inicio_Sesion/sesion.php';
$raiz = '../';
exigir_admin($raiz);
$host = 'localhost';
$dbname = 'bibloteca';
$usuario = 'root';
$password = '';
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $usuario, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    mostrar_error($raiz, 'Error de conexión', 'No se pudo conectar con la base de datos. Verificá que el servidor esté encendido e intentá nuevamente.', [['Volver a intentar', 'pagina_tabla_usuarios.php'], ['Volver a la página principal', url_principal($raiz)]], 500);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >
    <title>Tabla de alumnos | Biblioteca Común</title>
    <link rel="stylesheet" href="styles.css">
    <?php estilo_sesion($raiz); ?>
</head>
<body>
<header id="header">
    <a
        class="marca"
        href="<?php echo h(url_principal($raiz)); ?>">
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
            <path d="M4 5.5C4 4.7 4.7 4 5.5 4H11v15H5.5C4.7 19 4 18.3 4 17.5v-12z"/>
            <path d="M20 5.5c0-.8-.7-1.5-1.5-1.5H13v15h5.5c.8 0 1.5-.7 1.5-1.5v-12z"/>
        </svg>
        <span>Biblioteca Común</span>
    </a>
<?php mostrar_sesion($raiz); ?>
</header>
<section class="hero">
    <img
        class="hero-imagen"
        src="books-5211309_1280.jpg"
        alt="Biblioteca"
    >
    <div class="hero-info">
        <h2>
            Tabla de alumnos
        </h2>
        <p>
            Desde esta sección podés consultar los alumnos
            registrados en la Biblioteca Común.
        </p>
        <p class="hero-dato">
            Seleccioná el nombre de un alumno para acceder
            a su carnet de estudiante.
        </p>
        <a
            href="#alumnos"
            class="hero-boton"
        >
            Ver alumnos
        </a>
    </div>
</section>
<main
    class="catalogo"
    id="alumnos"
>
    <div class="catalogo-cabecera">
        <h1 id="titulo">
            Alumnos registrados
        </h1>
        <p class="catalogo-intro">
            Estos son los alumnos registrados en el sistema.
            Seleccioná un nombre para consultar su información.
        </p>
    </div>
    <div class="zona-filtros">
        <div class="tabla-contenedor">
            <table>
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Curso Actual</th>
                        <th>Mail</th>
                        <th>Telefono</th>
                    </tr>
                </thead>
                <tbody id="feed-contenedores">
                </tbody>
            </table>
        </div>
        <p class="error-tabla" id="error-tabla" hidden>No se pudieron cargar los alumnos. Revisá que el servidor esté encendido; se volverá a intentar automáticamente.</p>
    </div>
</main>
<a
    href="../agregar_alumno/Agregar_alumno.php"
    class="btn-agregar"
    aria-label="Agregar usuario"
>
    <svg
        class="btn-agregar-icono"
        viewBox="0 0 24 24"
        fill="none"
        stroke="currentColor"
        stroke-width="2"
        stroke-linecap="round"
        stroke-linejoin="round"
        aria-hidden="true"
    >
        <line x1="12" y1="5" x2="12" y2="19"></line>
        <line x1="5" y1="12" x2="19" y2="12"></line>
    </svg>
    <span class="btn-agregar-texto">
        Agregar usuario
    </span>
</a>
<script>
let ultimoIdRegistrado = 0;
const errorTabla = document.getElementById('error-tabla');
function escaparHTML(texto) {
    const div = document.createElement('div');
    div.textContent = texto ?? '';
    return div.innerHTML;
}
function crearFila(registro) {
    const nuevaFila = document.createElement('tr');
    nuevaFila.dataset.id = registro.id;
    nuevaFila.innerHTML = `
        <td>
            <a
                class="nombre-alumno"
                href="../Carnet_De_Estudiante/Archivos_Ficha_De_Estudiantes/carnet.php?id=${registro.id}"
            >
                ${escaparHTML(registro.usuario)}
            </a>
        </td>
        <td>
            ${escaparHTML(registro.curso_actual)}
        </td>
        <td>
            ${escaparHTML(registro.mail)}
        </td>
        <td>
            ${escaparHTML(registro.tel)}
        </td>
    `;
    return nuevaFila;
}
async function pedirNuevosDatos() {
    try {
        const respuesta = await fetch(
            `obtener_datos.php?ultimo_id=${ultimoIdRegistrado}`
        );
        if (respuesta.status === 401 || respuesta.status === 403) {
            location.reload();
            return;
        }
        if (!respuesta.ok) {
            throw new Error(
                `Error HTTP: ${respuesta.status}`
            );
        }
        const listaDeRegistros =
            await respuesta.json();
        errorTabla.hidden = true;
        const contenedorPadre =
            document.getElementById(
                'feed-contenedores'
            );
        listaDeRegistros.forEach(registro => {
            const id = parseInt(
                registro.id,
                10
            );
            if (
                contenedorPadre.querySelector(
                    `tr[data-id="${id}"]`
                )
            ) {
                return;
            }
            const nuevaFila =
                crearFila(registro);
            contenedorPadre.insertBefore(
                nuevaFila,
                contenedorPadre.firstChild
            );
            if (
                id > ultimoIdRegistrado
            ) {
                ultimoIdRegistrado = id;
            }
        });
    } catch (error) {
        console.error(
            'Error al obtener los datos:',
            error
        );
        errorTabla.hidden = false;
    }
}
pedirNuevosDatos();
setInterval(
    pedirNuevosDatos,
    2000
);
const header =
    document.getElementById("header");
let ultimoScroll = 0;
window.addEventListener("scroll", () => {
    const scrollActual =
        window.pageYOffset;
    if (scrollActual <= 0) {
        header.classList.remove("scroll-down");
        return;
    }
    if (
        scrollActual > ultimoScroll &&
        scrollActual > 100
    ) {
        header.classList.remove("scroll-up");
        header.classList.add("scroll-down");
    } else {
        header.classList.remove("scroll-down");
        header.classList.add("scroll-up");
    }
    ultimoScroll = scrollActual;
});
</script>
</body>
</html>
