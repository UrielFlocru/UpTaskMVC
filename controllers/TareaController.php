<?php

namespace Controllers;

use Model\Proyecto;
use Model\Tarea;

class TareaController {
    public static function index (){
        $proyectoUrl = $_GET['url'];
        if (!$proyectoUrl) header('Location: /dashboard');

        session_start();
        $proyecto = Proyecto::where('url', $proyectoUrl);
        if (!$proyecto || $proyecto->propietarioId !== $_SESSION['id'] ) header('Location: /404');

        $tareas = Tarea::belongsTo('proyectoId', $proyecto->id);
        echo json_encode(['tareas' => $tareas]);



    }

    public static function crear ( ){
        if ($_SERVER['REQUEST_METHOD'] === 'POST'){
            session_start();
            $proyecto = Proyecto::where('url', $_POST['url']);

            if (!$proyecto || $proyecto->propietarioId !== $_SESSION['id']){
                $respuesta = [
                    'tipo' => 'error',
                    'mensaje' => 'Hubo un problema al agregar la tarea'
                ];
                echo json_encode($respuesta);
                return;
            } 

            //Si todo bien instanciar y crear la tarea
            $tarea = new Tarea($_POST);
            $tarea->proyectoId = $proyecto->id;
            $resultado = $tarea->guardar();
            
            $respuesta = [
                'tipo' => 'exito',
                'id' => $resultado->id,
                'mensaje' => 'Tarea creada correctamente',
                'proyectoId' => $proyecto->id

            ];

            echo json_encode($respuesta);

        }
    }
    public static function actualizar ( ){
        if ($_SERVER['REQUEST_METHOD'] === 'POST'){


        }
    }
    public static function eliminar ( ){
        if ($_SERVER['REQUEST_METHOD'] === 'POST'){


        }
    }
}