@extends('pages.setup.main')

@push('css')
	<link href="/assets/plugins/bootstrap-select/dist/css/bootstrap-select.min.css" rel="stylesheet" />
	<link href="/assets/plugins/parsleyjs/src/parsley.css" rel="stylesheet" />
	<link href="/assets/plugins/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet" />
@endpush

@section('page')
	<li class="breadcrumb-item">Hari Khusus</li>
	<li class="breadcrumb-item active">Tambah Data</li>
@endsection

@section('header')
	<h1 class="page-header">Hari Khusus <small>Tambah Data</small></h1>
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
		<form action="/harikhusus/tambah" method="post" data-parsley-validate="true" data-parsley-errors-messages-disabled="">
			@csrf
			<div class="panel-body">
				<div class="form-group">
					<label class="control-label">Tanggal</label>
					<div class="input-group" id="default-daterange">
						<input type="text" name="tgl_khusus_waktu" class="form-control" value="{{ date('d F Y').' - '.date('d F Y') }}" readonly />
						<span class="input-group-append">
						<span class="input-group-text"><i class="fa fa-calendar"></i></span>
						</span>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label">Keterangan</label>
					<input class="form-control" type="text" name="tgl_khusus_keterangan" required  autocomplete="off" />
				</div>
			</div>
			<div class="panel-footer">
	            <input type="submit" value="Simpan" class="btn btn-sm btn-success"  />
	            <a href="/harikhusus" class="btn btn-sm btn-danger">Batal</a>
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