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
                <div class="card-header">Trending Threads</div>
                <ul class="list-group">
                    @forelse($trending_threads as $trending_thread)
                    <li class="list-group-item">
                        <a href="{{ $trending_thread->path }}"> {{ $trending_thread->title}}</a>
                    </li>
                    @empty
                    <p>No trending threads yet</p>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection