<?php

namespace Controllers;

use Model\Proyecto;
use MVC\Router;
use Model\Usuario;

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

        $usuario = Usuario::find($_SESSION['id']);
        $alertas = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST'){
            $usuario->sincronizar($_POST);
            $alertas = $usuario->validarPerfil();

            if (empty($alertas)){

                $usuarioExiste = Usuario::where('email', $usuario->email);
                
                if($usuarioExiste && $usuarioExiste->id !== $usuario->id){
                    //Error
                    Usuario::setAlerta('error','El correo electrónico ya se encuentra registrado');
                    $alertas = Usuario::getAlertas();

                } else {
                    //Guardar cambios
                    $usuario->guardar();

                    Usuario::setAlerta('exito','Guardado correctamente');
                    $alertas = Usuario::getAlertas();

                    $_SESSION['nombre'] = $usuario->nombre;
                }

                

            }

        }
        
        $router->render('dashboard/perfil',[
            'titulo' => 'Perfil',
            'usuario' => $usuario,
            'alertas' => $alertas
        ]);
    }

    public static function cambiarPass (Router $router){
        session_start();
        isAuth();

        $alertas = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST'){
            $usuario = Usuario::find($_SESSION['id']);

            //Sincronizar los datos
            $usuario->sincronizar($_POST);

            $alertas = $usuario->nuevaPass();

            if (empty($alertas)){
                $resultado = $usuario->comprobarPass();

                if ($resultado){

                    $usuario->password = $usuario->nueva;

                    //Eliminar propiedades no necesarias
                    unset($usuario->actual);
                    unset($usuario->nueva);

                    //Hashear la nueva contraseña
                    $usuario->hashPass();

                    //Guardar nueva contraseña
                    $resultado = $usuario->guardar();

                    if ($resultado){
                        Usuario::setAlerta('exito', 'Contraseña guardada correctamente');
                        $alertas = Usuario::getAlertas();
                    }

                } else {
                    Usuario::setAlerta('error', 'Contraseña incorrecta');
                    $alertas = Usuario::getAlertas();
                }
            }

        }

        $router->render('dashboard/cambiar-password',[
            'titulo' => 'Cambiar contraseña',
            'alertas' => $alertas
        ]);
    }

}
