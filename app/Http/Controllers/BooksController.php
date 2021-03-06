<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Models\User;
use App\Http\Models\Book;
use App\Http\Models\Borrow;
use JWTAuth;
use DB;
class BooksController extends Controller
{
	public function __construct()
	{
		//$this->middleware('jwt.verify');
		//$this->middleware('jwt_super_user.verify', ['except' => ['store', 'show', 'index']]);

	}
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
		$books = Book::all();
		if (!$books)
		{
			return ["message" => "no book"];

		}
		return $books;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
    	$currentUser = JWTAuth::parseToken()->authenticate();
        $owner_id = $currentUser->id;
		$validator = Validator::make(
		    [
		        'code' =>  $request->code,
		    ],
		    [
		        'code' => 'required|min:8|max:8|unique:books',
		    ]
			);
		if ($validator->fails())
		{
			$error = $validator->errors()->first();
			return ["message" => $error];
		}
		$user = User::where('id', $owner_id)->first();

		if (!$user)
		{
			return ["message" => "book owner dosent exist!"];
		}

        $book = new Book;
		$book->name = $request->name;
		$book->author = $request->author;
		$book->publisher = $request->publisher;
		$book->code = $request->code;
		$book->owner_id = $owner_id;	
		$book->save();
		return json_encode($book);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $book = Book::find($id);
        if (!$book){
        	return ["message" => "book dosent exist!"];
        }
        return json_encode($book);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $book = Book::find($id);
		$validator = Validator::make(
		    [
		        'owner_id' => $request->owner_id,
		        'code' =>  $request->code,
		    ],
		    [
		        'owner_id' => 'required|integer',
		        'code' => 'required|min:8|max:8|unique:books',
		    ]
			);
		if ($validator->fails())
		{
			$error = $validator->errors()->first();
			return ["message" => $error];
		}
		$user = User::where('id', $request->owner_id)->first();

		if (!$user)
		{
			return ["message" => "book owner dosent exist!"];
		}
		if($book) {
		    $book->name = $request->name;
		    $book->author = $request->author;
		    $book->publisher = $request->publisher;
			$book->owner_id = $request->owner_id;	
		    $book->save();
		    return ["name" => $book->name, "author" => $book->author, "publisher" => $book->publisher, "owner_id" => $book->owner_id];
		    }  
		else
		{
			return ["message" => "book not found"];
		}


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $book = Book::find($id);
        if (!$book){
        	return ["message" => "book dosent exist!"];
        }
        $borrow = Borrow::where('book_id', $id)->get();
        $borrow->delete();
        $book->delete();
        return ["message" => "dropped successfully"];
    }
}
