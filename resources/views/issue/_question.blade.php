<div class="card card-default" v-if="editing">
    <div class="card-header">
        <div class="level">
            <span class="flex">
                <input type="text" value="{{ $issue->title }}" class="form-control" v-model="form.title" />
            </span>
        </div>
    </div>
    <div class="card-body">
        <wysiwyg name="description" v-model="form.description"></wysiwyg>
    </div>

    <div class="card-footer">
        <button class="btn btn-light btn-sm" @click="editing = true" v-if="!editing">Edit</button>
        <button class="btn btn-primary btn-sm" @click="update">Update</button>
        <button class="btn btn-link btn-sm" @click="cancel">Cancel</button>
    </div>
</div>

<div class="card card-default" v-else>
    <div class="card-header">
        <div class="level">
            <span class="flex">
              <img src="{{ $issue->creator->avatar_path }}" width="25" height="25" class="mr-1">
               <a href="/profiles/{{ $issue->creator->name }}">
               {{ $issue->creator->name }}
                </a> posted <span v-text="form.title"></span>
            </span>

            @can('update', $issue)
                <form method="POST" action="{{ $issue->path() }}">
                    {{ csrf_field() }}
                    {{ method_field('DELETE') }}

                    <button class="btn btn-link">Delete Issue</button>
                </form>
            @endcan
        </div>
    </div>

    <div class="card-body">
        <highlight :content="form.description"></highlight>
    </div>

    <div class="card-footer">
        <button class="btn btn-light btn-sm" @click="editing = true">Edit</button>
    </div>
</div>