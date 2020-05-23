<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\user;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Laravel\Lumen\Routing\Controller as BaseController;

class AuthController extends BaseController
{
    /**
     * The request instance.
     *
     * @var \Illuminate\Http\Request
     */
    private $request;
    /**
     * Create a new controller instance.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }
    /**
     * Create a new token.
     *
     * @param  \App\User   $user
     * @return string
     */
    protected function jwt(User $user)
    {
        $payload = [
            'iss' => "lumen-jwt", // Issuer of the token
            'sub' => $user->id, // Subject of the token
            'iat' => time(), // Time when JWT was issued.
            'exp' => time() + 3600*3600 // Expiration time
        ];
        
        return JWTAuth::encode($payload, env('JWT_SECRET'));
    }
    /**
     * Authenticate a user and return the token if the provided credentials are correct.
     *
     * @param  \App\User   $user
     * @return mixed
     */
    public function authenticate(Request $request)
    {
        $this->validate($this->request, [
            'email'     => 'required|email',
            'password'  => 'required'
        ]);
        /*$user = User::where('email', $this->request->input('email'))->first();
        if (!$user) {
            return response()->json([
                'error' => 'Email does not exist.'
            ], 400);
        }*/
        $credentials=request(['email','password']);
        if ($token = JWTAuth::attempt($credentials)) {
            return response()->json(['success'=> true,
                'access_token' => $token
            ], 200);
        }
        return response()->json([
            'error' => 'Email or password is wrong.'
        ], 400);
    }

    /**
     * Registration method
     *
     * @param Request $request registration request
     *
     * @return array
     * @throws \Illuminate\Validation\ValidationException
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'password' => 'required',
            'email' => 'required|email|unique:users'
        ]);

        if ($validator->fails()) {
            return array(
                'success' => false,
                'message' => $validator->errors()->all()
            );
        }

        $user =  new User;

        $user->email= $request->email;
        $user->password= app('hash')->make($request->password);
        $user->save();

        unset($user->password);

        return Response::json(['success' => true, 'user' => $user],200);
    }
}