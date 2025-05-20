@extends('layouts.default')

@section('title', 'Manage Libraries')

@section('content')
	<!-- begin breadcrumb -->

	<!-- end breadcrumb -->
	<!-- begin page-header -->
	<h1 class="page-header">Manage Libraries</h1>
	<!-- end page-header -->

	<!-- begin panel -->
	<div class="panel panel-inverse">
		<div class="panel-heading">
			<h4 class="panel-title">Existing Libraries</h4>
			<div class="panel-heading-btn">
				<a href="/manage/libraries/create" class="btn btn-xs  btn-primary">Add Library</a>
			</div>
		</div>
		<div class="panel-body">
			@if(session('status'))
				<div class="alert alert-success alert-dismissible" id="sectionAlert">

					<li>{{session('status')}}</li>

				</div>
			@endif
			<table id="data-table-users" class="table table-striped table-responsive">
				<thead>
				<tr>
					<th>Library</th>
					<th>Actions</th>
				</tr>
				</thead>
				<tbody>
				@foreach($libraries as $library)
					<tr>
						<td>{{$library->name}}</td>
						<td>
							<a href="/manage/libraries/{{$library->slug}}" class="btn btn-xs btn-primary">Manage</a>
							<a href="/manage/libraries/{{$library->slug}}/rescan" class="btn btn-xs btn-success">Rescan</a>
						</td>
					</tr>
				@endforeach
				</tbody>
			</table>

			@section('js')
				<!-- script -->
				<link href="/v2/assets/plugins/datatables.net-bs5/css/dataTables.bootstrap5.min.css" rel="stylesheet" />
				<link href="/v2/assets/plugins/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css" rel="stylesheet" />
				<script src="/v2/assets/plugins/datatables.net/js/jquery.dataTables.min.js"></script>
				<script src="/v2/assets/plugins/datatables.net-bs5/js/dataTables.bootstrap5.min.js"></script>
				<script src="/v2/assets/plugins/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
				<script src="/v2/assets/plugins/datatables.net-responsive-bs5/js/responsive.bootstrap5.min.js"></script>
				<script>
					$('#data-table-users').DataTable({
						responsive: true,
					});
					@if(isset($tableSorting))
					var table = $('#data-table-users').DataTable();
					var order = table.order( [{{$tableSorting[0]}}, "{{$tableSorting[1]}}" ] ).draw();
					@endif

				</script>
			@endsection

		</div>
	</div>
	<!-- end panel -->
@endsection