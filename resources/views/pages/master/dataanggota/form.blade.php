@extends('pages.setup.main')

@push('css')
	<link href="/assets/plugins/password-indicator/css/password-indicator.css" rel="stylesheet" />
	<link href="/assets/plugins/bootstrap-select/dist/css/bootstrap-select.min.css" rel="stylesheet" />
	<link href="/assets/plugins/parsleyjs/src/parsley.css" rel="stylesheet" />
@endpush

@section('page')
	<li class="breadcrumb-item">Data Anggota</li>
	<li class="breadcrumb-item active">{{ $aksi }} Data</li>
@endsection

@section('header')
	<h1 class="page-header">Data Anggota <small>{{ $aksi }} Data</small></h1>
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
		<form action="/dataanggota/{{ strtolower($aksi) }}" method="post" data-parsley-validate="true" data-parsley-errors-messages-disabled="">
			@csrf
			<div class="panel-body">
				<input type="hidden" name="redirect" value="{{ url()->previous() }}">
				@if($aksi == 'Tambah')
				<div class="form-group input-group-sm">
					<label class="control-label">Nama Pegawai</label>
					<select class="form-control selectpicker" data-live-search="true" name="pegawai_id" data-style="btn-info" data-width="100%">
						@foreach($pegawai as $peg)
						<option value="{{ $peg->id }}" >{{ $peg->nm_pegawai }}</option>
						@endforeach
					</select>
				</div>
				@else
				<input type="hidden" name="pegawai_id" value="{{ $data->pegawai_id }}" required /> <div class="form-group">
					<label class="control-label">NIP</label>
					<input class="form-control" type="text" name="anggota_nip"  value="{{ $data->anggota_nip }}" required readonly />
				</div>
				<div class="form-group">
					<label class="control-label">Nama</label>
					<input class="form-control" type="text" value="{{ $data->pegawai->nm_pegawai }}" required readonly />
				</div>
				<div class="form-group">
					<label class="control-label">Unit</label>
					<input class="form-control" type="text" value="{{ $data->pegawai->unit->nm_unit }}" required readonly />
				</div>
				<div class="form-group">
					<label class="control-label">Jabatan</label>
					<input class="form-control" type="text" value="{{ $data->pegawai->jabatan->nm_jabatan }}" required readonly />
				</div>
				<div class="form-group">
					<label class="control-label">Bagian</label>
					<input class="form-control" type="text" value="{{ $data->pegawai->bagian->nm_bagian }}" required readonly />
				</div>
				<div class="form-group">
					<label class="control-label">Seksi</label>
					<input class="form-control" type="text" value="{{ $data->pegawai->seksi->nm_seksi }}" required readonly />
				</div>
				@endif
				<div class="form-group">
					<label class="control-label">Hak Akses</label>
					<select class="form-control selectpicker" style="width : 100%" name="anggota_hak_akses" id="anggota_hak_akses" data-style="btn-info" data-width="100%">
						<option value="14" {{ $data && $data->anggota_hak_akses == 14? "selected": "" }}>
							Super Admin
						</option>
						<option value="9" {{ $data && $data->anggota_hak_akses == 0? "selected": "" }}>
							User Biasa
						</option>
					</select>
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
	<script src="/assets/plugins/bootstrap-select/dist/js/bootstrap-select.min.js"></script>
	<script src="/assets/plugins/parsleyjs/dist/parsley.js"></script>
@endpush