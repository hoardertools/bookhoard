@extends('layouts.default')

@section('title', $book->name)

@section('content')
	<!-- begin page-header -->
	<h1 class="page-header">{{$book->name}}</h1>
	<!-- end page-header -->
	@if ($errors->any())
		<div class="alert alert-danger alert-dismissible" id="sectionAlert">

			@foreach($errors->all() as $error)
				<li>{{$error}}</li>
			@endforeach
		</div>

	@endif
	@if(session('status'))
		<div class="alert alert-success alert-dismissible" id="sectionAlert">

			<li>{{session('status')}}</li>

		</div>
	@endif
	<div class="result-list">
		<div class="result-item col-lg-8  center">
			<div class="result-info">
				<h4 class="title">
						{{$book->name}}</h4>

				<p class="desc">
					{{$book->description ?? "No description"}}
				</p>
				<div class="btn-row">
					@php
						function formatBytes($size, $precision = 2)
						{
							$base = log($size, 1024);
							$suffixes = array('', 'KB', 'MB', 'GB', 'TB');

							return round(pow(1024, $base - floor($base)), $precision) .' '. $suffixes[floor($base)];
						}

                        if(file_exists($book->path)){
                            $size = formatBytes(filesize($book->path));
						} else {
							$size = "File currently not found";
                        }


					@endphp
					<button class="btn btn-primary"><b>Size: {{$size ?? "File currently not found"}}</b></button>&nbsp; - <a href="/getComic/{{$book->id}}" class="btn btn-sm btn-success"><b>Download</b></a>&nbsp;
				</div><br>
				<table class="table table-responsive-sm table-striped">
					<tbody>
					<tr>
						<td>Filename</td>
						<td>{{basename($book->path)}}</td>
					</tr>
					<tr>
						<td>Path</td>
						<td>{{$book->path}}</td>
					</tr>

					@foreach($book->otherMeta() as $meta)
						@if($meta->key != "title")
							@if($meta->key != "series")
								@if($meta->key != "issue")
								<tr>
									<td>{{ucfirst($meta->key)}}</td>
									@if($meta->key == "author")
										<td>
											<a href="/author/{{$meta->id}}">{{$meta->value}}</a>
										</td>
									@else
										<td>{{$meta->value}}</td>
									@endif
								</tr>
								@endif
							@endif
						@endif
					@endforeach
					</tbody>
				</table>
				@if($book->has_image)
					<p class="img">
						{!! $book->renderImage() !!}
					</p>
				@endif

			</div>

		</div>

	</div>


@endsection

@push('scripts')
	<link href="/assets/plugins/datatables.net-bs5/css/dataTables.bootstrap5.min.css" rel="stylesheet" />
	<link href="/assets/plugins/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css" rel="stylesheet" />
	<script src="/assets/plugins/datatables.net/js/dataTables.min.js"></script>
	<script src="/assets/plugins/datatables.net-bs5/js/dataTables.bootstrap5.min.js"></script>
	<script src="/assets/plugins/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
	<script src="/assets/plugins/datatables.net-responsive-bs5/js/responsive.bootstrap5.min.js"></script>
	<script>



	</script>
@endpush