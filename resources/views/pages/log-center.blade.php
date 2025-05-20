@extends('layouts.default')

@section('title', "Download Queue")

@section('content')
	<!-- begin page-header -->
	<h1 class="page-header">Log Center</h1>
	<!-- end page-header -->

	<!-- begin panel -->
	<div class="panel panel-inverse">
		<div class="panel-heading">
			<h4 class="panel-title">Last 2000 Logs</h4>
			<div class="panel-heading-btn">

			</div>
		</div>
		<div class="panel-body">
			<div class="row">
						<table id="data-table-default" class="table table-striped table-bordered align-middle text-nowrap">
							<thead>
							<tr>
								<th>Message</th>
								<th>Level</th>
								<th>Type</th>
								<th>Date/Time</th>
							</tr>
							</thead>
							<tbody>
							@foreach($logs as $log)
								<tr>
									<td>{{$log->message}}</td>
									<td>{{$log->level}}</td>
									<td>{{$log->type}}</td>
									<td>{{$log->created_at}}</td>
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
			order: [[3, 'desc']]
		});


	</script>
@endpush