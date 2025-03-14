<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Author;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Carbon\Carbon;

class PostController extends Controller
{

    const PAGE_SIZE = 10;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $posts = Post::query();
        $authors = Author::all();
        $categories = Post::select('category')->distinct()->get();

        if ($request->filter && 'author' == $request->filter) {
            //  $posts = $posts->where('author_id', $request->author_id);
            $posts = Post::where('author_id', $request->author_id);
        }

        if ($request->filter == 'category' && $request->category) {
            // $posts = $posts->where('category', $request->category);
            $posts = Post::where('category', $request->category);
        }

        if ($request->search && 'all' == $request->search) {
            // $posts = $posts->where('title', 'like', '%' . $request->s . '%')
            //   ->orWhere('slug', 'like', '%' . $request->s . '%')->orWhere('body', 'like', '%' . $request->s . '%');
            $posts = Post::where('title', 'like', '%' . $request->s . '%')
                ->orWhere('slug', 'like', '%' . $request->s . '%')
                ->orWhere('body', 'like', '%' . $request->s . '%');
        }

        // Ограничиваем видимость статусов
        $user = auth()->user();
        if ($user && $user->role === 'author') {
            // Если автор, то показываем опубликованные посты ИЛИ его собственные черновики
            $posts = $posts->where(function ($query) use ($user) {
                $query->whereIn('status', ['published'])
                    ->orWhere(function ($q) use ($user) {
                        $q->where('status', 'draft')
                            ->where('author_id', $user->author->id);
                    });
            });
        } else {
            // Для остальных пользователей показываем только опубликованные посты
            $posts = $posts->whereIn('status', ['published']);
        }

        $posts = $posts->orderBy('created_at', 'desc')->paginate(self::PAGE_SIZE)->withQueryString();

        return view('post.index', [
            'posts' => $posts,
            'authors' => $authors,
            'categories' => $categories,
            'category' => $request->category ?? '',
            'author_id' => $request->author_id ?? 0
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Post $post)
    {
        return view('post.create', ['author' => $post->author,]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $author = auth()->user()->author; // Получаем авторизованного автора

        if (!$author) {
            abort(403, 'You cannot create post.');
        }


        $validator = Validator::make(
            $request->all(),
            [
                'post_title' => ['required', 'min:3', 'max:60'],
                'post_body' => ['required'],
                'post_category' => ['required', 'min:3', 'max:30'],
                'post_status' => ['required', 'in:draft,published,archived'],
                //'author_id' => ['required', 'integer', 'min:1'],

            ],
            [
                'post_title.required' => 'The post title is required.',
                'post_title.min'      => 'The post title must be at least 3 characters.',
                'post_title.max'      => 'The post title must not exceed 60 characters.',
                'post_body.required'  => 'The post body is required.',
                'post_category.required' => 'The post category is required.',
                'post_category.min'      => 'The post category must be at least 3 characters.',
                'post_category.max'      => 'The post category must not exceed 30 characters.',


            ]
        );

        if ($validator->fails()) {
            $request->flash();
            return redirect()->back()->withErrors($validator)->withInput();
        }


        $slug = Str::slug($request->post_title);
        $baseSlug = $slug;
        $counter = 1;

        while (Post::where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        $post = new Post();
        $post->title = $request->post_title;
        $post->body = $request->post_body;
        $post->category = $request->post_category;
        $post->status = $request->post_status;
        $post->author_id = $author->id;
        $post->slug = $slug;
        $post->save();

        return redirect()->route('post.index')->with('success_message', 'A new post has been created !');
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        return view('post.show', ['post' => $post, 'author' => $post->author,]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        return view('post.edit', ['post' => $post]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'post_title' => ['required', 'min:3', 'max:60'],
                'post_body' => ['required'],
                // 'post_slug' => ['required', 'string', 'max:100', 'alpha_dash', 'unique:posts,slug'],
                'post_category' => ['required', 'min:3', 'max:30'],
                'post_status' => ['required', 'in:draft,published,archived'],
                // 'author_id' => ['required', 'integer', 'min:1'],

            ],
            [
                'post_title.required' => 'The post title is required.',
                'post_title.min'      => 'The post title must be at least 3 characters.',
                'post_title.max'      => 'The post title must not exceed 60 characters.',
                'post_body.required'  => 'The post body is required.',
                'post_category.required' => 'The post category is required.',
                'post_category.min'      => 'The post category must be at least 3 characters.',
                'post_category.max'      => 'The post category must not exceed 30 characters.',

            ]
        );

        if ($validator->fails()) {
            $request->flash();
            return redirect()->back()->withErrors($validator);
        }

        $slug = Str::slug($request->post_title, '-');

        $existingPostCount = Post::where('slug', $slug)->count();
        if ($existingPostCount > 0) {
            $slug = $slug . '-' . ($existingPostCount + 1);
        }

        $post->title = $request->post_title;
        $post->body = $request->post_body;
        $post->category = $request->post_category;
        $post->status = $request->post_status;
        // $post->author_id = $request->author_id;
        $post->slug = $slug;
        $post->save();

        return redirect()->route('post.index')->with('success_message', 'The post has been updated !');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        if ($post->status === 'archived') {
            $post->delete();
            return redirect()->route('post.archive')->with('danger_message', 'The post has been deleted!');
        }

        $post->delete();
        return redirect()->route('post.index')->with('danger_message', 'The post has been deleted!');
    }


    public function archive(Request $request)
    {
        $authors = Author::all();
        $categories = Post::select('category')->distinct()->get();

        // Изначально строим запрос
        $posts = Post::where('status', 'archived');

        // Применяем фильтр по автору, если выбран
        if ($request->has('filter') && $request->filter == 'author' && $request->author_id) {
            $posts->where('author_id', $request->author_id);
        }

        // Применяем фильтр по категории, если выбран
        if ($request->has('filter') && $request->filter == 'category' && $request->category) {
            $posts->where('category', $request->category);
        }

        // Применяем поиск по заголовку, слагу или содержимому
        if ($request->has('search') && $request->search == 'all' && $request->s) {
            $posts->where(function ($query) use ($request) {
                $query->where('title', 'like', '%' . $request->s . '%')
                    ->orWhere('slug', 'like', '%' . $request->s . '%')
                    ->orWhere('body', 'like', '%' . $request->s . '%');
            });
        }

        $archivedPosts = $posts->orderBy('created_at', 'desc')->paginate(self::PAGE_SIZE)->withQueryString();
        // Архивируем посты, которые были опубликованы более 10 минут назад
        $dateThreshold = Carbon::now()->subYear();
        $updated = Post::where('status', 'published')
            ->where('created_at', '<=', $dateThreshold)
            ->update(['status' => 'archived']);

        // Сообщение об успешной архивации
        $message = $updated > 0 ? "$updated posts have been archived!" : "No posts were archived.";

        return view('post.archive',  [
            'archivedPosts' => $archivedPosts,
            'message' => $message,
            'authors' => $authors,
            'categories' => $categories,
            'category' => $request->category ?? '',
            'author_id' => $request->author_id ?? 0
        ]);
    }
}
