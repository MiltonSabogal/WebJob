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

    // Preparar y ejecutar la sentencia SQL
    $sql = "INSERT INTO empresas (nombre_empresa) VALUES (:nombre_empresa)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':nombre_empresa', $nombre);
    $stmt->execute();

    $stmt = $conn->prepare("SELECT LAST_INSERT_ID()");
    $stmt->execute();
    $result = $stmt->fetchColumn();
    $empresa_id = $result;


    $sql = "INSERT INTO perfiles (correo, contrasena, tipo, empresa_id) VALUES (:correo, :contrasena, :tipo, :empresa_id)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':correo', $correo);
    $stmt->bindParam(':contrasena', $contrasena);
    $tipo = "empresa";
    $stmt->bindParam(':tipo', $tipo);
    $stmt->bindParam(':empresa_id', $empresa_id);
    $stmt->execute();

    // Obtener el ID del perfil recién insertado
    $stmt = $conn->prepare("SELECT LAST_INSERT_ID()");
    $stmt->execute();
    $perfil_id = $stmt->fetchColumn();

    // Obtener el nombre de la empresa
    $stmt = $conn->prepare("SELECT nombre_empresa FROM empresas WHERE empresa_id = :empresa_id");
    $stmt->bindParam(':empresa_id', $empresa_id);
    $stmt->execute();
    $empresa_nombre = $stmt->fetchColumn();

    // Almacenar el nombre de la empresa en la sesión
    $_SESSION['empresa_nombre'] = $empresa_nombre;

    // Redirigir al usuario
    header("Location: confirmacionempresa.html?id=$perfil_id");
  } else {
    echo "Error: Datos del formulario no válidos.";
  }
} catch (PDOException $e) {
  echo "Error de conexión a la base de datos: " . $e->getMessage();
  // Registrar el error para depuración
  error_log("Error de conexión a la base de datos: " . $e->getMessage());
}
