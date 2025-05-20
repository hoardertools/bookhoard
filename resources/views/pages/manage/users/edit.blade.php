@extends('layouts.default')

@section('title', 'Edit User')

@section('content')
	<!-- begin breadcrumb -->

	<!-- end breadcrumb -->
	<!-- begin page-header -->
	<h1 class="page-header">Edit User</h1>
	<!-- end page-header -->

	<!-- begin panel -->
	<div class="panel panel-inverse">
		<div class="panel-heading">
			<h4 class="panel-title">Edit User</h4>
		</div>
		<div class="panel-body">
			@if ($errors->any())
				<div class="alert alert-danger alert-dismissible" id="sectionAlert">

					@foreach($errors->all() as $error)
						<li>{{$error}}</li>
					@endforeach
				</div>
			@endif
			<form class="form-horizontal" action="/manage/users/{{$user->id}}" method="POST">
				@method('PUT')
				@csrf

				<div class="row mb-15px">
					<label class="col-md-3 col-form-label" for="hf-email">Name</label>
					<div class="col-md-9">
						<input id="name" class="form-control" name="name" type="text"   placeholder="Name" value="{{ old("name") ?? $user->name }}">
					</div>
				</div>
				<div class="row mb-15px">
					<label class="col-md-3 col-form-label" for="hf-email">Email Address (Username)</label>
					<div class="col-md-9">
						<input id="email" class="form-control" name="email" type="text"   placeholder="Email" value="{{ old("email") ?? $user->email }}">
					</div>
				</div>
				<div class="row mb-15px" >
					<label class="col-md-3 col-form-label" for="hf-email">Can Read</label>
					<div class="col-md-9">
						<input id="canRead" class="form-check" name="canRead" type="checkbox"
						@if(old('canRead'))
							checked
						@elseif($user->canRead)
							checked
						@endif
						>
					</div>
				</div>
				<div class="row mb-15px" >
					<label class="col-md-3 col-form-label" for="hf-email">Can Write</label>
					<div class="col-md-9">
						<input id="canWrite" class="form-check" name="canWrite" type="checkbox"
							   @if(old('canWrite'))
								   checked
							   @elseif($user->canWrite)
								   checked
								@endif
						>
					</div>
				</div>
				<div class="row mb-15px" >
					<label class="col-md-3 col-form-label" for="hf-email">Can Download</label>
					<div class="col-md-9">
						<input id="canDownload" class="form-check" name="canDownload" type="checkbox"
							   @if(old('canDownload'))
								   checked
							   @elseif($user->canDownload)
								   checked
								@endif
						>
					</div>
				</div>
				<div class="row">

					<div class="col-md-2 offset-md-3">
						<button class="btn btn-success " type="submit">Edit User</button>
					</div>
				</div>
			</form>
		</div>

	</div>
	<!-- end panel -->
@endsection