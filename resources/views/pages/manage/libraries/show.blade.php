@extends('layouts.default')

@section('title', 'Manage Libraries')

@section('content')
	<!-- begin breadcrumb -->

	<!-- end breadcrumb -->
	<!-- begin page-header -->
	<h1 class="page-header">Manage Library - {{$library->name}}</h1>
	<!-- end page-header -->

	<!-- begin panel -->
	@if(session('status'))
		<div class="alert alert-success alert-dismissible" id="sectionAlert">

			<li>{{session('status')}}</li>

		</div>
	@endif
	<div class="panel panel-inverse">
		<div class="panel-heading">
			<h4 class="panel-title">{{$library->name}}</h4>
		</div>
		<div class="panel-body">
				<form class="row row-cols-lg-6 g-6 align-items-center" method="POST" action="/manage/libraries/{{$library->slug}}/updateName">@csrf
					<div class="col-6">Library Name</div>
					<div class="col-12"><input id="name" class="form-control" name="name" type="text"   placeholder="Name" value="{{ old("name") ?? $library->name }}"></div>
					<button type="submit" class="btn btn-primary w-100px me-5px">Save</button>
				</form>
			<br>
			<form class="row row-cols-lg-6 g-6 align-items-center" >
				<div class="col-6">Type</div>
				<div class="col-12">{{ucfirst($library->type)}}</div>

			</form>
		</div>

	</div>

	<div class="panel panel-inverse">
		<div class="panel-heading">
			<h4 class="panel-title">Directories</h4>
			<div class="panel-heading-btn">
				<!--<button class="btn btn-xs btn-primary" data-bs-toggle="modal" data-bs-target="#addDirectory" >Add Directory</button>-->
				<a class="btn btn-xs btn-primary" href="/manage/libraries/{{$library->slug}}/addDirectory" >Add Directory</a>
			</div>
		</div>
		<div class="panel-body">
			<div class="mb-3 fw-bold fs-13px">
				@foreach($library->directories as $directory)
					@if(is_null($directory->directory_id))
					<div class="d-flex align-items-center mb-3">
						<i class="fa fa-folder fa-lg fa-fw me-10px text-gray-600"></i>
						<div>{{$directory->path}} <a href="/manage/libraries/{{$library->slug}}/removeDirectoryPath/{{base64_encode($directory->path)}}" onclick="return confirm('Are you sure you want to remove this directory path?')">X</a></div>
					</div>
					@endif
					@endforeach

			</div>


		</div>
	</div>




	<div class="modal fade" id="addDirectory" tabindex="-1" aria-labelledby="addDirectory" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="addDirectoryTitle">Add Directory</h5>

				</div>
					<div class="modal-body" style="height:400px; overflow-y: auto">
						<form method="POST" action="/manage/libraries/{{$library->slug}}/addDirectory" id="addDirectoryForm">
							@csrf
						<input type="hidden" name="browsefolder" id="browsefolder" value="/">
						</form>
						<p>Please select a directory to add to the current library:</p>
						<p><b>Current Directory: </b> <i id="currentDirectory">/</i></p>
						<div class="form-group">
							<ul class="list-group" id="dirList">

							</ul>

						</div>

					</div>
					<div class="modal-footer">

						<button type="button" id="AddDirectory"  class="btn btn-primary">Add Current Directory</button>
					</div>
			</div>
		</div>
	</div>


@endsection


@push('scripts')
	<script>

		$(document).ready(function () {

			$.getJSON('/directoryBrowser/Lw==', function (data) {
				$('.dirlistItem').remove();
				$.each(data, function(index, value) {
					$("#dirList").append('<li class="list-group-item d-flex list-group-item-action justify-content-between align-items-center dirlistItem" >' + value + '</li>');
				});
			});

			$("#dirList").on("click", "li", function() {

				if($(this).text() === "Parent Directory"){
					$("#browsefolder").val($("#browsefolder").val().replace($("#browsefolder").val().split("/").pop(), ""));
					$("#browsefolder").val($("#browsefolder").val().substring(0, $("#browsefolder").val().length -1));
					$("#currentDirectory").text($("#currentDirectory").text().replace($("#currentDirectory").val().split("/").pop(), ""));
					$("#currentDirectory").text($("#currentDirectory").text().substring(0, $("#currentDirectory").val().length -1));
					if($("#browsefolder").val().length === 0){
						$("#browsefolder").val("/");
						$("#currentDirectory").text("/");
					}
				}else{
					$("#browsefolder").val($(this).text());
					$("#currentDirectory").text($(this).text());
				}

				$.getJSON('/directoryBrowser/' + window.btoa($("#browsefolder").val()), function (data) {
					$('.dirlistItem').remove();
					$("#dirList").append('<li class="list-group-item d-flex list-group-item-action justify-content-between align-items-center dirlistItem" >Parent Directory</li>');
					$.each(data, function(index, value) {
						$("#dirList").append('<li class="list-group-item d-flex list-group-item-action justify-content-between align-items-center dirlistItem" >' + value + '</li>');
					});
				});
			});

			$("#AddDirectory").on("click", function(){
				$("#addDirectoryForm").submit();

			});

		});


	</script>
	@endpush
