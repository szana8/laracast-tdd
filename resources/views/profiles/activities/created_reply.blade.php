@component('profiles.activities.activity')
    @slot('heading')
        <a href="#">{{ $profileUser->name }}</a> replied to
        <a href="{{ $activity->subject->issue->path() }}">{{ $activity->subject->issue->summary }}</a>
    @endslot

    @slot('body')
        {{ $activity->subject->body }}
    @endslot
@endcomponent