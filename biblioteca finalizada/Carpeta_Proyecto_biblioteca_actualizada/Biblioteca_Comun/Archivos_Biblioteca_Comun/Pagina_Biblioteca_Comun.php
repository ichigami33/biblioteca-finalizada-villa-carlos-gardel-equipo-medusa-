<?php



$raiz = '../../';
$urlBiblioteca = $raiz . 'Biblioteca_Comun/Archivos_Biblioteca_Comun/Pagina_Biblioteca_Comun.php';
$urlTabla = $raiz . 'tabla_usuarios/pagina_tabla_usuarios.php';
function e($texto)

{

    return htmlspecialchars((string)$texto, ENT_QUOTES, 'UTF-8');

}



$ruta_img = '../Imagenes_Biblioteca_Comun/';


$temas = [

    1  => ['nombre' => 'Novela Juvenil',         'mostrar' => 'Novela juvenil',           'etiqueta' => 'Juvenil',              'img' => '01_novela_juvenil.png'],

    2  => ['nombre' => 'Literatura Clasica',     'mostrar' => 'Literatura clásica',       'etiqueta' => 'Clásica',              'img' => '02_literatura_clasica.png'],

    3  => ['nombre' => 'Ciencia Ficcion',        'mostrar' => 'Ciencia ficción',          'etiqueta' => 'Ciencia ficción',      'img' => '03_ciencia_ficcion_fantasia.png'],

    4  => ['nombre' => 'Cuentos Cortos',         'mostrar' => 'Cuentos cortos',           'etiqueta' => 'Cuentos',              'img' => '04_cuentos_cortos.png'],

    5  => ['nombre' => 'Terror Suspenso',        'mostrar' => 'Terror y suspenso',        'etiqueta' => 'Terror',               'img' => '05_terror_suspenso.png'],

    6  => ['nombre' => 'Divulgacion Cientifica', 'mostrar' => 'Divulgación científica',   'etiqueta' => 'Divulgación científica', 'img' => '06_divulgacion_cientifica.png'],

    7  => ['nombre' => 'Historia',               'mostrar' => 'Historia',                 'etiqueta' => 'Historia',             'img' => '07_historia.png'],

    8  => ['nombre' => 'Poesia',                 'mostrar' => 'Poesía',                   'etiqueta' => 'Poesía',               'img' => '08_poesia.png'],

    9  => ['nombre' => 'Comics Novela Grafica',  'mostrar' => 'Cómics y novela gráfica',  'etiqueta' => 'Cómics',               'img' => '09_comics_novela_grafica.png'],

    10 => ['nombre' => 'Autoayuda',              'mostrar' => 'Autoayuda',                'etiqueta' => 'Autoayuda',            'img' => '10_autoayuda.png'],

];


$genero_filtrado = (isset($_GET['genero']) && is_string($_GET['genero']))

    ? $_GET['genero']

    : 'Todos';


if (

    $genero_filtrado !== 'Todos'

    && !in_array($genero_filtrado, array_column($temas, 'nombre'), true)

) {

    $genero_filtrado = 'Todos';

}



$busqueda = (isset($_GET['q']) && is_string($_GET['q']))

    ? trim($_GET['q'])

    : '';



$campo = (isset($_GET['campo']) && is_string($_GET['campo']))

    ? $_GET['campo']

    : 'todo';



if (!in_array($campo, ['todo', 'titulo', 'autor'], true)) {

    $campo = 'todo';

}



$libros_finales = [];

$conteo_temas = [];

$mensaje_debug = "";



$host = 'localhost';

$dbname = 'bibloteca';

$usuario = 'root';

$password = '';



try {



    $pdo = new PDO(

        "mysql:host=$host;dbname=$dbname;charset=utf8mb4",

        $usuario,

        $password

    );



    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);



    

    $sql = "

        SELECT

            isbn,

            tema,

            autor,

            editorial,

            stock,

            nombrel

        FROM librost

        ORDER BY nombrel ASC

    ";



    $stmt = $pdo->query($sql);



    $libros_bd = $stmt->fetchAll(PDO::FETCH_ASSOC);



    if (empty($libros_bd)) {



        $mensaje_debug =

            "La conexión fue exitosa, pero la tabla 'librost' está vacía.";



    } else {



        foreach ($libros_bd as $libro) {



            

            

            $info_tema = $temas[(int)$libro['tema']] ?? [

                'nombre'   => 'No definido',

                'mostrar'  => 'No definido',

                'etiqueta' => 'No definido',

                'img'      => 'books-5211309_1200.jpg',

            ];



            

            $stock = (int)$libro['stock'];



            if ($stock <= 0) {

                $stock_clase = 'stock-nada';

                $stock_texto = 'Sin stock';

            } elseif ($stock <= 3) {

                $stock_clase = 'stock-poco';

                $stock_texto = ($stock === 1) ? 'Queda 1' : "Quedan $stock";

            } else {

                $stock_clase = 'stock-ok';

                $stock_texto = "$stock en stock";

            }



            $libros_finales[] = [

                'isbn'        => $libro['isbn'],

                'tema'        => $info_tema['nombre'],

                'tema_mostrar' => $info_tema['mostrar'],

                'autor'       => $libro['autor'],

                'editorial'   => $libro['editorial'],

                'nombre'      => $libro['nombrel'],

                'img'         => $ruta_img . $info_tema['img'],

                'stock_clase' => $stock_clase,

                'stock_texto' => $stock_texto,

            ];



            

            $conteo_temas[$info_tema['nombre']] =

                ($conteo_temas[$info_tema['nombre']] ?? 0) + 1;

        }

    }



} catch (PDOException $e) {



    $mensaje_debug =

        "Error crítico de Base de Datos: " .

        $e->getMessage();

}


$ruta_css = __DIR__ . '/styles.css';

$css_existe = is_file($ruta_css);



$version_css = $css_existe ? filemtime($ruta_css) : time();



$css_correcto = $css_existe

    && strpos((string)file_get_contents($ruta_css), 'BIBLIOTECA COMÚN') !== false;



$total_libros = count($libros_finales);

$total_temas = count($temas);



?>

<?php
session_start();
?>

<!DOCTYPE html>

<html lang="es">



<head>



    <meta charset="UTF-8">



    <meta name="viewport" content="width=device-width, initial-scale=1.0">



    <title>Biblioteca Común</title>



    <link rel="stylesheet" href="styles.css?v=<?php echo (int)$version_css; ?>">



</head>



<body>


<header>



    <a class="marca" 
    href="../../Pagina_Principal/pagina_principal_biblioteca/Pagina_principal.php">

        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">

            <path d="M4 5.5C4 4.7 4.7 4 5.5 4H11v15H5.5C4.7 19 4 18.3 4 17.5v-12z"/>

            <path d="M20 5.5c0-.8-.7-1.5-1.5-1.5H13v15h5.5c.8 0 1.5-.7 1.5-1.5v-12z"/>

        </svg>

        Biblioteca Común

    </a>




</header>



<main>



    

    <section class="hero">



        <img

            class="hero-imagen"

            src="<?php echo e($ruta_img); ?>books-5211309_1280.jpg"

            alt="Estantes con libros"

        >



        <div class="hero-info">



            <h2>Encuentra tu próxima lectura</h2>



            <p>

                Busca por título o autor, o recorre los

                <?php echo (int)$total_temas; ?> temas del catálogo.

                
            </p>



            <?php if ($total_libros > 0) { ?>

                <p class="hero-dato">

                    Catálogo actual:

                    <?php echo $total_libros === 1 ? '1 libro' : $total_libros . ' libros'; ?>

                </p>

            <?php } ?>



            <a class="hero-boton" href="#catalogo">Ver el catálogo</a>



        </div>



    </section>


    

    <section class="catalogo" id="catalogo">



        <div class="catalogo-cabecera">



            <h1 id="titulo">Catálogo completo</h1>



            <p class="catalogo-intro">

                Estos son los libros que ofrecemos, ordenados por temas.

                Escribe en el buscador y los resultados se actualizan al instante.

            </p>



        </div>


        

        <!-- INICIO: SECCIÓN DE BÚSQUEDA Y FILTROS. Si no te gusta esta separación, elimina desde este comentario hasta FIN. -->
        <section class="zona-filtros">

        <div class="buscador" role="search">



            <svg class="buscador-icono" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true">

                <circle cx="11" cy="11" r="6.5"/>

                <path d="M16 16l4.5 4.5"/>

            </svg>



            <input

                type="search"

                id="busqueda"

                placeholder="Escribe un título o un autor"

                aria-label="Buscar libros"

                autocomplete="off"

                value="<?php echo e($busqueda); ?>"

            >



            <button type="button" class="buscador-borrar" id="borrar-busqueda" aria-label="Borrar búsqueda" hidden>

                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" aria-hidden="true">

                    <path d="M6 6l12 12M18 6L6 18"/>

                </svg>

            </button>



            

            <select id="campo" aria-label="Buscar en">

                <option value="todo"   <?php echo $campo === 'todo'   ? 'selected' : ''; ?>>Título y autor</option>

                <option value="titulo" <?php echo $campo === 'titulo' ? 'selected' : ''; ?>>Solo título</option>

                <option value="autor"  <?php echo $campo === 'autor'  ? 'selected' : ''; ?>>Solo autor</option>

            </select>



        </div>


        

        <h2 class="solo-lector">Filtrar por tema</h2>



        <div class="chips" role="group" aria-label="Filtrar por tema">



            <button

                type="button"

                class="chip <?php echo $genero_filtrado === 'Todos' ? 'activo' : ''; ?>"

                data-genero="Todos"

                aria-pressed="<?php echo $genero_filtrado === 'Todos' ? 'true' : 'false'; ?>"

            >

                <span class="chip-icono-todos">

                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linejoin="round" aria-hidden="true">

                        <rect x="4" y="4" width="6.5" height="6.5" rx="1.2"/>

                        <rect x="13.5" y="4" width="6.5" height="6.5" rx="1.2"/>

                        <rect x="4" y="13.5" width="6.5" height="6.5" rx="1.2"/>

                        <rect x="13.5" y="13.5" width="6.5" height="6.5" rx="1.2"/>

                    </svg>

                </span>

                <span class="chip-texto">Todos</span>

                <span class="chip-num"><?php echo (int)$total_libros; ?></span>

            </button>



            <?php foreach ($temas as $tema) { ?>



                <button

                    type="button"

                    class="chip <?php echo $genero_filtrado === $tema['nombre'] ? 'activo' : ''; ?>"

                    data-genero="<?php echo e($tema['nombre']); ?>"

                    aria-pressed="<?php echo $genero_filtrado === $tema['nombre'] ? 'true' : 'false'; ?>"

                >

                    <img src="<?php echo e($ruta_img . $tema['img']); ?>" alt="" width="28" height="28">

                    <span class="chip-texto"><?php echo e($tema['etiqueta']); ?></span>

                    <span class="chip-num"><?php echo (int)($conteo_temas[$tema['nombre']] ?? 0); ?></span>

                </button>



            <?php } ?>



        </div>


        

        <p class="resultado-info" id="contador" aria-live="polite"></p>

        </section>
        <!-- FIN: SECCIÓN DE BÚSQUEDA Y FILTROS. -->


        <?php if (!empty($mensaje_debug)) { ?>

            <p class="aviso-error">

                Diagnóstico: <?php echo e($mensaje_debug); ?>

            </p>

        <?php } ?>


        

        <!-- INICIO: SECCIÓN DE LIBROS. Si no te gusta esta separación, elimina desde este comentario hasta FIN. -->
        <section class="zona-libros">

        <div class="fichas" id="lista">



            <?php foreach ($libros_finales as $libro) { ?>



                <article
                    class="ficha"
                    data-titulo="<?php echo e($libro['nombre']); ?>"
                    data-autor="<?php echo e($libro['autor']); ?>"
                    data-tema="<?php echo e($libro['tema']); ?>"
                >

                    <!-- Acá empieza el contenido visual de la ficha -->

                    <div class="ficha-categoria">
                        <!-- lo que ya tenías acá -->
                    </div>

                    <h2 class="ficha-titulo">
                        <a href="../../ficha_libros/ficha.php?isbn=<?php echo urlencode($libro['isbn']); ?>">
                            <?php echo e($libro['nombre']); ?>
                        </a>
                    </h2>

                    <!-- resto de tu ficha -->


                    <div class="ficha-cabecera">

                        <img class="ficha-icono" src="<?php echo e($libro['img']); ?>" alt="" width="34" height="34">

                        <span class="ficha-tema"><?php echo e($libro['tema_mostrar']); ?></span>

                    </div>



                    <p class="ficha-autor">

                        Autor: <strong><?php echo e($libro['autor']); ?></strong>

                    </p>

                    <p class="ficha-editorial">
                        Editorial: <strong><?php echo e($libro['editorial']); ?></strong>
                    </p>

                    <div class="ficha-pie">

                        <span class="ficha-isbn">ISBN <?php echo e($libro['isbn']); ?></span>

                        <span class="ficha-stock <?php echo e($libro['stock_clase']); ?>">

                            <?php echo e($libro['stock_texto']); ?>

                        </span>

                    </div>



                </article>



            <?php } ?>



        </div>


        

        <div class="estado-vacio" id="sin-resultados" role="status" hidden>



            <p class="estado-vacio-titulo">No encontramos libros</p>



            <p class="estado-vacio-detalle" id="sin-resultados-detalle"></p>



            <button type="button" class="boton-secundario" id="restablecer">

                Ver todo el catálogo

            </button>



        </div>



    
        </section>
        <!-- FIN: SECCIÓN DE LIBROS. -->

</section>



</main>

 <?php if (
                $_SESSION['rango'] == 'Bibliotecario' ||
                $_SESSION['rango'] == 'Directivo'
            ): ?>
            <a

                    class="btn-agregar"

                    href="/Carpeta_Proyecto_biblioteca/Agregar_Libro/Agregar_libro.php"

                    aria-label="Agregar libro"

                >

                    <svg class="btn-agregar-icono" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" aria-hidden="true">

                        <path d="M12 5v14M5 12h14"/>

                    </svg>

                    <span class="btn-agregar-texto">Agregar libro</span>

            </a>
<?php endif; ?>



<script>


const inputBusqueda    = document.getElementById('busqueda');

const selectCampo      = document.getElementById('campo');

const botonBorrar      = document.getElementById('borrar-busqueda');

const botonesTema      = document.querySelectorAll('.chip');

const contador         = document.getElementById('contador');

const sinResultados    = document.getElementById('sin-resultados');

const detalleVacio     = document.getElementById('sin-resultados-detalle');

const botonRestablecer = document.getElementById('restablecer');


let temaActual = <?php echo json_encode($genero_filtrado, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT); ?>;


function normalizar(texto) {

    return texto

        .toLowerCase()

        .normalize('NFD')

        .replace(/[\u0300-\u036f]/g, '');

}


const libros = Array.from(document.querySelectorAll('.ficha')).map(ficha => ({

    elemento: ficha,

    titulo: normalizar(ficha.dataset.titulo),

    autor: normalizar(ficha.dataset.autor),

    tema: ficha.dataset.tema

}));


function coincideBusqueda(libro, palabras, campo) {



    let texto;



    if (campo === 'titulo') {

        texto = libro.titulo;

    } else if (campo === 'autor') {

        texto = libro.autor;

    } else {

        texto = libro.titulo + ' ' + libro.autor;

    }



    return palabras.every(palabra => texto.includes(palabra));

}


function actualizarURL() {



    const params = new URLSearchParams();



    if (temaActual !== 'Todos') {

        params.set('genero', temaActual);

    }



    if (inputBusqueda.value.trim() !== '') {

        params.set('q', inputBusqueda.value.trim());

    }



    if (selectCampo.value !== 'todo') {

        params.set('campo', selectCampo.value);

    }



    const consulta = params.toString();



    try {

        history.replaceState(null, '', consulta ? '?' + consulta : window.location.pathname);

    } catch (error) {

        

    }

}


function actualizar(animar) {



    if (libros.length === 0) {

        return;

    }



    const texto = inputBusqueda.value.trim();

    const palabras = normalizar(texto).split(/\s+/).filter(Boolean);

    const campo = selectCampo.value;



    let totalBusqueda = 0;   

    let visibles = 0;        

    const porTema = {};      



    libros.forEach(libro => {



        const coincide = coincideBusqueda(libro, palabras, campo);



        if (coincide) {

            totalBusqueda++;

            porTema[libro.tema] = (porTema[libro.tema] || 0) + 1;

        }



        const mostrar = coincide && (temaActual === 'Todos' || libro.tema === temaActual);

        const estabaOculta = libro.elemento.hidden;



        libro.elemento.hidden = !mostrar;



        if (mostrar) {

            visibles++;

        }



        

        if (animar && mostrar && estabaOculta) {

            libro.elemento.classList.add('entra');

        }

    });



    

    botonesTema.forEach(boton => {



        const tema = boton.dataset.genero;

        const cantidad = (tema === 'Todos') ? totalBusqueda : (porTema[tema] || 0);

        const activo = (tema === temaActual);



        boton.querySelector('.chip-num').textContent = cantidad;

        boton.classList.toggle('activo', activo);

        boton.classList.toggle('sin-libros', cantidad === 0);

        boton.setAttribute('aria-pressed', activo ? 'true' : 'false');

    });



    

    if (visibles === 0) {

        contador.textContent = '';   

    } else {

        contador.textContent = 'Mostrando ' + (visibles === 1 ? '1 libro' : visibles + ' libros');

    }



    

    sinResultados.hidden = (visibles !== 0);



    if (visibles === 0) {

        detalleVacio.textContent = (texto !== '')

            ? 'No hay resultados para “' + texto + '”. Prueba con otra palabra, cambia el campo de búsqueda o elige otro tema.'

            : 'Todavía no hay libros en este tema.';

    }



    

    botonBorrar.hidden = (inputBusqueda.value === '');



    actualizarURL();

}


inputBusqueda.addEventListener('input', () => actualizar(true));



selectCampo.addEventListener('change', () => actualizar(true));



botonesTema.forEach(boton => {

    boton.addEventListener('click', () => {

        temaActual = boton.dataset.genero;

        actualizar(true);

    });

});



botonBorrar.addEventListener('click', () => {

    inputBusqueda.value = '';

    actualizar(true);

    inputBusqueda.focus();

});


inputBusqueda.addEventListener('keydown', evento => {

    if (evento.key === 'Escape') {

        inputBusqueda.value = '';

        actualizar(true);

    }

});



botonRestablecer.addEventListener('click', () => {

    inputBusqueda.value = '';

    selectCampo.value = 'todo';

    temaActual = 'Todos';

    actualizar(true);

});


actualizar(false);


window.addEventListener(

    'beforeunload',

    () => {



        localStorage.setItem(

            'scrollPos',

            window.scrollY

        );



    }

);


window.addEventListener(

    'load',

    () => {



        const scrollPos =

            localStorage.getItem('scrollPos');



        if (scrollPos !== null) {



            window.scrollTo(

                0,

                parseInt(scrollPos)

            );



            localStorage.removeItem(

                'scrollPos'

            );



        }



    }

);


const header =

    document.querySelector("header");



let lastScroll = 0;



const umbral = 95;


window.addEventListener(

    "scroll",

    () => {



        const currentScroll =

            window.pageYOffset;



        if (currentScroll <= 0) {



            header.classList.remove(

                "scroll-down"

            );



            header.classList.remove(

                "scroll-up"

            );



            lastScroll = 0;



            return;

        }


        if (

            Math.abs(

                currentScroll - lastScroll

            ) < umbral

        ) {



            return;

        }


        if (

            currentScroll > lastScroll

            &&

            !header.classList.contains(

                "scroll-down"

            )

        ) {



            header.classList.remove(

                "scroll-up"

            );



            header.classList.add(

                "scroll-down"

            );



        }



        else if (

            currentScroll < lastScroll

            &&

            header.classList.contains(

                "scroll-down"

            )

        ) {



            header.classList.remove(

                "scroll-down"

            );



            header.classList.add(

                "scroll-up"

            );



        }



        lastScroll =

            currentScroll;



    }

);



</script>



</body>



</html>
