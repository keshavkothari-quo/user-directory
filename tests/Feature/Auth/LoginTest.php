<?php

namespace Tests\Feature\Auth;

use App\User;
use Tests\TestCase;

class LoginTest extends TestCase
{
    protected $user;
    protected $password = 'Test@123';
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testExample()
    {

        $response = $this->get('/');

        $response->assertStatus(200);
    }


    public function setUp(): void
    {
        parent::setUp(); // TODO: Change the autogenerated stub/
        $this->user = factory(User::class)->create([
            'password' => bcrypt($this->password)
        ]);
    }

    public function tearDown(): void
    {
        $this->user->delete();
    }

    /**
     * @test
     */
    public function test_user_can_view_a_login_form()
    {
        $response = $this->get('/login');
        $response->assertSuccessful();
        $response->assertViewIs('login');
    }

    /**
     * @test
     */
    public function user_can_login_with_correct_credentials()
    {
        $response = $this->post('/post-login', [
            'email' => $this->user->email,
            'password' => $this->password,
        ]);
        $response->assertRedirect('/dashboard');
        $this->assertAuthenticatedAs($this->user);
    }
}
