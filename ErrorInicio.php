<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Error Login Alumno</title>
    <link rel="stylesheet" href="login.css">
        <script>
                
            function checkAcceptation() {
              if (!confirm("Error al iniciar Sesion Intentalo Nuevamente "))
              history.go(-1);
              return " "

            }
          checkAcceptation();
              window.location.href = "login.php";
        </script> 
</head>
<body>
</body>
</html>