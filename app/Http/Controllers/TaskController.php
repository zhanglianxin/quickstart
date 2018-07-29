<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Task;
use App\Task1;
use App\Repositories\TaskRepository;

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
        $tasks1 = Task1::forUser($request->user());
        dd($tasks, $tasks1);
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

    // public function destory(Request $request, $taskId) {
    public function destory(Request $request, Task $task) {
        // 授权策略
        $result = $this->authorize('destroy', $task);

        if ($result) {
            $task->delete();
            return redirect('/tasks');
        } else {
            return redirect()->back()->withErrors('删除失败！');
        }
    }
}
