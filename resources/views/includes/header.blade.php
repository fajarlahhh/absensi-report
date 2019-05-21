	@php
		$headerClass = (!empty($headerInverse)) ? 'navbar-inverse ' : 'navbar-default ';
	@endphp
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
					<img src="{{ (Auth::user()->pegawai->foto? Auth::user()->pegawai->foto: '/assets/img/user/user.png') }}" alt="" />
					<span class="d-none d-md-inline">{{ strtoupper(Auth::user()->pegawai->nm_pegawai) }}</span> <b class="caret"></b>
				</a>
				<div class="dropdown-menu dropdown-menu-right">
					<a href="javascript:;" class="dropdown-item">Edit Profil</a>
					<a href="javascript:;" class="dropdown-item">Ganti Kata Sandi</a>
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
