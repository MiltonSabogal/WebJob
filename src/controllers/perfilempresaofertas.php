<?php
// Conexión a la base de datos (reemplaza con tus credenciales)
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "usuarios";
session_start();
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $nombreEmpresa = $_POST['nombreEmpresa'];
  $cargoSolicitado = $_POST['cargoSolicitado'];
  $ciudad = $_POST['ciudad'];
  $fechaPublicacion = $_POST['fechaPublicacion'];
  $salario = $_POST['salario'];
  $descripcion = $_POST['descripcion'];

  // **Edit existing offer or create a new one based on a flag**
  $isNewOffer = isset($_POST['isNewOffer']) ? $_POST['isNewOffer'] : false;

  if ($isNewOffer) {
    // Insert new offer into database
    $sql = "INSERT INTO ofertas (nombre_empresa, cargo_solicitado, ciudad, fecha_publicacion, salario, descripcion) VALUES (?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssssss', $nombreEmpresa, $cargoSolicitado, $ciudad, $fechaPublicacion, $salario, $descripcion);
    $stmt->execute();

    if ($stmt->affected_rows === 1) {
      echo 'Oferta creada exitosamente!';
    } else {
      echo 'Error al crear la oferta.';
    }

    $stmt->close();
  } else {
    // Update existing offer based on offer ID (replace with your logic to retrieve offer ID)
    $offerId = 1; // Replace with actual offer ID retrieved from the form or session

    $sql = "UPDATE ofertas SET nombre_empresa = ?, cargo_solicitado = ?, ciudad = ?, fecha_publicacion = ?, salario = ?, descripcion = ? WHERE id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sssssss', $nombreEmpresa, $cargoSolicitado, $ciudad, $fechaPublicacion, $salario, $descripcion, $offerId);
    $stmt->execute();

    if ($stmt->affected_rows === 1) {
      echo 'Oferta actualizada exitosamente!';
    } else {
      echo 'Error al actualizar la oferta.';
    }

    $stmt->close();
  }
}

$conn->close();
?>