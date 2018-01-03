<reply inline-template>
    <div class="media" id="reply-{{ $reply->id }}">
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

            @can ('update', $reply)
                <div class="level mt-1">

                    <form method="POST" action="/replies/{{ $reply->id }}" class="mr-1">
                        {{ csrf_field() }}
                        {{ method_field('PATCH') }}
                        <button type="submit" class="btn btn-success">编辑</button>
                    </form>

                    <form method="POST" action="/replies/{{ $reply->id }}">
                        {{ csrf_field() }}
                        {{ method_field('DELETE') }}
                        <button type="submit" class="btn btn-danger">删除</button>
                    </form>

                </div>
            @endcan
        </div>
    </div>
</reply>