<?php
   
namespace App\Http\Controllers\API;
   
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Http\Resources\UserResource;
use App\Models\User;
   
class UserController extends BaseController
{
    // show all user
    public function index()
    {
        $user = auth()->user();

        if ($find = request()->get('search')) {
            $user = $user->where('username', 'LIKE', "%{$find}%");
        }
   
        return $this->sendResponse(UserResource::collection($user->get()), 'User filtered.');
    }
 
    // show user by id
    public function show($id)
    {
        $user = auth()->user()->find($id);
 
        if (empty($user)) {
            return $this->sendError('User not found.');
        }
   
        return $this->sendResponse(new UserResource($user), 'User #' . $id . ' showed.');
    }
 
    // insert new user
    public function store(Request $request)
    {
        // must use api/register
        return $this->sendError('Unavailable.');
    }
 
    // update user by id
    public function update(Request $request, $id)
    {
        // must use api/profile
        return $this->sendError('Unavailable.');
    }
 
    // delete user by id
    public function destroy($id)
    {
        return $this->sendError('Unavailable.');
    }
}
