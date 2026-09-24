<?php

mysqli_report(MYSQLI_REPORT_OFF);

$servidor = "localhost";
$usuario = "root";
$password = "";
$base_datos = "bibloteca";

$conexion = mysqli_connect($servidor, $usuario, $password, $base_datos);

if (!$conexion) {
    throw new Exception("No se pudo conectar con la base de datos.");
}

mysqli_set_charset($conexion, "utf8mb4");
?>