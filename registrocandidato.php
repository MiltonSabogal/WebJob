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
    $nombre = htmlspecialchars($_POST['nombre'], ENT_QUOTES);
    if (strlen($nombre) > 20) {
      echo "El nombre de usuario es demasiado largo.";
      exit;
    }

    $correo = filter_var($_POST['correo'], FILTER_SANITIZE_EMAIL);
    if (!$correo) {
      echo "El correo electrónico no es válido.";
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
    header("Location: confirmacioncandidato.html");
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
