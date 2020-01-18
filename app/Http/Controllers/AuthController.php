<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Http\Models\User;
use App\Http\Models\Book;
use App\Http\Models\Borrow;
use App\Helpers\Utils;
use JWTAuth;
use Auth;
use DB;


class AuthController extends Controller
{
    public $loginAfterSignUp = true;

       public function __construct(MailController $mail_controller, Utils $utils)
    {
        $this->mail_controller = $mail_controller;
        $this->utils = $utils;
        //$this->middleware('jwt.verify', ['except' => ['login', 'register']]);
       // $this->middleware('auth1', ['except' => ['register']]);

    }



    public function register(Request $request)
    {
        dd(Hash::make($request->get('password')));
	    $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if($validator->fails()){
                return response()->json($validator->errors()->toJson(), 400);
        }
        $code = $this->utils->get_code(8);
        $user = User::create([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'password' => Hash::make($request->get('password')),
            'super_user' => $request->get('super_user'),
        ]);
        $userp = User::where("id", $user->id)->first();
        $userp->code = $code;
        $userp->save();
        $this->mail_controller->send_verification_email($user->email, $user->name, $code);
        return "we have send verification email to you!";
    }


    public function verify(Request $request, $id)
    {
        $user = User::where('id', $id)->first();
         if ($user->code != '' &&  $user->code == $request->get('code'))
         {
            $token = JWTAuth::fromUser($user);
            $user->is_verified = true;
            $user->code = '';
            $user->save();
            return response()->json(compact('user','token'),201);

         }
         return response()->json(['error' => 'invalid_code'], 500);

    }


    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        $user = User::where('email', $credentials["email"])->first();
        if(!$user->is_verified){
            return response()->json(['error' => 'user_not_verfied'], 500);
        }
        try {
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'invalid_credentials'], 400);
            }
        } catch (Exception $e) {
            return response()->json(['error' => 'could_not_create_token'], 500);
        }
        return response()->json(compact('token'));     
     }


    public function getAuthUser()
    {
        $user = JWTAuth::parseToken()->authenticate();
        return response()->json(compact('user'));
    }


    public function logout()
    {
        auth()->logout();
        return response()->json(['message'=>'Successfully logged out']);
    }


    public function edit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'password' => 'required|string|min:6',
        ]);

        if($validator->fails()){
                return response()->json($validator->errors()->toJson(), 400);
        }
        $user = JWTAuth::parseToken()->authenticate();
        //$user = User::find($id);
        $user->name = $request->get('name');
        $user->password = $request->get('password');
        $user->save();
        //$token = JWTAuth::fromUser($user);
        return response()->json(compact('user'),201);
    }


    public function show_my_books()
    {
    	$currentUser = JWTAuth::parseToken()->authenticate();
        $owner_id = $currentUser->id;
		$books = Book::where('owner_id', $owner_id)->get();
        return json_encode($books);
		

    }

    public function show_my_borrows()
    {
    	$currentUser = JWTAuth::parseToken()->authenticate();
        $borrower_id = $currentUser->id;
		$borrows = Borrow::where('borrower_id', $borrower_id)->get();
        return json_encode($borrows);
		

    }


    protected function respondWithToken($token)
    {
      return response()->json([
        'access_token' => $token,
        'token_type' => 'bearer',
        'expires_in' => auth()->factory()->getTTL() * 60
      ]);
    }
  
   public function users()
   {

        $users = User::all();
        return json_encode($users);
   }
}