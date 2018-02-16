@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card card-default">
                    <div class="card-header">Issues</div>

                    <div class="card-body">
                        @foreach($issues as $issue)
                            <article>
                                <div class="level">
                                    <h4 class="flex">
                                        <a href="{{ $issue->path() }}">{{ $issue->summary }}</a>
                                    </h4>
                                    <a href="{{ $issue->path() }}">
                                        {{ $issue->replies_count }} {{ str_plural('reply', $issue->replies_count) }}
                                    </a>
                                </div>

                                <div class="body">{{ $issue->description }}</div>
                            </article>
                            <hr>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
