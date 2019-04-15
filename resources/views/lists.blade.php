@extends('layouts.material')

@section('title')
    Lists
@endsection

@section('content')

    <div class="modal fade bd-example-modal-sm" tabindex="-1" id="delListModal" role="dialog" aria-labelledby="delListLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="delListModalLabel">Delete list</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Delete <span id="delName"></span>?
                    <input type="hidden" id="delId">
                    <div class="alert alert-danger hide" role="alert"></div>
                </div>
                <div class="modal-header">
                    <button class="btn btn-sm btn-danger" id="delListButton">Yes</button>
                    <button class="btn btn-sm btn-success" data-dismiss="modal">No</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="createListModal" tabindex="-1" role="dialog" aria-labelledby="createListModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form>
                    <div class="modal-header">
                        <h5 class="modal-title" id="createListModalLabel">New list</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="listName" class="col-form-label">Name:</label>
                            <input type="text" class="form-control" id="listName" name="name">
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary closeButton" data-dismiss="modal">Close</button>
                        <button type="button" id="saveNewList" class="btn btn-primary">Save new list</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modListModal" tabindex="-1" role="dialog" aria-labelledby="Task" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form>
                    <div class="modal-header">
                        <h5 class="modal-title" id="taskLabel">Modify List</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="modListName" class="col-form-label">Name:</label>
                            <input type="text" class="form-control" id="modListName" name="name">
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" id="modListButton" name="modList">Modify</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row">
            <div class="xs-offset-11 xs-1">
                <button class="btn btn-success btn-sm" data-toggle="modal" data-target="#createListModal">Create list</button>
            </div>
        </div>
        <div class="row">
            <table class="listTable table table-bordered">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Operations</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($lists as $list)
                        <tr id="list_{{ $list->id }}">
                            <td class="name">{{ $list->name }}</td>
                            <td>
                                <button class="btn btn-primary btn-sm editListButton" data-id="{{ $list->id }}" data-name="{{ $list->name }}">EDIT</button>
                                <button class="btn btn-danger btn-sm delListButton" data-id="{{ $list->id }}" data-name="{{ $list->name }}" data-toggle="modal" data-target="#delListModal">DEL</button>
                            </td>
                        </tr>
                    @empty
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

@endsection

@section('scripts')
    <script>


		$('#saveNewList').on('click', function () {
			var name = $('#listName').val();

			$.ajax({
                url: '{{ route('lists.store') }}',
                type: 'POST',
                dataType: 'JSON',
				data: { name: name },
                success: function () {
					location.reload();
				},
                error: function (error) {
					$('#createListModal').find('.is-invalid').removeClass('is-invalid');
					$('#createListModal').find('.invalid-feedback').hide();

					$.each(error.responseJSON.validator, function(key, message) {
						$('#createListModal input[name="' + key + '"').parent().find('.invalid-feedback').text(message.join()).show();
						$('#createListModal input[name="' + key + '"').addClass('is-invalid');
					});
                }
            });
		});

		$('body').on('click', '.delListButton', function () {
            $('#delId').val($(this).data('id'));
            $('#delName').text('"' + $(this).data('name') + '"');
        });
		$('body').on('click', '#delListButton', function () {
			$.ajax({
                url: '/lists/' + $('#delId').val(),
                type: 'DELETE',
                dataType: 'JSON',
                success: function (data) {
					$('#delListModal .alert').hide();
                    $('#list_' + data).remove();
                    $('#delListModal').modal('hide');
                },
                error: function (data) {
                	$('#delListModal .alert').text(data.responseJSON.error).show();
                }
            })
        });
		$('body').on('click', '.editListButton', function () {

			$('#modListModal').modal('show');
			$('#modListModal').find('.is-invalid').removeClass('is-invalid');
			$('#modListModal').find('.invalid-feedback').hide();

			$.ajax({
				url: '/lists/' + $(this).data('id'),
				type: 'GET',
				dataType: 'JSON',
				success: function(data) {
					$('#modListName').val(data.name);
					$('#modListModal').modal('show');
					$('#modListButton').attr('data-id', data.id);
				}
			});
        });

		$('body').on('click', '#modListButton', function (e) {
			e.preventDefault();

			$.ajax({
				url: '/lists/' + $(this).data('id'),
				type: 'PUT',
				data: { name: $('#modListName').val() },
				dataType: 'JSON',
				success: function(data) {

					var task = $("#list_" + data.id);

					task.find(".name").text(data.name);
					$('#modListModal').modal('hide');
				},
				error: function(error) {

					$('#modListModal').find('.is-invalid').removeClass('is-invalid');
					$('#modListModal').find('.invalid-feedback').hide();

					$.each(error.responseJSON.validator, function(key, message) {
						$('#modListModal input[name="' + key + '"').parent().find('.invalid-feedback').text(message.join()).show();
						$('#modListModal input[name="' + key + '"').addClass('is-invalid');
					});
				}
			});
		});
    </script>
@endsection