@extends('layouts.app')

@section('header')
    <link rel="stylesheet" href="/css/vendor/jquery.atwho.css">
@endsection

@section('content')
    <issue-view :issue="{{ $issue }}" inline-template>
        <div class="container">
            <div class="row">
                <div class="col-md-8">
                    <div class="card card-default">
                        <div class="card-header">
                            <div class="level">
                            <span class="flex">
                              <img src="{{ $issue->creator->avatar_path }}" width="25" height="25" class="mr-1">
                               <a href="/profiles/{{ $issue->creator->name }}">
                               {{ $issue->creator->name }}
                                </a> posted
                               {{ $issue->title }}
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
                                <subscribe-button :active="{{ json_encode($issue->isSubscribedTo) }}" v-if="signedIn"></subscribe-button>

                                <button class="btn btn-default"
                                        v-show="authorize('isAdmin')"
                                        @click="toggleLock"
                                        v-text="locked ? 'Unlock' : 'Lock'">
                                </button>
                            </p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
@endsection
