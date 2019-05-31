@extends('pages.absensi.main')

@push('css')
	<link href="/assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker.css" rel="stylesheet" />
	<link href="/assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.css" rel="stylesheet" />
	<link href="/assets/plugins/bootstrap-select/dist/css/bootstrap-select.min.css" rel="stylesheet" />
@endpush

@section('page')
	<li class="breadcrumb-item active">Data Kehadiran</li>
@endsection

@section('header')
	<h1 class="page-header">Data Kehadiran</h1>
@endsection

@section('subcontent')
	<div class="panel panel-inverse" data-sortable-id="form-stuff-1">
		<!-- begin panel-heading -->
		<div class="panel-heading">
			<div class="row">
                <div class="col-md-12 col-lg-3 col-xl-3 col-xs-12">
                	@role('user|administrator')
                    <div class="form-inline">
		                <a href="/dataanggota/tambah" class="btn btn-primary">Tambah</a>&nbsp;
                        <a href="/datakehadiran/download" class="btn btn-success">Download</a>
                    </div>
                    @endrole
                </div>
                <div class="col-md-12 col-lg-9 col-xl-9 col-xs-12">
                	<form id="frm-cari" action="/datakehadiran" method="GET">
                		@csrf
	                	<div class="form-inline pull-right">
							<div class="form-group">
								<select class="form-control selectpicker cari" data-live-search="true" name="pegawai" data-width="100%">
									<option value="00">Semua Pegawai</option>
									@foreach($anggota as $angg)
									<option value="{{ $angg->pegawai_id }}" 
										@if($pegawai == $angg->pegawai_id)
											selected
										@endif
									>{{ ucwords(strtolower($angg->pegawai->nm_pegawai)) }}</option>
									@endforeach
								</select>
							</div>&nbsp;
							<div class="form-group">
								<input type="text" readonly class="form-control cari" id="datepicker1" name="tgl1" placeholder="Tgl. Mulai" value="{{ date('1 M Y', strtotime($tgl2)) }}"/>
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
			<div class="table-responsive">
				<table class="table table-hover">
                    <thead>
						<tr>
							<th>No.</th>
							<th>Waktu</th>
							<th>NIP</th>
							<th>Nama</th>
							<th>Kode</th>
							<th>Keterangan</th>
						</tr>
					</thead>
					<tbody>
					    @foreach ($data as $index => $absen)
					    <tr>
					        <td>{{ (($data->currentPage() - 1 ) * $data->perPage() ) + $loop->iteration }}</td>
					        <td>{{ date('d M Y h:m:s', strtotime($absen->kehadiran_tgl)) }}</td>
					        <td>{{ $absen->anggota->anggota_nip }}</td>
					        <td>{{ $absen->pegawai->nm_pegawai }}</td>
					        <td>
				        	@php
				        	switch($absen->kehadiran_kode){
								case "0": echo "Masuk";
								break;
								case "1": echo "Pulang";
								break;
								case "2": echo "Istirahat";
								break;
								case "3": echo "Kembali";
								break;
								case "4": echo "Masuk Lembur";
								break;
								case "5": echo "Pulang Lembur";
								break;
				        	}
				        	@endphp
					        </td>
					        <td>{{ $absen->kehadiran_keterangan }}</td>
				      	</tr>
					    @endforeach
				    </tbody>
				</table>
			</div>
		</div>
		<div class="panel-footer form-inline">
            <div class="col-md-6 col-lg-10 col-xl-10 col-xs-12">
				{{ $data->links() }}
			</div>
			<div class="col-md-6 col-lg-2 col-xl-2 col-xs-12">
				<label class="pull-right">Jumlah Data : {{ $data->total() }}</label>
			</div>
		</div>
	</div>
@endsection

@push('scripts')
	<script src="/assets/plugins/bootstrap-select/dist/js/bootstrap-select.min.js"></script>
	<script src="/assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
	<script>
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