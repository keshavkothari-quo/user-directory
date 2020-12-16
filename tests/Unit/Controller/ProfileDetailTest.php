<?php

namespace Tests\Unit\Controller;

use App\Http\Container\ProfileContainer;
use App\User;
use App\UserCity;
use PHPUnit\Framework\TestCase;

class ProfileDetailTest extends TestCase
{
    protected $user;
    protected $password;
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testExample()
    {
        $this->assertTrue(true);
    }

    public function setUp(): void
    {
        $this->user = factory(User::class)->create([
            'password' => bcrypt($this->password = 'Test@123')
        ]);
    }

    public function tearDown(): void
    {
        UserCity::deleteUserCity($this->user->id);
        $this->user->delete();
    }


    public function testUpdateProfileData(){
        $faker = \Faker\Factory::create();
        $user = $this->user;
        $data = [
            "userId" => $user->id,
            "name" => $faker->name(),
            "email" => $user->email,
            "mobile" => $user->mobile,
            "dob" => $faker->date(),
            "state" => "1",
            "city" => "1",
        ];

        $profileContainer = new ProfileContainer();
        $updatedUser = $profileContainer->saveProfileData($data);
        $this->assertInstanceOf(User::class,$updatedUser);
        $this->assertEquals($data['name'], $updatedUser->name);
        $this->assertEquals($user->id,$updatedUser->id);
        $this->assertEquals($user->email, $updatedUser->email);
        $this->assertEquals($data['dob'], $updatedUser->dob);
    }

}
