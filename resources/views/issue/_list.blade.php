@forelse($issues as $issue)
    <div class="card card-default mb-4">
        <div class="card-header">
            <div class="level">

                <div class="flex">
                    <h4 class="flex">
                        <a href="{{ $issue->path() }}">
                            @if (auth()->check() && $issue->hasUpdateFor(auth()->user()))
                                <strong>{{ $issue->summary }}</strong>
                            @else
                                {{ $issue->summary }}
                            @endif
                        </a>
                    </h4>

                    Posted by: <a href="{{ route('profile', $issue->creator) }}">{{ $issue->creator->name }}</a>
                </div>

                <a href="{{ $issue->path() }}">
                    {{ $issue->replies_count }} {{ str_plural('reply', $issue->replies_count) }}
                </a>
            </div>
        </div>

        <div class="card-body">
            <div class="body">{{ $issue->description }}</div>
            <hr>
        </div>

        <div class="card-footer">
            {{ $issue->visits }} visits
        </div>
    </div>
@empty
    <p>There are no relevant results at this time.</p>
@endforelse