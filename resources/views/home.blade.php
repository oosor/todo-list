@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="header">
                    <div class="panel-heading" id="header-count"><span class="name">Todo-list: <span>{{ Auth::user()->name }}</span></span>
                        @if ($tasks->isNotEmpty())
                        <small>Found <span class="count">{{ $tasks->count() }}</span> task(s)</small>
                        @endif
                    </div>

                    <div class="panel-heading float-right">
                        <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#exampleModal"
                                onclick="document.getElementById('task-name').value = '';
                                    document.getElementById('checkBox').checked = false;
                                    document.getElementById('user-select').setAttribute('disabled', 'disabled')">
                            Add task
                        </button>
                        @include('task.create-task')
                        @if (Auth::user()->isAdmin())
                        <div class="dropdown">
                            <button type="button" class="btn btn-sm btn-primary dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                Select user task
                                <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu" role="menu">
                                <li class="bg-info"><a href="#admin" class="user-task">My tasks</a></li>
                                <li class="bg-warning"><a href="#all" class="user-task">All users tasks</a></li>
                                @foreach ($users as $user)
                                <li><a href="#user-{{ $user->id }}" class="user-task">{{ $user->name }}</a></li>
                                @endforeach
                            </ul>
                        </div>
                        @endif
                    </div>
                </div>

                <div class="panel-body" id="task-container">
                    <div class="tasks"></div>
                    <div class="load-task"></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
