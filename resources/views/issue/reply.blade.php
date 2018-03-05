<reply :attributes="{{ $reply }}" inline-template v-cloak>
    <div id="reply-{{ $reply->id }}" class="card card-default mt-4">
        <div class="card-header">
            <div class="level">
                <h5 class="flex">
                    <a href="/profiles/{{ $reply->owner->name }}">
                        {{ $reply->owner->name }}
                    </a>
                    <small>said {{ $reply->created_at->diffForHumans() }}...</small>
                </h5>

                @auth
                    <div>
                        <favorite :reply="{{ $reply }}"></favorite>
                    </div>
                @endauth
            </div>
        </div>
        <div class="card-body">
            <div v-if="editing">
                <div class="form-group">
                    <textarea class="form-control" v-model="body"></textarea>
                </div>
                <button class="btn btn-primary btn-sm" @click="update">Update</button>
                <button class="btn btn-link btn-sm" @click="editing = false">Cancel</button>
            </div>

            <div v-else v-html="body" class="trix-content"></div>
        </div>

        @can('update', $reply)
            <div class="card-footer level">
                <button class="btn btn-default btn-sm mr-1" @click="editing = true">Edit</button>
                <button class="btn btn-danger btn-sm mr-1" @click="destroy">Delete</button>
            </div>
        @endcan
    </div>
</reply>