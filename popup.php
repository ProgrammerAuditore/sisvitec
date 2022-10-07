<!DOCTYPE html>
<html lang="en">
  <head> 
      <meta charset="utf-8">
   <title>Mostrar Ventane Modal de forma Automático</title>
   <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
   <script src="//code.jquery.com/jquery-1.12.0.min.js"></script>
   <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>  
   <script>
      $(document).ready(function()
      {
         $("#mostrarmodal").modal("show");
      });
    </script>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>SisVinTec</title>
    <!-- Bootstrap core CSS -->
      <link href="Estilos/dist/css/bootstrap.min.css" rel="stylesheet">
      <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
      <link href="Estilos/EstilosAgregar.css" rel="stylesheet">
      
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
      <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
      
  </head>

  <body>
      
      <div class="modal fade" id="mostrarmodal" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
           <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
              <h3>Error al iniciar sesión</h3>
           </div>
           <div class="modal-body">
              <h4>Usuario, Contraseña o tipo de usuario incorrectos</h4>
              Vuelve a internarlo nuevamente.    
       </div>
           <div class="modal-footer">
               <form action="index.php">
                   <button type="submit" class="btn btn-default"> cerrar</button>
               </form>
               
           </div>
      </div>
   </div>
</div>
      <section class="jumbotron">
          <div class="container">
              <br>
              <br>
              <h1> SisVinTec</h1>
              
              <br>
          </div>
      </section>
      
      <footer>
            <div class="contenedor">
                <p >Copyright &copy; BCB</p>
            </div>           
       </footer>
      <!-- Bootstrap core JavaScript -->
    <script src="../Estilos/dist/js/jquery.js"></script>
    <script src="../Estilos/dist/js/bootstrap.min.js"></script>
  </body>