@extends('layout')

@section('content')

<div class="row">
    <div class="col-md-8">
        @foreach ($threads as $thread)
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
                <div class="panel-footer small">
                    {{ $thread->visits }} 阅读
                </div>
            </div>
        @endforeach
    </div>

    <div class="col-md-4">
        <div class="panel panel-default">
            <!-- Default panel contents -->
            <div class="panel-heading">热门推荐</div>

            <!-- List group -->
            <ul class="list-group">
                @foreach ($trending as $thread)
                    <li class="list-group-item">
                        <a href="{{ $thread->path }}">{{ $thread->title }}</a>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>
@endsection