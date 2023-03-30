@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row mb-4">
            <div class="col-md-12">
                <h1 class="mb-3">Welcome to Your Dashboard</h1>
                <div class="d-flex justify-content-between">
                    <!-- Show number of tasks -->
                    <a href="{{ route('tasks.index') }}">
                        <button class="btn btn-primary" type="button">
                            Total Tasks <span class="badge">{{ $numTasks }}</span>
                        </button>
                    </a>

                    <!-- Show number of projects -->
                    <a href="{{ route('projects.index') }}">
                        <button class="btn btn-primary" type="button">
                            Total Projects <span class="badge">{{ $numProjects }}</span>
                        </button>
                    </a>
                </div>
            </div>
        </div>

        <!-- Display project progress -->
        <div class="row mb-4">
            <div class="col-md-12">
                <h2>Project Progression</h2>
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Project</th>
                            <th>Total Tasks</th>
                            <th>Completed Tasks</th>
                            <th>Percentage Complete</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($data as $project)
                            <tr>
                                <td>{{ $project['project_name'] }}</td>
                                <td>{{ $project['total_tasks'] }}</td>
                                <td>{{ $project['completed_tasks'] }}</td>
                                <td>{{ $project['percentage_complete'] }}%</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Display success and errors -->
        @if(session('success'))
            <div class="row mb-4">
                <div class="col-md-12">
                    <div class="alert alert-success" role="alert">
                        {{ session('success') }}
                    </div>
                </div>
            </div>
        @endif

        <!-- Display any errors -->
        @if ($errors->any())
            <div class="row mb-4">
                <div class="col-md-12">
                    <div class="alert alert-danger" role="alert">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection
