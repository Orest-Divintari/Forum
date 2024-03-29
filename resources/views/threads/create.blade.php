@extends('layouts.app')

@section('head')
<script src="https://www.google.com/recaptcha/api.js"></script>
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Create a New Thread</div>

                <div class="card-body">
                    <form action="/threads" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="channel_id"></label>
                            <select name="channel_id" id="channel_id" class="form-control" required>
                                <option value="">Choose One...</option>

                                @foreach($channels as $channel)
                                <option value="{{ $channel->id }}"
                                    {{ old('channel_id') == $channel->id ? 'selected' : '' }}> {{ $channel->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="title">Title:</label>
                            <input type="text" class="form-control" name="title" id="title" required
                                value="{{ old('title') }}">
                        </div>
                        <div class="form-group">
                            <label for="body">Body:</label>
                            <wysiwyg name="body"></wysiwyg>
                        </div>

                        <div class="g-recaptcha" data-sitekey="{{ config('insomnia.recaptcha.site_key') }}"></div>
                        <div class="mt-2 form-group">
                            <button type="submit" class="btn btn-primary">Publish</button>
                        </div>

                        @if(count($errors))
                        <ul class=" alert alert-danger">
                            @foreach($errors->all() as $error)
                            <li> {{ $error }} </li>
                            @endforeach
                        </ul>
                        @endif
                    </form>


                </div>
            </div>
        </div>
    </div>
</div>
@endsection