// Suponiendo que tienes una conexión a la base de datos establecida
$target_dir = "uploads/";
$target_file = $target_dir . basename($_FILES["image"]["name"]);
$uploadOk = true;
$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

// Verifica si el archivo es una imagen
if(isset($_POST["submit"])) {
  $check = getimagesize($_FILES["image"]["tmp_name"]);
  if($check !== false) {
    echo "El archivo es una imagen - " . $check["mime"] . ".";
    $uploadOk = true;
  } else {
    echo "El archivo no es una imagen.";
    $uploadOk = false;
  }
}

// Verifica si el archivo ya existe
if (file_exists($target_file)) {
  echo "Lo siento, el archivo ya existe.";
  $uploadOk = false;
}

// Verifica el tamaño del archivo
if ($_FILES["image"]["size"] > 500000) {
  echo "Lo siento, el archivo es demasiado grande.";
  $uploadOk = false;
}

// Verifica el tipo de archivo
if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
&& $imageFileType != "gif" ) {
  echo "Lo siento, solo se permiten archivos JPG, PNG, JPEG y GIF.";
  $uploadOk = false;
}

// Verifica si hubo algún error al subir el archivo
if ($uploadOk == false) {
  echo "Lo siento, hubo un problema al subir tu archivo.";
} else {
  if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
    echo "El archivo ". basename( $_FILES["image"]["name"]). " se subió correctamente.";

    // Inserta la ruta de la imagen en la base de datos
    $sql = "INSERT INTO imagenes (ruta_imagen, tipo_imagen, usuario_id) VALUES (:ruta_imagen, :tipo_imagen, :usuario_id)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':ruta_imagen', $target_file);
    $stmt->bindParam(':tipo_imagen', $imageFileType);
    $stmt->bindParam(':usuario_id', $userId); // Reemplaza con el ID del usuario actual
    $stmt->execute();
  } else {
    echo "Lo siento, hubo un problema al subir tu archivo.";
  }
}