@extends('layouts.empty', ['paceTop' => true, 'bodyExtraClass' => 'bg-white'])

@section('title', ' | Absensi Karyawan')
@section('content')

	<div class="login-cover">
	    <div class="login-cover-image" style="background-image: url(../assets/img/login-bg/login-bg.jpg)" data-id="login-cover-image"></div>
	    <div class="login-cover-bg"></div>
	</div>
	<div class="login" data-pageload-addclass="animated fadeIn">
		<!-- begin brand -->
		<div class="login-header">
			<div class="brand">
	            <img src="/assets/img/logo/favicon.png" height="30"> <span class="text-white">{{ config("app.name") }}</span>
				<small style="color: rgba(255,255,255,0.5)">PDAM Giri Menang</small>
			</div>
			<div class="icon">
				<i class="fa fa-calendar text-white"></i>
			</div>
		</div>
		<div class=" bg-grey-transparent-5 p-20">
			<div class="table-responsive bg-grey-transparent-5 p-20" >	
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
			<br>
				
			<label class="text-white">Keterangan warna:</label>
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
		</div><br>
		<center><a href="/login" class="btn btn-primary">Login</a>&nbsp;</center>
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