<?php

function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    $data = str_replace('|', '', $data);
    return $data;
}

set_error_handler("errores");

function errores($errno, $errstr){
    //Control de errores
    
    if ($errno === E_USER_ERROR) {
        echo "<p><strong>Error:</strong> $errstr</p>";
    }
}

function comprobarDatosJugador($user, $nombre, $apellido) {
    //Comprobamos que los datos de cada campo no estén vacíos y que el usuario no se repita

    if ($user == null || $nombre == null || $apellido == null){ 
        trigger_error("Se deben rellenar todos los campos", E_USER_ERROR);
        return false;   
    }

    $fichero = fopen("jugadores.txt", "r");
    $users = array();

    //leemos cada linea
    while (($linea = fgets($fichero)) !== false) {
        // separamos los datos por columnas
        $datos = explode("|", trim($linea));
        
        if (count($datos) === 3) {
            if ($datos[0] == $user) {
                trigger_error("El usuario introducido ya existe", E_USER_ERROR);
                return false;
            }
            $users[] = $datos[0];
        }
    }
    
    fclose($fichero);

    //devuelve T si se cumplen todos los requisitos
    return true;
}

function comprobarDados($numdados) {
    //Comprobamos que el número de dados introducido sea correcto

    if ($numdados < 1 || $numdados > 10) {
        trigger_error("El número de dados debe estar entre 1 y 10", E_USER_ERROR);
        return false;
    }
    else{
        return true;
    }
}

function guardarRegistro($user, $nombre, $apellido){
    //Guardamos en un fichero los datos del formulario

    $fichero = fopen("jugadores.txt", "a");
    fwrite($fichero, $user . "|" . $nombre . "|" . $apellido . "\n");
    fclose($fichero);
}

function establecerJugadores(){
    //leemos el fichero donde guardamos los registros
    $fichero = fopen("jugadores.txt", "r");
    //array para almacenar los jugadores
    $jugadores = array();
    
    //leemos cada linea
    while (($linea = fgets($fichero)) !== false) {
        // separamos los datos por columnas
        $datos = explode("|", trim($linea));
        //guardamos en un array los datos 
        if (count($datos) === 3) {
            $jugadores[] = array(
                'user' => $datos[0],
                'nombre' => $datos[1],
                'apellido' => $datos[2]
            );
        }
    }
    
    fclose($fichero);

    //devuelve un array que almacena todos los jugadores con sus datos
    return $jugadores;
}

function ejecutarDados($numdados, $jugadores) {
    $dadosObtenidos = array();
    
    //guardamos en un array el user de cada usuario registrado y a su vez los dados que tiran
    for ($i=0; $i < count($jugadores); $i++) {
        $userJugador = $jugadores[$i]['user'];
        $dadosObtenidos[$userJugador] = tirarDados($numdados);
    }
    
    //devolvemos un array donde la clave es un jugador y su valor es el conjunto de dados que ha tirado.
    return $dadosObtenidos;
}

function tirarDados($numdados) {
    //tiramos los dados cuantas veces quiera el usuario segun el numero de dados
    $dadosPorJugador = array();

    for ($i=0; $i < $numdados; $i++) {
        $dado = rand(1, 6);
        array_push($dadosPorJugador, $dado);
    }

    //devolvemos un array que contiene todos los dados lanzados
    return $dadosPorJugador;
}

function sumarDados($dadosPorJugador, $numdados) {
    //realizamos la suma de todos los dados obtenidos
    $sumas = array();

    foreach ($dadosPorJugador as $jugador => $dados) {
        $suma = 0;

        if (count(array_unique($dados)) == 1 && $numdados > 2) {
            
            $suma += 100;
        }
        else{
            $suma += array_sum($dados);
        }

        $sumas[$jugador] = $suma;
    }

    //devolvemos un array donde la clave es el nombre del jugador y el valor es 
    //la puntuación que obtuvo al sumar sus dados
    return $sumas;
}

function generarTabla($dadosObtenidos) {
    //generamos la tabla, mostrando el nombre del jugador y sus dados
    echo "<br><h3>Tabla de Resultados</h3>";
    echo "<table border='1' style='border-collapse: collapse;'>";
    foreach ($dadosObtenidos as $jugador => $dados) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($jugador) . "</td>";

        foreach ($dados as $dado) {
            echo "<td><img src='./images/" . $dado . ".PNG' alt='Dado " . $dado . "' style='width:50px; height:50px;'></td>";
        }
        echo "</tr>";
    }
    echo "</table>";
}

function mostrarGanador($resultadoSuma) {
    //suma más alta
    $maxSuma = max($resultadoSuma);
    //jugador/es con la suma máxima
    $ganadores = array_keys($resultadoSuma, $maxSuma);
    
    // Leer el archivo y obtener los datos de cada jugador
    echo "<br><b>Número de Ganadores: " . count($ganadores) . "</b><br>";
    foreach ($ganadores as $user) {
        $encontrado = false;
        
        // Abrimos el archivo y buscamos al ganador según el usuario
        $fichero = fopen("jugadores.txt", "r");
        while (($linea = fgets($fichero)) !== false) {
            $datos = explode("|", trim($linea));
            if (count($datos) === 3 && $datos[0] === $user) {
                $nombreCompleto = $datos[1] . " " . $datos[2];
                echo "<b>Ganador:</b> $user ($nombreCompleto), con un total de $maxSuma<br>";
                $encontrado = true;
                break; // Salimos del bucle si se encuentra el usuario
            }
        }
        fclose($fichero);
    }
}


?>