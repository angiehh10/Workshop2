<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultado del Formulario</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            height: 100vh;
        }
        .message-container, .data-table {
            margin-top: 20px;
            padding: 20px;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 90%;
            max-width: 800px;
        }
        .success {
            color: #155724;
            background-color: #d4edda;
            border-color: #c3e6cb;
            padding: 10px;
            border-radius: 5px;
        }
        .error {
            color: #721c24;
            background-color: #f8d7da;
            border-color: #f5c6cb;
            padding: 10px;
            border-radius: 5px;
        }
        h1 {
            color: #333;
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        tbody tr:hover {
            background-color: #f1f1f1;
        }
    </style>
</head>
<body>

<?php

// Datos de conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";  
$dbname = "formulario_db";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die('<div class="message-container"><div class="error">Error en la conexión: ' . $conn->connect_error . '</div></div>');
}

// Obtener datos del formulario y escapar los caracteres especiales
$nombre = mysqli_real_escape_string($conn, $_POST['nombre']);
$apellido = mysqli_real_escape_string($conn, $_POST['apellido']);
$telefono = mysqli_real_escape_string($conn, $_POST['telefono']);
$correo = mysqli_real_escape_string($conn, $_POST['correo']);

// Verificar si los campos no están vacíos
if (empty($nombre) || empty($apellido) || empty($telefono) || empty($correo)) {
    die('<div class="message-container"><div class="error">Por favor, completa todos los campos.</div></div>');
}

// Preparar la consulta SQL para insertar
$sql = "INSERT INTO usuarios (nombre, apellido, telefono, correo) VALUES ('$nombre', '$apellido', '$telefono', '$correo')";

// Ejecutar la consulta y verificar si fue exitosa
if ($conn->query($sql) === TRUE) {
    echo '<div class="message-container"><div class="success">Nuevo registro creado exitosamente.</div></div>';
} else {
    echo '<div class="message-container"><div class="error">Error: ' . $sql . '<br>' . $conn->error . '</div></div>';
}

// Preparar la consulta SQL para leer los datos
$sql_select = "SELECT id, nombre, apellido, telefono, correo FROM usuarios";
$result = $conn->query($sql_select);

// Mostrar los datos si hay registros
if ($result->num_rows > 0) {
    echo '<div class="data-table">';
    echo '<h1>Registros de la Base de Datos</h1>';
    echo '<table>';
    echo '<thead>';
    echo '<tr><th>ID</th><th>Nombre</th><th>Apellido</th><th>Teléfono</th><th>Correo</th></tr>';
    echo '</thead>';
    echo '<tbody>';
    // Recorrer y mostrar los resultados en una tabla
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>{$row['id']}</td>
                <td>{$row['nombre']}</td>
                <td>{$row['apellido']}</td>
                <td>{$row['telefono']}</td>
                <td>{$row['correo']}</td>
              </tr>";
    }
    echo '</tbody>';
    echo '</table>';
    echo '</div>';
} else {
    echo '<div class="message-container"><div class="error">No se encontraron registros.</div></div>';
}

// Cerrar la conexión
$conn->close();
?>

</body>
</html>