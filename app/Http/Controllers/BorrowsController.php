<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Borrow;
use JWTAuth;
use DB;


class BorrowsController extends Controller
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
        $borrows = Borrow::all();
        if(!$borrows)
        {
            return ["message" => "no borrow"];
        }
        return $borrows;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
        $borrower_id = $currentUser['ID'];
        $validator = Validator::make(
            [
                'book_id' =>  $request->book_id,
                'borrowed_at' => $request->borrowed_at,
                'take_back_at' => $request->take_back_at,
                'taken_back' => $request->taken_back
            ],
            [
                'book_id' => 'required|integer',
                'borrowed_at' => 'required|date_format:"Y.m.j H:i:s"',
                'take_back_at' => 'required|date_format:"Y.m.j H:i:s"',
                'taken_back' => 'boolean'
            ]
            );
        if ($validator->fails())
        {
            $error = $validator->errors()->first();
            return json_encode($error);
        }

        $user = DB::table('users')->where('id', $borrower_id)->first();

        if (!$user)
        {
            return ["message" => "borrower dosent exist!"];
        }

        $book = DB::table('books')->where('id', $request->book_id)->first();

        if (!$book)
        {
            return ["message" => "book dosent exist!"];
        }

        $borrow = new Borrow;
        $borrow->borrower_id = $borrower_id;
        $borrow->borrowed_at = $request->borrowed_at;
        $borrow->take_back_at = $request->take_back_at;
        $borrow->taken_back = $request->taken_back;
        $borrow->book_id = $request->book_id;   
        $borrow->save();
        return json_encode($borrow);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $borrow = Borrow::find($id);
        if (!$borrow)
        {
            return ["message" => "no borrow"];

        }
        return json_encode($borrow);
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
        $currentUser = JWTAuth::parseToken()->authenticate();
        $borrower_id = $currentUser['ID'];
        $borrow = Borrow::find($id);
        $validator = Validator::make(
            [
                'book_id' =>  $request->book_id,
                'borrowed_at' => $request->borrowed_at,
                'take_back_at' => $request->take_back_at,
                'taken_back' => $request->taken_back
            ],
            [
                'book_id' => 'required|integer',
                'borrowed_at' => 'required|date_format:"Y.m.j H:i:s"',
                'take_back_at' => 'required|date_format:"Y.m.j H:i:s"',
                'taken_back' => 'boolean'
            ]
            );
        if ($validator->fails())
        {
            $error = $validator->errors()->first();
            return json_encode($error);
        }

        $user = DB::table('users')->where('id', $borrower_id)->first();

        if (!$user)
        {
            return ["message" => "borrower dosent exist!"];
        }

        $book = DB::table('books')->where('id', $request->book_id)->first();

        if (!$book)
        {
            return ["message" => "book dosent exist!"];
        }

        if($borrow)
        {
            $borrow->borrower_id = $borrower_id;
            $borrow->borrowed_at = $request->borrowed_at;
            $borrow->take_back_at = $request->take_back_at;
            $borrow->taken_back = $request->taken_back;
            $borrow->book_id = $request->book_id;   
            $borrow->save();
            return json_encode($borrow);

        }

        else
        {
            return ["message" => "borrow not found"];
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
        $borrow = Borrow::find($id);
        if (!$borrow)
        {
            return ["message" => "no borrow"];

        }
        $borrow->delete();
        return ["message" => "dropped successfully"];
    }



}
