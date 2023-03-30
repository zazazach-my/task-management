@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
            <div class="col-md-12">
                <h1>Welcome to Your Dashboard</h1>
                <hr>
                <h2>Your Projects
                    <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#add_new_task"
                            aria-expanded="false" aria-controls="collapseExample">
                        Add New Project
                    </button>
                </h2>
                <div class="collapse pb-2" id="add_new_task">
                    <div class="card card-body">
                        @include('projects.create')
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
                    <table id="projects-table" class="table table-striped table-bordered" cellspacing="0" width="100%" >
                        <thead>
                            <tr>
                                <th>Project Name</th>
                                <th>Description</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('Javascript')
<script>
    $(document).ready(function () {

        $('#projects-table').DataTable({
            ajax: '/projects/show',
            "columnDefs": [
                        {"targets": 1 , 
                            render: function ( data, type, row ) {
                                if (data !== null) {
                                    data = data.replace(/\n/g, "<br>");
                                    
                                    // Count the number of <br> tags in the text
                                    var brCount = (data.match(/<br>/g) || []).length;
                                    
                                    return brCount > 2 ?
                                        data.split('<br>').slice(0, 2).join('<br>') +' …' :
                                        data.length > 50 ?
                                        data.substr( 0, 50 ) +' …' :
                                        data;
                                } else {
                                    return data;
                                }
                            },
                            "createdCell": function (td, cellData, rowData, row, col) {
                                $(td).attr('title', rowData.description);
                            }                           
                        }
                    ],
            "columns": [
                { "data": "project_name" },
                { "data": "description" },
                { "data": "action" }
            ],
            "ordering": false
            
        });

    });


    $(document).on('click', '.delete-project-btn', function() {
        var project_id = $(this).data('id');
        var url = $(this).data('url');
        
        // Check if the project has tasks
        $.ajax({
            url: '/projects/' + project_id + '/tasks/count',
            method: 'GET',
            success: function(response) {
                if (response.count > 0) {
                    if (!confirm("This project has " + response.count + " task(s). Are you sure you want to delete it?")) {
                        return;
                    }
                }
                
                // Delete the project
                $.ajax({
                    url: url,
                    method: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        // Reload the datatable to reflect the updated data
                        $('table').DataTable().ajax.reload(null, false);
                    },
                    error: function(response) {
                        console.log(response);
                    }
                });
            },
            error: function(response) {
                console.log(response);
            }
        });
    });


    

</script>
@endsection
