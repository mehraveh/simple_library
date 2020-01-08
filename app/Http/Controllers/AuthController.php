<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\User;
use DB;


class AuthController extends Controller
{
    public $loginAfterSignUp = true;

       public function __construct()
    {
        //$this->middleware('auth1', ['except' => ['login']]);
    }

    public function register(Request $request)
    {
      $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => bcrypt($request->password),
      ]);

      $token = auth()->login($user);

      return $this->respondWithToken($token);
    }


    public function login(Request $request)
    {
      $credentials = $request->only(['email', 'password']);

      if (!$token = auth()->attempt($credentials)) {
        return response()->json(['error' => 'Unauthorized'], 401);
      }

      return $this->respondWithToken($token);
    }


    public function getAuthUser(Request $request)
    {
        return response()->json(auth()->user());
    }


    public function logout()
    {
        auth()->logout();
        return response()->json(['message'=>'Successfully logged out']);
    }

    public function show_books($owner_id)
    {
		$books = DB::table('books')->where('owner_id', $owner_id)->get();
        return json_encode($books);
		

    }
    public function show_borrows($borrower_id)
    {
		$borrows = DB::table('borrows')->where('borrower_id', $borrower_id)->get();
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

}