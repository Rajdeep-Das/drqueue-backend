<?php


/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});
$router->get('db',function () use ($router){
    $servername = "13.233.198.210";
    $username = "drqueue";
    $password = "drqueue";

    // Create connection
    $conn = new mysqli($servername, $username, $password);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    echo "Connected successfully";
});
$router->get('/api', function () use ($router) {

    return response()->json(['message' => 'DrQueue Api', 'version' => '1.0']);
});
$router->group(['prefix' => 'api'], function () use ($router) {
    // Matches "/api/register
    $router->post('auth/register', 'AuthController@register');
    // Matches "/api/auth/verify
    $router->post('auth/phone/verify', 'AuthController@verifyFirstTimeUserPhone');
    // Matches "/api/auth/login
    $router->post('auth/phone/login', 'AuthController@loginRequestPhone');
    // Matches "/api/auth/phone
    $router->post('auth/phone/token', 'AuthController@authenticatePhone');

    $router->group(
        ['middleware' => 'jwt.auth'],
        function() use ($router) {
            // Matches "/api/profile
            $router->get('profile', 'UserController@profile');
            $router->patch('profile','UserController@profileUpdate');

            // Matches "/api/users/1
            //get one user by id
            $router->get('users/{id}', 'UserController@singleUser');

            // Matches "/api/users
            $router->get('users', 'UserController@allUsers');


            /* ---------------  Institute Routes -----------------*/
            $router->get('/institutes','InstituteController@allInstitute');
            $router->post('/institutes','InstituteController@createInstitute');
            $router->patch('/institutes/{insId}','InstituteController@updateInstitute');
        }
    );
});





