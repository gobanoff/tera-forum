@extends('layouts.app')
@section('content')

    <div class="container">
        <div row justify-content-center>
            <div class="col-md-11">
                <div class="card">

                    <div class="card-header">Archive
                        <div class="sortButtons">Filter by :

                            <form method="GET">
                                <div class="forms">
                                    <div class="form-group select">

                                        <select name ="author_id"class="form-control">
                                            <option value="0"disabled selected>Select Author</option>

                                            @foreach ($authors as $author)
                                                <option value="{{ $author->id }}"
                                                    @if ($author_id == $author->id) selected @endif> {{ $author->name }}
                                                    {{ $author->surname }}</option>
                                            @endforeach
                                        </select>

                                        <button type="submit"class="btn btn-outline-success" name="filter"
                                            value="author">Author</button>
                                    </div>

                                    <div class="form-group select">

                                        <select name="category" class="form-control">
                                            <option value="0"disabled selected>Select Category</option>
                                            @foreach ($categories as $cat)
                                                <option value="{{ $cat->category }}"
                                                    @if ($category == $cat->category) selected @endif>
                                                    {{ $cat->category }}</option>
                                            @endforeach
                                        </select>

                                        <button type="submit"class="btn btn-outline-success" name="filter"
                                            value="category">Category</button>
                                    </div>


                                    <div class="form-group"id="sch">

                                        <input class="form-control"type="text" name="s" id="s">
                                        <button type="submit"class="btn btn-outline-success" name="search"
                                            value="all">Search</button>
                                    </div>

                                </div>
                            </form>

                         

                        </div>
                    </div>


                    <div class="pg">{{ $archivedPosts->links() }} </div>

                    <!-- Если архивированные посты есть, выводим их -->
                    @if ($archivedPosts->isEmpty())
                        <p>No archived posts available.</p>
                    @else
                        <ul class="list-group">

                            @foreach ($archivedPosts as $post)
                                <li class="list-group-item">
                                    <div class="list">

                                        <h4>{{ $post->title }}<span class="postTime">( {{ $post->created_at }} )
                                                {{ $post->status }}</span></h4>

                                        <div class="buttons">
                                            <a href="{{ route('post.show', $post) }}"class="btn btn-outline-warning">
                                                SHOW</a>

                                            @if (auth()->check() && auth()->user()->role === 'author' && auth()->user()->author->id === $post->author_id)
                                                <form action="{{ route('post.destroy', $post) }}" method="post">

                                                    @csrf
                                                    <button type="submit"class="btn btn-outline-danger">DELETE</button>
                                                </form>
                                                <button type="submit"class="btn btn-outline-success">Your Post</button>
                                            @endif

                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                    <div class="pg">{{ $archivedPosts->links() }} </div>


                </div>
            </div>
        </div>
    </div>

@endsection

@section('title')
    ARCHIVE
@endsection
