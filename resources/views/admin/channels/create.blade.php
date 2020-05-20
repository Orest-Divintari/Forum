@extends('admin.layouts.app')

@section('administration-content')
<form action="{{route('admin.channels.store')}}" method="POST">
    @csrf
    <div class="form-group">
        <label for="name">Name</label>
        <input type="input" class="form-control" id="name" name="name" required>
    </div>
    <div class="form-group">
        <label for="description">Description</label>
        <input type="input" class="form-control" id="description" name="description" required>
    </div>
    <button type="submit" class="btn btn-primary">Create</button>
</form>


@if($errors->any())
@foreach($errors->all() as $error)
<p class="text-danger">{{ $error }}</p>
@endforeach
@endif

@endsection