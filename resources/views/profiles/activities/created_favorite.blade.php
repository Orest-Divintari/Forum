@component('profiles.activities.activity')
@slot('heading')
<a href=""> {{$profileUser->name}} </a>
liked a reply on post<a href="{{ $activity->subject->favoritable->path() }}">
    {{$activity->subject->favoritable->thread->title}} </a>
@endslot

@slot('time')
{{ $activity->subject->created_at->diffForHumans() }}
@endslot

@slot('body')
{{ $activity->subject->favoritable->body }}
@endslot
@endcomponent