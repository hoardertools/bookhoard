@extends('layouts.default', [
	'paceTop' => true,
	'appSidebarHide' => true,
	'appHeaderHide' => true,
	'appContentClass' => 'p-0'
])

@section('title', 'Access Denied')

@section('content')
    <!-- BEGIN error -->
    <div class="error">
        <div class="error-code">Access Denied</div>
        <div class="error-content">
            <div class="error-message">Sorry!</div>
            <div class="error-desc mb-4">
                You don't have permission to access this page or resource.
            </div>
            @if(Auth::check())
                <div class="error-desc mb-4">
                    You are currently logged in, but don't have permissions for the action you are trying to perform, nor are global permissions enabled for this action.
                </div>
                <div>
                    <a href="/" class="btn btn-success px-3">Go Home</a>
                </div>
            @endif
            @if(!Auth::check())
                <div class="error-desc mb-4">
                    You are currently <b>not</b> logged in, and global permissions are not enabled for this action.
                </div>
                <div>
                    <a href="/login" class="btn btn-success px-3">Login</a>
                </div>
            @endif
        </div>
    </div>
    <!-- END error -->
@endsection

