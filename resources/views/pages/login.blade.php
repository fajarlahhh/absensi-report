@extends('layouts.empty', ['paceTop' => true, 'bodyExtraClass' => 'bg-white'])

@section('title', ' | Login')

@push('css')
	<link href="/assets/plugins/parsleyjs/src/parsley.css" rel="stylesheet" />
	<link href="/assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker.css" rel="stylesheet" />
	<link href="/assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.css" rel="stylesheet" />
@endpush

@section('content')
	<div class="login-cover">
	    <div class="login-cover-image" style="background-image: url(../assets/img/login-bg/login-bg.jpg)" data-id="login-cover-image"></div>
	    <div class="login-cover-bg"></div>
	</div>
	<!-- begin login -->
	<div class="login login-v2" data-pageload-addclass="animated fadeIn">
		<!-- begin brand -->
		<div class="login-header">
			<div class="brand">
	            <img src="/assets/img/logo/favicon.png" height="30"> {{ config("app.name") }}
				<small>Dinas Pendidikan dan Kebudayaan</small>
			</div>
			<div class="icon">
				<i class="fa fa-lock"></i>
			</div>
		</div>
		<!-- end brand -->
		<!-- begin login-content -->
		<div class="login-content">
			<form action="{{ route('login') }}" method="POST" class="margin-bottom-0" data-parsley-validate="true" data-parsley-errors-messages-disabled="">
				@csrf
				<div class="form-group m-b-20">
					<input type="text" class="form-control form-control-lg" autocomplete="off" name="uid" placeholder="ID" value="{{ old('uid') }}" required />
				</div>
				<div class="form-group m-b-20">
					<input type="password" class="form-control form-control-lg" name="password" placeholder="Kata Sandi" value="{{ old('password') }}" required />
				</div>
				<div class="checkbox checkbox-css m-b-20">
					<input type="checkbox" id="remember" name="remember" /> 
					<label for="remember">
						Ingat Saya
					</label>
				</div>
				<div class="login-buttons">
					<button type="submit" class="btn btn-success btn-block btn-lg">Login</button>
				</div>
			</form>
			© 2019 
			<small class="float-right pt-1">v1.0</small>
		</div>
		<!-- end login-content -->
	</div>
	<!-- end login -->
@endsection

@push('scripts')
	<script src="/assets/plugins/parsleyjs/dist/parsley.js"></script>
	<script src="/assets/plugins/sweetalert/sweetalert.min.js"></script>
	<script src="/assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
	<script>
		$(document).ready(function() {
            @if(Session::get('alert'))
                swal("Login Gagal", "{{ Session::get('alert') }}", "error");
            @endif
		});

		$('#datepicker1').datepicker({
			todayHighlight: true,
			format: 'dd MM yyyy',
			autoclose: true
		});

		$('#datepicker2').datepicker({
			todayHighlight: true,
			format: 'dd MM yyyy',
			autoclose: true
		});
	</script>
@endpush
