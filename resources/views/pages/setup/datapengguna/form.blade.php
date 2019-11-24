@extends('pages.setup.main')

@push('css')
	<link href="/assets/plugins/password-indicator/css/password-indicator.css" rel="stylesheet" />
	<link href="/assets/plugins/bootstrap-select/dist/css/bootstrap-select.min.css" rel="stylesheet" />
	<link href="/assets/plugins/parsleyjs/src/parsley.css" rel="stylesheet" />
@endpush

@section('page')
	<li class="breadcrumb-item">Data Pengguna</li>
	<li class="breadcrumb-item active">{{ $aksi }} Data</li>
@endsection

@section('header')
	<h1 class="page-header">Data Pengguna <small>{{ $aksi }} Data</small></h1>
@endsection

@section('subcontent')
	<div class="panel panel-inverse" data-sortable-id="form-stuff-1">
		<!-- begin panel-heading -->
		<div class="panel-heading">
			<div class="panel-heading-btn">
                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
            </div>
			<h4 class="panel-title">Form</h4>
		</div>
		<form action="/datapengguna/{{ strtolower($aksi) }}" method="post" data-parsley-validate="true" data-parsley-errors-messages-disabled="">
			@csrf
			<div class="panel-body">
				<input type="hidden" name="redirect" value="/datapengguna">
				<input type="hidden" name="id" value="{{ $data? $data->pengguna_id: '' }}">
				<div class="row">
					<div class="col-md-5">
						<div class="form-group">
							<label class="control-label">ID</label>
							<input class="form-control" type="text" name="pengguna_id" value="{{ $data? $data->pengguna_id: '' }}"  @if ($aksi == 'Edit')
							readonly
						@endif required autocomplete="off" />
						</div>
						<div class="form-group">
							<label class="control-label">Nama Pegawai</label>
							<input  class="form-control" type="text" name="pengguna_nama" value="{{ $data? $data->pengguna_nama: '' }}" required autocomplete="off" />
						</div>
						<div class="form-group">
							<label class="control-label">Kata Sandi</label>
							<input class="form-control" type="password" name="pengguna_sandi" id="pengguna_sandi" data-parsley-minlength="5" @if ($aksi == 'Tambah')
								required
							@endif autocomplete="off" />
						</div>
						<div class="form-group">
							<label class="control-label">Level</label>
							<select class="form-control selectpicker" style="width : 100%" name="pengguna_level" id="pengguna_level" data-style="btn-info" onchange="hakakses()" data-width="100%">
								@foreach($level as $lvl)
								<option value="{{ $lvl->id }}" 
									@if($aksi == 'Edit' && $data->getRoleNames()[0] == $lvl->name)
										selected
									@endif
								>{{ ucfirst($lvl->name) }}</option>
								@endforeach
							</select>
						</div>
					</div>
					<div class="col-md-7">
	                     <div class="panel-body row">
                        	@php
                        		$permission = ($aksi == 'Edit'? $data->getAllPermissions(): []);
								$i = 0;
								foreach (config('sidebar.menu') as $key => $menu) {
									if ($menu['title'] != 'Dashboard') {
										$subMenu = '';
									
										if (!empty($menu['sub_menu'])) {
											foreach ($menu['sub_menu'] as $key => $sub) {
												$subMenu .= "<div class='hakakses checkbox checkbox-css col-md-12'>
																<input type='checkbox' onchange='parent(\"cssCheckbox".$i."\")' class='cssCheckbox".$i."' id='cssCheckbox".substr($sub['url'], 1)."' name='izin[]' value='".substr($sub['url'], 1)."' ".($aksi == 'Edit'? ($data->roles[0]->name == 'administrator'? 'checked': (sizeof($permission) > 0 && $data->hasPermissionTo(substr($sub['url'], 1))? 'checked': '')): '')."/>
																<label for='cssCheckbox".substr($sub['url'], 1)."' class='p-l-5'>".$sub['title']."</label>
															</div>";
											}
										}
							@endphp
								<div class="hakakses checkbox checkbox-css col-md-6 col-lg-4">
									<input type="checkbox" onchange="child('cssCheckbox{{ $i }}')" id="cssCheckbox{{ $i }}" name="izin[]" value="{{ strtolower($menu['title']) }}" {{ ($aksi == 'Edit'? ($data->roles[0]->name == 'administrator'? 'checked': (sizeof($permission) > 0 && $data->hasPermissionTo(strtolower($menu['title']))? 'checked': '')): '') }}/>
									<label for="cssCheckbox{{ $i }}" class="p-l-5">{{ $menu['title'] }}</label>
									{!! $subMenu !!}
								</div>
                        	@php
										$i++;
									}
								}
							@endphp
                        </div>
					</div>
				</div>
			</div>
			<div class="panel-footer">
	            <input type="submit" value="Simpan" class="btn btn-sm btn-success"  />
	            <a href="/datapengguna" class="btn btn-sm btn-danger">Batal</a>
	        </div>
		</form>
	</div>	
    @if ($errors->any())
	<div class="alert alert-danger">
		<ul>
		    @foreach ($errors->all() as $error)
	      	<li>{{ $error }}</li>
		    @endforeach
		</ul>
	</div>
    @endif
@endsection

@push('scripts')
	<script src="/assets/plugins/bootstrap-show-password/bootstrap-show-password.js"></script>
	<script src="/assets/plugins/bootstrap-select/dist/js/bootstrap-select.min.js"></script>
	<script src="/assets/plugins/parsleyjs/dist/parsley.js"></script>
	<script>
		$(document).ready(function() {
			hakakses();
    		$('#pengguna_sandi').password()
		});

		function child(elmt) {
			if ($('#' + elmt).is(':checked')) {
				$('.' + elmt).prop('checked', true);
			}else{
				$('.' + elmt).prop('checked', false);
			}
		}

		function parent(elmt) {
			var i = 0;
		    $('.' + elmt).each(function() {
		    	if ($('.' + elmt).is(':checked')) {
		        	i++;
		    	}
		    });
		    if (i > 0) {
		    	$('#' + elmt).prop('checked', true);
		    }else{
		    	$('#' + elmt).prop('checked', false);
		    }
		}

		function hakakses() {
			if ($('#pengguna_level').val() == 1) {
				$('.hakakses input').prop('disabled', true);
				$('.hakakses input').prop('checked', true);
  				$(".hakakses").addClass("disabled");
			}else{
				$('.hakakses input').prop('disabled', false);
				if ('{{ $aksi }}' == 'tambah') {
					$('.hakakses input').prop('checked', false);
				}
  				$(".hakakses").removeClass("disabled");
			}
		}
	</script>
@endpush