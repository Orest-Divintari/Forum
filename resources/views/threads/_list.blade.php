@forelse($threads as $thread)
<div class="card mt-3">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h4 class="pt-2">
                    <a href="{{ $thread->path() }}">
                        @if(auth()->check() && $thread->hasUpdatesFor())
                        <strong>
                            {{ $thread->title }}
                        </strong>
                        @else
                        <span class="text-muted">
                            {{ $thread->title }}
                        </span>
                        @endif
                    </a>
                </h4>
                <h6>
                    Posted By <a class="text-link"
                        href="{{ route('profile', $thread->creator )}}">{{$thread->creator->name}}</a>
                </h6>
            </div>


            <strong>
                <a href="{{ $thread->path() }}">
                    {{ $thread->replies_count }} {{ Str::plural('reply', $thread->replies_count) }}
                </a>
            </strong>
        </div>
    </div>


    <div class="card-body">
        <article>{{ $thread->body }}</article>
    </div>


    <div class="card-footer text-muted"> {{$thread->visits}} visits</div>
</div>
@empty
<p>There are no relevant results at this time</p>
@endforelse