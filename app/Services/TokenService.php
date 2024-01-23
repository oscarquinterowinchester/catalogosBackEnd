<?php
namespace App\Services; 
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

//Manejador de tokens para acceso de usuarios y proporciona algunos metodos para obtener ciertos datos desde el token
class TokenService {

    //Genera una cadena de texto de 128 caracteres aleatorios 
    public function tokenGenerator(){
        $arregloCaracteres = [
            'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z',
            'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z',
            '0', '1', '2', '3', '4', '5', '6', '7', '8', '9'
        ];
        $token ="";
        $maximo = count($arregloCaracteres) - 1;
        for ($i=0; $i < 128; $i++) { 
            $index = random_int(0, $maximo);
            $token = $token.$arregloCaracteres[$index];
        }

        return $token;
    }

    //Recibe un entero > 1 correspondiente al id de usuario, la asocia un token y devuelve dicho token como respuesta
    public function getToken($usuarioID){
        DB::delete('DELETE FROM tokens_tbl WHERE user_fk = ? ',[$usuarioID]);
        $token = $this->tokenGenerator();
        $repetido = true;
        $intentos = 0;
        $fecha = Carbon::now()->toDateTimeString();

        while ($repetido && $intentos < 50) {
            try {
                DB::insert('insert into tokens_tbl(token,user_fk,created) values(?, ?, ?)', [$token,$usuarioID,$fecha]);
                $repetido = false;
            } catch (\Exception $th) {
                $token = $this->tokenGenerator();
            }
            $intentos++;
        }
        return $token;
    }

    //Recibe un token, si hay usuario asociados a este devuelve su perfil, retorna nulo en cualquier otro caso.
    public function getUserFromToken($token) {
        if(!$token || !is_string($token) || strlen($token) != 128) return null;
        return DB::selectOne('SELECT 
        us.correo,
        CASE WHEN email_verified_at IS NULL THEN 0 ELSE 1 END AS verificado,
        ut.range,
        ut.type,
        ust.estatus,
        FROM tokens_tbl tk
        INNER JOIN usuarios_tbl us ON us.id = tk.user_fk 
        INNER JOIN user_types_tbl ut ON ut.id = us.type
        INNER JOIN user_status_tbl ust ON ust.id = us.estatus_fk
        LEFT JOIN companys_tbl comp ON comp.id = us.company_fk
        WHERE tk.token = ?',[$token]);
    }

}