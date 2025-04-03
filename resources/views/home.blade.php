@extends('layouts.app')

@section('content')
    <div class="container"style="display: grid; grid-template-columns: 1fr 400px;">
        <div>
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header"> <a href="{{ route('author.index') }}"> Authors</a></div>
                    <div class="card-header"> <a href="{{ route('post.index') }}"> FK"Tera" Forum</a></div>
                    <div class="card-header"> <a href="{{ route('post.archive') }}"> Archive</a></div>
                    @if (auth()->check() && auth()->user()->role === 'author')
                        <div class="card-header"> <a href="{{ route('post.create') }}"> Create Your Post</a></div>
                    @endif



                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        <b style="color:rgb(33, 201, 25);">
                            {{ __('You are logged in!') }}</b>


                           


                    </div>
                </div>
            </div>
        </div>
    </div>

    <div>
        <!--<iframe src="http://localhost:8501" width="110%" height="500px" frameborder="0"></iframe>-->
    </div>
</div>
   
@endsection
