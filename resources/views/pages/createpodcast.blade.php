@extends('layouts.default')

@section('title', "Add Podcast")

@section('content')
	<!-- begin page-header -->
	<h1 class="page-header">Add Podcast</h1>
	<!-- end page-header -->

	<!-- begin panel -->
	<div class="panel panel-inverse">
		<div class="panel-heading">
			<h4 class="panel-title">Through RSS URL</h4>
		</div>
		<div class="panel-body">
			<div class="row">
				@if ($errors->any())
					<div class="alert alert-danger alert-dismissible" id="sectionAlert">

						@foreach($errors->all() as $error)
							<li>{{$error}}</li>
						@endforeach
					</div>
				@endif
				<form method="POST">
					@csrf
					<div class="row mb-15px">
						<label class="col-md-1 col-form-label" for="hf-email">Podcast RSS URL</label>
						<div class="col-md-9">
							<input id="rss" class="form-control" name="rss" type="text"   placeholder="Podcast RSS URL" value="{{ old("rss") }}">
						</div>
					</div>
					<input type="hidden" name="type" id="type" value="rss">
					<div class="row">

						<div class="col-md-1 offset-md-1">
							<button class="btn btn-success " type="submit">Add Podcast</button>
						</div>
					</div>
				</form>

			</div>
		</div>
	</div>
	<div class="panel panel-inverse">
		<div class="panel-heading">
			<h4 class="panel-title">Through OPML File</h4>
		</div>
		<div class="panel-body">
			<div class="row">
				@if ($errors->any())
					<div class="alert alert-danger alert-dismissible" id="sectionAlert">

						@foreach($errors->all() as $error)
							<li>{{$error}}</li>
						@endforeach
					</div>
				@endif
				<form method="POST" enctype="multipart/form-data">
					@csrf
					<div class="row mb-15px">
						<label class="col-md-1 col-form-label" for="hf-email">OPML File</label>
						<div class="col-md-9">
							<input id="opml" class="form-control" name="opml" type="file">
						</div>
					</div>
					<div class="row">
						<input type="hidden" name="type" id="type" value="opml">
						<div class="col-md-1 offset-md-1">
							<button class="btn btn-success " type="submit">Add Podcast(s)</button>
						</div>
					</div>
				</form>

			</div>
		</div>
	</div>

	<div class="panel panel-inverse">
		<div class="panel-heading">
			<h4 class="panel-title">Through Search</h4>
		</div>
		<div class="panel-body">
			<div class="row">
				@if ($errors->any())
					<div class="alert alert-danger alert-dismissible" id="sectionAlert">

						@foreach($errors->all() as $error)
							<li>{{$error}}</li>
						@endforeach
					</div>
				@endif
				Coming Soon!

			</div>
		</div>
	</div>
	<!-- end panel -->
@endsection
