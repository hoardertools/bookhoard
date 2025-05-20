@extends('layouts.default')

@section('title', $podcast->name)

@section('content')
	<!-- begin page-header -->
	<h1 class="page-header">{{$podcast->name}}</h1>
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
						{{$podcast->name}}</h4>
				@if($podcast->publisher)
				<p class="location">
					From {{$podcast->publisher}}

					</p>
				@endif
				<p class="desc">
					{{$podcast->description ?? "No description"}}
				</p>
				<div class="btn-row">
					@php
						function formatBytes($size, $precision = 2)
						{
							$base = log($size, 1024);
							$suffixes = array('', 'KB', 'MB', 'GB', 'TB');

							return round(pow(1024, $base - floor($base)), $precision) .' '. $suffixes[floor($base)];
						}

                        $size = formatBytes($podcast->total_size);

					@endphp
					<button class="btn btn-primary"><b>Size: {{$size}}</b></button>&nbsp;
					<button class="btn btn-gray"><b>Play Time: {{round($podcast->total_playtime/60/60, 2)}} hours</b></button>&nbsp;
					@if(\App\Http\Functions\HasWritePermissions::check())
						<a data-bs-toggle="modal" data-bs-target="#editPodcast" data-container="body" data-title="Configuration"><i class="fa fa-fw fa-edit"></i></a>
						<a href="/library/{{$podcast->library->slug}}/podcast/{{$podcast->id}}/refreshPodcast" data-container="body" data-title="Refresh"><i class="fa fa-fw fa-refresh"></i></a>
						<a href="{{$rss}}"  data-title="RSS Feed"><i class="fa fa-fw fa-rss"></i></a>

					@endif
				</div>
			</div>

		</div>

	</div>

	@foreach($podcast->episodes()->orderBy("published_at", "DESC")->get() as $episode)
		<div class="row">&nbsp;</div>
		<div class="result-list">
			<div class="result-item col-lg-6 offset-lg-1">
				<div class="result-info">
					<h4 class="title">{{$episode->getEpisodeName()}}</h4>
					<p class="location">{{$episode->published_at}}</p>
					<p class="desc">
						@if(strlen($episode->description) > 0)
							{{strip_tags($episode->description)}}
						@else
							No description
						@endif
					</p>
					<div class="btn-row">
						@php

                            $size = formatBytes($episode->filesize);

						@endphp
						<button class="btn btn-primary"><b>Size: {{$size}}</b></button>&nbsp;
						<button class="btn btn-gray"><b>Play Time: {{round($episode->duration/60, 2)}} minutes</b></button>&nbsp;
						<a href="/downloadFile/{{$episode->id}}" data-toggle="tooltip" data-container="body" data-title="Download"><i class="fa fa-fw fa-download"></i></a>
					</div>
				</div>

			</div>

		</div>
	@endforeach



	@if(\App\Http\Functions\HasWritePermissions::check())
		<div class="modal fade" id="editPodcast" tabindex="-1" aria-labelledby="addDirectory" aria-hidden="true">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="addDirectoryTitle">Edit Podcast</h5>

					</div>
					<form method="POST" action="/library/{{$podcast->library->slug}}/podcast/{{$podcast->id}}" id="editPodcastForm">
					<div class="modal-body"  style="overflow-y: auto">

							@csrf

							<div class="row mb-15px">
								<label class="col-md-3 col-form-label" for="hf-email">Name</label>
								<div class="col-md-9">
									<input id="name" class="form-control" name="name" type="text"   placeholder="Name" value="{{ $podcast->name }}">
								</div>
							</div>
							<div class="row mb-15px">
								<label class="col-md-3 col-form-label" for="hf-email">Description</label>
								<div class="col-md-9">
									<textarea id="description" class="form-control" name="description" type="text"   placeholder="Description" >{{ $podcast->description }}</textarea>
								</div>
							</div>
							<div class="row mb-15px">
								<label class="col-md-3 col-form-label" for="hf-email">RSS Feed (inbound)</label>
								<div class="col-md-9">
									<input id="rss" class="form-control" name="rss" type="text"   placeholder="RSS" value="{{ $podcast->rssUrl }}">
								</div>
							</div>
							<div class="row mb-15px">
								<label class="col-md-3 col-form-label" for="hf-email">Cover Image</label>
								<div class="col-md-9">
									<input id="cover" class="form-control" name="cover" type="file" placeholder="Cover Image">
								</div>
							</div>
							<div class="row mb-15px">
								<label class="col-md-3 col-form-label" for="hf-email">Publisher</label>
								<div class="col-md-9">
									<input id="publisher" class="form-control" name="publisher" type="text"   placeholder="Publisher" value="{{ $podcast->publisher }}">
								</div>
							</div>

					</div>
					<div class="modal-footer">
						<button type="submit" id="savePodcast"  class="btn btn-primary">Save</button>
					</div>
					</form>
				</div>
			</div>
		</div>
		@endif


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