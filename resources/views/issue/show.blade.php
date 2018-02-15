@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card card-default">
                    <div class="card-header">
                        <a href="#">
                            {{ $issue->creator->name }}
                        </a> posted
                        {{ $issue->summary }}
                    </div>
                    <div class="card-body">
                        {{ $issue->description }}
                    </div>
                </div>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-md-8 mt-4">
                @foreach($issue->replies as $reply)
                    @include('issue.reply', $reply)
                @endforeach
            </div>
        </div>

        @auth
            <div class="row justify-content-center">
                <div class="col-md-8 mt-4">
                    <form method="POST" action="{{ $issue->path() . '/replies' }}">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <textarea class="form-control" name="body" id="body" rows="5" placeholder="Have you say something?"></textarea>
                        </div>

                        <button class="btn btn-xs">Submit</button>
                    </form>
                </div>
            </div>
        @endauth
        @if (! auth()->check())
            <p class="text-center">Please <a href="{{ route('login') }}">sign in</a> to participate this issue!</p>
        @endif
    </div>
@endsection
