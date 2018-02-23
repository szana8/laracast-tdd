@extends('layouts.app')

@section('header')
    <link rel="stylesheet" href="/css/vendor/jquery.atwho.css">
@endsection

@section('content')
    <issue-view :initial-replies-count="{{ $issue->replies_count }}" inline-template>
        <div class="container">
            <div class="row">
                <div class="col-md-8">
                    <div class="card card-default">
                        <div class="card-header">
                            <div class="level">
                           <span class="flex">
                               <a href="/profiles/{{ $issue->creator->name }}">
                               {{ $issue->creator->name }}
                           </a> posted
                               {{ $issue->summary }}
                           </span>

                                @can('update', $issue)
                                    <form method="POST" action="{{ $issue->path() }}">
                                        {{ csrf_field() }}
                                        {{ method_field('DELETE') }}

                                        <button class="btn btn-link">Delete Issue</button>
                                    </form>
                                @endcan
                            </div>
                        </div>
                        <div class="card-body">
                            {{ $issue->description }}
                        </div>
                    </div>

                    <replies @added="repliesCount++" @removed="repliesCount--"></replies>

                </div>

                <div class="col-md-4">
                    <div class="card card-default">
                        <div class="card-body">
                            <p>
                                This issue was created {{ $issue->created_at->diffForHumans() }} by
                                <a href="/profiles/{{ $issue->creator->name }}">{{ $issue->creator->name }}</a> and currently
                                has <span v-text="repliesCount"></span> {{ str_plural('comment', $issue->replies_count) }}.
                            </p>

                            <p>
                                <subscribe-button :active="{{ json_encode($issue->isSubscribedTo) }}"></subscribe-button>
                            </p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
@endsection
