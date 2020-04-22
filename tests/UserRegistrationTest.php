<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class UserRegistrationTest extends TestCase
{
    use DatabaseTransactions;
    // Note : Use Transaction Do Not Delete Whole Database Table , Only Delete Records For This Test Traction Only

    /**
     * Testing Registration of User
     */
    public function testUserRegistration(){
        //$user = factory('App\Models\User')->create();
       // $this->seeInDatabase('users', ['name'=>$user->name,'phone' => $user->phone]);
        $this->json('POST', '/api/auth/register', ['name'=>'Radeep Test','phone' =>'1122334488'])
            ->seeJson([
                'success' => true,
            ]);
        $this->seeInDatabase('phone_logins', ['phone'=>'1122334488']);
    }
}
