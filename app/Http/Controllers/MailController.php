<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Mail;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class MailController extends Controller {


   public function send_email($mail_address) {

		Mail::raw('some one wants to borrow your book', function ($message)  use ($mail_address){
		   $message->to($mail_address);
		   $message->subject("borrow Request");
		   $message->from("mehraveh.ahmadi1996@gmail.com");});
   }

}