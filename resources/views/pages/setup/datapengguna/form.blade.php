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
				<div class="row">
					<div class="col-md-5">
						@if(!$data)
						<div class="form-group input-group-sm">
							<label class="control-label">Nama Pegawai</label>
							<select class="form-control selectpicker" data-live-search="true" name="pengguna_nip" data-style="btn-info" data-width="100%">
								@foreach($pegawai as $peg)
								<option value="{{ $peg->nip }}" 
									@if($data && $data->pegawai_nip == $peg->nip)
										selected
									@endif
								>{{ $peg->nm_pegawai }}</option>
								@endforeach
							</select>
						</div>
						<div class="form-group">
							<label class="control-label">Kata Sandi</label>
							<input data-toggle="password" class="form-control" type="password" name="pengguna_sandi" data-parsley-minlength="8" required />
						</div>
						@else
						<div class="form-group">
							<label class="control-label">NIP</label>
							<input class="form-control" type="text" name="pengguna_nip" value="{{ $data->pengguna_nip }}" required readonly />
						</div>
						<div class="form-group">
							<label class="control-label">Nama Pegawai</label>
							<input  class="form-control" type="text" name="pengguna_nama" value="{{ $data->pegawai->nm_pegawai }}" required readonly />
						</div>
						@endif
						<div class="form-group">
							<label class="control-label">No. Hp</label>
							<input class="form-control" type="text" name="pengguna_hp" value="{{ $data? $data->pengguna_hp: '' }}" required data-parsley-minlength="10" autocomplete="off" data-parsley-type="number" />
						</div>
						<div class="form-group">
							<label class="control-label">Level</label>
							<select class="form-control selectpicker" style="width : 100%" name="pengguna_level" id="pengguna_level" data-style="btn-info" onchange="hakakses()" data-width="100%">
								@foreach($level as $lvl)
								<option value="{{ $lvl->id }}" 
									@if($data && $data->getRoleNames()[0] == $lvl->name)
										selected
									@endif
								>{{ ucfirst($lvl->name) }}</option>
								@endforeach
							</select>
						</div>
					</div>
					<div class="col-md-7">
	                     <div class="panel panel-default">
	                        <!-- begin panel-heading -->
	                        <div class="panel-heading">
	                            <div class="panel-heading-btn">
	                                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
	                            </div>
	                            <h4 class="panel-title">Hak Akses</h4>
	                        </div>
	                        <!-- end panel-heading -->
	                        <!-- begin panel-body -->
	                        <div class="panel-body row">
	                            @foreach($izin as $akses)
								<div class="hakakses checkbox checkbox-css col-md-4 ">
									<input type="checkbox" id="cssCheckbox{{ $akses->name }}" name="izin[]" value="{{ $akses->name }}" 
									{{ ($data && $data->hasPermissionTo($akses->name)? 'checked': '') }} />
									<label for="cssCheckbox{{ $akses->name }}" class="p-l-5">{{ ucwords($akses->name) }}</label>
								</div>
	                            @endforeach
	                        </div>
	                        <!-- end panel-body -->
	                    </div>
					</div>
				</div>
			</div>
			<div class="panel-footer">
	            <input type="submit" value="Simpan" class="btn btn-sm btn-success"  />
	            <a href="{{ url()->previous() }}" class="btn btn-sm btn-danger">Batal</a>
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
		});

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