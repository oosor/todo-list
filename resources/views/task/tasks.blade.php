<div class="row">
    <div class="col-md-12">
        <ul class="list-group">
            @foreach ($tasks as $task)
            <li class="list-group-item {{ $task->checked ? 'list-group-item-success' : ($task->user->id == $task->author->id ? 'list-group-item-info' : 'list-group-item-danger') }}">
                <div class="task-date text-muted">
                    @if ($task->checked)
                    <div class="task-date-resolved"><span>Resolved at:</span> <span class="text-success">{{ $task->resolved_at }}</span></div>
                    @endif
                    <div class="task-date-created"><span>Created at:</span> <span class="text-info">{{ $task->created_at }}</span></div>
                </div>
                <div class="custom-control">
                    <input type="checkbox" class="custom-control-input check-task" title=""{{ $task->checked ? ' checked' : '' }} {{ $user->isAdmin() && $user->id != $task->user->id ? 'disabled' : '' }}>
                    <input type="hidden" value="{{ $task->id }}">
                </div>
                <div class="title{{ $task->checked ? ' resolved' : '' }}">{{ $task->name }}</div>
                <div class="task-user text-muted">
                    <div class="task-detail"><span>Created by:</span> <span class="text-warning"><strong>{{ $task->author['name'] }}</strong>{!! ($task->author->id != $task->user->id ? (' for <strong>'.$task->user['name']).'</strong>' : '') !!}</span></div>
                </div>
            </li>
            @endforeach
        </ul>
    </div>
</div>
