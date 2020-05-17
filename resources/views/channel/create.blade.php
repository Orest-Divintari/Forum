<form action="{{route('admin.channels.store'}}" method="POST">
    @csrf
    <div class="form-group">
        <label for="name">Name</label>
        <input type="input" class="form-control" id="name">
    </div>
    <div class="form-group">
        <label for="description">Description</label>
        <input type="input" class="form-control" id="description">
    </div>
    <button type="submit" class="btn btn-primary">Create</button>
</form>