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
			<div class="table-responsive  bg-grey-transparent-5 p-20" >
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
					    <tr>
					    @for($i = 0; $i < count($absensi); $i++)
					    	@if($i == 0)
					        <td>{{ $absensi[$i] }}</td>
					        @elseif($i == 1)
					        <td>{{ $absensi[$i] }}</td>
					        @else
							<td class="text-center {{ (strpos($aturan->aturan_hari_libur, date('N', strtotime($tgl1. ' + '.($i-2).' days'))) !== false? 'bg-red-transparent-3': ($libur->find(date('Y-m-d', strtotime($tgl1. ' + '.($i-2).' days')))? 'bg-red-transparent-3': (strpos($absensi[$i], '-') !== false? (substr($absensi[$i], 0, 2) == 11 || substr($absensi[$i], 0, 2) == 12? 'bg-blue-transparent-3': (substr($absensi[$i], 0, 2) == 13 || substr($absensi[$i], 0, 2) == 14? 'bg-yellow-transparent-3': 'bg-grey-transparent-3')): (strlen($absensi[$i]) == 8? ((int)str_replace(':','',$absensi[$i]) <= (int)str_replace(':','',($khusus->filter(function($item) use ($tgl1, $i){ return $item->tgl_khusus_waktu == date('Y-m-d', strtotime($tgl1. ' + '.($i-2).' days')); })->first()? $aturan->aturan_masuk_khusus: $aturan->aturan_masuk))? 'bg-green-transparent-3': 'bg-orange-transparent-3'): '')) )) }}">{{ strpos($absensi[$i], '-') !== false? (substr($absensi[$i], 0, 2) == 11? 'Sakit ('.substr($absensi[$i], 5).')': (substr($absensi[$i], 0, 2) == 12? 'Izin ('.substr($absensi[$i], 5).')': (substr($absensi[$i], 0, 2) == 13? 'Dispensasi ('.substr($absensi[$i], 5).')': (substr($absensi[$i], 0, 2) == 14? 'Tugas Dinas ('.substr($absensi[$i], 5).')': (substr($absensi[$i], 0, 2) == 15? 'Cuti': 'Lain-lain'))))): $absensi[$i] }}</td>
							@endif
						@endfor
				      	</tr>
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