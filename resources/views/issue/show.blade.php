@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
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

                @foreach($replies as $reply)
                    @include('issue.reply', $reply)
                @endforeach

                <div class="mt-4">
                    {{ $replies->links() }}
                </div>

                @if (auth()->check())
                    <div class="mt-4">
                        <form method="POST" action="{{ $issue->path() . '/replies' }}">
                            {{ csrf_field() }}
                            <div class="form-group">
                            <textarea class="form-control" name="body" id="body" rows="5"
                                      placeholder="Have you say something?"></textarea>
                            </div>

                            <button class="btn btn-xs">Submit</button>
                        </form>
                    </div>
                @else
                    <p class="text-center">Please <a href="{{ route('login') }}">sign in</a> to participate this issue!</p>
                @endif
            </div>

            <div class="col-md-4">
                <div class="card card-default">
                    <div class="card-body">
                        This issue was created {{ $issue->created_at->diffForHumans() }} by
                        <a href="#">{{ $issue->creator->name }}</a> and currently has {{ $issue->replies_count }} {{ str_plural('comment', $issue->replies_count) }}.

                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
