<?php
$host = 'localhost'; // El servidor donde se encuentra la base de datos
$user = 'srvccm'; // El usuario de la base de datos
$password = '720./Srv.xi8'; // La contraseña del usuario
$database = 'prueba'; // El nombre de la base de datos
$table = 'data'; // El nombre de la tabla a la que deseas conectarte

// Intenta conectar a la base de datos
$connection = mysqli_connect($host, $user, $password, $database);

// Verifica si hay algún error de conexión
if (mysqli_connect_errno()) {
    die("La conexión a la base de datos ha fallado: " . mysqli_connect_error());
}

// Consulta los datos de la tabla "data"
$query = "SELECT columna1, columna2 FROM $table";
$result = mysqli_query($connection, $query);

// Verifica si la consulta se realizó con éxito
if (!$result) {
    die("Error al consultar los datos de la tabla: " . mysqli_error($connection));
}

// Imprime los datos en una tabla HTML
echo "<table>";
echo "<tr><th>Columna 1</th><th>Columna 2</th></tr>";
while ($row = mysqli_fetch_assoc($result)) {
    echo "<tr><td>" . $row['columna1'] . "</td><td>" . $row['columna2'] . "</td></tr>";
}
echo "</table>";

// Cierra la conexión a la base de datos
mysqli_close($connection);
