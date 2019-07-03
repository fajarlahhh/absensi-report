@extends('pages.laporan.main')

@push('css')
	<link href="/assets/plugins/parsleyjs/src/parsley.css" rel="stylesheet" />
	<link href="/assets/plugins/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet" />
@endpush

@section('page')
	<li class="breadcrumb-item active">Posting Absensi</li>
@endsection

@section('header')
	<h1 class="page-header">Posting Absensi</h1>
@endsection

@section('subcontent')
	<div class="panel panel-inverse" data-sortable-id="form-stuff-1">
		<!-- begin panel-heading -->
		<form action="/postingabsensi" method="post" data-parsley-validate="true" data-parsley-errors-messages-disabled="">
			@csrf
			<div class="panel-body">
				<div class="form-group">
					<label class="control-label">Tanggal Kehadiran</label>
					<div class="input-group" id="default-daterange">
						<input type="text" name="tanggal" class="form-control" value="{{ Session::get('tgl')? Session::get('tgl'): date('d F Y').' - '.date('d F Y') }}" placeholder="Pilih Tanggal Izin" readonly />
						<span class="input-group-append">
						<span class="input-group-text"><i class="fa fa-calendar"></i></span>
						</span>
					</div>
				</div>
			</div>
			<div class="panel-footer">
	            <input type="submit" value="Posting" class="btn btn-sm btn-success"  />
	        </div>
		</form>
	</div>
@endsection

@push('scripts')
	<script src="/assets/plugins/parsleyjs/dist/parsley.js"></script>
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