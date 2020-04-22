<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class PhoneLoginTest extends TestCase
{
    use DatabaseTransactions;
    // Note : Use Transaction Do Not Delete Whole Database Table , Only Delete Records For This Test Traction Only

    /**
     * A basic test example.
     * When A Registered Phone Number in Entered OTP Is Generated and Send
     * Status Code Should Be 200 OK
     * success : true
     * message : OTP Send To {{Phone Number }}
     * @return void
     */
    public function testCorrectPhoneLogin()
    {
        $user = factory('App\Models\User')->create();
        $this->seeInDatabase('users', ['phone' => $user->phone]);
        $this->json('POST', '/api/auth/phone/login', ['phone' => $user->phone])
            ->seeJson([
                'success' => true,
            ]);
    }

    public function testWrongPhoneNumberLogin()
    {
        $this->post('/api/auth/phone/login',['phone' => '89102716933']);
        $this->assertEquals(
            404, $this->response->getStatusCode()
        );

    }
}
