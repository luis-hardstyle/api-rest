<?php


//arreglo de tipos de recursos disponobles
$allowedResourceTypes = [
    'books',
    'authors',
    'genres',

];

//validacion si el tipo de recurso esta en el arreglo
$resourseType = $_GET['resource_type'];

if (!in_array( $resourseType,$allowedResourceTypes))
{
    http_response_code(400);
    die;
    
}


// se definen los recursos
$books  =[
    1 =>[
        'titulo' => 'awa de owo',
        'id_autor' => 2,
        'id_genero' => 2,
    ],
    2 =>[
        'titulo' => 'owo de awa',
        'id_autor' => 3,
        'id_genero' => 3,
    ],
    3 =>[
        'titulo' => 'ewe de uwu',
        'id_autor' => 4,
        'id_genero' => 2,
    ]
];
//avisar al cliente que se esta enviando json
header('content-Type: aplication/json');

//levantamos el id del recurso buscado
$resourceId = array_key_exists('resource_id', $_GET) ? $_GET['resource_id']: '';

// determinar lo que se realizara en cada caso
//metodo que se realizo para cada peticion
switch (strtoupper($_SERVER['REQUEST_METHOD'])){
    case 'GET':
        if(empty($resourceId)){
            //devolver la coleccion de libros en formato json
             echo json_encode ($books);
        }else{
            if(array_key_exists($resourceId, $books)){
                echo json_encode( $books[ $resourceId ] );
            }else{
                http_response_code(404);
            }
        }
        

        break;
    case 'POST':
        //tomamos la entrada cruda
        $json = file_get_contents('php://input');
        // Transformamos el json recibido a un nuevo elemento del array
        $books[] = json_decode($json, true);

        //echo array_keys($books)[count($books) - 1];
        echo json_encode( $books );
        break;
    case 'PUT':
        //validamos que el recurso buscado exista
        if(!empty($resourceId) && array_key_exists($resourceId,$books)){
            
            //tomamos la entrada cruda
            $json = file_get_contents('php://input');

            // Transformamos el json recibido a un nuevo elemento del array
            $books[$resourceId] = json_decode($json, true);

            //retornamos la coleccion modificada en formato json
            echo json_encode($books);
        }
        break;
    case 'DELETE':
        //validamos que el recurso exista
        if(!empty($resourceId) && array_key_exists($resourceId,$books)){

            //Eliminamos el recurso
            unset($books[$resourceId]);
        }
        echo json_encode($books);
        break;
    
}


/*
-- Autenticacion via HTTP
$user = array_key_exists('PHP_AUTH_USER',$_SERVER) ? $_SERVER['PHP_AUTH_USER'] : '';
$pwd = array_key_exists('PHP_AUTH_PW',$_SERVER) ? $_SERVER['PHP_AUTH_PW'] : '';

if ($user !== 'mauro' || $pwd !== '1234') {
	die;
}
*/

// Autenticacion via HMAC
/*if(
    !array_key_exists('HTTP_X_HASH', $_SERVER) ||
    !array_key_exists('HTTP_X_TIMESTAMP', $_SERVER) ||
    !array_key_exists('HTTP_X_UID', $_SERVER)

){
    die;
};   

list($hash, $uid, $timestamp) = [
    $_SERVER['HTTP_X_HASH'],
    $_SERVER['HTTP_X_UID'],
    $_SERVER['HTTP_X_TIMESTAMP'],
];

$secret = 'Sh!! No se lo cuentes a 
nadie!';


$newHash = sha1($uid.$timestamp.$secret);

if ($newHash !== $hash){
    die;
};*/


// if (!array_key_exists('HTTP_X_TOKEN', $_SERVER)){
//     die;
// };

// $url = 'http://localhost:8001';

// $ch = curl_init($url);
// curl_setopt(
//     $ch,
//     CURLOPT_HTTPHEADER,
//     [
//         "X-Token:{$_SERVER['HTTP_X_TOKEN']}"
//     ]
// );
// curl_setopt(
//     $ch,
//     CURLOPT_RETURNTRANSFER,
//     true
// );

// $ret = curl_exec($ch);

// if($ret !== 'true'){
//     die;
// };