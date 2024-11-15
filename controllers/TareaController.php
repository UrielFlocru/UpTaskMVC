<?php

namespace Controllers;

use Model\Proyecto;

class TareaController {
    public static function index (){

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
            } else  {
                $respuesta = [
                    'tipo' => 'exito',
                    'mensaje' => 'La tarea se agrego con exito'
                ];
                echo json_encode($respuesta);
                return;
            }
            
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