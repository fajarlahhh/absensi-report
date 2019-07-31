<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/><meta charset="utf-8" />
	<title>{{ config("app.name") }} @yield('title')</title>
	<meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport" />
	<link rel="icon" href="/assets/img/logo/favicon.png" type="image/gif">
	<meta content="Absensi PDAM Giri Menang" name="description" />
	<meta content="Andi Fajar Nugraha" name="author" />
	<meta name="csrf-token" content="{{ csrf_token() }}">

	<!-- ================== BEGIN BASE CSS STYLE ================== -->
	<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
	<link href="/assets/css/default/style.css" rel="stylesheet" />
	<link href="/assets/css/default/style-responsive.css" rel="stylesheet" />
	<link href="/assets/css/default/theme/default.css" rel="stylesheet" id="theme" />
	<!-- ================== END BASE CSS STYLE ================== -->

	<!-- ================== BEGIN BASE JS ================== -->
	<script src="/assets/plugins/pace/pace.js"></script>
	<!-- ================== END BASE JS ================== -->
	<style type="text/css">
		.numbering{
			text-align: right;
		}
		@page {
		    size: auto;
		}
	</style>

</head>
@php
	$bodyClass = (!empty($boxedLayout)) ? 'boxed-layout ' : '';
	$bodyClass .= (!empty($paceTop)) ? 'pace-top ' : '';
	$bodyClass .= (!empty($bodyExtraClass)) ? $bodyExtraClass . ' ' : '';
@endphp
<body class="bg-white">
	<div class="text-center">
		<img src="/assets/img/logo/favicon.png" width="80" alt="">
	</div>
	
	@yield('content')
			
	@include('includes.page-js')
</body>
</html>