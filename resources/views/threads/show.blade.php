@extends('layouts.app')

@section('content')

<thread inline-template :replies_count="{{ $thread->replies_count }}">
    <div class="row ml-1">
        <div class="col-sm-8">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <a href="{{route('profile', $thread->creator->name )}}">
                                {{ $thread->creator->name }}
                            </a>
                            posted: {{ $thread->title }}
                        </div>

                        @can('delete', $thread)
                        <div>
                            <form action="{{ $thread->path() }}" method="POST">
                                @csrf
                                @method('DELETE')

                                <button class="btn btn-link" type="submit">Delete</button>
                            </form>
                        </div>
                        @endcan
                    </div>
                </div>

                <div class="card-body">
                    {{ $thread->body }}
                </div>
            </div>

            <div>
                <replies @added="repliesCounter++" @deleted="repliesCounter--">
                    <replies>
            </div>
            <div class="mt-4">
                replies
            </div>





        </div>



        <div class="col-md-4">
            <div class="pr-4">
                <div class="card">
                    <div class="card-body">
                        This thread was published {{ $thread->created_at->diffForHumans() }} by
                        <a href="">{{ $thread->creator->name }} </a> and currently has @{{ repliesCounter }}
                        {{ Str::plural('comment', $thread->replies_count) }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</thread>
@endsection