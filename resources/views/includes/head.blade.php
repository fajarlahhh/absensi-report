<meta charset="utf-8" />
<title>Absensi @yield('title')</title>
<meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport" />
<link rel="icon" href="/assets/img/logo/favicon.png" type="image/gif">
<meta content="" name="description" />
<meta content="" name="author" />
<meta name="csrf-token" content="{{ csrf_token() }}">

<!-- ================== BEGIN BASE CSS STYLE ================== -->
<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
<link href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" rel="stylesheet" />
<link href="/assets/css/bundle.css" rel="stylesheet" />
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
@stack('css')
