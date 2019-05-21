@extends('layouts.default')

@section(config("app.name"), ' | Dashboard')

@push('css')
    <link href="/assets/plugins/gritter/css/jquery.gritter.css" rel="stylesheet" />
@endpush

@section('content')
	<!-- begin breadcrumb -->
	<ol class="breadcrumb pull-right">
		<li class="breadcrumb-item"><a href="javascript:;">Home</a></li>
		<li class="breadcrumb-item active">Dashboard</li>
	</ol>
	<!-- end breadcrumb -->
	<!-- begin page-header -->
	<h1 class="page-header">Dashboard</h1>
	<!-- end page-header -->
@endsection

@push('scripts')
	<script src="/assets/plugins/gritter/js/jquery.gritter.min.js"></script>
	<script src="/assets/plugins/jquery-sparkline/jquery.sparkline.min.js"></script>
	<script src="/assets/js/demo/dashboard.js"></script>
	<script>
		$(document).ready(function() {
            @if(Session::get('gritter_title'))
				handleDashboardGritterNotification("{{ Session::get('gritter_title') }}", "{{ Session::get('gritter_text') }}", "{{ Session::get('gritter_image') }}");
		    @endif
		});
	</script>
@endpush
