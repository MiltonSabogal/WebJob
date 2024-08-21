
<?php
// Conexión a la base de datos (reemplaza con tus credenciales)
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "usuarios";
session_start();

$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Login logic
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['correo']) && isset($_POST['contrasena'])) {
    $correo = $_POST['correo'];
    $contrasena = $_POST['contrasena'];

    // Sanitize data using prepared statements
    $stmt = $conn->prepare("SELECT id, tipo, contrasena FROM perfiles WHERE correo = ?");
    $stmt->bind_param("s", $correo);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();

        // Verify password using password_verify
        if (password_verify($contrasena, $row['contrasena'])) {
            session_start();
            $_SESSION['usuario_id'] = $row['id'];
            $_SESSION['tipo_usuario'] = $row['tipo'];

            // Redirect to appropriate dashboard
            if ($row['tipo'] == 'candidato') {
                header("Location: perfilcandidato.html");
            } else {
                header("Location: perfilempresa.html");
            }
            exit();
        } else {
            echo "Contraseña incorrecta";
        }
    } else {
        echo "Usuario no encontrado";
    }

    $stmt->close();
}

// Logout logic
if (isset($_GET['Salir'])) {
    session_start();
    session_unset();
    session_destroy();
    header("Location: ingreso.html", true, 302); // Redirección permanente (302)
exit();
}

$conn->close();
