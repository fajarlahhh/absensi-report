@extends('pages.master.main')

@push('css')
	<link href="/assets/plugins/bootstrap-select/dist/css/bootstrap-select.min.css" rel="stylesheet" />
	<link href="/assets/plugins/parsleyjs/src/parsley.css" rel="stylesheet" />
@endpush

@section('page')
	<li class="breadcrumb-item">Shift Karyawan</li>
	<li class="breadcrumb-item active">Tambah Data</li>
@endsection

@section('header')
	<h1 class="page-header">Shift Karyawan <small>Tambah Data</small></h1>
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
		<form action="/shiftkaryawan/tambah" method="post" data-parsley-validate="true" data-parsley-errors-messages-disabled="">
			@csrf
			<div class="panel-body">
				<input type="hidden" name="redirect" value="{{ url()->previous() }}">
				<div class="form-group input-group-sm">
					<label class="control-label">Nama Anggota</label>
					<select class="form-control selectpicker" data-live-search="true" name="pegawai_id" id="pegawai_id" data-style="btn-info" data-width="100%" onchange="getnip()">
						@foreach($anggota as $peg)
						<option value="{{ $peg->pegawai_id }}">{{ $peg->pegawai->nm_pegawai }}</option>
						@endforeach
					</select>
				</div>
				<div class="form-group input-group-sm">
					<label class="control-label">Shift</label>
					<select class="form-control selectpicker" data-live-search="true" name="shift_id" id="shift_id" data-style="btn-info" data-width="100%" onchange="getnip()">
						@foreach($shift as $shf)
						<option value="{{ $shf->shift_id }}">{{ $shf->shift_nama }}</option>
						@endforeach
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