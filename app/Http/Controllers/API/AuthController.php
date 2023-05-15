<?php
   
namespace App\Http\Controllers\API;
   
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\User;
   
class AuthController extends BaseController
{
    /**
     * Register api
     *
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $this->validate($request, [
            'username' => 'required',
            'firstname' => 'required',
            'date_of_birth' => 'required|date',
            'image' => 'image|mimes:jpg,png,jpeg,gif,svg|max:2048',
            'phone' => 'regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
            'email' => 'required|email',
            'password' => 'required|min:6',
            'password_repeat' => 'required|same:password'
        ]);

        if (User::where('username', $request->username)->orWhere('email', $request->email)->exists()) { 
            return $this->sendError('Failed.', ['error' => 'Username/email exists.']);
        }

        $input = $request->all();
        $input['password'] = bcrypt($input['password']);

        if ($request->hasFile('image')) {
            $input['image'] = $request->file('image')->store('image', 'public');
        }
        
        $user = User::create($input);

        $success['token'] = $user->createToken('SIA_API')->accessToken;
        $success['username'] = $user->username;
   
        return $this->sendResponse($success, 'User created.');
    }
   
    /**
     * Login api
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        if (Auth::attempt(['username' => $request->username, 'password' => $request->password])) { 
            $user = Auth::user(); 
            $success['token'] =  $user->createToken('SIA_API')->accessToken; 
            $success['username'] = $user->username;
   
            return $this->sendResponse($success, 'Logged in.');
        }
        else { 
            return $this->sendError('Failed.', ['error' => 'Unauthorised']);
        } 
    }
}
