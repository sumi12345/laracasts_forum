@extends('layout')

@section('header')
    <link rel="stylesheet" href="{{ asset('css/jquery.atwho.min.css') }}">
@endsection

@section('content')
    <thread-view :thread="{{ $thread }}" inline-template>

    <div class="row">
        <div class="col-md-8">

            <div v-if="editing" class="panel panel-default">
                <div class="panel-heading">
                    <input type="text" class="form-control" v-model="form.title">
                </div>
                <div class="panel-body">
                    <textarea name="body" rows="10" class="form-control" v-model="form.body"></textarea>
                </div>
                <div class="panel-footer">
                    <button @click="update" class="btn btn-success btn-xs">更新</button>
                    <button @click="editing = false" class="btn btn-default btn-xs">取消</button>
                </div>
            </div>

            <div v-else class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title" v-text="this.title"></h3>
                </div>
                <div class="panel-body" v-text="this.body"></div>
                <div class="panel-footer" v-if="authorize('updateThread', this.thread)">
                    <button @click="editing = true" class="btn btn-default btn-xs">编辑</button>
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

                        <subscribe v-if="signedIn" :active="{{ $thread->isSubscribedTo ? 'true' : 'false' }}"></subscribe>

                        <button v-if="authorize('isAdmin')" @click="toggleLock" v-text="locked ? '已禁言' : '禁言'" class="btn btn-default"></button>
                    </div>
                </div>
            </div>

        </div>
    </div>
    </thread-view>

@endsection