@extends('layouts.app')
@section('content')

    <div class="container">

        <div row justify-content-center>
            <div class="col-md-11">
                <div class="card">

                    <div class="card-header"> {{ $post->title }} <span id="author-name">{{ $author->name }}
                            {{ $author->surname }}</span>
                        <span>{{ $post->category }}</span> <span>{{ $post->created_at }}</span>
                    </div>


                    <div class="list">

                        <textarea name="post_body" id="pb" cols="150" rows="25"> {{ $post->body }}</textarea>

                        <div class="postbuttons">
                            <a href="{{ route('post.index', $post) }}"class="btn btn-outline-warning"> POSTS</a>
                            @if ($post->status === 'archived')
                                <a href="{{ route('post.archive', $post) }}"class="btn btn-outline-warning"> ARCHIVE</a>
                            @endif
                            @if (auth()->check() && auth()->user()->role === 'author' && auth()->user()->author->id === $post->author_id)
                                <a href="{{ route('post.edit', $post) }}"class="btn btn-outline-primary"
                                    @if ($post->status === 'archived') style="display:none;" @endif> EDIT</a>

                                <form action="{{ route('post.destroy', $post) }}" method="post">

                                    @csrf
                                    <button type="submit"class="btn btn-outline-danger">DELETE</button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>


            </div>

            <div class="likes my-3">
                <span class="like"> Likes : </span> <span id="likes-count">{{ $post->likes_count }}</span>
                <button id="like-button"class="btn btn-primary" onclick="handleReaction({{ $post->id }}, 'like')">
                    <i class="fa-solid fa-thumbs-up icon"></i>
                </button>
                <span class="neutral"> Neutral : </span> <span id="neutrals-count">{{ $post->neutrals_count }}</span>

                <button id="neutral-button" class="btn btn-primary"
                    onclick="handleReaction({{ $post->id }}, 'neutral')">
                    <i class="fa-solid fa-handshake icon"></i>
                </button>

                <span class="dislike"> Dislikes : </span> <span id="dislikes-count">{{ $post->dislikes_count }}</span>

                <button id="dislike-button" class="btn btn-danger"
                    onclick="handleReaction({{ $post->id }}, 'dislike')">
                    <i class="fa-solid fa-thumbs-down icon"></i>
                </button>

            </div>

            <div class="comments-section">

                @if ($post->comments && $post->comments->count() > 0)
                    @foreach ($post->comments as $comment)
                        <div class="comment"id="comment-{{ $comment->id }}">
                            <p><strong>{{ $comment->user->name }} {{ $comment->user->surname }}</strong>
                                ({{ $comment->created_at }}):</p>
                            <p class="comment-body"style="color:black;">{{ $comment->body }}</p>

                            @if (auth()->id() === $comment->user_id)
                                <div class="commentBtn">

                                    <button class="btn btn-outline-primary edit-comment-btn"
                                        data-comment-id="{{ $comment->id }}">Edit</button>

                                    <form
                                        action="{{ route('comments.destroy', ['post' => $post->id, 'comment' => $comment->id]) }}"
                                        method="POST" style="display:inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-outline-danger">Delete</button>
                                    </form>
                                </div>
                                <div class="edit-comment-form" id="edit-form-{{ $comment->id }}" style="display:none;">
                                    <form
                                        action="{{ route('comments.update', ['post' => $post->id, 'comment' => $comment->id]) }}"
                                        method="POST" class="inline-edit-form">
                                        @csrf
                                        @method('PUT')
                                        <textarea name="body" rows="2" class="form-control">{{ $comment->body }}</textarea>
                                        <button type="submit"
                                            class="btn btn-outline-success save-comment-btn">Save</button>
                                        <button type="button"
                                            class="btn btn-outline-secondary cancel-edit-btn">Cancel</button>
                                    </form>
                                </div>
                            @endif
                        </div>
                    @endforeach
                @else
                    <p id="nocomment"style="color:rgb(219, 219, 219)">There is no comments yet.</p>
                @endif
            </div>

            <!-- Форма для добавления комментария -->
            @auth
                <div class="add-comment">

                    <form action="{{ route('comments.store', $post->id) }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <textarea id="comm"name="body" rows="4" class="form-control" placeholder="Write your comment...." required></textarea>
                        </div>
                        <button type="submit" class="btn btn-outline-primary">Add Comment</button>

                    </form>


                </div>
            @else
                <p>Чтобы добавить комментарий, <a href="{{ route('login') }}">войдите</a>.</p>
            @endauth

        </div>
    </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const editButtons = document.querySelectorAll('.edit-comment-btn');
            const cancelButtons = document.querySelectorAll('.cancel-edit-btn');

            editButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const commentId = this.getAttribute('data-comment-id');
                    document.querySelector(`#comment-${commentId} .comment-body`).style.display =
                        'none';
                    document.querySelector(`#edit-form-${commentId}`).style.display = 'block';
                    this.style.display = 'none';
                });
            });

            cancelButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const commentId = this.closest('.edit-comment-form').id.replace('edit-form-',
                        '');
                    document.querySelector(`#comment-${commentId} .comment-body`).style.display =
                        'block';
                    document.querySelector(`#edit-form-${commentId}`).style.display = 'none';
                    document.querySelector(`#comment-${commentId} .edit-comment-btn`).style
                        .display = 'inline-block';
                });
            });
        });


        $(document).ready(function() {
            $(".comment").hover(
                function() {
                    $(this).find(".commentBtn").fadeIn(1000);
                },
                function() {
                    $(this).find(".commentBtn").fadeOut(1000);
                }
            );
        });
    </script>

    <script>
        /*  function likePost(postId) {
                fetch(`/like/${postId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                })
                .then(response => response.json())
                .then(data => {
                    alert(data.message);               
            document.querySelector(`#likes-count`).innerText = `${data.likes_count}`;
            
            if (data.message === 'You liked this post!') {
                document.querySelector("#like-button").disabled = true;
                document.querySelector("#dislike-button").disabled = false;
                document.querySelector("#neutral-button").disabled = false;
            } else if (data.message === 'You cannot like a post that you have already disliked!') {
                document.querySelector("#dislike-button").disabled = true;
                document.querySelector("#neutral-button").disabled = true;
            }else if (data.message === 'You cannot like a post that you have already marked!') {
                document.querySelector("#dislike-button").disabled = true;
                document.querySelector("#neutral-button").disabled = true;
            }
                });
            }
        
            function dislikePost(postId) {
                fetch(`/dislike/${postId}`, {
                   method: 'POST',
                   headers: {
                       'Content-Type': 'application/json',
                       'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                 })
                 .then(response => response.json())
                 .then(data => {
                     alert(data.message);
             document.querySelector(`#dislikes-count`).innerText = `${data.dislikes_count}`;
             
              // Обновление состояния кнопок
            if (data.message === 'You disliked this post!') {
                document.querySelector("#dislike-button").disabled = true;
                document.querySelector("#like-button").disabled = false;
                document.querySelector("#neutral-button").disabled = false;
            } else if (data.message === 'You cannot dislike a post that you have liked!') {
                document.querySelector("#like-button").disabled = true;
                document.querySelector("#neutral-button").disabled = true;
            }else if (data.message === 'You cannot dislike a post that you have marked!') {
                document.querySelector("#like-button").disabled = true;
                document.querySelector("#neutral-button").disabled = true;
            }
               });
            }

            function neutralPost(postId) {
                fetch(`/neutral/${postId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                })
                .then(response => response.json())
                .then(data => {
                    alert(data.message);               
            document.querySelector(`#neutrals-count`).innerText = `${data.neutrals_count}`;
            
            if (data.message === 'You marked this post!') {
                document.querySelector("#neutral-button").disabled = true;
                document.querySelector("#like-button").disabled = false;
                document.querySelector("#dislike-button").disabled = false;
            } else if (data.message === 'You cannot response to a post that you have liked!') {
                document.querySelector("#dislike-button").disabled = true;
                document.querySelector("#like-button").disabled = true;
            }else if (data.message === 'You cannot response to a post that you have disliked!') {
                document.querySelector("#dislike-button").disabled = true;
                document.querySelector("#like-button").disabled = true;
            }
               });
            }*/

        function handleReaction(postId, type) {
            fetch(`/${type}/${postId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                })
                .then(response => response.json())
                .then(data => {
                    alert(data.message);

                    const counts = {
                        like: 'likes-count',
                        dislike: 'dislikes-count',
                        neutral: 'neutrals-count'
                    };

                    document.querySelector(`#${counts[type]}`).innerText = data[`${type}s_count`];

                    const buttons = {
                        like: document.querySelector("#like-button"),
                        dislike: document.querySelector("#dislike-button"),
                        neutral: document.querySelector("#neutral-button")
                    };

                    Object.keys(buttons).forEach(btn => {
                        buttons[btn].disabled = (btn === type);
                    });
                });
        }
    </script>


@endsection

@section('title')
    POST SHOW
@endsection
