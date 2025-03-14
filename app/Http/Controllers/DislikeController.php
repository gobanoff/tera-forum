<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use App\Models\Comment;
use App\Models\Dislike;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

class DislikeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Post $post)
    {

        /** @var User $user */
        $user = Auth::user();

        if (!$user) {
            return response()->json(['message' => 'You need to log in'], 401);
        }

        // Проверяем, дизлайкал ли пользователь этот пост ранее
        if ($user->dislikes()->where('post_id', $post->id)->exists()) {

            return response()->json(['message' => 'You have already disliked this post !', 'dislikes_count' => $post->dislikes()->count()], 409);
        }
        // Проверяем, лайкал ли пользователь этот пост
        if ($user->likes()->where('post_id', $post->id)->exists()) {
            return response()->json(['message' => 'You cannot dislike a post that you have liked!', 
            'likes_count' => $post->likes()->count(), 'dislikes_count' => $post->dislikes()->count()], 409);
        }
        // Проверяем, отмечал ли пользователь этот пост
        if ($user->neutrals()->where('post_id', $post->id)->exists()) {
            return response()->json([
                'message' => 'You cannot dislike a post that you have marked!',
                'dislikes_count' => $post->dislikes()->count(),
                'likes_count' => $post->likes()->count(),
                'neutrals_count' => $post->neutrals()->count()
            ], 409);
        }

        $dislike = new Dislike();
        $dislike->user_id = $user->id;
        $dislike->post_id = $post->id;
        $dislike->save();

        $post->increment('dislikes_count');

        return response()->json(['message' => 'You giving dislike to this post !', 'dislikes_count' => $post->dislikes()->count()]);
    }




    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
