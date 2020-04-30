<reply :attributes="{{$reply}}" inline-template v-cloak>
    <div id="reply-{{$reply->id}}" class="card mt-3">

        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <a href="{{ route('profile', $reply->creator->name) }}">
                        {{ $reply->creator->name }}
                    </a> said {{ $reply->created_at->diffForHumans() }}...
                </div>


                <div class="d-flex">
                    @auth
                    <favorite-button :current_reply="{{$reply}}"></favorite-button>
                    @endauth
                    @can('update', $reply)
                    <button @click="destroy" class="btn btn-danger">Delete</button>
                    <button v-if="editing" class="ml-2 btn btn-primary" @click="update">Update</button>
                    <button v-else="editing" class="ml-2 btn btn-light" @click="edit">Edit</button>
                    @endcan
                </div>

            </div>
        </div>

        <div>
            <div class="form-group" v-if="editing">
                <textarea style="resize:none" class=" border-0 form-control" v-model="reply.body"></textarea>
            </div>
            <div class="card-body" v-else>
                @{{reply.body}}
            </div>
            <div>
                <button v-show="editing" class="btn btn-link" @click="editing = false">Cancel</button>

            </div>
        </div>


    </div>
</reply>