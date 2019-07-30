@extends('pages.absensi.main')

@push('css')
	<link href="/assets/plugins/bootstrap-select/dist/css/bootstrap-select.min.css" rel="stylesheet" />
	<link href="/assets/plugins/parsleyjs/src/parsley.css" rel="stylesheet" />
	<link href="/assets/plugins/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css" rel="stylesheet" />
@endpush

@section('page')
	<li class="breadcrumb-item">Data Kehadiran</li>
	<li class="breadcrumb-item active">Download</li>
@endsection

@section('header')
	<h1 class="page-header">Data Kehadiran <small>Tambah Data</small></h1>
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
		<form action="/datakehadiran/tambah" method="post" data-parsley-validate="true" data-parsley-errors-messages-disabled="">
			@csrf
			<div class="panel-body">
				<div class="form-group">
				<div class="form-group">
					<label class="control-label">Anggota</label>
					<select class="form-control selectpicker" data-live-search="true" name="pegawai_id" data-style="btn-info" data-width="100%">
						@foreach($anggota as $angg)
						<option value="{{ $angg->pegawai_id }}" >{{ ucwords(strtolower($angg->pegawai->nm_pegawai)) }}</option>
						@endforeach
					</select>
				</div>
				<div class="form-group ">
					<label class="control-label">Waktu</label>
					<div class="input-group date" >
						<input type="text" class="form-control"  name="kehadiran_tgl" id="datetimepicker1" />
						<div class="input-group-addon">
							<i class="fa fa-calendar"></i>
						</div>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label">Kode</label>
					<select class="form-control selectpicker" data-live-search="true" name="kehadiran_kode" data-style="btn-info" data-width="100%">
						<option value="0" >Masuk</option>
						<option value="1" >Pulang</option>
						<option value="2" >Istirahat</option>
						<option value="3" >Kembali</option>
						<option value="4" >Masuk Lembur</option>
						<option value="5" >Pulang Lembur</option>
					</select>
				</div>
				<div class="form-group">
					<label class="control-label">Keterangan</label>
					<input class="form-control" type="text" name="kehadiran_keterangan" required data-parsley-maxlength="250" autocomplete="off" />
				</div>
			</div>
			<div class="panel-footer">
	            <input type="submit" value="Simpan" class="btn btn-sm btn-success"  />
	            <a href="/datakehadiran" class="btn btn-sm btn-danger">Batal</a>
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
	<script src="/assets/plugins/parsleyjs/dist/parsley.js"></script>
	<script src="/assets/plugins/bootstrap-select/dist/js/bootstrap-select.min.js"></script>
	<script src="/assets/plugins/bootstrap-daterangepicker/moment.min.js"></script>
	<script src="/assets/plugins/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js"></script>
	<script>
		$('#datetimepicker1').datetimepicker({
			defaultDate: "{{ date('Y-m-d H:i:s') }}",
			format: 'DD MMMM YYYY HH:mm:ss',
		});
	</script>
@endpush