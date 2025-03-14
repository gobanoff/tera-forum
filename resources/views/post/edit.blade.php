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

                    <div class="card-header">EDIT YOUR POST</div>
                    <div class="card-body">

                        <form action="{{ route('post.update', $post) }}" method="post"enctype="multipart/form-data">


                            <div class="form-group">
                                <label> Title :</label>
                                <input type="text" name="post_title"class="form-control"
                                    value="{{ old('post_title', $post->title) }}">
                                <small class="form-text text-muted">Change Title</small>

                            </div>

                            <div class="form-group">
                                <label> Text :</label>
                                <textarea name ="post_body"id="markdown-editor" class="form-control">{{ old('post_body', $post->body) }}</textarea>
                                <small class="form-text text-muted">Edit Text</small>
                            </div>

                            <div class="form-group">

                                <label> Category :</label>
                                <input type="text" name="post_category"class="form-control"
                                    value="{{ old('post_category', $post->category) }}">
                                <small class="form-text text-muted">Change Category</small>

                            </div>
                            <div class="form-group">
                                <label>Status:</label>
                                <select name="post_status" class="form-control">
                                    <option value="draft">Draft</option>
                                    <option value="published">Published</option>
                                    <!-- <option value="archived">Archived</option>-->
                                </select>
                                <small class="form-text text-muted">Editt Post Status</small>
                            </div>

                            @csrf

                            <button type="submit"class="btn btn-outline-warning">Save Your Changes</button>

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
    POST EDIT
@endsection
