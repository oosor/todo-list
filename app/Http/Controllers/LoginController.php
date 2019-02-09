<?php
/**
 * Created by IntelliJ IDEA.
 * User: jarvis
 * Date: 08.02.19
 * Time: 19:29
 */

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function authenticate(Request $request)
    {
        if (Auth::attempt(['name' => $request->input('name'), 'password' => $request->input('password')])) {
            // Аутентификация успешна...
            return redirect()->intended('home');
        }
        return redirect()->intended('login');
    }
}