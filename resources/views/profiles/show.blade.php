@extends ('layouts.app')


@section ('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8 ">
            <div class="page-header">
                <h1>
                    {{ $profileUser->name }}
                </h1>
            </div>

            @forelse($activities as $date => $records)
            <h3 class="page-header"> {{$date}} </h3>
            @foreach($records as $activity)
            @if(view()->exists("profiles.activities.{$activity->type}"))
            @include("profiles.activities.{$activity->type}")
            @endif
            @endforeach
            @empty
            <p>No activity yet</p>
            @endforelse
            <!-- @include("profiles.activities.test") -->
            <!-- @foreach($threads as $thread)
                <div class="card mt-3">


                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <a href=""> {{$thread->creator->name}} </a>
                                posted: <a href="{{ $thread->path() }}">{{ $thread->title }}</a>
                            </div>
                            <div>
                                {{ $thread->created_at->diffForHumans() }}
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        {{ $thread->body }}
                    </div>

                </div>
                @endforeach -->

            <!-- <div class="mt-5">
                    {{ $threads->links() }}
                </div> -->

        </div>
        <div class="col-md-4">
            <div class="page-header">
                <h1>Activity Log</h1>
            </div>


        </div>
    </div>



</div>
@endsection