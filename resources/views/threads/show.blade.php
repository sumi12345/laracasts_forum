@extends('layout')

@section('content')
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">{{ $thread->title }}</h3>
                </div>
                <div class="panel-body">
                    {{ $thread->body }}
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            @foreach ($thread->replies as $reply)
            <div class="media">
                <div class="media-left">
                    <a href="#">
                        <img class="media-object" src="" alt="">
                    </a>
                </div>
                <div class="media-body">
                    <h4 class="media-heading">
                        <a href="#">{{ $reply->owner->name }}</a>
                        <small>said {{ $reply->created_at->diffForHumans() }}</small>
                    </h4>
                    {{ $reply->body }}
                </div>
            </div>
            <p><br></p>
            @endforeach
        </div>
    </div>
@endsection