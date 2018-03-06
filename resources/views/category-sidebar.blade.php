<div class="border-l border-r p-6 bg-white w-48">
    <div class="widget">
        <h4 class="widget-heading">Categories</h4>

        <ul class="list-reset">
            @foreach ($categories as $category)
                <li class="text-xs pb-3 flex">
                    <span class="rounded-full h-3 w-3 mr-2" style="background: {{ $category->color }}"></span>

                    <a href="{{ route('categories', $category) }}" class="link">
                        {{ ucwords($category->name) }}
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
</div>