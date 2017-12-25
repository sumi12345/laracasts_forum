@extends('layout')

@section('content')
    <div class="row">
        <div class="col-md-8">

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">{{ $thread->title }}</h3>
                </div>
                <div class="panel-body">
                    {{ $thread->body }}
                </div>
            </div>

            @foreach ($replies as $reply)
                <div class="media">
                    <div class="media-left">
                        <a href="#">
                            <img class="media-object" src="" alt="">
                        </a>
                    </div>
                    <div class="media-body">
                        <div class="media-heading level">
                            <h4 class="flex">
                                <a href="{{ route('profile', $reply->owner->name) }}">{{ $reply->owner->name }}</a>
                                <small>said {{ $reply->created_at->diffForHumans() }}</small>
                            </h4>
                            <form method="POST" action="/replies/{{ $reply->id }}/favorites">
                                {{ csrf_field() }}
                                <button type="submit" class="btn btn-info" {{ $reply->isFavorited() ? 'disabled' : '' }}>
                                    {{ $reply->favorites_count }} 赞
                                </button>
                            </form>
                        </div>
                        {{ $reply->body }}
                    </div>
                </div>
                <p><hr></p>
            @endforeach
            {!! $replies->render() !!}

            @if (auth()->check())
            <form method="POST" action="{{ $thread->path() . '/replies' }}">
                <div class="form-group">
                    <textarea name="body" id="body" rows="3" class="form-control" placeholder="说点什么?"></textarea>
                </div>
                <button type="submit" class="btn btn-primary">发布</button>
            </form>
            @else
            <div><p>要发表评论请先 <a href="{{ url('auth/login') }}">登录</a></p></div>
            @endif

        </div>

        <div class="col-md-4">

            <div class="panel panel-default">
                <div class="panel-heading">About</div>
                <div class="panel-body">
                    <p>
                        由 <a href="{{ route('profile', $thread->creator->name) }}">{{ $thread->creator->name }}</a>
                        在 {{ $thread->created_at }} 发布,
                        有 {{ $thread->replies_count }} 条评论.
                    </p>

                    @can ('update', $thread)
                    <form action="{{ $thread->path() }}" method="POST">
                        {{ csrf_field() }}
                        {{ method_field('DELETE') }}
                        <button type="submit" class="btn btn-default">删除</button>
                    </form>
                    @endcan

                </div>
            </div>

        </div>
    </div>

@endsection