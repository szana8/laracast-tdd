@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <ais-index
                    app-id="{{ config('scout.algolia.id') }}"
                    api-key="{{ config('scout.algolia.key') }}"
                    index-name="issues"
                    query="{{ request('q') }}"
                    class="col-md-12"
            >
                <div class="row">
                    <div class="col-md-8">
                        <ais-results>
                            <template slot-scope="{ result }">
                                <li>
                                    <a :href="result.path">
                                        <ais-highlight :result="result" attribute-name="title"></ais-highlight>
                                    </a>
                                </li>
                            </template>
                        </ais-results>
                    </div>

                    <div class="col-md-4">
                        <div class="card mb-4">
                            <div class="card-header">
                                Search
                            </div>
                            <div class="card-body">
                                <ais-search-box>
                                    <ais-input placeholder="Find an issue" autofocus="true" class="form-control"></ais-input>
                                </ais-search-box>

                                <img src="https://www.algolia.com/static_assets/images/press/downloads/search-by-algolia.svg" height="40" class="mt-4" />
                            </div>
                        </div>

                        <div class="card mb-4">
                            <div class="card-header">
                                Categories
                            </div>
                            <div class="card-body">
                                <ais-refinement-list attribute-name="category.name"></ais-refinement-list>
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

            </ais-index>
        </div>
    </div>
@endsection
