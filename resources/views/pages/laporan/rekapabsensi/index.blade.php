@extends('pages.laporan.main')

@push('css')
	<link href="/assets/plugins/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet" />
@endpush

@section('page')
	<li class="breadcrumb-item active">Rekap Absensi</li>
@endsection

@section('header')
	<h1 class="page-header">Rekap Absensi</h1>
@endsection

@section('subcontent')
	<div class="panel panel-inverse" data-sortable-id="form-stuff-1">
		<!-- begin panel-heading -->
		<div class="panel-heading">
			<div class="row">
                <div class="col-md-12 col-lg-8 col-xl-8 col-xs-12">
                	<a href="#" class="btn btn-warning" onclick="cetak()">Cetak</a>&nbsp;
                </div>
                <div class="col-md-12 col-lg-4 col-xl-4 col-xs-12">
	            	<form id="frm-cari" action="/rekapabsensi" method="GET">
	            		@csrf
	                	<div class="form-inline pull-right">
	                		<div class="form-group">
								<select class="form-control selectpicker" onchange="submit()" data-live-search="true" id="ktr" name="ktr"  data-width="100%">
									@foreach($kantor as $ktr)
									<option value="{{ $ktr->kantor_id }}" 
										@if($ktr->kantor_id == $idkantor)
											selected
										@endif
									>{{ $ktr->kantor_nama }}</option>
									@endforeach
								</select>
		                    </div>&nbsp;
		                	<div class="input-group" id="default-daterange">
								<input type="text" name="tgl" class="form-control" value="{{ $tgl }}" placeholder="Pilih Tanggal Izin" readonly onchange="submit()" />
								<span class="input-group-append">
								<span class="input-group-text"><i class="fa fa-calendar"></i></span>
								</span>
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
							<th>Jumlah Hari Kerja</th>
							<th>Jumlah Terlambat</th>
							<th>Jumlah Kehadiran</th>
							<th>Sakit</th>
							<th>Izin</th>
							<th>Dispensasi</th>
							<th>Tugas Dinas</th>
							<th>Cuti</th>
							<th>Lain-lain</th>
						</tr>
					</thead>
					<tbody>
					    @foreach($absensi as $index => $absen)
					    <tr>
							@php
								$jmlHariKerja = 0;
								$jmlTerlambat = 0;
								$jmlMasuk = 0;
								$jmlSakit = 0;
								$jmlIzin = 0;
								$jmlDispensasi = 0;
								$jmlDinas = 0;
								$jmlCuti = 0;
								$jmlLain = 0;
							@endphp
					        <td>{{ $absen->pegawai->nip }}</td>
					        <td>{{ $absen->pegawai->nm_pegawai }}</td>
							<td>{{ $absen->hari }}</td>
							<td>{{ $absen->telat }}</td>
							<td>{{ $absen->masuk }}</td>
							<td>{{ $absen->sakit }}</td>
							<td>{{ $absen->izin }}</td>
							<td>{{ $absen->dispensasi }}</td>
							<td>{{ $absen->dinas }}</td>
							<td>{{ $absen->cuti }}</td>
							<td>{{ $absen->lain }}</td>
				      	</tr>
					    @endforeach
				    </tbody>
				</table>
			</div>
		</div>
	</div>
@endsection

@push('scripts')
	<script src="/assets/plugins/bootstrap-daterangepicker/moment.min.js"></script>	
	<script src="/assets/plugins/bootstrap-daterangepicker/daterangepicker.js"></script>
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

		$('#default-daterange').on('apply.daterangepicker', function(ev, picker) {
			$("#frm-cari").submit();
		});
	</script>
@endpush