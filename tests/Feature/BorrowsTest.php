<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use JWTAuth;
use App\Http\Models\User;
class BorrowsTest extends TestCase
{

      /** @test */
    public function borrows_test_fail()
    {
        $response = $this->get('/api/borrow');

        $response->assertStatus(401);
    }

      /** @test */
    public function borrows_test_super_user_fail()
    {
        $user = User::find(12); // sample user
        $token = JWTAuth::fromUser($user);
        $response =  $this->get('api/borrow/', ['HTTP_Authorization' => 'Bearer ' . $token]);

        //$response = $this->get('/api/user/');
        $response->assertStatus(403);
    }
      /** @test */
    public function borrows_test_ok()
    {
        $user = User::find(11); // sample user
        $token = JWTAuth::fromUser($user);
        $response =  $this->get('api/borrow/', ['HTTP_Authorization' => 'Bearer ' . $token]);

        //$response = $this->get('/api/user/');
        $response->assertStatus(200);
    }

     /** @test */
        public function borrow_test_fail()
    {
        $response = $this->get('/api/borrow/1');

        $response->assertStatus(401);
    }

      /** @test */
    public function borrow_test_super_user_fail()
    {
        $user = User::find(12); // sample user
        $token = JWTAuth::fromUser($user);
        $response =  $this->get('api/borrow/1', ['HTTP_Authorization' => 'Bearer ' . $token]);

        //$response = $this->get('/api/user/');
        $response->assertStatus(403);
    }

          /** @test */
    public function borrow_test_ok()
    {
        $user = User::find(11); // sample user
        $token = JWTAuth::fromUser($user);
        $response =  $this->get('api/borrow/1', ['HTTP_Authorization' => 'Bearer ' . $token]);

        //$response = $this->get('/api/user/');
        $response->assertStatus(200);
    }
}
