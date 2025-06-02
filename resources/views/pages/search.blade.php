@extends('layouts.default')

@section('title', 'Search')

@section('content')
	<!-- begin breadcrumb -->

	<!-- end breadcrumb -->
	<!-- begin page-header -->
	<h1 class="page-header">Search</h1>
	<!-- end page-header -->

	<!-- begin panel -->
	<div class="panel panel-inverse">
		<div class="panel-heading">
			<h4 class="panel-title">Search</h4>
		</div>
		<div class="panel-body">
			@if ($errors->any())
				<div class="alert alert-danger alert-dismissible" id="sectionAlert">

					@foreach($errors->all() as $error)
						<li>{{$error}}</li>
					@endforeach
				</div>
			@endif
			<form class="form-horizontal" action="/search" method="POST">
				@csrf

				<div class="row mb-15px">
					<label class="col-md-3 col-form-label" for="hf-email">Keyword</label>
					<div class="col-md-9">
						<input id="keyword" class="form-control" name="keyword" type="text"   placeholder="Keyword" value="{{ old("keyword") }}">
					</div>
				</div>
				<div class="row mb-15px">
					<label class="col-md-3 col-form-label" for="hf-email">Type</label>
					<div class="col-md-9">
						<select class="form-control" name="type" id="type">
								<option>Filename</option>
								<option>Title</option>
								<option>Author</option>
								<option>Directory</option>
								<option disabled>---------</option>
								@foreach($types as $type)
									<option>{{$type->key}}</option>
								@endforeach

						</select>
					</div>
				</div>

				<div class="row">

					<div class="col-md-2 offset-md-3">
						<button class="btn btn-success " type="submit">Search</button>
					</div>
				</div>
			</form>
		</div>

	</div>
	<!-- end panel -->
@endsection