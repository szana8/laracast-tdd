@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <h2 class="display-5">
                    {{ $profileUser->name }}
                    <small class="text-muted"> Since {{ $profileUser->created_at->diffForHumans() }}</small>
                </h2>

                @foreach($issues as $issue)
                    <div class="card card-default mt-4">
                        <div class="card-header">
                            <div class="level">
                                <span class="flex">
                                    <a href="#">{{ $issue->creator->name }}</a> posted:
                                    <a href="{{ $issue->path() }}">{{ $issue->summary }}</a>
                                </span>
                                <span>
                                    {{ $issue->created_at->diffForHumans() }}...
                                </span>
                            </div>
                        </div>

                        <div class="card-body">
                            {{ $issue->description }}
                        </div>
                    </div>
                @endforeach

                <div class="mt-4">
                    {{ $issues->links() }}
                </div>
            </div>
        </div>
    </div>


@endsection