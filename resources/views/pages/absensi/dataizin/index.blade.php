@extends('pages.absensi.main')

@push('css')
	<link href="/assets/plugins/bootstrap-select/dist/css/bootstrap-select.min.css" rel="stylesheet" />
	<link href="/assets/plugins/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet" />
@endpush

@section('page')
	<li class="breadcrumb-item active">Data Izin</li>
@endsection

@section('header')
	<h1 class="page-header">Data Izin</h1>
@endsection

@section('subcontent')
	<div class="panel panel-inverse" data-sortable-id="form-stuff-1">
		<!-- begin panel-heading -->
		<div class="panel-heading">
			<div class="row">
                <div class="col-md-12 col-lg-8 col-xl-8 col-xs-12">
                	@role('user|administrator')
                    <div class="form-inline">
                        <a href="/dataizin/tambah" class="btn btn-primary">Tambah</a>&nbsp;
	                	<form action="/dataizin/cetak" method="GET" target="_blank">
							<input type="hidden" name="tgl" value="{{ $tgl }}" />
                        	<input type="submit" class="btn btn-warning" value="Cetak"> 
						</form>
                    </div>
                    @endrole
                </div>
                <div class="col-md-12 col-lg-4 col-xl-4 col-xs-12">
                	<form id="frm-cari" action="/dataizin" method="GET">
                		@csrf
						<div class="input-group" id="default-daterange">
							<input type="text" name="tgl" class="form-control" value="{{ $tgl }}" placeholder="Pilih Tanggal Izin" readonly onchange="submit()" />
							<span class="input-group-append">
							<span class="input-group-text"><i class="fa fa-calendar"></i></span>
							</span>
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
							<th>ID</th>
							<th>Waktu</th>
							<th>NIP</th>
							<th>Nama</th>
							<th>Alasan</th>
							<th>Keterangan</th>
							<th></th>
						</tr>
					</thead>
					<tbody>
					    @foreach ($data as $index => $absen)
					    <tr>
					        <td>{{ (($data->currentPage() - 1 ) * $data->perPage() ) + $loop->iteration }}</td>
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
					        <td class="text-right"><a href='javascript:;' onclick="hapus({{ $absen->izin_id }})" id='btn-del' class='btn btn-danger btn-xs'><i class='fa fa-trash-alt'></i></a></td>
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
	<script src="/assets/plugins/bootstrap-daterangepicker/moment.min.js"></script>	
	<script src="/assets/plugins/bootstrap-daterangepicker/daterangepicker.js"></script>
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
		
		function hapus(id) {
			swal({
				title: 'Apakah anda yakin?',
				text: 'Anda akan menghapus kehadiran ' + id + '',
				icon: 'warning',
				buttons: {
					cancel: {
						text: 'Batal',
						value: null,
						visible: true,
						className: 'btn btn-default',
						closeModal: true,
					},
					confirm: {
						text: 'Ya',
						value: true,
						visible: true,
						className: 'btn btn-danger',
						closeModal: true
					}
				}
			}).then(function(isConfirm) {
		      	if (isConfirm) {
	          		window.location.href = "/dataizin/hapus/" + id;
		      	}
		    });
		}
	</script>
@endpush