<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">New Task</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="form-group">
                        <label for="task-name" class="col-form-label">Name:</label>
                        <input type="text" class="form-control" id="task-name" name="name"
                               oninput="this.value.length > 2
                               ? document.getElementById('add-task').removeAttribute('disabled')
                               : document.getElementById('add-task').setAttribute('disabled', 'disabled')" required>
                    </div>
                    @if (Auth::user()->isAdmin())

                    <div class="form-group">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="checkBox"
                                   onclick="this.checked
                                    ? document.getElementById('user-select').removeAttribute('disabled')
                                    : document.getElementById('user-select').setAttribute('disabled', 'disabled')">
                            <label class="form-check-label" for="checkBox">For user:</label>
                        </div>
                        <select class="form-control" id="user-select" name="user" disabled>
                            @foreach ($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    @endif
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-sm btn-primary add-task" id="add-task" disabled>Add task</button>
            </div>
        </div>
    </div>
</div>