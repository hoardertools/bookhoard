@extends('layouts.default')

@section('title', $library->name)

@section('content')
	<!-- begin page-header -->
	<h1 class="page-header">{{$library->name}}</h1>
	<!-- end page-header -->

	<!-- begin panel -->
	@if(count($directories) > 0)
	<div class="panel panel-inverse">
		<div class="panel-heading">
			<h4 class="panel-title">Subdirectories</h4>
			<div class="panel-heading-btn">

			</div>
		</div>
		<div class="panel-body">
			<div class="row">
				@if(session('success'))
					<div class="alert alert-success alert-dismissible" id="sectionAlert">
						{{ session('success') }}
					</div>
				@endif

						<table id="data-table-default" class="table table-striped table-bordered align-middle text-nowrap">
							<thead>
							<tr>
								<th>Name</th>
							</tr>
							</thead>
							<tbody>
							@foreach($directories as $directory)
								<tr>
									<td><a href="/library/{{$library->slug}}/directory/{{$directory->id}}">{{basename($directory->path)}}</a></td>
								</tr>
							@endforeach
							</tbody>
						</table>


			</div>
		</div>
	</div>
	@endif




	@if(count($books) > 0)
		<div class="panel panel-inverse">
			<div class="panel-heading">
				<h4 class="panel-title">Books</h4>
				<div class="panel-heading-btn">

				</div>
			</div>
			<div class="panel-body">
				<div class="row">
					@if(session('success'))
						<div class="alert alert-success alert-dismissible" id="sectionAlert">
							{{ session('success') }}
						</div>
					@endif

						<table id="data-table-books" class="table table-striped table-bordered align-middle text-nowrap">
							<thead>
							<tr>
								<th>Name</th>
								<th>Series</th>
								<th>Issue</th>
								<th>Title</th>
							</tr>
							</thead>
							<tbody>
							@foreach($books as $book)
								<tr data-book-id="{{ $book->id }}">
									<td><a href="/library/{{$library->slug}}/directory/{{$book->directory_id}}/book/{{$book->id}}">{{$book->name}}</a></td>
									<td class="series"></td>
									<td class="issue"></td>
									<td class="title"></td>
								</tr>
							@endforeach
							</tbody>
						</table>


				</div>
			</div>
		</div>
	@endif
	<!-- end panel -->
@endsection

@push('scripts')
	<link href="/assets/plugins/datatables.net-bs5/css/dataTables.bootstrap5.min.css" rel="stylesheet" />
	<link href="/assets/plugins/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css" rel="stylesheet" />
	<script src="/assets/plugins/datatables.net/js/dataTables.min.js"></script>
	<script src="/assets/plugins/datatables.net-bs5/js/dataTables.bootstrap5.min.js"></script>
	<script src="/assets/plugins/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
	<script src="/assets/plugins/datatables.net-responsive-bs5/js/responsive.bootstrap5.min.js"></script>
	<script>
		$(document).ready(function () {
			// Iterate over each element with the class 'directoryImage'
			$('.directoryImage').each(function () {
				var element = $(this);
				var podcastId = element.attr('id'); // Get the ID attribute of the current element

				// Make sure the element has an ID
				if (directoryId) {
					// Make the AJAX call to get the image URL
					$.ajax({
						url: '/getDirectoryImage/' + directoryId,
						method: 'GET',
						success: function (data) {
							// Update the 'src' attribute with the returned data
							element.attr('src', data);
						},
						error: function () {
							console.error('Error fetching data for directory ID:', directoryId);
						}
					});
				}
			});
		});
		$('#data-table-default').DataTable({
			responsive: true,
			pageLength: 25
		});

		$('#data-table-books').DataTable({
			responsive: true,
			pageLength: 25,
			drawCallback: function () {
				// Loop through each row after table is drawn
				$('#data-table-books tbody tr').each(function () {
					const $row = $(this);
					const bookId = $row.data('book-id');

					// AJAX call to fetch extra book data
					$.ajax({
						url: `/api/books/${bookId}/details`,
						method: 'GET',
						success: function (data) {
							$row.find('.series').text(data.series);
							$row.find('.issue').text(data.issue);
							$row.find('.title').text(data.title);
						},
						error: function () {
							$row.find('.series, .issue, .title').text('');
						}
					});
				});
			}
		});
	</script>
@endpush