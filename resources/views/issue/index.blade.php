@extends('layouts.app')

@section('content')
    @include('breadcrumbs')

    <div class="pt-6">
        @include ('issue._list')

        {{ $issues->render() }}
    </div>
@endsection
