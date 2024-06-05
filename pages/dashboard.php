<?php
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['user'])) {
    // Si no está autenticado, redireccionar al inicio de sesión
    header("Location: ../php/login.php");
    exit();
}

// Incluir la función para llamar a la API de OpenAI
require_once __DIR__ . '/../php/callOpenAIAPI.php'; // Asegúrate de que la ruta sea correcta

// Obtener los datos del usuario desde la sesión
$user = $_SESSION['user'];
$prompt = "Recibes estos datos del usuario: Nombre " . $user['nombre'] . ". Profesion " . $user['profesion'] . " edad " . $user['edad'] . ". Sigue las siguientes instrucciones para generar texto personalizado para el usuario. 
- 1. No utilices sus datos en la descripción. 
- 2. Con respecto a estos datos es necesario generar una introducción a la web. Esta web es un lugar en el que el contenido es diseñado con IA. Da la bienvenida al usuario
- 3. Debe ser un texto de unas 50 palabras, con sentido e instando al usuario a explorar la página. 
- 4. Por favor evita dejar frases a medias y utiliza retorno de carro después de un punto para html (?><br><?php). 
- 5. Utiliza palabras técnicas que tengan que ver con su profesión si tiene sentido, pero recuerda el uso del dashboard.
- 6. Ten muy en cuenta la edad. Hay personas que al ser más mayores no van a comprender palabras tecnológicas como 'dashboard'.";

// Llamar a la API de OpenAI
$response = callOpenAIAPIText($prompt);

$generated_text = 'No response from API';
if ($response && isset($response['choices'][0]['message']['content'])) {
    $generated_text = $response['choices'][0]['message']['content'];
}

// Leer el archivo JSON de usuarios con sus imágenes de fondo
$users = json_decode(file_get_contents(__DIR__ . '/../php/users.json'), true);

// Verificar si el usuario ya tiene una imagen de fondo guardada
foreach ($users['users'] as &$u) {
    if ($u['username'] == $user['username']) {
        if (empty($u['imagenDash'])) {
            // Si el usuario no tiene una imagen de fondo establecida, llamar a la API de DALL-E
            $image_prompt = "Genera una imagen de fondo personalizada para un dashboard de un usuario con los datos " . $user['nombre'] . " que es " . $user['profesion'] . " y tiene " . $user['edad'] . " años.";
            $image_response = callOpenAIAPIDall($image_prompt);

            if ($image_response && isset($image_response['data'][0]['url'])) {
                // Obtener la URL de la imagen generada por la API
                $background_image_url = $image_response['data'][0]['url'];

                // Guardar la imagen en la carpeta resources
                $image_filename = '../resources/background_' . $user['username'] . '.png'; // Ruta relativa para el navegador
                $absolute_image_path = __DIR__ . '/../resources/background_' . $user['username'] . '.png'; // Ruta absoluta para guardar el archivo
                file_put_contents($absolute_image_path, file_get_contents($background_image_url));

                // Actualizar la ruta de la imagen en el archivo JSON de usuarios
                $u['imagenDash'] = $image_filename;

                // Guardar los cambios en el archivo JSON de usuarios
                file_put_contents(__DIR__ . '/../php/users.json', json_encode($users));
            }
        } else {
            // Si el usuario ya tiene una imagen de fondo guardada, utilizar la existente
            $background_image_url = $u['imagenDash'];
        }
        break;
    }
}

if (!isset($background_image_url)) {
    echo "Usuario no encontrado o sin imagen generada.";
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="resources/styles.css">
</head>

<body>
    <nav>
        <p style="margin-right: 20px;">Hola, <?php echo $user['nombre']; ?></p>
        <button onclick="cerrarSesion()">Cerrar Sesión</button>
    </nav>
    <header style="background-image: url('<?php echo htmlspecialchars($background_image_url); ?>');">
        <div class="header-content">

            <p style="line-height: 30px;"><?php echo htmlspecialchars($generated_text); ?></p>
        </div>
    </header>
    <div class="grid-container">
        <div class="grid-item" id="card1">
            <a href="pagina1.php">
                <img src="resources/imagen1.png" alt="Thumbnail 1">
                <div class="overlay">
                    <p>Aprende Anatomía</p>
                </div>
            </a>
        </div>
        <div class="grid-item" id="card2">
            <a href="pagina2.php">
                <img src="resources/imagen2.png" alt="Thumbnail 2">
                <div class="overlay">
                    <p>Web 2</p>
                </div>
            </a>
        </div>
        <div class="grid-item" id="card3">
            <a href="pagina3.php">
                <img src="resources/imagen3.png" alt="Thumbnail 3">
                <div class="overlay">
                    <p>Web 3</p>
                </div>
            </a>
        </div>
        <div class="grid-item" id="card4">
            <a href="pagina4.php">
                <img src="resources/imagen4.png" alt="Thumbnail 4">
                <div class="overlay">
                    <p>Web 4</p>
                </div>
            </a>
        </div>
    </div>
    <footer>
        <p>I did it!!<br> <strong>Gloria Franco Cuesta</strong></p>
    </footer>
    <script>
        function cerrarSesion() {
            window.location.href = '../php/logout.php';
        }
    </script>
</body>

</html>