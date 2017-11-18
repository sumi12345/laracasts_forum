@extends('layout')

@section('content')

@foreach ($threads as $thread)
<div class="row">
    <div class="col-md-8 col-md-offset-2">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">
                    <a href="{{ $thread->path() }}">{{ $thread->title }}</a>
                    <small>posted {{ $thread->created_at }}</small>
                </h3>
            </div>
            <div class="panel-body">
                {{ $thread->body }}
            </div>
        </div>
    </div>
</div>
@endforeach

@endsection