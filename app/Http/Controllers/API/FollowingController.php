<?php
   
namespace App\Http\Controllers\API;
   
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Http\Resources\UserResource;
use App\Models\UserFollow;
   
class FollowingController extends BaseController
{
    // show login user following
    public function index()
    {
        $following = auth()->user()->followings;
   
        return $this->sendResponse(UserResource::collection($following), 'User followings showed.');
    }
 
    public function show($id)
    {
        return $this->sendError('Unavailable.');
    }
 
    public function store(Request $request)
    {
        return $this->sendError('Unavailable.');
    }
 
    // insert following
    public function update(Request $request, $id)
    {
        if (UserFollow::where('user_id', auth()->user()->id)->where('follower_id', $id)->exists()) {
            return $this->sendError('User #' . $id . ' already followed.');
        }

        if (auth()->user()->id == $id) {
            return $this->sendError('Cannot follow yourself.');
        }

        $following = new UserFollow();
        $following->user_id = auth()->user()->id;
        $following->follower_id = $id;
 
        if ($following->save()) {
            return $this->sendResponse([], 'User #' . $id . ' followed.');
        }
        else {
            return $this->sendError('Following not saved.');
        }
    }
 
    // delete following (unfollow)
    public function destroy($id)
    {
        $following = UserFollow::where('user_id', auth()->user()->id)->where('follower_id', $id);

        if (!$following->exists()) {
            return $this->sendError('User #' . $id . ' not followed.');
        }
 
        if ($following->delete()) {
            return $this->sendResponse([], 'User #' . $id . ' unfollowed.');
        }
        else {
            return $this->sendError('Following not saved.');
        }
    }
}
