<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\DislikeController;
use App\Http\Controllers\NeutralController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


Route::prefix('authors')->group(function () {

    Route::get('', [AuthorController::class, 'index'])->name('author.index');
    Route::get('show/{author}', [AuthorController::class, 'show'])->name('author.show');

    // Только аутентифицированные авторы могут создавать профиль
    Route::middleware(['auth', 'auth.author'])->group(function () {
        Route::get('create', [AuthorController::class, 'create'])->name('author.create');
        Route::post('store', [AuthorController::class, 'store'])->name('author.store');
        Route::get('edit/{author}', [AuthorController::class, 'edit'])->name('author.edit');
        Route::post('update/{authorData}', [AuthorController::class, 'update'])->name('author.update');
        Route::post('delete/{authorData}', [AuthorController::class, 'destroy'])->name('author.destroy');
        // Route::get('pdf/{author}', [ AuthorController::class,'pdf'])->name('author.pdf');
    });

    // Только владелец профиля может редактировать и удалять свой профиль
    Route::middleware(['auth', 'auth.author', 'isAuthorOwner'])->group(function () {
        //  Route::get('edit/{author}', [AuthorController::class, 'edit'])->name('author.edit');
        //  Route::post('update/{authorData}', [AuthorController::class, 'update'])->name('author.update');
        // Route::post('delete/{authorData}', [AuthorController::class, 'destroy'])->name('author.destroy');
    });
});

// Группа маршрутов для постов
Route::prefix('posts')->group(function () {
    Route::get('', [PostController::class, 'index'])->name('post.index');
    Route::get('show/{post}', [PostController::class, 'show'])->name('post.show');
    Route::get('archive', [PostController::class, 'archive'])->name('post.archive');


    Route::middleware(['auth', 'auth.author'])->group(function () {
        Route::get('create', [PostController::class, 'create'])->name('post.create');
        Route::post('store', [PostController::class, 'store'])->name('post.store');
        Route::get('edit/{post}', [PostController::class, 'edit'])->name('post.edit');
        Route::post('update/{post}', [PostController::class, 'update'])->name('post.update');
        Route::post('delete/{post}', [PostController::class, 'destroy'])->name('post.destroy');
    });
});

Route::middleware(['auth'])->group(function () {
    Route::post('/like/{post}', [LikeController::class, 'store'])->name('post.like');
    Route::post('/dislike/{post}', [DislikeController::class, 'store'])->name('post.dislike');
    Route::post('/neutral/{post}', [NeutralController::class, 'store'])->name('post.neutral');
    Route::get('pdf/{author}', [AuthorController::class, 'pdf'])->name('author.pdf');
    Route::get('posts/{post}/comments/create', [CommentController::class, 'create'])->name('comments.create');
    Route::post('posts/{post}/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::get('posts/{post}/comments/{comment}/edit', [CommentController::class, 'edit'])->name('comments.edit');
    Route::put('posts/{post}/comments/{comment}', [CommentController::class, 'update'])->name('comments.update');
    Route::post('posts/{post}/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');
});





Route::middleware(['auth', 'auth.author'])->group(function () {
    Route::get('/author-panel', [AuthorController::class, 'index'])->name('author.panel');
});


Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
