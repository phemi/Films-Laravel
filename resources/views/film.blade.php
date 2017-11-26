@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        @if(isset($film))
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">{{ $film->name }}</div>

                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-6">
                            <img class="responsive" src="http://via.placeholder.com/300x300" />

                        </div>
                        <div class="col-md-6">
                            <table class="table table-bordered table-responsive">
                                <tr>
                                    <th>
                                        Country
                                    </th>
                                    <td>
                                        {{ $film->country->country_name}}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        Release Date
                                    </th>
                                    <td>
                                        {{ $film->release_date}}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        Rating
                                    </th>
                                    <td>
                                        @for($i = 0; $i <= $film->rating; $i++)
                                            <span class="glyphicon glyphicon-star"></span>
                                        @endFor
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        Price
                                    </th>
                                    <td>
                                        <b>$ {{ $film->price}}</b>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        Genre(s)
                                    </th>
                                    <td>
                                        @foreach($film->film_genres as $filmGenre)
                                            @if(isset($filmGenre->genre->genre))
                                                {{$filmGenre->genre->genre}} <br />
                                            @endIf
                                        @endForeach
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" class="text-center">
                                     <a class="btn btn-danger"  data-toggle="modal" data-target="#commentModal">Post a comment</a>
                                    </td>
                                </tr>
                            </table>
                        </div>

                    </div>

                    <div class="row">
                        <div class="col-md-10 col-md-offset-1">
                            <h2>Description</h2>
                            <p>
                                {{$film->description}}
                            </p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-10 col-md-offset-1">
                            <h3>Comments</h3>

                                @foreach($film->comments as $comment)
                            <p style="background-color: rgba(101, 100, 100, 0.05) ; padding: 10px 10px 10px 10px; margin-bottom: 10px; ">
                                <b>{{$comment->name}}</b>
                                <br />
                                {{$comment->comment}}
                                <br />
                                <b>{{$comment->created_at}}</b>
                            </p>
                                @endForeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @endIf
    </div>
</div>


<div id="commentModal" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                Write on wall
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>


            <div class="modal-body">
                <div class="form-group">
                    <label >Name</label>
                    <input id="name" name="name" placeholder="" class="form-control" type="text"  />
                    <input type="hidden" name="film_id" value="{{$film->id}}" />
                </div>

                <div class="form-group">
                    <label>Message</label>
                    <textarea id="comment" name="comment" class="form-control"></textarea>
                </div>

            </div>
            <div class="modal-footer">
                <style>
                    .loading{
                        display: none;
                    }
                </style>
                <button name="loading" id="loading" class="loading btn btn-primary "  >Sending...</button>
                <button  id="commentSubmit" class="btn btn-primary" >Post</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>
<script
    src="https://code.jquery.com/jquery-3.2.1.min.js"
    integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4="
    crossorigin="anonymous"></script>

<script>
    $(document).ready(function() {
            $("#loading").hide();
            $('#commentSubmit').on('click', function (e) {
                e.preventDefault();
                $('#commentSubmit').hide();
                $("#loading").show();
                var name = $('#name').val();
                var comment = $('#comment').val();
                $.ajax({
                    type: "POST",
                    url: "/comment-on-film",
                    data: {name: name, comment: comment, film_id: '{{$film->id}}', _token: "{{csrf_token()}}" },
        success: function( data ) {
        if(data.hasError){
            $('#commentSubmit').show();
            $("#loading").hide();
            alert(data.message);
        }else{
            $('#commentSubmit').hide();
            $("#loading").hide();
            alert(data.message);
            $('#commentModal').modal('hide');
        }
    }
    });
    });
    });
</script>
@endsection
