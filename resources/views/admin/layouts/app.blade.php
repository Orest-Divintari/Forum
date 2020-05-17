@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-2">
            <ul class="nav flex-column">
                <li class="nav-item"><a href="{{ route('admin.dashboard.index') }}">Dashboard</a></li>
                <li class="nav-item"><a href="{{ route('admin.channels.index') }}">Channels</a></li>
            </ul>
        </div>
        <div class="col-md-10">
            <div class="card">
                <div class="card-body">
                    @yield('administration-content')
                </div>
            </div>
        </div>
    </div>
</div>


@endsection