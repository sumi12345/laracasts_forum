@extends('layout')

@section('content')

@foreach ($threads as $thread)
<div class="row">
    <div class="col-md-8 col-md-offset-2">
        <div class="panel panel-default">
            <div class="panel-heading">
                <a href="{{ $thread->path() }}">
                    <h3 class="panel-title">{{ $thread->title }}</h3>
                </a>
            </div>
            <div class="panel-body">
                {{ $thread->body }}
            </div>
        </div>
    </div>
</div>
@endforeach

@endsection