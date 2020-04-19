<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

use  App\Models\Institute;

class InstituteController extends Controller
{
    /**
     * Instantiate a new UserController instance.
     *
     * @return void
     */
    public function __construct()
    {
         $this->middleware('jwt.auth');
    }

    /**
     * Get all User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function allInstitute(Request $request)
    {
        $user = $request->auth;
        $institutes = Institute::where('superadmin', $user->id)
            ->get();
        return response()->json(['institutes' => $institutes], 200);
    }

    public function createInstitute(Request $request)
    {
      // Validate Incoming Request
        $user = $request->auth;  // Getting User From Auth
        //validate incoming request
        $this->validate($request, [
            'name' => 'required|string',
            'phone' => 'required|digits:10',
            'address'=> 'required|string',
            'postcode'=>'required|string',
        ]);

        try{
           $institute = new Institute;
           $institute->superadmin = $user->id;
           $input = $request->toArray();
           $institute->fill($input);
           $institute->save();

           //return successful response
            return response()->json(['institute' => $institute, 'message' => 'Institute Created','success' => true,], 201);

        }catch (\Exception $e)
        {
            return response()->json(['message' => 'User Registration Failed!','error' => $e->getMessage()], 409);
        }

    }

    public function updateInstitute(Request $request,$insId){
        $input = $request->toArray();
        $institute = Institute::findOrFail($insId);
        if($institute->superadmin == $request->auth->id)
        {
            $institute->fill($input);
            $institute->save();
            return response()->json(['institute' => $institute, 'message' => 'Institute Updated','success' => true,], 200);
        }else{
            return response()->json(['error' => 'Unauthorized Modification Access', 'message' => 'Only Institute Superadmin Can Modify','success' => false,], 200);
        }

    }
}
