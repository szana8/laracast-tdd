@extends('layouts.app')

@section('head')
    <link rel="stylesheet" href="/css/vendor/jquery.atwho.css">
@endsection

@section('content')
    <issue-view :issue="{{ $issue }}" inline-template>
        <div>
            @include('breadcrumbs')

            <div class="py-6 leading-normal">
                @include ('issue._question')

                <replies @added="repliesCount++" @removed="repliesCount--"></replies>
            </div>
        </div>
    </issue-view>
@endsection
