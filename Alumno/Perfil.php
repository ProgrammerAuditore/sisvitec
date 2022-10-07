<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<style type="text/css">
  </style>
<head>
<link rel="stylesheet" href="login.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>SisVinTec</title>
</head>
<div class="login">
  <h1>Iniciar Sesion</h1>
  <h1>Alumnos</h1>
    <form method="post" action="ComprobarUsuario.php">
      <input type="text" name="user" placeholder="Usuario:" required="required" />
        <input type="password" name="password" placeholder="Contraseña:" required="required" />
         <a href="ComprobarUsuario.php">                 
        <button type="submit" class="btn btn-primary btn-block btn-large">Iniciar Sesiòn.</button>
      </a>
    </form>
</div>
<body>
</body>
</html>
