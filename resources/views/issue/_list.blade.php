@forelse ($issues as $issue)
    <div class="flex {{ $loop->last ? '' : 'mb-6 pb-4' }}">
        <div class="mr-4">
            <img src="{{ $issue->creator->avatar_path }}"
                 alt="{{ $issue->creator->username }}"
                 class="w-8 h-8 bg-blue-darker rounded-full p-2">
        </div>

        <div class="flex-1 {{ $loop->last ? '' : 'border-b border-blue-lightest' }}">
            <h3 class="text-xl font-normal mb-2 tracking-tight">
                <a href="{{ $issue->path() }}" class="text-blue">
                    @if ($issue->pinned)
                        Pinned:
                    @endif

                    @if (auth()->check() && $issue->hasUpdatesFor(auth()->user()))
                        <strong>
                            {{ $issue->title }}
                        </strong>
                    @else
                        {{ $issue->title }}
                    @endif
                </a>
            </h3>

            <p class="text-2xs text-grey-darkest mb-4">
                Posted By: <a href="{{ route('profile', $issue->creator) }}" class="text-blue">{{ $issue->creator->username }}</a>
            </p>

            <issue-view :issue="{{ $issue }}" inline-template class="mb-6 text-grey-darkest leading-loose pr-8">
                <highlight :content="form.description"></highlight>
            </issue-view>

            <div class="flex items-center text-xs mb-6">
                <a class="btn bg-grey-light text-grey-darkest py-2 px-3 mr-4 text-2xs flex items-center" href="/issues/{{ $issue->category->slug }}">
                    <span class="rounded-full h-2 w-2 mr-2" style="background: {{ $issue->category->color }}"></span>

                    {{ ucwords($issue->category->name) }}
                </a>

                <span class="mr-2 flex items-center text-grey-darker text-2xs font-semibold mr-4">
                    @include ('svgs.icons.eye', ['class' => 'mr-2'])
                    {{ $issue->visits }} visits
                </span>

                <a href="{{ $issue->path() }}" class="mr-2 flex items-center text-grey-darker text-2xs font-semibold">
                    @include ('svgs.icons.book', ['class' => 'mr-2'])
                    {{ $issue->replies_count }} {{ str_plural('reply', $issue->replies_count) }}
                </a>

                <a class="btn ml-auto is-outlined text-grey-darker py-2 text-xs" href="{{ $issue->path() }}">read more</a>
            </div>
        </div>
    </div>
@empty
    <p>There are no relevant results at this time.</p>
@endforelse