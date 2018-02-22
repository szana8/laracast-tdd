@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                @forelse($issues as $issue)
                    <div class="card card-default mt-4">
                        <div class="card-header">
                            <div class="level">
                                <h4 class="flex">
                                    <a href="{{ $issue->path() }}">
                                        @if (auth()->check() && $issue->hasUpdateFor(auth()->user()))
                                            <strong>{{ $issue->summary }}</strong>
                                        @else
                                            {{ $issue->summary }}
                                        @endif
                                    </a>
                                </h4>
                                <a href="{{ $issue->path() }}">
                                    {{ $issue->replies_count }} {{ str_plural('reply', $issue->replies_count) }}
                                </a>
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="body">{{ $issue->description }}</div>
                            <hr>
                        </div>
                    </div>
                @empty
                    <p>There are no relevant results at this time.</p>
                @endforelse
            </div>
        </div>
    </div>
@endsection
