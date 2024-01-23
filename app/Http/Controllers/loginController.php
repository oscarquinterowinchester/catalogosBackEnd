<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class loginController extends Controller
{
    public function login(Request $r) {
    
        $user = $r->input('user');

        if(is_string($user)){
            
            // return ['estatus' => false,'msj' => 'La cagaste'];

            return ['estatus' => true,'msj' => '', 'data' => 'Hola mundo' ];
        }
    }
    
}
