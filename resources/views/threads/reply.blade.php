<reply :attributes="{{ $reply }}" inline-template v-clock>
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

            <div v-if="editing">
                <div class="form-group">
                    <textarea v-model="body" rows="3" class="form-control"></textarea>
                </div>

                <button class="btn btn-success btn-xs" @click="update">提交</button>
                <button class="btn btn-default btn-xs" @click="editing = false">取消</button>
            </div>
            <div v-else v-text="body"></div>

            @can ('update', $reply)
                <div class="level mt-1">

                    <button type="button" class="btn btn-default mr-1" @click="editing = true">编辑</button>

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