@extends('layouts.material')

@section('title')
    Board
@endsection

@section('content')

    <div class="modal fade" id="createTaskModal" tabindex="-1" role="dialog" aria-labelledby="Create Task" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form>
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Create Task</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                            {{ csrf_field() }}
                            <div class="form-group">
                                <label for="title" class="col-form-label">Title:</label>
                                <input type="text" class="form-control" id="title" name="title">
                                @if (session()->has('titleError'))
                                    <div class="invalid-feedback">
                                        Please provide a valid zip.
                                    </div>
                                @endif
                            </div>
                            <div class="form-group">
                                <label for="message-text" class="col-form-label">Description:</label>
                                <textarea class="form-control" id="message-text" name="description"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="list_id" class="col-form-label">List:</label>
                                <select name="list_id" required>
                                    @foreach($lists as $list)
                                        <option value="{{$list->id}}">{{ $list->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="user_id" class="col-form-label">Assign to:</label>
                                <select name="user_id" required>
                                    @foreach($users as $user)
                                        <option value="{{$user->id}}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="message-text" class="col-form-label">Due date:</label>
                                <input class="form-control" id="dueDate" type="text" name="due_date"/>
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

    <div class="container">
        <div class="row">
            <div class="xs-offset-11 xs-1">
                <button class="btn btn-success btn-sm" data-toggle="modal" data-target="#createTaskModal">Create task</button>
            </div>
        </div>
        <div class="row">
            @forelse($lists as $list)
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">{{ $list->name }}</h4>
                        <ul class="tasks list_{{ $list->id }}">
                            @forelse($list->tasks as $task)
                                <li class="ui-state-default task_{{ $task->id }}">
                                    <b>{{ $task->title }}</b><br>
                                    Desc: {{ $task->description }}<br>
                                    Assign: {{ $task->assignUser->name }}<br>
                                    Created: {{ $task->createdUser->name }}<br>
                                    Created time: {{ $task->created_at }}<br>
                                    Due Date: {{ date('Y-m-d', strtotime($task->due_date)) }}
                                </li>
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
        .tasks { list-style-type: none; margin: 0; float: left; margin-right: 10px; background: #eee; padding: 5px; width: 143px;}
        .card { float: left; margin-right: 5px; }
        .tasks li { padding: 5px; margin-bottom: 10px; }
        .tasks li:last-child { margin-bottom: 0; }
    </style>

@endsection

@section('scripts')

	<script>

        $("#dueDate").datepicker();

		$( "ul.tasks" ).sortable({
			connectWith: "div.card ul",
			dropOnEmpty: true,
            update: function(event, ui) {
				var lists = [];
				$("ul.tasks li").each(function(i) {
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
				console.log('sort: ', lists);
				$.ajax({
                    url: '{{ route('sort') }}',
                    type: 'POST',
					data: {_token: $('meta[name="csrf-token"]').attr('content'), data: lists },
                    dataType: 'JSON'
                });
            }
		});

		$( ".tasks" ).disableSelection();

		$("#addTaskButton").on('click', function(e) {

			e.preventDefault();
			var sendData = $(this).form().serializeArray();

			console.log(sendData);
			$.ajax({
				url: '{{ route('tasks.store') }}',
				type: 'POST',
				data: {_token: $('meta[name="csrf-token"]').attr('content'), data: sendData },
				dataType: 'JSON',
                success: function(data) {
					console.log(data);
					$(".list_" + data.list_id).append('<li class="ui-state-default task_' + data.id + '">' + data.title + '</li>');
					$('.close').click();
                }
			});
			console.log(sendData);
        })

	</script>

@endsection
