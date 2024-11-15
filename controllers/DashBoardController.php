<?php

namespace Controllers;

use Model\Proyecto;
use MVC\Router;

class DashBoardController {
    
    public static function index (Router $router){
        session_start();
        isAuth();

        $proyectos = Proyecto::belongsTo('propietarioId',$_SESSION['id']);
        

        
        $router->render('dashboard/index',[
            'titulo' => 'Proyectos',
            'proyectos' => $proyectos
        ]);
    }
    public static function crear_proyecto (Router $router){
        session_start();
        isAuth();

        $alertas = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST'){
            $proyecto = new Proyecto($_POST);

            //validación
            $alertas = $proyecto->validarProyecto();

            if (empty($alertas)){
                //Generar url única
                $hash = md5(uniqid());
                $proyecto->url = $hash;

                //Almacenar al creador del proyecto
                $proyecto->propietarioId = $_SESSION['id'];

                //Guardar proyecto
                $proyecto->guardar();

                //Redireccionar
                header('Location: /proyecto?url=' . $proyecto->url );
            }

        }

        $router->render('dashboard/crear-proyecto',[
            'titulo' => 'Crear Proyecto',
            'alertas' => $alertas
        ]);
    }

    public static function proyecto (Router $router){
        session_start();
        isAuth();

        //Revisar que exista la URL
        $url = $_GET['url'];
        if (!$url) header('Location: /dashboard');

        //Revisar el propietario del proyecto
        $proyecto = Proyecto::where('url',$url);
        if ($proyecto->propietarioId !== $_SESSION['id']) header('Location: /dashboard');

        $router->render('dashboard/proyecto',[
            'titulo' => $proyecto->proyecto
        ]);
    }

    public static function perfil (Router $router){
        session_start();

        isAuth();
        
        $router->render('dashboard/perfil',[
            'titulo' => 'Perfil'
        ]);
    }
}
