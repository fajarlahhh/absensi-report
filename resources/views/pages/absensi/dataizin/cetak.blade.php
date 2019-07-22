
@extends('layouts.empty')

@section('title', ' | Cetak Izin')

@section('content')
	<table class="table table-hover">
        <thead>
			<tr>
				<th>No.</th>
				<th>ID</th>
				<th>Waktu</th>
				<th>NIP</th>
				<th>Nama</th>
				<th>Alasan</th>
				<th>Keterangan</th>
			</tr>
		</thead>
		<tbody>
			@php
			$i=1;
			@endphp
		    @foreach ($data as $index => $absen)
		    <tr>
		        <td>{{ $i }}</td>
		        <td>{{ $absen->izin_id }}</td>
		        <td>{{ date('d M Y', strtotime($absen->izin_tgl)) }}</td>
		        <td>{{ $absen->pegawai->nip }}</td>
		        <td>{{ $absen->pegawai->nm_pegawai }}</td>
		        <td>
	        	@php
	        	switch($absen->izin_kode){
					case "11": echo "Sakit";
					break;
					case "12": echo "Izin";
					break;
					case "13": echo "Dispensasi";
					break;
					case "14": echo "Tugas Dinas";
					break;
					case "15": echo "Cuti";
					break;
					case "16": echo "Lain-lain";
					break;
	        	}
	        	@endphp
		        </td>
		        <td>{{ $absen->izin_keterangan }}</td>
				@php 
				$i++; 
				@endphp
	      	</tr>
		    @endforeach
	    </tbody>
	</table>
@endsection

@push('scripts')
	<script type="text/javascript">
		$(document).ready(function(){
			window.print();
		})
	</script>
@endpush