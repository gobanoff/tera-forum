<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $author->name }}</title>


    <style>
        .show {
            display: flex;
            justify-content: space-between;
            gap: 20px;
            font-size: 25px;
            font-weight: 700;
            font-style: italic;
            color: rgb(135, 130, 130);
            padding-bottom: 1.5rem;
        }

        .show1 {
            text-transform: capitalize;
            font-size: 25px;
            font-weight: 700;
            font-style: italic;
            color: rgb(176, 28, 202);
        }

        .list2 {
            display: flex;
            justify-content: center;
            font-size: 22px;
            padding-top: 20px;
            font-style: italic;
        }

        .list2 img {
            width: 50%;
            border-radius: 20rem;
            margin-bottom: 2rem;
        }

        .authorData {
            display: block;
            padding-left: 2rem;
            width: 50%;
        }

        .authorDataList {
            color: rgb(120, 18, 97);
            font-weight: 700;
            padding-top: 1rem;
            margin: 0;
        }

        small {
            color: rgb(120, 18, 97);
            font-size: 22px;
            font-style: italic;
            font-weight: 700;
        }

        .card-header {
            display: flex;
            justify-content: center;
            color: rgb(0, 0, 0);
            font-size: 30px;
            font-weight: 700;
            font-style: italic;
            padding-bottom: 2rem;
            padding-left: 15rem;
        }

        .card-body {
            display: flex;

        }

        .show5 {
            color: blue
        }
    </style>
</head>

<body>
    <div class="container">
        <div row justify-content-center>
            <div class="col-md-10">
                <div class="card">

                    <div class="card-header"> <b class="show5">{{ $author->name }} {{ $author->surname }}</b> Profile
                    </div>

                    <div class="card-body">

                        <div class="list2">
                            <div class="image">
                                <img src="{{ public_path('authorPhoto/' . basename($authorData->photo)) }}"
                                    alt="photo">
                            </div>

                            <div class="authorData">

                                <div class="show"> Full Name :
                                    <b class="show1">{{ $author->name }} {{ $author->surname }}</b>
                                </div>
                                <small>Posts : {{ $author->getPosts->count() }}</small>
                                @if ($authorData)
                                    <p class="authorDataList">Favorite Club : {{ $authorData->club }}</p>
                                    <p class="authorDataList">Hobby : {{ $authorData->hobby }}</p>
                                    <p class="authorDataList">Age : {{ $authorData->age }}</p>
                                    <p class="authorDataList">Nickname : {{ $authorData->nickname }}</p>
                                    <p class="authorDataList">E-mail : {{ $authorData->email }}</p>
                                @else
                                    <p class="authorDataList">No additional information available.</p>
                                @endif
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>










</body>

</html>
