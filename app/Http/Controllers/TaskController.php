<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Task;

use App\Repositories\TaskRepository;

use App\Http\Requests;

class TaskController extends Controller
{
    /**
     * 任务资源库的实例。
     *
     * @var TaskRepository
     */
    protected $tasks;

    public function __construct(TaskRepository $tasks) {
        $this->middleware('auth');

        $this->tasks = $tasks;
    }

    public function index(Request $request) {
        $tasks = $this->tasks->forUser($request->user());

        return view('tasks.index', ['tasks' => $tasks]);
    }

    public function store(Request $request) {
        $this->validate($request, [
            'name' => 'required|max:255'
        ]);

        $result = $request->user()->tasks()->create([
            'name' => $request->name,
        ]);

        if ($request) {
            return redirect('/tasks');
        } else {
            return redirect()->back()->withInput()->withErrors('保存失败！');
        }
    }
}
