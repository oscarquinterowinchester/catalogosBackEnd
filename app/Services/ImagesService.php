<?php
namespace App\Services; 
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Http\Request;

//Gestion de carga y recuperación de imagenes
class TokenService {

    //revisa si una instancia de Request contiene al menos una imagen y la almacena en el servidor, devuelve true si se almacenó una imagen
    public function saveImageFromRequest(Request $r, $path, $imageName) {
        if(!$path || !$imageName || !is_string($path) || !is_string($imageName) || strlen($path) === 0 || strlen($imageName) < 1) return null;
        $files = $r->allFiles();
        $max = 1;
        $iterator = 0;
        $path = null;
        if($files!=null){
            foreach ($files AS $keyFile=>$rowFile){ 
                if($iterator >= $max) continue;
                $file = $r->file($keyFile);

                //Verificamos que el archivo tenga las extenciones aceptadas
                if(!in_array($file->getClientOriginalExtension(), ['png','gif','jpeg', 'jpg','jfif'])) continue;

                //Para el nombre de la imagen eliminamos caracteres exrtraños y eliminamos espacios en blanco
                $name_clean = preg_replace_callback('/[^a-zA-Z0-9\s]|(\s+)/', function($match) {
                    return $match[1] ? '_' : '';
                }, $imageName);
                $nameFile = $name_clean.'.'.$file->getClientOriginalExtension();
                $isUpload = $file->move($path,$nameFile);
                if($isUpload){
                    return $path.'/'.$nameFile;
                }
            }
        }else return false;
    }
}