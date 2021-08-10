<?php

namespace App\Http\Controllers;
Use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    /**
     * Registro de usuario
     */
    public function signUp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|string|email',
            'budget' => 'required|numeric'
        ]);
        if ($validator->fails()){
            return response(['status'=>'error','data'=>$validator->messages()], 422);
        }
        $user = User::where('email', $request->email)->first();
        
        # Usuario ya existe entonces lo actualiza
        if ($user) {
            $user->update($request->all());
        }else{
        # Usuario nuevo
            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'budget' => (float)$request->budget,
                'password' => bcrypt("0000")
            ]);    
        }
        
        return response()->json([
            'status' => 'success',
            'data' => 'User success'
        ], 201);
    }

    /**
     * Inicio de sesión y creación de token
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255'
        ]);
        if ($validator->fails()){
            return response(['status'=>'error','data'=>$validator->errors()->all()], 422);
        }
        $user = User::where('email', $request->email)->first();
        if ($user) {
            $credentials = ['email' => $request->email, 'password' => "0000"];
           
            if (!Auth::attempt($credentials))
            return response()->json([
                'status'=>'error',
                'data' => 'Unauthorized'
            ], 401);
            $user = $request->user();
            $tokenResult = $user->createToken('Personal Access Token');

            $token = $tokenResult->token;
            if ($request->remember_me)
                $token->expires_at = Carbon::now()->addWeeks(1);
            $token->save();

            return response()->json([
                'status'=>'success',
                'data' => [
                    'access_token' => $tokenResult->accessToken,
                    'token_type' => 'Bearer',
                    'expires_at' => Carbon::parse($token->expires_at)->toDateTimeString()
                ]
            ]);
        } else {
            $response = ['status'=>'error',"data" =>'User does not exist'];
            return response($response, 422);
        }
    }

    /**
     * Cierre de sesión (anular el token)
     */
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();

        return response()->json([
            'status'=>'success',
            'data' => 'Successfully logged out'
        ]);
    }

    /**
     * Obtener el objeto User como json
     */
    public function user(Request $request)
    {
        return response()->json(['status'=>'success','data'=>$request->user()]);
    }
}
