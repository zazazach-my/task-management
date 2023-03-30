<?php

namespace App\Http\Controllers;

use App\Models\Project;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = auth()->user();
        $projects = $user->projects()->get();

        $data = [];

        foreach ($projects as $project) {
            $total_tasks = $project->tasks()->count();
            $completed_tasks = $project->tasks()->where('status', 1)->count();
            $percentage_complete = $total_tasks > 0 ? round($completed_tasks / $total_tasks * 100) : 0;

            $data[] = [
                'id' => $project->id,
                'project_name' => $project->project_name,
                'total_tasks' => $total_tasks,
                'completed_tasks' => $completed_tasks,
                'percentage_complete' => $percentage_complete
            ];
        }

        $numProjects = $user->projects()->count();
        $numTasks = $user->tasks()->count();

        return view('home' , compact('numProjects','numTasks', 'data'));
    }

}
