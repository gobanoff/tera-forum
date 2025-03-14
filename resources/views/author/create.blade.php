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

                    <div class="card-header">FILL IN YOUR PROFILE</div>
                    <div class="card-body">

                        <form action="{{ route('author.store') }}" method="post"enctype="multipart/form-data">

                            <div class="form-group">
                                <label> Favorite Club :</label>
                                <input type="text" name="author_club"class="form-control">
                                <small class="form-text text-muted">Add Your Favorite Club</small>
                            </div>

                            <div class="form-group">
                                <label> Nickname :</label>
                                <input type="text"name="author_nickname" class="form-control">
                                <small class="form-text text-muted">Add Your Nickname</small>
                            </div>

                            <div class="form-group">

                                <label> Age :</label>
                                <input type="text" name="author_age"class="form-control">
                                <small class="form-text text-muted">Add Your Age</small>

                            </div>
                            <div class="form-group">

                                <label> Hobby :</label>
                                <input type="text" name="author_hobby"class="form-control">
                                <small class="form-text text-muted">Add Your Hobby</small>

                            </div>
                            <div class="form-group">

                                <label> Email :</label>
                                <input type="email" name="author_email"class="form-control">
                                <small class="form-text text-muted">Your Email</small>

                            </div>
                            <div class="form-group">
                                <label> Photo :</label>
                                <input type="file" name="author_photo"class="form-control">
                                <small class="form-text text-muted">Enter photo</small>
                            </div>


                            <input type="hidden" name="author_id" value="{{ $author_id }}">


                            @csrf

                            <button type="submit"class="btn btn-outline-warning">Save Your Data</button>

                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>

@endsection


@section('title')
    AUTHOR DATA
@endsection
