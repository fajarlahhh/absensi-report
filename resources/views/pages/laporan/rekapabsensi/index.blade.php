@extends('pages.laporan.main')

@push('css')
	<link href="/assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker.css" rel="stylesheet" />
	<link href="/assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.css" rel="stylesheet" />
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
                <div class="col-md-12 col-lg-3 col-xl-3 col-xs-12">
                	<a href="#" class="btn btn-warning" onclick="cetak()">Cetak</a>&nbsp;
                </div>
                <div class="col-md-12 col-lg-9 col-xl-9 col-xs-12">
	            	<form id="frm-cari" action="/rekapabsensi" method="GET">
	            		@csrf
	                	<div class="form-inline pull-right">
							<div class="form-group">
								<input type="text" readonly class="form-control cari" id="datepicker1" name="tgl1" placeholder="Tgl. Mulai" value="{{ date('d M Y', strtotime($tgl1)) }}"/>
							</div>
		                    &nbsp;s/d&nbsp;
							<div class="form-group">
								<input type="text" readonly class="form-control cari" id="datepicker2" name="tgl2" placeholder="Tgl. Akhir" value="{{ date('d M Y', strtotime($tgl2)) }}" data-date-end-date="0d"/>
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
					    @for($i = 0; $i < count($absensi); $i++)
					    <tr>
							@php
								$jmlTerlambat = 0;
								$jmlMasuk = 0;
								$jmlSakit = 0;
								$jmlIzin = 0;
								$jmlDispensasi = 0;
								$jmlDinas = 0;
								$jmlCuti = 0;
								$jmlLain = 0;
							@endphp
					        <td>{{ $absensi[$i][0] }}</td>
					        <td>{{ $absensi[$i][1] }}</td>
							@for($j=2; $j <= $diff+1; $j++)
							@php
								if(strpos($aturan->aturan_hari_libur, date('N', strtotime($tgl1. ' + '.($j-2).' days'))) == false){
									if(strpos($absensi[$i][$j], '-') !== false){
										switch(substr($absensi[$i][$j], 0, 2)){
											case '11' : $jmlSakit++; break;
											case '12' : $jmlIzin++; break;
											case '13' : $jmlDispensasi++; break;
											case '14' : $jmlDinas++; break;
											case '15' : $jmlCuti++; break;
											case '16' : $jmlLain++; break;
										}
									}else{
										if($absensi[$i][$j]){
											$jmlMasuk++;

											if((int)str_replace(':','',$absensi[$i][$j]) > (int)str_replace(':','',($hari == 1? $aturan->aturan_masuk: $aturan->aturan_masuk_khusus))){
												$jmlTerlambat++;
											}
										}
									}
								}
							@endphp
							@endfor
							<td>{{ $jmlTerlambat }}</td>
							<td>{{ $jmlMasuk }}</td>
							<td>{{ $jmlSakit }}</td>
							<td>{{ $jmlIzin }}</td>
							<td>{{ $jmlDispensasi }}</td>
							<td>{{ $jmlDinas }}</td>
							<td>{{ $jmlCuti }}</td>
							<td>{{ $jmlLain }}</td>
				      	</tr>
					    @endfor
				    </tbody>
				</table>
			</div>
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