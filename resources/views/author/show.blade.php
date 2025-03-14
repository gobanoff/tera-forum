@extends('layouts.app')
@section('content')

    <div class="container">
        <div row justify-content-center>
            <div class="col-md-10">
                <div class="card">

                    <div class="card-header">{{ $author->name }} {{ $author->surname }} Profile
                        @if (auth()->check() && auth()->user()->role === 'author' && auth()->user()->id === $author->user_id)
                            @if ($authorData)
                                <a href="{{ route('author.edit', $author) }}"class="btn btn-outline-primary">EDIT YOUR
                                    PROFILE</a>

                                <form action="{{ route('author.destroy', $authorData->id) }}" method="post">
                                    @csrf
                                    <button type="submit"class="btn btn-outline-danger">DELETE YOUR PROFILE</button>
                                </form>
                            @else
                                <a
                                    href="{{ route('author.create', ['author_id' => $author->id]) }}"class="btn btn-outline-primary">ADD
                                    DATA TO YOUR PROFILE</a>
                            @endif
                        @endif
                    </div>
                    <div class="card-body">

                        <div class="list1">

                            <div class="show">
                                <b class="show1">Full Name :</b>{{ $author->name }} {{ $author->surname }}
                            </div>

                        </div>

                        <a href="{{ route('author.pdf', $author) }}"class="btn btn-outline-primary"> PDF</a>
                        <a href="{{ route('author.index', $author) }}"class="btn btn-outline-danger"> AUTHORS</a>
                        @if ($authorData)
                            <div class="list2">
                                <div class="foto"><img src="{{ $authorData->photo }}"alt="photo"> </div>
                                <div class="authorData">

                                    <small>Posts : {{ $author->getPosts->count() }}</small>

                                    <p class="authorDataList">Favorite Club : {{ $authorData->club }}</p>
                                    <p class="authorDataList">Hobby : {{ $authorData->hobby }}</p>
                                    <p class="authorDataList">Age : {{ $authorData->age }}</p>
                                    <p class="authorDataList">Nickname : {{ $authorData->nickname }}</p>
                                    <p class="authorDataList">E-mail :
                                        {!! $authorData->email ?? '<span style="color: rgb(187, 179, 179);">Hidden by Author</span>' !!}</p>
                                @else
                                    <div class="list2"><img src="{{ $author->photo }}"alt="photo">
                                        <div class="authorData">
                                            <p class="authorDataList">No additional information available.</p>
                        @endif
                    </div>
                </div>


            </div>
        </div>
    </div>
    </div>
    </div>
    </div>

@endsection

@section('title')
    AUTHORS PROFILE
@endsection
