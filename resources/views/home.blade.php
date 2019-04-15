@extends('layouts.material')

@section('title')
    Board
@endsection

@section('content')

    <div class="modal fade bd-example-modal-sm" tabindex="-1" id="delTaskModal" role="dialog" aria-labelledby="delTaskLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="delTaskModalLabel">Delete list</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Delete <span id="delTitle"></span>?
                    <input type="hidden" id="delId">
                </div>
                <div class="modal-header">
                    <button class="btn btn-sm btn-danger" id="delTaskButton">Yes</button>
                    <button class="btn btn-sm btn-success" data-dismiss="modal">No</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addTaskModal" tabindex="-1" role="dialog" aria-labelledby="Task" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form>
                    <div class="modal-header">
                        <h5 class="modal-title" id="taskLabel">Create Task</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                            <div class="form-group">
                                <label for="title" class="col-form-label">Title:</label>
                                <input type="text" class="form-control" id="title" name="title">
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="form-group">
                                <label for="description" class="col-form-label">Description:</label>
                                <textarea class="form-control" id="description" name="description"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="listId" class="col-form-label">List:</label>
                                <select name="list_id" id="listId">
                                    @foreach($lists as $list)
                                        <option value="{{$list->id}}">{{ $list->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="assignedUser" class="col-form-label">Assign to:</label>
                                <select name="user_id" id="assignedUser">
                                    @foreach($users as $user)
                                        <option value="{{$user->id}}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="message-text" class="col-form-label">Due date:</label>
                                <input class="form-control due_date" id="due_date" type="text" name="due_date"/>
                                <div class="invalid-feedback"></div>
                            </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" id="addTaskButton" name="addTask">Add</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modTaskModal" tabindex="-1" role="dialog" aria-labelledby="Task" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form>
                    <div class="modal-header">
                        <h5 class="modal-title" id="taskLabel">Modify Task</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="modTitle" class="col-form-label">Title:</label>
                            <input type="text" class="form-control" id="modTitle" name="title">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="form-group">
                            <label for="modDescription" class="col-form-label">Description:</label>
                            <textarea class="form-control" id="modDescription" name="description"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="modListId" class="col-form-label">List:</label>
                            <select name="list_id" id="modListId">
                                @foreach($lists as $list)
                                    <option value="{{$list->id}}">{{ $list->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="modAssignedUser" class="col-form-label">Assign to:</label>
                            <select id="modAssignedUser" name="user_id" >
                                @foreach($users as $user)
                                    <option value="{{$user->id}}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="modDueDate" class="col-form-label">Due date:</label>
                            <input class="form-control due_date" id="modDueDate" type="text" name="due_date"/>
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" id="modTaskButton" name="modTask">Modify</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row">
            <div class="xs-offset-11 xs-1">
                <button class="btn btn-success btn-sm" id="createTaskButton">Create task</button>
            </div>
        </div>
        <div class="row">
            @forelse($lists as $list)
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">{{ $list->name }}</h4>
                        <ul class="tasks list_{{ $list->id }}">
                            @forelse($list->tasks as $task)
                                <div class="portlet task_{{ $task->id }}">
                                    <div class="portlet-header">
                                        <span class="title">{{ $task->title }}</span>
                                    </div>
                                    <div class="portlet-content hide">
                                        <div class="desc">Desc: {{ $task->description }}</div>
                                        <div class="assign">Assign: {{ $task->assignUser->name }}</div>
                                        <div class="createdU">Created: {{ $task->createdUser->name }}</div>
                                        <div class="createdT">Created time: {{ $task->created_at }}</div>
                                        <div class="due_date">Due Date: {{ $task->due_date }}</div>
                                        <div class="float-left text-left">
                                            <button class="btn btn-danger btn-sm delTaskButton" data-id="{{ $task->id }}" data-title="{{ $task->title }}" data-toggle="modal" data-target="#delTaskModal">Del</button>
                                        </div>
                                        <div class="d-block float-right">
                                            <button class="btn btn-success btn-sm modModalButton" data-id="{{ $task->id }}">Modify</button>
                                        </div>
                                    </div>
                                </div>
                            @empty
                            @endforelse
                        </ul>
                    </div>
                </div>
            @empty

            @endforelse
        </div>
    </div>

@endsection

@section('styles')

    <style>
        .tasks { list-style-type: none; margin: 0; float: left; margin-right: 10px; background: #eee; padding: 5px; width: 200px;}
        .card { float: left; margin-right: 5px; }
        .tasks li { padding: 5px; margin-bottom: 10px; }
        .tasks li:last-child { margin-bottom: 0; }

        .portlet {
            margin: 0 0 0.5rem;
            padding: 0.3em;
        }
        .portlet:last-child {
            margin: 0;
        }
        .portlet-header {
            padding: 0.2em 0.3em;
            margin-bottom: 0.5em;
            position: relative;
        }
        .portlet-toggle {
            position: absolute;
            top: 50%;
            right: 0;
            margin-top: -8px;
        }
        .portlet-content {
            padding: 0.4em;
        }
        .portlet-placeholder {
            border: 1px dotted black;
            margin: 0 1em 1em 0;
            height: 50px;
        }
    </style>

@endsection

@section('scripts')

	<script>

		$('body').on('focus', '.due_date', function () {
			$(this).datepicker({
				dateFormat: 'yy-mm-dd'
            });
		});

		$( ".portlet" )
			.addClass( "ui-widget ui-widget-content ui-helper-clearfix ui-corner-all" )
			.find( ".portlet-header" )
			.addClass( "ui-widget-header ui-corner-all" )
			.prepend( "<span class='ui-icon ui-icon-plusthick portlet-toggle'></span>");

		$( ".portlet-toggle" ).on( "click", function() {
			var icon = $( this );
			icon.toggleClass( "ui-icon-minusthick ui-icon-plusthick" );
			icon.closest( ".portlet" ).find( ".portlet-content" ).toggle();
		});

        $( "ul.tasks" ).sortable({
            connectWith: "ul.tasks",
            dropOnEmpty: true,
			handle: ".portlet-header",
			cancel: ".portlet-toggle",
			placeholder: "portlet-placeholder ui-corner-all",
            update: function(event, ui) {
                var lists = [];
                $(".portlet").each(function(i) {
                    var list_id = $(this).parent().attr('class').split(' ').map(function(data) {
                        if ((data.indexOf('list') >= 0)) {
                            return data;
                        }
                    }).join('').split('_').slice(1,2)[0];
                    var task_id = $(this).attr('class').split(' ').map(function(data) {
                        if ((data.indexOf('task') >= 0)) {
                            return data;
                        }
                    }).join('').split('_').slice(1,2)[0];
                    lists.push({list_id: list_id, task_id: task_id});
                });

                $.ajax({
                    url: '{{ route('sort') }}',
                    type: 'POST',
                    data: { data: lists },
                    dataType: 'JSON',
					headers: {
						'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
					}
                });
            }
        });

        $( ".tasks" ).disableSelection();

        $('#createTaskButton').on('click', function() {
        	$('#addTaskModal').modal('show');
			$('#addTaskModal').find('.is-invalid').removeClass('is-invalid');
			$('#addTaskModal').find('.invalid-feedback').hide();
        });

        $('body').on('click', '#addTaskButton', function (e) {

            e.preventDefault();
            var sendData = $(this).form().serializeArray();

            $.ajax({
                url: '{{ route('tasks.store') }}',
                type: 'POST',
                data: { data: sendData },
                dataType: 'JSON',
                success: function() {
                	location.reload();
                },
                error: function(error) {

                	$('#addTaskModal').find('.is-invalid').removeClass('is-invalid');
					$('#addTaskModal').find('.invalid-feedback').hide();

					$.each(error.responseJSON.validator, function(key, message) {
						$('#addTaskModal input[name="' + key + '"').parent().find('.invalid-feedback').text(message.join()).show();
						$('#addTaskModal input[name="' + key + '"').addClass('is-invalid');
                    });
                }
            });
        });

        $('body').on('click', '.modModalButton', function() {

			$('#modTaskModal').modal('show');
			$('#modTaskModal').find('.is-invalid').removeClass('is-invalid');
			$('#modTaskModal').find('.invalid-feedback').hide();

			$.ajax({
				url: '/tasks/' + $(this).data('id'),
				type: 'GET',
				dataType: 'JSON',
				success: function(data) {
					$('#modAssignedUser').val(data.user_id);
					$('#modTitle').val(data.title);
					$('#modDescription').val(data.description);
					$('#modListId').val(data.list_id);
					$('#modDueDate').val(data.due_date);
                    $('#modTaskModal').modal('show');
                    $('#modTaskButton').attr('data-id', data.id);
				}
			});

        });

        $('body').on('click', '#modTaskButton', function (e) {

            e.preventDefault();
            var sendData = $(this).form().serializeArray();

            $.ajax({
                url: '/tasks/' + $(this).data('id'),
                type: 'PUT',
                data: { data: sendData },
                dataType: 'JSON',
                success: function(data) {

                    var task = $(".task_" + data.id);

                    task.find(".portlet-header .title").text(data.title);
                    $('#modTaskModal').modal('hide');
                },
                error: function(error) {

					$('#modTaskModal').find('.is-invalid').removeClass('is-invalid');
					$('#modTaskModal').find('.invalid-feedback').hide();

					$.each(error.responseJSON.validator, function(key, message) {
						$('#modTaskModal input[name="' + key + '"').parent().find('.invalid-feedback').text(message.join()).show();
						$('#modTaskModal input[name="' + key + '"').addClass('is-invalid');
					});
                }
            });
        });

		$('body').on('click', '.delTaskButton', function () {
			$('#delId').val($(this).data('id'));
			$('#delTitle').text('"' + $(this).data('title') + '"');
		});

		$('body').on('click', '#delTaskButton', function () {
			$.ajax({
				url: '/tasks/' + $('#delId').val(),
				type: 'DELETE',
				dataType: 'JSON',
				success: function (data) {
					$('.task_' + data).remove();
					$('#delTaskModal').modal('hide');
				}
			})
		});

	</script>

@endsection
