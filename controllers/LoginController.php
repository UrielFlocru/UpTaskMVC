<?php

namespace Controllers;

use Classes\Email;
use Model\Usuario;
use MVC\Router;

class LoginController {
    public static function login (Router $router){
        $alertas = [];

        if ($_SERVER['REQUEST_METHOD']==='POST'){
            $usuario = new Usuario($_POST);
            $alertas = $usuario->validarLogin();

            if (empty($alertas)){
                $usuario = Usuario::where('email',$usuario->email);
                
                if (!$usuario){
                    Usuario::setAlerta('error','El usuario no existe, verifica tu usuario y contraseña');
                } else if ($usuario->confirmar==='0'){
                    Usuario::setAlerta('error','El usuario no esta confirmado');
                } else {
                    //El usuario existe y esta confirmado
                    if (password_verify($_POST['password'],$usuario->password)){
                        //Iniciar sesión
                        session_start();
                        $_SESSION['id'] = $usuario->id;
                        $_SESSION['nombre'] = $usuario->nombre;
                        $_SESSION['email'] = $usuario->email;
                        $_SESSION['login'] = true;

                        //Redireccionar
                        header('Location: /dashboard');

                    } else {
                        Usuario::setAlerta('error','Contraseña incorrecta');
                    }
                }
            }
        }
        
        $alertas = Usuario::getAlertas();
        $router->render('auth/login',[
            'titulo' => 'Iniciar Sesión',
            'alertas' => $alertas
    
        ]);
    }

    public static function logout(){
        session_start();
        $_SESSION = [];
        header('Location: /');
    }

    public static function crear (Router $router){

        $usuario = new Usuario;
        $alertas = [];
        
        if ($_SERVER['REQUEST_METHOD']==='POST'){
            //Sincronizar valores
            $usuario->sincronizar($_POST);

            //Validar los datos
            $alertas= $usuario->validarDatos();

            if (empty($alertas)){
                $existeUsuario = Usuario::where('email', $usuario->email);

                if ($existeUsuario){
                    Usuario::setAlerta('error', 'El usuario ya esta registrado');
                    $alertas = Usuario::getAlertas();
                }
                else{ //Crear un nuevo usuario
                    //Hashear el password
                    $usuario->hashPass();
    
                    //Eliminar password2
                    unset($usuario->password2);
    
                    //Generar token
                    $usuario->generarToken();

                    //Enviar correo
                    $email = new Email($usuario->email, $usuario->nombre, $usuario->token );
                    $email->enviarConfirmacion();
    
                    //Guardar usuario
                    $resultado = $usuario->guardar();
    
                    if ($resultado){
                        header('Location: /mensaje');
                    }
                }
            }
            
        }

        $router->render('auth/crear',[
            'titulo' => 'Crea tu cuenta en UpTask',
            'usuario' => $usuario,
            'alertas' => $alertas
        ]);
    }
    public static function olvide (Router $router){
        $alertas = [];
        $accion = false;
        
        if ($_SERVER['REQUEST_METHOD']==='POST'){
            $auth = new Usuario($_POST);
            $alertas = $auth->validarEmail();

            if (empty($alertas)){
                //Verificar que el email exista
                $usuario = Usuario::where('email',$auth->email);

                if ($usuario && $usuario->confirmar === '1'){  //El usuario existe y esta confirmado
                    //Generar un nuevo token
                    $usuario->generarToken();
                    $usuario->guardar();

                    //Enviar email
                    $email = new Email($usuario->email, $usuario->nombre, $usuario->token);
                    $email->enviarInstrucciones();

                    Usuario::setAlerta('exito', 'Se han enviado las instrucciones a tu correo electrónico');
                    $accion = true;
                }else{
                    Usuario::setAlerta('error','El usuario no existe o no esta confirmado');
                }
            }
        }
        $alertas= Usuario::getAlertas();
        $router->render('auth/olvide',[
            'titulo' => 'Crea tu cuenta en UpTask',
            'alertas' => $alertas,
            'accion' => $accion
        ]);
    }
    public static function restablecer (Router $router){
        $alertas = [];
        $token = s($_GET['token']);
        $error = false;

        $usuario = Usuario::where('token',$token);
        
        if (empty($usuario)){
            Usuario::setAlerta('error', 'El token no es valido');
            $error=true;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST'){
            $password = new Usuario($_POST);
            $alertas = $password->validarContraseñas();
            

            if (empty($alertas)){
                //Eliminar pass y colocar la nueva
                $usuario->password = null;
                $usuario->password = $password->password;
                $usuario->hashPass();
                $usuario->token = null;
                unset($usuario->password2);

                

                $resultado = $usuario->guardar();
                
                if ($resultado){
                    header('Location: /?status=1');
                }
            }

        }
        $alertas = Usuario::getAlertas();
        $router->render('auth/restablecer',[
            'titulo' => 'Restablece tu contraseña',
            'alertas' => $alertas,
            'error' => $error
        ]);
    }

    public static function mensaje (Router $router){
        $router->render('auth/mensaje',[
            'titulo' => 'Restablece tu contraseña'
        ]);
      
    }
    public static function confirmar (Router $router){
        $alertas = [];
        $token = s($_GET['token']);
        $usuario = Usuario::where('token',$token);

        if (empty($usuario)){
            usuario::setAlerta('error','El token es invalido');
        }
        else{
            $usuario->confirmar = 1;
            $usuario->token = null;
            $usuario->guardar();

            Usuario::setAlerta('exito','Cuenta confirmada correctamente');
        }

        $alertas= Usuario::getAlertas();
        $router->render('auth/confirmar',[
            'titulo' => 'Confirma tu cuenta UpTask',
            'alertas' => $alertas
        ]);
       
    
    }

}