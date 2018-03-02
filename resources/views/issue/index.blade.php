@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                @include('issue._list')

                <div class="mt-4">{{ $issues->render() }}</div>
            </div>

            <div class="col-md-4">
                <div class="card mb-4">
                    <div class="card-header">
                        Search
                    </div>
                    <div class="card-body">
                        <form method="GET" action="/issues/search">
                            <input type="text" name="q" class="form-control" placeholder="Search for something..." />
                        </form>

                        <img src="https://www.algolia.com/static_assets/images/press/downloads/search-by-algolia.svg" height="40" class="mt-4" />
                    </div>
                </div>


                @if (count($trending))
                    <div class="card">
                        <div class="card-header">
                            Trending issues
                        </div>
                        <div class="card-body">
                            <ul class="list-group">
                                @foreach($trending as $issue)
                                    <li class="list-group-item">
                                        <a href="{{ url($issue->path) }}">{{ $issue->title }}</a>
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
