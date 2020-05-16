@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8">
            @include('threads._list')
            <div>
                {{ $threads->links() }}
            </div>
        </div>

        <div class="col-md-4 mt-3">
            <div class="card">
                <div class="card-header">Search</div>
                <div class="card-body">
                    <form action="/threads/search" method="GET">
                        @csrf
                        <div class="form-group">
                            <input class="form-control" placeholder="Search for somthing..." name="q" type="text">
                        </div>
                        <button class="btn btn-primary btn-sm">Search</button>
                    </form>

                </div>
            </div>
            @if(count($trending_threads))
            <div class="card">
                <div class="card-header">Trending Threads</div>
                <ul class="list-group">
                    @foreach($trending_threads as $trending_thread)
                    <li class="list-group-item">
                        <a href="{{ $trending_thread->path }}"> {{ $trending_thread->title}}</a>
                    </li>
                    @endforeach
                </ul>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection