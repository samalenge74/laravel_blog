<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\Posts;
use App\Models\Rating;
use App\Http\Controllers\Controller;
use App\Http\Requests\PostFormRequest;

class PostController extends Controller
{
    public function index() {
        // fetch 5 posts from database which are latest and active
        $posts = Posts::where('active', 1)->orderBy('created_at','desc')->paginate(5);
        //page heading
        $title = 'Latest Posts';
        
        // return home.blade.php template from resources/view folder
        return view('home')->withPosts($posts)->withTitle($title);
    }
    
    public function create(Request $request)
    {
        // only author and admin can post
        if ($request->user()->can_post()) {
            return view('posts.create');
        } else {
            return redirect('/')->withErrors('You have not sufficient permissions for writing post');
        }
    }
    
    public function store(PostFormRequest $request)
    {
        $post = new Posts();
        $post->title = $request->get('title');
        $post->body = $request->get('body');
        $post->slug = Str::slug($post->title);
        
        $duplicate = Posts::where('slug', $post->slug)->first();
        if($duplicate)
        {
            return redirect('new-post')->withErrors('Title already exists.');
        }
        
        $post->author_id = $request->user()->id;
        if($request->has('save'))
        {
            $post->active = 0;
            $message = 'Post save successfully';
        }else{
            $post->active = 1;
            $message = 'Post published successfully';
        }
        $post->save();
        return redirect('edit/' . $post->slug)->withMessasge($message);
    }
    
    public function show($slug)
    {
        $post = Posts::where('slug',$slug)->first();
        
        if(!$post)
        {
            return redirect('/')->withErrors('requested page not found');
        }
        $avg_rating = 0;
        $rating = $post->rating;
        if($rating)
            $avg_rating = $rating->avg('rating');
        
        $comments = $post->comments;
        $data['post'] = $post;
        $data['rating'] = $avg_rating;
        $data['comments'] = $comments;
        //return view('posts.show')->withPost($post)->withComments($comments);
        return view('posts.show', $data);
    }
    
    public function edit(Request $request, $slug)
    {
        $post = Posts::where('slug', $slug)->first();
        if($post && $request->user()->id == $post->author_id)
        {
            return view('posts.edit')->with('post', $post);
        }
        return redirect('/')->withErrors('You do not have sufficient permissions to edit this post');
    }
    
    public function update(Request $request) 
    {
        $post_id = $request->input('post_id');
        $post = Posts::find($post_id);
        if($post && $post->author_id == $request->user()->id)
        {
            $title = $request->input('title');
            $slug = Str::slug($title);
            $duplicate = Posts::where('slug', $slug)->first();
            if($duplicate)
            {
                if($duplicate->id != $post_id)
                {
                    return redirect('edit/' . $post->slug)->withErrors('Title already exists.')->withInput();
                }
                else
                {
                    $post->slug = $slug;
                }
            }
            
            $post->title = $title;
            $post->body = $request->input('body');
            
            if($request->has('save'))
            {
                $post->active = 0;
                $message = 'Post saved successfully';
                $landing = 'edit/' . $post->slug;
            }
            else {
                $post->active = 1;
                $message = 'Post updated successfully';
                $landing = $post->slug;
            }
            $post->save();
            return redirect($landing)->withMessage($message);
            
        }
        else {
            return redirect('/')->withErrors('You do not have sufficient permissions to update this post');
        }
    }
    
    public function destroy(Request $request, $id) {
        $post = Posts::find($id);
        if($post && $post->author_id == $request->user()->id)
        {
            $post->delete();
            $data['message'] = 'Post deleted Successfully';
        }
        else {
            $data['errors'] = 'You do not have sufficient permissions to delete this post';
        }
        
        return redirect('/')->with($data);
    }
    
    public function postRating(Request $request, Posts $post) {
        $rating = new Rating;
        $rating->user_id = $request->user()->id;
        $rating->rating = $request->input('star');
        $post->ratings()->save($rating);
        return redirect()->back();
    }
    
    public static function getAVGRating(Posts $post)
    {
        $avg_rating = 0;
        $ratings = Rating::where('rateable_id', $post->id);
        if($ratings)
            $avg_rating = $ratings->avg('rating');
        
        return $avg_rating;
        
    }
}
    