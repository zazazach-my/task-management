@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading"><h3>View Project Details</h3></div>
                <div class="panel-body">
                    <table class="table table-striped">
                        <tbody>
                            <tr>
                                <td>Project Name</td>
                                <td>{{ $project->project_name }}</td>
                            </tr>
                            <tr>
                                <td>Description</td>
                                <td>
                                {!! nl2br(e($project->description )) !!}
                                </td>
                            </tr>
                            <tr>
                                <td>Task</td>
                                <td>
                                @forelse ($tasks as $task)
                                    <li>{{ $task->title }} -  {{ $task->status ? 'Completed' : 'Incomplete' }}</li>
                                @empty
                                    No tasks
                                @endforelse
                                </td>
                            </tr>
                            <tr>
                                <td>Created At</td>
                                <td>{{ $project->created_at->format('M d, Y h:i A') }}</td>
                            </tr>
                            <tr>
                                <td>Updated At</td>
                                <td>{{ $project->updated_at->format('M d, Y h:i A') }}</td>
                            </tr>
                        </tbody>
                    </table>
                    <a href="{{ route('projects.edit', $project->id) }}" class="btn btn-primary">Edit Project</a>
                    <a href="{{ route('projects.index') }}" class="btn btn-default">Back</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
