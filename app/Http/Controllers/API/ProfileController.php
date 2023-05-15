<?php
   
namespace App\Http\Controllers\API;
   
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Http\Resources\UserResource;
use App\Models\User;
   
class ProfileController extends BaseController
{
    // show login user data
    public function index()
    {
        $user = auth()->user();
   
        return $this->sendResponse(new UserResource($user), 'User profile showed.');
    }
 
    public function show($id)
    {
        return $this->sendError('Unavailable.');
    }
 
    // update login user data
    public function store(Request $request)
    {
        $this->validate($request, [
            'date_of_birth' => 'date',
            'image' => 'image|mimes:jpg,png,jpeg,gif,svg|max:2048',
            'phone' => 'regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
            'email' => 'email',
            'password' => 'min:6',
            'password_repeat' => 'required_with:password|same:password'
        ]);

        $user = auth()->user();

        $input = $request->all();
        if (!empty($input['password'])) {
            $input['password'] = bcrypt($input['password']);
        }

        if ($request->hasFile('image')) {
            $input['image'] = $request->file('image')->store('image', 'public');
        }
 
        // update user profile
        $updated = $user->fill($input)->save();
 
        if ($updated) {
            return $this->sendResponse(new UserResource($user), 'User profile updated.');
        }
        else {
            return $this->sendError('User profile not saved.');
        }
    }
 
    // update user by id
    public function update(Request $request, $id)
    {        
        return $this->sendError('Unavailable.');
    }
 
    // delete user by id
    public function destroy($id)
    {
        return $this->sendError('Unavailable.');
    }
}
