@component('profiles.activities.activity')
@slot('heading')
<a href=""> {{$profileUser->name}} </a>
created thread <a href="{{ $activity->subject->path() }}">{{$activity->subject->title}}</a>
@endslot

@slot('time')
{{ $activity->subject->created_at->diffForHumans() }}
@endslot

@slot('body')
{{ $activity->subject->body }}
@endslot
@endcomponent

<!-- <div class="card mt-3">        
    <div class="card-header"> 
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <a href=""> {{$profileUser->name}} </a>
                created thread <a href="{{ $activity->subject->path() }}">{{$activity->subject->title}}</a>
            </div>
            <div>
                {{ $activity->subject->created_at->diffForHumans() }}
            </div>
        </div>
    </div>

    <div class="card-body">
        {{ $activity->subject->body }}
    </div>

</div> -->