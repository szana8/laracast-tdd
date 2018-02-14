@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card card-default">
                    <div class="card-header">Project Categories</div>
                    <div class="card-body">
                        @foreach ($categories as $category)
                            <article>
                                <h4>
                                    <a href="{{ $category->path() }}">{{ $category->name }}</a>
                                </h4>
                                <div class="body">{{ $category->description }}</div>
                            </article>

                            <hr>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
