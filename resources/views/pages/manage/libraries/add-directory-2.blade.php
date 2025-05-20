@extends('layouts.default')

@section('title', 'Manage Libraries')

@section('content')
	<!-- begin breadcrumb -->
@if(empty($browsefolder))

@endif
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
			<h4 class="panel-title">Directory to add: {{$browsefolder}}</h4>
			<div class="panel-heading-btn">
				<!--<button class="btn btn-xs btn-primary" data-bs-toggle="modal" data-bs-target="#addDirectory" >Add Directory</button>-->
			</div>
		</div>
		<div class="panel-body">
			<div class="mb-5 fw-bold fs-13px">
				<div class="modal-body" style="height:600px; overflow-y: auto">
					<form id="regexForm" method="POST" action="{{ route('regex.parse') }}">
						@csrf
						<label class="block mb-2 font-semibold">To enable advanced processing for existing files utilizing regex, please enter a suitable regex below and click Parse. To skip, click Next.</label>
						<br>
						<small>Example: <pre>{!! htmlspecialchars('/^(?<series>.+?) - (?<issue>\d{2}) - (?<title>.+)\.cbr$/i') !!}</pre>
						</small>
						<input type="text" name="regex" id="regex" class="w-full border rounded p-2 mb-10 col-lg-10" placeholder="Regex" required>

						<button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Parse</button><br>
						<small>Note: You must click Parse for the Regex to be included within the directory parsing settings.</small>
					</form><br>
					<div id="groupContainer" class="mt-6 hidden">
						<h3 class="text-xl font-semibold mb-2">Discovered Meta Tags</h3>
						<form id="groupForm">
							<div id="dropdowns" class="space-y-4"></div>
						</form>
						<h3 class="text-xl font-semibold mb-2">System-Special Meta Tags</h3>
						While you can use any meta tag / group tag you'd like, and it will reflect within the search engine, there are some special tags that are used by the system to help with processing. These are:
						<ul class="list-disc pl-5">
							<li><strong>series</strong> - The name of the series.</li>
							<li><strong>issue</strong> - The issue number.</li>
							<li><strong>title</strong> - The title of the issue.</li>
							<li><strong>year</strong> - The year of publication.</li>
							<li><strong>date</strong> - The date of publication.</li>
						</ul>
					</div>
				</div>
				<div class="modal-footer">
					<form method="POST">
						@csrf
						<input type="hidden" name="regex2" id="regex2" value="">
						<input type="hidden" name="folder" id="folder" value="{{$browsefolder}}">
					<button type="submit" id="AddDirectory"  class="btn btn-primary">Add Folder</button>
					</form>
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

		document.getElementById('regexForm').addEventListener('submit', async function(e) {
			e.preventDefault();
			const regex = document.getElementById('regex').value;
			//Set "regex" input field value to the regex value
			document.getElementById('regex2').value = regex;
			const response = await fetch('{{ route('regex.parse') }}', {
				method: 'POST',
				headers: {
					'Content-Type': 'application/json',
					'X-CSRF-TOKEN': '{{ csrf_token() }}'
				},
				body: JSON.stringify({ regex })
			});

			const data = await response.json();
			const container = document.getElementById('groupContainer');
			const dropdowns = document.getElementById('dropdowns');
			dropdowns.innerHTML = ''; // clear previous results

			if (data.groups.length > 0) {
				const ul = document.createElement('ul');
				ul.classList.add('list-disc', 'pl-5');

				data.groups.forEach(group => {
					const li = document.createElement('li');
					li.textContent = group;
					ul.appendChild(li);
				});

				dropdowns.appendChild(ul);
				container.classList.remove('hidden');
			} else {
				alert('No named groups found.');
			}

		});
	</script>
	@endpush
