@extends('layouts.app')
@section('content')

    <div class="container">
        <div row justify-content-center>
            <div class="col-md-8">

                @if ($errors->any())
                    <div class="alert alert-danger formErr">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="card">

                    <div class="card-header">POST CREATING</div>
                    <div class="card-body">

                        <form action="{{ route('post.store') }}" method="post"enctype="multipart/form-data">


                            <div class="form-group">
                                <label> Title :</label>
                                <input type="text" name="post_title"class="form-control">
                                <small class="form-text text-muted">Enter Post Title</small>
                            </div>

                            <div class="form-group">
                                <label> Text :</label>
                                <textarea name ="post_body"id="markdown-editor" class="form-control"></textarea>
                                <small class="form-text text-muted">Enter Text</small>
                            </div>

                            <div class="form-group">

                                <label> Category :</label>
                                <input type="text" name="post_category"class="form-control">
                                <small class="form-text text-muted">Enter Category</small>

                            </div>
                            <div class="form-group">
                                <label>Status:</label>
                                <select name="post_status" class="form-control">
                                    <option value="draft">Draft</option>
                                    <option value="published">Published</option>
                                    <!-- <option value="archived">Archived</option>-->
                                </select>
                                <small class="form-text text-muted">Select Post Status</small>
                            </div>
                            <input type="hidden" name="author_id" value="{{ auth()->user()->author->id }}">

                            @csrf

                            <button type="submit"class="btn btn-outline-warning">Create Post</button>

                        </form>
                    </div>


                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/simplemde/latest/simplemde.min.js"></script>
    <script>
        // Инициализация SimpleMDE 
        document.addEventListener("DOMContentLoaded", function() {
            var simplemde = new SimpleMDE({
                element: document.getElementById("markdown-editor"),
                spellChecker: false, // Выключаем проверку орфографии (по желанию)
                autosave: {
                    enabled: true,
                    uniqueId: "post_body", // уникальный идентификатор для автосохранения
                    delay: 1000, // автосохранение раз в секунду
                },
                placeholder: "Напишите ваш пост на Markdown..."
            });
        });
    </script>
@endsection

@section('title')
    POST CREATE
@endsection
