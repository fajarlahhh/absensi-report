@extends('layouts.print')

@section('title', ' | Rincian Absensi')

@section('content')
	<style type="text/css">
		table {
		  border-collapse: collapse;
		}

		table, th, td {
		  border: 1px solid #e2e7eb;
		  padding: 3px;
		}
	</style>
	<div class="text-center">
		<h5>Rincian Absensi bagian {{ $bagian->first(function($q)use($bag){ return $q->kd_bagian == $bag; })->nm_bagian }} {{ $tanggal }}</h5>
	</div>
	<table width="100%">
        <thead>
			<tr>
				<th width="80">NIP</th>
				<th>Nama</th>
				<th width="90">Tanggal</th>
				<th width="200">Keterangan</th>
				<th width="80">Telat Masuk</th>
				<th width="80">Masuk</th>
				<th width="80">Keluar</th>
				<th width="80">Kembali</th>
				<th width="80">Pulang</th>
			</tr>
		</thead>
		<tbody>
			@foreach($absensi as $index => $absen)
		    <tr>
		        <td rowspan="{{ sizeof($absen->absen) + 1 }}">{{ $absen->pegawai->nip }}</td>
		        <td rowspan="{{ sizeof($absen->absen) + 1 }}">{{ $absen->pegawai->nm_pegawai }}</td>
	      	</tr>
	      	@foreach($absen->absen as $index => $abs)
				@php
					switch($abs->absen_hari){
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
		    <tr>
		        <td class="text-center {{ $bg }}">{{ date('d M Y', strtotime($abs->absen_tgl)) }}</td>
		        @if($abs->absen_hari != 'b')
		        <td class="{{ $bg }}"></td>
		        <td class="{{ $bg }}"></td>
		        <td class="{{ $bg }}"></td>
		        <td class="{{ $bg }}"></td>
		        <td class="{{ $bg }}"></td>
		        <td class="{{ $bg }}"></td>
		        @else
		        <td class="{{ $bg }}">{{ $abs->absen_izin? $abs->absen_izin.' '.$abs->absen_izin_keterangan: '' }}</td>
		        <td class="text-center {{ $bg }}">{{ $abs->absen_masuk_telat && $abs->absen_hari == "b"? date('H:i:s', strtotime($abs->absen_masuk_telat)): '' }}</td>
		        <td class="text-center {{ $bg }}">{{ $abs->absen_masuk && !$abs->absen_izin? date('H:i:s', strtotime($abs->absen_masuk)): '' }}</td>
		        <td class="text-center {{ $bg }}">{{ $abs->absen_istirahat && !$abs->absen_izin? date('H:i:s', strtotime($abs->absen_istirahat)): '' }}</td>
		        <td class="text-center {{ $bg }}">{{ $abs->absen_kembali && !$abs->absen_izin? date('H:i:s', strtotime($abs->absen_kembali)): '' }}</td>
		        <td class="text-center {{ $bg }}">{{ $abs->absen_pulang && !$abs->absen_izin? date('H:i:s', strtotime($abs->absen_pulang)): '' }}</td>
		        @endif
	      	</tr>
			@endforeach
		    @endforeach
	    </tbody>
	</table>
@endsection
