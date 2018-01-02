<div class="media">
    <div class="media-left">
        <a href="#">
            <img class="media-object" src="" alt="">
        </a>
    </div>
    <div class="media-body">
        <div class="media-heading level">
            <h4 class="flex">
                <a href="{{ $activity->subject->favorited->path() }}">
                    {{ $profileUser->name }}
                    <small>{{ $activity->created_at }} favorited a reply. </small>
                </a>
            </h4>
        </div>
        {{ $activity->subject->favorited->body }}
    </div>
</div>