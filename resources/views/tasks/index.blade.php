@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
            <div class="col-md-12">
                <h1>Welcome to Your Dashboard</h1>
                <hr>
                <h2>Your Tasks
                    <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#add_new_task"
                            aria-expanded="false" aria-controls="collapseExample">
                        Add New Task
                    </button>
                </h2>
                <div class="collapse pb-2" id="add_new_task">
                    <div class="card card-body">
                        @include('tasks.create')
                    </div>
                </div>

                    <!-- Display success and errors -->

                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                    <hr>
                @endif

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
            
                <div>
                <br>
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <!-- Generate Bootstrap tabs dynamically based on project names -->
                            <li class="nav-item">
                                <a class="nav-link" href="#tab-all" data-toggle="tab" data-project-id="all">All Projects</a>
                            </li>
                        @foreach ($projects as $project)
                            <li class="nav-item">
                                <a class="nav-link" href="#tab-{{$project->id}}" data-toggle="tab" data-project-id="{{$project->id}}">{{$project->project_name}} </a>
                            </li>
                        @endforeach
                    </ul>
                    <div class="tab-content pt-3" id="myTabContent">
                        <div class="tab-pane" id="tab-all" data-project-id="all">
                            <table id="tasks-table-all" class="table table-striped table-bordered" cellspacing="0" width="100%" data-project-id="all">
                                <thead>
                                    <tr>
                                        <th>Title</th>
                                        <th>Description</th>
                                        <th>Project</th>
                                        <th>Status</th>
                                        <th>Toggle</th>
                                        <th>Action</th>
                                    </tr>
                                    <tr>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                         <!-- Generate Bootstrap tab contents dynamically based on project names -->
                        @foreach ($projects as $project)
                            <div class="tab-pane" id="tab-{{$project->id}}" data-project-id="{{$project->id}}">
                                <table id="tasks-table-{{$project->id}}" class="table-project table-striped table-bordered" data-project-id="{{$project->id}}" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>Title</th>
                                            <th>Description</th>
                                            <th>Project</th>
                                            <th>Status</th>
                                            <th>Toggle</th>
                                            <th>Action</th>
                                        </tr>
                                        <tr>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('Javascript')
<script>
    $(document).ready(function () {
        $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
            var projectId = $(e.target).data('project-id');
            $('#tasks-table-' + projectId).DataTable().columns.adjust();
        });

        $('table.table-project').each(function () {
            var projectId = $(this).data('project-id');
            $(this).DataTable({
                ajax: '/tasks/'+projectId,
                "columnDefs": [
                        { "width": "30%", "targets": 1 , 
                            render: function ( data, type, row ) {

                                if (data !== null) {
                                    data = data.replace(/\n/g, "<br>");

                                    return data.length > 50 ?
                                    data.substr( 0, 50 ) +' …' :
                                    data;

                                }else{

                                    return data;
                                }
                                
                            },
                            "createdCell": function (td, cellData, rowData, row, col) {
                                $(td).attr('title', rowData.description);
                            }                           
                        },
                        { "width": "30%", "targets": 0 },
                        { "width": "5%", "targets": 3 },
                        { "width": "5%", "targets": 4 },
                        { "width": "5%", "targets": 5 },
                    ],
                "columns": [
                    { "data": "title" },
                    { "data": "description" },
                    { "data": "project.project_name" },
                    { "data": "status" },
                    { "data": "toggle" },
                    { "data": "action" }
                ],
                "ordering": false,
                "initComplete": function () {
                    this.api().columns([3]).every(function () {
                        var column = this;
                        var select = $('<select><option value="">-- ALL --</option></select>')
                            .appendTo($(column.header()).empty())
                            .on('change', function () {
                                var val = $.fn.dataTable.util.escapeRegex(
                                    $(this).val()
                                );
                                column
                                    .search(val ? '^' + val + '$' : '', true, false).draw();
                            });

                        column
                            .data()
                            .unique()
                            .sort()
                            .each(function (d, j) {
                                select.append('<option value="' + d + '">' + d + '</option>');
                            });
                    });

                }
            });
        });

        $('#tasks-table-all').DataTable({
                ajax: '/tasks/all',
                "columnDefs": [
                        { "width": "30%", "targets": 1 , 
                            render: function ( data, type, row ) {
                                if (data !== null) {
                                    data = data.replace(/\n/g, "<br>");

                                    return data.length > 50 ?
                                    data.substr( 0, 50 ) +' …' :
                                    data;
                                    
                                }else{

                                    return data;
                                }
                            },
                            "createdCell": function (td, cellData, rowData, row, col) {
                                $(td).attr('title', rowData.description);
                            }                           
                        },
                        { "width": "30%", "targets": 0 },
                        { "width": "5%", "targets": 3 },
                        { "width": "5%", "targets": 4 },
                        { "width": "5%", "targets": 5 },
                    ],
                "columns": [
                    { "data": "title" },
                    { "data": "description" },
                    { "data": "project.project_name" },
                    { "data": "status" },
                    { "data": "toggle" },
                    { "data": "action" }
                ],
                "ordering": false,
                "initComplete": function () {
                    this.api().columns([2,3]).every(function () {
                        var column = this;
                        var select = $('<select><option value="">-- ALL --</option></select>')
                            .appendTo($(column.header()).empty())
                            .on('change', function () {
                                var val = $.fn.dataTable.util.escapeRegex(
                                    $(this).val()
                                );
                                column
                                    .search(val ? '^' + val + '$' : '', true, false).draw();
                            });

                        column
                            .data()
                            .unique()
                            .sort()
                            .each(function (d, j) {
                                select.append('<option value="' + d + '">' + d + '</option>');
                            });
                    });

                }
            });

        $('#myTab a:first').tab('show');
    });


    $(document).on('click', '.toggle-btn', function() {
        var task_id = $(this).data('id');
        var status = $(this).hasClass('btn-success') ? 0 : 1;

        $.ajax({
            url: '/tasks/' + task_id + '/toggle',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                _method: 'PATCH',
                status: status
            },
            success: function(response) {
                // Reload the datatable to reflect the updated data
                $('table').DataTable().ajax.reload(null, false);
                
            },
            error: function(response) {
                console.log(response);
            }
        });
    });

    $(document).on('click', '.delete-task-btn', function() {
        var task_id = $(this).data('id');
        var url = $(this).data('url');

        $.ajax({
            url: url,
            method: 'DELETE',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                // Reload the datatable to reflect the updated data
                $('table').DataTable().ajax.reload(null, false);

                // Display a success message
                var message = 'Task ' + task_id + ' has been deleted successfully.';
                var type = 'success';
                var title = 'Success!';
                
                // Use the Laravel's session class to flash the message
                @if (Session::has('success'))
                    var messages = {!! json_encode(Session::get('success')) !!};
                    messages.push({message: message, type: type, title: title});
                    sessionStorage.setItem('messages', JSON.stringify(messages));
                @else
                    var messages = [{message: message, type: type, title: title}];
                    sessionStorage.setItem('messages', JSON.stringify(messages));
                @endif
            },
            error: function(response) {
                console.log(response);
            }
        });
    });

</script>
@endsection
