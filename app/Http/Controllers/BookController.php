<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Books;
use DB;
class BookController extends Controller
{
	public function __construct()
	{
		//$this->middleware('auth1');

	}
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
		$books = Books::all();
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
        $book = new Books;
		$book->name = $request->name;
		$book->author = $request->author;
		$book->publisher = $request->publisher;
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
        return $book;
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
		// Make sure you've got the Page model
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
        $book->delete();
        return ["message" => "dropped successfully"];
    }
}
