<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\Project;


class TaskController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     */

    public function index()
    {   
        $user = auth()->user();
        $projects = $user->projects()->get();

        $tasks = $user->tasks();
        $tasks = $tasks->orderBy('created_at', 'desc')->get();


        return view('/tasks/index', compact('user', 'projects', 'tasks'));
    }
    
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $validate = $request->validate([
            'project_id' => 'required|integer',
            'title' => 'required|string|max:255|',
            'description' => 'nullable',
        ]);

        $task = new Task();
        $task->project_id = $validate['project_id'];
        $task->user_id = auth()->user()->id;
        $task->title = $validate['title'];
        $task->description = $validate['description'];
        $task->save();

        return back()->with('success', 'Task created successfully.');

    }

    /**
     * Display the specified resource.
     */
    public function show($projectId)
    {
        //
        if ($projectId == "all") {
            $user = auth()->user();
            $tasks = $user->tasks()->with('project')->get();

            $data = [];

            foreach ($tasks as $task) {
                $data[] = [
                    'id' => $task->id,
                    'title' => $task->title,
                    'description' => $task->description,
                    'project' => $task->project,
                    'status' => $task->status ? 'Completed' : 'Incomplete',
                    'toggle' => '<button class="btn btn-sm toggle-btn ' . ($task->status ? 'btn-success' : 'btn-secondary') . 
                                '" data-id="' . $task->id . '">' . ($task->status ? 'Completed' : 'Incomplete') . '</button>',
                    'action' => '<div class="btn-group">
                                    <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Action
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-right">
                                    <a class="dropdown-item" href="'. url('tasks/' . $task->id.'/view') .' ">View</a>
                                    <a class="dropdown-item" href="'. url('tasks/' . $task->id.'/edit') .' ">Edit</a>
                                        <a class="dropdown-item delete-task-btn" data-url="'. url('tasks/' . $task->id.'/delete') .' " data-id="' . $task->id . '" >Delete</a>
                                    </div>
                                </div>'
                ];
                
            }

            return response()->json([
                'data' => $data
            ]);
        }

        $user = auth()->user();
        $tasks = $user->tasks()->with('project')->where('project_id',$projectId)->get();

        $data = [];

        foreach ($tasks as $task) {
            $data[] = [
                'id' => $task->id,
                'title' => $task->title,
                'description' => $task->description,
                'project' => $task->project,
                'status' => $task->status ? 'Completed' : 'Incomplete',
                'toggle' => '<button class="btn btn-sm toggle-btn ' . ($task->status ? 'btn-success' : 'btn-secondary') . '" 
                            data-id="' . $task->id . '">' . ($task->status ? 'Completed' : 'Incomplete') . '</button>',
                'action' => '<div class="btn-group">
                                <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Action
                                </button>
                                <div class="dropdown-menu dropdown-menu-right">
                                    <a class="dropdown-item" href="'. url('tasks/' . $task->id.'/view') .' ">View</a>
                                    <a class="dropdown-item" href="'. url('tasks/' . $task->id.'/edit') .' ">Edit</a>
                                    <a class="dropdown-item delete-task-btn" data-url="'. url('tasks/' . $task->id.'/delete') .' " data-id="' . $task->id . '" >Delete</a>
                                </div>
                            </div>'
            ];
        }

        return response()->json([
            'data' => $data
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Task $task)
    {
        //
        $user = auth()->user();
        $projects = $user->projects()->get();

        return view('tasks.edit', compact('task','projects'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Task $task)
    {
        $validate = $request->validate([
            'project_id' => 'required|integer',
            'title' => 'required|string|max:255|',
            'description' => 'nullable',
        ]);

        $task->update($request->all());

        return redirect(route('tasks.index'))->with('success', 'Task updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $task = Task::findOrFail($id);
        $task->delete();

        return response()->json(['success' => true]);
    }

    public function toggle(Task $task)
    {
        $task->status = !$task->status;
        $task->save();

        return response()
        ->json(['success' => true]);

    }

    public function view(Task $task)
    {
        //
        $user = auth()->user();
        $projects = $user->projects()->get();

        return view('tasks.view', compact('task','projects'));
    }
    

}
