@extends('layout')

@section('content')

@foreach ($threads as $thread)
<div class="row">
    <div class="col-md-8 col-md-offset-2">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title level">
                    <a href="{{ $thread->path() }}" class="flex">
                        @if (auth()->check() && $thread->hasUpdatesFor())
                            <strong>{{ $thread->title }}</strong>
                        @else
                            {{ $thread->title }}
                        @endif
                    </a>
                    <small><a href="{{ $thread->path() }}">{{$thread->replies_count}} 回复 </a></small>
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