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

                    <div class="card-header">EDIT YOUR PROFILE</div>
                    <div class="card-body">
                        <form action="{{ route('author.update', $authorData->id) }}"
                            method="post"enctype="multipart/form-data">



                            <div class="form-group">
                                <label> Favorite Club :</label>
                                <input type="text" name="author_club"class="form-control"
                                    value="{{ old('author_club', $authorData->club) }}">
                                <small class="form-text text-muted">Change Your Favorite Club</small>
                            </div>

                            <div class="form-group">
                                <label> Nickname :</label>
                                <input type="text"name="author_nickname" class="form-control"
                                    value="{{ old('author_nickname', $authorData->nickname) }}">
                                <small class="form-text text-muted">Edit Your Nickname</small>
                            </div>

                            <div class="form-group">

                                <label> Age :</label>
                                <input type="text" name="author_age"class="form-control"
                                    value="{{ old('author_age', $authorData->age) }}">
                                <small class="form-text text-muted">Change Your Age</small>

                            </div>
                            <div class="form-group">

                                <label> Hobby :</label>
                                <input type="text" name="author_hobby"class="form-control"
                                    value="{{ old('author_hobby', $authorData->hobby) }}">
                                <small class="form-text text-muted">Change Your Hobby</small>

                            </div>
                            <div class="form-group">

                                <label> Email :</label>
                                <input type="email" name="author_email"class="form-control"
                                    value="{{ old('author_email', $authorData->email) }}">
                                <small class="form-text text-muted">Change Your Hobby</small>

                            </div>


                            @csrf

                            <button type="submit"class="btn btn-outline-warning">Save Your Changes</button>

                        </form>
                    </div>


                </div>
            </div>
        </div>
    </div>

@endsection


@section('title')
    AUTHOR EDIT
@endsection
