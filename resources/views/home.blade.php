@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        @if(isset($data))
        @foreach($data as $film)
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">{{ $film->name }}</div>

                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-6">
                            <img class="img img-responsive" src="{{empty($film->photo)? 'http://via.placeholder.com/300x300' : env('IMAGE_PATH').$film->photo  }}" />

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
                                    <td colspan="2" class="text-center">
                                    <a href="{{URL::to('films/'.$film->slug)}}" class="btn btn-success ">Preview</a> <a class="btn btn-danger">Add to favourite</a>
                                    </td>
                                </tr>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        @endForeach

        <div class="col-md-8 col-md-offset-2 text-center">
            {{$data->links()}}
        </div>
        @endIf
    </div>
</div>
@endsection
