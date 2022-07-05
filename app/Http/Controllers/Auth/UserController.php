<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\RefreshToken;
use Laravel\Passport\Token;

class UserController extends Controller
{
    public function register(Request $request) {

        try {
            $data = $request->validate([
                'name' => 'required|max:100',
                'email' => 'required|email',
                'password' => 'required|confirmed'
            ]);

            $data['password'] = bcrypt($request->password);

            $user = User::create($data);

            $token = $user->createToken('API Token')->accessToken;

            return response([
                'status' => "success",
                'message' => "Acccount has been successfully created",
                'data'  => [
                    'user' => $user,
                    'token' => $token
                ]
                ], 200);

        } catch (\Throwable $th) {
            return response([
                'status' => "error",
                'message' => $th->getMessage(),
                'data'  => ""
                ], 400);
        }
    }

    public function login(Request $request){

        try {
            $data = $request->validate([
                'email' => 'required|email',
                'password' => 'required',
            ]);

            $user = User::where('email', $request->email)->first();

            if($user){
                if (Hash::check($request->pasword, $request->email)){
                    $token = $user()->createToken("API Token")->accessToken();

                    return response([
                        'status' => "success",
                        'message' => "Login successfully",
                        'data'  => [
                            'token' => $token,
                        ]
                        ], 200);
                }else {
                    return response([
                        'status' => "error",
                        'message' => "Incorrect email or password, please try again!",
                        'data'  => ""
                        ], 400);
                }
            } else {
                return response([
                    'status' => "error",
                    'message' => "User has not registered!",
                    'data'  => ""
                    ], 400);
            }
        } catch (\Throwable $th) {
            return response([
                'status' => "error",
                'message' => $th->getMessage(),
                'data'  => ""
                ], 400);
        }
    }

    public function logout(User $user){
        try {
            $tokens =  $user->tokens->pluck('id');
            Token::whereIn('id', $tokens)
                ->update(['revoked'=> true]);

            RefreshToken::whereIn('access_token_id', $tokens)->update(['revoked' => true]);
        } catch (\Throwable $th) {
            return response([
                'status' => "error",
                'message' => $th->getMessage(),
                'data'  => ""
                ], 400);
        }
    }
}
