@extends('layouts.app')

@section('head')
<script type="text/javascript">
window.thread = @json($thread);
</script>
@endsection

@section('content')

<thread :thread="{{$thread}}" inline-template>
    <div class="row ml-1">
        <div class="col-sm-8">

            @include('threads._topic')
            <div>
                <replies @added="repliesCounter++" @deleted="repliesCounter--">
                    <replies>
            </div>



        </div>

        <div class="col-md-4">
            <div class="pr-4">
                <div class="card">
                    <div class="card-body">
                        <p>
                            This thread was published {{ $thread->created_at->diffForHumans() }} by
                            <a href="">{{ $thread->creator->name }} </a> and currently has @{{ repliesCounter }}
                            {{ Str::plural('comment', $thread->replies_count) }}
                        </p>
                        <div class="d-flex">
                            <subscribe-button v-if="signedIn" :active="{{ json_encode($thread->isSubscribedTo) }}">
                            </subscribe-button>
                            <button v-if="authorize('isAdmin') && locked == false" @click="lock"
                                class="btn btn-light">Lock</button>

                            <button v-if="authorize('isAdmin') && locked == true" @click="unlock"
                                class="btn btn-light">Unlock</button>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</thread>
@endsection