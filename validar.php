<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = $_POST['email'];

  // Validación adicional en PHP (ejemplo: verificar en base de datos)
  // ... (aquí va tu lógica de validación)

  if ('') {
    // Redirigir al usuario
    header('Location: crearcuenta.html', true, 302);
    exit();
  } else {
    // Mostrar un mensaje de error
    echo "El correo electrónico no es válido o ya está en uso.";
  }
}