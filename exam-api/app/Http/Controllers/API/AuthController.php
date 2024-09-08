<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Validator;
use App\Models\User;


class AuthController extends Controller
{
    //
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {

            $authUser = Auth::user();
            $token = $authUser->createToken($authUser->name, [$authUser->type])->plainTextToken;

            return response()->json(['data' => ['token' => $token], 'status' => 200]);
        } else {
            return response()->json(['message' => 'Incorrect credentials', 'status' => 400]);
        }


    }


    public function register(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|max:100',
            'email'    => 'required|email:rfc,dns|unique:users',
            'password' => 'required|min:6',
        ]);


        $name     = $request->input('name');
        $password = $request->input('password');
        $email    = $request->input('email');
        $type     = $request->input('type');


        if(!$type){
            $type = "user";
        }

        try {
            $user = new User();

            $user->fill([
                'name'     => $name,
                'password' => $password,
                'email'    => $email,
                'type'     => $type,
            ]);

            $user->save();

            $token = $user->createToken($user->name, [$type])->plainTextToken;

            $response = [
                'user' => $user,
                'token' => $token,
            ];

            return response()->json(['data' => $response, 'status' => 200]);

        } catch (Throwable $e) {
            return $e;

        }

        return response()->json(['data' => $data], 200);
    }

    public function logout()
    {
        auth()->user()->tokens()->delete();

        return response()->json(['message' => 'Logged out', 'status' => 200]);
    }
}
