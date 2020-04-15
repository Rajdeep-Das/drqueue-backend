<?php

namespace App\Http\Controllers;

use App\Models\PhoneLogin;
use Illuminate\Http\Request;
use App\Models\User;

//import auth facades
use Illuminate\Support\Facades\Auth;

use Firebase\JWT\JWT;

class AuthController extends Controller
{
    /**
     * Store a new user.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    private $request;

    /**
     * Create a new controller instance.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    public function __construct(Request $request) {
        $this->request = $request;
    }

    protected function jwt(User $user) {
        $payload = [
            'iss' => "drqueue-backend", // Issuer of the token
            'sub' => $user->id, // Subject of the token
            'iat' => time(), // Time when JWT was issued.
            'exp' => time()+ 60*60 // Expiration time
        ];

        // As you can see we are passing `JWT_SECRET` as the second parameter that will
        // be used to decode the token in the future.
        return JWT::encode($payload, env('JWT_SECRET'));
    }

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

            // TODO :: Send OTP For Verification
            $phone = $request->input('phone');
            $phoneLogin = PhoneLogin::createForPhoneLogin($phone);
            $otp = $phoneLogin->otp;


            //return successful response
            return response()->json(['user' => $user, 'message' => 'OTP Send To '.$phone,'otp'=>$otp,'success' => true,], 201);

        } catch (\Exception $e) {
            //return error message
            return response()->json(['message' => 'User Registration Failed!','error' => $e->getMessage()], 409);
        }

    }

    /**
     * Get a JWT via given credentials.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function loginRequestPhone(Request $request)
    {
        //validate incoming request
        $this->validate($request, [
            'phone' => 'required|string'
        ]);

        $phone = $request->input('phone');
        $phoneLogin = PhoneLogin::createForPhoneLogin($phone);
        $otp = $phoneLogin->otp;

        // TODO:: Send OTP To The User Phone


        return response()->json(['success' => true,'message'=>'OTP Send To '.$phone,'otp'=>$otp], 200);


    }

    public function verifyFirstTimeUserPhone(Request $request) {

        //validate incoming request
        $this->validate($request, [
            'phone' => 'required|string',
            'otp' => 'required|string'
        ]);

        $otp = $request->input('otp');
        $phone = $request->input('phone');
        $phoneLogin = PhoneLogin::validFromOtp($otp,$phone);

        if($phoneLogin){
            $user = $phoneLogin->user;
            $user->isPhoneVerified = true;
            $user->save();

            return response()->json([
                'success'=>true,
                'message' => 'Phone Verification Successful',
                'token' => $this->jwt($phoneLogin->user),
                'token_type' => 'Bearer'
            ], 200);
        }else{
            return response()->json([
                'success'=>false,
                'message' => 'Phone Verification Failed',
                'error' => 'OTP is wrong.'
            ], 400);
        }

    }




    public function authenticatePhone(Request $request)
    {
        //validate incoming request
        $this->validate($request, [
            'phone' => 'required|string',
            'otp' => 'required|string'
        ]);

        $otp = $request->input('otp');
        $phone = $request->input('phone');
        $phoneLogin = PhoneLogin::validFromOtp($otp,$phone);

        if($phoneLogin){
            return response()->json([
                'token' => $this->jwt($phoneLogin->user),
                'token_type' => 'Bearer'
            ], 200);
        }else{
            return response()->json([
            'error' => 'OTP is wrong.'
        ], 400);
        }
    }


}

