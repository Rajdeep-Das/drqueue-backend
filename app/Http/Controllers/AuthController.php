<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

//import auth facades
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Store a new user.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function register(Request $request)
    {
        //validate incoming request
        $this->validate($request, [
            'name' => 'required|string',
            'phone' => 'required|digits:10|unique:users',
            //'password' => 'required|confirmed',   // When email auth will enable
        ]);

        try {

            $user = new User;
            $user->name = $request->input('name');
            $user->phone = $request->input('phone');
            //$plainPassword = $request->input('password');
            //$user->password = app('hash')->make($plainPassword);

            $user->save();

            //return successful response
            return response()->json(['user' => $user, 'message' => 'CREATED'], 201);

        } catch (\Exception $e) {
            //return error message
            return response()->json(['message' => 'User Registration Failed!','error' => $e->getMessage()], 409);
        }

    }

    /**
     * Get a JWT via given credentials.
     *
     * @param Request $request
     * @return Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(Request $request)
    {
        //validate incoming request
        $this->validate($request, [
            'phone' => 'required|string',
            'password' => 'required|string',
        ]);

        $credentials = $request->only(['phone']);

        if (! $token = Auth::attempt($credentials)) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }


}

