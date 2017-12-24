@extends('layout')

@section('content')
    <div class="page-header">
        <h1>
            {{ $profileUser->name }}
            <small>since {{ $profileUser->created_at->diffForHumans() }} </small>
        </h1>
    </div>


    <div class="row">
        <div class="col-md-8">

            @foreach( $threads as $thread )
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title level">
                        <a href="{{ $thread->path() }}" class="flex">{{ $thread->title }}</a>
                        <small><a href="{{ $thread->path() }}">{{$thread->replies_count}} 回复 </a></small>
                    </h3>
                </div>
                <div class="panel-body">
                    {{ $thread->body }}
                </div>
            </div>
            @endforeach

            {!! $threads->render() !!}

        </div>
    </div>
@endsection