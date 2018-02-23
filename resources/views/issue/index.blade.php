@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                @include('issue._list')

                <div class="mt-4">{{ $issues->render() }}</div>
            </div>
        </div>
    </div>
@endsection
