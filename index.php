
<?php
session_start();

// Verificar si el formulario de inicio de sesión ha sido enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verificar si los datos de inicio de sesión son válidos
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Leer el archivo JSON de usuarios
    $users_json = file_get_contents('php/users.json');
    $users = json_decode($users_json, true);

    // Verificar las credenciales del usuario
    foreach ($users['users'] as $user) {
        if ($user['username'] == $username && $user['password'] == $password) {
            // Autenticación exitosa, guardar los datos del usuario en la sesión
            $_SESSION['user'] = $user;
            // Redireccionar al dashboard
            header("Location: pages/dashboard.php");
            exit();
        }
    }

    // Si las credenciales son incorrectas, mostrar un mensaje de error
    $error = "Nombre de usuario o contraseña incorrectos.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Proyecto Final</title>
    <link rel="stylesheet" href="resources/styles.css">
</head>
<body>
    <div class="login-container">
        <h2>User Login</h2>
        <form id="loginForm" method="post">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>
        <p id="message"></p>
    </div>
</body>
</html>