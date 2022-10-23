<header><meta charset="gb18030">
          <style>
                      .dropdown-submenu .dropdown-menu {
    top: 0;
    left: 80%;
    margin-left: .1rem;
    margin-right: .1rem;
}
          </style>

          <nav class="navbar navbar-inverse navbar-static-top" role="navigation">
              <div class="container">

                  <div class="navbar-header">

                      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navegacion-fm">
                          <span class="sr-only">Desplejar / ocultar menu</span>
                          <span class="icon-bar"></span>
                          <span class="icon-bar"></span>
                          <span class="icon-bar"></span>
                      </button>
                  </div>
                  <!-- inicia menu -->
                  <div class="collapse navbar-collapse" id="navegacion-fm">
                      <ul class='nav navbar-nav'>
                          <li class='active'><a href='PAdmon.php'>Inicio</a></li>
                          <li class='dropdown'>
                            <a href='Carrera.php' class='dropdown-toggle' data-toggle='dropdown' role='button'>
                                Empresa<span class='caret'></span></a>
                              <ul class='dropdown-menu' role='menu'>
                                  <li><a href='AgrEmpresa.php'>Agregar </a></li>
                                  <li><a href='ConsultarEmpresa.php'>Consultar/Eliminar</a></li>
                                </ul>
                          </li>
                                                    <li class='dropdown'>
                            <a href='#' class='dropdown-toggle' data-toggle='dropdown' role='button'>
                                Proyecto <span class='caret'></span></a>
                              <ul class='dropdown-menu' role='menu'>
                                  <li class='dropdown dropdown-submenu'>
                                          <li><a href='CP.php'>Consultar</a></li>
                                      
                                  </li>
                               </ul>
                          </li>
  
                          <li class='dropdown'>
                            <a href='#' class='dropdown-toggle' data-toggle='dropdown' role='button'>
                                Alumnos <span class='caret'></span></a>
                              <ul class='dropdown-menu' role='menu'>
                                  <li class='dropdown dropdown-submenu'>
                                          <li><a href='AgrAlu.php'>Agregar</a></li>
                                          <li><a href='ConsultaAlumno.php'>Consultar/Eliminar</a></li>
                                      
                                  </li>
                               </ul>
                          </li>
                            <li class='dropdown'>
                            <a href='#' class='dropdown-toggle' data-toggle='dropdown' role='button'>
                                Sesión <span class='caret'></span></a>
                              <ul class='dropdown-menu' role='menu'>
                                  <li><a href='/'>Salir</a></li>
                                  </ul>
                          </li>
                      </ul>
                      </div>
              </div>
          </nav>
      </header>
