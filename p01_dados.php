<HTML>
<HEAD> 
 <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
     <title>JUEGO DADOS - PRÁCTICA OBLIGATORIA</title>
    <link rel="stylesheet" href="./bootstrap.min.css">
  </head>

</HEAD>

<BODY>

  <form name='juegodados' action='<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>' method='POST'>

  <div class="container ">
    <!--Aplicacion-->
    <div class="card border-success mb-3" style="max-width: 30rem;">
      <div class="card-header"><B>REGISTRO</B></div>
      <div class="card-body">

        <B>Usuario: </B><input type='text' name='user' value='' size=25><br><br> 
        <B>Nombre: </B><input type='text' name='nombre' value='' size=25><br><br> 
        <B>Apellido: </B><input type='text' name='ape' value='' size=25><br><br> 

        <div>

          <input type="submit" value="Registrarse" name="guardar" class="btn btn-warning disabled">
            
        </div>
      </div>
    </div>    
  </div>	

  </form>

  <?php

  include ("funciones.php");
  if (isset($_POST['guardar'])) {
    $user = test_input($_POST['user']);
    $nombre = test_input($_POST['nombre']);
    $apellido = test_input($_POST['ape']);
    
    //Validaciones
    $valido = comprobarDatosJugador($user, $nombre, $apellido);
    
    if ($valido) {
      //Guardamos registro
      guardarRegistro($user, $nombre, $apellido);
    }
    
    
  }

  ?>


</BODY>
</HTML>