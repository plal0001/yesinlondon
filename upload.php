<?php
date_default_timezone_set('Europe/Madrid');  // Configura la zona horaria a Madrid

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $message = $_POST['message'];
    $files = $_FILES['photos'];

    // Crear la carpeta con el nombre del usuario si no existe
    $uploadDir = 'uploads/' . $name;
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    // Guardar el mensaje en un archivo de texto con la fecha y hora
    $timestamp = date('Ymd_His');
    $messageFile = $uploadDir . '/mensaje_' . $timestamp . '.txt';
    file_put_contents($messageFile, $message);

    $success = true;
    $messages = [];

    // Procesar los archivos subidos
    for ($i = 0; $i < count($files['name']); $i++) {
        $filename = basename($files['name'][$i]);
        $targetFilePath = $uploadDir . '/' . $filename;

        // Mover el archivo subido a la carpeta del usuario
        if (move_uploaded_file($files['tmp_name'][$i], $targetFilePath)) {
            $messages[] = "El archivo $filename ha sido subido con éxito.";
        } else {
            $success = false;
            $messages[] = "Hubo un error subiendo el archivo $filename.";
        }
    }

    // Generar el script de alertas en JavaScript
    echo "<script type='text/javascript'>";
    foreach ($messages as $message) {
        echo "alert('$message');";
    }
    if ($success) {
        echo "alert('Todos los archivos se han subido correctamente.');";
    } else {
        echo "alert('Ocurrieron algunos errores durante la subida de archivos.');";
    }
    echo "window.location.href = 'index.html';";  // Redirigir al usuario de vuelta al formulario
    echo "</script>";
} else {
    echo "<script type='text/javascript'>";
    echo "alert('Método no permitido.');";
    echo "window.location.href = 'index.html';";  // Redirigir al usuario de vuelta al formulario
    echo "</script>";
}
?>
