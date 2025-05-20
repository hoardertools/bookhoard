@extends('layouts.default')

@section('title', "Download Queue")

@section('content')
	<!-- begin page-header -->
	<h1 class="page-header">Download Queue</h1>
	<!-- end page-header -->

	<!-- begin panel -->
	<div class="panel panel-inverse">
		<div class="panel-heading">
			<h4 class="panel-title">Pending Downloads</h4>
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
								<th>Episode</th>
								<th>Added</th>
							</tr>
							</thead>
							<tbody>
							@foreach($downloadQueue as $episode)
								<tr>
									<td>{{$episode->title}}</td>
									<td>{{$episode->created_at}}</td>
								</tr>
							@endforeach
							</tbody>
						</table>


			</div>
		</div>
	</div>
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

		$('#data-table-default').DataTable({
			responsive: true,
			pageLength: 25,
			order: [[1, 'asc']]
		});


	</script>
@endpush