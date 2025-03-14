@extends('layouts.app')
@section('content')
    <div class="container">
        <div row justify-content-center>
            <div class="col-md-8">
                <div class="card">

                    <div class="card-header">AUTHORS</div>

                    <ul class="list-group">
                        @foreach ($authors as $author)
                            <li class="list-group-item">
                                <div class="list">
                                    <a href="{{ route('author.show', $author) }}"id="authorName"> {{ $author->name }}
                                        {{ $author->surname }} </a>

                                    <small>Posts : {{ $author->getPosts->count() }}</small>
                                    <div class="buttons">

                                        <a href="{{ route('author.show', $author) }}"class="btn btn-outline-warning">SHOW</a>
                                        @if (auth()->check() && auth()->user()->role === 'author' && auth()->user()->id === $author->user_id)
                                            @if (!$author->authorData)
                                                <a href="{{ route('author.create', ['author_id' => $author->id]) }}"
                                                    class="btn btn-outline-success">CREATE PROFILE</a>
                                            @else
                                                <a
                                                    href="{{ route('author.edit', $author) }}"class="btn btn-outline-primary">EDIT</a>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>


            </div>
        </div>
    </div>
    </div>
@endsection

@section('title')
    AUTHOR INDEX
@endsection
