document.getElementById("loginForm").addEventListener("submit", function(event) {
    event.preventDefault(); // Evita que el formulario se envíe por defecto

    // Obtener los valores del formulario
    var username = document.getElementsByName("username")[0].value;
    var password = document.getElementsByName("password")[0].value;

    // Realizar la solicitud para verificar las credenciales
    var xhr = new XMLHttpRequest();
    xhr.open("GET", "./php/users.json", true);
    xhr.onload = function() {
        if (xhr.status === 200) {
            var users = JSON.parse(xhr.responseText).users;
            var authenticatedUser = users.find(function(user) {
                return user.username === username && user.password === password;
            });
            if (authenticatedUser) {
                // Enviar los datos del usuario autenticado al servidor para iniciar sesión
                var xhrSession = new XMLHttpRequest();
                xhrSession.open("POST", "./php/start_session.php", true);
                xhrSession.setRequestHeader("Content-Type", "application/json;charset=UTF-8");
                xhrSession.onload = function() {
                    if (xhrSession.status === 200) {
                        window.location.href = '/PROYECTO_FINAL/pages/dashboard.php';
                    } else {
                        document.getElementById("message").innerHTML = "Error al iniciar sesión";
                    }
                };
                xhrSession.send(JSON.stringify(authenticatedUser));
            } else {
                document.getElementById("message").innerHTML = "Nombre de usuario o contraseña incorrectos";
            }
        } else {
            document.getElementById("message").innerHTML = "Error al cargar los datos de usuario";
        }
    };
    xhr.send();
});
