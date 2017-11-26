@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Create Film</div>

                <div class="panel-body">
                    @if(session('toast'))
                    @if(session('toast_level') == 'warning')
                    <div class="row alert alert-danger">
                        <p><ul>{{session('toast')}}</ul></p>
                    </div>
                    @else
                    <div class="row alert alert-success">
                        <p>{{session('toast')}}</p>
                    </div>
                    @endIf

                    @endIf
                    <form class="form-horizontal" method="POST" action="{{ route('create_film') }}" enctype="multipart/form-data">
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label for="name" class="col-md-4 control-label">Name</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" required autofocus>

                                @if ($errors->has('name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('slug') ? ' has-error' : '' }}">
                            <label for="slug" class="col-md-4 control-label">Slug</label>

                            <div class="col-md-6">
                                <input id="slug" type="text" class="form-control" name="slug" value="{{ old('slug') }}" required >

                                @if ($errors->has('slug'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('slug') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('rating') ? ' has-error' : '' }}">
                            <label for="rating" class="col-md-4 control-label">Rating</label>

                            <div class="col-md-6">
                                <input id="rating" type="text" class="form-control" name="rating" value="{{ old('rating') }}" required >

                                @if ($errors->has('rating'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('rating') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('release_date') ? ' has-error' : '' }}">
                            <label for="release_date" class="col-md-4 control-label">Release Date</label>

                            <div class="col-md-6">
                                <input id="release_date" type="date" class="form-control" name="release_date" value="{{ old('release_date') }}" required >

                                @if ($errors->has('release_date'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('release_date') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('price') ? ' has-error' : '' }}">
                            <label for="price" class="col-md-4 control-label">Price</label>

                            <div class="col-md-6">
                                <input id="price" type="text" class="form-control" name="price" value="{{ old('price') }}" required >

                                @if ($errors->has('price'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('price') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="country " class="col-md-4 control-label">Country</label>

                            <div id="country" class="col-md-6">
                                <select class="form-control" name="country_id">
                                    @if(isset($data->countries))
                                    @foreach($data->countries as $country)
                                    <option value="{{$country->id}}">
                                        {{$country->country_name}}
                                    </option>
                                    @endForeach
                                    @endIf
                                </select>
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('photo') ? ' has-error' : '' }}">
                            <label for="photo" class="col-md-4 control-label">Picture</label>

                            <div class="col-md-6">
                                <input id="photo" type="file" class="form-control" name="photo" value="{{ old('photo') }}" required >

                                @if ($errors->has('photo'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('photo') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="description" class="col-md-4 control-label">Description</label>

                            <div id="description" class="col-md-6">
                                <textarea class="form-control" name="description">{{ old('description') }}</textarea>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    Create
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
