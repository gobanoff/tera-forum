<?php

namespace App\Http\Controllers;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
         
        $comments = Comment::all();
        return view('comments.index', compact('comments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($post_id)
    {
         // Получаем пост, к которому добавляется комментарий
        $post = Post::findOrFail($post_id);
        return view('comments.create', compact('post'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request,$post_id)
    {
        $validator = Validator::make(
            $request->all(),
            [
             'body' => 'required|string|min:3',
            ]
        );
        if ($validator->fails()) {
            $request->flash();
            return redirect()->back()->withErrors($validator);
        }

        $comment = new Comment();
        $comment->body = $request->body;
        $comment->user_id = Auth::id(); 
        $comment->post_id = $post_id; 
        $comment->save();

       
        return redirect()->route('post.show', $post_id)->with('success_message', 'Your comment successfully added !');

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
        // Получаем комментарий для редактирования
        $comment = Comment::findOrFail($id);
        $post = $comment->post; // Получаем пост через связь
        return view('comments.edit', compact('comment', 'post'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $post_id, $comment_id)
    {
        $validator = Validator::make($request->all(), [
        'body' => 'required|string|min:3',
    ]);

    if ($validator->fails()) {
        return redirect()->back()->withErrors($validator)->withInput();
    }

    $comment = Comment::findOrFail($comment_id);

    // Проверяем, что пользователь является автором комментария
    if (auth()->id() !== $comment->user_id) {
        return redirect()->route('post.show', $comment->post_id)->with('error', 'You do not have permission to edit this comment');
    }

    $comment->body = $request->body;
    $comment->save();

    return redirect()->route('post.show', $comment->post_id)->with('success_message', 'Your comment has been updated!');
}
    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post, Comment $comment)
    {
        //if (auth()->id() !== $comment->user_id) {
       // return redirect()->route('post.show', $post)->with('error', 'У вас нет прав для удаления этого комментария');
  //  }

    $comment->delete();
    return redirect()->route('post.show', $post)->with('danger_message', 'Your comment has been deleted !');
    }
}
