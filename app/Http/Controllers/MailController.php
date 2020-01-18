<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Mail;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class MailController extends Controller {


   public function send_verification_email($mail_address, $name, $code) {

		Mail::raw("Hi " . $name . " this is your verification code: " . $code, function ($message)  use ($mail_address){
		   $message->to($mail_address);
		   $message->subject("verification mail");
		   $message->from("mehraveh.ahmadi1996@gmail.com");});
   }

   public function send_borrow_request_email($mail_address, $name) {

		Mail::raw($name . " wants to borrow your book", function ($message)  use ($mail_address){
		   $message->to($mail_address);
		   $message->subject("borrow Request");
		   $message->from("mehraveh.ahmadi1996@gmail.com");});
   }

   public function send_borrow_request_accepted_email($mail_address, $name) {

		Mail::raw($name . " accepetd your borrow request", function ($message)  use ($mail_address){
		   $message->to($mail_address);
		   $message->subject("borrow accepetd");
		   $message->from("mehraveh.ahmadi1996@gmail.com");});
   }

   public function send_borrow_request_rejected_email($mail_address, $name) {

		Mail::raw($name . " rejected your borrow request", function ($message)  use ($mail_address){
		   $message->to($mail_address);
		   $message->subject("borrow rejected");
		   $message->from("mehraveh.ahmadi1996@gmail.com");});
   }

}