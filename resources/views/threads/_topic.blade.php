<!-- Editing the topic -->
<div v-if="editing && authorize('owns', thread)" class="card">
    <div class="card-header">
        <div class="form-group">
            <input v-model="form.title" class="border-0 form-control" type="text">
        </div>
    </div>

    <div class="card-body">
        <div class="form-group">
            <wysiwyg v-model="form.body"></wysiwyg>
        </div>
    </div>

    <div class="card-footer">
        <div class="d-flex">
            <div class="flex-grow-1">
                <button @click="update" class="btn btn-primary btn-sm">Update</button>
                <button @click="cancel" class="btn btn-secondary btn-sm">Cancel</button>
            </div>
            <button @click="destroy" class="btn btn-danger btn-sm">Delete</button>
        </div>
    </div>
</div>

<!-- Viewing the section -->

<div v-else class="card">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <div class="d-flex">
                <img class="mr-2" src="{{ $thread->creator->avatar_path }}" width="25" height="25" alt="">
                <a href="{{route('profile', $thread->creator->name )}}">
                    @{{thread.creator.name}} @{{thread.creator.reputation}} XP

                </a>
                <span class="ml-1">
                    posted: @{{title}}
                </span>
            </div>
        </div>
    </div>

    <div class="card-body">
        <highlight :content="body"></highlight>
    </div>

    <div v-if="authorize('owns', thread)" class="card-footer">
        <button @click="edit" class="btn btn-primary btn-sm">Edit</button>
    </div>
</div>