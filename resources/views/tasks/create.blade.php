<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading"><h3>Create a New Task</h3></div>
                <div class="panel-body">
                    
                    <!-- Task form -->
                    <form method="POST" action="{{ route('tasks.store') }}" class="form" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="title">Title</label>
                            <input type="text" class="form-control" id="title" name="title" value="{{ old('title') }}" required>
                        </div>

                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea class="form-control" id="description" name="description">{{ old('description') }}</textarea>
                        </div>

                        <div class="form-group">
                            <label for="project">Project</label><br>
                            <select class="form-control" id="project" name="project_id" required>
                                <option value="">Select a project</option>
                                @foreach ($projects as $project)
                                    <option value="{{ $project->id }}">{{ $project->project_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Create Task</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#project').select2({
            placeholder: 'Search or create a project',
            allowClear: true,
            tags: true,
            createTag: function(params) {
                
                return {
                    id: params.term,
                    text: 'Create new project: ' + params.term,
                    newOption: true
                };
            },
           
        });

        $('#project').on('select2:select', function(e) {
            var data = e.params.data;

            if (data.newOption) {
                $.ajax({
                    url: '/projects/new',
                    method: 'POST',
                    data: {
                        project_name: data.text,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        // Add the new project to the dropdown list
                        var option = new Option(response.name, response.id, true, true);
                        $('#project').append(option).trigger('change');
                    },
                    error: function(xhr) {
                        console.log('Error creating new project: ' + xhr.responseText);
                    }
                });
            }
        });
    });
</script>
