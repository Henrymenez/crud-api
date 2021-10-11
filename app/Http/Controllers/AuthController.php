<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    //
    public function register(Request $request){

        $value = $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|unique:users,email',
            'password' => 'required|string|confirmed'

        ]);
        $user = User::create([
            'name' => $value['name'],
            'email' => $value['email'],
            'password' => bcrypt($value['password'])
        ]);

        $token = $user->createToken('meneztoken')->plainTextToken;

            $response = [
                'user' => $user,
                'token' => $token,
            ];

            return response($response, 201);
    }

    public function login(Request $request){

        $value = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string'

        ]);
       //check email
       $user =  User::where('email', $value['email'])->first();
       //check password

       if(!$user || !Hash::check($value['password'],$user->password)){
           return response([
               'message' => 'Bad Creds'
           ], 401);
       }
        $token = $user->createToken('meneztoken')->plainTextToken;

            $response = [
                'user' => $user,
                'token' => $token,
            ];

            return response($response, 201);
    }

    public function logout(Request $request){
      //  auth()->user()->tokens()->delete();

        return [
            'message' => 'Logged Out',
           
        ];
    }

}
