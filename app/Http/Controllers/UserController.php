<?php

namespace App\Http\Controllers;

use App\Models\users;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function CheckPhone(Request $request){
        $user = users::where('phone', $request->input('phone'))->first();
        if ($user == null){
            return [ 'status' => 'user does not exist' ];
        } else {
            if ($user->password == null){
                return [ 'status' => 'new user' ];
            } else {
                return [ 'status' => 'user exist' ];
            }
        }
    }
    public function SignIn(Request $request){
        session_start();
        $user = users::where('phone', $request->input('phone'))->get();
        if (count($user) == 1){
            $user = $user[0];
            $logged = false;
            if ($user->password == null){
                $user->password = password_hash($request->input('password'), PASSWORD_BCRYPT);
                $user->save();
                $logged = true;
            } else if (password_verify($request->input('password'), $user->password)){
                $logged = true;
            }

            if ($logged){
                $_SESSION['user'] = $user->phone;
                return redirect()->route('main_page');
            } else {
                $_SESSION['message'] = '{ "status": "danger", "message": "Неверный пароль" }';
                return redirect()->route('signin');
            }
        } else {
            $_SESSION['message'] = '{ "status": "danger", "message": "Пользователя не существует" }';
            return redirect()->route('signin');
        }
    }
    public function SignOut(Request $request){
        if (!isset($_SESSION)) session_start();
        if (isset($_SESSION['user'])) unset($_SESSION['user']);
        return redirect()->route('signin');
    }
    public function ResetPassword(Request $request, $id){
        if (isAdmin()){
            $user = \App\Models\users::where('id', $id)->first();
            if ($user != null){
                $user->password = password_hash('12345678', PASSWORD_BCRYPT);
                $user->save();
                return [ 'status' => 'success' ];
            } else {
                return [ 'status' => 'error', 'message' => 'user does not exist' ];
            }
        } else {
            return [ 'status' => 'error', 'message' => 'not enough permissions' ];
        }
    }
    public function Delete(Request $request, $id){
        if (isAdmin()){
            $user = \App\Models\users::where('id', $id)->first();
            if ($user != null){
                $user->delete();
                return [ 'status' => 'success' ];
            } else {
                return [ 'status' => 'error', 'message' => 'user does not exist' ];
            }
        } else {
            return [ 'status' => 'error', 'message' => 'not enough permissions' ];
        }
    }
}
