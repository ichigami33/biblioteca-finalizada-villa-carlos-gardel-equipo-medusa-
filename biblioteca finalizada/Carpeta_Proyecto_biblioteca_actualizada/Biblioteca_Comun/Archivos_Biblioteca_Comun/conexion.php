
<!DOCTYPE html>
<htm lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Biblioteca Comun</title>
        <link rel="stylesheet" href="styles.css">
    </head>
    <body>
        <form action="conexion.php" method="POST">
            <input type="text" name="nombreusuario" id="nombreusuario">
            <input type="submit" value="nose">
        </form>
        <div id="cosa">
            <?php

                $server = "localhost";
                $user = "root";
                $pass =  "";
                $db = "bibloteca";

                $conexion = new mysqli ($server, $user, $pass, $db);


                $sql = "select * temast";

        </div>
    </body>
</html>