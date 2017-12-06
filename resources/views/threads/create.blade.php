@extends('layout')

@section('content')
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Create a thread</div>
                <div class="panel-body">
                    <form method="POST" action="/threads">
                        {{ csrf_field() }}

                        <div class="form-group">
                            <label for="channel_id">Channel:</label>
                            <select name="channel_id" id="channel_id" class="form-control">
                                <option value="">choose a channel</option>
                                @foreach(App\Channel::all() as $channel)
                                    <option value="{{$channel->id}}" {{ old('channel_id') == $channel->id ? 'selected' : '' }}>{{$channel->name}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="title">Title:</label>
                            <input type="text" name="title" class="form-control" value="{{old('title')}}"/>
                        </div>

                        <div class="form-group">
                            <label for="body">Body:</label>
                            <textarea name="body" class="form-control" rows="8">{{old('body')}}</textarea>
                        </div>

                        <button type="submit" class="btn btn-primary">发布</button>
                        
                    </form>
                </div>
            </div>

            @if (count($errors))
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li> {{ $error }} </li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    </div>
@endsection