<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use JWTAuth;
use App\Http\Models\User;

class UsersTest extends TestCase
{


    /** @test */
        public function user_test()
    {
        $user = User::find(11);
        $token = JWTAuth::fromUser($user);
        $response =  $this->get('api/user/', ['HTTP_Authorization' => 'Bearer ' . $token]);
        $response->assertStatus(200)->assertJson(["user" => ['name' => $user->name]]);
    }
}
