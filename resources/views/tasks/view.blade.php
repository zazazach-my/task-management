@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading"><h3>View Tasks Details</h3></div>
                <div class="panel-body">
                    <table class="table table-striped">
                        <tbody>
                            <tr>
                                <th>Task Name</th>
                                <td>{{ $task->title }}</td>
                            </tr>
                            <tr>
                                <th>Description</th>
                                <td>{{ $task->description }}</td>
                            </tr>
                            <tr>
                                <th>Status</th>
                                <td>{{ $task->status? 'Completed' : 'Incomplete' }}</td>
                            </tr>
                            <tr>
                                <th>Project</th>
                                <td>{{ $task->project->project_name }}</td>
                            </tr>
                            <tr>
                                <th>Created At</th>
                                <td>{{ $task->created_at->format('M d, Y h:i A') }}</td>
                            </tr>
                            <tr>
                                <th>Updated At</th>
                                <td>{{ $task->updated_at->format('M d, Y h:i A') }}</td>
                            </tr>
                        </tbody>
                    </table>
                    <a href="{{ route('tasks.edit', $task->id) }}" class="btn btn-primary">Edit Tasks</a>
                    <a href="{{ route('tasks.index') }}" class="btn btn-default">Back</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
