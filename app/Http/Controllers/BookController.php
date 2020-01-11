<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Books;
use DB;
class BookController extends Controller
{
	public function __construct()
	{
		$this->middleware('jwt.verify');

	}
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
		$books = Books::all();
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
		$user = DB::table('users')->where('id', $request->owner_id)->first();

		if (!$user)
		{
			return ["message" => "book owner dosent exist!"];
		}

        $book = new Books;
		$book->name = $request->name;
		$book->author = $request->author;
		$book->publisher = $request->publisher;
		$book->code = $request->code;
		$book->owner_id = $request->owner_id;	
		$book->save();
		return ["name" => $book->name, "author" => $book->author, "publisher" => $book->publisher, "owner_id" => $book->owner_id];
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $book = Books::find($id);
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
        $book = Books::find($id);
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
		$user = DB::table('users')->where('id', $request->owner_id)->first();

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
        $book = Books::find($id);
        if (!$book){
        	return ["message" => "book dosent exist!"];
        }
        $book->delete();
        return ["message" => "dropped successfully"];
    }
}
