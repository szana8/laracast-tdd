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

    </div>
@endsection
