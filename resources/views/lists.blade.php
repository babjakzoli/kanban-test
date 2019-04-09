@extends('layouts.material')

@section('title')
    Lists
@endsection

@section('content')

    <div class="modal fade bd-example-modal-sm" tabindex="-1" id="delListModal" role="dialog" aria-labelledby="delListLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createListModalLabel">Delete list</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Delete <span id="delName"></span>?
                    <input type="hidden" id="delId">
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
                            <label for="list-name" class="col-form-label">Name:</label>
                            <input type="text" class="form-control" id="list-name">
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
                            <td>{{ $list->name }}</td>
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
			var name = $('#list-name').val();

			$.ajax({
                url: '{{ route('lists.store') }}',
                type: 'POST',
                dataType: 'JSON',
				data: {_token: $('meta[name="csrf-token"]').attr('content'), data: name },
                success: function (data) {
                    console.log(data);
                    $('.listTable tbody').append('<tr id="list_' + data.id +'"><td>' + data.name + '</td><td>\n' +
						'                                <button class="btn btn-primary btn-sm editListButton" data-id="' + data.id + '" data-name="' + data.name + '">EDIT</button>\n' +
						'                                <button class="btn btn-danger btn-sm delListButton" data-id="' + data.id + '" data-name="' + data.name + '" data-toggle="modal" data-target="#delListModal">DEL</button>\n' +
						'                            </td></tr>');
					$('#list-name').val('');
                    $('.closeButton').click();
				}
            });
		});

		$('body').on('click', '.delListButton', function () {
			console.log('name', $(this).data('name'));
            $('#delId').val($(this).data('id'));
            $('#delName').text('"' + $(this).data('name') + '"');
        });
		$('body').on('click', '#delListButton', function () {
			$.ajax({
                url: '/lists/' + $('#delId').val(),
                type: 'DELETE',
                dataType: 'JSON',
				data: {_token: $('meta[name="csrf-token"]').attr('content')},
                success: function (data) {
                    console.log(data);
                    $('#list_' + data).remove();
                    $('#delListModal .close').click();
                }
            })
        });
    </script>
@endsection