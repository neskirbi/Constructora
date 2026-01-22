<?php

date_default_timezone_set('America/Mexico_City');


function Memoria(){
    set_time_limit(0);
    ini_set('memory_limit', '-1');
    ini_set('max_execution_time', 0); 
    ini_set('post_max_size', '30G');
}

function Version(){
    return 1;
}

function GenerarPass(){  
    return \Illuminate\Support\Str::random(8);
}


/**
 * Path Fotos
 */
function ProyectName(){
    return '';
}

function Empresa(){
    return 'Constructora';
}


function GetUuid(){
    $data = random_bytes(16);
    $data[6] = chr(ord($data[6]) & 0x0f | 0x40); 
    $data[8] = chr(ord($data[8]) & 0x3f | 0x80); 
    return str_replace("-","",vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4)));
}


    function Comentarios(){
        //Escribir simpre arriba de esta funcion las nuevas funciones.
        //No dejar tanto puto espacio aqui abajo por que si no vale puritita verga la libreria de exportar a excel, PINCHE MAMADA!!!!
    }
?>