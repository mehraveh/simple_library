<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use JWTAuth;
use App\Http\Models\User;

class BooksTest extends TestCase
{


      /** @test */
    public function books_test_ok()
    {
        $user = User::find(11); // sample user
        $token = JWTAuth::fromUser($user);
        $response =  $this->get('api/book/', ['HTTP_Authorization' => 'Bearer ' . $token]);

        //$response = $this->get('/api/user/');
        $response->assertStatus(200);
    }

     
        /** @test */
    public function book_test_ok()
    {
        $user = User::find(11); // sample user
        $token = JWTAuth::fromUser($user);
        $response =  $this->get('api/book/1', ['HTTP_Authorization' => 'Bearer ' . $token]);

        //$response = $this->get('/api/user/');
        $response->assertStatus(200);
    }
      /** @test */
    public function books_test_fail()
    {
        $response = $this->get('/api/book');

        $response->assertStatus(401);
    }

     /** @test */
        public function book_test_fail()
    {
        $response = $this->get('/api/book/1');

        $response->assertStatus(401);
    }
}
