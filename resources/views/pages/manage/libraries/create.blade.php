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
			<h4 class="panel-title">Create Library</h4>
		</div>
		<div class="panel-body">
			@if ($errors->any())
				<div class="alert alert-danger alert-dismissible" id="sectionAlert">

					@foreach($errors->all() as $error)
						<li>{{$error}}</li>
					@endforeach
				</div>
			@endif
			<form class="form-horizontal" action="/manage/libraries" method="POST">
				@csrf

				<div class="row mb-15px">
					<label class="col-md-3 col-form-label" for="hf-email">Name</label>
					<div class="col-md-9">
						<input id="name" class="form-control" name="name" type="text"   placeholder="Name" value="{{ old("name") }}">
					</div>
				</div>
				<div class="row mb-15px">
					<label class="col-md-3 col-form-label" for="hf-email">Type</label>
					<div class="col-md-9">
						<select class="form-control" name="type">
							<option value="books" @if(old("type") == "books") selected @endif >Books</option>
							<option value="comics" @if(old("type") == "comics") selected @endif >Comics</option>
							<option value="magazines" @if(old("type") == "magazines") selected @endif >Magazines</option>
							<option value="newspapers" @if(old("type") == "newspapers") selected @endif >Newspapers</option>
						</select>
					</div>
				</div>
				<div class="row">
					<p>
					In this first step you will be able to create a new library. In the next steps, additional settings and library directories can be specified.</p>
				</div>
				<div class="row">

					<div class="col-md-2 offset-md-3">
						<button class="btn btn-success " type="submit">Create Library</button>
					</div>
				</div>
			</form>
		</div>

	</div>
	<!-- end panel -->
@endsection