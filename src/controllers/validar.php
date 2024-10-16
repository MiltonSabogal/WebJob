<?php
// Conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "usuarios";
session_start();

try {
  $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


  // Procesar el formulario solo si se ha enviado
  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener y validar datos del formulario
    $usuario = htmlspecialchars($_POST['usuario'], ENT_QUOTES);
    if (strlen($usuario) > 20) {
      echo "El nombre de usuario es demasiado largo.";
      exit;
    }

    $correo = filter_var($_POST['correo'], FILTER_SANITIZE_EMAIL);
    if (!$correo) {
      echo "El correo electrónico no es válido.";
      exit;
    }
    $contrasena = password_hash($_POST['contrasena'], PASSWORD_DEFAULT);

    // Preparar y ejecutar la sentencia SQL
    $sql = "INSERT INTO candidato (nombre, correo, contrasena) VALUES (:nombre, :correo, :contrasena)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':nombre', $usuario);
    $stmt->bindParam(':correo', $correo);
    $stmt->bindParam(':contrasena', $contrasena);
    $stmt->execute();

    // Redirigir al usuario
    header("Location: crearcuenta.html");
  } else {
    echo "Error: Datos del formulario no válidos.";
  }
} catch (PDOException $e) {
  echo "Error de conexión a la base de datos: " . $e->getMessage();
  // Registrar el error para depuración
  error_log("Error de conexión a la base de datos: " . $e->getMessage());
}
