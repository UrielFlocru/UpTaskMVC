<?php

namespace Model;

class Usuario extends ActiveRecord{
    protected static $tabla = 'usuarios'; 
    protected static $columnasDB = ['id', 'nombre', 'email', 'password', 'token', 'confirmar'];

    public $id;
    public $nombre;
    public $email;
    public $password;
    public $password2;
    public $token;
    public $confirmar;

    public function __construct($args = []){
        $this->id = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
        $this->email = $args['email'] ?? '';
        $this->password = $args['password'] ?? '';
        $this->password2 = $args['password2'] ?? '';
        $this->token = $args['token'] ?? '';
        $this->confirmar = $args['confirmar'] ?? 0;
    }

    public function validarDatos (){
        if (!$this->nombre){
            self::$alertas['error'][] = 'El nombre es obligatorio';
        }
        if (!$this->email){
            self::$alertas['error'][] = 'El correo electrónico es obligatorio';
        }
        if (!$this->password){
            self::$alertas['error'][] = 'La contraseña es obligatoria';
        }
        if (strlen($this->password) < 8){
            self::$alertas['error'][] = 'La contraseña debe tener al menos 8 caracteres';
        }
        if ($this->password !== $this->password2){
            self::$alertas['error'][] = 'Las contraseñas deben ser iguales';
        }
        
        return  self::$alertas;
    }

    public function validarEmail(){
        if (!$this->email){
            self::$alertas['error'][] = 'El correo electrónico es obligatorio';
        }
        if (!filter_var($this->email,FILTER_VALIDATE_EMAIL)){
            self::$alertas['error'][] = 'El correo electrónico no es valido';
        }
        return self::$alertas;
    }

    public function validarContraseñas (){
        if (!$this->password){
            self::$alertas['error'][] = 'La contraseña es obligatoria';
        }
        if (strlen($this->password) < 8){
            self::$alertas['error'][] = 'La contraseña debe tener al menos 8 caracteres';
        }
        if ($this->password !== $this->password2){
            self::$alertas['error'][] = 'Las contraseñas deben ser iguales';
        }
        
        return  self::$alertas;
    }

    public function validarLogin(){
        if (!$this->email){
            self::$alertas['error'][] = 'El correo electrónico es obligatorio';
        }
        if (!filter_var($this->email,FILTER_VALIDATE_EMAIL)){
            self::$alertas['error'][] = 'El correo electrónico no es valido';
        }
        if (!$this->password){
            self::$alertas['error'][] = 'La contraseña es obligatoria';
        }
        return self::$alertas;

    }

    public function validarPerfil (){
        if (!$this->nombre){
            self::$alertas['error'][] = 'El nombre es obligatorio';
        }
        if (!$this->email){
            self::$alertas['error'][] = 'El correo electrónico es obligatorio';
        }
        return self::$alertas;
    }

    public function hashPass (){
        $this->password = password_hash($this->password, PASSWORD_BCRYPT);
    }

    public function generarToken (){
        $this->token = uniqid();
    }
}