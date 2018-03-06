{{-- Editing the question. --}}
<modal name="update-issue" height="auto" v-cloak>
    <div class="p-6 py-8">
        <div class="mb-6 -mx-4">
            <div class="px-4 mb-6">
                <input type="text" class="w-full p-2 leading-normal" v-model="form.title">
                <span class="text-xs text-red" v-if="errors.title" v-text="errors.title[0]"></span>
            </div>

            <div class="px-4 mb-6">
                <wysiwyg v-model="form.description"></wysiwyg>
                <span class="text-xs text-red" v-if="errors.description" v-text="errors.description[0]"></span>
            </div>

            <div class="flex justify-between px-4">
                @can ('update', $issue)
                    <form action="{{ $issue->path() }}" method="POST" class="ml-a">
                        {{ csrf_field() }}
                        {{ method_field('DELETE') }}

                        <button type="submit" class="btn bg-red">Delete issue</button>
                    </form>
                @endcan

                <div>
                    <button class="btn mr-2" @click="resetForm">Cancel</button>
                    <button class="btn bg-blue" @click="update">Update</button>
                </div>
            </div>
        </div>
    </div>
</modal>


{{-- Viewing the question. --}}
<div class="">
    <div class="flex">
        <img src="{{ $issue->creator->avatar_path }}"
             alt="{{ $issue->creator->username }}"
             width="36"
             height="36"
             class="mr-1 bg-blue-darker rounded-full p-2">

        <div class="flex-1 border-b border-grey-lighter pb-8 ml-4">
            <h1 class="text-blue mb-2 text-2xl font-normal -mt-1" v-text="form.title"></h1>

            <p class="text-xs text-grey-darker mb-4">
                Posted by <a href="{{ route('profile', $issue->creator) }}" class="text-blue link">
                    {{ $issue->creator->username }} ({{ $issue->creator->reputation }} XP)
                </a>

                <span v-if="! editing">
                    <span v-if="(authorize('isAdmin') || authorize('owns', issue))">
                        <a href="#" class="text-blue link pl-2 ml-2 border-l" @click.prevent="editing = true">Edit</a>

                        <span v-if="authorize('isAdmin')">
                            <a href="#" class="link pl-2 ml-2 border-l" :class="locked ? 'font-bold' : ''" @click.prevent="toggleLock" v-text="locked ? 'Unlock' : 'Lock'"></a>
                            <a href="#" class="link pl-2 ml-2 border-l" :class="pinned ? 'font-bold' : ''" @click.prevent="togglePin" v-text="pinned ? 'Unpin' : 'Pin'"></a>
                        </span>
                    </span>

                    <subscribe-button :active="{{ json_encode($issue->isSubscribedTo) }}" v-if="signedIn"></subscribe-button>
                </span>
            </p>

            <highlight :content="form.description"></highlight>
        </div>
    </div>
</div>