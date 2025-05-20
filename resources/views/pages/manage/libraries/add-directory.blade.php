@extends('layouts.default')

@section('title', 'Manage Libraries')

@section('content')
	<!-- begin breadcrumb -->

	<!-- end breadcrumb -->
	<!-- begin page-header -->
	<h1 class="page-header">Manage Library - {{$library->name}} - Add Directory</h1>
	<!-- end page-header -->

	<!-- begin panel -->
	@if(session('status'))
		<div class="alert alert-success alert-dismissible" id="sectionAlert">

			<li>{{session('status')}}</li>

		</div>
	@endif

	<div class="panel panel-inverse">
		<div class="panel-heading">
			<h4 class="panel-title">Directories</h4>
			<div class="panel-heading-btn">
				<!--<button class="btn btn-xs btn-primary" data-bs-toggle="modal" data-bs-target="#addDirectory" >Add Directory</button>-->
			</div>
		</div>
		<div class="panel-body">
			<div class="mb-5 fw-bold fs-13px">
				<div class="modal-body" style="height:600px; overflow-y: auto">
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

					<button type="button" id="AddDirectory"  class="btn btn-primary">Next</button>
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
