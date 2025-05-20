@extends('layouts.default', ['appContentClass' => 'p-0'])

@section('title', 'Profile Page')

@push('css')
	<link href="/assets/plugins/superbox/superbox.min.css" rel="stylesheet" />
	<link href="/assets/plugins/lity/dist/lity.min.css" rel="stylesheet" />
@endpush

@push('scripts')
	<script src="/assets/plugins/superbox/jquery.superbox.min.js"></script>
	<script src="/assets/plugins/lity/dist/lity.min.js"></script>
	<script src="/assets/js/demo/profile.demo.js"></script>
@endpush

@section('content')

	<!-- BEGIN profile -->
	<div class="profile">
		<div class="profile-header">
			<!-- BEGIN profile-header-cover -->
			<div class="profile-header-cover"></div>
			<!-- END profile-header-cover -->
			<!-- BEGIN profile-header-content -->
			<div class="profile-header-content">
				<!-- BEGIN profile-header-img -->

				<!-- END profile-header-img -->
				<!-- BEGIN profile-header-info -->
				<div class="profile-header-info">
					<h4 class="mt-0 mb-1">{{Auth::user()->name}}</h4>
					<a href="/profile" class="btn btn-xs btn-yellow">Edit Profile</a>
				</div>
				<!-- END profile-header-info -->
			</div>
			<!-- END profile-header-content -->
			<!-- BEGIN profile-header-tab -->
			<ul class="profile-header-tab nav nav-tabs">
				<li class="nav-item"><a href="#profile-about" class="nav-link active" data-bs-toggle="tab">ABOUT</a></li>
			</ul>
			<!-- END profile-header-tab -->
		</div>
	</div>
	<!-- END profile -->
	<!-- BEGIN profile-content -->
	<div class="profile-content">
		<!-- BEGIN tab-content -->
		<div class="tab-content p-0">
			<!-- BEGIN #profile-post tab -->
			<div class="tab-pane fade show active" id="profile-post">
				<!-- BEGIN table -->
				<div class="table-responsive form-inline">
					@if ($errors->any())
						<div class="alert alert-danger alert-dismissible" id="sectionAlert">

							@foreach($errors->all() as $error)
								<li>{{$error}}</li>
							@endforeach
						</div>
					@endif
					<form method="POST">
					<table class="table table-profile align-middle">
						@csrf
						<thead>
						<tr>
							<th></th>
							<th>
								<h4>{{$user->name}}</h4>
							</th>
						</tr>
						</thead>
						<tbody>
						<tr class="highlight">
							<td class="field">Name</td>
							<td><input class="form-control" type="text" placeholder="Name" id="name" name="name" value="{{ old('name') ?? $user->name }}" /></td>
						</tr>
						<tr class="highlight">
							<td class="field">Email Address</td>
							<td><input class="form-control" type="text" placeholder="Email" id="email" name="email" value="{{ old('email') ?? $user->email }}" /></td>
						</tr>
						<tr class="divider">
							<td colspan="2"></td>
						</tr>
						<tr>
							<td class="field">Password</td>
							<td><input class="form-control" type="password" id="password" name="password"></td>
						</tr>
						<tr>
							<td class="field">Password (confirm)</td>
							<td><input class="form-control" type="password" id="passwordConfirm" name="passwordConfirm"></td>
						</tr>

						<tr class="divider">
							<td colspan="2"></td>
						</tr>
						<tr class="highlight">
							<td class="field">&nbsp;</td>
							<td class="">
								<button type="submit" class="btn btn-primary w-150px">Update</button>
								<button type="submit" class="btn border-0 w-150px ms-5px">Cancel</button>
							</td>
						</tr>
						</tbody>
					</table>
					</form>
				</div>
				<!-- END table -->
			</div>

		</div>
		<!-- END tab-content -->
	</div>
	<!-- END profile-content -->
@endsection
