@extends('layout')

@section('content')
    <div class="page-header">
        <h1>
            {{ $profileUser->name }}
            <small>since {{ $profileUser->created_at->diffForHumans() }} </small>
        </h1>
        <avatar :user="{{ $profileUser->toJson() }}"></avatar>
    </div>

    <div class="row">
        <div class="col-md-8">
            @foreach ($activities_by_date as $date => $activities)
                <h3 class="page-header">{{ $date }}</h3>

                @foreach ($activities as $activity)
                    @if (view()->exists("profiles.activities.$activity->type"))
                        @include ("profiles.activities.$activity->type")
                    @endif
                @endforeach
            @endforeach
        </div>
    </div>
@endsection