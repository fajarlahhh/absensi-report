@extends('pages.laporan.main')

@push('css')
	<link href="/assets/plugins/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet" />
	<link href="/assets/plugins/bootstrap-select/dist/css/bootstrap-select.min.css" rel="stylesheet" />
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
                <div class="col-md-12 col-lg-8 col-xl-8 col-xs-12">
                	<a href="#" class="btn btn-warning" onclick="cetak()">Cetak</a>&nbsp;
                </div>
                <div class="col-md-12 col-lg-4 col-xl-4 col-xs-12">
	            	<form id="frm-cari" action="/rinciankehadiran" method="GET">
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
							<th rowspan="2">NIP</th>
							<th rowspan="2" width="300">Nama</th>
							@if($absensi[0][2])
							@for($i=0; $i < sizeof($absensi[0][2]); $i++)
							@php
								switch($absensi[0][2][$i]->absen_hari){
									case 'l':
										$bg = "bg-red-transparent-3";
										break;
									case 'k':
										$bg = "bg-yellow-transparent-3";
										break;
									default:
										$bg = "";
										break;
								}
							@endphp
					        <th colspan="3" class="{{ $bg }} text-center">{{ date('d M Y', strtotime($absensi[0][2][$i]->absen_tgl)) }}<br><small>{{ $absensi[0][2][$i]->absen_tgl_keterangan }}</small></th>
							@endfor
							@endif
						</tr>
						<tr>
							@if($absensi[0][2])
							@for($i=0; $i < sizeof($absensi[0][2]); $i++)
							@php
								switch($absensi[0][2][$i]->absen_hari){
									case 'l':
										$bg = "bg-red-transparent-3";
										break;
									case 'k':
										$bg = "bg-yellow-transparent-3";
										break;
									default:
										$bg = "";
										break;
								}
							@endphp
							<td class="text-center {{ $bg }}">Masuk</td>
							<td class="text-center {{ $bg }}">Telat</td>
							<td class="text-center {{ $bg }}">Izin</td>
							@endfor
							@endif
						</tr>
					</thead>
					<tbody>
					    @for($i = 0; $i < count($absensi); $i++)
					    <tr>
					        <td>{{ $absensi[$i][0] }}</td>
					        <td>{{ $absensi[$i][1] }}</td>
							@if($absensi[0][2])
							@for($j=0; $j < sizeof($absensi[$i][2]); $j++)
							@php
								switch($absensi[$i][2][$j]->absen_hari){
									case 'l':
										$bg = "bg-red-transparent-3";
										break;
									case 'k':
										$bg = "bg-yellow-transparent-3";
										break;
									default:
										$bg = "";
										break;
								}
							@endphp
					        <td class="text-center {{ $bg }}">{{ $absensi[$i][2][$j]->absen_masuk && !$absensi[$i][2][$j]->absen_izin? date('H:i:s', strtotime($absensi[$i][2][$j]->absen_masuk)): '' }}</td>
					        <td class="text-center {{ $bg }}">{{ $absensi[$i][2][$j]->absen_masuk_telat && $absensi[$i][2][$j]->absen_hari == "b"? date('H:i:s', strtotime($absensi[$i][2][$j]->absen_masuk_telat)): '' }}</td>
					        <td class="{{ $bg }}">{{ $absensi[$i][2][$j]->absen_izin? $absensi[$i][2][$j]->absen_izin.' '.$absensi[$i][2][$j]->absen_izin_keterangan: '' }}</td>
							@endfor
							@endif
				      	</tr>
					    @endfor
				    </tbody>
				</table>
			</div>
		</div>
	</div>
@endsection

@push('scripts')
	<script src="/assets/plugins/print-this/printThis.js"></script>
	<script src="/assets/plugins/bootstrap-daterangepicker/moment.min.js"></script>	
	<script src="/assets/plugins/bootstrap-daterangepicker/daterangepicker.js"></script>
	<script src="/assets/plugins/bootstrap-select/dist/js/bootstrap-select.min.js"></script>
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

		$('#default-daterange').on('apply.daterangepicker', function(ev, picker) {
			$("#frm-cari").submit();
		});
		
		function cetak(){
			$("#laporan").printThis({
				importCSS: true,
				importStyle: true,
				removeInline: false,
			 	copyTagClasses: true
			});
		}
	</script>
@endpush