@if (auth()->check())
    <modal name="new-thread" height="auto" transition="slide">
        <form method="POST" action="/issues" class="p-6 py-8">
            {{ csrf_field() }}

            <div class="flex mb-6 -mx-4">
                <div class="flex-1 px-4">
                    <label for="title" class="block uppercase tracking-wide text-grey-darker text-xs font-bold mb-2">Title</label>
                    <input type="text" class="w-full p-2 leading-normal" id="title" name="title" value="{{ old('title') }}" required>
                </div>

                <div class="flex-1 px-4">
                    <label for="category_id" class="block uppercase tracking-wide text-grey-darker text-xs font-bold mb-2">category</label>

                    <select name="category_id" id="category_id" class="block appearance-none w-full bg-white rounded-none border border-grey-light text-grey-darker py-2 px-4 leading-normal pr-8" required>
                        <option value="">Choose One...</option>

                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="mb-6">
                <wysiwyg name="description"></wysiwyg>
            </div>

            <div class="mb-6">
                <div class="g-recaptcha" data-sitekey="{{ config('services.recaptcha.key') }}"></div>
            </div>

            <div class="flex justify-end">
                <a href="#" class="btn mr-4" @click="$modal.hide('new-issue')">Cancel</a>
                <button type="submit" class="btn is-green">Publish</button>
            </div>

            @if (count($errors))
                <ul class="alert alert-danger">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            @endif
        </form>
    </modal>
@endif