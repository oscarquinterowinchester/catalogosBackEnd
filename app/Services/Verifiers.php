<?php

namespace App\Services;
use Illuminate\Support\Facades\DB;

//Esta clase realiza diferentes tareas de verificación para las diferentes acciones que se realizan previas a la inserción
//o recepccion de información externa
class Verifiers{
    private $strings;
    public function __construct() {}

    //Verifica que se recibe una cadena con un formato valido de email, si se proporciona un dominio
    //Se verifica que dicho dominio esté después del @
    public function email($mail, $domain = null){
        if(!$mail || !is_string($mail)) return false;

        if (!preg_match("/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/", $mail)) return false;

        if($domain){
            $index = $this->searchIndex($mail, '@');
            if($index == -1) return false;
            if(substr($mail,$index+1) != $domain) return false;
        }

        return true; 
    }

    //Verifica que un dato recibido es un rfc de persona fisica (en méxico) valido
    public function RFC_persona_fisica_mx($rfc) {
        if(!$this->string($rfc,null, 1, 20)) return false;
        if (!preg_match("/^[A-Z&Ñ]{4}(\d{2})(0[1-9]|1[0-2])(0[1-9]|[12][0-9]|3[01])[A-Z\d]{3}$/", $rfc)) return false;
        return true;
    }

    //Verifica que un dato recibido es un rfc de persona moral (en méxico) valido
    public function RFC_persona_moral_mx($rfc) {
        if(!$this->string($rfc,null, 1, 20)) return false;
        if (!preg_match("/^[A-Z&Ñ]{3}(\d{2})(0[1-9]|1[0-2])(0[1-9]|[12][0-9]|3[01])[A-Z\d]{3}$/", $rfc)) return false;
        return true;
    }

    //Verifica si una cadena es una estructura de número telefonico, se le puede pasar el numero total de digitos, maximo de digitos o una expresion
    //regular con el fromato que debe tener para hacer la verificación
    public function phone($phone = null, $digits = null, $maxDigits = null, $regEx = null){
        if(!$phone || !is_string($phone)) return false;
        if( $digits && is_numeric($digits) && intval($digits) != strlen($phone)) return false;
        if( $maxDigits && is_numeric($maxDigits) && intval($maxDigits) < strlen($phone)) return false;
        if ($regEx && !preg_match($regEx, $phone)) return false;
        return true;
    }

     //Verifica que dentro de un array un indice exista y tenga un valor entero , si se le pasa un valor miniomo y/o maximo verifica que el entero este en el rango
     public function inArrayNumericInt($array, $index, $min = null, $max = null) {
        if(!array_key_exists($index,$array) || 
        !$array[$index] || 
        !is_numeric($array[$index]) || 
        ($min && intval($array[$index]) < $min) || 
        ($max && intval($array[$index]) > $max)) return false;

        return true;
    }

    //igual al anterio pero con valores decimales
    public function inArrayNumericFloat($array, $index, $min = null, $max = null) {
        if(!array_key_exists($index,$array) || 
        !$array[$index] || 
        !is_numeric($array[$index]) || 
        ($min && floatval($array[$index]) < $min) || 
        ($max && floatval($array[$index]) > $max)) return false;

        return true;
    }

    //igual al anteior pero con string
    public function inArrayString($array, $index, $len = null, $min = null, $max = null) {
        if(!array_key_exists($index,$array) || 
        !$array[$index] || 
        !is_string($array[$index]) || 
        ($len && strlen(trim($array[$index])) !== $len) ||
        ($min && strlen(trim($array[$index])) < $min) ||
        ($max && strlen(trim($array[$index])) > $max) ) return false;

        return true; 
    }

    //verifica si una variable es de tpo string con los atributos inidcados en los valores de entrada
    public function string($value, $len = null, $min = null, $max = null) {
        if(!is_string($value) || 
        ($len && strlen(trim($value)) !== $len) ||
        ($min && strlen(trim($value)) < $min) ||
        ($max && strlen(trim($value)) > $max) ) return false;

        return true; 
    }

    //Verifica que in valor sea un entero y cupla con los rangos enviados
    public function int($value, $min = null, $max = null) {
        if(!isset($value) || !is_numeric($value) || 
        ($min && intval($value) < $min) || 
        ($max && intval($value) > $max)) return false;
        return true;
    }

    //igual al anterio pero con valores decimales
    public function float($value = null, $min = null, $max = null) {
        if(!isset($value) || 
        !is_numeric($value) || 
        ($min && floatval($value) < $min) || 
        ($max && floatval($value) > $max)) return false;
        return true;
    }

     //verifica si una avriable es un array de strings, recibe la posibilidad de verificar la longitud mínima y máxima
     public function array_string($value = null, $minLength = null, $maxLength = null) {
        if(!isset($value) || 
        !is_array($value) || 
        ($minLength && count($value) < $minLength) || 
        ($maxLength && count($value) > $maxLength)) return false;

        foreach ($value as $p) if(!is_string($p)) return false;
        return true;
    }

    public function searchIndex($string, $single){
        if(!is_string($string) || !is_string($single) || strlen($single) != 1) return -1;

        $len = strlen($string);
        for ($i=0; $i < $len; $i++)  if(substr($string,$i,1) == $single) return $i;
        
        return -1;
    }

    //Atma un query usando las palabras clave y klos nombres de las columnas, amabas deben ser strings cada valor separados por espacios
    public function generalSearh($busqueda, $columnas) {
        $busqueda = $this->extraerBloquesUnicos($busqueda);
        $columnas = $this->extraerBloquesUnicos($columnas);
        $conditional ="";
        if(count($busqueda) == 0 || count($columnas) == 0) return '';

        foreach ($busqueda as $key) {

            $conc = ' AND LOWER(CONCAT_WS (';
            foreach ($columnas as $col) {
                $conc="$conc $col,' ',";
            }
            $len = strlen($conc);
            $conditional = $conditional.substr($conc, 0, $len - 5).")) LIKE LOWER('%".$key."%') ";
        }
        
        return  substr($conditional,4);
    }

    //Extrae los bloques de letras dentro de una cadena en una cadena con valores no repetidos
    public function extraerBloquesUnicos($cadena) {
        if(!$this->string($cadena, null, 1, null)) return [];

        $data = [];
        $len = strlen($cadena);
        $palabra = "";
        for ($i=0; $i < $len; $i++) { 
            $letra = substr($cadena, $i, 1);
            if($i ===  $len-1){
                if($letra !== ' ') $palabra = $palabra.$letra;
                if($palabra !== '') $data[] = "".$palabra;
            }else if($letra === ' ' ){
                if(strlen($palabra) > 0 ){ 
                    $data[] = $palabra;
                    $palabra = '';
                }
            }else{
                $palabra = $palabra.''.$letra;
            }
        }
        $data = array_unique($data);
    
        return $data;
    }

    //Recibe un perfil de usuario (es importate que este previamente modificado y contenga el atributo) devolvera un mensje con el estatus en false si no se contro el dato util
    public function compania_fom_diffenrent_user($userData, $company_text = null) {
        $company;
        if($userData->rango_permiso < 500){//si el usuario es de jerarquia mas alta que COMPANY ADMIN
            if(!$this->string($company_text, null, 1, 250)) return ['estatus' => false,'msj' => 'Proporcione en nombre de la compañia a la que se le asignará este tipo de almacenamiento'];
            //Verificamos la existencia de la compañia en la base de datos
            $company = DB::selectOne('SELECT company FROM companys WHERE LOWER(company) = ?',[strtolower($company_text)]);
            if(!$company) return ['estatus' => false,'msj' => 'La compañia no existe, verifique que el nombre que envía sea correcto.'];
            $company = $company->company;
        }else $company = $userData->compania;

        if(!$company) return ['estatus' => false,'msj' => 'Compañia no recibida'];
        return ['estatus' => true, 'data' => $company];;
    }
}