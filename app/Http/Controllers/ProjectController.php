<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\Project;

class ProjectController extends Controller
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
        // 
        $user = auth()->user();
        $projects = $user->projects()->get();
        

        return view('/projects/index', compact('user', 'projects'));
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
        
        if (count($request->all())==3){
            $validate = $request->validate([
                'project_name' => [
                    'required',
                    'string',
                    'max:255',
                    Rule::unique('projects')->where(function ($query) {
                        return $query->where('user_id', auth()->user()->id);
                    })
                ],
                'description' => 'string|max:255|nullable'
            ]);
            
            $project = new Project();
            $project->project_name = $validate['project_name'];
            $project->user_id = auth()->user()->id; 
            $project->description = $validate['description'];
            $project->save();

            return redirect(route('projects.index'))->with('success', 'Project created successfully.');



        }else{
            $validate = $request->validate([
                'project_name' => 'required|string|max:255',
            ]);
            
            // Remove the "Create new project: " prefix from the project name
            $validate['project_name'] = str_replace('Create new project: ', '', $validate['project_name']);
    
            $project = new Project();
            $project->project_name = $validate['project_name'];
            $project->user_id = auth()->user()->id; 
            $project->save();
    
            return response()->json([
                'id' => $project->id,
                'name' => $project->project_name,
            ]);
        }
        //
        
    }

    /**
     * Display the specified resource.
     */
    public function show()
    {
        $user = auth()->user();
        $projects = $user->projects()->get();
        
        $data = [];

        foreach ($projects as $project) {
            $data[] = [
                'id' => $project->id,
                'project_name' => $project->project_name,
                'description' => $project->description,
                'action' => '<div class="btn-group">
                                <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Action
                                </button>
                                <div class="dropdown-menu dropdown-menu-right">
                                    <a class="dropdown-item" href="'. url('projects/' . $project->id.'/view') .' ">View</a>
                                    <a class="dropdown-item" href="'. url('projects/' . $project->id.'/edit') .' ">Edit</a>
                                    <a class="dropdown-item delete-project-btn" data-url="'. url('projects/' . $project->id.'/delete') .' " data-id="' . $project->id . '" >Delete</a>
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
    public function edit(Project $project)
    {
        // 
        return view('projects.edit', compact('project'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Project $project)
    {
        //
        $id=$project->id;
        $validate = $request->validate([
            'project_name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('projects')->where(function ($query) use ($id) {
                    return $query->where('user_id', auth()->user()->id)
                        ->where('id', '!=', $id);
                })
            ],
            'description' => ['string','max:255','nullable']
        ]);

        $project->update($request->all());

        return redirect(route('projects.index'))->with('success', 'Project updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $project = Project::findOrFail($id);
        $project->delete();

        return response()->json(['success' => true]);
    }

    public function countTasks(Project $project)
    {
        $count = $project->tasks()->count();
        
        return response()->json(['count' => $count]);
    }

    public function view(string $id)
    {
        //
        $project = Project::findOrFail($id);
        $tasks = $project->tasks()->get();

        return view('/projects/view', compact('project', 'tasks'));
    }
    }


