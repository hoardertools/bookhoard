@extends('layouts.default', ['appContentClass' => 'p-0'])

@section('title', 'General Settings')

@push('css')
	<link href="/assets/plugins/superbox/superbox.min.css" rel="stylesheet" />
	<link href="/assets/plugins/lity/dist/lity.min.css" rel="stylesheet" />
@endpush

@push('scripts')
	<script src="/assets/plugins/superbox/jquery.superbox.min.js"></script>
	<script src="/assets/plugins/lity/dist/lity.min.js"></script>

@endpush

@section('content')

	<!-- BEGIN settings -->
	<div class="settings">
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
					<h4 class="mt-0 mb-1">Settings</h4>
				</div>
				<!-- END profile-header-info -->
			</div>
			<!-- END profile-header-content -->
			<!-- BEGIN profile-header-tab -->
			@include('pages.manage.settings-menu')
			<!-- END profile-header-tab -->
		</div>
	</div>
	<!-- END profile -->

	<!-- BEGIN settings-content -->
	<div class="profile-content">
		<!-- BEGIN tab-content -->
		<div class="tab-content p-0">
			<!-- BEGIN #settings-general tab -->
			<div class="tab-pane fade show active" id="settings-general">
				<!-- BEGIN table -->
				<div class="table-responsive form-inline">

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
					<form method="POST">
						<table class="table table-profile align-middle">
							@csrf
							<thead>
							<tr>
								<th></th>
								<th>
									<h4></h4>
								</th>
							</tr>
							</thead>
							<tbody>
							<tr>
								<td class="field">Enable Global Read Permissions</td>
								<td class="">
									<input type="checkbox" class="form-check" name="GlobalReadPermissions" id="GlobalReadPermissions" value="1"
									@if(old('GlobalWritePermissions'))
										checked
									@elseif(\App\Setting::where("key", "=", "GlobalReadPermissions")->first()->value)
										checked
									@endif>
								</td>
							</tr>
							<tr>
								<td class="field">Enable Global Download / Listen Permissions</td>
								<td class="">
									<input type="checkbox" class="form-check" name="GlobalDownloadPermissions" id="GlobalWritePermissions" value="1"
										   @if(old('GlobalWritePermissions'))
											   checked
										   @elseif(\App\Setting::where("key", "=", "GlobalDownloadPermissions")->first()->value)
											   checked
											@endif>
								</td>
							</tr>
							<tr>
								<td class="field">Enable Global Write Permissions (NOT RECOMMENDED)</td>
								<td class="">
									<input type="checkbox" class="form-check" name="GlobalWritePermissions" id="GlobalDownloadPermissions" value="1"
										   @if(old('GlobalWritePermissions'))
											   checked
										   @elseif(\App\Setting::where("key", "=", "GlobalWritePermissions")->first()->value)
											   checked
											@endif>
								</td>
							</tr>
							<tr class="highlight">
								<td class="field">Library Default View</td>
								<td class="">
									<select class="form-select" style="width:100px" name="DefaultView" >
										<option value="grid" @if(old('DefaultView') == "grid") selected @elseif(\App\Setting::where("key", "=", "DefaultView")->first()->value == "grid") selected @endif>Grid</option>
										<option value="table" @if(old('DefaultView') == "table") selected @elseif(\App\Setting::where("key", "=", "DefaultView")->first()->value == "table") selected @endif>Table</option>
									</select>
								</td>
							</tr>
							<tr class="highlight">
								<td class="field">Custom User Agent</td>
								<td class="">
									<input type="text" class="form-text" name="CustomUserAgent" id="CustomUserAgent" value="{{old('CustomUserAgent') ?? \App\Setting::where("key", "=", "CustomUserAgent")->first()->value}}">
								</td>
							</tr>
							<tr>
								<td class="field">Downloader Rate Limit (Global)</td>
								<td class="">
									<input type="text" class="form-text" name="GlobalDownloaderRateLimit" id="GlobalDownloaderRateLimit" value="{{old('GlobalDownloaderRateLimit') ?? \App\Setting::where("key", "=", "GlobalDownloaderRateLimit")->first()->value}}">
									<small>Fill in a per-minute maximum number of downloads the system will perform. 0 means unlimited.</small>
								</td>
							</tr>
							<tr>
								<td class="field">Downloader Rate Limit (Per Host)</td>
								<td class="">
									<input type="text" class="form-text" name="PerHostDownloaderRateLimit" id="PerHostDownloaderRateLimit" value="{{old('PerHostDownloaderRateLimit') ?? \App\Setting::where("key", "=", "PerHostDownloaderRateLimit")->first()->value}}">
									<small>Fill in a per-minute maximum number of downloads the system will perform per given host. 0 means unlimited.</small>
								</td>
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

@section('js')



@endsection