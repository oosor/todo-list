<?php

namespace App\Http\Controllers;

use App\Task;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TaskController extends Controller
{

    function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $tasks = [];
        if ((int) $request->input('id', -1) == 0 && $request->user()->isAdmin()) {
            $tasks = Task::with(['user' => function ($query) {
                $query->where('is_admin', 0);
            }, 'author'])->orderBy('created_at', 'desc')->get()->filter(function ($value) {
                return $value->user;
            });
        } else {
            $user = $this->_getUser($request);
            if ($user) {
                $tasks = $user->tasks()->with(['user', 'author'])->orderBy('created_at', 'desc')->get();
            }
        }

        return view($tasks->isEmpty() ? 'task.no-task' : 'task.tasks', ['user' => $request->user(), 'tasks' => $tasks]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = $this->_getUser($request);
        if ($user) {
            $task = new Task();
            $task->name = $request->input('name');
            //$task->timestamps = false;
            $user->tasks()->save($task);
            $request->user()->userTasks()->save($task);

            return json_encode(['status' => 'OK', 'data' => ['task' => $task->load('author')]]);
        }

        return json_encode(['status' => 'FAILED']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = $this->_getUser($request);
        $task = $user->isAdmin() ? Task::find($id) : $user->tasks()->find($id);

        if ($task) {
            $task->checked = $request->input('checked', false);
            $task->resolved_at = Carbon::now();
            $task->save();
            return json_encode(['status' => 'OK', 'data' => ['task' => $task->load(['user', 'author'])]]);
        }

        return json_encode(['status' => 'FAILED']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * get User
     *
     * @param Request $request
     * @return User
     * */
    private function _getUser(Request $request) {
        $id = (integer) $request->input('id', -1);
        return $id > 0 && $request->user()->isAdmin() ? User::find($id) : $request->user();
    }
}
