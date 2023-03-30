@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading"><h3>Edit Tasks</h3></div>
                <div class="panel-body">
                     <!-- Display any errors -->
                     @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    <hr>
                    @endif
                    <form action="{{ route('tasks.update', $task->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="title">Title</label>
                            <input type="text" name="title" id="title" class="form-control" value="{{ $task->title }}" required>
                        </div>

                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea name="description" id="description" class="form-control">{{ $task->description }}</textarea>
                        </div>

                        <div class="form-group">
                            <label for="status">Status</label>
                            <select name="status" id="status" class="form-control">
                                <option value="0" {{ $task->status ? 'selected' : '' }}>Incomplete</option>
                                <option value="1" {{ $task->status ? 'selected' : '' }}>Completed</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="project">Project</label><br>
                            <select class="form-control" id="project" name="project_id" required>
                                <optgroup label="Move task to">
                                    @foreach ($projects as $project)
                                        <option value="{{ $project->id }}" {{ $project->id == $task->project->id ? 'selected' : '' }}>
                                                {{ $project->project_name }}
                                        </option>
                                    @endforeach
                                </optgroup>    
                            </select>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary ">Update</button>
                            <a href="{{route('tasks.index')}}"><button type="button" class="ml-1 btn btn-secondary">Cancel</button></a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection
