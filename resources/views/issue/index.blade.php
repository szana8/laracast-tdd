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
                                <h4>
                                    <a href="{{ $issue->path() }}">{{ $issue->summary }}</a>
                                </h4>
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
