@extends('pages.laporan.main')

@push('css')
	<link href="/assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker.css" rel="stylesheet" />
	<link href="/assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.css" rel="stylesheet" />
@endpush

@section('page')
	<li class="breadcrumb-item active">Rincian Kehadiran</li>
@endsection

@section('header')
	<h1 class="page-header">Rincian Kehadiran</h1>
@endsection

@section('subcontent')
	<div class="panel panel-inverse" data-sortable-id="form-stuff-1">
		<!-- begin panel-heading -->
		<div class="panel-heading">
			<div class="row">
                <div class="col-md-12 col-lg-3 col-xl-3 col-xs-12">
                	<a href="#" class="btn btn-warning" onclick="cetak()">Cetak</a>&nbsp;
                </div>
                <div class="col-md-12 col-lg-9 col-xl-9 col-xs-12">
	            	<form id="frm-cari" action="/rinciankehadiran" method="GET">
	            		@csrf
	                	<div class="form-inline pull-right">
							<div class="form-group">
								<input type="text" readonly class="form-control cari" id="datepicker1" name="tgl1" placeholder="Tgl. Mulai" value="{{ date('d F Y', strtotime($tgl1)) }}"/>
							</div>
		                    &nbsp;s/d&nbsp;
							<div class="form-group">
								<input type="text" readonly class="form-control cari" id="datepicker2" name="tgl2" placeholder="Tgl. Akhir" value="{{ date('d F Y', strtotime($tgl2)) }}" data-date-end-date="0d"/>
		                    </div>
	                	</div>
					</form>
                </div>
			</div>
		</div>
		<div class="panel-body">
			<div class="table-responsive" >
				<table class="table table-bordered" id="laporan">
                    <thead>
						<tr>
							<th>NIP</th>
							<th width="300">Nama</th>
							@for($i=0; $i < $diff; $i++)
							<th width="220" class="{{ strpos($aturan->aturan_hari_libur, date('N', strtotime($tgl1. ' + '.$i.' days')))  !== false?'bg-red-transparent-3': ($libur->find(date('Y-m-d', strtotime($tgl1. ' + '.$i.' days')))? 'bg-red-transparent-3': '' ) }}">{{ date('d M Y', strtotime($tgl1. ' + '.$i.' days')) }}</th>
							@endfor
						</tr>
					</thead>
					<tbody>
					    @for($i = 0; $i < count($absensi); $i++)
					    <tr>
					        <td>{{ $absensi[$i][0] }}</td>
					        <td>{{ $absensi[$i][1] }}</td>
							@for($j=2; $j <= $diff+1; $j++)
							<td class="text-center {{ (strpos($aturan->aturan_hari_libur, date('N', strtotime($tgl1. ' + '.($j-2).' days'))) !== false? 'bg-red-transparent-3': ($libur->find(date('Y-m-d', strtotime($tgl1. ' + '.($j-2).' days')))? 'bg-red-transparent-3': (strpos($absensi[$i][$j], '-') !== false? (substr($absensi[$i][$j], 0, 2) == 11 || substr($absensi[$i][$j], 0, 2) == 12? 'bg-blue-transparent-3': (substr($absensi[$i][$j], 0, 2) == 13 || substr($absensi[$i][$j], 0, 2) == 14? 'bg-yellow-transparent-3': 'bg-grey-transparent-3')): (strlen($absensi[$i][$j]) == 8? ((int)str_replace(':','',$absensi[$i][$j]) <= (int)str_replace(':','',($khusus->filter(function($item) use ($tgl1, $j){ return $item->tgl_khusus_waktu == date('Y-m-d', strtotime($tgl1. ' + '.($j-2).' days')); })->first()? $aturan->aturan_masuk_khusus: $aturan->aturan_masuk))? 'bg-green-transparent-3': 'bg-orange-transparent-3'): '')) )) }}">{{ strpos($absensi[$i][$j], '-') !== false? (substr($absensi[$i][$j], 0, 2) == 11? 'Sakit ('.substr($absensi[$i][$j], 5).')': (substr($absensi[$i][$j], 0, 2) == 12? 'Izin ('.substr($absensi[$i][$j], 5).')': (substr($absensi[$i][$j], 0, 2) == 13? 'Dispensasi ('.substr($absensi[$i][$j], 5).')': (substr($absensi[$i][$j], 0, 2) == 14? 'Tugas Dinas ('.substr($absensi[$i][$j], 5).')': (substr($absensi[$i][$j], 0, 2) == 15? 'Cuti': 'Lain-lain'))))): $absensi[$i][$j] }}</td>
							@endfor
				      	</tr>
					    @endfor
				    </tbody>
				</table>
			</div>
		</div>
		<div class="panel-footer">
			<label>Keterangan warna:</label>
			<table class="table">
				<tr>
					<td class="bg-red-transparent-3" width="50">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
					<td>Hari Libur</td>
				</tr>
				<tr>
					<td class="bg-green-transparent-3" width="50">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
					<td>Masuk Tepat Waktu</td>
				</tr>
				<tr>
					<td class="bg-orange-transparent-3" width="50">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
					<td>Masuk Terlambat</td>
				</tr>
			</table>
		</div>
	</div>
@endsection

@push('scripts')
	<script src="/assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
	<script src="/assets/plugins/print-this/printThis.js"></script>
	<script>
		function cetak(){
			$("#laporan").printThis({
				importCSS: true,
				importStyle: true,
				removeInline: false,
			 	copyTagClasses: true
			});
		}

		$(".cari").change(function() {
		     $("#frm-cari").submit();
		});

		$('#datepicker1').datepicker({
			todayHighlight: true,
			format: 'dd MM yyyy',
			autoclose: true
		});

		$('#datepicker2').datepicker({
			todayHighlight: true,
			format: 'dd MM yyyy',
			autoclose: true
		});
	</script>
@endpush