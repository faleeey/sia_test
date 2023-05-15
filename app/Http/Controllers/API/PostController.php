<?php
   
namespace App\Http\Controllers\API;
   
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Http\Resources\PostResource;
use App\Models\Post;
use App\Models\PostFile;
   
class PostController extends BaseController
{
    // view all post
    public function index()
    {
        $posts = auth()->user()->posts;
   
        return $this->sendResponse(PostResource::collection($posts), 'All post showed.');
    }
 
    // view post by id
    public function show($id)
    {
        $post = auth()->user()->posts()->find($id);
 
        if (empty($post)) {
            return $this->sendError('Post not found.');
        }
   
        return $this->sendResponse(new PostResource($post), 'Post #' . $id . ' showed.');
    }
 
    // insert new post
    public function store(Request $request)
    {
        $this->validate($request, [
            'caption' => 'required',
            'image' => 'image|mimes:jpg,png,jpeg,gif,svg|max:2048',
            'images' => 'array',
            'images.*' => 'image|mimes:jpg,png,jpeg,gif,svg|max:2048'
        ]);
 
        $post = new Post();
        $post->caption = $request->caption;
 
        if (auth()->user()->posts()->save($post)) {
            $files = [];

            // save single image
            if ($request->hasFile('image')) {
                $files[] = $request->file('image')->store('image', 'public');
            }
            // save multiple image
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $files[] = $image->store('image', 'public');
                }
            }

            foreach ($files as $file) {
                $image = new PostFile();
                $image->post_id = $post->id;
                $image->image = $file;
                $image->save();
            }

            return $this->sendResponse(new PostResource($post), 'Post saved.');
        }
        else {
            return $this->sendError('Post not saved.');
        }
    }
 
    // update post by id
    public function update(Request $request, $id)
    {        
        $post = auth()->user()->posts()->find($id);
 
        if (!$post) {
            return $this->sendError('Post not found.');
        }
 
        $updated = $post->fill($request->all())->save();
 
        if ($updated) {
            return $this->sendResponse(new PostResource($post), 'Post updated.');
        }
        else {
            return $this->sendError('Post not saved.');
        }
    }
 
    // delete post by id
    public function destroy($id)
    {
        $post = auth()->user()->posts()->find($id);
 
        if (!$post) {
            return $this->sendError('Post not found.');
        }
 
        if ($post->delete()) {
            return $this->sendResponse([], 'Post deleted.');
        }
        else {
            return $this->sendError('Post not deleted.');
        }
    }
}
