<?php
// Activar el reporte de errores
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
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
    $nombre = htmlspecialchars($_POST['nombre'], ENT_QUOTES);
    if (strlen($nombre) > 20) {
      echo "El nombre de usuario es demasiado largo.";
      exit;
    }

    $correo = $_POST['correo'];

// Expresión regular más estricta
if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
    echo "El correo electrónico no es válido. Debe tener el formato usuario@dominio.com";
    exit;
}
    $contrasena = password_hash($_POST['contrasena'], PASSWORD_DEFAULT);
    $conn->beginTransaction();

    

    $sql = "INSERT INTO perfiles (correo, contrasena, tipo) VALUES (:correo, :contrasena, :tipo)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':correo', $correo);
    $stmt->bindParam(':contrasena', $contrasena);
    $tipo = "candidato";
    $stmt->bindParam(':tipo', $tipo);
    $stmt->execute();
    $perfil_id = $conn->lastInsertId();
     // Insertar en candidatos con el perfil_id obtenido
     $sql = "INSERT INTO candidatos (nombre_candidato, perfil_id) VALUES (:nombre_candidato, :perfil_id)";
     $stmt = $conn->prepare($sql);
     $stmt->bindParam(':nombre_candidato', $nombre);
     $stmt->bindParam(':perfil_id', $perfil_id);
     $stmt->execute();
     $conn->commit();
 
    // Redirigir al usuario
    header("Location: /src/views/confirmacioncandidato.html");
  } else {
    echo "Error: Datos del formulario no válidos.";
  }
} catch (PDOException $e) {
  // Deshacer la transacción en caso de error
  $conn->rollBack();
  echo "Error: " . $e->getMessage();
  // Registrar el error para depuración
  error_log("Error: " . $e->getMessage());
}
