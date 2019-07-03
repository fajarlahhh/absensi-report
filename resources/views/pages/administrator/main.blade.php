@extends('layouts.default')

@section('title', ' | Administrator')

@section('content')
	<!-- begin breadcrumb -->
	<ol class="breadcrumb pull-right">
		<li class="breadcrumb-item"><a href="javascript:;">Home</a></li>
		<li class="breadcrumb-item"><a href="javascript:;">Administrator</a></li>
		@yield('page')
	</ol>
	<!-- end breadcrumb -->
	<!-- begin page-header -->
	@yield('header')
	<!-- end page-header -->
	@yield('subcontent')
@endsection

@push('scripts')
	<script src="/assets/plugins/jquery-sparkline/jquery.sparkline.min.js"></script>
@endpush
