<div class="media">
    <div class="media-left">
        <a href="#">
            <img class="media-object" src="" alt="">
        </a>
    </div>
    <div class="media-body">
        <div class="media-heading level">
            <h4 class="flex">
                <a href="{{ route('profile', $profileUser->name) }}">{{ $profileUser->name }}</a>
                <small>{{ $activity->subject->created_at }} published </small>
                <a href="{{ $activity->subject->path() }}">{{ $activity->subject->title }}</a>
            </h4>
        </div>
        {{ $activity->subject->body }}
    </div>
</div>