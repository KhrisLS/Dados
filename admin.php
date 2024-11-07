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
      <div class="card-header"><B>TIRAR DADOS</B></div>
      <div class="card-body">
      <B>Numero Dados: </B><input type='text' name='numdados' value='' size=5><br><br>
        <div>

          <input type="submit" value="Comenzar Partida" name="jugar" class="btn btn-warning disabled">
            
        </div>
      </div>
    </div>    
  </div>	

  </form>
  
  <?php

  include ("funciones.php");

  if (isset($_POST['jugar'])) {

    $numdados = test_input($_POST['numdados']);

    //Validaciones
    $valido = comprobarDados($numdados);
    
    if ($valido) {
      //establecemos jugadores registrados
      $jugadores = establecerJugadores();
      //tiramos los dados por cada jugador
      $dadosPorJugador = ejecutarDados($numdados, $jugadores);
      //sumamos los dados
      $resultadoSuma = sumarDados($dadosPorJugador, $numdados);
      //generamos la tabla
      generarTabla($dadosPorJugador);
      //mostramos el ganador
      mostrarGanador($resultadoSuma);
    }
    

  }

  ?>


</BODY>
</HTML>