<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Http\Models\User;
use App\Http\Models\Book;
use App\Http\Models\Borrow;
use Carbon\Carbon;
use JWTAuth;
use DB;


class BorrowsController extends Controller
{
    protected $mail_controller;
    public function __construct(MailController $mail_controller)
    {
        //$this->middleware('jwt.verify');
        //$this->mail_controller = new MailController();
        $this->mail_controller = $mail_controller;

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
        $borrower_id = $currentUser->id;
        $validator = Validator::make(
            [
                'book_id' =>  $request->book_id,
                'borrowed_at' => $request->borrowed_at,
                'take_back_at' => $request->take_back_at,
            ],
            [
                'book_id' => 'required|integer',
                'borrowed_at' => 'required|date_format:"Y.m.j H:i:s"',
                'take_back_at' => 'required|date_format:"Y.m.j H:i:s"',
            ]
            );
        if ($validator->fails())
        {
            $error = $validator->errors()->first();
            return json_encode($error);
        }

        $user = User::where('id', $borrower_id)->first();

        if (!$user)
        {
            return ["message" => "borrower dosent exist!"];
        }

        $book = Book::where('id', $request->book_id)->first();

        if (!$book)
        {
            return ["message" => "book dosent exist!"];
        }
        $book_owner = User::where('id', $book->owner_id)->first();

        $borrow = new Borrow;
        $borrow->borrower_id = $borrower_id;
        $borrow->borrowed_at = $request->borrowed_at;
        $borrow->take_back_at = $request->take_back_at;
        $borrow->taken_back = false;
        $borrow->book_id = $request->book_id;   
        $borrow->mode = 'pending';
        $borrow->save();
        $this->mail_controller->send_borrow_request_email($book_owner->email, $book_owner->name);
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
        $borrower_id = $currentUser->id;
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

        $user = User::where('id', $borrower_id)->first();

        if (!$user)
        {
            return ["message" => "borrower dosent exist!"];
        }

        $book = Book::where('id', $request->book_id)->first();

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
            $borrow->mode = $request->mode;  
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

    public function accept($id)
    {
        $borrow = Borrow::find($id);
        if (!$borrow)
        {
            return ["message" => "no borrow"];

        }
        $user = JWTAuth::parseToken()->authenticate();
        $book = Book::where('id', $borrow->book_id)->first();
        $user_2 = User::where('id', $book->owner_id)->first();
        if( $user->id != $book->owner_id){
            return response()->json(["message" => "This borrow is not yours!!!"], 403);
        }
        $borrow->mode = 'accepted';
        $borrow->save();
        $this->mail_controller->send_borrow_request_accepted_email($user_2->email, $user_2->name);
        return json_encode($borrow);

    }

    public function reject($id)
    {
        $borrow = Borrow::find($id);
        if (!$borrow)
        {
            return ["message" => "no borrow"];

        }
        $user = JWTAuth::parseToken()->authenticate();
        $book = Book::where('id', $borrow->book_id)->first();
        $user_2 = User::where('id', $book->owner_id)->first();
        if( $user->id != $book->owner_id){
            return response()->json(["message" => "This borrow is not yours!!!"], 403);
        }
        $borrow->mode = 'reject';
        $borrow->save();
        $this->mail_controller->send_borrow_request_rejected_email($user_2->email, $user_2->name);
        return json_encode($borrow);

    }

    public function take_back($id)
    {
        $borrow = Borrow::find($id);
        if (!$borrow)
        {
            return ["message" => "no borrow"];

        }
        $mytime = Carbon::now();

        if($mytime >= $borrow->take_back_at){
            return "take back it";
        }
        else{
            dd($mytime->toDateTimeString(), $borrow->take_back_at);
        }
    }

}
