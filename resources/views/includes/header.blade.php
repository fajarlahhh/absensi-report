	@php
		$headerClass = (!empty($headerInverse)) ? 'navbar-inverse ' : 'navbar-default ';
	@endphp
	@push('css')
		<link href="/assets/plugins/password-indicator/css/password-indicator.css" rel="stylesheet" />
		<link href="/assets/plugins/parsleyjs/src/parsley.css" rel="stylesheet" />
	@endpush
	<!-- begin #header -->
	<div id="header" class="header {{ $headerClass }}">
		<!-- begin navbar-header -->
		<div class="navbar-header">
			<a href="/" class="navbar-brand"><img src="/assets/img/logo/favicon.png" alt="" /> <b>PDAM GM</b> {{ config("app.name") }}</a>
			<button type="button" class="navbar-toggle" data-click="sidebar-toggled">
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
		</div>
		<!-- end navbar-header -->
		
		
		<!-- begin header-nav -->
		<ul class="navbar-nav navbar-right">
			<li class="dropdown navbar-user">
				<a href="#" class="dropdown-toggle" data-toggle="dropdown">
					<img src="{{ ($foto? $foto: '/assets/img/user/user.png') }}" alt="" />
					<span class="d-none d-md-inline">{{ strtoupper(Auth::user()->pegawai->nm_pegawai) }}</span> <b class="caret"></b>
				</a>
				<div class="dropdown-menu dropdown-menu-right">
					<a href="#modal-katasandi" class="dropdown-item" data-toggle="modal">Ganti Kata Sandi</a>
					<div class="dropdown-divider"></div>
					<a href="{{ route('logout') }}" class="dropdown-item" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">{{ __('Logout') }}</a>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
				</div>
			</li>
		</ul>
		<!-- end header navigation right -->
	</div>
	<!-- end #header -->

	<div class="modal fade" id="modal-katasandi">
		<div class="modal-dialog">
			<div class="modal-content">
				<form action="/gantisandi" method="post" data-parsley-validate="true" data-parsley-errors-messages-disabled="">
					@csrf
					<div class="modal-header">
						<h4 class="modal-title">Ganti Kata Sandi</h4>
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
					</div>
					<div class="modal-body">
						<div class="form-group">
							<label class="control-label">Kata Sandi Lama</label>
							<input data-toggle="password" class="form-control" type="password" name="pengguna_sandi_lama"  required />
						</div>
						<div class="form-group">
							<label class="control-label">Kata Sandi Baru</label>
							<input data-toggle="password" class="form-control" type="password" name="pengguna_sandi_baru" data-parsley-minlength="8" required />
						</div>
					</div>
					<div class="modal-footer">
						<a href="javascript:;" class="btn btn-white" data-dismiss="modal">Close</a>
						<input type="submit" value="Simpan" class="btn btn-success">
					</div>
				</div>
			</form>
		</div>
	</div>

@push('scripts')
	<script src="/assets/plugins/bootstrap-show-password/bootstrap-show-password.js"></script>
	<script src="/assets/plugins/parsleyjs/dist/parsley.js"></script>
@endpush