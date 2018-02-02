@extends('layout')

@section('header')
    <link rel="stylesheet" href="{{ asset('css/jquery.atwho.min.css') }}">
@endsection

@section('content')
    <thread-view inline-template :initial-replies-count="{{ $thread->replies_count }}">

    <div class="row">
        <div class="col-md-8">

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        {{ $thread->title }}
                    </h3>
                </div>
                <div class="panel-body">
                    {{ $thread->body }}
                </div>
            </div>

            <replies :data="{{ json_encode($replies) }}" @added="repliesCount++" @removed="repliesCount--"></replies>
            {!! $replies_link !!}

        </div>

        <div class="col-md-4">

            <div class="panel panel-default">
                <div class="panel-heading">About</div>
                <div class="panel-body">
                    <p>
                        <a href="{{ route('profile', $thread->creator) }}">
                            <img src="{{ $thread->creator->avatar }}" height="25"> {{ $thread->creator->name }}
                        </a>
                        在 {{ $thread->created_at }} 发布,
                        有 <span v-text="repliesCount"></span> 条评论.
                    </p>

                    <div class="level">
                        @can ('update', $thread)
                            <form action="{{ $thread->path() }}" method="POST">
                                {{ csrf_field() }}
                                {{ method_field('DELETE') }}
                                <button type="submit" class="btn btn-default">删除</button>
                            </form>
                        @endcan

                        <subscribe :active="{{ $thread->isSubscribedTo ? 'true' : 'false' }}"></subscribe>
                    </div>
                </div>
            </div>

        </div>
    </div>
    </thread-view>

@endsection