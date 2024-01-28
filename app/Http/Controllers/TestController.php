<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TestController extends Controller
{
    public function email_template(Request $r) {
        return view('email_template.email_account_confirmation_template',['url' => 'http://localhost:8000/templates/email'])->render();
    }
}
