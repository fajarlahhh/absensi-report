@extends('layouts.print')

@section('title', ' | Rekap Absensi')

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
		<h5>Rekap Absensi bagian {{ $bagian->first(function($q)use($bag){ return $q->kd_bagian == $bag; })->nm_bagian }} {{ $tanggal }}</h5>
	</div>
	<table width="100%">
        <thead>
			<tr>
				<th>NIP</th>
				<th width="300">Nama</th>
				<th width="50">HK</th>
				<th width="50">TK</th>
				<th width="50">TL</th>
				<th width="50">I</th>
				<th width="50">S</th>
				<th width="50">C</th>
				<th width="50">TD</th>
				<th width="50">Jml. Kehadirah</th>
				<th>% Kehadiran</th>
			</tr>
		</thead>
		<tbody>
		    @foreach($absensi as $index => $absen)
		    <tr>
		        <td>{{ $absen->pegawai->nip }}</td>
		        <td>{{ $absen->pegawai->nm_pegawai }}</td>
				<td class="text-right">{{ sizeof($absen->absen) > 0? $absen->absen[0]->hari: '0' }}</td>
				<td class="text-right">{{ sizeof($absen->absen) > 0? $absen->absen[0]->tanpaketerangan: '0' }}</td>
				<td class="text-right">{{ sizeof($absen->absen) > 0? $absen->absen[0]->telat: '0' }}</td>
				<td class="text-right">{{ sizeof($absen->absen) > 0? $absen->absen[0]->izin: '0' }}</td>
				<td class="text-right">{{ sizeof($absen->absen) > 0? $absen->absen[0]->sakit: '0' }}</td>
				<td class="text-right">{{ sizeof($absen->absen) > 0? $absen->absen[0]->cuti: '0' }}</td>
				<td class="text-right">{{ sizeof($absen->absen) > 0? $absen->absen[0]->dinas: '0' }}</td>
				<td class="text-right">{{ sizeof($absen->absen) > 0? $absen->absen[0]->masuk: '0' }}</td>
				<td class="text-right">{{ sizeof($absen->absen) > 0? number_format($absen->absen[0]->masuk/$absen->absen[0]->hari * 100, 2): '0' }}</td>
	      	</tr>
		    @endforeach
	    </tbody>
	</table>
@endsection
