@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">

            @forelse($threads as $thread)
            <div class="card mt-3">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="pt-2">
                            <a href="{{ $thread->path() }}">
                                {{ $thread->title }}
                            </a>
                        </h5>

                        <strong>
                            <a href="{{ $thread->path() }}">
                                {{ $thread->replies_count }} {{ Str::plural('reply', $thread->replies_count) }}
                            </a>
                        </strong>
                    </div>
                </div>


                <div class="card-body">

                    <article>

                        <div class="body"> {{ $thread->body }} </div>

                    </article>
                </div>
            </div>
            @empty
            <p>There are no relevant results at this time</p>
            @endforelse
        </div>
    </div>
</div>
@endsection