@extends('pages.setup.main')

@push('css')
	<link href="/assets/plugins/bootstrap-select/dist/css/bootstrap-select.min.css" rel="stylesheet" />
	<link href="/assets/plugins/parsleyjs/src/parsley.css" rel="stylesheet" />
	<link href="/assets/plugins/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet" />
@endpush

@section('page')
	<li class="breadcrumb-item">Data Izin</li>
	<li class="breadcrumb-item active">Download</li>
@endsection

@section('header')
	<h1 class="page-header">Data Izin <small>Tambah Data</small></h1>
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
		<form action="/dataizin/tambah" method="post" data-parsley-validate="true" data-parsley-errors-messages-disabled="">
			@csrf
			<div class="panel-body">
				<div class="form-group">
					<label class="control-label">Anggota</label>
					<select class="form-control selectpicker" data-live-search="true" name="pegawai_id" data-style="btn-info" data-width="100%">
						@foreach($anggota as $angg)
						<option value="{{ $angg->pegawai_id }}" >{{ ucwords(strtolower($angg->pegawai->nm_pegawai)) }}</option>
						@endforeach
					</select>
				</div>
				<div class="form-group">
					<label class="control-label">Tanggal Izin</label>
					<div class="input-group" id="default-daterange">
						<input type="text" name="izin_tgl" class="form-control" value="{{ date('d F Y').' - '.date('d F Y') }}" placeholder="Pilih Tanggal Izin" readonly />
						<span class="input-group-append">
						<span class="input-group-text"><i class="fa fa-calendar"></i></span>
						</span>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label">Alasan</label>
					<select class="form-control selectpicker" data-live-search="true" name="izin_kode" data-style="btn-info" data-width="100%">
						<option value="11" >Sakit</option>
						<option value="12" >Izin</option>
						<option value="13" >Dispensasi</option>
						<option value="14" >Tugas Dinas</option>
						<option value="15" >Cuti</option>
					</select>
				</div>
				<div class="form-group">
					<label class="control-label">Keterangan</label>
					<input class="form-control" type="text" name="izin_keterangan" required data-parsley-maxlength="250" autocomplete="off" />
				</div>
			</div>
			<div class="panel-footer">
	            <input type="submit" value="Simpan" class="btn btn-sm btn-success"  />
	            <a href="/dataizin" class="btn btn-sm btn-danger">Batal</a>
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
	<script src="/assets/plugins/bootstrap-daterangepicker/daterangepicker.js"></script>
	<script>
		
	$('#default-daterange').daterangepicker({
		opens: 'right',
		format: 'DD MMMM YYYY',
		separator: ' s/d ',
		startDate: moment('{{ date('Y-m-d') }}'),
		endDate: moment('{{ date('Y-m-d') }}'),
    	dateLimit: { days: 30 },
	}, function (start, end) {
		$('#default-daterange input').val(start.format('DD MMMM YYYY') + ' - ' + end.format('DD MMMM YYYY'));
	});
	</script>
@endpush