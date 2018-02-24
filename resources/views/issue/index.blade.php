@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                @include('issue._list')

                <div class="mt-4">{{ $issues->render() }}</div>
            </div>

            <div class="col-md-4">
                @if (count($trending))
                    <div class="card">
                        <div class="card-header">
                            Trending issues
                        </div>
                        <div class="card-body">
                            <ul class="list-group">
                                @foreach($trending as $issue)
                                    <li class="list-group-item">
                                        <a href="{{ url($issue->path) }}">{{ $issue->summary }}</a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif
            </div>

        </div>
    </div>
@endsection
