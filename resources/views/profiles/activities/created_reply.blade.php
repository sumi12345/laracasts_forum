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
                <small>{{ $activity->created_at }} replied to </small>
                <a href="{{ $activity->subject->thread->path() }}">{{ $activity->subject->thread->title }}</a>
            </h4>
        </div>
        {{ $activity->subject->body }}
    </div>
</div>