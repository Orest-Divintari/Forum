@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 mt-3">

            <search></search>
        </div>
        <div class="col-md-4 mt-3">

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